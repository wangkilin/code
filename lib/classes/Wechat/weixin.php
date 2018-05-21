<?php

/**
*wechatphptest
appID
wxd25e67dfa7b2dd59
appsecret
d91611978da6c8b67f41a9945c0599ae

*/

//define your token
define("TOKEN", "TokenForKilinPublicPlatform");

require_once './WechatListener.class.php';
require_once('./MyWechatHandler.class.php');

function doLog ($msg, $level='debug')
{
	$msg = '[' . $level . ']' . date('Y-m-d H:i:s  ') . $msg ."\r\n";
    @error_log($msg, 3, 'testLog.php');
}

/*
$wechatObj=newwechatCallbackapiTest();
$wechatObj->valid();
*/
/*
$_GET['echostr'] = 'cccc';
$_GET['signature'] = 'cccc';
$_GET['timestamp'] = 'ccccc';
$_GET['nonce'] = 'dddd';
/**/
/* subscribe
$GLOBALS['HTTP_RAW_POST_DATA'] = "
<xml>
<ToUserName><![CDATA[toUser]]></ToUserName>
<FromUserName><![CDATA[oGOQft12biMFq3aAdwIf6p9DrGAs]]></FromUserName>
<CreateTime>123456789</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
</xml>";
//*/

/*
$GLOBALS['HTTP_RAW_POST_DATA'] = "
<xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUserName]]></FromUserName>
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[this is a test]]></Content>
 <MsgId>1234567890123456</MsgId>
 </xml>
";
//*/
$wechat = new WechatListener(TOKEN);
$wechatHandler = new MyWechatHandler();
$wechat->setHandler(array('text'        => array($wechatHandler, 'handleText'),
                          'subscribe'   => array($wechatHandler, 'handleSubscribe'),
                          'click'       => array($wechatHandler, 'handleClick'),
                         )
                    )
       ->setOption('logger', 'doLog')
       ->listen();


/*EOF*/