<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subscription_users', function (Blueprint $table) {
            // Đổi kiểu dữ liệu cột pref_id thành unsignedBigInteger
            $table->unsignedBigInteger('pref_id')->nullable()->change();

            // Thêm khóa ngoại
            $table->foreign('pref_id')->references('id')->on('prefectures')
                ->onDelete('cascade'); // Hoặc bạn có thể chọn hành động khác
        });
    }

    /**
     * Đảo ngược các migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_users', function (Blueprint $table) {
            // Xóa khóa ngoại và hoàn nguyên cột
            $table->dropForeign(['pref_id']);
            $table->dropColumn('pref_id');
        });
    }
};
