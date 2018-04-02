<?php

use Igni\Terminal\Application;
use Igni\Terminal\Canvas;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->command('', function(Canvas $canvas) {
    $progressBar = new Canvas\Widget\ProgressBar(1000);
    $progressBar
        ->label('Test label: ${value} %')
        ->length($canvas->getWidth() - 40);

    $canvas->disable();
    for ($i = 0; $i <= $progressBar->getTotal(); $i++) {
        $progressBar->progress($i, $canvas);
        usleep(1000);
    }

    $canvas->enable();
});

$app->run();
