<?php
class Bootstrap extends Yaf\Bootstrap_Abstract{

    private $config;
    private $app;

    public function _init(Yaf\Dispatcher $dispatcher){
        \Yaf\Session::getInstance()->start();
        $this->app = Yaf\Application::app();
    }

    public function _initConfig(Yaf\Dispatcher $dispatcher) {
        $this->config = $this->app->getConfig()->toArray();
	}

    public function _initLoader(Yaf\Dispatcher $dispatcher){
        //注册本地类(先找项目library再找全局library)
        $loader = Yaf\Loader::getInstance();
        $loader->registerLocalNamespace(array('App'));
    }

    public function _initService(Yaf\Dispatcher $dispatcher)
    {
        $service = Core\ServiceLocator::getInstance();
        //db
        $service->set('db', function(){
            return new Core\Connection($this->config['db']);
        });
        //log
        $service->set('log', function(){
            $logConfig = $this->config['log'];
            $log = new Monolog\Logger($logConfig['loger']);
            $log->pushHandler(
                new Monolog\Handler\StreamHandler($logConfig['path'].'/'.date('Y-m-d').'.log',
                    Monolog\Logger::ERROR)
            );
            return $log;
        });
        //rbac管理服务
        $service->set('rbacManage', function(){
           return new Service\rbac\Manage();
        });
    }

	public function _initPlugin(Yaf\Dispatcher $dispatcher) {

        //开启debug控制台输出
        if(APP_DEBUG){
            $dispatcher->registerPlugin(new DebugPlugin());
        }

        //开启布局支持
        $dispatcher->registerPlugin(new LayoutPlugin());

        //开启性能分析
        if( APP_ANALYZE && function_exists('xhprof_enable') ) {
            $dispatcher->registerPlugin(new XhprofPlugin());
        }
	}

	public function _initRoute(Yaf\Dispatcher $dispatcher) {
        /*
        //http://www.laruence.com/manual/yaf.routes.static.html#yaf.routes.simple
        //路由注册的顺序很重要, 最后注册的路由协议, 最先尝试路由, 这就有个陷阱. 请注意.
        $route = new Yaf\Route\Rewrite('/', [
                'module' => 'index',
                'controller' => 'index',
                'action' => 'index',
            ]);

        $dispatcher->getRouter()->addRoute('test', $route);
        */
	}

	public function _initView(Yaf\Dispatcher $dispatcher){
	}

    /**
     * 线上
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initErrorHandle(Yaf\Dispatcher $dispatcher){
        if(APP_DEBUG){
            return true;
        }
        set_error_handler(function($errno, $errstr, $errfile, $errline){
            //TODO:设置显示errorhandle
        });
        register_shutdown_function(function(){
            $error = error_get_last();
            if($error){
            }
        });
    }
}
