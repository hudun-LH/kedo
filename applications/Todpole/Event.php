<?php
/**
 * 
 * 聊天主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * @author walkor <worker-man@qq.com>
 * 
 */

require_once ROOT_DIR . '/Lib/Gateway.php';
require_once ROOT_DIR . '/Protocols/WebSocket.php';

class Event
{

   
// 禁止使用的关键字,使用此关键字直接踢掉用户
   protected static $black_words = array(
                 '中华人民共和国',
                 '共产党',
                 '中国',
                 '习近平',
                 '中共',
                 '共惨党',
                 '内射',
                 '精子',
                 '卵子',
                 '打炮',
                 '约炮',
                 '一夜情',
                 '性交',
                 '射精',
                 '做爱',
                 '毛片',
                 '黄片',
                 '法轮',
                 '搞基',
                 '搞鸡',
                 '鸡巴',
                 '內射',
                 '强奸',
                 '江泽民',
                 '胡锦涛',
                 '温家宝',
                 '毛泽东',
                 '周恩来',
                 '台独',
                 '台獨',
                 '64',
                 '六四',
                 '台灣',
                 '台湾',
                 '中國',
                 '天安',
                 '六.四',
                 '独立',
                 '独力',
                 '獨立',
                );
    // 封禁的ip
    protected static $black_ip = array(
         '171.221.108.141' => '171.221.108.141',
         '54.251.116.230'  => '54.251.116.230',
         '171.221.111.9'   => '171.221.111.9',
         '221.150.182.185' => '221.150.182.185',
         '1.161.146.124'   => '1.161.146.124',
         '180.176.11.57'   => '180.176.11.57',
         '27.147.9.150'    => '27.147.9.150',
         '1.161.145.142'   => '1.161.145.142',
         '188.99.143.221'  => '188.99.143.221',
         '114.33.161.237'  => '114.33.161.237',
         '113.255.54.131'  => '113.255.54.131',
         '175.170.1.147'   => '175.170.1.147',
         '1.173.216.78'    => '1.173.216.78',
         '36.226.233.90'   => '36.226.233.90',
    );

    protected static $blackDomain = array(
       'kd.ico.la' => 'kd.ico.la',
       'kedou.cnxz58.com' => 'kedou.cnxz58.com',
       '1.junxia.sinaapp.com' => '1.junxia.sinaapp.com',
       'junxia.sinaapp.com'   => 'junxia.sinaapp.com',
       'www.fbj4.com'         => 'www.fbj4.com',
       'www.c78.net'          => 'www.c78.net',
       'aivgou.cn'            => 'aivgou.cn',
       'guanq.com'            => 'guanq.com',
    );

   /**
    * 当有用户连接时，会触发该方法
    */
   public static function onConnect($message)
   {
       // WebSocket 握手阶段
       if(0 === strpos($message, 'GET'))
       {
           // Origin
           $origin = '';
           if(preg_match("/Origin: *(.*?)\r\n/", $message, $match))
           {
               $origin = trim($match[1]);
               $host_info = @parse_url($origin);
               if(isset($host_info['host']))
               {
                   $origin = $host_info['host'];
               }
               
               if(isset(self::$blackDomain[$origin]) && rand(0, 5) >0)
               {
                  GateWay::kickCurrentUser('');
                  file_put_contents('/tmp/origin.log', $origin."\t".Context::$client_ip."\trestrain\t" . date('Y-m-d H:i:s')."\n", FILE_APPEND);
                  return;
               }
               file_put_contents('/tmp/origin.log', $origin."\t".Context::$client_ip."\t\t" . date('Y-m-d H:i:s')."\n", FILE_APPEND);
               
           }

           // 解析Sec-WebSocket-Key
           $Sec_WebSocket_Key = '';
           if(preg_match("/Sec-WebSocket-Key: *(.*?)\r\n/", $message, $match))
           {
               $Sec_WebSocket_Key = $match[1];
           }
           $new_key = base64_encode(sha1($Sec_WebSocket_Key."258EAFA5-E914-47DA-95CA-C5AB0DC85B11",true));
           // 握手返回的数据
           $new_message = "HTTP/1.1 101 Switching Protocols\r\n";
           $new_message .= "Upgrade: websocket\r\n";
           $new_message .= "Sec-WebSocket-Version: 13\r\n";
           $new_message .= "Connection: Upgrade\r\n";
           $new_message .= "Sec-WebSocket-Accept: " . $new_key . "\r\n\r\n";
           
           GateWay::sendToCurrentUid($new_message, true);
           
           // 把时间戳当成uid，todpole程序uid
           $uid = (int)(substr(strval(microtime(true)), 2, 10)*100);
           
           $new_message = '{"type":"welcome","id":'.$uid.'}';
           
           // 记录uid到gateway通信地址的映射
           GateWay::storeUid($uid);
           
           // 发送数据包到address对应的gateway，确认connection成功
           GateWay::notifyConnectionSuccess($uid);
           
           // 发送数据包到客户端 完成握手
           return GateWay::sendToCurrentUid($new_message);
       }
       // 如果是flash发来的policy请求
       elseif(trim($message) === '<policy-file-request/>')
       {
           $policy_xml = '<?xml version="1.0"?><cross-domain-policy><site-control permitted-cross-domain-policies="all"/><allow-access-from domain="*" to-ports="*"/></cross-domain-policy>'."\0";
           return GateWay::sendToCurrentUid($policy_xml, true);
       }
       
       return null;
   }
   
   /**
    * 当用户断开连接时
    * @param integer $uid 用户id 
    */
   public static function onClose($uid)
   {
       // 广播 xxx 退出了
       GateWay::sendToAll(json_encode(array('type'=>'closed', 'id'=>$uid)));
   }
   
   /**
    * 有消息时
    * @param int $uid
    * @param string $message
    */
   public static function onMessage($uid, $message)
   {
        // $message len < 7 是用户退出了,直接返回，等待socket关闭触发onclose方法
        if(strlen($message) < 7)
        {
            return ;
        }
        $message = \WebSocket::decode($message);
        $message_data = json_decode($message, true);
        if(!$message_data)
        {
            return ;
        }


        if(isset($message_data['name']) && strlen($message_data['name'])>30)
        {
           $message_data['name'] = substr($message_data['name'], 0, 30);
        }

        $check_str = '';
        if(isset($message_data['name']))
        {
          $check_str = $message_data['name'];
        }
        if(isset($message_data['message']))
        {
           $check_str = $message_data['message'];
        }
        if( preg_match('/天.+?安/i', $check_str) || preg_match('/六.+?四/i', $check_str) || preg_match('/支那/i', $check_str) || preg_match('/ctrl.+?w/i', $check_str) || preg_match('/alt.+?f.*?4/i', $check_str) || preg_match('/crtl.+?w/i', $check_str) || preg_match('/ctl.+?w/i', $check_str) || preg_match('/i.*?c.*?o.*?l.*?a/i', $check_str))
        {
           file_put_contents('/tmp/kick.log', $uid.'|'.Context::$client_ip." {$check_str} black words.\n",   FILE_APPEND);
           self::onClose($uid);
           GateWay::kickUid($uid, '');
           return;
         }



        if(isset($message_data['name']))
        {
           if($message_data['name'] == '警察')file_put_contents('/tmp/xxx.log', Context::$client_ip."\t".$message_data['name']."\n", FILE_APPEND);
           $message_data['name'] = str_replace(array('精子', '卵子'), array('●～', '⊙⊙'), $message_data['name']);
           $message_data['name'] = str_replace(self::$black_words, '**', $message_data['name']);
        }

        if(isset($message_data['message']))
        {
           $message_data['message'] = str_replace(array('精子', '卵子'), array('●～', '⊙⊙'), $message_data['message']);
           $message_data['message'] = str_replace(self::$black_words, '**', $message_data['message']);
        }

        
        switch($message_data['type'])
        {
            // 更新用户
            case 'update':
                //if(rand(0, 1) > 0) return;
                if(isset(self::$black_ip[Context::$client_ip]))
                {
                    self::onClose($uid);
                    file_put_contents('/tmp/kick.log', $uid.'|'.Context::$client_ip." ip kicked \n",   FILE_APPEND);
                    GateWay::kickUid($uid, '');
                    return;
                }

                // 转播给所有用户
                Gateway::sendToAll(json_encode(
                        array(
                                'type'     => 'update',
                                'id'         => $uid,
                                'angle'   => $message_data["angle"]+0,
                                'momentum' => $message_data["momentum"]+0,
                                'x'                   => $message_data["x"]+0,
                                'y'                   => $message_data["y"]+0,
                                //'life'                => 1,
                                'name'           => isset($message_data['name']) ? $message_data['name'] : 'Guest.'.$uid,
                                //'authorized'  => false,
                                'sex'               => isset($message_data["sex"]) ? $message_data["sex"]+0 : -1,
                                'icon'             => isset($message_data['icon']) ? $message_data['icon'] : '/images/default.png',
                                )
                        ));
                return;
            // 聊天
            case 'message':
                // 向大家说
                $new_message = array(
                    'type'=>'message', 
                    'id'=>$uid,
                    'message'=>$message_data['message'],
                );
                return Gateway::sendToAll(json_encode($new_message));
        }
   }
}
