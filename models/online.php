<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   ========================================
|   by WeCenter Software
|   © 2011 - 2013 WeCenter. All Rights Reserved
|   http://www.wecenter.com
|   ========================================
|   Support: WeCenter@qq.com
|   
+---------------------------------------------------------------------------
*/


if (!defined('iCodeBang_Com'))
{
    die;
}

class onlineModel extends Model
{
    public function online_active($uid, $last_active)
    {        
        if (!$uid OR ($last_active + 60) > time())
        {
            return false;
        }
        
        $online_data = array(
            'uid' => $uid,
            'last_active' => time(),
            'ip' => ip2long(fetch_ip()),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'active_url' => $_SERVER['HTTP_REFERER'],
        );
        
        if ($user_online = $this->fetch_row('users_online', 'uid = ' . intval($uid)))
        {
            $this->shutdown_update('users_online', $online_data, 'uid = ' . $user_online['uid']);
        }
        else
        {            
            $this->insert('users_online', $online_data);
        }
        
        $online_time = time() - $last_active;
        
        if ($online_time > 300)
        {
            $online_time = 1;
        }
        
        $this->shutdown_query("UPDATE " . get_table('users') . ' SET online_time = online_time + ' . intval($online_time) . ', last_active = ' . time() . ' WHERE uid = ' . intval($uid));
        
        return true;
    }
    
    public function delete_expire_users()
    {        
        return $this->delete('users_online', 'last_active < ' . (time() - 1800));
    }
}
