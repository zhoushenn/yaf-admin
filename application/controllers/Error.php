<?php
/**
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 */
class ErrorController extends Yaf\Controller_Abstract {

	public function errorAction($exception) {
		if(APP_DEBUG){
			echo "<pre><h3>$exception</h3>";
			echo "<h3>GET:</h3>";
			var_export($_GET);
			echo "<h3>POST:</h3>";
			var_export($_POST);
			return false;
		}
		$code    = $exception->getCode();
        $message = $exception->getMessage();
		if($exception instanceof Yaf\Exception){
			$message = sprintf("发生一个系统错误(code=%d)，请联系站长", $code);
		}else{
			$message = sprintf("发生一个用户错误(code=%d), %s", $code, $message);
		}
        $this->getView()->message = $message;
		return true;
	}
}
