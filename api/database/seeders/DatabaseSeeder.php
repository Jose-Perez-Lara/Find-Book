<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Negocio;
use App\Models\Categorias;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Juan',
            'email' => 'prueba@negocio.com',
            'password' => 'prueba',
            'firebase_uid' => 'sJPstttCIROJ5jBwK9FNNC0cZsx1',
            'telefono' => '222222222',
            'rol_id' => '2',
            'verificado' => true,
        ]);

        Categorias::create([
            'nombre' => 'Peluquería'
        ]);
        
        Categorias::create([
            'nombre' => 'Tienda de tatuajes'
        ]);

        Categorias::create([
            'nombre' => 'Salón de uñas'
        ]);

        Categorias::create([
            'nombre' => 'Spa'
        ]);

        Categorias::create([
            'nombre' => 'Depilación'
        ]);

        Categorias::create([
            'nombre' => 'Piercing'
        ]);

        Negocio::create([
            'usuario_id' => 1,
            'nombre' => 'Peluqueria prueba',
            'descripcion' => 'Descripcion peluquería de prueba',
            'categoria_id' => 1,
        ]);
    }
}