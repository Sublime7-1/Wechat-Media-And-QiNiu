<?php
/**
 * 音频转码后的异步回调
 */

$json = file_get_contents("php://input");

$data = json_decode($json,true);

if($data['code']===0){
	//转码成功逻辑处理

}else{
	//转码失败的处理

}
