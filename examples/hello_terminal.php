<?php

use Igni\Terminal\Application;
use Igni\Terminal\Arguments;
use Igni\Terminal\Canvas;
use Igni\Terminal\Canvas\Style;
use Igni\Terminal\Canvas\Widget\Text;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->command('', function(Canvas $canvas, Arguments $args) {
    $name = $args->get('name', 'terminal');

    $message = new Text("Hello ${name}", Style::create('white', 'green'));
    $canvas->draw($message);
});

$app->run();
