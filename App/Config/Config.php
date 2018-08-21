<?php
namespace Backend\Config;

use Phalcon\Config;

return new Config([
    'application' => [
        'commonsDir'           => dirname(__DIR__) . '/',
        'vendorAutoLoaderFile' => dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php',
        'bootstrapDir'         => dirname(dirname(__DIR__)) . '/Bootstrap/',
        'componentDir'         => dirname(dirname(__DIR__)) . '/Component/',
        'controllerDir'        => dirname(dirname(__DIR__)) . '/Controller/',
        'configDir'            => dirname(dirname(__DIR__)) . '/Config/',
        'fractalDir'           => dirname(dirname(__DIR__)) . '/Fractal/',
        'helperDir'            => dirname(dirname(__DIR__)) . '/Helper/',
        'modelsDir'            => dirname(dirname(__DIR__)) . '/Models/',
        'viewsDir'             => dirname(dirname(__DIR__)) . '/Views/',
        'logsDir'              => dirname(dirname(__DIR__)) . '/Logs/',
    ],
    'jwt'         => [
        'private_key' => dirname(dirname(__DIR__)) . '/Private/jwt/' . ENVIRONMENT . '/rsa_private_key.pem',
        'public_key'  => dirname(dirname(__DIR__)) . '/Private/jwt/' . ENVIRONMENT . '/rsa_public_key.pem'
    ],
]);