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
        Schema::create('member_field_format_trns', function (Blueprint $table) {
            $table->bigIncrements('member_field_format_trn_id')->primary();
            $table->unsignedBigInteger('member_field_format_master_id');
            $table->string('member_field_format_trn_name', 100)->nullable();
            $table->string('member_field_format_trn_value', 50);
            $table->timestamps();

            // Set foreign key with member_field_format_master table
            $table->foreign('member_field_format_master_id')
                  ->references('member_field_format_master_id')
                  ->on('member_field_format_masters')
                  ->onDelete('cascade'); 
        });

        DB::statement('ALTER TABLE member_field_format_trns WITH SYSTEM VERSIONING');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_field_format_trns');
        DB::statement('ALTER TABLE member_field_format_trns DROP SYSTEM VERSIONING');
        Schema::table('member_field_format_trns', function (Blueprint $table) {
            $table->dropForeign(['member_field_format_master_id']);
        });
    }
};
