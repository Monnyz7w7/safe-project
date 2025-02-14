<?php

namespace App\Http\Controllers\Api;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::whereRole(Role::USER)->paginate(50));
    }

    public function show(User $user)
    {
        return UserResource::make($user->load('actionBy'));
    }
}
