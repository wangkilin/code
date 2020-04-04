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

class metalSubCategoryModel extends Model
{
    protected $table = 'metal_sub_category';

    public function getAll()
    {
        return $this->fetch_all();
    }

    public function getByName ($uid)
    {
        return $this->fetch_row($this->table, 'uid='.intval($uid));
    }

    public function getByCategoryId ($categoryId)
    {
        return $this->fetch_all($this->table, 'category_id = ' . $categoryId);
    }

    public function setHomeworkRecord ($uid, $courseId)
    {
        $item = $this->getByUId($uid);
        if ($item) {
            $result = $this->change($item['id'], $uid, $courseId);
        } else {
            $result = $this->add($uid, $courseId);
        }

        return $result;
    }

    /**
     * 根据ids删除
     * @param array | int $ids 数据条目id列表
     * @return boolean
     */
    public function removeById ($id)
    {
        return $this->removeByIds($id);
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
    public function change ($id, $uid, $courseId)
    {
        $result = false;
        $nowDate = date('Y-m-d', APP_START_TIME);
        $yesterdayDate = date('Y-m-d', APP_START_TIME - 3600 * 24);
        $sql = 'UPDATE ' . $this->get_table() . '
                SET  latest_course_id  = ' . intval($courseId) . ',
                     keep_days = ( CASE latest_date
                                   WHEN "' . $nowDate . '" THEN keep_days
                                   WHEN "' . $yesterdayDate . '" THEN keep_days + 1
                                   ELSE 1 END ),
                     latest_date = "' . $nowDate . '"
                WHERE id = ' . intval($id);

        return $this->query($sql);
    }

    /**
     * 添加新教程
     * @param array $data 教程信息数组
     * @return number
     */
    public function add ($uid, $courseId)
    {
        $sql = 'INSERT INTO ' . $this->get_table() . '
                    (uid, latest_course_id, keep_days, latest_date)
                VALUES
                   ('.intval($uid).', ' . intval($courseId) . ', 1, "' .  date('Y-m-d', APP_START_TIME) . '")';
        return $this->query($sql);

        return $id;
    }
}
/* EOF */
