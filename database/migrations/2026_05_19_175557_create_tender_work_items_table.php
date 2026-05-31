<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_work_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tender_document_id')->constrained()->cascadeOnDelete();

            $table->string('schedule_name')->nullable();   // Schedule A, Schedule B, etc.
            $table->string('item_number')->nullable();     // 1, 2, 3, B, etc.
            $table->string('item_code')->nullable();       // 135011, 061022, etc.
            $table->text('description')->nullable();
            $table->decimal('quantity', 15, 3)->nullable();
            $table->decimal('item_quantity', 15, 3)->nullable();
            $table->string('unit')->nullable();            // TRM, RM, Each, etc.
            $table->decimal('escl_rate', 10, 4)->nullable();
            $table->decimal('advised_value', 15, 2)->nullable();
            $table->decimal('bid_rate_unit_rate', 10, 4)->nullable();
            $table->decimal('bid_amount', 15, 2)->nullable();
            $table->boolean('is_sub_item')->default(false);
            $table->unsignedBigInteger('parent_item_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_work_items');
    }
};
