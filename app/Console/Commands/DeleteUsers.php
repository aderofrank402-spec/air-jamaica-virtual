<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete {emails*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete users by email addresses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $emails = $this->argument('emails');
        
        foreach ($emails as $email) {
            $this->info("Searching for user with email: {$email}");
            $user = DB::table('users')->where('email', $email)->first();
            
            if ($user) {
                $this->info("User found: ID {$user->id}, Name: {$user->name}, Email: {$user->email}");
                $deleted = DB::table('users')->where('email', $email)->delete();
                
                if ($deleted) {
                    $this->info("Successfully deleted user with email: {$email}");
                } else {
                    $this->error("Failed to delete user with email: {$email}");
                }
            } else {
                $this->warn("No user found with email: {$email}");
            }
        }
    }
}