<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('connected_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('provider');
            $table->string('provider_id');
            $table->string('name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->text('avatar_path')->nullable();
            $table->string('token', 1000);
            $table->string('secret')->nullable();
            $table->string('refresh_token', 1000)->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'id']);
            $table->index(['provider', 'provider_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connected_accounts');
    }
};
