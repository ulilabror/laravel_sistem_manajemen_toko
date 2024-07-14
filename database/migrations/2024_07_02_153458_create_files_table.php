<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('related_id');
            $table->string('related_type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
};
