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
        Schema::create('member_tables', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('member_field_format_trn_id')->nullable();
            $table->unsignedBigInteger('subscription_user_id');
            $table->unsignedBigInteger('member_field_types_id')->nullable();
            $table->unsignedBigInteger('member_fields_id');
            $table->tinyInteger('is_required')->nullable();
            $table->integer('order_by');
            $table->longText('field_value')->nullable();
            $table->bigInteger('used_by')->nullable();
            $table->text('csv_input_rule')->nullable();
            $table->longText('field_validation')->nullable();
            $table->longText('field_config')->nullable();
            $table->timestamps();

            $table->foreign('member_field_format_trn_id')
                ->references('member_field_format_trn_id')
                ->on('member_field_format_trns')
                ->onDelete('set null');

            $table->foreign('subscription_user_id')
                ->references('subscription_user_id')
                ->on('subscription_users')
                ->onDelete('cascade');

            $table->foreign('member_field_types_id')
                ->references('id')
                ->on('member_field_types')
                ->onDelete('set null');

            $table->foreign('member_fields_id')
                ->references('id')
                ->on('member_fields')
                ->onDelete('cascade');
        });

        DB::statement('ALTER TABLE member_tables WITH SYSTEM VERSIONING');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_tables');
        DB::statement('ALTER TABLE member_tables DROP SYSTEM VERSIONING');
        Schema::table('member_tables', function (Blueprint $table) {
            $table->dropForeign([
                'member_field_format_trn_id',
                'subscription_user_id',
                'member_field_types_id',
                'member_fields_id'
            ]);
        });
    }
};
