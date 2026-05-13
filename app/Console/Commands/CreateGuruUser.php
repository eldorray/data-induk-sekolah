<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Command CLI untuk membuat user dengan role 'guru' (hanya bisa akses Nilai Ijazah).
 *
 * Usage:
 *   php artisan user:create-guru
 *   php artisan user:create-guru --name="Pak Budi" --email=budi@example.com --password=rahasia123
 */
class CreateGuruUser extends Command
{
    protected $signature = 'user:create-guru
        {--name= : Nama lengkap guru}
        {--email= : Email login}
        {--password= : Password (min 8 karakter)}
        {--role=guru : Role user (admin atau guru)}';

    protected $description = 'Buat user baru dengan role guru/admin untuk mengakses Nilai Ijazah Kelas 6';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Nama lengkap');
        $email = $this->option('email') ?: $this->ask('Email');
        $password = $this->option('password') ?: $this->secret('Password (min 8 karakter)');
        $role = $this->option('role');

        if (! in_array($role, [User::ROLE_ADMIN, User::ROLE_GURU], true)) {
            $role = $this->choice('Role', [User::ROLE_GURU, User::ROLE_ADMIN], User::ROLE_GURU);
        }

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('Validasi gagal:');
            foreach ($validator->errors()->all() as $err) {
                $this->line(' - '.$err);
            }

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        $this->info("✓ User berhasil dibuat (ID: {$user->id})");
        $this->table(
            ['Field', 'Value'],
            [
                ['Nama', $user->name],
                ['Email', $user->email],
                ['Role', $user->role.' ('.(User::ROLES[$user->role] ?? $user->role).')'],
            ]
        );

        if ($role === User::ROLE_GURU) {
            $this->newLine();
            $this->line('<comment>Guru ini hanya bisa mengakses menu "Nilai Ijazah Kelas 6".</comment>');
        }

        return self::SUCCESS;
    }
}