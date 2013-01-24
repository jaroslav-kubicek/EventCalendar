<?php

// absolutní cesta do rootu adresáře
define('WWW_DIR', __DIR__);

// absolutní cesta k rootu aplikace
define('APP_DIR', WWW_DIR . '/../app');

// absolutní cesta k rootu knihoven
define('LIBS_DIR', WWW_DIR . '/../libs');

// načte bootstrap soubor
require APP_DIR . '/bootstrap.php';

