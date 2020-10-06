<?php

use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Unit::query()->insert([
            ['name' => '%'],
            ['name' => 'Bầu'],
            ['name' => 'Bể'],
            ['name' => 'Bộ'],
            ['name' => 'Bóng'],
            ['name' => 'Ca'],
            ['name' => 'Cái'],
            ['name' => 'Cành'],
            ['name' => 'Cây'],
            ['name' => 'Chậu'],
            ['name' => 'Chai'],
            ['name' => 'Chiếc'],
            ['name' => 'Con'],
            ['name' => 'Cọc'],
            ['name' => 'Công'],
            ['name' => 'Cột'],
            ['name' => 'Cuộn'],
            ['name' => 'Dây'],
            ['name' => 'Đôi'],
            ['name' => 'Giỏ'],
            ['name' => 'Gam'],
            ['name' => 'Hộp'],
            ['name' => 'Hòm'],
            ['name' => 'Kg'],
            ['name' => 'Kw'],
            ['name' => 'Lít'],
            ['name' => 'Lọ'],
            ['name' => 'M'],
            ['name' => 'm2'],
            ['name' => 'm3'],
            ['name' => 'Mét'],
            ['name' => 'md'],
            ['name' => 'Ống'],
            ['name' => 'Quả'],
            ['name' => 'Quyển'],
            ['name' => 'Ram'],
            ['name' => 'Tập'],
            ['name' => 'Tấm'],
            ['name' => 'Tấn'],
            ['name' => 'Tờ'],
            ['name' => 'Thanh'],
            ['name' => 'Tuýp'],
            ['name' => 'Viên'],
            ['name' => 'Khung'],
            ['name' => 'Cặp'],
            ['name' => 'Cánh'],
            ['name' => 'Cont'],
            ['name' => 'Máy'],
        ]);
    }
}
