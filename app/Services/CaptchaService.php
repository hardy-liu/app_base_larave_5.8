<?php

namespace App\Services;

use Mews\Captcha\Facades\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\CustomException;

class CaptchaService
{
    public static function getCaptchaImg()
    {
        return Captcha::create();
    }

    public static function validateCaptcha(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'captcha' => 'required|captcha'
        ]);

        if ($validator->fails()) {
            throw new CustomException('图片验证码错误');
        } else {
            return true;
        }
    }
}
