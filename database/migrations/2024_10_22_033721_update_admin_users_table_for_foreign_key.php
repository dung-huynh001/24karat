<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdminUsersTableForForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->renameColumn('subcription_user_id', 'subscription_user_id');

            $table->foreign('subscription_user_id')
                  ->references('subscription_user_id')
                  ->on('subscription_users')
                  ->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropForeign(['subscription_user_id']);

            $table->renameColumn('subcription_user_id', 'subscription_user_id');
        });
    }
}
