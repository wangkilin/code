<?php
require_once(dirname(__FILE__) . '/../WechatAbstract.class.php');

/**
 * 用于回复的基本消息类型
 */
abstract class ResponseAbstract extends WechatAbstract
{

    abstract public function __toString();
}

/* EOF */