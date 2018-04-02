<?php

use Igni\Terminal\Application;
use Igni\Terminal\Arguments;
use Igni\Terminal\Canvas;
use Igni\Terminal\Canvas\Style;
use Igni\Terminal\Canvas\Widget\Text;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->command('', function(Canvas $canvas, Arguments $args) {
    $paddingStyleWithBackground = Style::create('red', 'yellow')
        ->padding(5, 10);

    $centeredText = Style::create('green', 'lightgray')
        ->center(true)
        ->width(100);

    $textWithPrefix = Style::create('red', 'green')
        ->prefix('|::')
        ->suffix('::|');

    $cappedText = Style::create('light red', 'white')
        ->width(20);

    $cappedTextWithPadding = Style::create('red', 'green')
        ->padding(2, 5)
        ->width(20);

    $canvas->draw(new Text('Padded text with background', $paddingStyleWithBackground));

    $canvas->draw(new Text('Centered text in box', $centeredText));

    $canvas->draw(new Text('Text with prefix and suffix', $textWithPrefix));

    $canvas->draw(new Text('This text will be capped to eighteen characters', $cappedText));

    $canvas->draw(new Text('This text will be capped to eighteen characters', $cappedTextWithPadding));
});

$app->run();
