<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUser($email){

        $users = User::where('email', $email)->first();

        if ($users) {
            $username = $users->name;
            return $users;
        }

        return "User not found";
    }
}
