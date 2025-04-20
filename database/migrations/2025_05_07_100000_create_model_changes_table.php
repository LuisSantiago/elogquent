<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('model_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->morphs('model');
            $table->json('changes');
            $table->timestamp('created_at')->useCurrent();
        });
    }
};
