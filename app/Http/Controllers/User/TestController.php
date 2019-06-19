<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function test(Request $request)
    {
        return 'ok';
    }
}
