<?php

namespace App\Console\Commands;

use App\Comment;
use App\Flavour;
use App\Liquid;
use App\User;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-data {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports data from V1';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function progressLog($str, $progress, $total, $step = 100)
    {
        $progress += 1;
        if ($progress % $step === 0) {
            $this->info(
                sprintf($str, $progress, $total)
            );
        }
    }

    public function runImport($data)
    {
        if (!empty($data['users'])) {
            $this->info("Importing users");
            //DB::table('users')->delete();

            foreach ($data['users'] as $k => $oldUser) {
                $this->progressLog("User %d / %d", $k, count($data['users']), 10);

                $user = new User();
                $user->old_id = $oldUser['_id']['$oid'];
                $user->username = $oldUser['username'];
                $user->email = $oldUser['email'];
                $user->role = $oldUser['role'] ?? "user";
                $user->password = $oldUser["password"];
                $user->created_at = new Carbon($oldUser['createdAt']['$date'] ?? null);
                $user->updated_at = new Carbon($oldUser['updatedAt']['$date'] ?? null);

                if (User::whereEmail($user->email)->first()) {
                    $user->email = Uuid::uuid4()->toString() . '@example.com';
                }

                $user->save();
            }
        }

        if (!empty($data['vendors'])) {
            $this->info("Importing vendors");
            foreach ($data['vendors'] as $k => $oldVendor) {
                $this->progressLog("Vendor %d / %d", $k, count($data['vendors']), 10);

                $vendor = new Vendor();
                $vendor->old_id = $oldVendor['_id']['$oid'];
                $vendor->name = $oldVendor['name'];
                $vendor->abbr = $oldVendor['abbr'] ?? "";
                $vendor->created_at = new Carbon($oldVendor['createdAt']['$date'] ?? null);
                $vendor->updated_at = new Carbon($oldVendor['updatedAt']['$date'] ?? null);

                $uid = $oldVendor['addedBy']['$oid'] ?? null;
                if ($uid && $user = User::whereOldId($uid)->first()) {
                    $vendor->author_id = $user->id;
                }

                $vendor->save();
            }
        }

        if (!empty($data['flavours'])) {
            $this->info("Importing flavours");
            foreach ($data['flavours'] as $k => $oldFlavour) {
                $this->progressLog("Flavour %d / %d", $k, count($data['flavours']), 10);

                $flavour = new Flavour();
                $flavour->old_id = $oldFlavour['_id']['$oid'];
                $flavour->name = $oldFlavour['name'];
                $flavour->is_vg = $oldFlavour['isVg'];
                $flavour->base_percent = $oldFlavour['basePercent'];

                $vid = $oldFlavour['vendor']['$oid'] ?? null;
                if ($vid && $vendor = Vendor::whereOldId($vid)->first()) {
                    $flavour->vendor_id = $vendor->id;
                }

                $uid = $oldFlavour['addedBy']['$oid'] ?? null;
                if ($uid && $user = User::whereOldId($uid)->first()) {
                    $flavour->author_id = $user->id;
                }

                $flavour->created_at = new Carbon($oldFlavour["createdAt"]['$date'] ?? null);
                $flavour->updated_at = new Carbon($oldFlavour["updatedAt"]['$date'] ?? null);

                $flavour->save();
            }
        }

        if (!empty($data['liquids'])) {
            $this->info("Importing liquids");
            foreach ($data['liquids'] as $k => $oldLiquid) {
                $this->progressLog("Liquid %d / %d", $k, count($data['liquids']), 10);

                $liquid = new Liquid();
                $liquid->old_id = $oldLiquid['_id']['$oid'];

                $liquid->name = $oldLiquid['name'];
                $liquid->base_nic_strength = $oldLiquid['base']['nicStrength'];
                $liquid->target_pg_percentage = $oldLiquid['target']['pgPercent'];
                $liquid->target_vg_percentage = $oldLiquid['target']['vgPercent'];
                $liquid->target_nic_strength = $oldLiquid['target']['nicStrength'];

                $uid = $oldLiquid['author']['$oid'] ?? null;
                if ($uid && $user = User::whereOldId($uid)->first()) {
                    $liquid->author_id = $user->id;
                }

                $liquid->created_at = new Carbon($oldLiquid['createdAt']['$date'] ?? null);
                $liquid->updated_at = new Carbon($oldLiquid['updatedAt']['$date'] ?? null);

                $liquid->save();

                $flavours = [];
                foreach ($oldLiquid['flavours'] as $oldFlavourRef) {
                    $fid = $oldFlavourRef['flavour']['$oid'] ?? null;
                    if ($fid && $flavour = Flavour::whereOldId($fid)->first()) {
                        $flavours[$flavour->id] = [
                            'percent' => $oldFlavourRef['perc']
                        ];
                    }
                }

                $liquid->flavours()->sync($flavours);
            }
        }

        if (!empty($data['comments'])) {
            $this->info("Importing comments");
            foreach ($data['comments'] as $k => $oldComment) {
                $this->progressLog("Comment %d / %d", $k, count($data['comments']), 10);

                $comment = new Comment();
                $comment->old_id = $oldComment['_id']['$oid'];

                $comment->comment = $oldComment['comment'];

                if ($user = User::whereOldId($oldComment['author']['$oid'])->first()) {
                    $comment->author_id = $user->id;
                }

                if ($liquid = Liquid::withoutGlobalScopes()->whereOldId($oldComment['liquid']['$oid'])->first()) {
                    $comment->liquid_id = $liquid->id;
                }

                $comment->created_at = new Carbon($oldComment['createdAt']['$date'] ?? null);
                $comment->updated_at = new Carbon($oldComment['updatedAt']['$date'] ?? null);

                $comment->save();
            }
        }

        if (!empty($data['liquids'])) {
            $this->info("Mapping next versions");

            foreach ($data['liquids'] as $liquid) {
                if (!empty($liquid['next_version']['$oid'])) {
                    $this->info(
                        sprintf("Liquid %s has a new version %s", $liquid['_id']['$oid'], $liquid['next_version']['$oid'])
                    );

                    /** @var Liquid $nextVersion */
                    $nextVersion = Liquid::withoutGlobalScopes()->whereOldId($liquid['next_version']['$oid'])->first();

                    /** @var Liquid $oldVersion */
                    $oldVersion = Liquid::withoutGlobalScopes()->whereOldId($liquid['_id']['$oid'])->first();
                    if ($nextVersion && $oldVersion) {
                        $oldVersion->next_version_id = $nextVersion->id;
                        $oldVersion->save();
                    } else {
                        if (!$oldVersion) {
                            $this->error("Unable to find old version");
                        }
                        if (!$nextVersion) {
                            $this->error("Unable to find next version");
                        }

                    }
                }
            }
        }
    }

    /**
     * Execute the console command.
     *
     * ID is mapped by ["field"]["$oid"], usually _id
     * Dates are mapped by ["fieldName"]["$date"]
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error('Invalid file');
            exit(1);
        }

        $json = file_get_contents($filePath);
        $data = json_decode($json, true);

        if (json_last_error()) {
            $this->error(json_last_error_msg());
            exit(1);
        }

        /** @var DatabaseManager $db */
        $db = app('db');
        $db->beginTransaction();

        try {
            $this->runImport($data);
        } catch (\Exception $ex) {
            $db->rollBack();
            throw $ex;
        }

        $db->commit();
    }
}
