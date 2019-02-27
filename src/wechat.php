<?php
namespace Itkee/WechatSDK;

class WeChat{
	private $appid;
	private $appsecret;
	private $redirect_url;

	public function __construct($appid, $appsecret,$redirect_url)
    {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
        // $this->redirect_url = $redirect_url;
        $this->redirect_url = request()->url();
    }

    /**
     * 微信登录
     */
    public function wx_pc_login(){
        $appid = $this->appid;
        $redirect_uri = $this->appsecret;
        $redirect_uri = urlencode($this->redirect_url);
        if(!$_GET['code']){
			//获取code
	        $wx_login_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
	        header('Location:'.$wx_login_url);
        }else{
        	$code = $_GET['code'];
	        //获取access_token
	        $get_access_token = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
	        $data = http_request($get_access_token);
	        $access_token_info = json_decode($data,true);
	        //获取用户信息
	        $get_user_info = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token_info['access_token']}&openid={$access_token_info['openid']}&lang=zh_CN";
	        $user_info = http_request($get_user_info);
	        $user_info = json_decode($user_info,true);
	        return $user_info;
        }
        
    }

}
function http_request($url){
    //初始化
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);
    //打印获得的数据
    return $output;
}

?>