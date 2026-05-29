<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    \Illuminate\Support\Facades\DB::table('kategoris')->insert([
        ['nama_kategori' => 'Dimsum Kukus'],
        ['nama_kategori' => 'Pangsit'],
        ['nama_kategori' => 'Dimsum Spesial'],
    ]);
}
}
