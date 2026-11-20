# Triangle Cron

Triangle Cron registers the Localzet Cron scheduler as a one-worker process in a Triangle Engine application. It reads task definitions from `config('cron')` and attaches them to the scheduler process when that server starts.

## Requirements

- PHP `^8.1`
- Triangle Engine `^9.0`
- Localzet Server `^7.1` (provided by Engine 9)
- Localzet Cron `^2.0`

Version 2.x targets Engine 9 and Server 7. Applications on Engine 8 and Server 4 must stay on Triangle Cron 1.x.

## Installation

```sh
composer require triangle/cron:^2.0
```

Add task definitions to `config/cron.php`:

```php
<?php

return [
    [
        'name' => 'cleanup-expired-records',
        'rule' => '*/5 * * * *',
        'callback' => static function (): void {
            // Run a short, non-blocking task here.
        },
    ],
];
```

Five-field rules use minute precision. Six-field rules can include seconds. Callbacks run on the Localzet Server event loop: keep them short and non-blocking, and send long-running work to a queue or another process.

## Runtime behavior

When Engine bootstraps a managed server startup (`start` or `restart`), the component registers a dedicated `Cron` server with one worker. At that worker's `onServerStart` event, it reads `config('cron', [])` and registers entries that have both a non-empty `rule` and a `callback`.

This is an in-process scheduler, not a durable job queue. Scheduled work is not persisted across restarts, and the component does not coordinate schedules across separate application installations. Keep the scheduler process to one worker unless the application deliberately wants each worker to execute every configured task.

## Checks

```sh
composer install
composer run check
```

The compatibility check verifies that the component implements Engine 9's autoload contract, creates its scheduler server through Server 7's startup helper, and loads only complete task definitions. CI runs the checks on PHP 8.1–8.5.

## License

AGPL-3.0-or-later.

Copyright © 2016-2026 [Localzet Group](https://www.localzet.com/). All rights reserved.
