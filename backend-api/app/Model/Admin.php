<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    public function role(){
        return $this->belongsTo(AdminRole::class,'admin_role_id');
    }
}
