<?php

declare(strict_types=1);

namespace App\Admin\Controllers;

use Dcat\Admin\Http\Controllers\AuthController as BaseAuthController;

class AuthController extends BaseAuthController
{
    protected $view = 'admin.login';
}
