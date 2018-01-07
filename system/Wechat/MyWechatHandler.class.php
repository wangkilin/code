<?php

class TestHandler
{
    public function __construct()
    {

    }

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

    public function handleClick(WechatListenerAbstract $request)
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
            $msg = '收到点击：'. $msgPool[$msgId];
        } else {
            switch ($request->getRequest('eventkey')) {
                case 'about_contact':
                    break;

                default:
                    break;
            }
            $msg = '收到点击：' . $request->getRequest('eventkey');
        }

        $WechatTextResponseInstance = new WechatTextResponse($fromuser, $touser, $msg);

        return $WechatTextResponseInstance;
    }

    public function handleSubscribe (WechatListenerAbstract $request)
    {
        $fromuser = $request->getRequest('fromusername');
        $touser = $request->getRequest('tousername');
    }

    public function handleImage (WechatListenerAbstract $request)
    {
        $fromuser = $request->getRequest('fromusername');
        $touser = $request->getRequest('tousername');
        $picurl = $request->getRequest('PicUrl');
        $filepath = C('TMP_IMG_SAVE_PATH');
        if (!is_dir($filepath)){
            mkdir($filepath, 0777, true);
        }
        $filename = $filepath.md5($picurl).'.jpg';
        file_put_contents($filename, file_get_contents($picurl));
        
        $params['picture'] = $filename;
        $params['wechatid'] = $fromuser;
        $res = \AppTools::webService('\Model\WeChat\WeChat', 'wechatSave', array('params'=>$params));
        unlink($filename);//删除临时图片文件
        if ($res['status']!=0) {
            $msg = '服务不可用，请稍后再试';
        }else{
            $params = array();
            $params['cardid'] = $res['data']['cardid'];
            $res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
            
            $info = $res['data']['wechats'][0];
            if (empty($info)) {
                $msg = '数据添加失败';
            }else{
                $items = array(
                    new WechatNewsArticle($info['FN'], $info['ORG'], $info['picture'], U('/demo/wechat/wdetail', array("cardid"=>$info['cardid']), false, true)),
                );
                $WechatTextResponseInstance = new WechatNewsResponse($fromuser,$touser,$items);

                return $WechatTextResponseInstance;
            }
        } 
        $WechatTextResponseInstance = new WechatTextResponse($fromuser, $touser, $msg);
            return $WechatTextResponseInstance;
    }

}