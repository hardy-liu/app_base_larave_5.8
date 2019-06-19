<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         description="用户id",
 *         type="integer",
 *         example=100000,
 *     ),
 *     @OA\Property(
 *         property="name",
 *         description="用户昵称",
 *         type="string",
 *         example="Marry",
 *     ),
 *     @OA\Property(
 *         property="email",
 *         description="用户邮箱",
 *         type="string",
 *         example="aaa@123.com",
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         description="用户手机",
 *         type="string",
 *         example="18888888888",
 *     ),
 *     @OA\Property(
 *         property="active",
 *         description="是否激活(1-正常,0-禁用)",
 *         type="integer",
 *         example=1,
 *     ),
 *     @OA\Property(
 *         property="register_ip",
 *         description="注册ip",
 *         type="string",
 *         example="127.0.0.1",
 *     ),
 *     @OA\Property(
 *         property="last_login_ip",
 *         description="上次登录ip",
 *         type="string",
 *         example="127.0.0.1",
 *     ),
 *     @OA\Property(
 *         property="last_login_time",
 *         description="上次登录时间",
 *         type="string",
 *         example="2018-06-12 14:38:26",
 *     ),
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/CreatedAtUpdatedAt"),
 *     }
 * ),
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mysql';
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $guarded = [
        //
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
//        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'phone', 'active', 'register_ip', 'last_login_ip', 'last_login_time',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'email_verified_at' => 'datetime',
    ];

    /**
     * 自动更新created_at和updated_at字段
     *
     * @var bool
     */
    public $timestamps = true;
}
