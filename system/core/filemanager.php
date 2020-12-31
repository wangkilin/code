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
class core_filemanager
{
    /**
     * @var instance
     * database connection instance
     */
    protected $db = null;

    /**
     * 设置数据库连接实例
     * @param object $db 数据库连接实例
     *
     * @return $this
     */
    public function setDb ($db)
    {
        $this->db = $db;

        return $this;
    }
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

    static public function getDirContentByPage ($dirPath, $startPage = 1, $number = 20)
    {
        if ( ! is_dir($dirPath) ) {
            throw new Exception('Dir is not exist:' . $dirPath);
        }
        $dirResourse = opendir($dirPath);
        if ( ! $dirResourse ) {
            throw new Exception('Dir is not readable:' . $dirPath);
        }

        $total = 0;
        $startPage = intval($startPage);
        $number    = intval($number);
        $start = ( $startPage - 1 ) * $number;
        $end   = $startPage * $number;
        $returnList = $fileList = $dirList = array();
        while (false !== ($filename=readdir($dirResourse)) ) {
            if ('.'==$filename || '..'==$filename) {
                continue;
            }
            $_tmpName = $dirPath . DIRECTORY_SEPARATOR . $filename;
            if (is_file($_tmpName)) {
                $fileList[] = $filename;
            } else if (is_dir($_tmpName)) {
                $dirList[] = $filename;
            }
            $total++;
        }
        sort($fileList);
        sort($dirList);
        $fileList = array_merge($dirList, $fileList);
        $_pos = 0;
        foreach ($fileList as $_filename) {
            if ($_pos >= $end) {
                break;
            }
            if ($_pos >=$start) {
                $returnList[] = $_filename;
            }

            $_pos++;
        }

        return array('total'=>$total, 'page'=>$startPage, 'perpage'=>$number, 'files'=>$returnList);
    }

    /**
     * 从CDN删除文件
     * @param string $cdnName   CDN服务商名称
     * @param string $bucketName    cdn上的文件存储空间名称
     * @param string $fileKey       cdn上的文件key，即cdn上的文件名
     *
     * @return mixed 成功删除返回true， 失败返回object包含错误信息
     *
     * @example var_dump(core_filemanager::deleteFileFromCdn('qiniu', 'sinhe', 'static/css/default/common.css') );
     */
    static public function deleteFileFromCdn ($cdnName, $bucketName, $fileKey)
    {
        switch ($cdnName) {
            case 'qiniu': // 七牛cdn
            default:
                $className = 'core_cdn_qiniu'; // 默认使用七牛CDN处理类
                break;
        }
        // 实例化对应的类
        ${$className} = & loadClass($className);

        // 执行删除操作， 返回删除结果
        return ${$className}->deleteFile($bucketName, $fileKey);
    }

    /**
     * 上传文件到cdn存储
     * @param string $cdnName    CDN服务商名称
     * @param string $bucketName 存储空间名称
     * @param string $localFilePath  本地文件路径
     * @param string $qiniuPath 文件存储到cdn后的路径
     *
     * @example var_dump(json_decode(core_filemanager::uploadToCdn('qiniu','sinhe', 'static/css/default/login.css', 'static/css/default/common.css')->getResponse()->body)->error );
     *
     * @return
     */
    static public function uploadFileToCdn ($cdnName, $bucketName, $localFilePath, $cdnFilePath)
    {
        if (! is_file($localFilePath)) {
            throw new Zend_Exception('File to be uploaded to Qiniu not exist:' . $localFilePath);
        }
        switch ($cdnName) {
            case 'qiniu':
            default:
                $className = 'core_cdn_qiniu';
                break;
        }
        ${$className} = & loadClass($className);

        return ${$className}->uploadFile($bucketName, $localFilePath, $cdnFilePath);

        // 生成上传 Token
        $token = $auth->uploadToken($bucketName);

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
