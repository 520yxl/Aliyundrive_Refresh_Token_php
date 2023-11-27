<?php
$client_id='d066108b500c49cea65784';//阿里云盘开发者应用ID
$client_secret='d172f7db9a8cc779';////阿里云盘开发者应用密钥

//判断SSL是否开启
function is_SSL(){
	if(!isset($_SERVER['HTTPS']))
		return FALSE;
	if($_SERVER['HTTPS']===1){  //Apache
		return TRUE;
	}elseif($_SERVER['HTTPS']==='on'){ //IIS
		return TRUE;
	}elseif($_SERVER['SERVER_PORT']==443){ //其他
		return TRUE;
	}
	return FALSE;
}

$api_url='https://openapi.alipan.com/oauth/access_token';
$grant_type='authorization_code';
$code=$_GET['code'];
//获取回调地址
if (is_SSL()<>FALSE) {
$hts='https://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
}else{
    $hts='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
}
$redirect_uri=urlEncode($hts);
//重定向到阿里云盘登陆页面
$url='https://openapi.alipan.com/oauth/authorize?client_id='.$client_id.'&redirect_uri='.$redirect_uri.'&scope=user:base,file:all:read,file:all:write&state=&response_type=code';
//https://openapi.aliyundrive.com/oauth/authorize?client_id=55091393987b4cc090b090ee17e85e0a&redirect_uri=oob&scope=
// redirect($url);
$post_data=array();
$post_data["client_id"]=$client_id;
$post_data["client_secret"]=$client_secret;
$post_data["grant_type"]=$grant_type;
$post_data["code"]=$code;
// $post_data["refresh_token"]=$refresh_token;
$jsonData = json_encode($post_data);

// 设置请求头
$header = array(
    'Content-type: application/json; charset=utf-8',
    'Accept: application/json'
);

// 初始化cURL请求
$ch = curl_init();

// 设置cURL选项
curl_setopt($ch, CURLOPT_URL, $api_url); // 请求的URL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回响应结果
curl_setopt($ch, CURLOPT_POST, true); // 使用POST请求
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // 设置POST数据
curl_setopt($ch, CURLOPT_HTTPHEADER, $header); // 设置请求头

// 执行cURL请求
$response = curl_exec($ch);

// 关闭cURL资源
curl_close($ch);

// 解码JSON数据
$data = json_decode($response, true);

// 访问数据

$refresh_token=$data['refresh_token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Get Aliyundrive Refresh Token</title>
    <link rel="shortcut icon" href="//www.520yxl.cn/favicon.ico">
    <!-- 引入 layui.css -->
    <link href="//unpkg.com/layui@2.8.6/dist/css/layui.css" rel="stylesheet">
      <link href="//lib.baomitu.com/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"/>
  <script src="//lib.baomitu.com/jquery/1.12.4/jquery.min.js"></script>
  <script src="//lib.baomitu.com/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="//lib.baomitu.com/layer/3.5.1/layer.js"></script>
  <!--[if lt IE 9]>
    <script src="//lib.baomitu.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//lib.baomitu.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="container">
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
<div class="panel panel-primary">
	<div class="panel-heading" style="text-align: center;"><h3 class="panel-title">
		Get Aliyundrive Refresh Token
	</div>
	<div class="panel-body" style="text-align: center;">
		<div class="list-group">
			<ul class="nav nav-tabs">
				<!--<li class="active"><a href="aliyundrive.php">点击跳转阿里云盘授权登陆</a></li>-->
			</ul>
			<div class="list-group-item"><img src="https://img.alicdn.com/imgextra/i3/O1CN01qcJZEf1VXF0KBzyNb_!!6000000002662-2-tps-384-92.png"></div>
			<div id="load" class="alert alert-info" style="font-weight:bold;display:none;"></div>
			<div id="login" class="list-group-item">
				<div class="form-group qqlogin">
					<div class="input-group"><div class="input-group-addon">刷新令牌</div>
					<input type="text" id="uin" value="<?php echo $refresh_token; ?>" class="form-control" onkeydown="if(event.keyCode==13){submit.click()}"/>
				</div></div>
				</div>
				<a href="<?php echo $url; ?>"><button type="button" id="submit" class="btn btn-primary btn-block">点击跳转阿里云盘授权登陆</button></a>
				<!--<br/><a href="javascript:window.location.reload()">点此重新登录</a>-->
			</div>
		</div>
	</div>
</div>
</div>
</div>
</body>
</html>
