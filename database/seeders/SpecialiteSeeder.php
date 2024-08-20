<?php

namespace Database\Seeders;

use App\Models\Specialite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Specialite::create(['nom' => 'Cardiologue','description' => 'iuytresazx']);
        Specialite::create(['nom' => 'Dermatologue','description' => 'iuytresazx']);
        Specialite::create(['nom' => 'Généraliste','description' => 'iuytresazx']);

    }
}
