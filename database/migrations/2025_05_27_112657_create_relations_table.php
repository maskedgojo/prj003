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
        Schema::create('relations', function (Blueprint $table) {
            $table->id('relations_dtl_id');
            $table->Integer('relations_master_id')->nullable();
            $table->string('title')->nullable();
            $table->string('page_url')->nullable();
            $table->text('description')->nullable();
            $table->enum('is_file_uploaded', ['Y', 'N'])->default('N')->nullable();
            $table->integer('fileupload_count')->default(0);
            $table->tinyInteger('is_disabled')->default(0);
            $table->integer('order_column')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relations');
    }
};
