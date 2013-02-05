<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Silex\Application();
$app['debug'] = true;

$app->register(new \Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../templates',
));

#$app->register(new Silex\Provider\MonologServiceProvider(), array(
#    'monolog.logfile' => __DIR__ . '/../logs/application.log',
#));

$app->get('/', function () use ($app) {
    $templates = array();
    $i = new FilesystemIterator(__DIR__ . '/../templates');
    foreach ($i as $f) {
        $t = $f->getFilename();
        if (!preg_match('#^([^_]\w+)\.twig$#', $t))
            continue;
        $t = substr($t, 0, -5);
        $templates[] = $t;
    }
    return $app['twig']->render(
        '_index.twig',
        array('templates' => $templates)
    );
});

$app->get('/template/{template}', function ($template) use ($app) {
    $template = $template . '.twig';
    return $app['twig']->render(
        $template
    );
});

$app->run();


