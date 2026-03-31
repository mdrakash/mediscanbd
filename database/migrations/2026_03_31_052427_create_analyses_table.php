<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('medicines')->nullable();
            $table->longText('test_summary')->nullable();
            $table->json('parameters')->nullable();
            $table->json('abnormal_findings')->nullable();
            $table->text('advice')->nullable();
            $table->text('next_steps')->nullable();
            $table->text('lifestyle_tips')->nullable();
            $table->string('report_type')->nullable();
            $table->json('tests_advised')->nullable();
            $table->longText('raw_response')->nullable();
            $table->enum('language', ['bn', 'en'])->default('bn');
            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyses');
    }
};
