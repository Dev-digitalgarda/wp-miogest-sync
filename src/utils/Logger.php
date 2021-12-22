<?php

namespace WpMiogestSync\Utils;

use Monolog;

$__wp_miogest_sync_logger = new Monolog\Logger('wp-miogest-sync');
$__wp_miogest_sync_logger->pushHandler(new Monolog\Handler\StreamHandler('wp-miogest-sync.log'));

class Logger
{
    public static ?Monolog\Logger $logger = null;

    private static function getTimeTag(): string {
        return "[" . date('Y-m-d H:i:s') . "]";
    }

    public static function info(string $message)
    {
        $timeTag = self::getTimeTag();
        self::$logger->info($message);
        echo "$timeTag\t[INFO]\t$message <br />";
    }

    public static function warning(string $message)
    {
        $timeTag = self::getTimeTag();
        self::$logger->warning($message);
        echo "$timeTag\t[WARNING]\t$message <br />";
    }
}
Logger::$logger = $__wp_miogest_sync_logger;
