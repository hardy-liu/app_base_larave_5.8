<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function updatePass(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:8',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (! Hash::check($request->password, $user->password)) {
            throw new CustomException('原密码输入错误');
        }

        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return $this->res('更新密码成功');
    }

    public function getInfo(Request $request)
    {
        $admin = Auth::user();
        return $this->res('success', $admin);
    }
}
