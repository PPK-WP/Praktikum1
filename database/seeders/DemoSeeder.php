<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Data demo sesuai user story: budi owner 3 workspace,
     * citra & dimas member "Project Website".
     */
    public function run(): void
    {
        $budi = User::create(['name' => 'Budi', 'email' => 'budi@jara.test', 'password' => 'password', 'role' => 'user']);
        $citra = User::create(['name' => 'Citra', 'email' => 'citra@jara.test', 'password' => 'password', 'role' => 'user']);
        $dimas = User::create(['name' => 'Dimas', 'email' => 'dimas@jara.test', 'password' => 'password', 'role' => 'user']);

        // Workspace pribadi
        $budi->lists()->create(['name' => 'Kantor'])->tasks()->createMany([
            ['title' => 'Kirim laporan mingguan', 'priority' => 'penting', 'due_date' => today()->subDays(2)],
            ['title' => 'Rapat koordinasi divisi', 'priority' => 'sedang', 'due_date' => today()->addDay()],
            ['title' => 'Rapikan arsip dokumen', 'priority' => 'rendah', 'due_date' => today()->addDays(7)],
        ]);

        $budi->lists()->create(['name' => 'Kuliah'])->tasks()->createMany([
            ['title' => 'Kumpulkan laporan praktikum PPK', 'priority' => 'penting', 'due_date' => today()->subDay()],
            ['title' => 'Baca materi Eloquent relationship', 'description' => 'Bab one-to-many & many-to-many', 'priority' => 'sedang', 'due_date' => today()->addDays(3)],
            ['title' => 'Latihan soal UTS', 'priority' => 'rendah', 'due_date' => today()->addDays(10)],
        ]);

        // Workspace tim: 4 task, 2 selesai → progres 50%
        $website = $budi->lists()->create(['name' => 'Project Website']);

        $website->tasks()->createMany([
            ['title' => 'Rancang ERD database', 'priority' => 'penting', 'due_date' => today()->subDays(3), 'status' => 'done'],
            ['title' => 'Setup baseline Laravel 13', 'priority' => 'sedang', 'due_date' => today()->subDay(), 'status' => 'done'],
            ['title' => 'Implementasi halaman workspace', 'description' => 'CRUD workspace & task', 'priority' => 'penting', 'due_date' => today()->addDays(2)],
            ['title' => 'Uji coba & demo aplikasi', 'priority' => 'rendah', 'due_date' => today()->addDays(5)],
        ]);

        $website->members()->attach([
            $citra->id => ['joined_at' => now()],
            $dimas->id => ['joined_at' => now()],
        ]);
    }
}
