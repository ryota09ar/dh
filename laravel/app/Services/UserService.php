<?php

namespace App\Services;

use App\Models\User;

class UserService{
    public static function return_name($user_id){
        if (User::where('family_name', User::find($user_id)->family_name)->select('family_name')->groupBy('family_name')->havingRaw('COUNT(*) >= 2')->exists()) {
            return User::find($user_id)->family_name.mb_substr(User::find($user_id)->first_name, 0, 1, "UTF-8");
        } else{
            return User::find($user_id)->family_name;
        }
    }
}
