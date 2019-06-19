<?php

return [
    //计划任务日志
    'cron_task_log' => storage_path('logs/' . env('CRON_TASK_LOG', 'artisan.log')),

    'cache_key_prefix_user_sessions' => 'user_sessions:',    //用户session id的缓存key prefix

];
