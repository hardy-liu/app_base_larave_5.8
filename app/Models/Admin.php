<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $primaryKey = 'id';

    protected $guarded = [
        //
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 自动更新created_at和updated_at字段
     *
     * @var bool
     */
    public $timestamps = true;


    //指定mail通知channel的地址（默认就为email字段）
    public function routeNotificationForMail()
    {
        return $this->email;
    }
}
