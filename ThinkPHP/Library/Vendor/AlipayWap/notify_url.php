<?php

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();
if($verify_result) {//验证成功

	$out_trade_no = $_POST['out_trade_no'];

	//支付宝交易号

	$trade_no = $_POST['trade_no'];

	//交易状态
	$trade_status = $_POST['trade_status'];


    if($_POST['trade_status'] == 'TRADE_FINISHED') {	
		if(strpos($out_trade_no,'s9r')!==false)
		{
			$ch = curl_init();
			$url='http://www.czdidai.com/Czdd/Zqhd/ali_notify_url';
			$post_data=array();
			$post_data['out_trade_no']=$_POST['out_trade_no'];
			$post_data['trade_no']=$_POST['trade_no'];
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$output = curl_exec($ch);
			curl_close($ch);				
		}
		else
		{
			$ch = curl_init();
			$url='http://www.czdidai.com/Czdd/Weweb/ali_notify_url';
			$post_data=array();
			$post_data['out_trade_no']=$_POST['out_trade_no'];
			$post_data['trade_no']=$_POST['trade_no'];
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$output = curl_exec($ch);
			curl_close($ch);			
		}
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		if(strpos($out_trade_no,'s9r')!==false)
		{
			$ch = curl_init();
			$url='http://www.czdidai.com/Czdd/Zqhd/ali_notify_url';
			$post_data=array();
			$post_data['out_trade_no']=$_POST['out_trade_no'];
			$post_data['trade_no']=$_POST['trade_no'];
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$output = curl_exec($ch);
			curl_close($ch);				
		}
		else
		{
			$ch = curl_init();
			$url='http://www.czdidai.com/Czdd/Weweb/ali_notify_url';
			$post_data=array();
			$post_data['out_trade_no']=$_POST['out_trade_no'];
			$post_data['trade_no']=$_POST['trade_no'];
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$output = curl_exec($ch);
			curl_close($ch);			
		}
    }
}
else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>