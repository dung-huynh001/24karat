<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrefecturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete existing data
        // DB::table('prefectures')->truncate();

        // Seed new data
        DB::table('prefectures')->insert([
            ['id' => 1, 'code' => '01', 'name' => '北海道', 'delete_flag' => 0],
            ['id' => 2, 'code' => '02', 'name' => '青森県', 'delete_flag' => 0],
            ['id' => 3, 'code' => '03', 'name' => '岩手県', 'delete_flag' => 0],
            ['id' => 4, 'code' => '04', 'name' => '宮城県', 'delete_flag' => 0],
            ['id' => 5, 'code' => '05', 'name' => '秋田県', 'delete_flag' => 0],
            ['id' => 6, 'code' => '06', 'name' => '山形県', 'delete_flag' => 0],
            ['id' => 7, 'code' => '07', 'name' => '福島県', 'delete_flag' => 0],
            ['id' => 8, 'code' => '08', 'name' => '茨城県', 'delete_flag' => 0],
            ['id' => 9, 'code' => '09', 'name' => '栃木県', 'delete_flag' => 0],
            ['id' => 10, 'code' => '10', 'name' => '群馬県', 'delete_flag' => 0],
            ['id' => 11, 'code' => '11', 'name' => '埼玉県', 'delete_flag' => 0],
            ['id' => 12, 'code' => '12', 'name' => '千葉県', 'delete_flag' => 0],
            ['id' => 13, 'code' => '13', 'name' => '東京都', 'delete_flag' => 0],
            ['id' => 14, 'code' => '14', 'name' => '神奈川県', 'delete_flag' => 0],
            ['id' => 15, 'code' => '15', 'name' => '新潟県', 'delete_flag' => 0],
            ['id' => 16, 'code' => '16', 'name' => '富山県', 'delete_flag' => 0],
            ['id' => 17, 'code' => '17', 'name' => '石川県', 'delete_flag' => 0],
            ['id' => 18, 'code' => '18', 'name' => '福井県', 'delete_flag' => 0],
            ['id' => 19, 'code' => '19', 'name' => '山梨県', 'delete_flag' => 0],
            ['id' => 20, 'code' => '20', 'name' => '長野県', 'delete_flag' => 0],
            ['id' => 21, 'code' => '21', 'name' => '岐阜県', 'delete_flag' => 0],
            ['id' => 22, 'code' => '22', 'name' => '静岡県', 'delete_flag' => 0],
            ['id' => 23, 'code' => '23', 'name' => '愛知県', 'delete_flag' => 0],
            ['id' => 24, 'code' => '24', 'name' => '三重県', 'delete_flag' => 0],
            ['id' => 25, 'code' => '25', 'name' => '滋賀県', 'delete_flag' => 0],
            ['id' => 26, 'code' => '26', 'name' => '京都府', 'delete_flag' => 0],
            ['id' => 27, 'code' => '27', 'name' => '大阪府', 'delete_flag' => 0],
            ['id' => 28, 'code' => '28', 'name' => '兵庫県', 'delete_flag' => 0],
            ['id' => 29, 'code' => '29', 'name' => '奈良県', 'delete_flag' => 0],
            ['id' => 30, 'code' => '30', 'name' => '和歌山県', 'delete_flag' => 0],
            ['id' => 31, 'code' => '31', 'name' => '鳥取県', 'delete_flag' => 0],
            ['id' => 32, 'code' => '32', 'name' => '島根県', 'delete_flag' => 0],
            ['id' => 33, 'code' => '33', 'name' => '岡山県', 'delete_flag' => 0],
            ['id' => 34, 'code' => '34', 'name' => '広島県', 'delete_flag' => 0],
            ['id' => 35, 'code' => '35', 'name' => '山口県', 'delete_flag' => 0],
            ['id' => 36, 'code' => '36', 'name' => '徳島県', 'delete_flag' => 0],
            ['id' => 37, 'code' => '37', 'name' => '香川県', 'delete_flag' => 0],
            ['id' => 38, 'code' => '38', 'name' => '愛媛県', 'delete_flag' => 0],
            ['id' => 39, 'code' => '39', 'name' => '高知県', 'delete_flag' => 0],
            ['id' => 40, 'code' => '40', 'name' => '福岡県', 'delete_flag' => 0],
            ['id' => 41, 'code' => '41', 'name' => '佐賀県', 'delete_flag' => 0],
            ['id' => 42, 'code' => '42', 'name' => '長崎県', 'delete_flag' => 0],
            ['id' => 43, 'code' => '43', 'name' => '熊本県', 'delete_flag' => 0],
            ['id' => 44, 'code' => '44', 'name' => '大分県', 'delete_flag' => 0],
            ['id' => 45, 'code' => '45', 'name' => '宮崎県', 'delete_flag' => 0],
            ['id' => 46, 'code' => '46', 'name' => '鹿児島県', 'delete_flag' => 0],
            ['id' => 47, 'code' => '47', 'name' => '沖縄県', 'delete_flag' => 0],
        ]);
    }
}
