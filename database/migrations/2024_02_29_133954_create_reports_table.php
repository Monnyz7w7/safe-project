<?php

use App\Models\User;
use App\Models\DiscordServer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('discord_username');
            $table->string('discord_user_id')->index();
            $table->string('discord_user_global_name')->nullable();
            $table->string('discord_user_avatar_url')->nullable();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(DiscordServer::class)->constrained()->cascadeOnDelete();
            $table->longText('details');
            $table->string('status')->index();
            $table->timestamp('action_at')->nullable();
            $table->foreignId('action_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
