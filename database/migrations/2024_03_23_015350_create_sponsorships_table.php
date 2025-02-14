<?php

use App\Models\User;
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
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longtext('description');
            $table->string('link');
            $table->string('image_url')->nullable();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->string('type');
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
        Schema::dropIfExists('sponsorships');
    }
};
