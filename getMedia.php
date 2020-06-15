<?php
/**
 * 示例
 * 通过上传千牛成功后返回的ID，从千牛服务器直接获取转码后的录音
 */

require_once('./qiniu/qiniu.php');

error_reporting(0);

$audioId = 'audioId';//上传成功返回的audioId

if(empty($audioId)){
	exit;
}

$qiniu  = new qiniu();

$data = $qiniu->status($audioId);

if($data && $data['code']==0){
	// $data['items'][0]['key']  转码后的录音文件
	echo json_encode(array('status'=>0,'msg'=>'转码成功','src'=>$data['items'][0]['key']));
	exit;
}else{
	echo json_encode(array('status'=>1,'msg'=>'转码未完成'));
	exit;
}

?>