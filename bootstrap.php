<?php

use Dotenv\Dotenv;
const BASE_PATH = __DIR__;

require BASE_PATH . '/vendor/autoload.php';
Dotenv::createImmutable(BASE_PATH)->load();
return require_once BASE_PATH . '/config/config.php';