<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('micro_skill_submissions', function (Blueprint $table) {
            $table->unique(['intern_id', 'title']);
        });
    }

    public function down(): void
    {
        Schema::table('micro_skill_submissions', function (Blueprint $table) {
            $table->dropUnique('micro_skill_submissions_intern_id_title_unique');
        });
    }
};
