<?php
namespace Service\user;
abstract class UserAuthAbstract{

    public abstract function login($account, $password, $expire=3600);

    public abstract function logOut();

	public abstract function signIn($data);

	public abstract function valid($account, $password);

    public abstract function validByToken($token);

    protected function generateToken($account){
    	return md5($account . time());
    }

    public abstract function setRemberMeToken($userId, $token, $expire);

    public abstract function delRemberMeToken();

}
