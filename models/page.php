<?php
defined('iCodeBang_Com') OR die('Access denied!');
/**
 * 页面管理模型
 * @author zhoumingxia
 *
 */
class pageModel extends Model
{
    const PUBLIC_AREA_NO_LIMIT = 1;
    const PUBLIC_AREA_INSIDE   = 0;
    const PUBLIC_AREA_OUTSIDE  = 2;
    /**
     * 页码发布的区域可见范围列表
     * @var array
     */
    const PUBLIC_AREA_LIST = array (
        self::PUBLIC_AREA_OUTSIDE => '外网',
        self::PUBLIC_AREA_INSIDE  => '内网',
        self::PUBLIC_AREA_NO_LIMIT => '不限',
    );
    /**
     * 确定访问的域名id. 后续根据域名id，获取对应域名下的内容；
     */
    protected $_domainId = null;

    protected $table = 'pages';

    /**
     * 设置访问的域名id。
     *
     * @param int $domainId 域名id
     *
     * @return $this;
     */
    public function setup()
    {
        // 获取域名id
        $this->_domainId = is_int(Controller::$domainId) ? Controller::$domainId : null;

        return $this;
    }
    /**
     * 根据token获取页面嘻嘻
     * @param string $token
     * @return multitype:
     */
    public function getPageByToken($token)
    {
        $where = is_null($this->_domainId) ? '' : ' AND belong_domain = ' . $this->_domainId;
        return $this->fetch_row('pages', "url_token = '" . $this->quote($token) . "'" . $where);
    }

    /**
     * 根据页面id获取页面信息
     * @param int $id
     * @return array
     */
    public function getPageById($id)
    {
        $where = is_null($this->_domainId) ? '' : ' AND belong_domain = ' . $this->_domainId;
        return $this->fetch_row('pages', 'id = ' . intval($id) . $where);
    }

    /**
     * 添加动态页面
     */
    public function add_page($title, $keywords, $description, $contents, $url_token, $moreInfo=array())
    {
        $data = array(
            'title'         => $title,
            'keywords'      => $keywords,
            'description'   => $description,
            'contents'      => $contents,
            'url_token'     => $url_token,
            'belong_domain' => $this->_domainId,
            'add_time'      => date('Y-m-d H:i:s'),
            'modify_time'   => date('Y-m-d H:i:s'),
            'user_id'       => Application::user()->get_info('uid'),
        );
        isset($moreInfo['is_top']) AND $data['is_top'] = $moreInfo['is_top'];
        isset($moreInfo['publish_area']) AND $data['publish_area'] = $moreInfo['publish_area'];
        isset($moreInfo['publish_time']) AND $data['publish_time'] = strtotime($moreInfo['publish_time']);
        isset($moreInfo['category_id']) AND $data['category_id'] = $moreInfo['category_id'];
        isset($moreInfo['is_receipt_required']) AND $data['is_receipt_required'] = $moreInfo['is_receipt_required'];

        return $this->insert('pages', $data);
    }

    public function remove_page($id)
    {
        $where = is_null($this->_domainId) ? '' : ' AND belong_domain = ' . $this->_domainId;

        // 删除附件
        if ($attachs = $this->model('publish')->getAttachListByItemTypeAndId('page', $id)) {
            foreach ($attachs as $key => $val)
            {
                $this->model('publish')->remove_attach($val['id'], $val['access_key'], false);
            }
        }

        return $this->delete('pages', 'id = ' . intval($id) . $where);
    }

    /**
     * 更新动态页面
     */
    public function update_page($id, $title, $keywords, $description, $contents, $url_token, $moreInfo=array())
    {
        $where = is_null($this->_domainId) ? '' : ' AND belong_domain = ' . $this->_domainId;

        $data = array(
            'title'         => $title,
            'keywords'      => $keywords,
            'description'   => $description,
            'contents'      => $contents,
            'url_token'     => $url_token,
            'modify_time'   => date('Y-m-d H:i:s'),
            'user_id'       => Application::user()->get_info('uid'),
        );
        isset($moreInfo['is_top']) AND $data['is_top'] = $moreInfo['is_top'];
        isset($moreInfo['publish_area']) AND $data['publish_area'] = $moreInfo['publish_area'];
        isset($moreInfo['publish_time']) AND $data['publish_time'] = strtotime($moreInfo['publish_time']);
        isset($moreInfo['category_id']) AND $data['category_id'] = $moreInfo['category_id'];
        isset($moreInfo['is_receipt_required']) AND $data['is_receipt_required'] = $moreInfo['is_receipt_required'];

        return $this->update('pages', $data, 'id = ' . intval($id) . $where);
    }

    public function fetch_page_list($page, $limit = 10)
    {
        $where = is_null($this->_domainId) ? null : 'belong_domain = ' . $this->_domainId;
        return $this->fetch_page('pages', $where, 'is_top DESC,modify_time DESC,id DESC', $page, $limit);
    }

    public function update_page_enabled($page_id, $enabled)
    {
        $where = is_null($this->_domainId) ? '' : ' AND belong_domain = ' . $this->_domainId;

        return $this->update('pages', array(
            'enabled' => intval($enabled)
        ), 'id = ' . intval($page_id) . $where);
    }
}
