一、使用

1.在需要调用的文件中包含 qiniu文件夹 里的 qiniu.php, 然后 new qiniu();

2.uploadFile     上传文件
*filename		是二进制数据，
*savename	上传到七牛保存的文件名，
返回访问路径，不支持文件流;

3.imageResize 图片缩放处理
*filename      	上传到七牛保存的文件名
*downloadFile  	保存本地文件名

4.audioTranscodeMp3     音频转码 
 *fileName     	上传到七牛保存的文件名
 *notifyUrl  	转码完成后通知到你的业务服务器。
 *pipeline  	转时使用的队列名称，默认为空
 *@return   	上传成功则返回id，失败则返回失败原因

二、说明
getMedia.php 和 uploadMedia.php  这两个文件只为微信上传录音时，后台对接七牛服务器进行录音转码的处理

三、七牛接口地址
https://developer.qiniu.com/dora/api/6166/an-overview-of-the-api-1

四、说明
如有错误麻烦联系更改，谢谢
