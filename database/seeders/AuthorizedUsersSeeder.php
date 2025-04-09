<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuthorizedUser;

class AuthorizedUsersSeeder extends Seeder
{
    public function run(): void
    {
        $emails = [
            'jean@example.com',
            'alice@example.com',
            'emma@example.com',
            'bluespyd@gmail.com'
        ];

        foreach ($emails as $email) {
            AuthorizedUser::updateOrCreate(['email' => $email]);
        }
    }
}

