<?php
namespace Admin;
/**
 * 后台通用控制器 加了rbac
 * Class AdminCommonController
 * @package Admin
 */
class AdminCommonController extends \CommonController{

    public $layout = 'layout/adminLayout.phtml';
    public $defaultMsgTemplate = 'common/showmsg.phtml';
    /**
     * @var boolean 是否开启用户验证 authenticate
     */
    public $doAuth = true;
    /**
     * @var bool 是否检查权限
     */
    public $checkAuthorization = true;

    /**
     * @var string 当前请求的权限节点
     */
    public $currentRequest = '';

    public function init(){
        parent::init();

        $currentRequest = '/' . strtolower( implode('/', [
                $this->_request->module,
                $this->_request->controller,
                $this->_request->action,
            ]) );
        $this->getView()->currentRequest = $currentRequest;
        if($this->checkAuthorization && $this->userInfo){
            $rbacManage = \Core\ServiceLocator::getInstance()->get('rbacManage');
            if( ! $rbacManage->isAdmin( $this->userInfo['name'] ) ) {
                $authInfo = $rbacManage->checkAuthorization($this->userInfo['id'], $currentRequest);
                if ($authInfo == false) {
                    throw new \Exception('没有节点访问权限');
                }
            }
        }
    }
}