<?php

namespace App\Policies;

use App\Models\Skill;
use App\Models\User;

class SkillPolicy {
    public function viewAll() {
        return false;
    }

    public function create(): bool {
        return false;
    }

    public function update(User $user, Skill $skill): bool {
        return false;
    }

    public function delete(User $user, Skill $skill): bool {
        return false;
    }
}
