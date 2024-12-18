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
        try {
            Schema::create('subscription_user_forms', function (Blueprint $table) {
                $table->bigIncrements('subscription_user_form_id')->primary();
                $table->unsignedBigInteger('subscription_user_id');
                $table->unsignedBigInteger('member_form_id');
                $table->string('title', 100)->nullable();
                $table->string('background_color', 50)->nullable();
                $table->string('background_image', 255)->nullable();
                $table->tinyInteger('is_display')->default(0);
                $table->timestamps();
    
                $table->foreign('subscription_user_id')
                    ->references('subscription_user_id')
                    ->on('subscription_users')
                    ->onDelete('cascade');
    
                $table->foreign('member_form_id')
                    ->references('member_form_id')
                    ->on('member_forms')
                    ->onDelete('cascade');
            });
    
            DB::statement('ALTER TABLE subscription_user_forms WITH SYSTEM VERSIONING');
        } catch(Exception $e) {
            if (Schema::hasTable('subscription_user_forms')) {
                Schema::dropIfExists('subscription_user_forms');
            }

            // Rethrow the exception to ensure migration fails
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_user_forms');
        DB::statement('ALTER TABLE subscription_user_forms WITH SYSTEM VERSIONING');
        Schema::table('subscription_user_forms', function (Blueprint $table) {
            $table->dropForeign(['subscription_user_id', 'member_form_id']);
        });
    }
};
