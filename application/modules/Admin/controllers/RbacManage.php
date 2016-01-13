<?php
/**
 * User: zhouwenlong
 * Date: 2015/12/28
 */
class RbacManageController extends Admin\AdminCommonController
{

    /**
     * @var \Service\rbac\Manage;
     */
    public $rbacManage;

    public function init()
    {
        parent::init();
        $this->rbacManage = \Core\ServiceLocator::rbacManage();
    }


    /**
     * 权限分配
     * @return bool
     */
    public function indexAction()
    {
        $result  =  $this->rbacManage->getItems( $this->rbacManage->itemFormatForZTree() );
        $userDic = $this->rbacManage->getUserDic();
        $this->getView()->roleList       = $result['roleList'];
        $this->getView()->permissionList = $result['permissionList'];
        $this->getView()->userDic        = $userDic;
        return true;
    }

    /**
     * 添加用户
     * @return bool
     */
    public function addUserAction()
    {
        if($this->_request->isGet()){
            return true;
        }

        $post = $this->post();
        try{
            $this->rbacManage->addUser($post['name'], $post['email'], $post['pwd']);
        }catch(\Exception $e){
            return $this->showMsg( $e->getMessage() );
        }

        return $this->showMsg('添加成功');
    }

    public function updateUserAction()
    {
        if($this->_request->isGet()){
            $id = $this->get('id');
            $result = $this->rbacManage->getUserInfo($id);
            $this->getView()->assign('userInfo', $result);
            $this->getView()->assign('updateSence', 1);
            return $this->displayWithLayout('addUser');
        }

        $post = $this->post();
        $result = $this->rbacManage->updateUser($post);
        if($result === false){
            return $this->showMsg('修改失败');
        }else{
            return $this->showMsg('修改成功');
        }
    }

    public function delUserAction()
    {
        $id = $this->get('id');
        $result = $this->rbacManage->delUser($id);
        if($result){
            return $this->showMsg('删除成功');
        }else{
            return $this->showMsg('删除失败');
        }
    }

    /**
     * 用户绑定角色
     */
    public function userAssignRoleAction()
    {
        $post = $this->post();

        $rs = $this->rbacManage->userAssignRole($post['userId'], $post['roles']);
        if($rs){
            $this->ajaxResponse('', 1, '绑定成功');
        }else{
            $this->ajaxResponse('', -1, '绑定失败');
        }

        return false;
    }

    /**
     * 获取用户角色
     */
    public function getUserRoleAction()
    {
        $userId = $this->get('uid');
        $rs = $this->rbacManage->getUserRole($userId);
        if($rs === false){
             $this->ajaxResponse('', -1);
        }else{
             $this->ajaxResponse($rs);
        }

        return false;
    }

    /**
     * 角色分配权限
     * @return bool
     */
    public function roleAssignPermissionAction()
    {
        if($this->_request->isPost()){
            $post = $this->post();
            $post['itemIds'] = isset($post['itemIds']) ? $post['itemIds'] : [];
            $rs = $this->rbacManage->roleAssignPermission($post['roleId'], $post['itemIds']);
            if($rs == false) {
                $this->ajaxResponse('', -1, '权限分配失败');
            }else{
                $this->ajaxResponse('', 1, '权限分配成功');
            }
            return false;
        }

        $result = $this->rbacManage->getPermissionList();
        $this->getView()->dataList = $result['permissionList'];
        $this->getView()->roleList = $result['roleList'];
        return true;
    }


    /**
     * @return bool
     */
    public function roleGetPermissionsAction()
    {
        $roleId = $this->get('roleId');
        $rolePermissions = $this->rbacManage->roleGetPermissions($roleId);
        return $this->ajaxResponse($rolePermissions);
    }

    public function itemListAction()
    {
        //FIXME: 写个form辅助类，减少如下代码的编写
        $params['type']  = $this->get('type');

        $whereCondition = $where = [];
        if($params['type'] == \RbacItemModel::ITEM_TYPE_ROLE){
            $whereCondition['type'] = $params['type'];
        }elseif($params['type'] == 'nodes'){
            $whereCondition['type'] = [
                \RbacItemModel::ITEM_TYPE_FPERMISSION,
                \RbacItemModel::ITEM_TYPE_PERMISSION
            ];
        }
//        $where['ORDER'] = ['type DESC'];
        PC::debug($whereCondition);
        $result = $this->rbacManage->getItemsGroup('*', $whereCondition);
        $this->getView()->dataList = $result;
        $this->getView()->params = $params;

        return true;
    }

    public function userListAction()
    {
        $params['page']   = $this->get('page');
        $params['name']   = $this->get('name');
        $params['email']  = $this->get('email');

        $whereCondition = [];
        if($params['name']){
            $whereCondition['name[~]']   = $params['name'];
        }
        if($params['email']){
            $whereCondition['email[~]']  = $params['email'];
        }
        if($whereCondition){
            $where['AND']   = $whereCondition;
        }
        $where["LIMIT"] = Util_Pagination::getLimit($params['page']);
        $result = $this->rbacManage->getUserList('*', $where);
        Util_Pagination::config($result['count'], Util_Helper::url('rbacmanage', 'userList'), $params['page'], $whereCondition);
        $this->getView()->dataList = $result;
        $this->getView()->params   = $params;

        return true;
    }

    public function getItemParentNodesAction()
    {
        $pNodes = $this->rbacManage->getItemParentNodes();
        if($pNodes === false){
            return $this->ajaxResponse('', -1, '无法获取节点数据');
        }
        if(!$pNodes){
            return $this->ajaxResponse('', -2, '暂无父节点数据，请先添加');
        }

        return $this->ajaxResponse($pNodes);
    }

    public function addItemAction()
    {
        if( $this->_request->isPost() ){
            $post = $this->post();
            try{
                $result = $this->rbacManage->addItem($post);
            }catch(\Exception $e){
                return $this->ajaxResponse('', -1, $e->getMessage());
            }
            return $this->ajaxResponse('', 1, '添加成功');
        }

        return true;
    }

    public function delItemAction()
    {
        $nodeType = $this->post('nodeType');
        $id = $this->post('id');
        if($nodeType == 'pnode'){
            $where = [
              'OR' => [
                  'id' => $id,
                  'fid' => $id,
              ],
            ];
        }elseif($nodeType == 'snode'){
            $where = [
                    'id' => $id,
            ];
        }
        $result = $this->rbacManage->delItem($where);
        if(!$result){
            $this->ajaxResponse('', -1, "删除错误");
        }else{
            $this->ajaxResponse('', 1, "删除成功");
        }

        return false;
    }

    public function updateItemAction()
    {
        //TODO:实现更新
    }
}