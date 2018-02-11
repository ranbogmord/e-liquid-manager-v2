<?php

namespace App\Console\Commands;

use App\User;
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
                $user->password = Uuid::uuid4()->toString();
                $user->created_at = new Carbon($oldUser['createdAt']['$date'] ?? null);
                $user->updated_at = new Carbon($oldUser['updatedAt']['$date'] ?? null);

                $user->save();
            }
        }
    }
}
