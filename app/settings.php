<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.logd',
                    'level' => Logger::DEBUG,
                ],
                "db" => [
                    'driver' => 'mysql',
                    'host' => 'localhost',
                    'username' => 'root',
                    'database' => 'uruk',
                    'password' => 'alek384ck32@Q',
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'flags' => [
                        // Turn off persistent connections
                        PDO::ATTR_PERSISTENT => false,
                        // Enable exceptions
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        // Emulate prepared statements
                        PDO::ATTR_EMULATE_PREPARES => true,
                        // Set default fetch mode to array
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ],
                ],
                "twig" => [
                    'path_templates' => __DIR__ . '/templates',
                    'path_cache' => false
                ],
                "redis"=> [
                    'host' => 'localhost',
                    'port' => 6379,
                    'connectTimeout' => 1000,
                    'auth' => ['phpredis', '1111'],
//                    'ssl' => [
//                        'verify_peer_name' => false
//                    ],
                ]
            ]);
        }
    ]);
};
