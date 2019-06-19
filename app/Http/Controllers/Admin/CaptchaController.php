<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\CaptchaService;
use App\Http\Controllers\Controller;

class CaptchaController extends Controller
{
    public function showCaptcha(Request $request)
    {
        return CaptchaService::getCaptchaImg();
    }
}
