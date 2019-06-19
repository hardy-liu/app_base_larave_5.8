<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserSessionIdCacheService
{
    protected $cacheKeyPrefix;
    protected $cacheLifetime;

    public function __construct()
    {
        $this->cacheKeyPrefix = config('custom.cache_key_prefix_user_sessions', 'user_sessions:');
        $this->cacheLifetime = config('session.lifetime');
    }

    public function getSessionIds($userId)
    {
        $cacheKey = $this->cacheKeyPrefix . $userId;
        return Cache::has($cacheKey) ? Cache::get($cacheKey) : [];
    }

    public function storeSessionId(Request $request, $user)
    {
        $sessionId = $request->session()->getId();
        $cacheKey = $this->cacheKeyPrefix . $user->id;

        if (Cache::has($cacheKey)) {
            $sessionIds = Cache::get($cacheKey);
            $sessionIds[] = $sessionId; //列表中添加新的session id
        } else {
            $sessionIds = [
                $sessionId,
            ];
        }

        Cache::put($cacheKey, $sessionIds, $this->cacheLifetime);
    }

    public function deleteSessionId($sessionId, $userId)
    {
        $cacheKey = $this->cacheKeyPrefix . $userId;

        if (Cache::has($cacheKey)) {
            $sessionIds = Cache::get($cacheKey);
            $index = array_search($sessionId, $sessionIds);
            if ( $index !== false) {
                unset($sessionIds[$index]);
                $sessionIds = array_merge($sessionIds);   //重新索引
                Cache::put($cacheKey, $sessionIds, $this->cacheLifetime);   //存入redis
            }
        }
    }

    public function flushSessionIds($userId)
    {
        $cacheKey = $this->cacheKeyPrefix . $userId;
        Cache::forget($cacheKey);
    }
}
