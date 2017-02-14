<?php
/**
 * Created by PhpStorm.
 * User: Lukas Dabkowski <lukas@phpcat.net>
 * Date: 2017-02-12
 */

define( 'ROOT', __DIR__ . '/../' );

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app[ 'debug' ] = true;

$app[ 'twitter' ] = require_once __DIR__ . '/../config/twitter.php';

require_once __DIR__ . '/../app/routes.php';

return $app;