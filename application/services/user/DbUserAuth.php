<?php
namespace Service\user;

use Exception;
use Yaf;

/**
 * 用户验证服务类
 * Class DbUserAuth
 * @package Service\user
 */
class DbUserAuth extends UserAuthAbstract{

    protected $userModel;
    const TOKEN_NAME = 'rmk';

    public function __construct(){
        $this->userModel = new \UserModel();
    }

    /**
     * 验证用户信息
     * @param  [type] $account  [description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public function valid($account, $password){
        $userinfo = $this->userModel->getInfoByEmail($account);
        if( ! $userinfo || $userinfo['pwd'] != md5($password) ){
            throw new Exception('用户名或密码错误');
        }
        
        return $userinfo;
    }

    /**
     * 登陆操作
     * @param  [type]  $account  [description]
     * @param  [type]  $password [description]
     * @param  integer $expire   [description]
     * @return [type]            [description]
     */
    public function login($account, $password, $remember = false, $expire = 3600){
        $userinfo = $this->valid($account, $password);
        $session  = Yaf\Session::getInstance();
        $session['userinfo'] = $userinfo; 

        if($remember == true){
            $token    = $this->generateToken($userinfo['name']);
            $this->setRemberMeToken($userinfo['id'], $token, time() + $expire);
        }
        
        return $userinfo;
    }

    /**
     * @param $name
     * @param $email
     * @param $pwd
     */
    public function register($name, $email, $pwd)
    {
        //TODO:datacheck

        $where = [
            'OR' => [
                'name' => $name,
                'email' => $email,
            ],
            'LIMIT' => [1],
        ];
        $result = $this->userModel->select(['name','email'], $where);
        foreach($result as $item){
            if($item['name'] == $name){
                throw new \InvalidArgumentException('该用户名已被注册');
            }
            if($item['email'] == $email){
                throw new \InvalidArgumentException('该邮箱已被注册');
            }
        }
        $result = $this->userModel->insert([
            'name'  =>  $name,
            'email' => $email,
            'pwd'   => md5($pwd),
            'ctime' => time(),
        ]);
        if($result === false){
            throw new \Exception('无法创建用户');
        }

        return true;
    }

    /**
     * 登出
     * @return [type] [description]
     */
    public function logOut(){
        $this->delRemberMeToken();
        session_destroy();
    }

    /**
     * 注册
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function signIn($data){
        $data['pwd'] = md5($data['pwd']);
        if( false === $this->userModel->insert($data) ){
           throw new Exception('注册失败');
        }
        return true;
    }


    public function validByToken($token)
    {
        // TODO: Implement validByToken() method.
    }

    public static function getUserBySession(){
        $session = Yaf\Session::getInstance();
        if( ! isset($session['userinfo']) ){
            return false;
        }
        return $session['userinfo'];
    }

    public function getUserByToken($token){
        $userinfo  = $this->userModel->getInfoByToken($token);

        if(isset($userinfo)){
            $session  = Yaf\Session::getInstance();
            $session['userinfo'] = $userinfo; 
            return $userinfo;
        }
        return false;
    }

    public function generateToken($name)
    {   
        return md5($name.time());
    }

    /**
     * 
     * @param [type] $userId [description]
     * @param [type] $token  [description]
     * @param [type] $expire [description]
     */
    public function setRemberMeToken($userId, $token, $expire)
    {
        if( ! setcookie(self::TOKEN_NAME, $token, $expire, '/') ){
           throw new Exception("无法设置cookie");
        }

        $result =  $this->userModel->setToken($userId, $token);
        if($result === false){
            $this->delRemberMeToken();
            throw new Exception("无法更新用户token");
        }

        return true;
    }

    public function delRemberMeToken()
    {
        $result = setcookie(self::TOKEN_NAME, null, -1, '/');
        return $result;
    }
}