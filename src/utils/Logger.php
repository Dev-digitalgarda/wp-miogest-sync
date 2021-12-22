<?php

namespace WpMiogestSync\Utils;

use Monolog;

$__wp_miogest_sync_logger = new Monolog\Logger('wp-miogest-sync');
$__wp_miogest_sync_logger->pushHandler(new Monolog\Handler\StreamHandler('wp-miogest-sync.log'));

class Logger
{
    public static ?Monolog\Logger $logger = null;

    public static function info(string $message)
    {
        self::$logger->info($message);
        echo $message . "<br />";
    }

    public static function warning(string $message)
    {
        self::$logger->warning($message);
        echo $message . "<br />";
    }
}
Logger::$logger = $__wp_miogest_sync_logger;
