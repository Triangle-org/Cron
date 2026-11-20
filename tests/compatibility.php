<?php declare(strict_types=1);

/**
 * @package     Triangle Cron
 * @author      Ivan Zorin <creator@localzet.com>
 * @copyright   Copyright © 2016-2026 Localzet Group. All rights reserved.
 * @license     https://www.gnu.org/licenses/agpl-3.0 GNU Affero General Public License v3.0
 *
 *              This program is free software: you can redistribute it and/or modify
 *              it under the terms of the GNU Affero General Public License as published
 *              by the Free Software Foundation, either version 3 of the License, or
 *              (at your option) any later version.
 *
 *              This program is distributed in the hope that it will be useful,
 *              but WITHOUT ANY WARRANTY; without even the implied warranty of
 *              MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *              GNU Affero General Public License for more details.
 *
 *              You should have received a copy of the GNU Affero General Public License
 *              along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

use Triangle\Cron\Autoload;
use Triangle\Engine\Config;
use Triangle\Engine\AutoloadInterface;
use localzet\Cron;
use localzet\Timer;

require dirname(__DIR__) . '/vendor/autoload.php';

if (!is_subclass_of(Autoload::class, AutoloadInterface::class)) {
    throw new RuntimeException('Cron autoload must implement the Engine autoload contract.');
}

Autoload::start('application-command');

Config::set([
    'cron' => [
        [
            'rule' => '*/5 * * * *',
            'callback' => static function (): void {},
            'name' => 'compatibility-check',
        ],
        ['rule' => '*/5 * * * *'],
    ],
]);
Timer::init();
Autoload::start('start');

$servers = \localzet\Server::getAllServers();
$server = end($servers);

if (!$server instanceof \localzet\Server || $server->name !== 'Cron') {
    throw new RuntimeException('Cron must register a dedicated Server 7 process.');
}

if (!is_callable($server->onServerStart)) {
    throw new RuntimeException('Cron must register its task loader on server startup.');
}

($server->onServerStart)($server);

if (count(Cron::getAll()) !== 1) {
    throw new RuntimeException('Cron must register only complete task definitions.');
}

foreach (Cron::getAll() as $job) {
    $job->destroy();
}

echo "OK: Engine 9 and Server 7 startup compatibility\n";
