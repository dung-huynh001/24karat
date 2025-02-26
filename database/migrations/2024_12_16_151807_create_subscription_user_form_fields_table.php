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
        Schema::create('subscription_user_form_fields', function (Blueprint $table) {
            $table->bigIncrements('subscription_user_form_field_id')->primary();
            $table->unsignedBigInteger('subscription_user_form_id');
            $table->unsignedBigInteger('member_field_id');
            $table->longText('field_config')->nullable();
            $table->tinyInteger('is_display')->default(0);
            $table->timestamps();

            // Set foreign key for subscription_user_form_fields table
            $table->foreign('subscription_user_form_id')
                ->references('subscription_user_form_id')
                ->on('subscription_user_forms')
                ->onDelete('cascade');

            // Set foreign key for subscription_user_form_fields table
            $table->foreign('member_field_id')
                ->references('id')
                ->on('member_fields')
                ->onDelete('cascade');
        });

        DB::statement('ALTER TABLE subscription_user_form_fields WITH SYSTEM VERSIONING');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_user_form_fields');
        DB::statement('ALTER TABLE subscription_user_form_fields DROP SYSTEM VERSIONING');
        Schema::table('subscription_user_form_fields', function (Blueprint $table) {
            $table->dropForeign(['subscription_user_form_id', 'member_field_id']);
        });
    }
};
