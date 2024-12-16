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
        Schema::create('subscription_user_form_fields', function (Blueprint $table) {
            $table->bigIncrements('subscription_user_form_field_id')->unsigned()->primary();
            $table->unsignedBigInteger('subscription_user_form_id');
            $table->unsignedBigInteger('member_field_id');
            $table->longText('field_config')->nullable();
            $table->tinyInteger('is_display')->default(0);
            $table->tinyInteger('delete_flag')->default(0);
            $table->timestamps();

            // Set foreign key for subscription_user_form_fields table
            $table->foreign('subscription_user_form_id')
                ->references('subscription_user_form_id')
                ->on('subscription_user_forms')
                ->onDelete('set null');

            // Set foreign key for subscription_user_form_fields table
            $table->foreign('member_field_id')
                ->references('member_field_id')
                ->on('member_fields')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_user_form_fields');
        Schema::table('subscription_user_form_fields', function (Blueprint $table) {
            $table->dropForeign(['subscription_user_form_id', 'member_field_id']);
        });
    }
};
