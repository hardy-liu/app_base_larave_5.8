<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $per_page = 15;
    protected $page = 1;
    protected $order = ['id', 'desc'];

    public function __construct(Request $request)
    {
        $this->per_page = $request->per_page ?: $this->per_page;
        $this->order = $request->sort ? explode('|', $request->sort) : $this->order;
        $this->page = $request->page ?: $this->page;
    }

    /**
     * @param string $msg
     * @param $data
     * @return array
     */
    public function res($msg, $data = null)
    {
        return [
            'success' => true,
            'code' => -1,
            'message' => $msg,
            'data' => $data,
        ];
    }

    /**
     * @param string $msg
     * @param string $code
     * @param $data
     * @return array
     */
    public function errorRes(string $msg, int $code = 1, $data = null): array
    {
        return [
            'success' => false,
            'code' => $code,
            'message' => $msg,
            'data' => $data,
        ];
    }
}
