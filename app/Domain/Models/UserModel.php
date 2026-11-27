<?php

namespace App\Domain\Models;

use App\Helpers\Core\PasswordTrait;

class UserModel extends BaseModel
{
    use PasswordTrait;

    public function createUser(array $userInfo ) {
        $plainPassword = $userInfo['password'];
        $encryptedPassword = $this->cryptPassword($plainPassword);
        $userInfo['password'] = $encryptedPassword;

        //validate

        return $this->insert("ws_users", $userInfo);
    }

    public function findByUsername(string $username ) : ?array {
        $sql = "SELECT * FROM ws_users WHERE $username = :username";
        return $this->fetchSingle($sql, ["username" => $username]);

    }
}
