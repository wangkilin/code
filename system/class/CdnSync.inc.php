<?php
/**
 *
 */
class CdnSync
{
    /**
     * http请求失败
     */
    const ERROR_HTTP_REQUEST   = 1;
    /**
     * 解析分类错误
     */
    const ERROR_PARSE_CATEGORY = 2;
    /**
     * 解析内容错误
     */
    const ERROR_PARSE_CONTENT  = 4;

    /**
     * 待解析的http内容
     */
    private $_content = '';

    /**
     * 全局配置信息
     */
    protected $configInfo = array();

    protected $db = null;
    protected $model = null;

    protected $jsonDbMap = array(
            "popular_rank"   => 'rank',//string(5) "1#铜"
    );

    /**
     * 主分类信息列表
     */
    protected $categoryList = [];

    /**
     * 调试信息
     */
    protected $debugInfos = [];

    /**
     * 是否有错误发生。 如有错误， 需要将错误信息发送到邮件
     */
    protected $hasError = false;

    public function getDebugInfo ()
    {
        return $this->debugInfos;
    }

    public function hasError ()
    {
        return $this->hasError;
    }

    public function loadDb ()
    {

        loadClass('core_config');
        $this->db = loadClass('core_db');
    }

    public function __construct ($configInfo=array())
    {

        $this->loadDb();
        $this->model = Application::model();
    }

    public function getDb ()
    {
        return $this->db;
    }

    public function getModel ()
    {
        return $this->model;
    }

    /**
     * 将对应目录下的文件，存放到数据库中， 等待cdn同步。
     *   1. 获取根路径在数据库的信息
     *   2. 获取目录下的内容， 做处理
     *      2.1 文件为目录， 递归处理
     *      2.2 文件为普通文件，存储到数据库
     *   3. 获取数据库中是否已有对应文件信息， 有进行更新，没有进行插入
     * @param int $dbRootId 上传根目录在数据库中的id
     * @param string $dir 待处理的目录完整路径
     * @param string $baseDir 基础目录路径。 存储目录是，会把基础目录前缀删除
     */
    public function loadFileIntoDb ($dbRootId, $dir, $baseDir='')
    {
        static $localRootInfo = array();

        //var_dump($dbRootId, $dir, $baseDir, __LINE__);
        if (! $localRootInfo[$dbRootId]) {
            $localRootInfo[$dbRootId] = $this->model->fetch_row('cdn_local_root', 'id = ' . intval($dbRootId));
        }
        try {
            $fileList = core_filemanager::getDirContentByPage($dir, 1, 1000000);
        } catch (Exception $e) {
            echo $e->getMessage();
            var_dump($dbRootId, $dir, $baseDir);
            return;
        }

        $baseDir = rtrim($baseDir, '\\/');
        $storeDir = ($baseDir!=='' && strpos($dir, $baseDir)===0) ? substr($dir, strlen($baseDir)) : $dir;
        $storeDir = trim($storeDir, '\\/');
        $batchTime = time();
        foreach ($fileList['files'] as $_item) {
            if ($_item['type']=='dir') {
                $this->loadFileIntoDb($dbRootId, $dir . DS . $_item['name'], $baseDir);
            } else {
                // 查找数据库中是否已经有对应文件的信息
                $dbFileInfo = $this->model->fetch_row('cdn_local_file',
                                                    'file_name = "'.$this->model->quote($_item['name'])
                                                      .'" AND file_path = "' . $this->model->quote($storeDir)
                                                      . '" AND local_root_id = ' . intval($dbRootId)
                                            );
                $setLocal = array(
                    'file_name'     => $_item['name'],// varchar(255) NOT NULL DEFAULT '' COMMENT '本地文件在local root下的路径',
                    'file_path'     => $storeDir,// varchar(255) NOT NULL DEFAULT '' COMMENT '本地文件在local root下的目录路径',
                    'modify_time'   => $_item['stat']['mtime'],// int(11) NOT NULL COMMENT '文件最后的修改时间。用来和上传到cdn时间比较， 决定是否覆盖上传',
                    'local_root_id' => $dbRootId, // smallint(3) unsigned NOT NULL COMMENT '本地设置的文件管理顶级目录。 关联到 cdn_local_root表id',
                    'is_deleted'    => 0 ,// tinyint(1) NOT NULL DEFAULT '0' COMMENT '文件是否被删除了',
                    'check_time'    => $batchTime,
                );

                $setCdn = array(
                    'cdn_bucket_id' => $localRootInfo[$dbRootId]['cdn_bucket_id'], // smallint(3) NOT NULL COMMENT '云存储空间名称',
                    'cdn_file_key'  => $storeDir . DS . $_item['name'],  // varchar(255) NOT NULL DEFAULT '' COMMENT '文件在cdn上的key，对应cdn路径',
                    //'local_file_id' => $fileId, // int(11) unsigned NOT NULL COMMENT '本地设置的文件id',
                    'upload_time'   => 0,   // int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件上传时间',
                    'status'        => 1,        // tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态： 1-待上传；2-上传过，但是更新后没覆盖到cdn；3-已上传到cdn；0-文件被删除',
                );

                if ($dbFileInfo) { // 有对应文件信息， 执行更新操作
                    // 更新检查时间
                    $fileId = $this->model->update('cdn_local_file', $setLocal, 'id = ' . $dbFileInfo['id']);
                    if ($setLocal['modify_time']>$dbFileInfo['modify_time']) { // 检查文件是否有更新，没有更新将状态设置成上传过
                        $setCdn['status'] = 2;
                        unset($setCdn['upload_time']);
                        $this->model->update('cdn_file_map', $setCdn, 'local_file_id = ' . $dbFileInfo['id'] .' AND cdn_bucket_id = ' . $localRootInfo[$dbRootId]['cdn_bucket_id']);
                    }
                } else { // 没有对应文件信息， 添加新记录

                    $fileId = $this->model->insert('cdn_local_file', $setLocal);
                    $setCdn['local_file_id'] = $fileId;
                    $this->model->insert('cdn_file_map', $setCdn);
                }
            }
        }
        // 目录处理后， 没有处理到的数据，标记成删除状态
        $this->model->update('cdn_local_file',
                             array('is_deleted' => '1'),
                            'check_time != '.$batchTime
                              . ' AND file_path = "' . $this->model->quote($storeDir) . '"'
                              . ' AND local_root_id = ' . intval($dbRootId)
                    );

    }

    public function uploadFileToCdn ()
    {
        $bucketList = $this->model->fetch_all('cdn_bucket');
        $ids = array_column($bucketList, 'id');
        $bucketList = array_combine($ids, $bucketList);
        $list = $this->model->fetch_all('cdn_file_map', 'status = 1 OR status = 2', 'id DESC', 30000);
        $localFileIds = array_column($list, 'local_file_id');
        $localFileList = $this->model->fetch_all('cdn_local_file', 'id IN ("' .join('","', $localFileIds) .'")');
        $localFileIds  = array_column($localFileList, 'id');
        $localFileList = array_combine($localFileIds, $localFileList);
        $timesLimit = 30000;
        foreach ($list as $_item) {
            if ($timesLimit++ > $timesLimit) {
                break;
            }
            $_filePath = WEB_ROOT_DIR . $localFileList[$_item['local_file_id']]['file_path'] . DS . $localFileList[$_item['local_file_id']]['file_name'];
            $_cdnName = $bucketList[$_item['cdn_bucket_id']]['cdn_name'];
            $_bucketName = $bucketList[$_item['cdn_bucket_id']]['bucket_name'];
            //var_dump($_cdnName, $_bucketName, $_filePath, $_item['cdn_file_key']);
            $tryTimes = 0;
            while ($tryTimes++ < 2) {
                try {
                    $uploadResult = core_filemanager::uploadFileToCdn($_cdnName, $_bucketName, $_filePath, $_item['cdn_file_key']);

                    if (is_array($uploadResult) && isset($uploadResult['key'])) { // 上传成功
                        $this->model->update('cdn_file_map', array('status'=>3, 'upload_time'=>time()), 'id = ' . $_item['id']);
                    } else {
                        //echo $_filePath, '-------', $uploadResult->getResponse()->body , "\r\n";
                        if (strpos($uploadResult->getResponse()->body, '"error":"file exists"')) {
                            core_filemanager::deleteFileFromCdn($_cdnName, $_bucketName, $_item['cdn_file_key']);
                            continue;
                        }
                    }
                } catch (Exception $e) {
                    echo 'Error:', $_filePath, '-----', $e->getMessage() . "\r\n";
                }
                break;
            }
        }
    }

}

/* EOF */
