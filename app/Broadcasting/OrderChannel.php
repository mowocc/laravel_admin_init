<?php

namespace App\Broadcasting;

use App\Models\Admin\Auth\User;

class OrderChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\Admin\Auth\User  $user
     * @return array|bool
     */
    public function join(User $user)
    {
        //
    }
}
