<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $table): void {
            $table->id();
            $table->string('tender_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->foreignId('category_id')->constrained('tender_categories')->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->decimal('budget', 15, 2);
            $table->date('submission_deadline');
            $table->enum('status', ['draft', 'published', 'closed', 'awarded'])->default('draft');
            $table->unsignedBigInteger('awarded_bid_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
