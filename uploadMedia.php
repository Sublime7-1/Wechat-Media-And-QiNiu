<?php
/**
 * 示例
 * 从微信服务器获取录音上传至七牛服务器
 */
require_once('./qiniu/qiniu.php');
error_reporting(0);


$serverId   = 'serverId';//微信上传录音wx.uploadVoice成功后返回的serverId
$token_data = 'access_token';//微信授权的access_token

if(empty($serverId)||empty($token_data)){
	exit;
}

//获取上传到微信服务器的资源
$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$token_data}&media_id={$serverId}";
$fileInfo = file_get_contents($url);


//自定义名称
$filename = uniqid().".amr";

$qiniu  = new qiniu();

// 上传录音
$data   =  $qiniu->uploadFile($fileInfo,$filename);

if($data){
    // 录音转码
	$id = $qiniu->audioTranscodeMp3($data);
	if($id){

        //上传录音成功后的逻辑处理：保存什么的...

		echo json_encode(array('status'=>0,'msg'=>'记录成功','audioId'=>$id));exit;
	}else{
		echo json_encode(array('status'=>1,'msg'=>'上传失败'));exit;
	}
	
}else{
	echo json_encode(array('status'=>1,'msg'=>'上传失败'));exit;
}


function saveWeixinFile($filename,$filecontent)
{
    $local_file = fopen($filename, 'w');

    if (false !== $local_file){

        if (false !== fwrite($local_file, $filecontent)) {
            fclose($local_file);
        }
    }
}


?>