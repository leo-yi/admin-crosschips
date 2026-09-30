<?php

namespace App\Models;

use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * Class User
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $avatar
 * @property int $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, HasDateTimeFormatter;

    protected $table = 'users';

    protected $casts = [
        'is_active' => 'int',
    ];

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'username',
        'email',
        'password',
        'phone',
        'avatar',
        'is_active',
    ];
}
