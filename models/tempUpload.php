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

class tempUploadModel extends Model
{
	protected $table = 'temp_upload';

	public function bindUploadAndItem ($ids, $itemId)
	{
		if (is_digits($ids)) {
			$ids = array($ids);
		}
		if (! is_digits($itemId) || ! is_array($ids) || count($ids)==0) {
			return false;
		}
		array_walk($ids, 'intval_string');

		$where = "item_id = 0 AND id IN (" . join(', ', $ids) . ")";

		return  $this->update('attach', array('item_id' => $item_id), $where);
	}

	public function add ($data)
	{
		$id = 0;
		if ($set = $this->processData($data)) {
			$set['add_time'] = APP_START_TIME;
			$id = $this->insert('temp_upload', $set);
		}

		return $id;
	}

	public function processData ($data)
	{
		$set = array();
		if (isset($data['file_name'])) {
			$set['file_name'] = htmlspecialchars($data['file_name']);
		}
		if (isset($data['file_location'])) {
			$set['file_location'] = htmlspecialchars($data['file_location']);
		}
		if (isset($data['item_type'])) {
			$set['item_type'] = htmlspecialchars($data['item_type']);
		}
		if (isset($data['item_id'])) {
			$set['item_id'] = intval($data['file_location']);
		}

		return $set;
	}

	public function update ($id, $data)
	{
		$result = false;
		if ($set = $this->processData($data)) {
			$result = parent::update('temp_upload', $set, 'id = ' . intval($id));
		}

		return $result;
	}
}

/* EOF */