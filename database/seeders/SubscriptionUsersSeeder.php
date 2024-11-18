<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subscription_users')->insert([
            [
                'sub_domain' => '24karat',
                'barcode_type' => 'EAN13',
                'company_name' => '24karat',
                'zip' => '123-4567',
                'created_at' => now(),
                'updated_at' => now(),
                'delete_flag' => 0
            ],
            [
                'sub_domain' => 'yamaha',
                'barcode_type' => 'FREECODE',
                'company_name' => 'ヤマハ発動機',
                'zip' => '999-9999',
                'created_at' => now(),
                'updated_at' => now(),
                'delete_flag' => 0
            ],
            [
                'sub_domain' => 'uccj',
                'barcode_type' => 'FREECODE',
                'company_name' => 'UCCJ',
                'zip' => '123-4567',
                'created_at' => now(),
                'updated_at' => now(),
                'delete_flag' => 0
            ],
            [
                'sub_domain' => 'mcom',
                'barcode_type' => 'EAN13',
                'company_name' => 'mcom (ミューコム)',
                'zip' => '111-1111',
                'created_at' => now(),
                'updated_at' => now(),
                'delete_flag' => 0
            ],
            [
                'sub_domain' => 'sagami-oil',
                'barcode_type' => 'EAN13',
                'company_name' => '相模石油株式会社',
                'zip' => '254-0043',
                'created_at' => now(),
                'updated_at' => now(),
                'delete_flag' => 0
            ],
        ]);
    }
}
