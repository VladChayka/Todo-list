<?php

namespace App\Repositories;

use App\DTO\UserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function store(UserData $userData)
    {
        $user = new User();

        $user->email = mb_strtolower($userData->email);
        $user->name = $userData->name;

        if ($userData->password) {
            $user->password = Hash::make($userData->password);
        }

        $user->save();

        return $user;
    }
    public static function getUserByLogin(UserData $userData)
    {
        $user = User::where('email', $userData->email)
            ->first();

        if ($user && Hash::check($userData->password, $user->password)) {
            return $user;
        }

        return null;
    }
}
