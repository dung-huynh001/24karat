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
        Schema::create('member_field_types', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('subscription_user_id')->nullable();
            $table->string('field_type_name', 100);
            $table->timestamps();

            $table->foreign('subscription_user_id')
                ->references('subscription_user_id')
                ->on('subscription_users')
                ->onDelete('set null');
        });

        DB::statement('ALTER TABLE member_field_types WITH SYSTEM VERSIONING');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_field_types');
        DB::statement('ALTER TABLE member_field_types DROP SYSTEM VERSIONING');
        Schema::table('member_field_types', function (Blueprint $table) {
            $table->dropForeign(['subscription_user_id']);
        });
    }
};
