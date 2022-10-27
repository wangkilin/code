<?php
/**
 *
 */
class CleanData
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
     * 清理附件垃圾。 没有和数据库中的数据条目绑定的文件， 移除
     */
    public function cleanAttach ($dir)
    {
        // 获取目录下的文件列表
        $fileList = core_filemanager::getDirContentByPage($dir, 1, 1000000);
        // 如果目录返回的文件为空格， 删除这个目录
        if ($fileList['total']==0) {
            //rename($dir, rtrim($dir) . '.remove');
            rmdir($dir);
        }
        // 逐一处理文件
        $dir = rtrim($dir, DS);
        foreach ($fileList['files'] as $_item) {
            if ($_item['type']=='dir') { // 如果是目录， 递归执行
                $this->cleanAttach($dir . DS . $_item['name']);
                continue;
            }

            if (strpos($_item['name'], '_')) { // 忽略缩略图文件
                continue;
            }

            // 在附件表中查找文件。 如果没找到， 将文件删除
            if ($this->getModel()->fetch_row('attach', 'file_location ="' . $_item['name'] .'"')) {
                continue;
            } else {
                echo $dir . DS . $_item['name'], "\r\n";
                $_filenameInfo = explode('.', $_item['name']);
                if (count($_filenameInfo)==2) { // 删除缩小的图片
                    @ unlink($_filenameInfo[0] . '_90x90.' . $_filenameInfo[1]);
                    @ unlink($_filenameInfo[0] . '_170x110.' . $_filenameInfo[1]);
                }
                @ unlink($dir . DS . $_item['name']);
            }
        }

    }

    /**
     * 清除没有绑定内容的附件
     */
    public function cleanUnbindAttach ()
    {
        $debug = false;
        // 1. 找到附件表中没有绑定内容的， 逐条操作
        // 将12小时前的文件，只要没有绑定内容，删除
        $attachList = $this->getModel()->fetch_all('attach', 'item_id=0 and item_type="article" and add_time<' . (time() - 3600 * 12) , 'id desc');
        echo count($attachList) . "\r\n";
        foreach ($attachList as $_itemInfo) {
            $_itemInfo['add_date_time'] = date('Y-m-d H:i:s', $_itemInfo['add_time']);
            $debug AND var_dump($_itemInfo);
            list($k, $v) = each(Application::model('publish')->parse_attach_data(array($_itemInfo), 'article') );
            // $v = array(7) {
            //     ["id"]=>
            //     int(1)
            //     ["is_image"]=>
            //     int(1)
            //     ["file_name"]=>
            //     string(12) "404-logo.png"
            //     ["access_key"]=>
            //     NULL
            //     ["file_location"]=>
            //     string(36) "2eb7c1aca991bb54572c5cc6215ef604.png"
            //     ["attachment"]=>
            //     string(54) "/article/20170731/2eb7c1aca991bb54572c5cc6215ef604.png"
            //     ["path"]=>
            //     string(54) "/article/20170731/2eb7c1aca991bb54572c5cc6215ef604.png"
            //   }
            // 2. 根据找到的文件， 查找关联cdn数据的基表内容。 如果附件已经载入到基表中， 可能已经上传到cdn上。 还需要删除cdn文件内容
            $fileInCdnLocalFileInfo = Application::model()->fetch_row('cdn_local_file', 'file_name = "'.$v['file_location'].'"');
            $debug AND var_dump('CdnLocalFile', $fileInCdnLocalFileInfo);
            // 有cdn数据， 将相关cdn数据删除。  1. 删除小图，2. 删除中图， 3. 删除大图
            if ($fileInCdnLocalFileInfo) {
                $file90InCdnLocalFileInfo =  Application::model()->fetch_row('cdn_local_file', 'file_name = "'.str_replace('.', '_90x90.',$v['file_location']).'"');
                $debug AND var_dump('file90Local', $file90InCdnLocalFileInfo);
                if ($file90InCdnLocalFileInfo) {
                    $file90CdnInfo = Application::model()->fetch_row('cdn_file_map', 'local_file_id = "'.$file90InCdnLocalFileInfo['id'].'"');
                    $debug AND var_dump('file90Cdn', $file90CdnInfo);
                    if ($file90CdnInfo) {
                        $bucketInfo = Application::model()->fetch_row('cdn_bucket', 'id = ' . $file90CdnInfo['cdn_bucket_id']);
                        $file90CdnInfo['status']==3 && core_filemanager::deleteFileFromCdn($bucketInfo['cdn_name'], $bucketInfo['bucket_name'], $file90CdnInfo['cdn_file_key']);
                        @unlink(WEB_ROOT_DIR . $file90CdnInfo['cdn_file_key']);
                        $debug AND var_dump('unlink', WEB_ROOT_DIR . $file90CdnInfo['cdn_file_key']);
                        Application::model()->delete('cdn_file_map', 'local_file_id = "'.$file90InCdnLocalFileInfo['id'].'"');
                    }

                    Application::model()->delete('cdn_local_file', 'file_name = "'.str_replace('.', '_90x90.',$v['file_location']).'"');

                }
                $file170InCdnLocalFileInfo =  Application::model()->fetch_row('cdn_local_file', 'file_name = "'.str_replace('.', '_170x110.',$v['file_location']).'"');
                if ($file170InCdnLocalFileInfo) {
                    $file170CdnInfo = Application::model()->fetch_row('cdn_file_map', 'local_file_id = "'.$file170InCdnLocalFileInfo['id'].'"');
                    if ($file170CdnInfo) {
                        $bucketInfo = Application::model()->fetch_row('cdn_bucket', 'id = ' . $file170CdnInfo['cdn_bucket_id']);
                        $file170CdnInfo['status']==3 && core_filemanager::deleteFileFromCdn($bucketInfo['cdn_name'], $bucketInfo['bucket_name'], $file170CdnInfo['cdn_file_key']);
                        @unlink(WEB_ROOT_DIR . $file170CdnInfo['cdn_file_key']);
                        Application::model()->delete('cdn_file_map', 'local_file_id = "'.$file170InCdnLocalFileInfo['id'].'"');
                    }

                    Application::model()->delete('cdn_local_file', 'file_name = "'.str_replace('.', '_170x110.',$v['file_location']).'"');
                }


                $fileCdnInfo = Application::model()->fetch_row('cdn_file_map', 'local_file_id = "'.$fileInCdnLocalFileInfo['id'].'"');
                $debug AND var_dump('fileCdnInfo', $fileCdnInfo);
                if ($fileCdnInfo) {
                    $bucketInfo = Application::model()->fetch_row('cdn_bucket', 'id = ' . $fileCdnInfo['cdn_bucket_id']);
                    $fileCdnInfo['status']==3 && core_filemanager::deleteFileFromCdn($bucketInfo['cdn_name'], $bucketInfo['bucket_name'], $fileCdnInfo['cdn_file_key']);
                    @unlink(WEB_ROOT_DIR . $fileCdnInfo['cdn_file_key']);
                    $debug AND var_dump('unlink', WEB_ROOT_DIR . $fileCdnInfo['cdn_file_key']);
                    Application::model()->delete('cdn_file_map', 'local_file_id = "'.$fileInCdnLocalFileInfo['id'].'"');
                }
                Application::model()->delete('cdn_local_file', 'file_name = "'.$v['file_location'].'"');
            }

            $debug AND var_dump('unlink', WEB_ROOT_DIR . 'uploads/article/' .date('Ymd', $_itemInfo['add_time']).'/' . $v['file_location']);
            @unlink(WEB_ROOT_DIR . 'uploads/article/' . date('Ymd', $_itemInfo['add_time']).'/' . $v['file_location']);
            @unlink(WEB_ROOT_DIR . 'uploads/article/' . date('Ymd', $_itemInfo['add_time']).'/' . str_replace('.', '_90x90.',$v['file_location']));
            @unlink(WEB_ROOT_DIR . 'uploads/article/' . date('Ymd', $_itemInfo['add_time']).'/' . str_replace('.', '_170x110.',$v['file_location']));

            Application::model()->delete('attach', 'id = ' . $v['id']);
        }
    }

}

/* EOF */
