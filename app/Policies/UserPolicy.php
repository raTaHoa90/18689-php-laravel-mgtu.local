<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{

    public function accessToAdminPanel(User $user){
        return !in_array($user->group_role, [User::ROLE_CLIENT, User::ROLE_GUEST, User::ROLE_BANNED]);
    }

    public function updateOrderStatusToWork(User $user){

    }

    public function updateOrderStatusToTravel(User $user){

    }

    public function updateOrderStatusToApply(User $user){

    }
}
