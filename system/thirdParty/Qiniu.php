<?php
/**
 * 七牛云存储组件
 *
 * @author lunixy<lunixy@smartfinancecloud.com>
 * @date 2017-06-23 10:47:43
 */

namespace app\common\components;

use Yii;
use yii\base\Component;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Qiniu extends Component
{
    public $accessKey;
    public $secretKey;
    public $auth;
    public $defaultBucket;
    public $cdnHost;

    public function init()
    {
        parent::init();
        $this->auth = new Auth($this->accessKey, $this->secretKey);
    }

    /**
     * 上传文件到七牛
     *
     * @param string $filePath 需要上传的文件路径
     * @param string $key 在七牛上存储的文件名
     *
     * @return string 上传后文件访问的地址
     */
    public function upload($filePath, $key, $bucket = null)
    {
        $uploadMgr = new UploadManager();
        $bucket = empty($bucket) ? $this->defaultBucket : $bucket;
        $token = $this->auth->uploadToken($bucket);
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            Yii::$app->response->error(
                Yii::$app->response->resCode['ERROR_QINIU_UPLOAD_FAILED'],
                'Upload to qiniu failed.'
            );
        } else {
            return $this->cdnHost . $ret['key'];
        }
    }

    /**
     * 生成七牛 CDN URL
     *
     * @param string $key 相对路径
     *
     * @return string
     */
    public function generateUrl($key)
    {
        return !empty($key) ? $this->cdnHost . trim($key, '/') : '';
    }
}
