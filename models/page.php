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
	 * 根据token获取页面嘻嘻
	 * @param string $token
	 * @return multitype:
	 */
	public function getPageByToken($token)
	{
		return $this->fetch_row('pages', "url_token = '" . $this->quote($token) . "'");
	}

	/**
	 * 根据页面id获取页面信息
	 * @param int $id
	 * @return array
	 */
	public function getPageById($id)
	{
		return $this->fetch_row('pages', 'id = ' . intval($id));
	}

	public function add_page($title, $keywords, $description, $contents, $url_token)
	{
		return $this->insert('pages', array(
			'title' => $title,
			'keywords' => $keywords,
			'description' => $description,
			'contents' => $contents,
			'url_token' => $url_token
		));
	}

	public function remove_page($id)
	{
		return $this->delete('pages', 'id = ' . intval($id));
	}

	public function update_page($id, $title, $keywords, $description, $contents, $url_token)
	{
		return $this->update('pages', array(
			'title' => $title,
			'keywords' => $keywords,
			'description' => $description,
			'contents' => $contents,
			'url_token' => $url_token
		), 'id = ' . intval($id));
	}

	public function fetch_page_list($page, $limit = 10)
	{
		return $this->fetch_page('pages', null, 'id DESC', $page, $limit);
	}

	public function update_page_enabled($page_id, $enabled)
	{
		return $this->update('pages', array(
			'enabled' => intval($enabled)
		), 'id = ' . intval($page_id));
	}
}
