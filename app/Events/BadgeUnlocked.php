<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class BadgeUnlocked
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(String $badge_name, User $user)
    {
        $this->$badge_name = $badge_name;
        $this->$user = $user;
    }
}
