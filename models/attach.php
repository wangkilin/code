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
}

/* EOF */
