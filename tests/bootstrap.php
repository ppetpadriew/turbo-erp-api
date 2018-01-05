<?php
require_once __DIR__ . '/../vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__, '.env.test'))->load();
(new Dotenv\Dotenv(__DIR__ . '/../', '.env.default'))->load();

require_once __DIR__ . '/../app/bootstrap.php';
