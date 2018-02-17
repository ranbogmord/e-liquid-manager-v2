<?php

namespace App\Console\Commands;

use App\Comment;
use App\Flavour;
use App\Liquid;
use App\User;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Console\Command;
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

    /**
     * Execute the console command.
     *
     * ID is mapped by ["field"]["$oid"], usually _id
     * Dates are mapped by ["fieldName"]["$date"]
     *
     * @return mixed
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error('Invalid file');
        }

        $json = file_get_contents($filePath);
        $data = json_decode($json, true);

        if (!empty($data['users'])) {
            //DB::table('users')->delete();

            foreach ($data['users'] as $oldUser) {
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
            foreach ($data['vendors'] as $oldVendor) {
                $vendor = new Vendor();
                $vendor->old_id = $oldVendor['_id']['$oid'];
                $vendor->name = $oldVendor['name'];
                $vendor->abbr = $oldVendor['abbr'] ?? "";
                $vendor->created_at = new Carbon($oldVendor['createdAt']['$date'] ?? null);
                $vendor->updated_at = new Carbon($oldVendor['updatedAt']['$date'] ?? null);

                if ($user = User::whereOldId($oldVendor['addedBy']['$oid'])->first()) {
                    $vendor->author_id = $user->id;
                }

                $vendor->save();
            }
        }

        if (!empty($data['flavours'])) {
            foreach ($data['flavours'] as $oldFlavour) {
                $flavour = new Flavour();
                $flavour->old_id = $oldFlavour['_id']['$oid'];
                $flavour->name = $oldFlavour['name'];
                $flavour->is_vg = $oldFlavour['isVg'];
                $flavour->base_percent = $oldFlavour['basePercent'];

                if ($vendor = Vendor::whereOldId($oldFlavour['vendor']['$oid'])->first()) {
                    $flavour->vendor_id = $vendor->id;
                }

                if ($user = User::whereOldId($oldFlavour['addedBy']['$oid'])->first()) {
                    $flavour->author_id = $user->id;
                }

                $flavour->created_at = new Carbon($oldFlavour["createdAt"]['$date'] ?? null);
                $flavour->updated_at = new Carbon($oldFlavour["updatedAt"]['$date'] ?? null);

                $flavour->save();
            }
        }

        if (!empty($data['liquids'])) {
            foreach ($data['liquids'] as $oldLiquid) {
                $liquid = new Liquid();
                $liquid->old_id = $oldLiquid['_id']['$oid'];

                $liquid->name = $oldLiquid['name'];
                $liquid->base_nic_strength = $oldLiquid['base']['nicStrength'];
                $liquid->target_pg_percentage = $oldLiquid['target']['pgPercent'];
                $liquid->target_vg_percentage = $oldLiquid['target']['vgPercent'];
                $liquid->target_nic_strength = $oldLiquid['target']['nicStrength'];

                if ($user = User::whereOldId($oldLiquid['author']['$oid'])->first()) {
                    $liquid->author_id = $user->id;
                }

                $liquid->created_at = new Carbon($oldLiquid['createdAt']['$date'] ?? null);
                $liquid->updated_at = new Carbon($oldLiquid['updatedAt']['$date'] ?? null);

                $liquid->save();

                $flavours = [];
                foreach ($oldLiquid['flavours'] as $oldFlavourRef) {
                    if ($flavour = Flavour::whereOldId($oldFlavourRef['flavour']['_id'])->first()) {
                        $flavours[$flavour->id] = [
                            'percent' => $oldFlavourRef['perc']
                        ];
                    }
                }

                $liquid->flavours()->sync($flavours);
            }
        }

        if (!empty($data['comments'])) {
            foreach ($data['comments'] as $oldComment) {
                $comment = new Comment();
                $comment->old_id = $oldComment['_id']['$oid'];

                $comment->comment = $oldComment['comment'];

                if ($user = User::whereOldId($oldComment['author']['$oid'])->first()) {
                    $comment->author_id = $user->id;
                }

                if ($liquid = Liquid::whereOldId($oldComment['liquid']['$oid'])->first()) {
                    $comment->liquid_id = $liquid->id;
                }

                $comment->created_at = $oldComment['createdAt']['$date'];
                $comment->updated_at = $oldComment['updatedAt']['$date'];

                $comment->save();
            }
        }
    }
}
