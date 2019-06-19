<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 获取用户信息
     *
     * @OA\Get(
     *     path="/api/user/info",
     *     description="获取用户信息",
     *     operationId="api.user.info.get",
     *     tags={"user"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/Success"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/Unauthorized"),
     *     ),
     * )
     */
    public function getUserInfo(Request $request)
    {
        $user = Auth::user();

        return $this->res('success', $user);
    }
}
