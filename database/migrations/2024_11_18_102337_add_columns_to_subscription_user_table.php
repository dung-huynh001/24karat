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
        Schema::table('subscription_users', function (Blueprint $table) {
            $table->bigInteger('pref_id')->nullable()->after('zip');
            $table->string('address1', 100)->nullable()->after('pref_id');
            $table->string('address2', 100)->nullable()->after('address1');
            $table->string('tel', 20)->nullable()->after('address2');
            $table->string('manager_mail', 100)->nullable()->after('tel');
            $table->tinyInteger('is_display_tempo_page')->default(0)->after('manager_mail');
            $table->tinyInteger('is_display_member_page')->default(0)->after('is_display_tempo_page');
            $table->tinyInteger('delete_flag')->default(0)->after('is_display_member_page');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_users', function (Blueprint $table) {
            $table->dropColumn([
                'pref_id',
                'address1',
                'address2',
                'tel',
                'manage_mail',
                'is_display_tempo_page',
                'is_display_member_page',
                'delete_flag'
            ]);
        });
    }
};
