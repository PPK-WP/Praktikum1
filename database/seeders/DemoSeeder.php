<?php

namespace Database\Seeders;

use App\Models\TaskActivity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Data demo sesuai user story: budi owner 3 workspace,
     * citra & dimas member "Project Website".
     * Setiap task punya pemilik (created_by) + catatan aktivitas (task_activities).
     */
    public function run(): void
    {
        $budi = User::create(['name' => 'Budi', 'email' => 'budi@jara.test', 'password' => 'password', 'role' => 'user']);
        $citra = User::create(['name' => 'Citra', 'email' => 'citra@jara.test', 'password' => 'password', 'role' => 'user']);
        $dimas = User::create(['name' => 'Dimas', 'email' => 'dimas@jara.test', 'password' => 'password', 'role' => 'user']);

        // Workspace pribadi milik budi
        $kantor = $budi->lists()->create(['name' => 'Kantor']);
        $kantorTasks = $kantor->tasks()->createMany([
            ['title' => 'Kirim laporan mingguan', 'priority' => 'penting', 'due_date' => today()->subDays(2), 'created_by' => $budi->id],
            ['title' => 'Rapat koordinasi divisi', 'priority' => 'sedang', 'due_date' => today()->addDay(), 'created_by' => $budi->id],
            ['title' => 'Rapikan arsip dokumen', 'priority' => 'rendah', 'due_date' => today()->addDays(7), 'created_by' => $budi->id],
        ]);

        $kuliah = $budi->lists()->create(['name' => 'Kuliah']);
        $kuliahTasks = $kuliah->tasks()->createMany([
            ['title' => 'Kumpulkan laporan praktikum PPK', 'priority' => 'penting', 'due_date' => today()->subDay(), 'created_by' => $budi->id],
            ['title' => 'Baca materi Eloquent relationship', 'description' => 'Bab one-to-many & many-to-many', 'priority' => 'sedang', 'due_date' => today()->addDays(3), 'created_by' => $budi->id],
            ['title' => 'Latihan soal UTS', 'priority' => 'rendah', 'due_date' => today()->addDays(10), 'created_by' => $budi->id],
        ]);

        // Workspace tim: 4 task, 2 selesai → progres 50%.
        // Pemilik bervariasi (budi 2 · citra 1 · dimas 1) agar tampilan Pembuat & aktivitas terbukti.
        $website = $budi->lists()->create(['name' => 'Project Website']);
        $websiteTasks = $website->tasks()->createMany([
            ['title' => 'Rancang ERD database', 'priority' => 'penting', 'due_date' => today()->subDays(3), 'status' => 'done', 'created_by' => $budi->id],
            ['title' => 'Setup baseline Laravel 13', 'priority' => 'sedang', 'due_date' => today()->subDay(), 'status' => 'done', 'created_by' => $citra->id],
            ['title' => 'Implementasi halaman workspace', 'description' => 'CRUD workspace & task', 'priority' => 'penting', 'due_date' => today()->addDays(2), 'created_by' => $budi->id],
            ['title' => 'Uji coba & demo aplikasi', 'priority' => 'rendah', 'due_date' => today()->addDays(5), 'created_by' => $dimas->id],
        ]);

        $website->members()->attach([
            $citra->id => ['joined_at' => now()],
            $dimas->id => ['joined_at' => now()],
        ]);

        // Catatan aktivitas (audit trail) yang konsisten dengan task di atas.
        $this->seedActivity($kantorTasks[0], $budi, 'created', now()->subDays(3));
        $this->seedActivity($kantorTasks[1], $budi, 'created', now()->subDays(2));
        $this->seedActivity($kantorTasks[2], $budi, 'created', now()->subDay());

        $this->seedActivity($kuliahTasks[0], $budi, 'created', now()->subDays(2));
        $this->seedActivity($kuliahTasks[1], $budi, 'created', now()->subDay());
        $this->seedActivity($kuliahTasks[2], $budi, 'created', now());

        $this->seedActivity($websiteTasks[0], $budi, 'created', now()->subDays(4));
        $this->seedActivity($websiteTasks[0], $budi, 'completed', now()->subDays(3));
        $this->seedActivity($websiteTasks[1], $citra, 'created', now()->subDays(2));
        $this->seedActivity($websiteTasks[1], $citra, 'completed', now()->subDay());
        $this->seedActivity($websiteTasks[2], $budi, 'created', now()->subDays(2));
        $this->seedActivity($websiteTasks[3], $dimas, 'created', now());
    }

    private function seedActivity(\App\Models\Task $task, User $user, string $action, \Illuminate\Support\Carbon $time): void
    {
        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'action' => $action,
            'created_at' => $time,
        ]);
    }
}