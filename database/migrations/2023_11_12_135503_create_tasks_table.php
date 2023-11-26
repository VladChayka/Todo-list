<?php

use App\Models\Task;
use App\Models\User;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('status');
            $table->integer('priority');
            $table->timestamp('created_at');
            $table->timestamp('completed_at')->nullable();
            $table->fullText(['title', 'description']);
            $table->index('status');
            $table->index('priority');
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Task::class)->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
