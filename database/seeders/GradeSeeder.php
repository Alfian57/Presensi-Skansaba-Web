<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'X RPL 1',
            'X RPL 2',
            'X TKJ 1',
            'X TKJ 2',
            'X AKL 1',
            'X AKL 2',
            'X AKL 3',
            'X AKL 4',
            'X BDP 1',
            'X BDP 2',
            'X OTKP 1',
            'X OTKP 2',
            'X MM 1',
            'X MM 2',
            'X PS 1',
            'X PS 2',
            'XI RPL 1',
            'XI RPL 2',
            'XI TKJ 1',
            'XI TKJ 2',
            'XI AKL 1',
            'XI AKL 2',
            'XI AKL 3',
            'XI AKL 4',
            'XI BDP 1',
            'XI BDP 2',
            'XI OTKP 1',
            'XI OTKP 2',
            'XI MM 1',
            'XI MM 2',
            'XI PS 1',
            'XI PS 2',
            'XII RPL 1',
            'XII RPL 2',
            'XII TKJ 1',
            'XII TKJ 2',
            'XII AKL 1',
            'XII AKL 2',
            'XII AKL 3',
            'XII AKL 4',
            'XII BDP 1',
            'XII BDP 2',
            'XII OTKP 1',
            'XII OTKP 2',
            'XII MM 1',
            'XII MM 2',
            'XII PS 1',
            'XII PS 2',
        ];

        foreach ($data as $item) {
            Grade::create([
                'name' => $item,
                'slug' => Str::slug($item),
            ]);
        }
    }
}
