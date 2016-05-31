<?php
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号

	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];

	if(strpos($out_trade_no,'s9r')!==false)
	{
		$ch = curl_init();
		$url='http://www.czdidai.com/Czdd/Zqhd/ali_return_url';
		$post_data=array();
		$post_data['out_trade_no']=$_GET['out_trade_no'];
		$post_data['trade_no']=$_GET['trade_no'];
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		curl_close($ch);
		echo $output;			
	}
	else
	{
		$ch = curl_init();
		$url='http://www.czdidai.com/Czdd/Weweb/ali_return_url';
		$post_data=array();
		$post_data['out_trade_no']=$_GET['out_trade_no'];
		$post_data['trade_no']=$_GET['trade_no'];
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		curl_close($ch);
		echo $output;	
	}
}
?>