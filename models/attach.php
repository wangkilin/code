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

class attachModel extends Model
{
    /**
     * 绑定附件和内容id
     * @param string $item_type
     * @param int    $item_id
     * @param string $attach_access_key 附件所属的key。 多个附件可对应一个key
     */
    public function bindAttachAndItem ($item_type, $item_id, $attach_access_key)
    {
        if (!is_digits($item_id) OR !$attach_access_key) {
            return false;
        }

        $where = "item_type = '" . $this->quote($item_type) . "'
              AND item_id = 0
              AND access_key = '" . $this->quote($attach_access_key) . "'";

        return  $this->update('attach', array('item_id' => $item_id), $where);
    }

    /**
     * 获取发布内容对应的附件信息列表
     * @param string $itemTyoe 发布内容类型： article/course/question/page 等
     * @param array  $itemIds  发布内容条目id列表
     *
     * @return array
     */
    public function getAttachListByItemids ($itemType, $itemIds)
    {
        $where = 'item_type="'.$this->quote($itemType).'" AND item_id IN (' . $this->quote(join(',', $itemIds)) . ')';
        $itemList = $this->model('attach')->fetch_all('', $where);
        $attachList = array();
        foreach($itemList as $_item) {
            isset($attachList[$_item['item_id']]) OR $attachList[$_item['item_id']] = array();
            list($k, $v) = each($this->model('publish')->parse_attach_data(array($_item), $_item['item_type']) );
            $attachList[$_item['item_id']][$k] = $v;
        }

        return $attachList;
    }

    /**
     * 获取发布内容条目中的
     * @param string $itemTyoe 发布内容类型： article/course/question/page 等
     * @param array  $itemIds  发布内容条目id列表
     */
    public function getThumbListByItemids ($itemType, $itemIds)
    {
        $imgList = array();
        $attachList = $this->getAttachListByItemids($itemType, $itemIds);

        foreach ($attachList as $_itemId => $_itemList) {
            foreach ($_itemList as $_item) {
                if ($_item['is_image']) {
                    $imgList[$_itemId] = $_item['attachment'];
                    break;
                }
            }
        }

        return $imgList;
    }
}

/* EOF */
