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

set_include_path(get_include_path() . PATH_SEPARATOR .
                     realpath(dirname(__FILE__).'/'));

require_once('Zend/Loader/Autoloader.php');
Zend_Loader_Autoloader::getInstance();
require_once './Wechat/WechatListener.class.php';
require_once('./Wechat/MyWechatHandler.class.php');
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
$testHandler = new TestHandler();
$wechat->setHandler(array('text'        => array($testHandler, 'handleText'),
                          'subscribe'   => array($testHandler, 'handleSubscribe'),
                          'click'   => array($testHandler, 'handleClick'),
                         )
                    )
       ->listen();


/*EOF*/