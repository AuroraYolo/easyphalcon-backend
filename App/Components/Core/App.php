<?php
namespace Backend\Components\Core;
use \Phalcon\Di;
class App {
    /**
     * @var Di $di
     */
    private static $di;

    /**
     * @var \Phalcon\Logger\AdapterInterface
     */
    private static $logger;

    /**
     * @var 全局配置
     */
    private static $globalConfig = null;

    /**
     * @var \Phalcon\Cache\Backend\Memcache
     */
    private static $memcache;

    /**
     * @var \Redis
     */
    private static $redis;

    /**
     * 获取DI对象
     *
     */
    public static function getDI() {
        if(self::$di == null) {
            self::$di = (new DI())->getDefault();
        }
        return self::$di;
    }

    /**
     * 获取全局的配置
     *
     *
     * @return \Phalcon\Config
     */
    public static function globalConfig() {
        if(is_null(self::$globalConfig)) {
            self::$globalConfig = self::getDI()->getShared('globalConfig');
        }
        return self::$globalConfig;
    }

    /**
     * 获取memcache对象
     *
     * @return \Phalcon\Cache\Backend\Memcache
     * @throws \Exception
     */
    public static function memCache() {
        if(self::$memcache == null) {
            $mem = self::getDI()->getShared('memCache');
            if(is_object($mem)) {
                self::$memcache = $mem;
                return self::$memcache;
            }
            throw new \Exception('系统严重错误，获取[memcache]失败');
        }
        return self::$memcache;
    }

    /**
     * 返回redis对象
     *
     * @return \Redis
     * @throws \Exception
     */
    public static function redis() {
        if(self::$redis == null) {
            $redis = self::getDI()->getShared('redis');
            if(is_object($redis)) {
                self::$redis = $redis;
                return self::$redis;
            }
            throw new \Exception('系统严重错误，获取[redis]失败');
        }
        return self::$redis;
    }

    /**
     * 返回日志操作的功能
     * 使用方法
     * App::Logger()->error('msg');
     * App::Logger()->info('msg');
     * App::Logger()->notice('msg');
     * App::Logger()->warning('msg');
     * App::Logger()->debug('msg');
     *
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function Logger() {
        if(self::$logger == null) {
            self::$logger = self::getDI()->getShared('logger');
        }
        return self::$logger;
    }

}