# WORKFLOW RINGKAS — PROYEK JARA (Advanced To-Do List)

| Info | Keterangan |
|---|---|
| Proyek | JARA — to-do list pribadi + tim (multi-workspace) |
| Tim | 1 PM + 2 Programmer (P1: SRS-001–004 · P2: SRS-005–008) |
| Stack | Laravel 13 · MySQL 8.0 · Blade · Breeze · Bootstrap 5 (CDN) · PHP 8.3+ |
| Durasi | ± 2 jam |
| Aturan kunci | Registrasi mandiri DITUTUP (akun hanya dibuat Admin) · `due_date` WAJIB · prioritas penting/sedang/rendah |

> Diagram Mermaid otomatis ter-render di GitHub dan VS Code (ekstensi *Markdown Preview Mermaid*).

---

## 1. Peran & Pembagian Tugas

| Peran | Branch | SRS | File utama yang dimiliki |
|---|---|---|---|
| **PM** | `main` | — | Baseline (migration, model, layout, seeder), merge, laporan |
| **P1** | `feature/auth-lists-tasks` | 001–004 | `DashboardController`, `ListController`, `TaskController`, `views/lists/**` (kecuali `partials/`), `views/tasks/**`, section `[P1]` |
| **P2** | `feature/status-collab-admin` | 005–008 | `TaskStatusController`, `MemberController`, `Admin/UserController`, `EnsureUserIsAdmin`, `views/lists/partials/**`, `views/admin/**`, `bootstrap/app.php`, section `[P2]` |

**Aturan emas:** dua programmer tidak pernah mengedit file yang sama. Kebutuhan di luar scope ditulis sebagai blok `### HANDOFF UNTUK PM`.

| ID | SRS | PIC |
|---|---|---|
| 001 | Login/logout (tanpa registrasi) | P1 |
| 002 | Workspace CRUD | P1 |
| 003 | Task CRUD | P1 |
| 004 | Prioritas & tenggat wajib | P1 |
| 005 | Tandai selesai (toggle) | P2 *(pengecatan tercoret/hijau: P1)* |
| 006 | Kolaborasi (undang/keluarkan anggota) | P2 |
| 007 | Monitoring progres | P2 |
| 008 | Administrasi pengguna (Admin) | P2 |

---

## 2. Alur Bisnis JARA — Start hingga End

```mermaid
flowchart TD
    START([START — aplikasi dibuka]) --> LOGIN["Halaman Login<br/>(tidak ada menu Register)"]
    LOGIN --> CHK{Kredensial valid?}
    CHK -- Tidak --> ERR[Pesan error] --> LOGIN
    CHK -- Ya --> ROLE{Role akun?}

    ROLE -- Admin --> ADM["/admin/users — User Management"]
    ADM --> ADD["Tambah akun<br/>(nama, email, password, role)"]
    ADD --> ADM
    ADM --> DEL{Hapus akun?}
    DEL -- "Akun sendiri" --> TOLAK[Ditolak sistem] --> ADM
    DEL -- "Akun lain" --> HAPUS["Akun + keanggotaan workspace terhapus"] --> ADM
    ADM --> OUT

    ROLE -- "User biasa" --> WS["/lists — Daftar Workspace"]
    WS --> PILIH{Buat atau buka workspace?}
    PILIH -- Buat --> NEW["Simpan workspace baru<br/>(pembuat = Owner)"] --> WS
    PILIH -- Buka --> AKSES{"isAccessibleBy()<br/>Owner atau Member?"}
    AKSES -- Tidak --> F403([403 Forbidden])
    AKSES -- Ya --> SHOW["Halaman Workspace:<br/>task urut tenggat terdekat<br/>+ progress bar + daftar anggota"]

    SHOW --> TASK["Tambah / ubah / hapus task<br/>(prioritas + tenggat WAJIB)"]
    TASK --> VALID{Tenggat & judul terisi?}
    VALID -- Tidak --> REJECT[Validasi menolak] --> TASK
    VALID -- Ya --> SIMPAN[Task tersimpan] --> SHOW

    SHOW --> TOGGLE["Tandai selesai (todo ⇄ done)"]
    TOGGLE --> PROG["Progres dihitung ulang:<br/>Selesai · Belum · Total · persen"] --> SHOW

    SHOW --> OWNER{User adalah Owner?}
    OWNER -- Ya --> INVITE["Undang anggota via email /<br/>keluarkan anggota"] --> SHOW
    OWNER -- Tidak --> SHOW

    SHOW --> OUT[Logout]
    WS --> OUT
    OUT --> END([END])
```

---

## 3. Alur Kerja Tim (±2 jam)

```mermaid
flowchart TD
    A([START — T+0]) --> B["PM: finalisasi 8 SRS & pembagian"]
    B --> C["PM: generate baseline<br/>(Laravel 13 + Breeze + registrasi dimatikan<br/>+ migration + model + seeder + route skeleton)"]
    C --> D["PM: push branch main ke GitHub"]
    D --> E["P1 & P2: clone/pull, buat branch fitur"]
    E --> F1["P1 · SRS-001 → 002 → 003 → 004"]
    E --> F2["P2 · SRS-008 → 005 → 006 → 007"]
    F1 --> G["Implementasi paralel<br/>(commit kecil, push rutin)"]
    F2 --> G
    G --> H["Push branch + Pull Request"]
    H --> I["PM: merge berurutan P1 → P2"]
    I --> J{Konflik / bug?}
    J -- Ya --> K["Programmer terkait perbaiki → push ulang"] --> I
    J -- Tidak --> L["migrate:fresh --seed<br/>+ smoke test 8 SRS"]
    L --> M["PM: laporan + screenshot demo"]
    M --> N([END — T+120])
```

### Rundown

| Menit | Aktivitas | PIC |
|---|---|---|
| 0–10 | Briefing; PM mulai baseline; programmer pahami SRS & siapkan prompt AI | Semua |
| 10–25 | Baseline push `main`; anggota pull, `php artisan serve`, buat branch | PM → semua |
| 25–85 | Implementasi paralel per branch | P1, P2 |
| 85–105 | Merge bertahap P1 → P2 + resolve konflik | PM |
| 105–115 | `migrate:fresh --seed`, smoke test 8 SRS, bugfix kecil | Semua |
| 115–120 | Screenshot, finalisasi laporan, siap demo | PM |

---

## 4. Titik Rawan & Cara Menghindarinya

| Risiko | Solusi yang sudah disepakati |
|---|---|
| Dua orang edit `TaskController` | Dipisah: `TaskController` (P1) vs `TaskStatusController` (P2) |
| Partial belum ada saat branch diuji | P1 memanggil dengan `@includeIf(...)` → aman dilewati |
| Konflik `routes/web.php` | Section berkomentar `[P1]` / `[P2]`, saat merge ambil kedua blok |
| Konflik `bootstrap/app.php` | Hanya P2 yang boleh mengubah (alias middleware `admin`) |
| `route()` error saat branch terpisah | Navigasi & form pakai URL literal (`/lists`, `/admin/users`) |
| Tombol toggle "bolong" | Smoke test SRS-005 dijalankan **setelah** kedua merge |

---

## 5. Checklist Selesai (Definition of Done)

- [ ] Kedua branch ter-merge ke `main` tanpa konflik tersisa
- [ ] Dari nol: `clone` → `composer install` → `.env` → `migrate --seed` → `serve` berjalan
- [ ] `/register` → 404; akun baru hanya lahir dari `/admin/users`
- [ ] Simpan task tanpa tenggat → ditolak validasi; task urut tenggat terdekat
- [ ] Toggle selesai → judul tercoret + hijau, progres "Selesai X · Belum Y · Z%" ikut berubah
- [ ] Owner undang member by email → muncul → dikeluarkan → hilang
- [ ] Non-member buka workspace orang lain → 403
- [ ] Setiap programmer mampu menjelaskan kode pada scope-nya

**Akun demo:** `admin@jara.test` (Admin) · `budi@jara.test` (Owner) · `citra@` & `dimas@jara.test` (Member) — password: `password`
