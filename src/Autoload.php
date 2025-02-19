<?php
/**
 * @package     Triangle Cron Component
 * @link        https://github.com/Triangle-org/Events
 *
 * @author      Ivan Zorin <creator@localzet.com>
 * @copyright   Copyright (c) 2023-2025 Triangle Framework Team
 * @license     https://www.gnu.org/licenses/agpl-3.0 GNU Affero General Public License v3.0
 *
 *              This program is free software: you can redistribute it and/or modify
 *              it under the terms of the GNU Affero General Public License as published
 *              by the Free Software Foundation, either version 3 of the License, or
 *              (at your option) any later version.
 *
 *              This program is distributed in the hope that it will be useful,
 *              but WITHOUT ANY WARRANTY; without even the implied warranty of
 *              MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *              GNU Affero General Public License for more details.
 *
 *              You should have received a copy of the GNU Affero General Public License
 *              along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *              For any questions, please contact <triangle@localzet.com>
 */

namespace Triangle\Cron;

use localzet\Cron;
use localzet\Server;
use Triangle\Engine\AutoloadInterface;

class Autoload implements AutoloadInterface
{
    public static function start(?string $arg = '', ?Server $server = null): void
    {
        if (\Triangle\Engine\Autoload::isManageCommand($arg)) {
            $server = localzet_start('Cron', 1);
            $server->onServerStart = function () {
                foreach (config('cron', []) as $task) {
                    if (!empty($task['callback']) && !empty($task['rule'])) {
                        new Cron($task['rule'], $task['callback'], $task['name'] ?? '');
                    }
                }
            };
        }
    }
}