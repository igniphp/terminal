<?php

use Igni\Terminal\Application;
use Igni\Terminal\Arguments;
use Igni\Terminal\Canvas;
use Igni\Terminal\Canvas\Widget\SelectList;
use Igni\Terminal\Canvas\Widget\Text;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->command('', function(Canvas $canvas, Arguments $args) {
    $list = new SelectList(
        'Whats your favourite color?',
        'red',
        'green',
        'blue',
        'yellow',
        'navy',
        'white',
        'black'
    );

    while(true) {
        $canvas->draw($list);
        $picked = $list->value($canvas);
        $info =  new Text("You have picked ${picked} option.");
        $canvas->draw($info);
    }
});

$app->run();
