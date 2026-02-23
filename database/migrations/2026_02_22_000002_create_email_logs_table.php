<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email');
            $table->string('type')->default('marketing'); // marketing, welcome, promo
            $table->string('subject');
            $table->string('status')->default('sent'); // sent, failed, bounced
            $table->string('resend_id')->nullable();
            $table->timestamps();

            $table->index(['email', 'type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
