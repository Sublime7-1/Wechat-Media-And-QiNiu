<?php
/**
 * 使用七牛
 */

require('autoload.php');

final class Config
{
    const BUCKETNAME = '七牛的后台管理页面中创建的空间名';
    const ACCESS_KEY = 'access_key';
    const SECRET_KEY = 'secret_key';
}

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Processing\PersistentFop;
class qiniu{

	private $token;
	private $auth;
	
	
	function __construct (){
		$this->auth = new Auth(Config::ACCESS_KEY, Config::SECRET_KEY);
		$this->token = $this->auth->uploadToken(Config::BUCKETNAME);
		if (is_null($this->token)) exit(1);
	}
	

	/*
	*上传文件
	*filename  二进制数据
	*saveName  上传到七牛保存的文件名
	*@return   上传成功则返回文件路径，失败则返回false
	*/
	function uploadFile($file,$saveName){
		$upManager = new UploadManager();
		list($data, $err) = $upManager->put($this->token,$saveName, $file);
		if ($err != null) {
			return false;
		} else {
			return $data['key'];
		}
	}

	
	/*
	*图片缩放处理
	*filename      上传到七牛保存的文件名
	*downloadFile  保存本地文件名
	*height  	  缩放 高度
	*height  	  缩放 宽度
	*@return   上传成功则返回文件路径，失败则返回false
	*/
	function imageResize($filename,$downloadFile,$height=100,$width=100){
		try{
			$options = array(
			OssClient::OSS_FILE_DOWNLOAD => $downloadFile,
			OssClient::OSS_PROCESS => "image/resize,m_fixed,h_".$height.",w_".$width, );
		    $res =  $this->ossClient->getObject($this->bucketName, $filename, $options);
		    return $res;
		} catch(OssException $e) {
			
			return false;
		}	
	}
	
	
	/*
	*音频转码
	*filename   上传到七牛保存的文件名
	*notifyUrl  转码完成后通知到你的业务服务器。
	*@return   上传成功则返回id，失败则返回失败原因
	*/
	function audioTranscodeMp3($filename,$pipeline=''){
		//转码是使用的队列名称
		
		//设置转码成功后请求的回调地址
		$notifyUrl  = dirname( dirname($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'])).'/qiniu/notify.php';

		$force 		= false;
		$config 	= new \Qiniu\Config();

		$pfop 		= new PersistentFop($this->auth, $config);

		//要进行转码的转码操作。 http://developer.qiniu.com/docs/v6/api/reference/fop/av/avthumb.html
		//转码音频的配置,具体内容查看上面的链接
        $fops 		= "avthumb/mp3/ab/192k/volume/3.00|saveas/" . \Qiniu\base64_urlSafeEncode(Config::BUCKETNAME . ":".$filename.".mp3");

		list($id, $err) = $pfop->execute(Config::BUCKETNAME, $filename, $fops, $pipeline, $notifyUrl, $force);
		
		if ($err != null) {
			return false;
		} else {
			return $id;
		}
	}
	
	
	/*
	*音频转码
	*filename   上传到七牛保存的文件名
	*notifyUrl  转码完成后通知到你的业务服务器。
	*@return   上传成功则返回id，失败则返回失败原因
	*/
	function status($id){
		//转码是使用的队列名称
		$config = new \Qiniu\Config();

		$pfop = new PersistentFop($this->auth, $config);

		list($ret, $err) = $pfop->status($id);
		
		if ($err != null) {
			return false;
		} else {
			return $ret;
		}
	}
	
}
