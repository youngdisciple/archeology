<?php

namespace App\Helpers;

use Monolog\Logger;
use Monolog\Level;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use DateTimeZone;


class LogHelper
{

    public static function logAccess(string $record, array $extra = [])
    {
        self::logToFile('access', 'access.log', Level::Info, $record, $extra);
    }

    public static function logError(string $record, array $extra = [])
    {
        self::logToFile('error', 'error.log', Level::Error, $record, $extra);
    }

    private static function logToFile(string $channel, string $file_name, Level $level,  string $record, array $extra = [])
    {
        $logger = new Logger($channel);
        $lineFormatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message% %context%\n", 'Y-m-d H:i:s');
        $logger->pushHandler(
            (new StreamHandler(APP_BASE_PATH . "/var/logs/" . $file_name, $level))->setFormatter($lineFormatter)
        )->setTimezone(new DateTimeZone('America/Toronto'));

        if ($level == Level::Info) {
            $logger->info($record, $extra);
        }
        if ($level == Level::Error) {
            $logger->error($record, $extra);
        }
    }
}
