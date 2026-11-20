<?php

namespace App\Domain\Models;

class RegisterModel extends BaseModel
{

    public function register(array $userInfo) : mixed{

        return $this->insert(
            'ws_users',
            $userInfo
        );
    }
}
