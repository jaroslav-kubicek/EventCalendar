<?php

use Nette\Application\Routers\Route;
use Nette\Diagnostics\Debugger;

require LIBS_DIR . '/Nette/loader.php';

// aktivování debuggeru a laděnky
Debugger::$logDirectory = __DIR__ . '/../log';
Debugger::$strictMode = TRUE;
Debugger::enable();

// nastavení dočasného úložiště
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/../temp');

// stanovení cesty k třídám aplikace a Nette
$configurator->createRobotLoader()
    ->addDirectory(APP_DIR)
    ->addDirectory(LIBS_DIR)
    ->register();

// nastavení konfigurace hlavního DI kontejneru
$configurator->addConfig(__DIR__ . '/config/config.neon');
$container = $configurator->createContainer();

// nastavení routování
$router = $container->router;

$router[] = new Route('index.php', array(
            'presenter' => 'Homepage',
            'action' => 'default',
                ), Route::ONE_WAY);

$router[] = new Route('<presenter>/<action>[/<id>]', array(
            'presenter' => 'Homepage',
            'action' => 'default',
            'id' => NULL,
        ));

//nastavení error presenteru
//$application = $container->application;
//$application->errorPresenter = 'Error';

// spuštění aplikace
$container->application->run();
