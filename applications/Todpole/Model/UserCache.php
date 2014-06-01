<?php 
require_once  ROOT_DIR . '/Lib/DB/Table.php';
require_once ROOT_DIR . '/Lib/Store.php';

class UserCache
{
    /**
     * [12=>[nick=>'xx', 'sex'=>xx, 'last_update'=>timestamp], 23=>[..], ..]
     * @var array
     */
    public static $cache = array();
    public static $expTime= 5;
    public static $keyPrefix = 'UserCache_';
    public static function get($uid)
    {
        $time_now = time();
        if(isset(self::$cache[$uid]) && $time_now - self::$cache[$uid]['last_update'] < self::$expTime)
        {
            return self::$cache[$uid];
        }
        $user_info = Store::get(self::getKey($uid));
        if($user_info)
        {
            $user_info['last_update'] = $time_now;
            self::$cache[$uid] = $user_info;
            return $user_info;
        }
        else
        {
            if($uid>100000000)
            {
                $user_table = new Table('user', 'uid');
                $user_info = $user_table->get('nick,sex',$uid);
                if($user_info)
                {
                    Store::set(self::getKey($uid), $user_info);
                    $user_info['last_update'] = $time_now;
                    self::$cache[$uid] = $user_info;
                    return $user_info;
                }
                else
                {
                    self::$cache[$uid] = array('last_update' => $time_now);
                }
            }
            else
            {
                self::$cache[$uid] = array('last_update' => $time_now);
            }
        }
        return self::$cache[$uid];
    }
    
    public static function delete($uid)
    {
        unset(self::$cache[$uid]);
        Store::delete(self::getKey($uid));
    }
    
    public static function set($uid, $key, $value)
    {
        $user_info = self::get($uid);
        $user_info[$key] = $value;
        $user_info['last_update'] = time();
        Store::set(self::getKey($uid), $user_info);
        self::$cache[$uid] = $user_info;
    }
    
    public static function getKey($uid)
    {
        return self::$keyPrefix.$uid;
    }
}