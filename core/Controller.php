<?php
namespace Core;

/**
 * 基类控制器，实现常用控制器方法，xss clean、设置布局 etc
 *
 * @package Core
 * @author zhoushen <445484792@qq.com>
 * @since 1.0
 */
abstract class Controller extends \Yaf\Controller_Abstract{

    /**
     * @var string 信息提示模板
     */
    public $defaultMsgTemplate = 'error/showmsg.phtml';
    /**
     * @var string 布局文件
     */
    public $layout;

    public function init()
    {
        //do not call render for ajax request
        if ($this->getRequest()->isXmlHttpRequest()) {
            \Yaf\Dispatcher::getInstance()->autoRender(FALSE);
        }
        //use layout
        if($this->layout){
            $this->setLayout($this->layout);
        }
    }

    /**
     * 设置布局
     * @param string $layout 布局文件
     */
    public function setLayout($layout)
    {
        $this->getResPonse()->layout = function($content) use ($layout){
            //$content子视图的内容
            return $this->getView()->render($layout, ['content'=>$content]);
        };
    }

    /**
     * 带布局渲染页面
     * @param $tpl
     */
    public function displayWithLayout($tpl)
    {
        $this->getResponse()->setBody( $this->render($tpl) );
        return false;
    }

    /**
     * 路由重定向
     * @param $c
     * @param string $a
     * @param string $m
     * @param array $params
     * @param string $routerType
     */
    public function redirectTo($c, $a = 'index', $m = 'default', array $params = [],  $routerType = 'default')
    {
        if($m == 'default'){
            $m = $this->_request->getModuleName();
        }
        $url = \Util_Helper::url($c, $a, $m, $params, $routerType);
        $this->redirect($url);
        die;
    }

    /**
     * ajax请求响应内容
     * @param int $code
     * @param string $msg
     * @param string $content
     */
    public function ajaxResponse($content = '', $code = 1, $msg = 'success'){
        echo json_encode([
                'code' => $code,
                'msg'  => $msg,
                'content' => $content,
            ]);
        return false;
    }

    /**
     * 显示消息提示信息
     * @param $msg
     * @param null $toUrl
     * @param int $timeOut
     * @return bool
     */
    public function showMsg($msg, $toUrl = null, $timeOut = 3 ,$template = null){
        if(!$template) $template = $this->defaultMsgTemplate;
        $this->getView()->display($template,
                                    [
                                        'message'=>$msg, 
                                        'toUrl'=>$toUrl, 
                                        'time'=>$timeOut
                                     ]
                                );
        //response body equal ''
        return false;
    }

    /**
     * @param null $name
     * @return mixed|null
     */
    public function get($name = null)
    {
        //静态路由没有$_GET
        if(!$_GET){
            $_GET = $this->getRequest()->getParams();
        }

        if(!$name){
            $data = $_GET;
        }elseif(isset($_GET[$name])){
            $data = $_GET[$name];
        }else{
            return false;
        }

        return $this->xssClean($data);
    }

    /**
     * @param null $name
     * @return mixed|null
     */
    public function post($name = null)
    {

        if(!$name){
            $data = $_POST;
        }elseif(isset($_POST[$name])){
            $data = $_POST[$name];
        }else{
            return false;
        }

        return $this->xssClean($data);
    }

    /**
     * @param null $name
     * @return bool|mixed
     */
    public function cookie($name = null)
    {
        if(!$name){
            $data = $_COOKIE;
        }elseif(isset($_COOKIE[$name])){
            $data = $_COOKIE[$name];
        }else{
            return false;
        }

        return $this->xssClean($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function xssClean($data)
    {
        if(is_array($data)){
            return filter_var_array($data, FILTER_SANITIZE_STRING);
        }else{
            return filter_var($data, FILTER_SANITIZE_STRING);
        }
    }
}