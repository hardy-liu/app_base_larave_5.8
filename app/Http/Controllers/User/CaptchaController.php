<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Services\CaptchaService;
use App\Http\Controllers\Controller;

class CaptchaController extends Controller
{
    /**
     * 获取验证码图片
     *
     * @OA\Get(
     *     path="/api/captcha",
     *     description="获取验证码图片",
     *     operationId="api.captcha.get",
     *     tags={"captcha"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="返回二维码图片",
     *         @OA\JsonContent(ref="#/components/schemas/Success"),
     *     )
     * )
     */
    public function showCaptcha(Request $request)
    {
        return CaptchaService::getCaptchaImg();
    }
}
