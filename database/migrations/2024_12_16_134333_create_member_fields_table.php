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
        Schema::create('member_fields', function (Blueprint $table) {
            $$table->bigIncrements('id')->unsigned()->primary();
            $table->unsignedBigInteger('member_field_format_trn_id')->nullable();
            $table->string('field_name', 191);
            $table->longText('field_value')->nullable();
            $table->longText('field_validation')->nullable();
            $table->longText('field_config')->nullable();
            $table->bigInteger('used_by')->nullable();
            $table->text('csv_input_rule')->nullable();
            $table->tinyInteger('delete_flag')->default(0);
            $table->timestamps();

            // Set foreign key with member_field_format_trns table
            $table->foreign('member_field_format_trn_id')
                  ->references('member_field_format_trn_id')
                  ->on('member_field_format_trns')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_fields');
        Schema::table('member_fields', function (Blueprint $table) {
            $table->dropForeign(['member_field_format_trn_id']);
        });
    }
};
