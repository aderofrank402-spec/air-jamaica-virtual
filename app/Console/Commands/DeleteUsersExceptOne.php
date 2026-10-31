<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUsersExceptOne extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-except {email : The email of the user to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all users except the one with the specified email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keepEmail = $this->argument('email');
        
        // Check if the user with the specified email exists
        $keepUser = User::where('email', $keepEmail)->first();
        
        if (!$keepUser) {
            $this->error("User with email '{$keepEmail}' not found!");
            return 1;
        }
        
        // Count users to be deleted
        $usersToDelete = User::where('email', '!=', $keepEmail)->count();
        
        if ($usersToDelete === 0) {
            $this->info("No users to delete. Only the user with email '{$keepEmail}' exists.");
            return 0;
        }
        
        $this->info("Found {$usersToDelete} users to delete.");
        $this->info("Keeping user: {$keepUser->name} ({$keepUser->email})");
        
        // Ask for confirmation
        if (!$this->confirm("Are you sure you want to delete {$usersToDelete} users? This action cannot be undone.")) {
            $this->info('Operation cancelled.');
            return 0;
        }
        
        // Perform the permanent deletion
        $this->info('Permanently deleting users from database...');
        
        try {
            // Get users to delete and force delete them permanently
            $usersToDeleteCollection = User::where('email', '!=', $keepEmail)->get();
            $deletedCount = 0;
            
            foreach ($usersToDeleteCollection as $user) {
                $user->forceDelete(); // Permanently delete from database
                $deletedCount++;
            }
            
            $this->info("Successfully permanently deleted {$deletedCount} users from database.");
            $this->info("Kept user: {$keepUser->name} ({$keepUser->email})");
            
        } catch (\Exception $e) {
            $this->error("Error deleting users: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
