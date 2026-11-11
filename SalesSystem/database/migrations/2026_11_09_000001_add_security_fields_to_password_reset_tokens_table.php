<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('purpose', 30)->default('registration')->after('token');
            $table->timestamp('expires_at')->nullable()->after('created_at');
            $table->unsignedTinyInteger('attempts')->default(0)->after('expires_at');
            $table->timestamp('last_sent_at')->nullable()->after('attempts');
            $table->index(['user_id', 'purpose']);
        });
    }

    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'purpose']);
            $table->dropColumn(['purpose', 'expires_at', 'attempts', 'last_sent_at']);
        });
    }
};
