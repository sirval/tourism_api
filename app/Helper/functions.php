<?php

namespace App\Helper;

use App\Models\User;

if (! function_exists('find_user_by_email')) {
    function find_user_by_email($email): mixed
    {
        $user = User::where('email', $email)->first();

        return $user !== null ? $user : null;
    }
}

if (! function_exists('find_user_by_id')) {
    function find_user_by_id($id): mixed
    {
        $user = User::where('id', $id)->first();

        return $user !== null ? $user : null;
    }
}
