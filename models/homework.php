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

defined('iCodeBang_Com') OR die('Access denied!');

class homeworkModel extends Model
{
    protected $table = 'course_homework';

	static public $courses = array();

	/**
	 * 根据ids删除
	 * @param array | int $ids 数据条目id列表
	 * @return boolean
	 */
	public function removeById ($id)
	{
		return $this->removeByIds($ids);
	}
	/**
	 * 根据ids删除
	 * @param array | int $ids 数据条目id列表
	 * @return boolean
	 */
	public function removeByIds ($ids)
	{
		return $this->deleteByIds($ids, $this->table);
	}

	/**
	 * 更新文章
	 * @param int $id 文章id
	 * @param array $data 文章信息
	 * @return bool
	 */
	public function modify ($id, $data)
	{
		$result = false;
		if ($set = $this->processData($data)) {
		    $this->update($this->table, $set, 'id = ' . intval($id));

		    // 设置了附件， 绑定附件和文章关系
		    if ($set['attach_id']) {
		        $data['batchKey'] = '';
		        $this->model('attach')->bindAttachAndItem('homework', $id, $data['batchKey']);
		    }
		}

		return $result;
	}

	/**
	 * 处理标签数据， 供更新和添加使用
	 * @param array $data
	 * @return multitype:string NULL
	 */
	protected function processData ($data)
	{
		$set = array();
		if (isset($data['content'])) {
			$set['content'] = $data['content'];
		}
		if (isset($data['course_id'])) {
			$set['course_id'] = intval($data['course_id']);
		}
		if (isset($data['attach_id'])) {
		    $set['attach_id'] = intval($data['attach_id']);
		}
		if (isset($data['sort'])) {
		    $set['sort'] = intval($data['sort']);
		}
		if (isset($data['uid'])) {
			$set['uid']  = $data['uid'];
		}

		return $set;
	}

	/**
	 * 添加新教程
	 * @param array $data 教程信息数组
	 * @return number
	 */
	public function add ($data)
	{
		$id = 0;
		if ($set = $this->processData($data)) {
			$set['add_time'] = time();
			$id = $this->insert($this->table, $set);

			// 设置了附件， 绑定附件和文章关系
			if ($set['attach_id']) {
			    $data['batchKey'] = '';
				$this->model('attach')->bindAttachAndItem('homework', $id, $data['batchKey']);
			}
		}

		return $id;
	}
}
/* EOF */