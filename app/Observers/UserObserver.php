<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Listen to the Server deleted event.
     *
     * @param  User  $user
     * @return void
     */
    public function deleted(User $user): void
    {
        $user->update(['password' => bcrypt('password')]);
    }
}
