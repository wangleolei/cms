<?php
/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string){
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string) {
	if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES);
	foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
	return $string;
}
/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('%2527','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace('"','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	$string = str_replace('\\','',$string);
	$string = remove_xss($string);
	return $string;
}

/**
 * xss过滤函数
 *
 * @param $string
 * @return string
 */
function remove_xss($string) { 
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

    $parm = array_merge($parm1, $parm2); 

	for ($i = 0; $i < sizeof($parm); $i++) { 
		$pattern = '/'; 
		for ($j = 0; $j < strlen($parm[$i]); $j++) { 
			if ($j > 0) { 
				$pattern .= '('; 
				$pattern .= '(&#[x|X]0([9][a][b]);?)?'; 
				$pattern .= '|(&#0([9][10][13]);?)?'; 
				$pattern .= ')?'; 
			}
			$pattern .= $parm[$i][$j]; 
		}
		$pattern .= '/i';
		$string = preg_replace($pattern, '', $string); 
	}
	return $string;
}

/**
 * 过滤ASCII码从0-28的控制字符
 * @return String
 */
function trim_unsafe_control_chars($str) {
	$rule = '/[' . chr ( 1 ) . '-' . chr ( 8 ) . chr ( 11 ) . '-' . chr ( 12 ) . chr ( 14 ) . '-' . chr ( 31 ) . ']*/';
	return str_replace ( chr ( 0 ), '', preg_replace ( $rule, '', $str ) );
}

/**
 * 格式化文本域内容
 *
 * @param $string 文本域内容
 * @return string
 */
function trim_textarea($string) {
	$string = nl2br ( str_replace ( ' ', '&nbsp;', $string ) );
	return $string;
}

/**
 * 将文本格式成适合js输出的字符串
 * @param string $string 需要处理的字符串
 * @param intval $isjs 是否执行字符串格式化，默认为执行
 * @return string 处理后的字符串
 */
function format_js($string, $isjs = 1) {
	$string = addslashes(str_replace(array("\r", "\n", "\t"), array('', '', ''), $string));
	return $isjs ? 'document.write("'.$string.'");' : $string;
}

/**
 * 转义 javascript 代码标记
 *
 * @param $str
 * @return mixed
 */
 function trim_script($str) {
	if(is_array($str)){
		foreach ($str as $key => $val){
			$str[$key] = trim_script($val);
		}
 	}else{
 		$str = preg_replace ( '/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str );
		$str = preg_replace ( '/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str );
		$str = preg_replace ( '/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str );
		$str = preg_replace ( '/]]\>/si', ']] >', $str );
 	}
	return $str;
}
/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}
/**
 * 字符截取 支持UTF8/GBK
 * @param $string
 * @param $length
 * @param $dot
 */
function str_cut($string, $length, $dot = '...',$charset='utf-8') {
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
	$strcut = '';
	if($charset == 'utf-8') {
		$length = intval($length-strlen($dot)-$length/3);
		$n = $tn = $noc = 0;
		while($n < strlen($string)) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) {
				break;
			}
		}
		if($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
		$strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
	} else {
		$dotlen = strlen($dot);
		$maxi = $length - $dotlen - 1;
		$current_str = '';
		$search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
		$replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
		$search_flip = array_flip($search_arr);
		for ($i = 0; $i < $maxi; $i++) {
			$current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
			if (in_array($current_str, $search_arr)) {
				$key = $search_flip[$current_str];
				$current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
			}
			$strcut .= $current_str;
		}
	}
	return $strcut.$dot;
}
/**
* 将字符串转换为数组
*
* @param	string	$data	字符串
* @return	array	返回数组格式，如果，data为空，则返回空数组
*/
function string2array($data) {
	//dump(debug_backtrace());exit;
	if($data == '') return array();
	@eval("\$array = $data;");
	return $array;
}
/**
* 将数组转换为字符串
*
* @param	array	$data		数组
* @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
* @return	string	返回字符串，如果，data为空，则返回空
*/
function array2string($data, $isformdata = 1) {
	if($data == '') return '';
	if($isformdata) $data = new_stripslashes($data);
	return addslashes(var_export($data, TRUE));
}
/**
 * 对用户的密码进行加密
 * @param $password
 * @param $encrypt //传入加密串，在修改密码时做认证
 * @return array/password
 */
function password($password, $encrypt='') {
	$pwd = array();
	$pwd['encrypt'] =  $encrypt ? $encrypt : create_randomstr();
	$pwd['password'] = md5(md5(trim($password)).$pwd['encrypt']);
	return $encrypt ? $pwd['password'] : $pwd;
}
/**
 * 生成随机字符串
 * @param string $lenth 长度
 * @return string 字符串
 */
function create_randomstr($lenth = 6) {
	return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

/**
* 产生随机字符串
*
* @param    int        $length  输出长度
* @param    string     $chars   可选的 ，默认为 0123456789
* @return   string     字符串
*/
function random($length, $chars = '0123456789') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}
/**
 * 功能：计算文件大小
 * @param int $bytes
 * @return string 转换后的字符串
 */
function get_byte($bytes) {
    if (empty($bytes)) {
        return '--';
    }
    $sizetext = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    return round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), 2) . $sizetext[$i];
}
/**
 * 取得文件扩展
 *
 * @param $filename 文件名
 * @return 扩展名
 */
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}
/*
* 生成缩略图,并返回缩略图位置
* @param  string  $url    原来图片的url地址,不支持第三方图片
* @param  int     $width   生成缩略图的宽
* @param  int     $heignt  生产缩略图的高
* @param  bool    $watermark  是否使用水印
*/
function thumb($url,$width,$height,$watermark)
{
	$system_name=C('system_name]');
	if(!$url)return "http://www.atool.org/placeholder.png?size={$width}x{$height}&text={$system_name}&&bg=6d98cf&fg=fff";
	$url=str_replace('_crop','',$url);
	if(filter_var($url, FILTER_VALIDATE_URL))
	{
		$urlinfo=parse_url($url);
		$path=$urlinfo['path'];
		$pathinfo=pathinfo($path);	
	}
	else
	{
		$path=$url;
		$pathinfo=pathinfo($url);
	}
	if(!in_array($pathinfo['extension'],C('UPLOAD_ALLOW_EXT')))exit('文件格式错误！');
	$file_name=$pathinfo['filename'].'_'.$width.'_'.$height.'.'.$pathinfo['extension'];//生成的缩略图名称
	$file_path='.'.$pathinfo['dirname'].'/'.$file_name;
	if(file_exists('.'.$path) && !file_exists($file_path))
	{
		$image = new \Think\Image();
		$image->open('.'.$path);
		$image->thumb($width,$height);
		//图片水印
		if($width>=C('WATERMARK_MIN_WATH')&&$height>=C('WATERMARK_MIN_HIGH'))
		{
			if(C('WATERMARK_TYPE')==1)
			{
				$image->water('./Public/images/watermark.png',C('WATERMARK_POS'),50);
			}
			elseif(C('WATERMARK_TYPE')==2)
			{
				$image->text(C('WATERMARK_TEXT'),'./Public/ttfs/2.ttf',20,C('WATERMARK_COLOR')?C('WATERMARK_COLOR'):'#000000',C('WATERMARK_POS'));
			}				
		}			
		$image->save($file_path);	
	}
	return C('upload_host').$pathinfo['dirname'].'/'.$file_name;//返回生成的图片地址
}
/*
* 获取指定规格的缩略图
* @param  string  $url    原来图片的url地址,不支持第三方图片
* @param  int     $width   缩略图的最大宽
* @param  int     $heignt  缩略图最大高 
*/
function thumb_url($url,$width,$height)
{
	if(strpos($url,'crop')!==false)return $url;
	if(filter_var($url, FILTER_VALIDATE_URL))
	{
		$urlinfo=parse_url($url);
		$path=$urlinfo['path'];
		$pathinfo=pathinfo($path);	
	}
	else
	{
		$path=$url;
		$pathinfo=pathinfo($url);
	}
	$md5_url=md5($url);//MD5加密url
	$system_name=C('system_name]');
	if(!is_file('.'.$pathinfo['dirname'].'/'.$pathinfo['filename'].'_'.$width.'_'.$height.'.'.$pathinfo['extension']))return "http://www.atool.org/placeholder.png?size={$width}x{$height}&text={$system_name}&&bg=6d98cf&fg=fff";
	return C('upload_host').$pathinfo['dirname'].'/'.$pathinfo['filename'].'_'.$width.'_'.$height.'.'.$pathinfo['extension'];
}
/*
* 使用命名空间的方式远程调用控制器中的方法
* @param  string  $url   调用的
* @param  string  $method  方法名称
* @param  string  $vars   参数
*/
function RN($url,$method,$vars=array())
{
	$hander = new $url;
	return call_user_func_array(array($hander,$method), $vars);
}
function xml_to_array( $xml )
{
    $reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches))
    {
        $count = count($matches[0]);
        $arr = array();
        for($i = 0; $i < $count; $i++)
        {
            $key= $matches[1][$i];
            $val = xml_to_array( $matches[2][$i] );  // 递归
            if(array_key_exists($key, $arr))
            {
                if(is_array($arr[$key]))
                {
                    if(!array_key_exists(0,$arr[$key]))
                    {
                        $arr[$key] = array($arr[$key]);
                    }
                }else{
                    $arr[$key] = array($arr[$key]);
                }
                $arr[$key][] = $val;
            }else{
                $arr[$key] = $val;
            }
        }
        return $arr;
    }else{
        return $xml;
    }
} 
/*
* 邮件发送函数
* @param  email  $to   接收者邮箱账号
* @param  string  $name  接收者名称
* @param  string  $subject  邮件标题
* @param  string  $body  邮件内容
* @param  string  $attachment  邮件附件
*/
function send_mail($to, $name, $subject = '', $body = '', $attachment = null){
Vendor('PHPMailer.PHPMailerAutoload'); //从PHPMailer目录导class.phpmailer.php类文件
$mail = new PHPMailer(); //PHPMailer对象
$mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
$mail->IsSMTP(); // 设定使用SMTP服务
$mail->SMTPDebug = 0; // 关闭SMTP调试功能
// 1 = errors and messages
// 2 = messages only
$mail->SMTPAuth = (C('mail_auth')==1)?true:false; // 启用 SMTP 验证功能

$mail->SMTPSecure = 'ssl'; // 使用安全协议

$mail->Host = C('mail_server'); // SMTP 服务器

$mail->Port = C('mail_port'); // SMTP服务器的端口号

$mail->Username = C('mail_user'); // SMTP服务器用户名

$mail->Password = C('mail_password'); // SMTP服务器密码

$mail->SetFrom( C('mail_from'),C('system_name'));

$replyEmail = C('REPLY_EMAIL')?C('REPLY_EMAIL'):C('mail_from');

$replyName =C('REPLY_NAME')?C('REPLY_NAME'):C('mail_from');

$mail->AddReplyTo($replyEmail, $replyName);

$mail->Subject = $subject;

$mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";

$mail->MsgHTML($body);

$mail->AddAddress($to, $name);

if(is_array($attachment)){ // 添加附件

foreach ($attachment as $file){

is_file($file) && $mail->AddAttachment($file);

}

}
return $mail->Send() ? true : $mail->ErrorInfo;

}
/*
* 短信发送
* @param  string  $mobile  发送手机号码  
* @param  string  $content  短信内容 
* @param  string  $cell  扩展号 
* @param  string  $sendTime  发送时间  固定14位长度字符串，比如：20060912152435代表2006年9月12日15时24分35秒，为空表示立即发送 
*/
function send_sms($mobile,$content,$cell,$sendTime)
{
	$uid=C('sms.CorpID');
	$passwd=C('sms.Pwd');
	$content.=C('sms.sign');
	$client = new SoapClient('http://mb345.com:999/ws/LinkWS.asmx?wsdl',array('encoding'=>'UTF-8'));	 
	$sendParam = array(
		'CorpID'=>$uid,
		'Pwd'=>$passwd,
		'Mobile'=>$mobile,
		'Content'=>$content,
		'Cell'=>$cell,
		'SendTime'=>$sendTime
		);
	$result = $client->BatchSend($sendParam);
	$result = $result->BatchSendResult;
	if($result>=0)
	{
		$info='发送成功！';
	}
	elseif($result==-1)
	{
		$info='账号未注册！';	
	}
	elseif($result==-2)
	{
		$info='其他错误！';	
	}
	elseif($result==-3)
	{
		$info='帐号或密码错误！';		
	}
	elseif($result==-5)
	{
		$info='余额不足，请充值！';		
	}
	elseif($result==-6)
	{
		$info='定时发送时间不是有效的时间格式';		
	}
	elseif($result==-7)
	{
		$info='提交信息末尾未签名，请添加中文的企业签名【 】或未采用gb2312编码';		
	}
	elseif($result==-8)
	{
		$info='发送内容需在1到300字之间';		
	}
	elseif($result==-9)
	{
		$info='发送号码为空';		
	}
	elseif($result==-10)
	{
		$info='定时时间不能小于系统当前时间';		
	}
	elseif($result==-100)
	{
		$info='限制IP访问';
	}
	$m_sms=M('plug_sms');
	$m_sms->data(array('mobile'=>$mobile,'content'=>$content,'sendTime'=>time(),'info'=>$info,'openid'=>session('memberinfo.openid')))->add();
	return array($result,$info);
}
/*
* 发送短信验证码
* @param  int  $long  验证码有效时间 单位秒
*/
function send_sms_code($mobile)
{
	 $mobile_verify=array('mobile'=>$mobile,'mobile_code'=>rand(1000,9999),'mobile_time'=>time());
	 session('mobile_verify',$mobile_verify);
	 $content='亲爱的用户，你的验证码为：'.$mobile_verify['mobile_code'].'，有效期为一小时。';
	 return send_sms($mobile,$content);
	 //return array(1,'cc');
}
/*
* 验证短信验证码是否正确
* @param  sting  $code  用户输入的验证码
*/
function check_sms_code($code,$mobile)
{
	if(empty($code))return false;
	$mobile_code=session('mobile_verify.mobile_code');
	if(empty($mobile_code))return false;
	if(strtolower($mobile_code)!=$code)return false;
	if($mobile!=session('mobile_verify.mobile'))return false;
	return true;
}
/**
 * 判断是否为图片
 */
function is_image($file) {
	$ext_arr = array('jpg','gif','png','bmp','jpeg','tiff');
	$ext = fileext($file);
	return in_array($ext,$ext_arr) ? $ext_arr :false;
}
/*
* 获取头像图片
*/
function get_head($type='middle',$username)
{
	if(empty($username))$username=session('userinfo.username');
	return C('upload_host').C('UPLOAD_URL').'/crop/head/'.md5($username.'admin').'_'.$type.'.png?'.time();
}
/*
* 邮件发送函数
* @param  area 路径
* @param  para  参数
* @param  ext  路由后缀
*/
function UN($area='',$para=array(),$ext='.html')
{
	$url=U($area,$para);
	$route=$GLOBALS['setting_model']->getSetting(3);
	if($route['open']==1)
	{
		if(stripos($url,'/Home/Index/lists')===0)
		{
			return '/'.str_replace(':catid',$para['catid'],$route['pc_lists']).$ext;		
		}
		else if(stripos($url,'/Home/Index/detail')===0)
		{
			return '/'.str_replace(array(':catid',':id'),array($para['catid'],$para['id']),$route['pc_detail']).$ext;dd;
		}
		else if(stripos($url,'/Mobile/Index/lists')===0)
		{
			return '/'.str_replace(':catid',$para['catid'],$route['mb_lists']).$ext;		
		}
		else if(stripos($url,'/Mobile/Index/detail')===0)
		{
			return '/'.str_replace(array(':catid',':id'),array($para['catid'],$para['id']),$route['mb_detail']).$ext;dd;
		}	
	}
	else
	{
		return $url;
	}
}
/*
*　把xml文档转换为数组
*/
function xml2array($contents, $get_attributes=1, $priority = 'tag')   
{  
    if(!$contents) return array();   
  
    if(!function_exists('xml_parser_create')) {  
        //print "'xml_parser_create()' function not found!";  
        return array();  
    }   
  
    //Get the XML parser of PHP - PHP must have this module for the parser to work  
    $parser = xml_parser_create('');  
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss  
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);  
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);  
    xml_parse_into_struct($parser, trim($contents), $xml_values);  
    xml_parser_free($parser);   
  
    if(!$xml_values) return;//Hmm...   
  
    //Initializations  
    $xml_array = array();  
    $parents = array();  
    $opened_tags = array();  
    $arr = array();   
  
    $current = &$xml_array; //Refference   
  
    //Go through the tags.  
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array  
    foreach($xml_values as $data) {  
        unset($attributes,$value);//Remove existing values, or there will be trouble   
  
        //This command will extract these variables into the foreach scope  
        // tag(string), type(string), level(int), attributes(array).  
        extract($data);//We could use the array by itself, but this cooler.   
  
        $result = array();  
        $attributes_data = array();   
  
        if(isset($value)) {  
            if($priority == 'tag') $result = $value;  
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode  
        }   
  
        //Set the attributes too.  
        if(isset($attributes) and $get_attributes) {  
            foreach($attributes as $attr => $val) {  
                if($priority == 'tag') $attributes_data[$attr] = $val;  
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'  
            }  
        }   
  
        //See tag status and do the needed.  
        if($type == "open") {//The starting of the tag '<tag>'  
            $parent[$level-1] = &$current;  
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag  
                $current[$tag] = $result;  
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;  
                $repeated_tag_index[$tag.'_'.$level] = 1;   
  
                $current = &$current[$tag];   
  
            } else { //There was another element with the same tag name   
  
                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array  
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;  
                    $repeated_tag_index[$tag.'_'.$level]++;  
                } else {//This section will make the value an array if multiple tags with the same name appear together  
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array  
                    $repeated_tag_index[$tag.'_'.$level] = 2;   
  
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well  
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];  
                        unset($current[$tag.'_attr']);  
                    }   
  
                }  
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;  
                $current = &$current[$tag][$last_item_index];  
            }   
  
        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'  
            //See if the key is already taken.  
            if(!isset($current[$tag])) { //New Key  
                $current[$tag] = $result;  
                $repeated_tag_index[$tag.'_'.$level] = 1;  
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;   
  
            } else { //If taken, put all things inside a list(array)  
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...   
  
                    // ...push the new element into that array.  
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;   
  
                    if($priority == 'tag' and $get_attributes and $attributes_data) {  
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;  
                    }  
                    $repeated_tag_index[$tag.'_'.$level]++;   
  
                } else { //If it is not an array...  
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value  
                    $repeated_tag_index[$tag.'_'.$level] = 1;  
                    if($priority == 'tag' and $get_attributes) {  
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well   
  
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];  
                            unset($current[$tag.'_attr']);  
                        }   
  
                        if($attributes_data) {  
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;  
                        }  
                    }  
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken  
                }  
            }   
  
        } elseif($type == 'close') { //End of tag '</tag>'  
            $current = &$parent[$level-1];  
        }  
    }   
  
    return($xml_array);  
}
/**
 * 返回带协议的域名
 */
function sp_get_host(){
	$host=$_SERVER["HTTP_HOST"];
	$protocol=is_ssl()?"https://":"http://";
	return $protocol.$host;
}