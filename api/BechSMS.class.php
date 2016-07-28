<?php
/**
 * bechsms短信发送类
 * 感谢用户@jason提供。
 * 2013-12-02
 */
class BechSMS {
	private $akey = '3799'; //开发者选项信息设置的accesskey
	private $skey = 'd5d35829ac0c2ccd25bf447d709fc462c29b83e3';  //开发者选项信息设置的secretkey
	
	/**
	 * 发送短信
	 * @param string mtel 手机号码
	 * @param string content 发送的内容 
	 * @param string charset 您网站或者应用的字符编码，可选 utf8 gbk gb2312，默认utf8
	 */
	function sendmsg($mtel,$content,$charset='utf8'){
		if($charset!='utf8')
			$content = trim(mb_convert_encoding($content,'UTF-8','GBK'));
		$url = "http://sms.bechtech.cn/Api/send/data/json?accesskey=$this->akey&secretkey=$this->skey&mobile=$mtel&content=".urlencode($content);
		 $json = $this->curl_get($url);
		 $arr = json_decode($json,true); //格式化返回数组
		 return $arr;
	}
	
	
	//curl get
	function curl_get($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$dom = curl_exec($ch);
		curl_close($ch);
		return $dom;
	}
	
	//curl post
	function curl_post($url,$postdate){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_POST,true);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$postdate);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$result=curl_exec($curl);
		return $result;
	}

}
