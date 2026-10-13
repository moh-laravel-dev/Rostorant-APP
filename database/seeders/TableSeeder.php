<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            Table::create([
                'name' => 'Table ' . $i,
                'number' => $i,
                'status' => true,
            ]);
        }
    }
}
