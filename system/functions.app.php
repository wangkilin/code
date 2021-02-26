<?php
/**
 * WeCenter Framework
 *
 * An open source application development framework for PHP 5.2.2 or newer
 *
 * @package    WeCenter Framework
 * @author        WeCenter Dev Team
 * @copyright    Copyright (c) 2011 - 2014, WeCenter, Inc.
 * @license        http://www.wecenter.com/license/
 * @link        http://www.wecenter.com/
 * @since        Version 1.0
 * @filesource
 */

/**
 * WeCenter APP 函数类
 *
 * @package        WeCenter
 * @subpackage    App
 * @category    Libraries
 * @author        WeCenter Dev Team
 */


/**
 * 获取头像地址
 *
 * 举个例子：$uid=12345，那么头像路径很可能(根据您部署的上传文件夹而定)会被存储为/uploads/000/01/23/45_avatar_min.jpg
 *
 * @param  int
 * @param  string
 * @return string
 */
function get_avatar_url($uid, $size = 'min')
{
    $uid = intval($uid);

    if (!$uid)
    {
        return G_STATIC_URL . '/common/avatar-' . $size . '-img.png';
    }

    foreach (Application::config()->get('image')->avatar_thumbnail as $key => $val)
    {
        $all_size[] = $key;
    }

    $size = in_array($size, $all_size) ? $size : $all_size[0];

    $uid = sprintf("%09d", $uid);
    $dir1 = substr($uid, 0, 3);
    $dir2 = substr($uid, 3, 2);
    $dir3 = substr($uid, 5, 2);

    if (file_exists(get_setting('upload_dir') . '/avatar/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($uid, - 2) . '_avatar_' . $size . '.jpg'))
    {
        return get_setting('upload_url') . '/avatar/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($uid, - 2) . '_avatar_' . $size . '.jpg';
    }
    else
    {
        return G_STATIC_URL . '/common/avatar-' . $size . '-img.png';
    }
}

/**
 * 附件url地址，实际上是通过一定格式编码指配到/app/file/main.php中，让download控制器处理并发送下载请求
 * @param  string $file_name 附件的真实文件名，即上传之前的文件名称，包含后缀
 * @param  string $url 附件完整的真实url地址
 * @return string 附件下载的完整url地址
 */
function download_url($file_name, $url)
{
    return get_js_url('/file/download/file_name-' . base64_encode($file_name) . '__url-' . base64_encode($url));
}

/**
 * 检测当前操作是否需要验证码
 * @param string $permission_tag 权限标识
 *
 * @return bool
 */
function human_valid($permission_tag)
{
    if (! is_array(Application::session()->human_valid))
    {
        return FALSE;
    }

    if (! Application::session()->human_valid[$permission_tag] or ! Application::session()->permission[$permission_tag])
    {
        return FALSE;
    }

    foreach (Application::session()->human_valid[$permission_tag] as $time => $val)
    {
        if (date('H', $time) != date('H', time()))
        {
            unset(Application::session()->human_valid[$permission_tag][$time]);
        }
    }

    if (sizeof(Application::session()->human_valid[$permission_tag]) >= Application::session()->permission[$permission_tag])
    {
        return TRUE;
    }

    return FALSE;
}

function set_human_valid($permission_tag)
{
    if (! is_array(Application::session()->human_valid))
    {
        return FALSE;
    }

    Application::session()->human_valid[$permission_tag][time()] = TRUE;

    return count(Application::session()->human_valid[$permission_tag]);
}

/**
 * 仅附件处理中的preg_replace_callback()的每次搜索时的回调
 * @param  array $matches preg_replace_callback()搜索时返回给第二参数的结果
 * @return string  取出附件的加载模板字符串
 */
function parse_attachs_callback($matches)
{
    if ($attach = Application::model('publish')->getAttachById($matches[1]))
    {
        View::assign('attach', $attach);

        return View::output('question/ajax/load_attach', false);
    }
}

/**
 * 获取模块图片指定尺寸的完整url地址
 * @param  string $module     模块名称
 * @param  string $filename   文件名
 * @return int    $addedTime  图片上传的时间
 */
function getModuleUploadedFileUrl($module, $filename, $addedTime=null)
{
    if ($addedTime) {
        $filename = gmdate('Ymd/', $addedTime) . $filename;
    }

    return get_setting('upload_url') . '/'.$module.'/' . $filename;
}

/**
 * 获取模块图片指定尺寸的完整url地址
 * @param  string $size
 * @param  string $pic_file 某一尺寸的图片文件名
 * @return string           取出主题图片或主题默认图片的完整url地址
 */
function getModulePicUrlBySize($module, $size = null, $pic_file = null)
{
    if ($pic_file && ($sized_file = getUploadedModulePicNameBySize($module, $pic_file, $size))) {
        return get_setting('upload_url') . '/'.$module.'/' . $sized_file;
    }

    if (! $size || ! $pic_file) {
        $size = 'mid';
    }

    return G_STATIC_URL . '/common/'.$module.'-' . $size . '-img.png';
}

/**
 * 获取专题图片指定尺寸的完整url地址
 * @param  string $size     三种图片尺寸 max(100px)|mid(50px)|min(32px)
 * @param  string $pic_file 某一尺寸的图片文件名
 * @return string           取出专题图片的完整url地址
 */
function get_feature_pic_url($size = null, $pic_file = null)
{
    if (! $pic_file)
    {
        return false;
    }
    else
    {
        if ($size)
        {
            $pic_file = str_replace(Application::config()->get('image')->feature_thumbnail['min']['w'] . '_' . Application::config()->get('image')->feature_thumbnail['min']['h'], Application::config()->get('image')->feature_thumbnail[$size]['w'] . '_' . Application::config()->get('image')->feature_thumbnail[$size]['h'], $pic_file);
        }
    }

    return get_setting('upload_url') . '/feature/' . $pic_file;
}



/**
 * 上传文件处理， 不受session限制
 * @param string $module 模块名称
 * @param string $batchKey 上传文件的key
 * @param string $filename 文件名称， 需要包含扩展名
 * @param string $dataStream 文件的二进制数据流
 *
 * @return array
 */
function doUploadAttach ($module, $batchKey, $filename, $dataStream=null)
{
    $settings = Application::$settings = Application::model('setting')->get_settings();
    Application::upload()->initialize(array(
        'allowed_types' => $settings['allowed_upload_types'],
        'upload_path'   => $settings['upload_dir'] . '/' . $module . '/' . gmdate('Ymd', APP_START_TIME),
        'is_image'      => FALSE,
        'max_size'      => $settings['upload_size_limit']
    ));

    if($dataStream) {
        Application::upload()->do_upload($filename, $dataStream);
    } else if (isset($_GET[$filename])) {
        Application::upload()->do_upload($_GET[$filename], file_get_contents('php://input'));
    } else if (isset($_FILES[$filename])) {
        Application::upload()->do_upload($filename);
    } else {
        return false;
    }

    // 检查上传结果
    if (Application::upload()->get_error()) {
        switch (Application::upload()->get_error()) {
            case 'upload_invalid_filetype':
                return Application::RSM(null, '-1', _t('文件类型无效'));
                break;

            case 'upload_invalid_filesize':
                return Application::RSM(null, '-1', _t('文件尺寸过大, 最大允许尺寸为 %s KB', get_setting('upload_size_limit')));
                break;

            default:
                return Application::RSM(null, '-1', _t('错误代码') . ': ' . Application::upload()->get_error());
                break;
        }
    }

    if (! Application::upload()->data()) {
        return Application::RSM(null, '-1', _t('上传失败, 请与管理员联系'));
    }

    $upload_data = Application::upload()->data();

    if ($upload_data['is_image'] == 1) {
        foreach (Application::config()->get('image')->attachment_thumbnail AS $key => $val)
        {
            $thumb_file[$key] = getUploadPicFileNameBySize($val);

            Application::image()->initialize(array(
                'quality'      => 90,
                'source_image' => $upload_data['full_path'],
                'new_image'    => $thumb_file[$key],
                'width'        => $val['w'],
                'height'       => $val['h']
            ))->resize();
        }
    }

    $cssClass = Application::model('publish')->getCssClassByFileName(basename($upload_data['full_path']));
    // 插入数据库
    $attach_id = Application::model('publish')
                      ->add_attach(
                              $module,
                              $upload_data['orig_name'],
                              $batchKey,
                              APP_START_TIME,
                              basename($upload_data['full_path']),
                              $upload_data['is_image'],
                              $upload_data['file_type'],
                              $cssClass
                        );

    $output = array(
        'success' => true,
        'delete_url' => get_js_url('/publish/ajax/remove_attach/attach_id-' . Application::crypt()->encode(json_encode(array(
            'attach_id'  => $attach_id,
            'access_key' => $batchKey
        )))),
        'attach_id'  => $attach_id,
        'attach_tag' => 'attach'

    );

    $attach_info = Application::model('publish')->getAttachById($attach_id);
    if ($attach_info['thumb']) {
        $output['thumb'] = $attach_info['thumb'];
    } else {
        $output['class_name'] = $cssClass;
    }
    $output['url'] = $attach_info['attachment'];

    //exit(htmlspecialchars(json_encode($output), ENT_NOQUOTES));
    return $output;
}


/**
 * 发表文章
 */
function doPublishArticle($userId, $title, $content, $categoryId=1, $topics=array(), $attachAccessKey='', $moreInfo=array())
{
    $settings = Application::$settings = Application::model('setting')->get_settings();
    if (!$title) {
        return (Application::RSM(null, - 1, Application::lang()->_t('请输入文章标题')));
    }
    // 检查文章标题长度
    if ($settings['question_title_limit'] > 0 AND cjk_strlen($title) > $settings['question_title_limit']) {
        return (Application::RSM(null, '-1', Application::lang()->_t('文章标题字数不得大于 %s 字节', $settings['question_title_limit'])));
    }
    // // 只允许插入当前页面上传的附件
    // if (!$this->model('publish')->insert_attach_is_self_upload($_POST['message'], $_POST['attach_ids']))
    // {
    //     H::ajax_json_output(Application::RSM(null, '-1', Application::lang()->_t('只允许插入当前页面上传的附件')));
    // }


    if ($topics) {
        foreach ($topics AS $key => $topic_title) {
            $topic_title = trim($topic_title);

            if ($topic_title) {
                $topics[$key] = $topic_title;
            } else {
                unset($_POST['topics'][$key]);
            }
        }

        if ($settings['question_topics_limit'] AND sizeof($topics) > $settings['question_topics_limit'])
        {
            return(Application::RSM(null, '-1', Application::lang()->_t('单个文章话题数量最多为 %s 个, 请调整话题数量', $settings['question_topics_limit'])));
        }
    }
    if ($settings['new_question_force_add_topic'] == 'Y' AND !$topics) {
        return(Application::RSM(null, '-1', Application::lang()->_t('请为文章添加话题')));
    }

    Application::model('draft')->delete_draft(1, 'article', $userId);



    if (H::sensitive_word_exists($content)) {
        Application::model('publish')->publish_approval('article', array(
            'title' => $title,
            'message' => $content,
            'category_id' => $categoryId,
            'topics' => $topics,
            'permission_create_topic' => true
        ), $userId, $attachAccessKey);

        return(Application::RSM(array(
            'url' => get_js_url('/publish/wait_approval/')
        ), 1, null));

    } else {
        // 添加
        $articleInfo = array(
            'uid'          => intval($userId),
            'title'        => htmlspecialchars($title),
            'message'      => $content,
            'category_id'  => intval($categoryId),
            'add_time'     => time(),
            'content_type' => 1
        );
        $articleInfo = array_merge($articleInfo, $moreInfo);
        if ($article_id = Application::model()->insert('article',  $articleInfo) ) {

            if (is_array($topics)) {
                foreach ($topics as $key => $topic_title) {
                    $topic_id = Application::model('topic')->saveTopic($topic_title, $userId, true);

                    Application::model('topic')->setTopicItemRelation($userId, $topic_id, $article_id, 'article');
                }
            }

            if ($attachAccessKey) {
                Application::model('publish')->update_attach('article', $article_id, $attachAccessKey);
            }

            Application::model('search_fulltext')->push_index('article', $title, $article_id);

            // 记录日志
            ACTION_LOG::save_action($userId, $article_id, ACTION_LOG::CATEGORY_QUESTION, ACTION_LOG::ADD_ARTICLE, $title, $content, 0);

            Application::model('posts')->set_posts_index($article_id, 'article');

            Application::model()->update('users', array(
                'article_count' => Application::model()->count('article', 'uid = ' . intval($userId))
            ), 'uid = ' . intval($userId));
        }
        $url = get_js_url('/article/' . $article_id);

        return(Application::RSM(array('url' => $url, 'id'=>$article_id), 1, null));
    }
}

function doPublishCourse ()
{

}

function doPublishCourseTable ()
{

}

/**
 * 请求远端服务器
 * @param  string   $url    请求的url
 * @param  string   $method  请求方法
 * @param  array    $headers 请求header
 * @param  resource $body    上传文件资源
 * @return boolean
 */
function curlRequest($url, $method, $headers = null, $body = null)
{
    $ch  = curl_init($url);

    $_headers = array('Expect:');
    if (!is_null($headers) && is_array($headers)){
        foreach($headers as $k => $v) {
            array_push($_headers, "{$k}: {$v}");
        }
    }

    $length = 0;
    $date   = gmdate('D, d M Y H:i:s \G\M\T');

    if (!is_null($body)) {
        if(is_resource($body)){
            fseek($body, 0, SEEK_END);
            $length = ftell($body);
            fseek($body, 0);

            array_push($_headers, "Content-Length: {$length}");
            curl_setopt($ch, CURLOPT_INFILE, $body);
            curl_setopt($ch, CURLOPT_INFILESIZE, $length);
        } else {
            if (is_array($body)) {
                $body = http_build_query($body);
            }
            $length = @strlen($body);
            array_push($_headers, "Content-Length: {$length}");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
    } else if ($method == 'PUT' || $method == 'POST'){
        array_push($_headers, "Content-Length: {$length}");
    }

    // array_push($_headers, 'Authorization: ' . $this->sign($method, $uri, $date, $length));
    array_push($_headers, "Date: {$date}");

    curl_setopt($ch, CURLOPT_HTTPHEADER, $_headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    // https 请求， 忽略验证
    if ( (is_string($url) && stripos($url, 'https://')===0)
        || (isset($_headers['scheme']) && strtolower($_headers['scheme'])=='https') ) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    if ($method == 'PUT' || $method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
    } else {
        curl_setopt($ch, CURLOPT_POST, 0);
    }

    if ($method == 'HEAD') {
        curl_setopt($ch, CURLOPT_NOBODY, true);
    }

    $response = curl_exec($ch);
    $error = curl_error($ch); // 记录错误
    $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    list($header, $body) = explode("\r\n\r\n", $response, 2);
    if ($status == 200) {
        if ($method == 'GET') {
            return $body;
        } else {
            return $response;
        }
    } else {
        $return = array('status'=>$status);
        if ($error) {
            $return['error'] = $error;
        }
        return $return;
    }
}


/**
 * 根据图片在数据库中的名字和指定尺寸， 获取模块上传图片的文件名
 * @param string $moduleName
 * @param string $picNameInDb
 * @param string $size
 * @return mixed
 */
function getUploadedModulePicNameBySize($moduleName, $picNameInDb, $size = null)
{
    $imgConfig = Application::config()->get('image')->{$moduleName . '_thumbnail'};
    $baseFile = str_replace('_' . $imgConfig['min']['w'] . 'x' . $imgConfig['min']['h'] . '.', '.', $picNameInDb);

    if (! $size && ! isset($imgConfig[$size])) {
        return $baseFile;
    }

    return str_replace('.', '_' . $imgConfig[$size]['w'] . 'x' . $imgConfig[$size]['h'] . '.', $baseFile);
}

function get_host_top_domain()
{
    $host = strtolower($_SERVER['HTTP_HOST']);

    if (strpos($host, '/') !== false)
    {
        $parse = @parse_url($host);
        $host = $parse['host'];
    }

    $top_level_domain_db = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me', 'jp', 'uk', 'ws', 'eu', 'pw', 'kr', 'io', 'us', 'cn');

    foreach ($top_level_domain_db as $v)
    {
        $str .= ($str ? '|' : '') . $v;
    }

    $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";

    if (preg_match('/' . $matchstr . '/ies', $host, $matchs))
    {
        $domain = $matchs['0'];
    }
    else
    {
        $domain = $host;
    }

    return $domain;
}

function parse_link_callback($matches)
{
    if (preg_match('/^(?!http).*/i', $matches[1]))
    {
        $url = 'http://' . $matches[1];
    }
    else
    {
        $url = $matches[1];
    }

    if (is_inside_url($url))
    {
        return '<a href="' . $url . '">' . FORMAT::sub_url($matches[1], 50) . '</a>';
    }
    else
    {
        return '<a href="' . $url . '" rel="nofollow" target="_blank">' . FORMAT::sub_url($matches[1], 50) . '</a>';
    }
}

function is_inside_url($url)
{
    if (!$url)
    {
        return false;
    }

    if (preg_match('/^(?!http).*/i', $url))
    {
        $url = 'http://' . $url;
    }

    $domain = get_host_top_domain();

    if (preg_match('/^http[s]?:\/\/([-_a-zA-Z0-9]+[\.])*?' . $domain . '(?!\.)[-a-zA-Z0-9@:;%_\+.~#?&\/\/=]*$/i', $url))
    {
        return true;
    }

    return false;
}

function get_weixin_rule_image($image_file, $size = '')
{
    return Application::model('weixin')->get_weixin_rule_image($image_file, $size);
}

function import_editor_static_files()
{
    //View::import_js('js/ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js');
    View::import_js('js/editor/ckeditor.4.11/ckeditor.js');
    //View::import_js('js/editor/ckeditor/ckeditor.js');
    //View::import_js('js/ckeditor/ckeditor.js');
    //View::import_js('js/ckeditor/ckeditor.js');
    View::import_js('js/editor/ckeditor/adapters/jquery.js');
}

function get_chapter_icon_url($id, $size = 'max', $default = true)
{
    if (file_exists(get_setting('upload_dir') . '/chapter/' . $id . '-' . $size . '.jpg'))
    {
        return get_setting('upload_url') . '/chapter/' . $id . '-' . $size . '.jpg';
    }
    else if ($default)
    {
        return G_STATIC_URL . '/common/help-chapter-' . $size . '-img.png';
    }

    return false;
}

function base64_url_encode($parm)
{
    if (!is_array($parm))
    {
        return false;
    }

    return strtr(base64_encode(json_encode($parm)), '+/=', '-_,');
}

function base64_url_decode($parm)
{
    return json_decode(base64_decode(strtr($parm, '-_,', '+/=')), true);
}

function remove_assoc($from, $type, $id)
{
    if (!$from OR !$type OR !is_digits($id))
    {
        return false;
    }

    return $this->query('UPDATE ' . $this->get_table($from) . ' SET `' . $type . '_id` = NULL WHERE `' . $type . '_id` = ' . $id);
}

/**
 * 用给定数组， 生成页面下拉列表的option数据。
 * @param array $dataList 二维数组
 * @param string $textKey 数组中用来生成option文本的键值
 * @param string $valueKey 数组中用来生成option value的键值
 * @param string|int|array $defaultValue 默认值
 * @param array $bindAttributes 生成option中的属性绑定值
 *
 * @return string
 */
function buildSelectOptions (array $dataList, $textKey, $valueKey, $defaultValue=null, array $bindAttributes=array())
{
    $html = '';

    isset($defaultValue) && !is_array($defaultValue) AND $defaultValue = array($defaultValue);
    foreach ($dataList as $_item) {
        if (isset($defaultValue) && in_array($_item[$valueKey], $defaultValue)) {
            $attributes  = ' selected="selected"';
        } else {
            $attributes  = '';
        }

        foreach ($_item as $_key=>$_value) {
            if (isset($bindAttributes[$_key])) {
                $attributes .= ' ' . $bindAttributes[$_key] .'="' . $_value . '"';
            } else if ($_key==$textKey || $_key==$valueKey) {
                continue;
            } else {
                continue;
                //$attributes .= ' data-' . $_key .'="' . $_value . '"';
            }
        }
        $html .= '<option value="' . $_item[$valueKey]. '"' . $attributes . '>' . $_item[$textKey] . '</option>';
    }

    return $html;
}
/**
 * 将列表数据，进行树形化名称返回
 * @param array $lists
 * @param string $titleKey
 * @param string $idKey
 * @param string $parentIdKey
 *
 * @return array
 */
function getListInTreeList ($lists, $titleKey='title', $idKey='id', $parentIdKey='parent_id')
{
    $tmpSortList = array();
    foreach ($lists as & $_item) {
        $_tmpId = $_item[$parentIdKey];
        $_tmpKey = '/' . $_tmpId;
        if (! isset($lists[$_tmpId])) {
            $tmpSortList[$_tmpKey  . '/' . $_item[$idKey] .'/'] = $_item;
            continue;
        }
        $_item[$titleKey] = '|__' . $_item[$titleKey];
        while(isset($lists[$_tmpId])) {
            $_item[$titleKey] = '&nbsp; &nbsp; ' . $_item[$titleKey];
            $_tmpId = $lists[$_tmpId][$parentIdKey];
            $_tmpKey = '/' . $_tmpId . $_tmpKey;
        }
        $tmpSortList[$_tmpKey  . '/' . $_item[$idKey] .'/' ] = $_item;
    }
    ksort($tmpSortList);
    $lists = array_values($tmpSortList);

    return $lists;
}
