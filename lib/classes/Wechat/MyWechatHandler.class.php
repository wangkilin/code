<?php

class MyWechatHandler
{
    public function __construct()
    {

    }

    /**
     * 收到文本消息
     * @param WechatListenerAbstract $request
     */
    public function handleText(WechatListenerAbstract $request)
    {
        $msg = $request->getRequest('content');
        $fromuser = $request->getRequest('fromusername');
        $touser = $request->getRequest('tousername');

        if ('oGOQft12biMFq3aAdwIf6p9DrGAs'==$fromuser) {
            $msgPool = array('猪，发啥内容呢？',
                    '猪，没看明白你发的啥呢？再发一次呗。',
                    '猪，你是说："' . $msg . '" ?',
                    '猪，啥都别说了。 好好长肉吧！',
                    '大肥猪，好好长肉。 不要乱发消息！',
                    '猪啊，你好可爱哦！',
                    '猪， 你说我怎么这么稀罕你呢！',
                    '猪，加油！');
            $msgId = rand(0, 7);
            $msg = $msgPool[$msgId];
        } else {
            $msg = 'We got you sent message: ' . $msg . 'this msg is handled by callback';
        }

        $WechatTextResponseInstance = new WechatTextResponse($fromuser, $touser, $msg);

        return $WechatTextResponseInstance;
    }

    /**
     * 点击菜单
     * @param WechatListenerAbstract $request
     * @return WechatTextResponse
     */
    public function handleClick(WechatListenerAbstract $request)
    {
        $msg = $request->getRequest('content');
        $fromuser = $request->getRequest('fromusername');
        $touser = $request->getRequest('tousername');

//         if ('oGOQft12biMFq3aAdwIf6p9DrGAs'==$fromuser) {
//             $msgPool = array('猪，你是说："' . $msg . '" ?',);
//             $msgId = 0;
//             $msg = '收到点击：'. $msgPool[$msgId];
//         } else {
            switch ($request->getRequest('eventkey')) {
            	case 'english':
            		$msg = $this->showEnglishMsg($request);
            		break;
            	case 'travel':
            		$msg = $this->showTravelMsg($request);
            		break;
                case 'about_contact':
                    break;

                default:
                    break;
            }
            $msg = '收到点击：' . $request->getRequest('eventkey');
//         }

        if (! is_object($msg)) {
        	$msg = new WechatTextResponse($fromuser, $touser, $msg);
        }

        $WechatTextResponseInstance = new WechatTextResponse($fromuser, $touser, $msg);

        return $msg;
    }

    /**
     * 用户关注公众号
     * @param WechatListenerAbstract $request
     */
    public function handleSubscribe (WechatListenerAbstract $request)
    {
        $fromuser = $request->getRequest('fromusername');
        $touser = $request->getRequest('tousername');
    }

    /**
     * 语音消息处理
     * @param WechatListenerAbstract $request
     */
    public function handleVoice (WechatListenerAbstract $request)
    {

    }

    /**
     * 视频消息处理
     * @param WechatListenerAbstract $request
     */
    public function handleVideo (WechatListenerAbstract $request)
    {

    }

    /**
     * 地理位置消息处理
     * @param WechatListenerAbstract $request
     */
    public function handleLocation (WechatListenerAbstract $request)
    {

    }

    protected function showEnglishMsg (WechatListenerAbstract $request)
    {
    	$items = array(
    	             new WechatNewsArticle(
    	             		'每日推送',
    	             		'随机推送的单词和相应例句',
    	             		$picUrl,
    	             		$linkUrl
    	             ),
    	             new WechatNewsArticle(
    	             		'四六级',
    	             		'查看四级和六级英语单词和释义',
    	             		$picUrl,
    	             		$linkUrl
    	             ),
    	             new WechatNewsArticle(
    	             		'单词释义',
    	             		'查看英文单词解释，例句',
    	             		$picUrl,
    	             		$linkUrl
    	             ),
    	             new WechatNewsArticle(
    	             		'听英文',
    	             		'在线听英文句子，对话',
    	             		$picUrl,
    	             		$linkUrl
    	             ),
    	         );
    	$msg = new WechatNewsResponse(
    			    $this->getRequest('fromusername'),
    			    $this->getRequest('tousername'),
    			    $items
    		   );
    }

    protected function showTravelMsg (WechatListenerAbstract $request)
    {
    	$items = array(
    	             new WechatNewsArticle(
    	             		'周边游',
    	             		'随机推送的单词和相应例句',
    	             		$picUrl,
    	             		$linkUrl
    	             ),
    	             new WechatNewsArticle(
    	             		'当季热门',
    	             		'查看四级和六级英语单词和释义',
    	             		$picUrl,
    	             		$linkUrl
    	             ),
    	             new WechatNewsArticle(
    	             		'结伴游',
    	             		'查看英文单词解释，例句',
    	             		$picUrl,
    	             		$linkUrl
    	             ),
    	             new WechatNewsArticle(
    	             		'优惠福利',
    	             		'在线听英文句子，对话',
    	             		$picUrl,
    	             		$linkUrl
    	             ),
    	         );
    	$msg = new WechatNewsResponse(
    			    $this->getRequest('fromusername'),
    			    $this->getRequest('tousername'),
    			    $items
    		   );
    }

    protected function showIcodebangMsg (WechatListenerAbstract $request)
    {

    }

    protected function showEdulongMsg (WechatListenerAbstract $request)
    {

    }

}