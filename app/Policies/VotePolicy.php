<?php

namespace App\Policies;

use App\Models\Vote;
use App\Models\User;

class VotePolicy
{
    public function update(User $user, Vote $vote): bool
    {
        return $user->id === $vote->user_id;
    }

    public function delete(User $user, Vote $vote): bool
    {
        return $user->id === $vote->user_id;
    }
}
