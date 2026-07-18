<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create
        {--name= : Nama lengkap admin}
        {--email= : Alamat email admin}
        {--password= : Password admin, kosongkan agar diminta secara tersembunyi}';

    protected $description = 'Create a new administrator or promote an existing user';

    public function handle(): int
    {
        $name = trim((string) ($this->option('name') ?: $this->ask('Nama admin')));
        $email = strtolower(trim((string) ($this->option('email') ?: $this->ask('Email admin'))));
        $password = (string) ($this->option('password') ?: $this->secret('Password admin'));

        $validator = Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email:rfc', 'max:255'],
                'password' => [
                    'required',
                    'string',
                    Password::min(12)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                ],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'email_verified_at' => now(),
                'is_admin' => true,
            ],
        );

        $this->newLine();
        $this->info(
            $user->wasRecentlyCreated
                ? 'Admin berhasil dibuat.'
                : 'Akun berhasil diperbarui dan diberikan akses admin.'
        );

        $this->table(
            ['ID', 'Nama', 'Email', 'Admin'],
            [[
                $user->id,
                $user->name,
                $user->email,
                $user->is_admin ? 'Ya' : 'Tidak',
            ]],
        );

        return self::SUCCESS;
    }
}
