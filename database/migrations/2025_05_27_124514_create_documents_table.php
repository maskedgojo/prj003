<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id('doc_id');
            $table->string('file_type')->nullable();
            $table->string('table_name')->nullable();
            $table->unsignedBigInteger('ref_id')->nullable()->comment('table_id');
            $table->foreign('ref_id')->references('relations_dtl_id')->on('relations')->onDelete('cascade');
            $table->string('uploaded_file_desc')->nullable();
            $table->string('random_file_name')->nullable();
            $table->string('url')->nullable();
            $table->string('publication')->nullable();
            $table->string('user_file_name')->nullable();
            $table->integer('precedence')->default(0);
            $table->tinyInteger('is_disabled')->default(0);
            $table->Integer('uploaded_by_user')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
