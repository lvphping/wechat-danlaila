<?php
// +----------------------------------------------------------------------
// | phpWeChat 公用函数库 Last modified 2016-03-31 15:06
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------

use phpWeChat\MySql;

// +环境操作相关函数
/**
	函数：get_server_name()
	功能：获取当前主域名
*/
function get_server_name()
{
	return !empty($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : (!empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME'));
}

/**
	函数：get_current_url()
	功能：获取当前URL
*/
function get_current_url()
{
	$cur='';

	if(isset($_SERVER['REQUEST_URI']))
	{
		$cur=$_SERVER['REQUEST_URI'];
	}
	elseif(isset($_SERVER['PHP_SELF']) && isset($_SERVER['argv']))
	{
		$cur=$_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0];
	}
	else 
	{
		$cur=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	}

	return strip_tags($cur);
}

// +Curl操作相关函数
/**
	函数：ccurl_post($curlpost='',$url='') $curlpost要Post的数据 $url Post目标地址
	功能：通过Curl Post数据 
*/
function ccurl_post($curlpost,$url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlpost);
		$return_str = curl_exec($curl);
		curl_close($curl);
		return $return_str;
}

function http_request($url, $data = null)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

	if (!empty($data))
	{
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data))
		);
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}

function http_media_request($url, $data = array())
{
	$ch = curl_init();

	if (class_exists('CURLFile')) 
	{
		curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
	} 
	else 
	{
		if (defined('CURLOPT_SAFE_UPLOAD')) 
		{
			curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
		}
	}
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_POST,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}

// +手机短信操作相关函数
/**
	函数：send_checkcode_sms($mobile,$code='') $mobile要发送的手机号 $code 验证码
	功能：发送验证码短信
*/
function send_checkcode_sms($mobile,$code='',$to=array())
{
	$target = $to['sms_target'];
	$post_data = "coding=UTF-8&account=".$to['sms_account']."&password=".$to['sms_pwd']."&mobile=$mobile&content=".rawurlencode(str_replace('【变量】',$code,$to['sms_template']));

	$rs=ccurl_post($post_data, $target);
	$gets = simplexml_load_string($rs);
	return $gets->code;
}

// +格式化操作相关函数
/**
	函数：format_url($str='') $str文件路径
	功能：格式化文件路径
*/
function format_url($str='')
{
	global $PW;
	return strtolower(substr($str,0,7))=='http://'?$str:$PW['site_url'].(substr($str,0,1)=='/'?substr($str,1):$str);
}

// +COOKIE操作相关函数
/**
	函数：set_cookie($key,$value='',$time=0) $key COOKIE键值 $value值 $time生命周期
	功能：设置COOKIE值
*/
function set_cookie($key,$value='',$time=0)
{
	$_COOKIE[$key] = $value;
	$time = $time > 0 ? $time : ($value == '' ? CLIENT_TIME - 3600 : 0);
	
	$secure=$_SERVER['SERVER_PORT']=='443'?true:false;
	$key = COOKIE_PRE.$key;
	$_COOKIE[$key] = $value;
	if(is_array($value))
	{
		foreach($value as $k=>$v)
		{
			setcookie($key.'['.$k.']',$v,$time,COOKIE_PATH,COOKIE_DOMAIN,$secure);
		}
	}
	else
	{
		setcookie($key,$value,$time,COOKIE_PATH,COOKIE_DOMAIN,$secure);
	}
}
/**
	函数：get_cookie($key) $key COOKIE键值  
	功能：获取COOKIE值
*/
function get_cookie($key)
{
	$key = COOKIE_PRE.$key;
	return isset($_COOKIE[$key])?$_COOKIE[$key]:false;
}

// +数字操作相关函数
/**
	get_hash($_int, $max=10) $id整数 $max限定阈值
	功能：根据$_int 获取一个不大于$max的整数。此函数用于分表或规划缓存服务器
*/
function get_hash($_int,$max=10) 
{
	$hash = sprintf("%u", crc32($_int));
	return intval(fmod($hash, $max));
}

// +字符串操作相关函数
/**
	函数：sub_string($string, $length, $dot='...') $string要截取的字符串 $length要截取的长度 $dot截取填充字符默认 ...
	功能：字符串截取
*/

function sub_string($string, $length, $dot='...')
{
	$length=$length*2;

	$string=trim($string);
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
	$strcut = '';
	$n = $tn = $noc = 0;
	while($n < $strlen)
	{
		$t = ord($string[$n]);
		if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
			$tn = 1; $n++; $noc++;
		} elseif(194 <= $t && $t <= 223) {
			$tn = 2; $n += 2; $noc += 2;
		} elseif(224 <= $t && $t < 239) {
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
		if($noc >= $length) break;
	}
	if($noc > $length) $n -= $tn;
	$strcut = substr($string, 0, $n);
	$strcut = str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), $strcut);
	return $strcut.$dot;
}

/*
	生成适合URL传输的base64编码
*/
function urlsafe_b64encode($string) 
{
	$data = base64_encode($string);
	$data = str_replace(array('+','/','='),array('-','_',''),$data);
	return $data;
}

function urlsafe_b64decode($string) 
{
	$data = str_replace(array('-','_'),array('+','/'),$string);
	$mod4 = strlen($data) % 4;
	if ($mod4) 
	{
		$data .= substr('====', $mod4);
	}
	return base64_decode($data);
}

/**
	函数：getXmlVal($start='',$end='',$xml='')
	功能：获取XML字符串中的键对应的值
*/
function getXmlVal($start='',$end='',$xml='')
{
	preg_match('/'.$start.'(.*)'.$end.'/i',$xml,$matches);
	return str_replace(array('<![CDATA[',']]>'),'',$matches[1]);
}

/**
	函数：return_edefualt($str='',$defualt='') $str 要检查的字符串  $defualt 未赋值时默认的赋值
	功能：当$str为false时，返回一个默认赋值
*/

function return_edefualt($str='',$defualt='')
{
	return $str?$str:$defualt;
}	

// +安全相关函数
/**
	函数：filter_pass($string) $string要过滤的字符串
	功能：入库时(MySQL)过滤危险字符
*/

function filter_pass($string,$disabledattributes = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'))
{
	
	if(is_array($string))
	{
		foreach($string as $key => $val) $string[$key] =filter_pass($val);
	}
	else
	{
		$string = trim ( $string );
		
		if(PHP_VERSION < '5.5')
		{
			$string = stripslashes(preg_replace('/\s('.implode('|', $disabledattributes).').*?([\s\>])/', '\\2', preg_replace('/<(.*?)>/ie', "'<'.preg_replace(array('/javascript:[^\"\']*/i', '/(".implode('|', $disabledattributes).")[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", $string)));
		}
		else
		{
			$string = stripslashes(preg_replace('/(\s*?)('.implode('|', $disabledattributes).')=([^\s>]+)/i','',$string));
		}
	}

	return $string;
}

/**
	函数：pw_trim($string) $string要过滤的字符串或数组
	功能：对字符串或数组进行trim过滤
*/
function pw_trim($string)
{
	if(!is_array($string))
	{
		return trim($string);
	}
	foreach($string as $key=>$value)
	{
		$string[$key]=pw_trim($value);
	}
	return $string;
}

/**
	函数：pw_strip_tags($string) $string要过滤的字符串或数组
	功能：对字符串或数组进行strip_tags过滤
*/
function pw_strip_tags($string)
{
	if(!is_array($string))
	{
		return strip_tags($string);
	}
	foreach($string as $key=>$value)
	{
		$string[$key]=pw_strip_tags($value);
	}
	return $string;
}

/**
	函数：pw_htmlspecialchars($string) $string要过滤的字符串或数组
	功能：对字符串或数组进行htmlspecialchars过滤
*/
function pw_htmlspecialchars($string)
{
	if(!is_array($string))
	{
		return htmlspecialchars($string);
	}
	foreach($string as $key=>$value)
	{
		$string[$key]=pw_htmlspecialchars($value);
	}
	return $string;
}

/**
	函数：pw_stripslashes($string) $string要过滤的字符串或数组
	功能：对字符串或数组进行stripslashes过滤
*/
function pw_stripslashes($str)
{
	if(!is_array($str))
	{
		return stripslashes($str);
	}

	foreach($str as $key => $value)
	{
		$str[$key]=pw_stripslashes($value);
	}
	return $str;
}

/**
	函数：pw_addslashes($string) $string要过滤的字符串或数组
	功能：对字符串或数组进行addslashes过滤 
*/
function pw_addslashes($str)
{
	if(!is_array($str))
	{
		return addslashes($str);
	}

	foreach($str as $key=>$value)
	{
		$str[$key]=pw_addslashes($value);
	}
	return $str;
}

/**
	函数：pw_md5($str) $str要加密的字符串
	功能：返回16位md5加密字符串
*/
function pw_md5($str)
{
	return substr(md5($str),8,16);
}

/**
	函数：pw_urlencode($str) $str要加密的字符串
	功能：返回urlencode后的字符串
*/
function pw_urlencode($str)
{
	if(!is_array($str))
	{
		return urlencode($str);
	}

	foreach($str as $key=>$value)
	{
		$str[$key]=pw_urlencode($value);
	}
	return $str;
}

/**
	函数：str_callback_w($str='') $str要检测替换的字符串或数组
	功能：对字符串或数组进行检查替换，返回只包含a-z 、0-9、 _字符的结果
*/
function str_callback_w($str='')
{
	if(!is_array($str))
	{
		return preg_replace('/[^a-z0-9_\-]/i','',$str);
	}

	foreach($str as $key=>$value)
	{
		$str[$key]=str_callback_w($value);
	}
	return $str;
}

function nr2PHP_EOL($str='')
{
	return str_replace('[_N_R]',PHP_EOL,$str);
}

function PHP_EOL2nr($str='')
{
	return str_replace(PHP_EOL,'[_N_R]',$str);
}

// +PHP环境检测函数

/**
	is_func_disabled($func)  $func 函数名
	功能：检测指定函数是否被PHP.ini禁用函数
*/
function is_func_disabled($func)
{
	return in_array($func,array_map('trim',explode(',',ini_get('disable_functions'))));
}

// +数组操作函数

/**
	string2array($str)  $str 要转换的字符串
	功能：字符串转换为数组
*/
function string2array($str='')
{
	if($str=='') return array();
	if(is_array($str)) return $str;
	if(is_func_disabled('eval'))
	{
		$cachefile=PW_ROOT.'data/tmp/'.md5($str).'.array.php';
		$array="<?php\n//".date('Y-m-d H:i:s',time())."\nreturn ".$str.";\n?>";
		$strlen=file_put_contents($cachefile,$array);
		@chmod($cachefile,0777);

		$array=cache_read(md5($str).'.array.php',PW_ROOT.'data/tmp/');
		cache_delete(md5($str).'.array.php',PW_ROOT.'data/tmp/');
	}
	else
	{
		@eval("\$array = $str;");
	}
	return $array;
}

// +缓存操作函数

/**
	get_cache_counts($sql,$ttl=0,$iscache=0)  $sql要查询的语句 $ttl缓存生命周期 $iscache是否返回实时数据
	功能：获取 $sql 查询对应的记录条数并缓存 
*/
function get_cache_counts($sql,$ttl=0,$iscache=0)
{
	global $PW;

	$sql=strtolower($sql);

	$key = pw_md5($sql);
	$ttl=intval($ttl)?intval($ttl):(defined('IN_MANAGE')?0:(!$iscache?0:$PW['cache_ttl']));

	if($ttl)
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE."counts` WHERE `key`='$key'");

		if(!$r || $r['cachetime']<CLIENT_TIME-$ttl)
		{
			$r=MySql::fetchOne($sql);
			MySql::insert(DB_PRE.'counts',array('key'=>$key,'cachetime'=>$CLIENT_TIME,'count'=>$r['count']),true);
		}
	}
	else
	{
		$r=MySql::fetchOne($sql);
	}
	return $r['count'];
}

/**
	cache_read($cachename,$path='')  $cachename缓存文件名  $path缓存文件路径
	功能：读取缓存文件
*/
function cache_read($cachename,$path='')
{
	$cachename=str_replace(array('\\','/'),'',$cachename);
	$path=empty($path)?PW_ROOT.'data/cache/':$path;
	$cachefile=$path.$cachename;
	if(!is_file($cachefile))
	{
		return false;
	}
	return @include $cachefile;
}

/**
	cache_write($cachename,$array=array(),$path='')  $cachename缓存文件名 $array要写入的数组值 $path缓存文件路径
	功能：写入缓存文件
*/
function cache_write($cachename,$array=array(),$path='')
{
	if(!is_array($array))return false;
	$path=empty($path)?PW_ROOT.'data/cache/':$path;
	$cachefile=$path.$cachename;
	
	$array="<?php\n//".date('Y-m-d H:i:s',time())."\nreturn ".var_export($array,true).";\n?>";
	$strlen=file_put_contents($cachefile,$array);

	@chmod($cachefile,0777);
	return $strlen;
}

/**
	cache_delete($cachename,$path='')  $cachename缓存文件名  $path缓存文件路径
	功能：删除缓存文件
*/
function cache_delete($cachename,$path='')
{
	$path=empty($path)?PW_ROOT.'data/cache/':$path;
	$cachefile=$path.$cachename;

	if(!is_file($cachefile))
	{
		return ;
	}
	return @unlink($cachefile);
}

// +文件处理相关函数

/**
	get_fileext($file='') $file文件路径
	功能：获取文件后缀 如 jpg/gif/exe 等
*/
function get_fileext($file='')
{
	return strtolower(substr(strrchr($file,'.'),1));
}

/**
	make_dir($path='') $path文件夹路径
	功能：递归创建文件夹
*/
function make_dir($path='')
{
	chdir(PW_ROOT);
	if(is_dir($path))
	{
		return true;
	}

	$path=explode('/',strtolower(preg_replace('/[^0-9a-z_\-\/]/i','',str_replace('\\','/',$path))));

	$path_name='./';
	
	foreach($path as $_path)
	{
		$path_name.=$_path.'/';
		if(!file_exists($path_name) && !empty($_path))
		{
			@mkdir($path_name);
			@chmod($path_name,0777);
		}
	}
	return true;
}

/**
	format_dir_path($path='') $path文件夹路径
	功能：格式化文件夹路径
*/
function format_dir_path($path)
{
	$path = str_replace('\\', '/', $path);
	if(substr($path,-1) != '/') $path = $path.'/';
	return $path;
}

/**
	format_file_name($filename='') $filename文件名
	功能：格式化文件名
*/
function format_file_name($filename='')
{  
	return preg_replace('/^.+[\\\\\\/]/', '', $filename);  
} 

/**
	rm_files($path='') $path文件夹
	功能：删除路径 $path 下的所有文件
*/
function rm_files($path='')
{
	if(!file_exists($path))return true;

	foreach(glob(format_dir_path($path).'*.*') as $f)
	{
		@unlink($f);
	}
	return true;
}

/**
	rm_dirs($path='') $path文件夹
	功能：删除路径 $path 下的所有文件夹以及文件
*/
function rm_dirs($path)
{
	$path=str_replace('\\','/',$path);
	$path=str_replace('//','/',$path);

	if(!file_exists($path))return true;

	/*
		简单安全检查  如果 文件夹下含有 rm.lock 则不能删除该文件夹 以及文件
	*/

	if(is_dir($path))
	{
		if(file_exists(str_replace('//','/',$path.'/rm.lock')))return false;
	}

	else
	{
		if(file_exists(dirname($path).'/rm.lock'))return false;
	}

	if(is_dir($path))
	{
		$path = format_dir_path($path);
		$list = glob($path.'*');
		foreach($list as $v)
		{
			is_dir($v) ? rm_dirs($v) : @unlink($v);
		}
		return rmdir($path);
	}
	else
	{
		return unlink($path);
	}
}

// +图片视频附件处理相关函数

/**
	is_image($img='')  $img图片地址
	功能：判断文件是不是一个图片
*/
function is_image($img='')
{
	return in_array(strtolower(get_fileext($img)),array('gif','jpg','jpeg','png','bmp'));
}

/**
	is_video($video='')  $video视频地址
	功能：判断文件是不是一个视频
*/
function is_video($video='')
{
	return in_array(strtolower(get_fileext($video)),array('flv','swf','mp4'));
}

/**
	is_voice($voice='')  $voice语音地址
	功能：判断文件是不是一个语音
*/
function is_voice($voice='')
{
	return in_array(strtolower(get_fileext($voice)),array('mp3','wma','wav','amr'));
}

/**
	is_attachment($attachment='')  $attachment附件地址
	功能：判断文件是不是一个附件
*/
function is_attachment($attachment='')
{
	return in_array(strtolower(get_fileext($attachment)),array('rar','zip','gz'));
}

/**
	is_pem($pem='')  $pem安全证书地址
	功能：判断文件是不是一个安全证书
*/
function is_pem($pem='')
{
	return in_array(strtolower(get_fileext($pem)),array('pem'));
}

/**
	imageinfo($img='')  $img图片地址
	功能：获取图片信息
*/
function imageinfo($img='')
{
	$info=array();
	$t=basename($img);
	$t=explode('.',$t);
	$info['name']=$t[0];
	$info['size']=filesize($img);
	$imageinfo=getimagesize($img);
	$info['width']=$imageinfo[0];
	$info['height']=$imageinfo[1];
	$info['width_height']=$imageinfo[3];
	$info['mime']=$imageinfo['mime'];
	unset($imageinfo);
	$imageinfo=pathinfo($img);
	$info['path']=$imageinfo['dirname'].'/'; 
	$info['type']=strtolower($imageinfo['extension']);
	unset($imageinfo,$name);
	return $info;
}

/**
	get_thumb_path($img='',$width=600)  $img原图图片地址 要获取的缩略图对应宽度 默认 600 / 200
	功能：获取图片对应的缩略图地址
*/
function get_thumb_path($img='',$width=600)
{
	$_thumb_path=dirname($img).'/'.basename($img,'.'.get_fileext($img)).'x'.$width.'.'.get_fileext($img);

	return is_file(PW_ROOT.$_thumb_path)?$_thumb_path:$img;
}

/**
	setwatermark($img='')  $img要加水印的图片
	功能：给图片加水印
*/
function setwatermark($img='')
{
	global $PW;
	
	if(!$PW['upload_watermark_enable'])
	{
		return true;
	}

	if(is_file($img))
	{
		$imageinfo=imageinfo($img);
		$source_w=$imageinfo['width'];
		$source_h=$imageinfo['height'];
		$watermarkimg=PW_ROOT.$PW['upload_watermark'];
		
		if($source_w<$PW['upload_watermark_width'] || $source_h<$PW['upload_watermark_height'] || $source_h<200 || !is_file($watermarkimg))
		{
			return true;
		}

		$imagecreatefunc='imagecreatefrom'.($imageinfo['type']=='jpg'?'jpeg':$imageinfo['type']);
		$imagecreatefunc='imagecreatefrom'.($imageinfo['type']=='bmp'?'wbmp':($imageinfo['type']=='jpg'?'jpeg':$imageinfo['type']));
		$im=$imagecreatefunc($img);
		
		$watermarkinfo=imageinfo($watermarkimg);
		$width=$watermarkinfo['width'];
		$height=$watermarkinfo['height'];
		$watermarkcreatefunc='imagecreatefrom'.($watermarkinfo['type']=='jpg'?'jpeg':$watermarkinfo['type']);
		$watermark_im=$watermarkcreatefunc($watermarkimg);
		
		if($source_w<$width || $source_h<$height)
		{
			return true;
		}

		switch($PW['upload_watermark_pos'])
		{
			case 0:
				$wx = mt_rand(0,($source_w - $width));
				$wy = mt_rand(0,($source_h - $height));
				break;
			case 1:
				$wx = 5;
				$wy = 5;
				break;
			case 2:
				$wx = ($source_w - $width) / 2;
				$wy = 5;
				break;
			case 3:
				$wx = $source_w - $width-5;
				$wy = 5;
				break;
			case 4:
				$wx = 5;
				$wy = ($source_h - $height) / 2;
				break;
			case 5:
				$wx = ($source_w - $width) / 2;
				$wy = ($source_h - $height) / 2;
				break;
			case 6:
				$wx = $source_w - $width-5;
				$wy = ($source_h - $height) / 2;
				break;
			case 7:
				$wx = 5;
				$wy = $source_h - $height-5;
				break;
			case 8:
				$wx = ($source_w - $width) / 2;
				$wy = $source_h - $height-5;
				break;
			default:
				$wx = $source_w - $width-5;
				$wy = $source_h - $height-5;
				break;
		}
		if($imageinfo['type'] == 'png') 
		{
			imagecopy($im, $watermark_im, $wx, $wy, 0, 0, $width, $height);
		} 
		else 
		{
			imagecopymerge($im, $watermark_im, $wx, $wy, 0, 0, $width, $height, $PW['upload_watermark_pct']);
		}

		$imagefunc='image'.($imageinfo['type']=='jpg'?'jpeg':$imageinfo['type']);
		$imagefunc='image'.($imageinfo['type']=='bmp'?'wbmp':($imageinfo['type']=='jpg'?'jpeg':$imageinfo['type']));
		$imagefunc($im,$img);
		imagedestroy($im);
		return true;
	}

	return true;
}

// +操作提示相关函数

/**
	函数：alert($msg='') $msg弹出信息
*/
function alert($msg='')
{
	exit('<script language="javascript">{alert("'.$msg.'");}</script>');
}

/**
	函数：die_to_url($url='',$parent='') $url要跳转的URL $parent跳转的窗体 top.或者parent.或者为空
	功能：页面die() 并无提示跳转
*/
function die_to_url($url='',$parent='')
{
	exit('<script language="javascript">{self.'.$parent.'location.href="'.$url.'";}</script>');
}

/**
	函数：myErrorHandler($errno, $errstr, $errfile, $errline) $errno错误编号 $errstr错误内容 $errfile错误文件 $errline错误所在行
	功能：自定义系统错误处理
*/
function myErrorHandler($errno, $errstr, $errfile='', $errline='')
{
    if (!(error_reporting() & $errno)) 
	{
        return;
    }
	else
	{
		echo <<<Eof
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>My ERROR [{$errno}] {$errstr}</title>
		<style type="text/css">
		body{background:#f3f3f3;}
		a{color:#2d2d2f; text-decoration:underline}
		.error{border:#cbcbcb 1px solid;font-family: Microsoft YaHei; background:#fff; width:65%;  padding:20px; margin:0px auto; margin-top:15%;}
		.error .desc{background:#faefeb; color:#dd5722; font-size:14px;line-height:2em; padding:20px; font-weight:bold; margin:0px auto;}
		.error .tips{background:#ffffe3; color:#2d2d2f; font-size:12px;line-height:2em; padding:20px; border:#c7c9c8 1px solid; margin:0px auto; margin-top:20px;}
		</style>
		</head>

		<body>
		<div class="error">
			<div class="desc">
			<b>My ERROR</b> [{$errno}] {$errstr}
			</div>
			<div class="tips">
			{$errstr} on line {$errline} in file {$errfile}
			</div>
		</div>
		</body>
		</html>
Eof;
		if(IN_LOG)
		{
			$log_path=PW_ROOT."data/log/".date('Y-m-d').'/';
			@mkdir($log_path);
			error_log("{$errstr} on line {$errline} in file {$errfile} ".date('Y-m-d H:i:s')."\n\r", 3, $log_path."error.log");
		}
		exit();
	}
}

/**
	fatal_error($errstr, $errno, $errfile, $errline)  $errstr错误内容 $errno错误编号
	功能：系统致命错误函数
*/
function fatal_error($errstr='',$errno='')
{
	echo <<<Eof
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>My ERROR [{$errno}] {$errstr}</title>
      <style type="text/css">
      body{background:#f3f3f3;}
      a{color:#2d2d2f; text-decoration:underline}
      .error{border:#cbcbcb 1px solid;font-family: Microsoft YaHei; background:#fff; width:65%;  padding:20px; margin:0px auto; margin-top:15%;}
      .error .desc{background:#faefeb; color:#dd5722; font-size:14px;line-height:2em; padding:20px; font-weight:bold; margin:0px auto;}
      .error .tips{background:#ffffe3; color:#2d2d2f; font-size:12px;line-height:2em; padding:20px; border:#c7c9c8 1px solid; margin:0px auto; margin-top:20px;}
      </style>
      </head>

      <body>
      <div class="error">
          <div class="desc">
          <b>My ERROR</b> [{$errno}]
          </div>
          <div class="tips">
          {$errstr}
          </div>
      </div>
      </body>
      </html>
Eof;
      if(IN_LOG)
	  {
		$log_path=PW_ROOT."data/log/".date('Y-m-d').'/';
		@mkdir($log_path);
		@error_log("{$errstr} on line {$errline} in file {$errfile} ".date('Y-m-d H:i:s')."\n\r", 3, $log_path."error.log");
	  }
      exit();
}

/**
	pw_call_user_func($function,$parameter,$extra='')  
	功能：调用用户函数
*/
function pw_call_user_func($function,$parameter,$extra='')
{
	$para=array();
	$parameter=preg_replace('/\"\s+\"/i','""',$parameter);
	$parameter=explode(" ",$parameter);

	if($parameter)
	{
		foreach($parameter as $val)
		{
			preg_match('/([0-9a-z_]+)\s*=\s*([^\s]+)/i',$val,$matches);
			$para[$matches[1]]=filter_para($matches[2]);
		}
	}

	if($extra)
	{
		preg_match('/([0-9a-z_]+)\s*=\"([^\"]+)\"/i',$extra,$matches);
		$para[$matches[1]]=filter_para($matches[2]);
	}
	return call_user_func($function,$para);
}

/**
	filter_para($para) 过滤参数
	功能：过滤参数
*/
function filter_para($para)
{
	return str_replace(array('\'','"'),'',stripslashes($para));
}

/**
	U($folder) $folder 模块folder
	功能：获取模块访问URL
*/
function U($folder='',$action='index',$para=array())
{
	$php_self='index.php' ;

	$mod=phpWeChat\Module::getModule($folder);
	$parentmod=phpWeChat\Module::getModuleByKey($mod['parentkey']);

	$_para=array();
	if(CONFIG_REWRITE && !define('IN_MANAGE'))
	{
		if($para)
		{
			foreach($para as $key => $val)
			{
				$_para[]=$key.'-'.$val.'/';
			}
		}
		return PW_PATH.$parentmod['folder'].'/'.$mod['folder'].'/'.($_para?implode('',$_para):'');
	}
	else
	{
		if($para)
		{
			foreach($para as $key => $val)
			{
				$_para[]=$key.'='.$val;
			}
		}
		return $php_self.'?m='.$mod['folder'].'&a='.$action.($_para?'&'.implode('&',$_para):'');
	}
}

// +生成随机数

/**
	create_rand($len)  $len 随机字符串长度
	功能：生成随机数函数
*/
function create_rand($len=32)
{
	$rand='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	$str='';
	for($i=0;$i<$len;$i++)
	{
		$str.=$rand{mt_rand(0,61)};
	}

	return $str;
}

// +正则验证

/**
	is_username($username='')  $username 用户名字符串
	功能：验证是否是用户名
*/
function is_username($username='')
{
	$username=trim($username);

	if(!$username)
	{
		return false;
	}

	return preg_match('/^[\x80-\xff_a-zA-Z0-9]{1,64}$/i',$username);
}

/**
	is_pwd($pwd='')  $pwd 用户密码
	功能：验证是否是用户密码
*/
function is_pwd($pwd='')
{
	$pwd=trim($pwd);

	if(!$pwd)
	{
		return false;
	}

	return !preg_match('/[\x80-\xff]/i',$pwd);
}

/**
	is_email($email='')  $email 邮箱
	功能：验证是否是正确邮箱格式
*/
function is_email($email='')
{
	$email=trim($email);

	if(!$email)
	{
		return false;
	}

	return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/i',$email);
}



/**
	is_telephone($telephone='')  $telephone 手机号
	功能：验证是否是手机号
*/
function is_telephone($telephone='')
{
	$telephone=trim($telephone);

	if(!$telephone)
	{
		return false;
	}

	return preg_match('/^1[34578]{1}\d{9}$/i',$telephone);
}

/**
	is_qq($qq='')  $qq qq号
	功能：验证是否是qq
*/
function is_qq($qq='')
{
	$qq=trim($qq);

	if(!$qq)
	{
		return false;
	}

	return preg_match('/^[0-9]{5,15}$/i',$qq);
}

/**
	is_in_chinese($str='')  $str 字符串
	功能：验证是否[含有]中文字符 常用来校验地址等信息 
*/
function is_in_chinese($str='')
{
	$str=trim($str);

	if(!$str)
	{
		return false;
	}

	return preg_match('/[\x80-\xff]+/i',$str);
}

/**
	is_url($url='')  $url 字符串
	功能：验证是否是url
*/
function is_url($url='')
{
	$url=trim($url);

	if(!$url)
	{
		return false;
	}

	return preg_match('/^http:\/\/[A-Za-z0-9]+\.?[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/i',$url);
}

/*
	format_focus_img() 将多图上传字段转换成二位数组
*/
function format_focus_img($img)
{
	$arr=array();

	foreach(explode(',',$img) as $im)
	{
		$im=explode('`',$im);
		$arr[$im[1]]=$im[0];
	}

	return $arr;
}

/*
	deformat_focus_img() 将多图上传数组字段转换成可存储的字符串
*/
function deformat_focus_img($imgs='')
{
	$focuspic=array();

	foreach($_POST['urlimgs_'.$imgs] as $key => $val)
	{
		if(!$_POST['deleteimgs_'.$imgs][$key] && is_file(PW_ROOT.$val))
		{
			$focuspic[]=$val.'`'.$_POST['nameimgs_'.$imgs][$key];
		}
	}
	return implode(',',$focuspic);
}

/*
	recurse_copy($src,$dst) 复制文件夹 原目录，复制到的目录
*/
function recurse_copy($src,$dst)
{  
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

/*
	微信前端提示函数
 */
function wealert($msg,$type="success")
{
	if($type=="success")
	{
		$html='<!DOCTYPE html>
			<html lang="en">
			<head>
			    <meta charset="UTF-8">
			    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
			    <title>操作提示</title>
			    <link rel="stylesheet" href="'.PW_PATH.'statics/weui/weui.css"/>
			    <style type="text/css">
			    	.googs{width: 98%; margin: 0 auto; clear: both}
			    	.googs img{width: 100%}

			    	.btn{width: 98%; margin: 0 auto; clear: both; position: fixed; bottom: .1rem; left: 1%}
			    </style>
			    <script type="text/javascript" src="'.PW_PATH.'statics/jquery/jquery-1.12.2.min.js"></script>
			    <script language="javascript" type="text/javascript">
			        var PW_PATH="'.PW_PATH.'";
			    </script>
			    <script src="'.PW_PATH.'statics/core.js" language="javascript"></script>
			</head>

			<body>
			<div class="weui_msg">
			    <div class="weui_icon_area"><i class="weui_icon_success weui_icon_msg"></i></div>
			    <div class="weui_text_area">
			        <h2 class="weui_msg_title">操作成功</h2>
			        <p class="weui_msg_desc">'.$msg.'</p>
			    </div>
			    <div class="weui_opr_area">
			        <p class="weui_btn_area">
			            <a href="#" onclick="history.back(-1);" class="weui_btn weui_btn_primary">确定</a>
			        </p>
			    </div>
			</div>
			</body>
			</html>';
	}
	else
	{
		$html='<!DOCTYPE html>
			<html lang="en">
			<head>
			    <meta charset="UTF-8">
			    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
			    <title>操作提示</title>
			    <link rel="stylesheet" href="'.PW_PATH.'statics/weui/weui.css"/>
			    <style type="text/css">
			    	.googs{width: 98%; margin: 0 auto; clear: both}
			    	.googs img{width: 100%}

			    	.btn{width: 98%; margin: 0 auto; clear: both; position: fixed; bottom: .1rem; left: 1%}
			    </style>
			    <script type="text/javascript" src="'.PW_PATH.'statics/jquery/jquery-1.12.2.min.js"></script>
			    <script language="javascript" type="text/javascript">
			        var PW_PATH="'.PW_PATH.'";
			    </script>
			    <script src="'.PW_PATH.'statics/core.js" language="javascript"></script>
			</head>

			<body>
			<div class="weui_msg">
			    <div class="weui_icon_area"><i class="weui_icon_msg weui_icon_warn"></i></div>
			    <div class="weui_text_area">
			        <h2 class="weui_msg_title">操作失败</h2>
			        <p class="weui_msg_desc">'.$msg.'</p>
			    </div>
			    <div class="weui_opr_area">
			        <p class="weui_btn_area">
			            <a href="#" onclick="history.back(-1);" class="weui_btn weui_btn_warn">确定</a>
			        </p>
			    </div>
			</div>
			</body>
			</html>';
	}

	exit($html);
}

/*
	自动加载类
 */
function auto_load($classname) {
	$classname=explode('\\',strtolower($classname));
	$basename=$classname[sizeof($classname)-1];

	if($classname[0]=='phpwechat' || !$classname)
	{
		$filename =PW_ROOT.'include/'.$basename .".class.php";
	}
	elseif($classname[0]=='admin')
	{
		$filename =PW_ROOT.'admin/include/'.$basename .".class.php";
	}
	else
	{
		if(isset($classname[2]))
		{
			$filename =PW_ROOT.'addons/'.$classname[0].'/addons/'.$classname[1].'/include/'.$basename .".class.php";
		}
		else
		{
			unset($classname[sizeof($classname)-1]);
			$filename =PW_ROOT.'addons/'.implode('/',$classname).'/include/'.$basename .".class.php";
		}
	}

	if(is_file($filename))
	{
		include_once($filename);
	}
}
?>