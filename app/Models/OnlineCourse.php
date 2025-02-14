<?php

namespace App\Models;

use App\Enum\Role;
use Illuminate\Database\Eloquent\Model;

class OnlineCourse extends Model
{
    use \Sushi\Sushi;

    protected $rows = [
        [
            'role' => Role::ADMIN,
            'url' => 'https://learn.monnycraft.com/l/admin'
        ],
        [
            'role' => Role::WATCHER,
            'url' => 'https://learn.monnycraft.com/l/datawatcher'
        ],
        [
            'role' => Role::INSIDER,
            'url' => 'https://learn.monnycraft.com/l/insider'
        ],
        [
            'role' => Role::DEVELOPER,
            'url' => 'https://learn.monnycraft.com/l/developer'
        ],
        [
            'role' => Role::PROMOTER,
            'url' => 'https://learn.monnycraft.com/l/promoter'
        ],
        [
            'role' => Role::VALIDATOR,
            'url' => 'https://learn.monnycraft.com/l/uservalidator'
        ]
    ];
}
