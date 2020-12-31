<?php
/**
+-------------------------------------------+
|   iCodeBang CMS [#RELEASE_VERSION#]       |
|   by iCodeBang.com Team                   |
|   © iCodeBang.com. All Rights Reserved    |
|   ------------------------------------    |
|   Support: icodebang@126.com              |
|   WebSite: http://www.icodebang.com       |
+-------------------------------------------+
*/

require_once(INC_PATH . 'thirdParty/Qiniu/functions.php');

class core_cdn_qiniu
{
    /**
     * @var object 七牛认证授权实例
     */
    protected $auth = null;
    /**
     * 构造函数
     */
    public function __construct()
    {

    }
    /**
     * 析构函数
     */
    public function __destruct()
    {

    }

    /**
     * 获取七牛认证授权实例
     * @return object
     */
    public function getAuth ()
    {
        if (! $this->auth) {
            $qiniuConfigModel = Application::config()->load_config('cdn.inc');

            // 构建鉴权对象
            $this->auth = new \Qiniu\Auth($qiniuConfigModel->qiniu['AccessKey'], $qiniuConfigModel->qiniu['SecretKey']);
        }

        return $this->auth;
    }

    /**
     * 从七牛云删除文件
     * @param string $bucketName 存储空间名称
     * @param string $fileKey    文件在存储空间的路径key
     *
     * @return array
     */
    public function deleteFile ($bucketName, $fileKey)
    {
        $auth = $this->getAuth();

        $config = new \Qiniu\Config();
        $bucketManager = new Qiniu\Storage\BucketManager($auth, $config);

        // 删除指定资源，参考文档：https://developer.qiniu.com/kodo/api/1257/delete
        list($ret, $err) = $bucketManager->delete($bucketName, $fileKey);
        if ($err !== null) {
            return $err;
        }

        return true;
    }

    /**
     * 上传文件到七牛存储
     * @param string $bucketName 七牛存储空间名称
     * @param string $localFilePath  本地文件路径
     * @param string $qiniuPath 文件存储到七牛后的路径
     *
     * @example var_dump(json_decode(core_filemanager::uploadToQiniu('sinhe', 'static/css/default/login.css', 'static/css/default/common.css')->getResponse()->body)->error );
     *
     * @return
     */
    public function uploadFile ($bucketName, $localFilePath, $qiniuPath)
    {
        if (! is_file($localFilePath)) {
            throw new Zend_Exception('File to be uploaded to Qiniu not exist:' . $localFilePath);
        }

        // 生成上传 Token
        $token = $this->getAuth()->uploadToken($bucketName);

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new Qiniu\Storage\UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传，该方法会判断文件大小，进而决定使用表单上传还是分片上传，无需手动配置。
        //return $uploadMgr->putFile($token, $qiniuPath, $localFilePath);

        list($ret, $err) = $uploadMgr->putFile($token, $qiniuPath, $localFilePath);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
            /**
             * object(Qiniu\Http\Error)#35 (2) {
             *     ["url":"Qiniu\Http\Error":private]=> string(23) "http://up-z2.qiniup.com"
             *     ["response":"Qiniu\Http\Error":private]=> object(Qiniu\Http\Response)#34 (6) {
             *             ["statusCode"]=> int(614)
             *             ["headers"]=> array(16) {
             *                    ["Server"]=> string(9) "openresty"
             *                    ["Date"]=> string(19) "Tue, 24 Nov 2020 06"
             *                    ["Content-Type"]=> string(16) "application/json"
             *                    ["Content-Length"]=> string(2) "23"
             *                    ["Connection"]=> string(10) "keep-alive"
             *                    ["Access-Control-Allow-Headers"]=> string(37) "X-File-Name, X-File-Type, X-File-Size"
             *                    ["Access-Control-Allow-Methods"]=> string(19) "OPTIONS, HEAD, POST"
             *                    ["Access-Control-Allow-Origin"]=> string(1) "*"
             *                    ["Access-Control-Expose-Headers"]=> string(14) "X-Log, X-Reqid"
             *                    ["Access-Control-Max-Age"]=> string(7) "2592000"
             *                    ["Cache-Control"]=> string(35) "no-store, no-cache, must-revalidate"
             *                    ["Pragma"]=> string(8) "no-cache" ["X-Content-Type-Options"]=> string(7) "nosniff" ["X-Reqid"]=> string(16) "9D0AAACtOkUcX0oW" ["X-Svr"]=> string(2) "UP" ["X-Log"]=> string(5) "X-Log" }
             *             ["body"]=> string(23) "{"error":"file exists"}"
             *             ["error"]=> string(11) "file exists" ["jsonData":"Qiniu\Http\Response":private]=> array(1) { ["error"]=> string(11) "file exists" } ["duration"]=> float(0.269) } }
             */
            return $err;
        } else {
            // result: array(2) { ["hash"]=> string(28) "FgD0UIIDF4yGra8nSKaYVp7VyXu2" ["key"]=> string(29) "static/css/default/common.css" }
            return $ret;
        }
    }
}


/* EOF */
