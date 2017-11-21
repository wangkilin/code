<?php
class menuModel extends Model
{
	public function add_nav_menu($title, $description, $type = 'custom', $type_id = 0, $link = null)
	{
		Application::cache()->cleanGroup('nav_menu');

		return $this->insert('nav_menu', array(
			'title' => $title,
			'description' => $description,
			'type' => $type,
			'type_id' => $type_id,
			'link' => $link,
			'icon' => '',
			'sort' => 99,
		));
	}
	/**
	 * 添加技能导航菜单
	 * @param unknown $title
	 * @param unknown $description
	 * @param string $type
	 * @param number $type_id
	 * @param string $link
	 * @return number
	 */
	public function add_tag_nav_menu($title, $description, $type = 'custom', $type_id = 0, $link = null)
	{
		Application::cache()->cleanGroup('tag_nav_menu');

		return $this->insert('tag_nav_menu', array(
			'title' => $title,
			'description' => $description,
			'type' => $type,
			'type_id' => $type_id,
			'link' => $link,
			'icon' => '',
			'sort' => 99,
		));
	}

	public function process_child_menu_links($data, $app)
	{
		if (!$data)
		{
			return false;
		}

		switch ($app)
		{
			case 'explore':
				$url_prefix = '';

				$url_mobile_prefix = 'm/explore/';
			break;

			case 'article':
				$url_prefix = 'article/';

				$url_mobile_prefix = 'm/article/';
			break;
		}

		foreach ($data AS $key => $val)
		{
			if (!$val['url_token'])
			{
				$val['url_token'] = $val['id'];
			}

			if (defined('IN_MOBILE'))
			{
				$data[$key]['link'] = $url_mobile_prefix . 'category-' . $val['id'];
			}
			else
			{
				$data[$key]['link'] = $url_prefix . 'category-' . $val['url_token'];
			}

			$data[$key]['child'] = $this->process_child_menu_links($this->model('system')->fetch_category($val['type'], $val['id']), $app);
		}

		return $data;
	}

	public function getTagMenuList ($app=null)
	{
		$menuList = array();
		// 获取全部菜单数据
		if (! $menuData = Application::cache()->get('tagMenuList')) {
			$sql = 'SELECT m.*, c.url_token, s.url_token AS tag_url_token '
				 . ' FROM ' . $this->get_table('tag_nav_menu') . ' AS m '
			     . ' LEFT JOIN ' . $this->get_table('tag_category') . ' AS c '
			     . '    ON m.type_id = c.id AND m.type = "category" '
			     . ' LEFT JOIN ' . $this->get_table('tag') .  ' AS s '
			     . '    ON m.type_id = s.id AND m.type = "tag" '
			     . ' ORDER BY m.`parent_id` ASC, `sort` ASC';
			$menuData = $this->query_all($sql);
			Application::cache()->set('tagMenuList', $menuData, get_setting('cache_level_low'), 'nav_menu');
		}
		$url_prefix = $app . '/';
		$url_mobile_prefix = 'm/' . $app . '/';
		is_array($menuData) OR $menuData = array();

		foreach ($menuData as $key => $val) {
			switch ($val['type']) {
				case 'category':
				case 'tag':
					$token = $val['type']=='category' ? $val['url_token'] : $val['tag_url_token'];
					if (defined('IN_MOBILE')) {
						$val['link'] = $url_mobile_prefix . $val['type'] . '-' . $val['type_id'];
					} else {
						$val['link'] = $url_prefix . $val['type'] . '-' . ($token ? $token :$val['type_id']);

					}
					break;
			}
			$val['child'] = array();
			if ($val['parent_id'] && isset($menuList[$val['parent_id']])) {
				$menuList[$val['parent_id']]['child'][$val['id']] = $val;
				continue;
			}
			$menuList[$val['id']] = $val;
		}

		return $menuList;
	}

	/**
	 * 获取菜单列表数据
	 * @param string $moduleName 组装菜单链接用到的频道名称，保证菜单只指向到频道内部
	 * @return array:
	 */
	public function getMenuListWithModuleInLink ($moduleName=null)
	{
		$menuList = array();
		// 获取全部菜单数据
		if (! $menuData = Application::cache()->get('menuList')) {
			$sql = 'SELECT m.*, c.url_token '
				 . ' FROM ' . $this->get_table('nav_menu') . ' AS m '
			     . ' LEFT JOIN ' . $this->get_table('category') . ' AS c '
			     . '    ON m.type_id = c.id AND m.type = "category" '
			     . ' LEFT JOIN ' . $this->get_table('topic') . ' AS t '
			     . '    ON m.type_id = t.topic_id AND m.type = "topic" '
			     . ' ORDER BY `sort` ASC';
			$menuData = $this->query_all($sql);
			Application::cache()->set('menuList', $menuData, get_setting('cache_level_low'), 'nav_menu');
		}

		$url_prefix = $moduleName . '/';
		$url_mobile_prefix = 'm/' . $moduleName . '/';
		is_array($menuData) OR $menuData = array();

		foreach ($menuData as $key => $val) {
			switch ($val['type']) {
				case 'topic': // 话题
				case 'category': // 分类
					if (defined('IN_MOBILE')) {
						$val['link'] = $url_mobile_prefix . $val['type'] . '-' . $val['type_id'];
					} else {
						$val['link'] = $url_prefix . $val['type'] . '-' . ($val['url_token'] ? $val['url_token'] :$val['type_id']);
					}
					break;

				default:
					break;
			}
			$val['child'] = array();
			if ($val['parent_id'] && isset($menuList[$val['parent_id']])) {
				$menuList[$val['parent_id']]['child'][$val['id']] = $val;
				continue;
			}
			$menuList[$val['id']] = $val;

		}

		return $menuList;

	}

	/**
	 * 获取导航菜单， 在导航菜单中加入模块名称
	 * @param string $moduleName
	 * @return Ambigous <string, unknown>
	 */
	public function getNavMenuWithModuleInLink($moduleName = null)
	{
		if (!$nav_menu_data = Application::cache()->get('nav_menu_list')) {
			$nav_menu_data = $this->fetch_all('nav_menu', null, 'sort ASC');

			Application::cache()->set('nav_menu_list', $nav_menu_data, get_setting('cache_level_low'), 'nav_menu');
		}

		if ($nav_menu_data) {
			$category_info = $this->model('system')->get_category_list('question');

			switch ($moduleName) {
				case 'explore':
				case 'index':
					$url_prefix = 'index/';

					$url_mobile_prefix = 'm/';

					break;

				case 'question':
					$url_prefix = 'question/';

					$url_mobile_prefix = 'm/';

					break;

				case 'article':
					$url_prefix = 'article/';

					$url_mobile_prefix = 'm/article/';

					break;

				case 'project':
					$url_prefix = 'project/';

					$url_mobile_prefix = 'project/';

					break;
			}

			foreach ($nav_menu_data as $key => $val)
			{
				switch ($val['type'])
				{
					case 'category':
						if (defined('IN_MOBILE')) {
							$nav_menu_data[$key]['link'] = $url_mobile_prefix . 'category-' . $category_info[$val['type_id']]['id'];
						} else {
							$nav_menu_data[$key]['link'] = $url_prefix . 'category-' . $category_info[$val['type_id']]['url_token'];

							$nav_menu_data[$key]['child'] = $this->process_child_menu_links($this->model('system')->fetch_category($category_info[$val['type_id']]['type'], $val['type_id']), $moduleName);
						}
					break;
				}

				$nav_menu_data['category_ids'][] = $val['type_id'];
			}

			if (defined('IN_MOBILE'))
			{
				$nav_menu_data['base']['link'] = $url_mobile_prefix;
			}
			else
			{
				$nav_menu_data['base']['link'] = $url_prefix;
			}
		}

		return $nav_menu_data;
	}

	public function update_nav_menu($nav_menu_id, $data)
	{
		Application::cache()->cleanGroup('nav_menu');

		return $this->update('nav_menu', $data, 'id = ' . intval($nav_menu_id));
	}

	/**
	 * 更新技能菜单
	 * @param unknown $nav_menu_id
	 * @param unknown $data
	 * @return number
	 */
	public function update_tag_nav_menu($nav_menu_id, $data)
	{
		Application::cache()->cleanGroup('tag_nav_menu');

		return $this->update('tag_nav_menu', $data, 'id = ' . intval($nav_menu_id));
	}

	/**
	 * 删除技能导航菜单项
	 * @param unknown $id
	 * @return number
	 */
	public function remove_tag_nav_menu($id)
	{
		Application::cache()->cleanGroup('tag_nav_menu');

		return $this->delete('tag_nav_menu', 'id = ' . intval($id));
	}

	public function remove_nav_menu($nav_menu_id)
	{
		Application::cache()->cleanGroup('nav_menu');

		return $this->delete('nav_menu', 'id = ' . intval($nav_menu_id));
	}
}