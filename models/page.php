<?php
defined('iCodeBang_Com') OR die('Access denied!');
/**
 * 页面管理模型
 * @author zhoumingxia
 *
 */
class pageModel extends Model
{
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

    public function add_page($title, $keywords, $description, $contents, $url_token)
    {
        return $this->insert('pages', array(
            'title'         => $title,
            'keywords'      => $keywords,
            'description'   => $description,
            'contents'      => $contents,
            'url_token'     => $url_token,
            'belong_domain' => $this->_domainId,
            'add_time'      => date('Y-m-d H:i:s'),
            'user_id'       => Application::user()->get_info('uid'),
        ));
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

    public function update_page($id, $title, $keywords, $description, $contents, $url_token)
    {
        $where = is_null($this->_domainId) ? '' : ' AND belong_domain = ' . $this->_domainId;

        return $this->update('pages', array(
            'title'         => $title,
            'keywords'      => $keywords,
            'description'   => $description,
            'contents'      => $contents,
            'url_token'     => $url_token,
            'modify_time'   => date('Y-m-d H:i:s'),
            'user_id'       => Application::user()->get_info('uid'),
        ), 'id = ' . intval($id) . $where);
    }

    public function fetch_page_list($page, $limit = 10)
    {
        $where = is_null($this->_domainId) ? null : 'belong_domain = ' . $this->_domainId;
        return $this->fetch_page('pages', $where, 'id DESC', $page, $limit);
    }

    public function update_page_enabled($page_id, $enabled)
    {
        $where = is_null($this->_domainId) ? '' : ' AND belong_domain = ' . $this->_domainId;

        return $this->update('pages', array(
            'enabled' => intval($enabled)
        ), 'id = ' . intval($page_id) . $where);
    }
}
