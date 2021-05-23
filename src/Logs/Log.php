<?php


namespace Gavan4eg\HouseBank\Logs;

use Illuminate\Support\Facades\Log as Logger;

class Log
{
    /**
     * Логирование
     *
     * @param $data
     * @return bool
     */
    public function log($data): bool
    {
        Logger::info(json_encode($data));

        return true;
    }

    /**
     * Ошибка
     *
     * @param $data
     * @return bool
     */
    public function error($data): bool
    {
        Logger::error('Error create function. '.json_encode($data));

        return true;
    }
}
