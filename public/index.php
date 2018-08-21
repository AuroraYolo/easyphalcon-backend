<?php
namespace Backend;

use Backend\Bootstrap\Bootstrap;
use Backend\Constant\Services;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Di\FactoryDefault;
use Phalcon\Exception;
use Phalcon\Http\Response;

date_default_timezone_set('Etc/GMT-8');

//定义常量
define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
define('APP_PATH', BASE_PATH . '/APP');

if (!extension_loaded('phalcon')) {
    exit("Please install phalcon extension. See https://phalconphp.com/zh/ \n");
}

//检查配置文件
if (!file_exists(BASE_PATH . '/.env')) {
    exit("Please check the configuration file \n");
}

try {
    $envConfig = new Ini(BASE_PATH . '/.env');
    define('ENVIRONMENT', $envConfig['ENVIRONMENT']);
    if (defined('ENVIRONMENT')) {
        switch ((ENVIRONMENT)) {
            case 'development':
                error_reporting(E_ALL);
                ini_set('display_errors', 'On');
                break;
            case  'testing':
                error_reporting(E_ALL);
                ini_set('display_errors', 'On');
                break;
            case 'production':
            default:
                error_reporting(0);
                ini_set('display_errors', 'Off');
                break;
        }
    }
    include_once APP_PATH . '/Bootstrap/Bootstrap.php';
    $app = new Bootstrap($envConfig);
    $app->handle();
    /**
     *
     * @var Response $response
     */
    $response  = $app->di->getShared(Services::RESPONSE);
    $returnVal = $app->dispatcher->getReturnedValue();
    if ($returnVal !== null) {
        if (is_string($returnVal)) {
            $response->setContent($returnVal)->send();
        } else {
            $response->setJsonContent($returnVal)->send();
        }
    } else {
        echo $app->handle()->getContent();
    }
} catch (\Throwable $ex) {
    $di       = $app->di ?? new FactoryDefault();
    $response = $di->getShared(Services::RESPONSE);
    if (!$response || !$response instanceof Response) {
        $response = new Response();
    }
    $isDebug = ENVIRONMENT == 'development' ? true : false;
    echo $ex->getMessage();
    //TODO 开启调试模式打印错误信息
} catch (Exception $ex) {
    echo $ex->getMessage();
}
finally {

}