<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->bigIncrements('admin_user_id')->primary(); // BIGINT(20) auto_increment
            $table->unsignedBigInteger('subcription_user_id')->nullable(); // BIGINT(20) allow null
            $table->tinyInteger('is_butterflydance_user')->nullable(); // TINYINT(4) allow null
            $table->string('name', 100)->nullable(); // VARCHAR(100) allow null
            $table->string('email', 100); // VARCHAR(100) 
            $table->string('password', 100); // VARCHAR(100)
            $table->string('avatar', 255)->nullable(); // VARCHAR(255) allow null
            $table->string('temporary_url_token', 255)->nullable(); // VARCHAR(255) allow null
            $table->timestamp('email_verified_at')->nullable(); // TIMESTAMP allow null
            $table->string('remember_token', 100)->nullable(); // VARCHAR(100) allow null
            $table->tinyInteger('delete_flag')->default(0); // TINYINT(4)
            $table->timestamps(); // created_at and updated_at as TIMESTAMP, allow null
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
