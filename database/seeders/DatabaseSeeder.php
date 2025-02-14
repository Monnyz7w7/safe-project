<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enum\Role;
use App\Enum\Status;
use App\Models\User;
use App\Models\Report;
use App\Models\Partnership;
use App\Models\Sponsorship;
use App\Models\DiscordServer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@monnycraft.test',
            'role' => Role::ADMIN,
            'action_at' => now(),
            'status' => Status::APPROVED,
        ]);

        User::factory()
            ->create([
            'name' => 'Developer',
            'email' => 'dev@monnycraft.test',
            'role' => Role::DEVELOPER,
            'action_at' => now(),
            'status' => Status::APPROVED,
            'action_by' => 1
        ]);

        User::factory()
            ->create([
            'name' => 'Promoter',
            'email' => 'promoter@monnycraft.test',
            'role' => Role::PROMOTER,
            'action_at' => now(),
            'status' => Status::APPROVED,
            'action_by' => 1
        ]);

        User::factory()
            ->create([
            'name' => 'Validator',
            'email' => 'validator@monnycraft.test',
            'role' => Role::VALIDATOR,
            'action_at' => now(),
            'status' => Status::APPROVED,
            'action_by' => 1
        ]);

        User::factory()
            ->create([
            'name' => 'Insider',
            'email' => 'insider@monnycraft.test',
            'role' => Role::INSIDER,
            'action_at' => now(),
            'status' => Status::APPROVED,
            'action_by' => 1
        ]);

        User::factory()
            ->create([
            'name' => 'Watcher',
            'email' => 'watcher@monnycraft.test',
            'role' => Role::WATCHER,
            'action_at' => now(),
            'status' => Status::APPROVED,
            'action_by' => 1
        ]);

        $approvedUsers = User::factory(5)
            ->approved()
            ->has(DiscordServer::factory())
            ->has(Partnership::factory())
            ->has(Sponsorship::factory(rand(1, 3)))
            ->create();

        foreach ($approvedUsers as $user) {
            Report::factory(rand(1, 5))->create([
                'user_id' => $user->id,
                'discord_server_id' => $user->discordServer->id
            ]);
        }

        $pendingUsers = User::factory(10)
            ->has(DiscordServer::factory())
            ->create();
    }
}
