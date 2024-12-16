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
        Schema::create('member_field_format_trns', function (Blueprint $table) {
            $table->bigIncrements('member_field_format_trn_id')->unsigned()->primary();
            $table->unsignedBigInteger('member_field_format_master_id');
            $table->string('member_field_format_trn_name', 100)->nullable();
            $table->string('member_field_format_trn_value', 50);
            $table->tinyInteger('delete_flag')->default(0);
            $table->timestamps();

            // Set foreign key with member_field_format_master table
            $table->foreign('member_field_format_master_id')
                  ->references('member_field_format_master_id')
                  ->on('member_field_format_masters')
                  ->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_field_format_trns');
        Schema::table('member_field_format_trns', function (Blueprint $table) {
            $table->dropForeign(['member_field_format_master_id']);
        });
    }
};
