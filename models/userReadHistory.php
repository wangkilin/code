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

class userReadHistoryModel extends Model
{
    protected $table = 'user_read_history';

    /**
     * 根据用户id和条目类型id获取浏览历史
     * @param int $uid 用户id
     * @param int $itemId 条目id
     * @param string $itemType 条目类型
     * @return array
     */
    public function getByUidAndItemId($uid, $itemId, $itemType)
    {
        $where = array('uid = ' . intval($uid),
                       'item_id = ' . intval($itemId),
                       'item_type ="' . $this->quote($itemType) . '"'
                 );

        $where = join(' AND ', $where);

        $item = $this->fetch_row($this->table, $where);
        if ($item) {
            $item['page_position'] && $item['page_position'] = json_decode($item['page_position'], true);
            $item['read_content_marks'] && $item['read_content_marks'] = json_decode($item['read_content_marks'], true);
        }

        return $item;
    }

    /**
     * 获取用户浏览记录
     * @param int $uid
     * @param string $itemType
     * @return array
     */
    public function getListByUid ($uid, $itemType=null)
    {
        $where = 'uid = ' . intval($uid);
        if ($itemType) {
            $where .= ' AND item_type="' . $this->quote($itemType) . '"';
        }

        return $this->fetch_all($this->table, $where);
    }

    /**
     * 更新文章
     * @param int $id 文章id
     * @param array $data 文章信息
     * @return bool
     */
    public function update ($id, $data, array $replaceTag = array())
    {
        $result = false;
        if ($set = $this->processData($data)) {
            $itemInfo = array();
            if ($replaceTag) {
                $itemInfo = $this->getById($id, $this->table);
            }
            // 设定了页面阅读位置
            if ($set['page_position']) {
                if (! empty($itemInfo['page_position']) && empty($replaceTag['page_position']) ) {
                    $set['page_position'] = array_merge_recursive($set['page_position'],$itemInfo['page_position'] );

                }
                $set['page_position'] = json_encode($set['page_position']);
            }
            // 设定了页面内容阅读记录
            if ($set['read_content_marks']) {
                if (! empty($itemInfo['read_content_marks']) && empty($replaceTag['read_content_marks']) ) {
                    $set['read_content_marks'] = array_merge_recursive($set['read_content_marks'],$itemInfo['read_content_marks'] );

                }
                $set['read_content_marks'] = json_encode($set['read_content_marks']);
            }
            $result = parent::update($this->table, $set, 'id = ' . intval($id));
        }

        return $result;
    }

    /**
     * 处理数据， 供更新和添加使用
     * @param array $data
     * @return array
     */
    protected function processData ($data)
    {
        $set = array();
        if (isset($data['read_content_marks']) && is_array($data['read_content_marks'])) {
            $set['read_content_marks'] = $data['read_content_marks'];
        }
        if (isset($data['page_position']) && is_array($data['page_position'])) {
            $set['page_position'] = $data['page_position'];
        }
        if (isset($data['item_type'])) {
            $set['item_type'] = $this->quote($data['item_type']);
        }
        if (isset($data['item_id'])) {
            $set['item_id'] = intval($data['item_id']);
        }
        if (isset($data['uid'])) {
            $set['uid']  = intval($data['uid']);
        }
        if ($set) {
            $set['last_time'] = APP_START_TIME;
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
            isset($set['page_position']) AND $set['page_position'] = json_encode($set['page_position']);
            isset($set['read_content_marks']) AND $set['read_content_marks'] = json_encode($set['read_content_marks']);

            $id = $this->insert($this->table, $set);
        }

        return $id;
    }
}

/* EOF */