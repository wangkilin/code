<?php

class topicModel extends Model
{
	/**
	 * 子话题标识
	 */
	const IS_CHILD = 1;
	/**
	 * 根话题标识
	 */
	CONST IS_PARENT = 2;

	/**
	 * 根据条件获取标签列表
	 * @param unknown $conditions
	 * @param string $sort
	 * @param number $perPage
	 * @param number $pageNum
	 * @return unknown
	 */
	public function getTopicsByConditions ($conditions, $sort='id DESC', $perPage=10, $pageNum=0)
	{
		$where = array();

		if ($conditions['keyword']) {
			$where[] = "topic_title LIKE '" . $this->quote($conditions['keyword']) . "%'";
		}

		if ($conditions['discuss_count_min'] OR $conditions['discuss_count_min'] == '0') {
			$where[] = 'discuss_count >= ' . intval($conditions['discuss_count_min']);
		}

		if ($conditions['discuss_count_max'] OR $conditions['discuss_count_max'] == '0') {
			$where[] = 'discuss_count <= ' . intval($conditions['discuss_count_max']);
		}

		if (base64_decode($conditions['start_date'])) {
			$where[] = 'add_time >= ' . strtotime(base64_decode($conditions['start_date']));
		}

		if (base64_decode($conditions['end_date'])) {
			$where[] = 'add_time <= ' . strtotime('+1 day', strtotime(base64_decode($conditions['end_date'])));
		}

		if (! $sort) {
			$sort = 'topic_id DESC';
		}

		$tagList      = $this->getTopicList(implode(' AND ', $where), $sort, $perPage, $pageNum);

		return $tagList;
	}
	/**
	 * 更新标签
	 * @param int $id 标签id
	 * @param array $data 标签数据列表
	 * @return number
	 */
	public function updateTopic($id, $data)
	{
		$result = false;
		if (!$topic_info = $this->getTopicById($id)) {
			return $result;
		}
		if ($set = $this->processTopicData($data)) {
			if (isset($set['url_token']) && $set['url_token']==$id) {
				$set['url_token'] = '';
			}
			$result = $this->update('topic', $set, 'topic_id = ' . intval($id));

			if (! empty($set['parent_ids'])) {
				$this->setTopicRelations($id, unserialize($set['parent_ids']));
			}

			$uid = Application::user()->get_info('uid');
			if ($uid) {
				// 记录日志
				if ($data['topic_title'] AND $data['topic_title'] != $topic_info['topic_title']) {
					ACTION_LOG::save_action($uid, $id, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::MOD_TOPIC, $data['topic_title'], $topic_info['topic_title']);
				}

				if ($data['topic_description'] AND $data['topic_description'] != $topic_info['topic_description']) {
					ACTION_LOG::save_action($uid, $id, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::MOD_TOPIC_DESCRI, $data['topic_description'], $topic_info['topic_description']);
				}

				if ($data['topic_pic'] AND $data['topic_pic'] != $topic_info['topic_pic']){
					ACTION_LOG::save_action($uid, $id, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::MOD_TOPIC_PIC, $data['topic_pic'], $topic_info['topic_pic']);
				}
			}
		}

		return $result;
	}

	/**
	 * 处理标签数据， 供更新和添加使用
	 * @param array $data
	 * @return multitype:string NULL
	 */
	protected function processTopicData ($data)
	{
		$set = array();
		if (isset($data['title'])) {
			$set['topic_title'] = htmlspecialchars(str_replace(array('-', '/'), '_', $data['title']));
		}
		if (isset($data['pic'])) {
			$set['topic_pic'] = htmlspecialchars($data['pic']);
		}
		if (isset($data['lock'])) {
			$set['topic_lock'] = intval($data['lock']);
		}
		if (isset($data['description'])) {
			$set['topic_description'] = htmlspecialchars($data['description']);
		}
		if (isset($data['is_parent'])) {
			$set['is_parent'] = intval($data['is_parent']);
		}
		if (! empty($set['is_parent'])) {
			$data['parent_ids'] = array();
		}
		if (isset($data['parent_ids']) && is_array($data['parent_ids'])) {
			array_walk_recursive($data['parent_ids'], 'intval');
			$set['parent_ids'] = serialize($data['parent_ids']);
		}
		if (isset($data['parent_id'])) {
			$set['parent_id'] = intval($data['parent_id']);
		}
		if (isset($data['url_token'])) {
			$set['url_token'] = str_replace(array('-', '/'), '_', $data['url_token']);
		}

		return $set;
	}
	/**
	 *  设置话题之间的绑定关系
	 * @param int $tagId
	 * @param array $categoryIds
	 * @return tagModel
	 */
	public function setTopicRelations ($topicId, $parentIds)
	{
		$oldParentIds = array();
		$relations = $this->getTopicRelationsById($topicId, self::IS_CHILD);
		foreach ($relations as $_relation) {
			$parentId = $oldParentIds[] = $_relation['topic_id'];
			if (! in_array($parentId, $parentIds)) {
				$this->removeTopicFromTopic($topicId, $parentId);
			}
		}

		foreach ($parentIds as $parentId) {
			if (! in_array($parentId, $oldParentIds)) {
			    $this->addTopicIntoTopic($topicId, $parentId);
			}
		}

		return $this;
	}
	/**
	 * 通过标签id和标签级别获取关联的标签
	 * @param int $tagId 话题id
	 * @param string $isChildOrParent self::IS_CHILD/self::IS_PARENT 话题id是跟话题， 还是子话题
	 * @return array
	 */
	public function getTopicRelationsById ($tagId=null, $isChildOrParent)
	{
		$col = $isChildOrParent==self::IS_CHILD ? 'child_id' : 'topic_id';
		if (is_array($tagId) && count($$tagId)) {
			array_walk_recursive($tagId, 'intval_string');
			$where = $col . ' IN (' . join(',', $tagId);
		} else if (is_numeric($tagId)) {
			$where = $col . ' = ' . intval($tagId);
		} else {
			$where = null;
		}
		$list = $this->fetch_all('tag_tag_relation', $where);
		$relations = array();
		foreach ($list as $_relation) {
			isset($relations[$_relation[$col]]) OR $relations[$_relation[$col]] = array();
			$relations[$_relation[$col]][] = $_relation;
		}

		return $relations;
	}

	/**
	 * 获取两个标签间的关系
	 * @param unknown $tagId
	 * @param unknown $parentId
	 * @return multitype:
	 */
	public function hasRelation ($tagId, $parentId)
	{
		return $this->fetch_row('tag_tag_relation', 'topic_id='.intval($parentId).' AND child_id='.intval($tagId));
	}

	/**
	 * 将标签加入到标签中
	 * @param int $tagId
	 * @param int $parentId
	 * @return number
	 */
	public function addTopicIntoTopic ($tagId, $parentId)
	{
		$id = 0;
		if ($this->getTopicById($tagId) && $this->getTopicById($parentId)) {
			$data = array(
					'topic_id'      => $parentId,
					'child_id'      => $tagId
			);
			$id = $this->insert('tag_tag_relation', $data);
		}

		return $id;
	}

	/**
	 * 将标签从父级标签中移除
	 * @param int $tagId
	 * @param int $parentId
	 * @return Ambigous <boolean, number>
	 */
	public function removeTopicFromTopic ($tagId, $parentId)
	{
		$result = false;
		if ($this->hasRelation($tagId, $parentId)) {
			$result = $this->delete('tag_tag_relation', 'topic_id='.intval($parentId).' AND child_id=' .intval($tagId));
        }

		return $result;
	}

	/**
	 * 添加新标签
	 * @param unknown $data
	 * @return number
	 */
	public function addTopic ($data)
	{
		$id = 0;
		if ($set = $this->processTopicData($data)) {
			$set['add_time'] = time();
			$id = $this->insert('topic', $set);
			if ($id && ! empty($set['parent_ids'])) {
				$this->setTopicRelations($id, unserialize($set['parent_ids']));
			}
		}

		return $id;
	}
	/**
	 * 根据指定条件获取话题列表
	 * @param string $where
	 * @param string $order
	 * @param number $limit
	 * @param string $page
	 * @return Ambigous <multitype:, string>
	 */
	public function getTopicList($where = null, $order = 'topic_id DESC', $limit = 10, $page = null)
	{
		$list = array();
		if ($topic_list = $this->fetch_page('topic', $where, $order, $page, $limit)) {
			foreach ($topic_list AS $key => $val) {
				if (!$val['url_token']) {
					$topic_list[$key]['url_token'] = rawurlencode($val['topic_title']);
				}
				if ($val['parent_ids']) {
					$topic_list[$key]['parent_ids'] = unserialize($topic_list[$key]['parent_ids']);
				} else {
					$topic_list[$key]['parent_ids'] = array();
				}
				$list[$topic_list[$key]['topic_id']] = & $topic_list[$key];
			}
		}

		return $list;
	}

	public function get_focus_topic_list($uid, $limit = 20)
	{
		if (!$uid)
		{
			return false;
		}

		if (!$focus_topics = $this->fetch_all('topic_focus', 'uid = ' . intval($uid)))
		{
			return false;
		}

		foreach ($focus_topics AS $key => $val)
		{
			$topic_ids[] = $val['topic_id'];
		}

		if ($topic_list = $this->fetch_all('topic', 'topic_id IN(' . implode(',', $topic_ids) . ')', 'discuss_count DESC', $limit))
		{
			foreach ($topic_list AS $key => $val)
			{
				if (!$val['url_token'])
				{
					$topic_list[$key]['url_token'] = urlencode($val['topic_title']);
				}
			}
		}

		return $topic_list;
	}

	public function get_focus_topic_ids_by_uid($uid)
	{
		if (!$uid)
		{
			return false;
		}

		if (!$topic_focus = $this->fetch_all('topic_focus', "uid = " . intval($uid)))
		{
			return false;
		}

		foreach ($topic_focus as $key => $val)
		{
			$topic_ids[$val['topic_id']] = $val['topic_id'];
		}

		return $topic_ids;
	}

	public function get_sized_file($size = null, $pic_file = null)
	{
		if (! $pic_file) {
			return false;
		}

		$original_file = str_replace('_' . Application::config()->get('image')->topic_thumbnail['min']['w'] . 'x' . Application::config()->get('image')->topic_thumbnail['min']['h'] . '.', '.', $pic_file);

		if (! $size)
		{
			return $original_file;
		}

		// Fix date() bug
		if (!file_exists(get_setting('upload_dir') . '/topic/' . $original_file))
		{
			$dir_info = explode('/', $original_file);

			$dir_date = intval($dir_info[0]);

			$original_file = ($dir_date + 1) . '/' . basename($original_file);
		}

		return str_replace('.', '_' . Application::config()->get('image')->topic_thumbnail[$size]['w'] . 'x' . Application::config()->get('image')->topic_thumbnail[$size]['h'] . '.', $original_file);
	}

	/**
	 *
	 * 获取单条话题内容
	 * @param int $topic_id 话题ID
	 *
	 * @return array
	 */
	public function getById ($topicId)
	{
		return $this->getTopicById($topicId);
	}
	public function getTopicById($topic_id)
	{
		static $topics;

		if (! $topic_id) {
			return false;
		}

		if (! $topics[$topic_id]) {
			$topics[$topic_id] = $this->fetch_row('topic', 'topic_id = ' . intval($topic_id));

			if ($topics[$topic_id]) {
				if (!$topics[$topic_id]['url_token']) {
				    $topics[$topic_id]['url_token'] = urlencode($topics[$topic_id]['topic_title']);
				}
				if ($topics[$topic_id]['parent_ids']) {
				    $topics[$topic_id]['parent_ids'] = unserialize($topics[$topic_id]['parent_ids']);
				} else {
					$topics[$topic_id]['parent_ids'] = array();
				}
			}
		}

		return $topics[$topic_id];
	}

	/**
	 * 通过url token获取话题
	 * @param unknown $url_token
	 * @return multitype:
	 */
	public function getTopicByUrlToken($url_token)
	{
		$topicInfo = $this->fetch_row('topic', "url_token = '" . $this->quote($url_token) . "'");

		return $topicInfo;
	}

	/**
	 * 通过话题id获取合并过的话题列表
	 * @param int $topic_id 话题id
	 * @return array
	 */
	public function getMergedTopicById($topic_id)
	{
		return $this->fetch_all('topic_merge', 'target_id = ' . intval($topic_id));
	}
	/**
	 * 将未合并过的话题合并到另外一个话题
	 * @param int $source_id 待合并的话题id
	 * @param int $target_id 合并到目标话题id
	 * @param int $uid 合并话题的用户id
	 * @return number|boolean
	 */
	public function mergeTwoTopicByIds($source_id, $target_id, $uid)
	{
		if ($this->count('topic', 'topic_id = ' . intval($source_id) . ' AND merged_id = 0')) {
			$this->update('topic', array(
				'merged_id' => intval($target_id)
			), 'topic_id = ' . intval($source_id));

			return $this->insert('topic_merge', array(
				'source_id' => intval($source_id),
				'target_id' => intval($target_id),
				'uid'       => intval($uid),
				'time'      => time()
			));
		}

		return false;
	}

	public function remove_merge_topic($source_id, $target_id)
	{
		$this->update('topic', array(
			'merged_id' => 0
		), 'topic_id = ' . intval($source_id));

		return $this->delete('topic_merge', 'source_id = ' . intval($source_id) . ' AND target_id = ' . intval($target_id));
	}

	/**
	 * 通过ids获取话题列表
	 * @param array $topic_ids
	 * @return boolean|string
	 */
	public function getTopicsByIds($topic_ids)
	{
		$list = array();
		if (!is_array($topic_ids) || count($topic_ids)==0) {
			return false;
		}

		array_walk_recursive($topic_ids, 'intval_string');

		$topics = $this->fetch_all('topic', 'topic_id IN(' . join(',', $topic_ids) . ')');

		foreach ($topics AS $val) {
			if (!$val['url_token']) {
				$val['url_token'] = urlencode($val['topic_title']);
			}

			$list[$val['topic_id']] = $val;
		}

		return $list;
	}

	/**
	 * 通过标题获取话题
	 * @param string $topic_title 话题标题
	 * @return Ambigous <multitype:, boolean>
	 */
	public function getTopicByTitle($topic_title)
	{
		$topic = null;
		if ($topic_id = $this->getTopicIdByTitle($topic_title)) {
			$topic = $this->getTopicById($topic_id);
		}

		return $topic;
	}
	/**
	 * 通过标题获取话题id
	 * @param string $topic_title 话题标题
	 * @return mixed
	 */
	public function getTopicIdByTitle($topic_title)
	{
		return $this->fetch_one('topic', 'topic_id', "topic_title = '" . $this->quote(htmlspecialchars($topic_title)) . "'");
	}

	/**
	 * 根据文章id获取话题列表
	 * @param int $articleId 文章id
	 * @param string $articleType 文章类型
	 * @return array
	 */
	public function getTopicsByArticleId($articleId, $articleType)
	{
		if (! is_numeric($articleId) || ! $articleId) {
			return array();
		}
		$result = $this->getTopicsByArticleIds(array($articleId), $articleType);

		return $result[$articleId];
	}

	/**
	 * 根据文章ids获取话题列表
	 * @param array $articleIds 文章id列表
	 * @param string $articleType 文章类型
	 * @return array
	 */
	public function getTopicsByArticleIds($articleIds, $articleType)
	{
		$result = array();

		if (! is_array($articleIds) || count($articleIds) == 0) {
			return $result;
		}

		array_walk_recursive($articleIds, 'intval_string');
		// 初始化，将每个文章对应的话题设置为空数组
		$result = array_fill_keys($articleIds, array());
		// 获取文章和话题关系列表
		$where = count($articleIds) == 1 ? 'item_id = ' . join('', $articleIds) : 'item_id IN(' . implode(',', $articleIds) . ')';
		if (! $relations = $this->fetch_all('topic_relation', $where . " AND `type` = '" . $this->quote($articleType) . "'")) {

			return $result;
		}
		// 暂存话题id
		foreach ($relations AS $val) {
			$topicIds[] = $val['topic_id'];
		}
		// 通过话题id列表， 获取话题信息
		$topicsList = $this->getTopicsByIds($topicIds);
		// 将话题绑定到文章id
		foreach ($relations AS $val) {
			$result[$val['item_id']][] = $topicsList[$val['topic_id']];
		}

		return $result;
	}

	/**
	 * 保存话题， 如话题已存在，则更新话题讨论的统计数据
	 * @param unknown $title
	 * @param string $uid
	 * @param string $autoCreate
	 * @param string $description
	 * @return Ambigous <mixed, number>
	 */
	public function saveTopic($title, $uid = null, $autoCreate = true, $description = '')
	{
		$title = str_replace(array('-', '/'), '_', $title);

		if (! $topicId = $this->getTopicIdByTitle($title) AND $autoCreate) {
			$topicId = $this->insert('topic', array(
				'topic_title' => htmlspecialchars($title),
				'add_time'    => time(),
				'topic_lock'  => 0,
				'topic_description' => htmlspecialchars($description),
			));

			if ($uid) {
				ACTION_LOG::save_action($uid, $topicId, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::ADD_TOPIC, $title);

				$this->switchTopicFollowed($uid, $topicId);
			}

		} else {
			$this->updateDiscussCountStatById($topicId);
		}

		return $topicId;
	}
	/**
	 * 移除条目和话题之间的关系
	 * @param int $uid 操作用户id
	 * @param int $topic_id 话题id
	 * @param int $item_id 文章条目id
	 * @param string $type 文章类型
	 * @return boolean|number
	 */
	public function removeTopicItemRelation($uid, $topic_id, $item_id, $type)
	{
		if (!$topic_info = $this->getTopicById($topic_id)) {
			return false;
		}

		switch ($type) {
			case 'question':
				ACTION_LOG::save_action($uid, $item_id, ACTION_LOG::CATEGORY_QUESTION, ACTION_LOG::DELETE_TOPIC, $topic_info['topic_title'], $topic_id);
			    break;
		}

		$where = 'topic_id = ' . intval($topic_id) . '
			  AND item_id = ' . intval($item_id) . "
			  AND `type` = '" . $this->quote($type) . "'";

		return $this->delete('topic_relation', $where);
	}

	public function update_topic($uid, $topic_id, $topic_title = null, $topic_description = null, $topic_pic = null)
	{
		if (!$topic_info = $this->getTopicById($topic_id))
		{
			return false;
		}

		if ($topic_title)
		{
			$data['topic_title'] = htmlspecialchars(trim($topic_title));
		}

		if ($topic_description)
		{
			$data['topic_description'] = htmlspecialchars($topic_description);
		}

		if ($topic_pic)
		{
			$data['topic_pic'] = htmlspecialchars($topic_pic);
		}

		if ($data)
		{
			$this->update('topic', $data, 'topic_id = ' . intval($topic_id));

			// 记录日志
			if ($topic_title AND $topic_title != $topic_info['topic_title'])
			{
				ACTION_LOG::save_action($uid, $topic_id, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::MOD_TOPIC, $topic_title, $topic_info['topic_title']);
			}

			if ($topic_description AND $topic_description != $topic_info['topic_description'])
			{
				ACTION_LOG::save_action($uid, $topic_id, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::MOD_TOPIC_DESCRI, $topic_description, $topic_info['topic_description']);
			}

			if ($topic_pic AND $topic_pic != $topic_info['topic_pic'])
			{
				ACTION_LOG::save_action($uid, $topic_id, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::MOD_TOPIC_PIC, $topic_pic, $topic_info['topic_pic']);
			}
		}

		return TRUE;
	}

	/**
	 *
	 * 锁定/解锁话题
	 * @param int $topic_id
	 * @param int $topic_lock
	 *
	 * @return boolean true|false
	 */
	public function lock_topic_by_ids($topic_ids, $topic_lock = 0)
	{
		if (!$topic_ids)
		{
			return false;
		}

		if (!is_array($topic_ids))
		{
			$topic_ids = array(
				$topic_ids,
			);
		}

		array_walk_recursive($topic_ids, 'intval_string');

		return $this->update('topic', array(
			'topic_lock' => $topic_lock
		), 'topic_id IN (' . implode(',', $topic_ids) . ')');

	}

	/**
	 * 获取话题的锁定状态
	 * @param int $topic_id 话题id
	 * @return Ambigous <>
	 */
	public function isTopicLocked($topic_id)
	{
		$topic_info = $this->getTopicById($topic_id);

		return $topic_info['topic_lock'];
	}

	/**
	 * 构建标签下拉列表HTML数据
	 * @param int|array $selectedId 默认选定
	 * @param string $subPrefix 二级选项 前缀
	 * @return string
	 */
	public function buildTopicDropdownHtml($selectedId = null, $subPrefix = '--')
	{
		// 获取根话题和子话题， 以及相关关系
		$rootTopics = $this->getRootTopics();
		$relations  = $this->getTopicRelationsById(null, self::IS_PARENT);
		$childTopics= $this->getTopicList('is_parent=0', 'topic_id DESC', PHP_INT_MAX);

		if (! is_array($selectedId)) {
			$selectedId = array($selectedId);
		}

		$html = '';
		foreach ($rootTopics AS $_rootTopic) {
			$selected = '';
			if (in_array($_rootTopic['topic_id'], $selectedId)) {
				$selected =  'selected="selected"';
			}

			$html .= '<option class="root-level" value="' . $_rootTopic['topic_id'] . '"' . $selected . '>' . $_rootTopic['topic_title'] . '</option>';
			if (! isset($relations[$_rootTopic['topic_id']])) {
				continue;
			}
			// 查看当前根话题下是否有子话题， 如果有子话题， 列出子话题
			foreach ($relations[$_rootTopic['topic_id']] as $_relation) {
				$selected = '';
				if (! isset($childTopics[$_relation['child_id']])) {
					continue;
				}
				$childTopics[$_relation['child_id']]['has_parent'] = true;
				if (in_array($_relation['child_id'], $selectedId)) {
					$selected =  'selected="selected"';
				}
				$html .= '<option class="sub-level" value="' . $_relation['child_id'] . '"' . $selected . '>' . $subPrefix . ' ' .$childTopics[$_relation['child_id']]['topic_title'] . '</option>';
			}
		}
		// 将没有归类到跟话题的子话题输出
		foreach ($childTopics as $_topic) {
			if (isset($_topic['has_parent'])) {
				continue;
			}
			$selected = '';
			if (in_array($_topic['topic_id'], $selectedId)) {
				$selected =  'selected="selected"';
			}
			$html .= '<option value="' . $_topic['topic_id'] . '"' . $selected . '>/-' . $_topic['topic_title'] . '</option>';

		}

		return $html;
	}

	/**
	 * 切换话题关注状态： 添加/取消关注
	 * @param unknown $uid
	 * @param unknown $topic_id
	 * @return string
	 */
	public function switchTopicFollowed($uid, $topic_id)
	{
		settype($uid, 'int');
		settype($topic_id, 'int');
		if (! $this->isTopicFollowed($uid, $topic_id)) {
			if ($this->insert('topic_focus', array(
				"topic_id" => $topic_id,
				"add_time" => time(),
				"uid"      => $uid,
			))) {
				$this->query('UPDATE ' . $this->get_table('topic') . " SET focus_count = focus_count + 1 WHERE topic_id = " . $topic_id);
			}

			$result = 'add';

			// 记录日志
			ACTION_LOG::save_action($uid, $topic_id, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::ADD_TOPIC_FOCUS);

		} else {
			$deleteResult = $this->delete('topic_focus', 'uid = ' . $uid . ' AND topic_id = ' . $topic_id);
			if ($deleteResult) {
				$this->query('UPDATE ' . $this->get_table('topic') . " SET focus_count = focus_count - 1 WHERE focus_count > 0 AND topic_id = " . $topic_id);
			}

			$result = 'remove';

			ACTION_LOG::delete_action_history('associate_type = ' . ACTION_LOG::CATEGORY_QUESTION . ' AND associate_action = ' . ACTION_LOG::ADD_TOPIC_FOCUS . ' AND uid = ' . $uid . ' AND associate_id = ' . $topic_id);
		}

		// 获取用户关注的话题
		$focus_count = $this->count('topic_focus', 'uid = ' . $uid);
        // 更新用户关注的计数
		$this->model('account')->setAccountInfos(array(
			'topic_focus_count' => $focus_count
		), $uid);

		return $result;
	}

	/**
	 * 判断话题是否已被关注
	 * @param int $uid
	 * @param int $topic_id
	 * @return mixed
	 */
	public function isTopicFollowed($uid, $topic_id)
	{
		return $this->fetch_one('topic_focus', 'focus_id', "uid = " . intval($uid) . " AND topic_id = " . intval($topic_id));
	}

	/**
	 * 查找指定话题中用户关注的话题
	 * @param int $uid
	 * @param array $topic_ids
	 * @return boolean|unknown
	 */
	public function getFollowedFromTopicIds($uid, $topic_ids)
	{
		if (!is_array($topic_ids)) {
			return false;
		}

		$list = array();
		array_walk_recursive($topic_ids, 'intval_string');

		if ($focus = $this->query_all('SELECT focus_id, topic_id
				FROM ' . $this->get_table('topic_focus') . "
				WHERE uid = " . intval($uid) . "
				  AND topic_id IN(" . implode(',', $topic_ids) . ")")) {
			foreach ($focus as $val) {
				$list[$val['topic_id']] = $val['focus_id'];
			}
		}

		return $list;
	}

	/**
	 * 更新话题讨论的统计信息.
	 * @param int $topic_id 话题id
	 */
	public function updateDiscussCountStatById($topic_id)
	{
		if (! $topic_id) {
			return false;
		}
		$where = 'topic_id = ' . intval($topic_id);
		$this->update('topic', array(
			'discuss_count'            => $this->count('topic_relation', $where),
			'discuss_count_last_week'  => $this->count('topic_relation', 'add_time > ' . (time() - 604800) . ' AND ' . $where),
			'discuss_count_last_month' => $this->count('topic_relation', 'add_time > ' . (time() - 2592000) . ' AND ' . $where),
			'discuss_count_update'     => intval($this->fetch_one('topic_relation', 'add_time', $where, 'add_time DESC'))
		), $where);
	}

	/**
	 * 物理删除话题及其关联的图片等
	 *
	 * @param  $topic_id
	 */
	public function remove_topic_by_ids($topic_id)
	{
		if (!$topic_id)
		{
			return false;
		}

		if (is_array($topic_id))
		{
			$topic_ids = $topic_id;
		}
		else
		{
			$topic_ids[] = $topic_id;
		}

		array_walk_recursive($topic_ids, 'intval_string');

		foreach($topic_ids as $topic_id)
		{
			if (!$topic_info = $this->getTopicById($topic_id))
			{
				continue;
			}

			if ($topic_info['topic_pic'])
			{
				foreach (Application::config()->get('image')->topic_thumbnail as $size)
				{
					@unlink(get_setting('upload_dir') . '/topic/' . str_replace(Application::config()->get('image')->topic_thumbnail['min']['w'] . '_' . Application::config()->get('image')->topic_thumbnail['min']['h'], $size['w'] . '_' . $size['h'], $topic_info['topic_pic']));

				}

				@unlink(get_setting('upload_dir') . '/topic/' . str_replace('_' . Application::config()->get('image')->topic_thumbnail['min']['w'] . '_' . Application::config()->get('image')->topic_thumbnail['min']['h'], '', $topic_info['topic_pic']));
			}

			// 删除动作
			ACTION_LOG::delete_action_history('associate_type = ' . ACTION_LOG::CATEGORY_TOPIC . ' AND associate_id = ' . intval($topic_id));
			ACTION_LOG::delete_action_history('associate_type = ' . ACTION_LOG::CATEGORY_QUESTION . ' AND associate_action = ' . ACTION_LOG::ADD_TOPIC . ' AND associate_attached = ' . intval($topic_id));

			$this->delete('topic_focus', 'topic_id = ' . intval($topic_id));
			$this->delete('topic_relation', 'topic_id = ' . intval($topic_id));
			$this->delete('feature_topic', 'topic_id = ' . intval($topic_id));
			$this->delete('related_topic', 'topic_id = ' . intval($topic_id) . ' OR related_id = ' . intval($topic_id));
			$this->delete('reputation_topic', ' topic_id = ' . intval($topic_id));
			$this->delete('topic', 'topic_id = ' . intval($topic_id));

			$this->update('topic', array(
				'parent_id' => 0
			), 'parent_id = ' . intval($topic_id));
		}

		return true;
	}

	// 我关注的人关注的话题
	public function get_user_recommend_v2($uid, $limit = 10)
	{
		$topic_focus_ids = array(0);

		$follow_uids = array(0);

		if ($topic_focus = $this->query_all("SELECT topic_id FROM " . $this->get_table("topic_focus") . " WHERE uid = " . (int)$uid))
		{
			foreach ($topic_focus as $key => $val)
			{
				$topic_focus_ids[] = $val['topic_id'];
			}
		}

		if ($friends = $this->model('follow')->get_user_friends($uid, false))
		{
			foreach ($friends as $key => $val)
			{
				$follow_uids[] = $val['uid'];
				$follow_users_array[$val['uid']] = $val;
			}
		}

		if (! $follow_uids)
		{
			return $this->getTopicList("topic_id NOT IN(" . implode($topic_focus_ids, ',') . ")", 'topic_id DESC', $limit);
		}

		if ($topic_focus = $this->query_all("SELECT DISTINCT topic_id, uid FROM " . $this->get_table("topic_focus") . " WHERE uid IN(" . implode($follow_uids, ',') . ") AND topic_id NOT IN (" . implode($topic_focus_ids, ',') . ") ORDER BY focus_id DESC LIMIT " . $limit))
		{
			foreach ($topic_focus as $key => $val)
			{
				$topic_ids[] = $val['topic_id'];
				$topic_id_focus_uid[$val['topic_id']] = $val['uid'];
			}
		}
		if (! $topic_ids)
		{
			if ($topic_focus_ids)
			{
				return $this->getTopicList("topic_id NOT IN (" . implode($topic_focus_ids, ',') . ")", 'topic_id DESC', $limit);
			}
			else
			{
				return $this->getTopicList(null, 'topic_id DESC', $limit);
			}
		}

		if ($topic_focus_ids)
		{
			$topics = $this->fetch_all('topic', 'topic_id IN(' . implode($topic_ids, ',') . ') AND topic_id NOT IN(' . implode($topic_focus_ids, ',') . ')', 'topic_id DESC', $limit);
		}
		else
		{
			$topics = $this->fetch_all('topic', 'topic_id IN(' . implode($topic_ids, ',') . ')', 'topic_id DESC', $limit);
		}

		foreach ($topics as $key => $val)
		{
			$topics[$key]['focus_users'] = $follow_users_array[$topic_id_focus_uid[$val['topic_id']]];

			if (!$val['url_token'])
			{
				$topics[$key]['url_token'] = urlencode($val['topic_title']);
			}
		}

		return $topics;
	}

	public function get_focus_users_by_topic($topic_id, $limit = 10)
	{
		$user_list = array();

		$uids = $this->query_all("SELECT DISTINCT uid FROM " . $this->get_table('topic_focus') . " WHERE topic_id = " . intval($topic_id), $limit);

		if ($uids)
		{
			$user_list_query = $this->model('account')->getUsersByIds(fetch_array_value($uids, 'uid'));

			if ($user_list_query)
			{
				foreach ($user_list_query AS $user_info)
				{
					$user_list[$user_info['uid']]['uid'] = $user_info['uid'];

					$user_list[$user_info['uid']]['user_name'] = $user_info['user_name'];

					$user_list[$user_info['uid']]['avatar_file'] = get_avatar_url($user_info['uid'], 'mid');

					$user_list[$user_info['uid']]['url'] = get_js_url('/user/' . $user_info['url_token']);
				}
			}
		}

		return $user_list;
	}

	/**
	 * 根据话题id获取话题下的条目数据ids
	 * @param unknown $topic_id
	 * @param string $type question， article...
	 * @param int $limit
	 * @return Ambigous <boolean, unknown>
	 */
	public function getItemIdsByTopicId($topic_id, $type = null, $limit = null)
	{
		return $this->getItemIdsByTopicIds(array(
			$topic_id
		), $limit);
	}

	/**
	 * 获取话题ids下的条目ids
	 * @param unknown $topic_ids
	 * @param string $type
	 * @param string $limit
	 * @return boolean|unknown
	 */
	public function getItemIdsByTopicIds($topic_ids, $type = null, $limit = null)
	{
		if (!is_array($topic_ids)) {
			return false;
		}

		array_walk_recursive($topic_ids, 'intval_string');

		$where = array('topic_id IN(' . implode(',', $topic_ids) . ')');

		if ($type) {
			$where[] = "`type` = '" . $this->quote($type) . "'";
		}

		if ($result = $this->query_all("SELECT item_id FROM " . $this->get_table('topic_relation') . " WHERE " . implode(' AND ', $where), $limit)) {
			foreach ($result AS $key => $val) {
				$item_ids[] = $val['item_id'];
			}
		}

		return $item_ids;
	}

	/**
	 * 通过话题id来获取该话题中最佳回复的几个人信息
	 * @param int $topic_id 话题id
	 * @param int $limit    获取的人数
	 * @return array
	 */
	public function getBestReplyUsersByTopicId($topic_id, $limit)
	{
		// 尝试先从缓存中获取
		if ($helpful_users = Application::cache()->get('helpful_users_' . md5($topic_id . '_' . $limit))) {
			return $helpful_users;
		}
		// 获取最佳回复用户信息
		if ($reputation_list = $this->fetch_all('reputation_topic', 'reputation > 0 AND topic_id = ' . intval($topic_id), 'reputation DESC', $limit)) {
			foreach ($reputation_list AS $val) {
				$best_answer_uids[] = $val['uid'];

				$helpful_users[$val['uid']]['agree_count'] = $val['agree_count'];
				$helpful_users[$val['uid']]['thanks_count'] = $val['thanks_count'];
			}

			$users_info = $this->model('account')->getUsersByIds($best_answer_uids, true);
			foreach ($users_info as $val) {
				$helpful_users[$val['uid']]['user_info'] = $val;
			}
		}

		Application::cache()->set('helpful_users_' . md5($topic_id . '_' . $limit), $helpful_users, get_setting('cache_level_normal'));

		return $helpful_users;
	}

	public function get_helpful_users_by_topic_ids($topic_ids, $limit = 10, $experience_limit = 1)
	{
		if (!is_array($topic_ids))
		{
			return false;
		}

		array_walk_recursive($topic_ids, 'intval_string');

		if ($helpful_users = Application::cache()->get('helpful_users_' . md5(implode('_', $topic_ids) . '_' . $limit . '_' . $experience_limit)))
		{
			return $helpful_users;
		}

		foreach ($topic_ids AS $topic_id)
		{
			if ($reputation_list = $this->fetch_all('reputation_topic', 'reputation > 0 AND topic_id = ' . intval($topic_id), 'reputation DESC', $limit))
			{
				foreach ($reputation_list AS $key => $val)
				{
					$best_answer_uids[$val['uid']] = $val['uid'];
				}
			}
		}

		if (!$best_answer_uids)
		{
			return false;
		}

		foreach ($best_answer_uids AS $best_answer_uid)
		{
			$rank_rate = $this->sum('reputation_topic', 'reputation', 'reputation > 0 AND topic_id IN(' . implode(',', $topic_ids) . ') AND uid = ' . intval($best_answer_uid));

			$best_answer_user_ranks[] = array(
				'rate' => $rank_rate,
				'uid' => $best_answer_uid
			);
		}

		$best_answer_user_ranks = aasort($best_answer_user_ranks, 'rate', 'DESC');

		if (sizeof($best_answer_user_ranks) > $limit)
		{
			$best_answer_user_ranks = array_slice($best_answer_user_ranks, 0, $limit);
		}

		unset($best_answer_uids);

		foreach ($best_answer_user_ranks AS $user_rank)
		{
			$best_answer_uids[$user_rank['uid']] = $user_rank['uid'];
		}

		$users_info = $this->model('account')->getUsersByIds($best_answer_uids, true);

		foreach ($best_answer_user_ranks AS $user_rank)
		{
			$helpful_users[$user_rank['uid']]['user_info'] = $users_info[$user_rank['uid']];

			$experience = array();

			foreach ($topic_ids AS $topic_id)
			{
				$topic_agree_count = $this->model('reputation')->calculate_agree_count($user_rank['uid'], array($topic_id));

				$experience[] = array(
					'topic_id' => $topic_id,
					'agree_count' => $topic_agree_count
				);
			}

			$experience = aasort($experience, 'agree_count', 'DESC');

			if (sizeof($experience) > $experience_limit)
			{
				$experience = array_slice($experience, 0, $experience_limit);
			}

			foreach ($experience AS $key => $val)
			{
				$helpful_users[$user_rank['uid']]['experience'][] = array(
					'topic_id' => $val['topic_id'],
					'agree_count' => $val['agree_count']
				);

				$experience_topic_ids[$val['topic_id']] = $val['topic_id'];
			}
		}

		$experience_topics_info = $this->model('topic')->getTopicsByIds($experience_topic_ids);

		if ($helpful_users)
		{
			foreach ($helpful_users AS $key => $val)
			{
				if (is_array($helpful_users[$key]['experience']))
				{
					foreach ($helpful_users[$key]['experience'] AS $exp_key => $exp_val)
					{
						$helpful_users[$key]['experience'][$exp_key]['topic_info'] = $experience_topics_info[$exp_val['topic_id']];
					}
				}
			}
		}

		Application::cache()->set('helpful_users_' . md5(implode('_', $topic_ids) . '_' . $limit . '_' . $experience_limit), $helpful_users, get_setting('cache_level_low'));

		return $helpful_users;
	}

	/**
	 * 获取热门话题
	 * @param  $category
	 * @param  $limit
	 */
	public function get_hot_topics($category_id = 0, $limit = 5, $section = null)
	{
		$where = array();

		if ($category_id)
		{
			if ($questions = $this->query_all("SELECT question_id FROM " . get_table('question') . " WHERE category_id IN(" . implode(',', $this->model('system')->get_category_with_child_ids('question', $category_id)) . ') ORDER BY add_time DESC LIMIT 200'))
			{
				foreach ($questions AS $key => $val)
				{
					$question_ids[] = $val['question_id'];
				}
			}

			if (!$question_ids)
			{
				return false;
			}

			if (!$topic_relation = $this->fetch_all('topic_relation', 'item_id IN(' . implode(',', $question_ids) . ") AND `type` = 'question'"))
			{
				return false;
			}

			foreach ($topic_relation AS $key => $val)
			{
				$topic_ids[] = $val['topic_id'];
			}

			$where[] = 'topic_id IN(' . implode(',', $topic_ids) . ')';
		}

		switch ($section)
		{
			default:
				return $this->fetch_all('topic', implode(' AND ', $where), 'discuss_count DESC', $limit);
			break;

			case 'week':
				$where[] = 'discuss_count_update > ' . (time() - 604801);

				return $this->fetch_all('topic', implode(' AND ', $where), 'discuss_count_last_week DESC', $limit);
			break;

			case 'month':
				$where[] = 'discuss_count_update > ' . (time() - 2592001);

				return $this->fetch_all('topic', implode(' AND ', $where), 'discuss_count_last_month DESC', $limit);
			break;
		}
	}

	/**
	 * 处理话题日志
	 * @param array $log_list
	 *
	 * @return array
	 */
	public function analysis_log($log_list)
	{
		$uid_list = array();
		$topic_list = array();

		if (!$log_list)
		{
			return false;
		}

		foreach ($log_list as $key => $log)
		{
			if (! in_array($log['uid'], $uid_list))
			{
				$uid_list[] = $log['uid'];
			}

			if ($log['associate_attached'] AND is_digits($log['associate_attached']) AND !in_array($log['associate_attached'], $topic_list))
			{
				$topic_list[] = $log['associate_attached'];
			}

			if ($log['associate_content'] AND is_digits($log['associate_content']) AND !in_array($log['associate_content'], $topic_list))
			{
				$topic_list[] = $log['associate_content'];
			}
		}

		/**
		 * 格式话简单数据类型
		 */
		if ($topics_array = $this->getTopicsByIds($topic_list))
		{
			foreach ($topics_array as $key => $val)
			{
				$topic_title_list[$val['topic_id']] = $val['topic_title'];
			}
		}

		if ($user_name_array = $this->model('account')->getUsersByIds($uid_list))
		{
			foreach ($user_name_array as $user_info)
			{
				$user_info_list[$user_info['uid']] = $user_info;
			}
		}

		/**
		 * 格式话数组
		 */
		foreach ($log_list as $key => $log)
		{
			$user_name = $user_info_list[$log['uid']]['user_name'];

			$user_url = get_js_url('user/' . $user_info_list[$log['uid']]['url_token']);

			switch ($log['associate_action'])
			{
				case ACTION_LOG::ADD_TOPIC : //增加话题
					$title_list = '<a href="' . $user_url . '">' . $user_name . '</a> ' . Application::lang()->_t('创建了该话题'). '</p>';
					break;

				case ACTION_LOG::ADD_TOPIC_FOCUS : //关注话题


					break;

				case ACTION_LOG::DELETE_TOPIC : //删除话题


					break;

				case ACTION_LOG::MOD_TOPIC : //修改话题标题
					$Services_Diff = new Services_Diff($log['associate_attached'], $log['associate_content']);

					$title_list = '<a href="' . $user_url . '">' . $user_name . '</a> ' . Application::lang()->_t('修改了话题标题') . ' <p>' . $Services_Diff->get_Text_Diff_Renderer_inline() . "</p>";
					break;

				case ACTION_LOG::MOD_TOPIC_DESCRI : //修改话题描述
					$log['associate_attached'] = trim($log['associate_attached']);
					$log['associate_content'] = trim($log['associate_content']);

					$Services_Diff = new Services_Diff($log['associate_attached'], $log['associate_content']);
					$title_list = '<a href="' . $user_url . '">' . $user_name . '</a> ' . Application::lang()->_t('修改了话题描述') . ' <p>' . $Services_Diff->get_Text_Diff_Renderer_inline() . '</p>';

					break;

				case ACTION_LOG::MOD_TOPIC_PIC : //修改话题图片
					$title_list = '<a href="' . $user_url . '">' . $user_name . '</a> ' . Application::lang()->_t('修改了话题图片');
					break;

				case ACTION_LOG::ADD_RELATED_TOPIC : //添加相关话题
					$topic_info = $this->getTopicById($log['associate_attached']);

					$title_list = '<a href="' . $user_url . '">' . $user_name . '</a> ' . Application::lang()->_t('添加了相关话题') . '<p><a href="topic/' . rawurlencode($topic_info['topic_title']) . '">' . $topic_info['topic_title'] . '</a></p>';
					break;

				case ACTION_LOG::DELETE_RELATED_TOPIC : //删除相关话题
					$topic_info = $this->getTopicById($log['associate_attached']);

					$title_list = '<a href="' . $user_url . '">' . $user_name . '</a> ' . Application::lang()->_t('删除了相关话题') . '<p><a href="topic/' . rawurlencode($topic_info['topic_title']) . '">' . $topic_info['topic_title'] . '</a></p>';
					break;
			}

			$data_list[] = ($title_list) ? array(
				'user_name' => $user_name,
				'title' => $title_list,
				'add_time' => date('Y-m-d', $log['add_time']),
				'log_id' => sprintf('%06s', $log['history_id']),
				'user_url' => $user_url
			) : '';
		}

		return $data_list;
	}

	public function save_related_topic($topic_id, $related_id)
	{
		$this->pre_save_auto_related_topics($topic_id);

		if (! $related_topic = $this->fetch_row('related_topic', 'topic_id = ' . intval($topic_id) . ' AND related_id = ' . intval($related_id)))
		{
			return $this->insert('related_topic', array(
				'topic_id' => intval($topic_id),
				'related_id' => intval($related_id)
			));
		}

		return false;
	}

	public function remove_related_topic($topic_id, $related_id)
	{
		$this->pre_save_auto_related_topics($topic_id);

		return $this->delete('related_topic', 'topic_id = ' . intval($topic_id) . ' AND related_id = ' . intval($related_id));
	}

	public function pre_save_auto_related_topics($topic_id)
	{
		if (! $this->isUserSetRelated($topic_id))
		{
			if ($auto_related_topics = $this->getAutoRelatedTopics($topic_id))
			{
				foreach ($auto_related_topics as $key => $val)
				{
					$this->insert('related_topic', array(
						'topic_id' => intval($topic_id),
						'related_id' => $val['topic_id']
					));
				}
			}

			$this->shutdown_update('topic', array(
				'user_related' => 1
			), 'topic_id = ' . intval($topic_id));
		}
	}

	/**
	 * 获取用户为话题设置的相关话题
	 * @param unknown $topic_id
	 * @return Ambigous <boolean, string, multitype:string >
	 */
	public function getUserRelatedTopics($topic_id)
	{
		if ($related_topic = $this->fetch_all('related_topic', 'topic_id = ' . intval($topic_id))) {
			foreach ($related_topic as $key => $val) {
				$topic_ids[] = $val['related_id'];
			}
		}

		if ($topic_ids) {
			return $this->getTopicsByIds($topic_ids);
		}
	}

	public function getAutoRelatedTopics($topic_id)
	{
		if (! $question_ids = $this->getItemIdsByTopicId($topic_id, 'question', 10)) {
			return false;
		}

		if ($question_ids) {
			if ($topics = $this->model('question')->get_question_topic_by_questions($question_ids, 10)) {
				foreach ($topics as $key => $val) {
					if ($val['topic_id'] == $topic_id) {
						unset($topics[$key]);
					}
				}

				return $topics;
			}
		}
	}

	/**
	 * 获取相关话题
	 * @param unknown $topic_id
	 * @return Ambigous <boolean, string, multitype:string , unknown>
	 */
	public function related_topics($topic_id)
	{
		if ($this->isUserSetRelated($topic_id)) {
		// 管理员设置了相关话题
			$related_topic = $this->getUserRelatedTopics($topic_id);
		} else {
		// 自动获取相关话题
			$related_topic = $this->getAutoRelatedTopics($topic_id);
		}

		return $related_topic;
	}

	/**
	 * 话题是否已被管理员设置了相关话题
	 * @param unknown $topic_id
	 * @return Ambigous <>
	 */
	public function isUserSetRelated($topic_id)
	{
		$topic = $this->getTopicById($topic_id);

		return $topic['user_related'];
	}

	/**
	 * 获取话题中最佳回复的信息数据
	 * @param string|array $topic_ids 逗号分隔的话题ids, 或者话题ids
	 * @param int $uid
	 * @param int $limit
	 * @return boolean|string
	 */
	public function getTopicBestAnswersInfo($topic_ids, $uid, $limit)
	{
		if (is_string($topic_ids)) {
			$topic_ids = explode(',', $topic_ids);
		}
		if (!is_digits($topic_ids)) {
			return false;
		}
		$topic_ids = join(',', $topic_ids);

		$cache_key = 'topic_best_answer_action_list_' . md5($topic_ids . $limit);
		if (!$result = Application::cache()->get($cache_key)) {
			/*if ($topic_relation = $this->query_all("SELECT item_id FROM " . $this->get_table('topic_relation') . " WHERE topic_id IN (" . $topic_ids . ") AND `type` = 'question'")) {
				foreach ($topic_relation AS $val) {
					$question_ids[$val['item_id']] = $val['item_id'];
				}
				unset($topic_relation);
			} else {
				return false;
			}

			if ($best_answers = $this->query_all("SELECT question_id, best_answer FROM " . $this->get_table('question') . " WHERE best_answer > 0 AND question_id IN (" . implode(',', $question_ids) . ") ORDER BY update_time DESC LIMIT " . $limit)) {
				unset($question_ids);

				foreach ($best_answers AS $val) {
					$answer_ids[$val['best_answer']] = $val['best_answer'];
					$question_ids[$val['question_id']] = $val['question_id'];
				}
			} else {
				return false;
			}*/

			$sql = 'SELECT q.*
					FROM ' . $this->get_table('question') . ' q
					INNER JOIN ' . $this->get_table('topic_relation') . ' r
					   ON q.question_id = r.item_id
					  AND r.`type` = \'question\'
					WHERE q.best_answer > 0
					  AND r.topic_id IN ("'.$topic_ids.'")
					  AND r.`type` = \'question\'
					ORDER BY update_time DESC LIMIT ' . $limit;
			if ($questions = $this->query_all($sql) ) {
				foreach ($questions AS $val) {
					$answer_ids[$val['best_answer']] = $val['best_answer'];
					$question_ids[$val['question_id']] = $val['question_id'];
				}
			} else {
				return false;
			}
			// 先获取有最佳回复的相关问题
			if ($question_ids && ($questions_info = $this->model('question')->getQuestionsByIds($question_ids))) {
				foreach ($questions_info AS $key => $val) {
					$questions_info[$key]['associate_action'] = ACTION_LOG::ANSWER_QUESTION;

					$action_list_uids[$val['published_uid']] = $val['published_uid'];
				}
			}
			// 根据问题id获取最佳答案， 获取最佳答案作者id
			if ($answer_ids && ($answers_info = $this->model('answer')->getAnswersByIds($answer_ids)) ) {
				foreach ($answers_info AS $val) {
					$action_list_uids[$val['uid']] = $val['uid'];
				}
			}
			// 获取最佳答案的作者信息
			if ($action_list_uids) {
				$action_list_users_info = $this->model('account')->getUsersByIds($action_list_uids);
			}
			// 获取答案的投票用户
			$answers_info_vote_user = $this->model('answer')->getVoteUsersByAnswerIds($answer_ids);

			$answer_attachs = $this->model('publish')->getAttachesByItemTypeAndIds('answer', $answer_ids, 'min');


			foreach ($questions_info AS $key => $val) {
				$result[$key]['question_info'] = $val;
				$result[$key]['user_info'] = $action_list_users_info[$answers_info[$val['best_answer']]['uid']];

				if ($val['has_attach']) {
					$result[$key]['question_info']['attachs'] = $question_attachs[$val['question_id']];
				}

				$result[$key]['answer_info'] = $answers_info[$val['best_answer']];

				if ($val['answer_info']['has_attach']) {
					$result[$key]['answer_info']['attachs'] = $answer_attachs[$val['best_answer']];
				}
			}

			Application::cache()->set($cache_key, $result, get_setting('cache_level_low'));
		}

		// 获取当前用户是否对问题关注， 以及对答案的投票
		if ($uid) {
			foreach ($result AS $key => $val) {
				$question_ids[] = $val['question_info']['question_id'];

				if ($val['question_info']['best_answer']) {
					$answer_ids[] = $val['question_info']['best_answer'];
				}
			}
			// 获取用户是否对问题做了关注， 以及用户对答案的投票状态
			$questions_focus = $this->model('question')->getUserFollowQuestionsStatus($question_ids, $uid);
			$answers_info_vote_status = $this->model('answer')->checkUserVoteAnswerStatus($answer_ids, $uid);
		}

		foreach ($result AS $key => $val) {
			$result[$key]['question_info']['isFollowed'] = $questions_focus[$val['question_info']['question_id']];
			$result[$key]['answer_info']['agree_status'] = intval($answers_info_vote_status[$val['question_info']['best_answer']]);

			$result[$key]['title'] = $val['question_info']['question_content'];
			$result[$key]['link'] = get_js_url('/question/' . $val['question_info']['question_id']);

			$result[$key]['add_time'] = $result[$key]['answer_info']['add_time'];

			$result[$key]['last_action_str'] = ACTION_LOG::format_action_data(ACTION_LOG::ANSWER_QUESTION, $result[$key]['answer_info']['uid'], $result[$key]['user_info']['user_name'], $result[$key]['question_info']);
		}

		return $result;
	}

	public function check_url_token($url_token, $topic_id)
	{
		return $this->count('topic', "url_token = '" . $this->quote($url_token) . "' OR topic_title = '" . $this->quote($url_token) . "' AND topic_id != " . intval($topic_id));
	}

	public function update_url_token($url_token, $topic_id)
	{
		return $this->update('topic', array(
			'url_token' => htmlspecialchars($url_token)
		), 'topic_id = ' . intval($topic_id));
	}

	public function update_seo_title($seo_title, $topic_id)
	{
		return $this->update('topic', array(
			'seo_title' => htmlspecialchars($seo_title)
		), 'topic_id = ' . intval($topic_id));
	}

	public function setTopicItemRelation($uid, $topic_id, $item_id, $type)
	{
		if (!$topic_id OR !$item_id OR !$type) {
			return false;
		}

		if (! $topic_info = $this->getTopicById($topic_id)) {
			return false;
		}

		if ($flag = $this->getTopicItemRelation($topic_id, $item_id, $type))
		{
			return $flag;
		}

		switch ($type) {
			case 'question':
				ACTION_LOG::save_action($uid, $item_id, ACTION_LOG::CATEGORY_QUESTION, ACTION_LOG::ADD_TOPIC, $topic_info['topic_title'], $topic_id);

				ACTION_LOG::save_action($uid, $topic_id, ACTION_LOG::CATEGORY_TOPIC, ACTION_LOG::ADD_TOPIC, $topic_info['topic_title'], $item_id);
			break;
		}

		$this->model('account')->markRecentTopicTitles($uid, $topic_info['topic_title']);

		$insert_id = $this->insert('topic_relation', array(
			'topic_id' => intval($topic_id),
			'type' 	   => $type,
			'item_id'  => intval($item_id),
			'add_time' => time(),
			'uid' 	   => intval($uid),
		));

		$this->model('topic')->updateDiscussCountStatById($topic_id);

		return $insert_id;
	}

	public function getTopicItemRelation($topic_id, $item_id, $type)
	{
		$where[] = 'topic_id = ' . intval($topic_id);
		$where[] = "`type` = '" . $this->quote($type) . "'";

		if ($item_id)
		{
			$where[] = 'item_id = ' . intval($item_id);
		}

		return $this->fetch_one('topic_relation', 'id', implode(' AND ', $where));
	}

	public function get_topics_by_item_id($item_id, $type)
	{
		$result = $this->get_topics_by_item_ids(array(
			$item_id
		), $type);

		return $result[$item_id];
	}

	public function get_topics_by_item_ids($item_ids, $type)
	{
		if (!is_array($item_ids) OR sizeof($item_ids) == 0)
		{
			return false;
		}

		array_walk_recursive($item_ids, 'intval_string');

		if (!$item_topics = $this->fetch_all('topic_relation', "item_id IN(" . implode(',', $item_ids) . ") AND `type` = '" . $this->quote($type) . "'"))
		{
			foreach ($item_ids AS $item_id)
			{
				if (!$result[$item_id])
				{
					$result[$item_id] = array();
				}
			}

			return $result;
		}

		foreach ($item_topics AS $key => $val)
		{
			$topic_ids[] = $val['topic_id'];
		}

		$topics_info = $this->model('topic')->getTopicsByIds($topic_ids);

		foreach ($item_topics AS $key => $val)
		{
			$result[$val['item_id']][] = $topics_info[$val['topic_id']];
		}

		foreach ($item_ids AS $item_id)
		{
			if (!$result[$item_id])
			{
				$result[$item_id] = array();
			}
		}

		return $result;
	}

	public function set_is_parent($topic_id, $is_parent)
	{
		if (!$topic_id)
		{
			return false;
		}

		$to_update_topic['is_parent'] = intval($is_parent);

		if ($to_update_topic['is_parent'] != 0)
		{
			$to_update_topic['parent_id'] = 0;
		}

		if (is_array($topic_id))
		{
			array_walk_recursive($topic_id, 'intval_string');

			$where = 'topic_id IN (' . implode(',', $topic_id) . ')';
		}
		else
		{
			$where = 'topic_id = ' . intval($topic_id);
		}

		return $this->update('topic', $to_update_topic, $where);
	}

	public function set_parent_id($topic_id, $parent_id)
	{
		if (is_array($topic_id))
		{
			array_walk_recursive($topic_id, 'intval_string');

			$where = 'topic_id IN (' . implode(',', $topic_id) . ')';
		}
		else
		{
			$where = 'topic_id = ' . intval($topic_id);
		}

		return $this->update('topic', array('parent_id' => intval($parent_id)), $where);
	}

	/**
	 * 获取根话题
	 * @return array
	 */
	public function getRootTopics()
	{
		$list = array();
		$parent_topic_list_query = $this->fetch_all('topic', 'is_parent = 1', 'topic_title ASC');

		if (!$parent_topic_list_query) {
			return false;
		}

		foreach ($parent_topic_list_query AS $parent_topic_info) {
			if (!$parent_topic_info['url_token']) {
				$parent_topic_info['url_token'] = urlencode($parent_topic_info['topic_title']);
			}

			$list[$parent_topic_info['topic_id']] = $parent_topic_info;
		}

		return $list;
	}

	/**
	 * 获取话题的子话题ids
	 * @param unknown $topic_id
	 * @return unknown
	 */
	public function getChildTopicIds($topic_id)
	{
		$child_topic_ids = array();
		if ($child_topics = $this->query_all("SELECT topic_id FROM " . $this->get_table('topic') . " WHERE parent_id = " . intval($topic_id))) {
			foreach ($child_topics AS $val) {
				$child_topic_ids[] = $val['topic_id'];
			}
		}

		return $child_topic_ids;
	}

	/**
	 * 获取和话题相关的话题ids
	 * @param unknown $topic_id
	 * @return boolean|multitype:
	 */
	public function getRelatedTopicIdsById($topic_id)
	{
		if (!$topic_info = $this->getTopicById($topic_id)) {
			return false;
		}
		// 如果话题已被合并到其他话题， 已合并后的话题作为基础数据
		if ($topic_info['merged_id'] AND $topic_info['merged_id'] != $topic_info['topic_id']) {
			$merged_topic_info = $this->getTopicById($topic_info['merged_id']);

			if ($merged_topic_info) {
				$topic_info = $merged_topic_info;
			}
		}

		$related_topics_ids = array();
		$related_topics = $this->related_topics($topic_info['topic_id']);

		if ($related_topics) {
			foreach ($related_topics AS $related_topic) {
				$related_topics_ids[$related_topic['topic_id']] = $related_topic['topic_id'];
			}
		}

		// 获取子话题
		$child_topic_ids = $this->getChildTopicIds($topic_info['topic_id']);
		if ($child_topic_ids) {
			foreach ($child_topic_ids AS $topic_id) {
				$related_topics_ids[$topic_id] = $topic_id;
			}
		}

		$merged_topic_ids = array($topic_info['topic_id']);
		// 获取合并的话题
		$merged_topics = $this->getMergedTopicById($topic_info['topic_id']);
		if ($merged_topics) {
			foreach ($merged_topics AS $merged_topic) {
				$merged_topic_ids[] = $merged_topic['source_id'];
			}
		}

		return array_merge($related_topics_ids, $merged_topic_ids);
	}
}
