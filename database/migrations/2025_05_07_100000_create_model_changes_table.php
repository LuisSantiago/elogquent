<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function getConnection(): ?string
    {
        return config('elogquent.database_connection');
    }

    public function up(): void
    {
        $schema = Schema::connection($this->getConnection());

        $schema->create('elogquent_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->morphs('model');
            $table->tinyText('column');
            $table->string('value');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        $schema = Schema::connection($this->getConnection());

        $schema->dropIfExists('elogquent_entries');
    }
};
