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
        Schema::create('member_forms', function (Blueprint $table) {
            $table->bigIncrements('member_form_id')->primary();
            $table->unsignedBigInteger('member_field_type_id');
            $table->string('form_name', 50)->nullable();
            $table->timestamps();

            $table->foreign('member_field_type_id')
                ->references('id')
                ->on('member_field_types')
                ->onDelete('cascade');
        });

        DB::statement('ALTER TABLE member_forms WITH SYSTEM VERSIONING');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_forms');
        DB::statement('ALTER TABLE member_forms DROP SYSTEM VERSIONING');
        Schema::table('member_forms', function (Blueprint $table) {
            $table->dropForeign(['member_field_type_id']);
        });

    }
};
