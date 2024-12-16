<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('member_field_format_masters', function (Blueprint $table) {
            $table->bigIncrements('member_field_format_master_id')->unsigned()->primary();
            $table->string('member_field_format_master_name', 100);
            $table->string('mode_display_option', 100)->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE member_field_format_masters ADD SYSTEM VERSIONING');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_field_format_masters');
        DB::statement('ALTER TABLE member_field_format_masters DROP SYSTEM VERSIONING');
    }
};
