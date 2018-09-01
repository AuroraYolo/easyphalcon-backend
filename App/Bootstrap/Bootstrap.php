<?php
namespace Backend\Bootstrap;

use Backend\Components\Acl\Access;
use Backend\Components\Http\ErrorHelper;
use Backend\Components\Http\Request;
use Backend\Components\Http\Response;
use Backend\Constant\Services;
use Backend\Middleware\AclMiddleWare;
use Backend\Middleware\AuthTokenMiddleWare;
use Backend\Middleware\NotFoundMiddleWare;
use Phalcon\Cache\Backend\Libmemcached;
use Phalcon\Config;
use Phalcon\Crypt;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Db\Profiler;
use Phalcon\Di\FactoryDefault as Di;
use Phalcon\Di\Service;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\Manager as ModelManager;
use Phalcon\Mvc\Model\MetaData\Files as MetaDataFile;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Simple as SimpleView;
use Redis;
use Phalcon\Cache\Backend\Memcache;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Logger\Adapter\File as LoggerAdapterFile;
use Phalcon\Logger\Formatter\Line as LineFormatter;

class Bootstrap extends Application
{
    /**
     * @var Config $config
     */
    private $config = null;

    public function __construct(Config $config)
    {
        if (is_null($this->config)) {
            $this->config = $config->merge(include_once APP_PATH . '/Config/Config.php');
        }
        $this->registerAutoLoaders($this->config);
        $this->registerServices($this->config);
    }

    /**
     * @description Register autoloaders
     *
     * @param Config $config
     */
    protected function registerAutoLoaders(Config $config)
    {
        $loader = new Loader();
        $loader->registerDirs([
            'bootstrapDir'  => $config->application->bootstrapDir,
            'commonsDir'    => $config->application->commonsDir,
            'controllerDir' => $config->application->controllerDir,
            'componentDir'  => $config->application->componentDir,
            'configDir'     => $config->application->configDir,
            'fractalDir'    => $config->application->fractalDir,
            'helperDir'     => $config->application->helperDir,
            'modelsDir'     => $config->application->modelsDir,
            'viewsDir'      => $config->application->viewsDir,
            'logsDir'       => $config->application->logsDir,
        ]);
        $loader->registerNamespaces([
            'Backend' => APP_PATH
        ]);
        $loader->register();
    }

    /**
     * @description Register services
     *
     * @param Config $config
     */
    protected function registerServices(Config $config)
    {
        $di = new Di();
        $di->setShared(Services::CONFIG, $config);
        $di->setShared(Services::ROUTER, function ()
        {
            return include APP_PATH . '/Config/Router.php';
        });
        $di->setShared(Services::ACL, new Access);
        $di->setShared(Services::REQUEST, new Request);
        $di->setShared(Services::RESPONSE, new Response);
        $di->setShared(Services::DISPATCHER, function () use ($config)
        {
            $dispatcher    = new Dispatcher();
            $eventsManager = new EventsManager();
            $eventsManager->attach('dispatch', new AclMiddleWare);
            $eventsManager->attach('dispatch', new AuthTokenMiddleWare);
            $eventsManager->attach('dispatch', new NotFoundMiddleWare);
            $dispatcher->setEventsManager($eventsManager);
            $dispatcher->setDefaultNamespace("Backend\\Controllers\\");
            return $dispatcher;
        });
        $di->setShared(Services::SIMPLE_VIEW, function () use ($config)
        {
            $view = new SimpleView();
            $view->setViewsDir($config->application->viewsDir);
            $view->registerEngines(
                [
                    ".phtml" => "Phalcon\\Mvc\\View\\Engine\\Php",
                    '.html'  => 'Phalcon\Mvc\View\Engine\Php',
                    ".volt"  => "Phalcon\\Mvc\\View\\Engine\\Volt",
                ]
            );
            return $view;
        });
        $di->set(Services::VIEW, function () use ($config)
        {
            $view = new View();
            $view->setViewsDir($config->application->viewsDir);
            $view->registerEngines(
                [
                    ".phtml" => "Phalcon\\Mvc\\View\\Engine\\Php",
                    '.html'  => 'Phalcon\Mvc\View\Engine\Php',
                    ".volt"  => "Phalcon\\Mvc\\View\\Engine\\Volt",
                ]
            );
            $view->disableLevel([
                View::LEVEL_BEFORE_TEMPLATE => true,
                View::LEVEL_AFTER_TEMPLATE  => true,
                //View::LEVEL_LAYOUT => true,
                View::LEVEL_MAIN_LAYOUT     => true,
                View::LEVEL_ACTION_VIEW     => true,
            ]);
            return $view;
        }, true);
        $di->setShared(Services::DB, function () use ($config)
        {
            $db = new DbAdapter([
                'host'     => $config->dbMaster->host,
                'username' => $config->dbMaster->username,
                'password' => $config->dbMaster->password,
                'dbname'   => $config->dbMaster->dbname,
                'charset'  => 'utf8mb4',
                'options'  => [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            ]);

            if (defined('ENVIRONMENT') && ENVIRONMENT == 'development') {
                $eventsManager = new EventsManager();
                $profiler      = new Profiler();
                $eventsManager->attach('db', function ($event, $connection) use ($profiler, $config)
                {
                    if ($event->getType() == 'beforeQuery') {
                        $profiler->startProfile($connection->getSQLStatement());
                    }
                    if ($event->getType() == 'afterQuery') {
                        $profiler->stopProfile();
                        $profile = $profiler->getLastProfile();
                        //获取sql对象
                        $sql = $profile->getSqlStatement();
                        //获取查询参数
                        $params = $profile->getSqlVariables();
                        $params = json_encode($params);
                        //获取执行时间
                        $executeTime = $profile->getTotalElapsedSeconds();
                        $profiler->reset();
                        $maxExecuteTime = isset($config->db->max_execute_time) ?? 0;
                        $scale          = intval($config->db->scale);
                        if (bccomp($executeTime, $maxExecuteTime, $scale) != -1) {
                        }
                    }
                });
                $db->setEventsManager($eventsManager);
                return $db;
            }
        });
        $di->setShared('dbSlave', function () use ($config)
        {
            $db = new DbAdapter([
                'host'     => $config->dbSlave->host,
                'username' => $config->dbSlave->username,
                'password' => $config->dbSlave->password,
                'dbname'   => $config->dbSlave->dbname,
                'charset'  => 'utf8mb4',
                'options'  => [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            ]);

            if (defined('ENVIRONMENT') && ENVIRONMENT == 'development') {
                $eventsManager = new EventsManager();
                $profiler      = new Profiler();
                $eventsManager->attach('db', function ($event, $connection) use ($profiler, $config)
                {
                    if ($event->getType() == 'beforeQuery') {
                        $profiler->startProfile($connection->getSQLStatement());
                    }
                    if ($event->getType() == 'afterQuery') {
                        $profiler->stopProfile();
                        $profile = $profiler->getLastProfile();
                        //获取sql对象
                        $sql = $profile->getSqlStatement();
                        //获取查询参数
                        $params = $profile->getSqlVariables();
                        $params = json_encode($params);
                        //获取执行时间
                        $executeTime = $profile->getTotalElapsedSeconds();
                        $profiler->reset();
                        $maxExecuteTime = isset($config->db->max_execute_time) ?? 0;
                        $scale          = intval($config->db->scale);
                        if (bccomp($executeTime, $maxExecuteTime, $scale) != -1) {
                        }
                    }
                });
                $db->setEventsManager($eventsManager);
                return $db;
            }
        });
        $di->setShared(Services::LOG, function () use ($config)
        {
            $logger = new LoggerAdapterFile($config->application->logsDir . date('Ymd') . '.log');
            $logger->setFormatter(new LineFormatter('[%date%][%type%] [%message%]', 'Y-m-d H:i:s'));
            return $logger;
        });
        $di->setShared(Services::REDIS_CACHE, function () use ($config)
        {
            $redis = new Redis();
            $redis->connect($config->redis->host, $config->redis->port);
            $redis->setOption(Redis::OPT_PREFIX, $config->redis->prefix);
            return $redis;
        });
        $di->setShared(Services::MEMCACHE_CACHE, function () use ($config)
        {
            $frontCache = new FrontData([
                [
                    "lifetime" => 172800,
                ]
            ]);
            if (extension_loaded('memcached')) {
                $memcache = new Libmemcached($frontCache, [
                    "servers" => [
                        [
                            "host"   => $config->memcache->host,
                            "port"   => $config->memcache->port,
                            "weight" => 1,
                        ],
                    ],
                    "client"  => [
                        \Memcached::OPT_HASH       => \Memcached::HASH_MD5,
                        \Memcached::OPT_PREFIX_KEY => "prefix.",
                    ]
                ]);
            } else {
                $memcache = new Memcache($frontCache, [
                    "host"       => $config->memcache->host,
                    "port"       => $config->memcache->port,
                    "persistent" => false,
                ]);
            }
            return $memcache;
        });
        $di->set(Services::URL, function () use ($config)
        {
            $url = new Url();
            $url->setBasePath('');
            $url->setBaseUri('');
            $url->setStaticBaseUri('');
            return $url;
        });
        $di->set(Services::CRYPT, function () use ($config)
        {
            $crypt = new Crypt();
            $crypt->setCipher('AES-256-CBC');
            $crypt->setKey(md5(Services::CRYPT_KEY));
            return $crypt;
        });

        $di->set(Services::MODELS_METADATA, function () use ($config)
        {
            return new MetaDataFile([
                'metaDataDir' => APP_PATH . '/Cache/Metadata/'
            ]);
        });
        $di->set(Services::EVENTS_MANAGER, function ()
        {
            $eventsManager = new EventsManager();
            return $eventsManager;
        }, true);
        $di->set(Services::MODELS_MANAGER, function () use ($di)
        {
            $modelManager = new ModelManager();
            $modelManager->setEventsManager($di->get(Services::EVENTS_MANAGER));
            return $modelManager;
        }, true);
        $di->setShared(Services::ERROR_HELPER, new ErrorHelper);
        $this->setDI($di);
    }
}