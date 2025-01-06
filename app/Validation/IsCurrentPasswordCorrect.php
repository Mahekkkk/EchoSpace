<?php

namespace App\Validation;
use App\Libraries\CiAuth;
use App\Libraries\hash;
use App\Models\User;

class IsCurrentPasswordCorrect
{
    public function check_current_password(string $password): bool
{
    $password = trim($password); // Remove extra spaces from input
    $user_id = CiAuth::id(); // Get the logged-in user's ID

    $user = new User();
    $user_info = $user->asObject()->where("id", $user_id)->first();

    // Check if the provided password matches the stored hashed password
    if (!$user_info || !hash::check($password, $user_info['password'])) {
        return false;
    }

    return true;
}

}
