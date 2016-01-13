<?php
/**
 * 布局插件
 * 用来控制器渲染视图的时候使用布局
 *
 * @author zhoushen
 * @since 2015/10/14
 */
class LayoutPlugin extends Yaf\Plugin_Abstract {

	public function postDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
		//disableView or return false in controller or empty template file will lead response body eq ''
		if(isset($response->layout) && !empty($response->getBody()) ){
			$response->setBody( call_user_func( $response->layout, $response->getBody() ) );
		}
	}
}
