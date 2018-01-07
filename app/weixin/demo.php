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

importClass('ConvertFormat', INC_PATH . 'Wechat/');
importClass('Request', INC_PATH . 'Wechat/');
importClass('WechatListener', INC_PATH . 'Wechat/');
importClass('MyWechatHandler', INC_PATH . 'Wechat/');


class demo extends Controller
{

	//get请求方式
	const METHOD_GET  = 'get';
	//post请求方式
	const METHOD_POST = 'post';
	private $session = null;

    protected $webSerivce = null;
    private $_unCallMethods = array (
            '__construct',
            '__set',
            'get',
            '__get',
            '__isset',
            '__call',
            '__destruct',
            '_initialize',
    );

    public function setup ()
    {}
    public function beforeAction ()
    {
        $options = array('decodeResponseMode' => \Request::DECODE_MODE_ARRAY,
                         'logger'             => 'trace',
        );
        $this->wechatRequest =  $this->getWechatRequester()
                                     ->setOptions($options)
                                ;


        $reflectionClass = new \ReflectionClass($this);
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);

        $hasLink = true;
        $links = '';

        foreach ($methods as $_method) {
            if (in_array(strtolower($_method->getName()), array('listener'))) {
                //return;
            }
            $methodName = $_method->getName();
            if (substr($methodName, -7) !='_action') {
                continue;
            }
            $methodComment = $_method->getDocComment();
            preg_match('/\/\*([^@]*)@/us', (string)$methodComment, $match);
            if (!$match) {
                preg_match('/\/\*(.*)\*\//us', (string)$methodComment, $match);
            }

            if (strtolower($methodName)==strtolower(ACTION)) {
                $hasLink = $hasLink && strpos($methodComment, '@noLink')===false;
            }

            if ($match && isset($match[1])) {
                $match = trim($match[1], "* \r\n");
                $match = explode("\n", $match);
                $methodComment = $methodName . '('.$match[0] . ')';
            } else {
                $methodComment = $methodName;
            }

            $links .= '<a href="?/'.MODULE.'/'.CONTROLLER.'/'.substr($methodName,0, -7).'/">' . trim($methodComment, " \r\n*"). '</a>' . "<br/>\n";



        }
        var_dump(MODULE, CONTROLLER, ACTION);

        if ($hasLink) {
            echo $links;
            echo '<hr/>';
        }
    }

    /**
     * Index
     */
    public function index_action ()
    {

    }

    public function listener ()
    {
        $xml = (array) simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);
        Log::write($GLOBALS['HTTP_RAW_POST_DATA']);
        Log::write(' 公众号接收到的xml格式：'.json_encode($xml));

        if ($xml['MsgType'] == 'image') {
/*             if ('ofIP5vqZUs5C5Y-AiJ11vgXp-K3g' == $xml['FromUserName']) {
                //吴州
                $this->wuzhou($xml);
            } else { */
                //崔盛辉
                $this->cuishenghui($xml);
            //}
        }
        if ($xml['MsgType'] == 'voice') {
            //张鹏
            $this->zhangpeng($xml);
        }else if($xml['MsgType'] == 'text'){
        	$this->zhangpeng($xml);
        }else if($xml['MsgType'] == 'event'){
        	if($xml['Event'] == 'subscribe'){
        		$this->saveWxUserInfo($xml);
        	}
        }

        die;
        exit($_GET['echostr']);

        /*
        $wechat = new WechatListener(TOKEN);
        $testHandler = new TestHandler();
        $wechat->setHandler(array('text'        => array($testHandler, 'handleText'),
                'subscribe'   => array($testHandler, 'handleSubscribe'),
                'click'   => array($testHandler, 'handleClick'),
        )
        )
        ->listen();
        */
    }

    /**
     * 菜单上传图片
     * @param unknown $xml
     */
    public function cuishenghui($xml){
        $time = time();
        $url  = $xml['PicUrl'];

        //文字消息
        $msg = "<xml>
            <ToUserName><![CDATA[{$xml['FromUserName']}]]></ToUserName>
            <FromUserName><![CDATA[{$xml['ToUserName']}]]></FromUserName>
            <CreateTime>{$time}</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>
        ";

        //图文消息
        $news = "<xml>
            <ToUserName><![CDATA[{$xml['FromUserName']}]]></ToUserName>
            <FromUserName><![CDATA[{$xml['ToUserName']}]]></FromUserName>
            <CreateTime>{$time}</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <ArticleCount>1</ArticleCount>
            <Articles>
            <item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>
            </Articles>
            </xml>
        ";

        $filepath = C('TMP_IMG_SAVE_PATH');
        if (!is_dir($filepath)){
            mkdir($filepath, 0777, true);
        }
        $filename = $filepath.md5($url).'.jpg';
        file_put_contents($filename, file_get_contents($url));
        //GFunc::delImgOrientation($filename);

        $params['picture'] = $filename;
        $params['wechatid'] = $xml['FromUserName'];
        $res = \AppTools::webService('\Model\WeChat\WeChat', 'wechatSave', array('params'=>$params));

        if ($res['status']!=0) {
            echo sprintf($msg, '上传失败，请稍后再试');
            unlink($filename);//删除临时图片文件
            return;
        }

        $params = array();
        $params['cardid'] = $res['data']['cardid'];
        //$params['fields'] = 'cardid, FN, ORG';
        $res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
        //Log::write('88888'.json_encode($res));
        $list = $this->analyShowVcard($res['data']['wechats']);
        $info = $list[0];
        if (empty($info)) {
            echo sprintf($msg, '数据添加失败');
            unlink($filename);//删除临时图片文件
            return;
        }
        $companyArr = $info['ORG'];
        $name = $info['FN'];
        $news = sprintf($news, "$name[0]",@$companyArr[0], $info['picture'], U(MODULE_NAME.'/'.CONTROLLER_NAME.'/wDetailZp', array("cardid"=>$info['cardid']), false, true));
        //Log::write('789789'.$news);
        is_file($filename) && unlink($filename);//删除临时图片文件
        echo $news;
    }

    /**
     * 网页授权回调
     */
    public function redirect333(){
        include_once 'jssdk.php';
        $jssdk = new \JSSDK(C('Wechat')['AppID'], C('Wechat')['AppSecret']);
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token";
        $params['appid'] = C('Wechat')['AppID'];
        $params['secret'] = C('Wechat')['AppSecret'];
        $params['code'] = I('code');
        $params['grant_type'] = 'authorization_code';
        $url .= '?'.http_build_query($params).'#wechat_redirect';
        $rst = json_decode($jssdk->httpGet($url), true);
        Log::write(' 基本授权信息 '.print_r($rst,1));
        if (!empty($rst['openid'])) {
        	$callback = I('callback');
        	$wxtk = I('wxtk');
        	$this->session['openid'] = $rst['openid'];
        	$this->session['base_token'] = $rst['access_token'];
        	$this->session['base_expires'] = $rst['expires_in'];
        	$this->session['base_refresh_token'] = $rst['refresh_token'];
        	session(MODULE_NAME,$this->session);
        	if($wxtk){
        	/* 	$rstToken = $this->exec(C('GET_ORADT_WEIXIN_TOKEN_URL').U(MODULE_NAME.'/Wechat/getWxTokenForExternal'));
        		$rstToken = json_decode($rstToken,true);
        		$this->session['access_token']   = $rstToken['access_token'];
        		$this->$session['expires_in'] = $rstToken['expires_in'];
        		session(MODULE_NAME,$this->session); */
        		$flush = 0;
        		if(empty($this->session['access_token']) || empty($this->session['expires_in'])){
        			$flush = 1;
        		}
        		$rstToken = $this->getWxTokenToLocal($flush);
        		//Log::write('-----------test----------'.$url.print_r($rstToken,1));
        		Log::write('回调请求url'.print_r(array($this->session['access_token'],$this->session['expires_in']),1));
        	}
            $url = U('demo/wechat/'.$callback);
            redirect($url);
        }
    }

    /**
     * 保存用户基本信息到api中
     */
    public function saveWxUserInfo($xml=''){
    	$rstToken = $this->exec(C('GET_ORADT_WEIXIN_TOKEN_URL').U(MODULE_NAME.'/Wechat/getWxTokenForExternal'));
    	$rstToken = json_decode($rstToken,true);
    	$baseToken = $rstToken['access_token'];
    	$openid    = $xml['FromUserName'];
    	$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$baseToken}&openid={$openid}&lang=zh_CN";
     	//$url = "https://api.weixin.qq.com/cgi-bin/user/info";
    	//$param = array('access_token'=>$baseToken,'openid'=>$openid,'lang'=>'zh_CN');
    	//$info = json_decode($this->exec($url,$param,'get'),true);

    	include_once 'jssdk.php';
    	$jssdk = new \JSSDK(C('Wechat')['AppID'], C('Wechat')['AppSecret']);
    	$rst = json_decode($jssdk->httpGet($url),true);
    //	Log::write(' adddddddddaa '.$url.print_r($rst,1));
    	if($rst['subscribe'] == '1'){
    		$params = array('wechatid'=> $openid, 'info'=> json_encode($rst));
    		$res = \AppTools::webService('\Model\WeChat\WeChat', 'bindWxUserInfo', array('params'=>$params));
    		//Log::write(' aaa '.print_r($params,1));
    		Log::write(' bbb '.print_r($res,1));
    		if($res['status'] != 0){//绑定信息失败
    			Log::write('api绑定微信信息失败:'.print_r($res,1));
    		}else{
    			$this->session['userid'] = $res['data']['userid'];
    			session(MODULE_NAME,$this->session);
    		}
    	}else{
    		Log::write('获取微信用户基本信息失败:'.print_r($rst,1));
    		if($rst['errcode'] == '40001'){
    			//$rstToken = GFunc::getCustomMessageToken(C('Wechat')['AppID'], C('Wechat')['AppSecret'],7200,true);
    			//G('GET_ORADT_WEIXIN_TOKEN_URL').U(MODULE_NAME.'/Wechat/getWxTokenForExternal',array('flush'=>1));
    			$rstToken = $this->getWxTokenToLocal(1);
    		}
    	}

    }

    //显示二维码的页面
    public function qrCode(){
    	$this->_weixinAuthBase('orCode');
    	//Log::write('a 1: '.print_r($this->session,1));
    	if(empty($this->session['userid'])){
    		$params = array('wechatid'=> $this->session['openid']);
    		$res = \AppTools::webService('\Model\WeChat\WeChat', 'getBindWxUserInfo', array('params'=>$params));
    		//Log::write('a 2: '.print_r($res,1));
    		if(!empty($res['data']['wechats'])){
    			$userid = $res['data']['wechats'][0]['userid'];
    		}else{
    			$this->saveWxUserInfo(array('FromUserName'=>$this->session['openid']));
    			$userid = $this->session['userid'];
    			//Log::write('a 3: '.$userid);
    		}
    		$this->session['userid'] = $userid;
    		session(MODULE_NAME,$this->session);
    	}else{
    		$userid = $this->session['userid'];
    	}

    	//Log::write('a444: '.$userid);
    	$this->assign('userid', $userid);//用户id
    	$this->display('qrCode');
    }

    //获取生成的二维码
    public function getQrCode(){
    	include_once LIB_ROOT_PATH."3rdParty/phpqrcode/phpqrcode.php";//引入PHP QR库文件
    	//$value = U('/'.MODULE_NAME.'/'.CONTROLLER_NAME.'/wxBindScanner', array('userid'=>$this->session['userid']), true, true, true);
    	$value= 'userid='.$this->session['userid'];
    	$errorCorrectionLevel = "L";
    	$matrixPointSize = "3";
    	$margin = 1; //参数$margin表示二维码周围边框空白区域间距值
    	\QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize,$margin);
    	exit;
    }

    //微信绑定扫描仪确认页面
/*     public function wxBindScannerConfirm()
    {
    	$userid = I('userid','');
    	$this->display('wxBindScannerConfirm');
    } */

    /**
     * 获取微信token给外部使用
     */
    public function getWxTokenForExternal()
    {
    	$flag = (bool)I('get.flush',false); //是否刷新token，默认不刷新
    	$rstToken = GFunc::getCustomMessageToken(C('Wechat')['AppID'], C('Wechat')['AppSecret'],7200,$flag);
    	echo json_encode(array('access_token'=>$rstToken['access_token'], 'expires_in'=>$rstToken['expires_in']));
    }

    /**
     * 把外部获取微信token的方法先转化为本地的方法
     */
    public function getWxTokenToLocal($flush=0)
    {
    	$rstToken = $this->exec(C('GET_ORADT_WEIXIN_TOKEN_URL').U(MODULE_NAME.'/Wechat/getWxTokenForExternal'),array('flush'=>$flush));
    	$rstToken = json_decode($rstToken,true);
    	$this->session['access_token']   = $rstToken['access_token'];
    	$this->session['expires_in']    = $rstToken['expires_in'];
    	session(MODULE_NAME,$this->session);
    	Log::write(' 内部通过外网获取token : '.print_r(array($this->session['access_token'],$this->session['expires_in']),1));
    	return $rstToken;
    }

    /**
     * 网页保存图片
     */
    public function saveImage(){
        $data = base64_decode(str_replace('data:image/jgp;base64,', '', I('data')));
        $filepath = C('TMP_IMG_SAVE_PATH');
        if (!is_dir($filepath)){
            mkdir($filepath, 0777, true);
        }
        $filename = $filepath.md5($data).'.jpg';
        file_put_contents($filename, $data);
        Log::write('-------上传图片  vcard start  '.$this->session['openid']);
        $params['picture'] = $filename;
        $params['wechatid'] = $this->session['openid'];
        $res = \AppTools::webService('\Model\WeChat\WeChat', 'wechatSave', array('params'=>$params));
        Log::write('-------上传图片  vcard end  '.$this->session['openid'].' cardid='.@$res['data']['cardid']);
        unlink($filename);
        $openid = I('openid');
        $this->sendMsg($openid,$res);
        echo json_encode($res);
    }
	//上传名片后服务器端推送消息到客户
    public function sendMsg($openid,$res){
    	Log::write('-------上传图片  vcard 发送消息之前  '.$openid.' cardid='.@$res['data']['cardid']);
    	//Log::write('-------上传图片 $$$$$ token '.print_r($this->session,1));
    	$token = $this->session['access_token'];
    	$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$token;
    	$msg = array(
    			'touser'=>$openid,
    			'msgtype'=>'text',
    			'text'=>array('content'=>'hellow')
    		);
    	$json = '';
    	if ($res['status']!=0) {
		    $msg['text']['content'] = '上传失败，请稍后再试';
		    $json = \ConvertFormat::json_encode($msg);
    	}else{
    		$params = array();
    		$params['cardid'] = $res['data']['cardid'];
    		//$params['fields'] = 'cardid, FN, ORG';
    		$res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
    		$wechatsList = $this->analyShowVcard($res['data']['wechats']);
    		$info = $wechatsList[0];
    		if (empty($info)) {
    			//$msg = sprintf($msg, $openid,'上传失败，请稍后重试.');
    			$msg['text']['content'] = '上传失败，请稍后重试.';
    			$json = \ConvertFormat::json_encode($msg);
    		}else{
    			$picurl =  U(MODULE_NAME.'/'.CONTROLLER_NAME.'/wDetailZp', array("cardid"=>$info['cardid']), false, true);
    			//$msg = sprintf($news, $openid,"$name[0]",@$companyArr[0],$picurl,$info['picture']);
    			$news = array(
    					'touser'=>$openid,
    					'msgtype' => 'news',
    					'news' => array(
    							'articles'=>array(
    									array(
    									'title' => $info['FN'][0],
    									'description' => @$info['ORG'][0],
    									'url' => $picurl,
    									'picurl' => $info['picture'])
    					))
    				);
    			$json = \ConvertFormat::json_encode($news);
    		}
    	}

    	$rst = $this->exec($url,$json,'POST');
    	$rst = json_decode($rst,true);
    	if($rst['errcode'] == '40001'){
    		Log::write($openid.'-------上传图片 $$$$$ end refresh token');
    		//$rstToken = GFunc::getCustomMessageToken(C('Wechat')['AppID'], C('Wechat')['AppSecret'],7200,true);
    		//$rstToken = G('GET_ORADT_WEIXIN_TOKEN_URL').U(MODULE_NAME.'/Wechat/getWxTokenForExternal',array('flush'=>1));
    		//$rstToken = json_decode($rstToken,true);
    		$rstToken = $this->getWxTokenToLocal(1);
    		Log::write('-----------上传图片 后   生成新的token----------'.print_r($rstToken,1));
    	}
    	Log::write('-------上传图片  vcard 发送消息之后 '.$openid.' cardid='.@$info['cardid']);
    }

    /**
     * 名片详细页面
     */
/*     public function wdetail(){
        include_once 'jssdk.php';
        if (empty($this->session['openid'])){
        	$urlCall = U(MODULE_NAME.'/Wechat/redirect333',array('callback'=>'wdetail'),'',true);
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize";
            $params['appid'] = C('Wechat')['AppID'];
            $params['redirect_uri'] = $urlCall;//'http://dev.orayun.com/demo/wechat/redirect333.html';
            $params['response_type'] = 'code';
            $params['scope'] = 'snsapi_base';
            $params['state'] = '123';
            $url .= '?'.http_build_query($params).'#wechat_redirect';
            //echo $url;die;
            redirect($url);
            return;
        }
        $params['cardid'] = I('cardid');
        $res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
        $info = $res['data']['wechats'][0];
        $this->assign('info', $info);

        //网页调用照片

        $jssdk = new \JSSDK(C('Wechat')['AppID'], C('Wechat')['AppSecret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage', $signPackage);
        $this->assign('openid', $this->session['openid']);
        $this->display('wDetail');
    } */

    /**
     * 名片列表页
     */
/*     public function wlist(){
        include_once 'jssdk.php';
        if (empty($this->session['openid'])){
            $urlCall = U(MODULE_NAME.'/Wechat/redirect333',array('callback'=>'wlist'),'',true);
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize";
            $params['appid'] = C('Wechat')['AppID'];
            $params['redirect_uri'] = $urlCall;//'http://dev.orayun.com/demo/wechat/redirect333.html';
            $params['response_type'] = 'code';
            $params['scope'] = 'snsapi_base';
            $params['state'] = '123';
            $url .= '?'.http_build_query($params).'#wechat_redirect';
            //echo $url;die;
            redirect($url);
            return;
        }
    	//Log::write('列表请求参数'.json_encode($_REQUEST));
    	$keyword = urldecode(I('keyword',''));//搜索关键字
    	$openid = I('openid');
    	$params['kwds'] = $keyword;
    	$openid && $params['wechatid'] = $openid;
        $params['sort'] = 'createdtime desc';
       	//Log::write('列表传递参数'.json_encode($params));
        $res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
        $this->assign('list', $res['data']['wechats']);

        //网页调用照片
        $jssdk = new \JSSDK(C('Wechat')['AppID'], C('Wechat')['AppSecret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage', $signPackage);

        $this->display('wList');
    } */

/*     public function wuzhijie($xml){
        $wechatparams['FromUserName'] = $xml['FromUserName'];
        $wechatparams['ToUserName'] = $xml['ToUserName'];
        $wechatparams['createtime'] = time();
        $wechatparams['imgurl'] = 'https://oradtdev.s3.cn-north-1.amazonaws.com.cn/resource/2017/0328/PQ0hjedfym20170328132221.jpg';

        //根据语音识别结果，查询名片
        $params = array();
        $params['kwds'] = $this->cutStrs($xml['Recognition']);
        $cardlist = $this->getVcardlist($params);
        if(!empty($cardlist)){
            $wechatparams['imgurl'] = $cardlist[0]['picture'];
            $wechatparams['title'] = $params['kwds'];
            $wechatparams['linkurl'] = 'http://dev.orayun.com/demo/wechat/wechatcardlist/kwd/'.urlencode($params['kwds']);
            $msg = $this->returnNewsMsg($wechatparams);
        }else{
            $wechatparams['content'] = '未查询到你说的内容:'.$params['kwds'];
            $msg = $this->returnMsg($wechatparams);
        }

        echo $msg;

    } */

/*     public function wuzhou($xml){
        Log::write('------------------------------');
        $token = C('Wechat.Token');
        $wechat =  new \WechatListener($token);
        $request = $wechat->getRequest();
        Log::write(var_export($request,true));
        $testHandler = new \TestHandler();
        $wechat->setHandler(array(
                'image'   => array($testHandler, 'handleImage'),
        )
        )
        ->listen();
        $data = $wechat->getData();
        Log::write(var_export($data,true));
        Log::write('-------------------------------');
    } */

    public function zhangpeng($xml){
    	$time = time();
    	$msg = "<xml>
    				<ToUserName><![CDATA[{$xml['FromUserName']}]]></ToUserName>
    				<FromUserName><![CDATA[{$xml['ToUserName']}]]></FromUserName>
    				<CreateTime>{$time}</CreateTime>
    				<MsgType><![CDATA[text]]></MsgType>
    				<Content><![CDATA[%s]]></Content>
    		   </xml>";
    	$keyword = '';
    	if($xml['MsgType'] == 'voice'){
    		$keyword = rtrim($xml['Recognition'],'。！？!?.');
    	}else if($xml['MsgType'] == 'text'){
    		$keyword = $xml['Content'] ;
    	}
    	$keyword = trim($keyword);
    	if(empty($keyword)){
    		echo sprintf($msg, '语音识别失败 ，搜索关键字为空');exit;
    	}
    	//Log::write('微信xml---'.json_encode($xml).'  #########'.$keyword);
    	$max = 5; //最多显示图文数量
    	$params = array();
    	$params['kwds'] = trim($keyword);
    	$params['wechatid'] = $xml['FromUserName'];
    	$params['rows'] = $max;
/*     	$res = \AppTools::webService('\Model\Common\Common','callApi',
    			array(
    					'url'    => WEB_SERVICE_ROOT_URL.'/common/wechat/getwechat',
    					'params' => $params,
    					'crud'  => 'R'
    			)
    	); */
    	$res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
    	if ($res['status']!=0) {
    		echo sprintf($msg, '无法识别，请稍后再试');
    	}else{
    		if($res['data']['numfound']==0){
    			echo sprintf($msg, '你搜索的关键字为：'.$keyword.'  未查询到数据');
    		}else{
    			$list = $this->analyShowVcard($res['data']['wechats']);
    			$max = count($list)>$max?$max:count($list);
    			$msg = " <xml>
    			<ToUserName><![CDATA[{$xml['FromUserName']}]]></ToUserName>
    			<FromUserName><![CDATA[{$xml['ToUserName']}]]></FromUserName>
    					<CreateTime>{$time}</CreateTime>
    					<MsgType><![CDATA[news]]></MsgType>
    					<Content><![CDATA[]]></Content>
    					<ArticleCount>{$max}</ArticleCount>
    					<Articles>
							%s
    					</Articles>
    					<FuncFlag>0</FuncFlag>
    					</xml>";
    			$content = '';
    			foreach ($list as $index=>$val){
    				if($index>$max){
    					break;
    				}
    				$url = U(MODULE_NAME.'/'.CONTROLLER_NAME.'/wDetailZp', array('cardid'=>$val['cardid']),true,true);
    				$picture = $index==0?$val['picture']:''; //-{$val['TITLE']
    				$content .="<item>
    						<Title><![CDATA[{$val['FN'][0]}]]></Title>
    						<Description><![CDATA[]]>
    						</Description>
    					    <PicUrl><![CDATA[{$picture}]]></PicUrl>
    					<Url><![CDATA[{$url}]]></Url>
    					</item>";
    			}
    			echo  sprintf($msg,$content);
    		}
    	}
    }

    //公司跳转
    public function companyRedirect(){
    	$name = I('name','中国大唐集团公司'); //公司名称
    	$params = array('name'=>$name);

    	$api_url = 'http://api.tianyancha.com/services/v3/open/w/detail.json';
    	$params  = array('name'=>urldecode($name));
    	Log::write('天眼查--请求参数-- '.json_encode($params));
    	$result  = $this->exec($api_url,$params);
    	$res = json_decode(str_replace("\t", "    ", $result), true);
    	Log::write('天眼查结果  '.json_encode($res));
    	//$res     = json_decode($result,TRUE);//json 解析
    	//echo __FILE__.' LINE:'.__LINE__."\r\n".'<pre>',print_r($res,1);exit;
    	$error   = $res['error_code'];
    	if (0 != $error) {
    		$content = array();
    	}
    	$content = isset($res['result'])?$res['result']:array();
    	$companyId = 0;
    	if($content){
    		$companyId = $content['baseInfo']['id'];
    		$tianyanurl = 'http://www.tianyancha.com/company/'.$companyId;
    		Log::write('tiany######cha= '.$tianyanurl);
    		redirect($tianyanurl);
    	}else{
    		//$tianyanurl = U(MODULE_NAME.'/Wechat/getCompanyInfo',array('test'=>1),'',true);
    		$tianyanurl = 'http://www.gsxt.gov.cn/index.html';
    		redirect($tianyanurl);
    	}
    }

    /**
     * 发起一个get或post请求
     * @param $url 请求的url
     * @param int $method 请求方式
     * @param array $params 请求参数
     * @param array $extra_conf curl配置, 高级需求可以用, 如
     * $extra_conf = array(
     *    CURLOPT_HEADER => true,
     *    CURLOPT_RETURNTRANSFER = false
     * )
     * @return bool|mixed 成功返回数据，失败返回false
     * @throws Exception
     */
    public static function exec($url,  $params = array(), $method = self::METHOD_GET, $extra_conf = array())
    {
    	//Log::write('-------exec $$$$$$$$$$$---- '.print_r(func_get_args(),1));
    	$params = is_array($params)? http_build_query($params): $params;
    	//如果是get请求，直接将参数附在url后面
    	if(strtoupper($method) == strtoupper(self::METHOD_GET))
    	{
    		$url .= (strpos($url, '?') === false ? '?':'&') . $params;
    	}

    	//默认配置
    	$curl_conf = array(
    			CURLOPT_URL => $url,  //请求url
    			CURLOPT_HEADER => false,  //不输出头信息
    			CURLOPT_RETURNTRANSFER => true, //不输出返回数据
    			CURLOPT_CONNECTTIMEOUT => 3 // 连接超时时间
    	);

    	//配置post请求额外需要的配置项
    	if(strtoupper($method) == strtoupper(self::METHOD_POST))
    	{
    		//使用post方式
    		$curl_conf[CURLOPT_POST] = true;
    		//post参数
    		$curl_conf[CURLOPT_POSTFIELDS] = $params;
    	}

    	//添加额外的配置
    	foreach($extra_conf as $k => $v)
    	{
    		$curl_conf[$k] = $v;
    	}

    	$data = false;
    	try
    	{
    		//初始化一个curl句柄
    		$curl_handle = curl_init();
    		//设置curl的配置项
    		curl_setopt_array($curl_handle, $curl_conf);
    		//发起请求
    		$data = curl_exec($curl_handle);
    		if($data === false)
    		{
    			throw new \Exception('CURL ERROR: ' . curl_error($curl_handle));
    		}
    	}
    	catch(\Exception $e)
    	{
    		echo $e->getMessage();
    	}
    	curl_close($curl_handle);

    	return $data;
    }

    //企业详情页面
    public function getCompanyInfo()
    {
    	include_once 'jssdk.php';
    	if (empty($this->session['openid'])){
    		$urlCall = U(MODULE_NAME.'/Wechat/redirect333',array('callback'=>'getCompanyInfo'),'',true);
    		$url = "https://open.weixin.qq.com/connect/oauth2/authorize";
    		$params['appid'] = C('Wechat')['AppID'];
    		$params['redirect_uri'] = $urlCall;//'http://dev.orayun.com/demo/wechat/redirect333.html';
    		$params['response_type'] = 'code';
    		$params['scope'] = 'snsapi_base';
    		$params['state'] = '123';
    		$url .= '?'.http_build_query($params).'#wechat_redirect';
    		redirect($url);
    		return;
    	}
    	if(I('test')){
    		$this->assign('data','');
    		$this->assign('openid', $this->session['openid']);
    		$this->assign('cardid', '');
    		$this->assign('type','');
    	}else{
    	//Log::write(ACTION_NAME.'-列表请求参数'.json_encode($_REQUEST));
    	$cardid = I('cardid',''); //名片id
    	$name = urldecode(I('name','中国大唐集团公司')); //公司名称
    	$params = array('name'=>$name);
    	$res = \AppTools::webService('\Model\WeChat\WeChat', 'getCompanyInfo', array('params'=>$params));
    	$data = empty($res['data']['content'])?'':json_decode(str_replace("\t", "    ", $res['data']['content']), true);
    	//Log::write('公司详情信息 ####： '.json_encode($data));
    	$this->assign('data',$data);
    	$this->assign('openid', $this->session['openid']);
    	$this->assign('cardid', $cardid);
    	$this->assign('type',$this->getAppName());
    	/* if($_SESSION['openid'] == 'ofIP5vnuTl1UTMpiIu3pO4_mRQ90' || I('test') == '1'){
    		$this->relation(1);
    	} */
    	}
    	$this->display('companyInfo');
    }

    //人脉关系图
    public function relationChat($test=0){
    	$colorNodeCompany = '#46A2D2'; //公司节点颜色
    	$colorNodeHuman   = '#F67A52'; //人员节点颜色
    	$color2 = '#89C6DB'; //浅蓝色
    	$colorLink = array(
    			'参股'		=>'#F25A29', //参股  红色
    			'董事'		=> $color2, //
    			'董事长'		=> $color2, //
    			'执行董事' 		=> $color2, //
    			'执行（常务）董事' => $color2, //
    			'经理' 		=> $color2, //
    			'副董事长' 		=> $color2, //
    			'监事' 		=> $color2, //
    			'执行董事兼总..' => $color2, //
    			'法人' 		=> '#CCE198', // 浅蓝色
    	);
    	$colorSet = array('node'=>array(''));
    	$nodesJson = '[{"id":"2373842731","properties":{"name":"西安橙鑫数据\n科技有限公司","ntype":"s"},"labels":["Company"]},{"id":"1031370370","properties":{"name":"上海嘉禾影视娱乐管理咨询有限公司","ntype":"f"},"labels":["Company"]},{"id":"1776754159","properties":{"name":"伍克燕","ntype":"s"},"labels":["Human"]},{"id":"24478376","properties":{"name":"北京橙鑫数据科技有限公司","ntype":"s"},"labels":["Company"]},{"id":"2961003290","properties":{"name":"嘉禾国产电影发行（深圳）有限公司","ntype":"f"},"labels":["Company"]},{"id":"2314527770","properties":{"name":"北京橙源科技有限公司","ntype":"f"},"labels":["Company"]},{"id":"31125997","properties":{"name":"北京橙天嘉禾东方玫瑰影城管理有限公司","ntype":"f"},"labels":["Company"]},{"id":"571449905","properties":{"name":"景德镇橙天嘉禾金鼎影城有限责任公司","ntype":"f"},"labels":["Company"]},{"id":"2167607366","properties":{"name":"解秋生","ntype":"s"},"labels":["Human"]},{"id":"21452719","properties":{"name":"橙天嘉禾影城（中国）有限公司","ntype":"f"},"labels":["Company"]},{"id":"2345064858","properties":{"name":"橙鑫数据科技（香港）有限公司","ntype":"s"},"labels":["Company"]},{"id":"1339820299","properties":{"name":"常州幸福蓝海橙天嘉禾影城有限公司","ntype":"f"},"labels":["Company"]}]';
    	$links = '[{"startNode":"2167607366","id":"196282012","type":"SERVE","endNode":"2373842731","properties":{"labels":["执行董事兼总.."]}},{"startNode":"2167607366","id":"12254399","type":"OWN","endNode":"24478376","properties":{"labels":["法人"]}},{"startNode":"2167607366","id":"215476242","type":"INVEST_H","endNode":"2314527770","properties":{"labels":["参股"]}},{"startNode":"2167607366","id":"142023117","type":"SERVE","endNode":"2314527770","properties":{"labels":["执行董事"]}},{"startNode":"2167607366","id":"181244153","type":"SERVE","endNode":"1339820299","properties":{"labels":["副董事长"]}},{"startNode":"2345064858","id":"245743361","type":"INVEST_C","endNode":"24478376","properties":{"labels":["参股"]}},{"startNode":"21452719","id":"244498322","type":"INVEST_C","endNode":"1339820299","properties":{"labels":["参股"]}},{"startNode":"2167607366","id":"175995466","type":"SERVE","endNode":"571449905","properties":{"labels":["董事长"]}},{"startNode":"21452719","id":"229098414","type":"INVEST_C","endNode":"31125997","properties":{"labels":["参股"]}},{"startNode":"1776754159","id":"215476243","type":"INVEST_H","endNode":"2314527770","properties":{"labels":["参股"]}},{"startNode":"2167607366","id":"149890756","type":"SERVE","endNode":"1031370370","properties":{"labels":["董事长"]}},{"startNode":"1776754159","id":"182718172","type":"SERVE","endNode":"24478376","properties":{"labels":["监事"]}},{"startNode":"2167607366","id":"182718170","type":"SERVE","endNode":"24478376","properties":{"labels":["执行董事"]}},{"startNode":"1776754159","id":"162171790","type":"SERVE","endNode":"31125997","properties":{"labels":["董事长"]}},{"startNode":"2167607366","id":"159976507","type":"SERVE","endNode":"21452719","properties":{"labels":["董事"]}},{"startNode":"2167607366","id":"97603439","type":"OWN","endNode":"1031370370","properties":{"labels":["法人"]}},{"startNode":"1776754159","id":"15162914","type":"OWN","endNode":"31125997","properties":{"labels":["法人"]}},{"startNode":"1776754159","id":"159976510","type":"SERVE","endNode":"21452719","properties":{"labels":["董事"]}},{"startNode":"2167607366","id":"16726836","type":"OWN","endNode":"571449905","properties":{"labels":["法人"]}},{"startNode":"2167607366","id":"134913462","type":"SERVE","endNode":"2961003290","properties":{"labels":["董事"]}},{"startNode":"1776754159","id":"4007631","type":"OWN","endNode":"2961003290","properties":{"labels":["法人"]}},{"startNode":"2167607366","id":"142023118","type":"SERVE","endNode":"2314527770","properties":{"labels":["经理"]}},{"startNode":"1776754159","id":"134913463","type":"SERVE","endNode":"2961003290","properties":{"labels":["执行（常务）董事"]}},{"startNode":"2167607366","id":"97652537","type":"OWN","endNode":"2373842731","properties":{"labels":["法人"]}},{"startNode":"1776754159","id":"196282013","type":"SERVE","endNode":"2373842731","properties":{"labels":["监事"]}},{"startNode":"1776754159","id":"149890758","type":"SERVE","endNode":"1031370370","properties":{"labels":["董事"]}},{"startNode":"2167607366","id":"182718171","type":"SERVE","endNode":"24478376","properties":{"labels":["经理"]}},{"startNode":"24478376","id":"257502776","type":"INVEST_C","endNode":"2373842731","properties":{"labels":["参股"]}},{"startNode":"1776754159","id":"175995468","type":"SERVE","endNode":"571449905","properties":{"labels":["董事"]}},{"startNode":"1776754159","id":"181244148","type":"SERVE","endNode":"1339820299","properties":{"labels":["董事"]}},{"startNode":"2167607366","id":"51234553","type":"OWN","endNode":"2314527770","properties":{"labels":["法人"]}},{"startNode":"2167607366","id":"162171792","type":"SERVE","endNode":"31125997","properties":{"labels":["董事"]}}]';
    	$nodesArr = json_decode($nodesJson,true);
    	$linksArr = json_decode($links,true);
    	$nodes = $links = array(
    			//array('data'=>array('id'=>'2373842731','name'=>'西安橙鑫数据科技有限公司','label'=>'Company')),
    			//array('data'=>array('id'=>'1031370370','name'=>'上海嘉禾影视娱乐管理咨询有限公司','label'=>'Human')),
    	);
    	foreach ($nodesArr as $val){
    		$nodes[] = array('data'=>array('id'=>$val['id'], 'name'=>$val['properties']['name'],'label'=>$val['labels'][0]));
    	}
    	foreach ($linksArr as $val){
    		$color = isset($colorLink[$val['properties']['labels'][0]])?$colorLink[$val['properties']['labels'][0]]:$color2;
    		$links[] = array(
    				'data' => array('source'=>$val['startNode'], 'target'=>$val['endNode'],'relationship'=>$val['properties']['labels'][0]
    						,'color'=>$color),
    				'classes' => md5($colorLink[$val['properties']['labels'][0]])
    		);
    	}
    	//echo __FILE__.' LINE:'.__LINE__."\r\n".'<pre>',print_r($nodes,1);
    	//echo __FILE__.' LINE:'.__LINE__."\r\n".'<pre>',print_r($links,1);exit;
    	$nodes = json_encode($nodes);
    	$links = json_encode($links);
    	$this->assign('nodes',$nodes);
    	$this->assign('links',$links);
    	$this->assign('type','zp');

    	!$test && $this->display('relationChat');
    }

    /**
     * 处理人脉关系图名字的长度问题
     */
    public function dealWithStrLen($str)
    {
    	//$str = '北京橙鑫数据科技有限公里数a';
    	//$place = (strlen($str) + mb_strlen($str,'UTF8'))/2; //计算占位符，一个英文是一个占位符，一个中文是两个占位符合
    	//echo __FILE__.' LINE:'.__LINE__."\r\n".'<pre>',var_dump($place);
    	$len = mb_strlen($str,'UTF-8');
    }

    //个人信息页面
    public function personInfo()
    {
    	$this->_weixinAuthBase('personInfo');
    	$cardid = I('cardid',''); //名片id
    	$name = urldecode(I('name','')); //公司名称

    	$params['cardid'] = $cardid;
    	$res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
    	$wechatsList = $this->analyShowVcard($res['data']['wechats']);
    	$info = $res['data']['wechats'][0];
    	//!empty($info['FN']) && $info['FN'] = $info['FN'];

    	include_once 'jssdk.php';
    	$jssdk = new \JSSDK(C('Wechat')['AppID'], C('Wechat')['AppSecret']);
    	$url = 'http://192.168.30.251:38080/person/details';
    	$timeStart = microtime(true);
    	Log::write('个人页面--请求参数-- '.json_encode($info));
    	$rst = $jssdk->_curl($url, 'POST',array(),json_encode($info));
    	$timeEnd = microtime(true);
    	Log::write('个人页面--请求参数--########### dmtime test='.($timeEnd-$timeStart).I('name','').'  %%%%'.$name.' $$'.urlencode($name));
    	if(empty($rst)){
    		//$nameArr = explode(',', $info['FN']);
    		$kwd = urlencode($info['FN'][0]);
    		$url = "https://www.baidu.com/s?wd={$kwd}";
    		redirect($url);exit;
    	}
    	Log::write('个人页面返回结果  '.$rst);
    	$rst = json_decode(str_replace("\t", "    ", $rst), true);

    	if($rst){
    		foreach ($rst as $key=>$val){
    			if($key == 'dynamic'){
    				if($val){
    					foreach ($val as $k=>$v){
    						$rst[$key][$k] = empty($val) ? '' : json_decode(str_replace("\t", "    ", $v), true);
    					}
    				}
    			}else if ('img' == $key) {
                    $rst[$key]  = empty($val)?'':$val;
                }else{
    				$rst[$key] = empty($val) ? '' : json_decode(str_replace("\t", "    ", $val[0]), true);
    			}

    		}
    	}
    	//echo __FILE__.' LINE:'.__LINE__."\r\n".'<pre>',print_r($rst,1);exit;
    	Log::write('个人页面返回结果'.json_encode($rst));
    	Log::write('个人页面返回结果'.print_r(json_encode($rst),1));
    	$this->assign('data',$rst);
    	$this->assign('openid', $this->session['openid']);
    	$this->assign('cardid', $cardid);
    	$this->assign('name', $name);
    	$this->display('personInfo');
    }

    /**
     * 获取要下载的app路径
     * @param string $type 要下载的类型：android 或 iso
     */
    protected function getAppName()
    {
    		$type='';
    		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    		$iphone = (strpos($agent, 'iphone')) ? true : false;
    		$ipad = (strpos($agent, 'ipad')) ? true : false;
    		$android = (strpos($agent, 'android')) ? true : false;
    		if($iphone || $ipad){//苹果设备
    			$type = 'ios';
    		}else if($android){
    			$type = 'android';
    		}else{
    			//var_dump('check telephone type fail');exit;
    			$type = 'android';
    		}
    		return $type;
    }

    /**
     * 名片列表页
     */
    public function wListZp(){
		$this->_weixinAuthBase('wListZp'); //微信基本授权操作
		$newSearch = I('newSearch',''); //1表示新搜索
		$params = array();
		$rows = 10;
    	$keyword = urldecode(I('keyword',''));//搜索关键字
    	$page = I('get.page',1);
    	$start = ($page-1)*$rows;
    	$openid = I('openid','')?I('openid'):$this->session['openid'];
    	$params['kwds'] = $keyword;
    	$openid && $params['wechatid'] = $openid;
    	$params['sort'] = 'createdtime desc';
    	$params['rows'] = $rows;
    	$params['start'] = $start;
    	$params['new'] = $newSearch;
    	$res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
    	$wechatsList = $this->analyShowVcard($res['data']['wechats']);
    	if($res['status'] == 0 && $res['data']['numfound'] !=0){
    		$this->assign('list',$wechatsList);
    	}else{
    		$this->assign('list',array());
    	}
    	$totalPage = ceil($res['data']['numfound']/$rows);
    	$this->assign('totalPage', $totalPage); //总记录数
    	$this->assign('currPage', $page); //当前页码数
    	$this->assign('openid', $this->session['openid']);
    	if(IS_AJAX){
            $res = $this->fetch('listitem');
    		$this->ajaxReturn($res);
    	}else{
            isset($res['data']['numfound']) ? '':$res['data']['numfound']=0;
    		$this->assign('datanumber', $res['data']['numfound']);
    		$this->assign('rows', $rows);

    		//网页调用照片
    		//include_once 'jssdk.php';
    		$jssdk = new \JSSDK(C('Wechat')['AppID'], C('Wechat')['AppSecret']);
    		$signPackage = $jssdk->GetSignPackage();
    		$this->assign('signPackage', $signPackage);
    		$this->assign('keyword', $keyword);
    		$this->assign('newSearch', $newSearch);
    		$this->display('wListZp');
    	}
    }

    /**
     * 微信基本授权操作
     */
    private function _weixinAuthBase($callback='')
    {
    	$t = I('t');
    	include_once 'jssdk.php';
    	if ((empty($this->session['openid']) ) && empty($t)){
    		$params = array();
    		$urlCall = U(MODULE_NAME.'/Wechat/redirect333',array('callback'=>$callback),'',true);
    		$url = "https://open.weixin.qq.com/connect/oauth2/authorize";
    		$params['appid'] = C('Wechat')['AppID'];
    		$params['redirect_uri'] = $urlCall;//'http://dev.orayun.com/demo/wechat/redirect333.html';
    		$params['response_type'] = 'code';
    		$params['scope'] = 'snsapi_base';
    		$params['state'] = '123';
    		$url .= '?'.http_build_query($params).'#wechat_redirect';
    		redirect($url);
    		return;
    	}
    }

    /**
     * 名片详细页面
     */
    public function wDetailZp(){
		$this->_weixinAuthBase('wDetailZp'); //微信基本授权操作
		$params = array();
    	$cardid = I('cardid');
    	$params['cardid'] = $cardid;
    	$res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
    	$wechatsList = $this->analyShowVcard($res['data']['wechats']);
    	$info = $res['data']['numfound']==0?array():$wechatsList[0];
    	//echo __FILE__.' LINE:'.__LINE__."\r\n".'<pre>',print_r($info,1);exit;
    	$this->assign('info', $info);
    	Log::write('File:'.__FILE__.' LINE:'.__LINE__." 修############## c   \r\n".'<pre>'.var_export($info,true));
    	Log::write('File:'.__FILE__.' LINE:'.__LINE__." 修############## a   \r\n".'<pre>'.var_export($info['URL'],true));
    	//网页调用照片
    	include_once 'jssdk.php';
    	$jssdk = new \JSSDK(C('Wechat')['AppID'], C('Wechat')['AppSecret']);
    	$signPackage = $jssdk->GetSignPackage();
    	$this->assign('signPackage', $signPackage);
    	$this->assign('openid', $this->session['openid']);
    	$this->assign('cardid', $cardid);
    	$this->assign('sysType', $this->getAppName());
    	Log::write(' wDetailZp 来源 :'.$_SERVER['HTTP_REFERER']);
    	$this->assign('urlSource', strpos($_SERVER['HTTP_REFERER'], 'wListZp')===false?'':$_SERVER['HTTP_REFERER']);
    	$this->assign('kwd',urldecode(I('kwd')));
    	$this->display('wDetailZp');
    }

    //获取经纬度
    public function getLocation(){//http://dev.orayun.com/
    	$address = urldecode(I('address'));
    	$url = 'http://api.map.baidu.com/geocoder/v2/';
    	$param = array('address'=>$address,'ak'=>'GNMfmaHWOLrt5HMqz4ofS1t1','callback'=>'getLocInfo','output'=>'json');
    	$rst = $this->exec($url,$param);

    	Log::write('--------------Location param-----------------'.print_r($param,1));
    	Log::write('--------------Location-----------------'.$rst);

    	$rst = str_replace('getLocInfo&&getLocInfo(', '', $rst);
    	$rst = rtrim($rst,')');

    	Log::write('--------------Location---------last--------'.$rst);
    	$rst = json_decode($rst,true);
        //百度地图坐标，转换为腾讯地图坐标
        $maplocation = $this->Convert_BD09_To_GCJ02($rst['result']['location']['lat'],$rst['result']['location']['lng']);
        $rst['result']['location']['lat'] = $maplocation['lat'];
        $rst['result']['location']['lng'] = $maplocation['lng'];
    	echo json_encode($rst);
    }

    //删除名片
    public function delCard(){
    	$cardid = urldecode(I('cardid'));
    	$res = \AppTools::webService('\Model\WeChat\WeChat', 'wxDelCard', array('params'=>array('cardid'=>$cardid)));
    	$this->ajaxReturn($res);
    }

    //显示上传名片页面
    public function showUpload()
    {
    	//$this->_weixinAuthBase('showUpload');
    	Log::write('-- showUpload 初始化时 $_SESSION 参数--'.print_r($this->session,1));
    	$t = I('t');
    	include_once 'jssdk.php';
    	if ((empty($this->session['access_token']) || empty($this->session['openid']) || $this->session['expires_in']-time()<=0 || $this->session['expires_in']-time()>=7200) && empty($t)){
    		Log::write('-- showUpload session 中有数据为空 或无效  $_SESSION '.$this->session);
    		$wxtk = 0;
    		if(empty($this->session['access_token']) || $this->session['expires_in']-time()<=0 || $this->session['expires_in']-time()>=7200){
    			$wxtk = 1;
    		}
    		$params = array();
    		$urlCall = U(MODULE_NAME.'/Wechat/redirect333',array('callback'=>'showUpload','wxtk'=>$wxtk),'',true);
    		$url = "https://open.weixin.qq.com/connect/oauth2/authorize";
    		$params['appid'] = C('Wechat')['AppID'];
    		$params['redirect_uri'] = $urlCall; //'http://dev.orayun.com/demo/wechat/redirect333.html';
    		$params['response_type'] = 'code';
    		$params['scope'] = 'snsapi_base';
    		$params['state'] = '123';
    		$url .= '?'.http_build_query($params).'#wechat_redirect';
    		redirect($url);
    		return;
    	}
    	//$this->exec(C('GET_ORADT_WEIXIN_TOKEN_URL').U(MODULE_NAME.'/Wechat/getWxTokenForExternal'));
    	$rstToken = $this->getWxTokenToLocal();

    	//网页调用照片
    	$jssdk = new \JSSDK(C('Wechat')['AppID'], C('Wechat')['AppSecret']);
    	$signPackage = $jssdk->GetSignPackage();
    	$this->assign('signPackage', $signPackage);
    	$this->assign('openid',$this->session['openid']);
    	$this->assign('type',$this->getAppName());

    	$this->display('showUpload');
    }

    public function wechatcardlist(){
        include_once 'jssdk.php';
        $jssdk = new \JSSDK(C('Wechat')['AppID'], C('Wechat')['AppSecret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage', $signPackage);

        $params['kwds'] = urldecode(I('kwd'));
        $params['sort'] = 'createdtime desc';
        $res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
        $this->assign('list', $res['data']['wechats']);
        $this->display('wechatcardlist');
    }
    /**
     * 名片详细页面
     */
    public function webChatCardDetail(){
        $params['cardid'] = I('cardid');
        $res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
        $info = $res['data']['wechats'][0];
        $this->assign('info', $info);
        $this->display('webchatdcardetail');
    }

    public function getVoiceWords(){
        $xml = (array) simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);
        $wechatparams['FromUserName'] = $xml['FromUserName'];
        $wechatparams['ToUserName'] = $xml['ToUserName'];
        $wechatparams['createtime'] = time();
        $wechatparams['imgurl'] = 'https://oradtdev.s3.cn-north-1.amazonaws.com.cn/resource/2017/0328/PQ0hjedfym20170328132221.jpg';

        //根据语音识别结果，查询名片
        $params = array();
        $params['kwds'] = $this->cutStrs($xml['Recognition']);
        //$cardlist = $this->getVcardlist($params);
        Log::write('--------------voice save-----------------');
        Log::write(var_export($xml,true));
        Log::write('--------------voice save-----------------');
    }

    public function getVcardlist($params){
        $params['sort'] = 'createdtime desc';
        $result = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
        if ($result['status'] == 0 && $result['data']['numfound'] !== 0 ){
            $result = $result['data']['wechats'];
        }else{
            $result = array();
        }
        return $result;
    }
    public function returnNewsMsg($wechatparams){
        $msg = "<xml>
                <ToUserName><![CDATA[{$wechatparams['FromUserName']}]]></ToUserName>
                <FromUserName><![CDATA[{$wechatparams['ToUserName']}]]></FromUserName>
                <CreateTime>{$wechatparams['createtime']}</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>1</ArticleCount>
                <Articles>
                    <item>
                    <Title><![CDATA[{$wechatparams['title']}]]></Title>
                    <Description><![CDATA[{$wechatparams['title']}]]></Description>
                    <PicUrl><![CDATA[{$wechatparams['imgurl']}]]></PicUrl>
                    <Url><![CDATA[{$wechatparams['linkurl']}]]></Url>
                    </item>
                </Articles>
                </xml> ";
        return $msg;
    }
    public function returnMsg($wechatparams){
        $msg = "<xml>
                <ToUserName><![CDATA[{$wechatparams['FromUserName']}]]></ToUserName>
                <FromUserName><![CDATA[{$wechatparams['ToUserName']}]]></FromUserName>
                <CreateTime>{$wechatparams['createtime']}</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[{$wechatparams['content']}]]></Content>
                </xml>";
        return $msg;
    }
    public function cutStrs($str){
        $str  = mb_substr($str,0,(mb_strlen($str,'UTF-8')-1),'utf8');
        return $str;
    }


    /**
     * 获取wechat请求实例
     * @return \WechatRequester
     */
    protected function getWechatRequester ()
    {
        $appId     = get_setting("weixin_app_id");
        $appSecret = get_setting("weixin_app_secret");
        $token     = get_setting("weixin_mp_token");
        $requesterClient = new \Request($appId, $appSecret, $token);

        return $requesterClient;
    }

    /**
     * 设置公众号菜单
     */
    public function setMenu ()
    {
        $menuJsonData = C('Wechat.menu');
        $result = $this->wechatRequest->setMenu($menuJsonData);
        echo print_r($result, true);
    }

    /**
     * 获取公众号菜单
     */
    public function getMenu ()
    {
        $result = $this->wechatRequest->getMenu();
        echo print_r($result, true);
    }

    /**
     * 获取素材总数
     */
    public function getTmpMaterialById_action ()
    {
        $mediaId = $_GET['mediaId'];
        $mediaId = 'UPihgxtuvMp-ey3dQYzA-EPHt9fJnDUTeM4lUonCV-Lt-tXFQA8Z3hrLKcE7WU2f';
        $decodeResponseMode = $this->wechatRequest->decodeResponseMode;
        $this->wechatRequest->decodeResponseMode = Request::DECODE_MODE_TEXT;
        $result = $this->wechatRequest->getTmpMediaById($mediaId);
        $this->wechatRequest->decodeResponseMode = $decodeResponseMode;

        echo print_r($result, true);
    }

    /**
     * 获取素材总数
     */
    public function getMaterialCount_action ()
    {
        echo 'aaa';
        $result = $this->wechatRequest->getMaterialCount();

        echo print_r($result, true);
    }

    /**
     * 获取素材列表
     */
    public function getMaterialList_action ()
    {
        $type = I('type', 'image');
        $offset = I('offset' , 0);
        $count  = I('count', 20);
        $result = $this->wechatRequest->getMaterialListByType($type, $offset, $count);

        echo print_r($result, true);
    }

    /**
     * 添加摇一摇素材图片
     */
    public function addShakeArroundMaterial ()
    {
        if (! IS_POST || ! isset($_FILES['media'])) {
            $this->display('addShakeArroundMaterial');
            return;
        }
        $result = $this->wechatRequest->addShakeArroundMaterial($_FILES['media']['tmp_name'], 'icon');

        echo $result;
    }

    /**
     * 获取摇一摇申请状态
     * 返回说明 正常时的返回JSON数据包示例：
        {
            "data": {
                "apply_time": 1432026025,
                "audit_comment": "test",
                "audit_status": 1,
                "audit_time": 0
            },
            "errcode": 0,
            "errmsg": "success."
        }

     * 参数说明
     * 参数 	说明
        apply_time 	提交申请的时间戳
        audit_status 	审核状态。0：审核未通过、1：审核中、2：审核已通过；审核会在三个工作日内完成
        audit_comment 	审核备注，包括审核不通过的原因
        audit_time 	确定审核结果的时间戳；若状态为审核中，则该时间值为0
     */
    public function checkShakeArroundStatus ()
    {
        $result = $this->wechatRequest->checkShakeArroundStatus();

        echo $result;
    }

    /**
     * 申请开通摇一摇功能
     */
    public function applyShakeArround ()
    {
        if (! IS_POST) {
            $this->display('applyShakeArround');
            return;
        }

        $params = array(
            'name'          => I('post.name'),
            'phone_number'  => I('post.phone_number'),
            'email'         => I('post.email'),
            'industry_id'   => I('post.industry_id'),
            'qualification_cert_urls'         => I('post.qualification_cert_urls'),
            'apply_reason'  => I('post.apply_reason'),
        );
        $result = $this->wechatRequest->applyShakeArround($params);

        echo print_r($result, true);
    }

    /**
     * 申请摇一摇设备ID
     */
    public function applyShakeArroundDeviceId ()
    {
        $quantity     = I('quantity', 10);
        $apply_reason = I('quantity', '开发测试');
        $comment      = I('comment', null, 'strval');
        $poi_id       = I('poi_id', null, 'intval');
        $result = $this->wechatRequest->applyShakeArroundDeviceId($quantity, $apply_reason, $comment, $poi_id);

        echo print_r($result, true);
    }

    /**
     * 查看摇一摇设备ID申请状态
     */
    public function checkShakeArroundDeviceApplyStatus ()
    {
        $applyId       = I('applyId', 427104, 'intval');
        $result = $this->wechatRequest->checkShakeArroundDeviceApplyStatus ($applyId);

        echo $result;
    }

    /**
     * 获取摇一摇设备列表
     */
    public function getShakeArroundDeviceList ()
    {
        $applyId       = I('applyId', 427104, 'intval');
        $lastSeen      = I('lastSeen', 0, 'intval');
        $count         = I('count', 50, 'intval');
        $result = $this->wechatRequest->getShakeArroundDeviceList ($lastSeen, $count, $applyId);
        $deviceList = json_decode($result, true);
        $deviceList = $deviceList['data']['devices'];
        $lastDevice = array_pop($deviceList);


        echo '<a href="'
            . U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,
                array('applyId'=>$applyId, 'lastSeen'=>$lastDevice['device_id'], 'count'=>$count)
               )
            . '">下一页</a>';
        echo '<hr/>';
        echo '设备列表：';
        echo print_r($result, true);
    }

    /**
     * 添加摇一摇页面
     */
    public function addShakeArroundPage ()
    {
        if (! IS_POST) {
            $this->assign('form', array(
                    'legend' => '添加摇一摇页面',
                    'data'   => array (
                          array('label'  => '主标题',
                                'name'   => 'title',
                                'desc'   => '在摇一摇页面展示的主标题，不超过6个汉字或12个英文字母 '),
                          array('label'  => '副标题',
                                'name'   => 'description',
                                'desc'   => '在摇一摇页面展示的副标题，不超过7个汉字或14个英文字母  '),
                          array('label'  => '页面展示的图片URL',
                                'name'   => 'icon_url',
                                'desc'   => '在摇一摇页面展示的图片。图片需先上传至微信侧服务器，用“素材管理-上传图片素材”接口上传图片，返回的图片URL再配置在此处'),
                          array('label'  => '跳转URL',
                                'name'   => 'page_url',
                                'desc'   => '跳转URL '),
                          array('label'  => '备注信息',
                                'name'   => 'comment',
                                'desc'   => '页面的备注信息，不超过15个汉字或30个英文字母  '),
                    ),
                )
            );
            $this->display('commonForm');
            return;
        }

        $title       = I('post.title');
        $description = I('post.description');
        $icon_url    = I('post.icon_url');
        $page_url    = I('post.page_url');
        $comment     = I('post.comment');
        $result = $this->wechatRequest->addShakeArroundPage($title, $description, $icon_url, $page_url, $comment);

        echo print_r($result, true);
    }

    /**
     * 修改名片信息展示
     * @return [type] [description]
     */
    public function showCardDetail(){
        if(IS_POST){
            $result = $this->editCardDetail();
            $this->assign('result',$result);
        }
        $cardid  = I('cardid');
        $params = array();
        $params['cardid'] = $cardid;
        $res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
        $wechatsList = $this->analyShowVcard($res['data']['wechats']);
        $info = $wechatsList[0];
       // echo __FILE__.' LINE:'.__LINE__."\r\n".'<pre>',print_r($info,1);exit;
        if(!empty($info['FN']) && $info['FN']){
           // $nameArr = explode(',',$info['FN']);
            $info['FN'] = $info['FN'][0];
        }else{
        	$info['FN'] = '';
        }
        if(!empty($info['ORG']) && $info['ORG']){
           // $companyArr = explode('###',$info['ORG']);
            $info['ORG'] = $info['ORG'][0];
        }else{
        	$info['ORG'] = '';
        }
        if(!empty($info['ADR']) && $info['ADR']){
           // $addressArr = explode(',',$info['ADR']);
            $info['ADR'] = $info['ADR'][0];
        }else{
        	$info['ADR'] = '';
        }
        //!empty($info['CELL']) && $info['CELL'] = $info['CELL']; //explode(',',$info['CELL']);
       // !empty($info['TEL']) && $info['TEL']  =  $info['TEL']; //explode(',',$info['TEL']);
       // !empty($info['URL']) && $info['URL'] = $info['URL']; //explode(',',$info['URL']);
        $this->assign('info',$info);

        //网页调用照片
        include_once 'jssdk.php';
        $jssdk = new \JSSDK(C('Wechat')['AppID'], C('Wechat')['AppSecret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage', $signPackage);
        $this->assign('openid', $this->session['openid']);
        $this->assign('cardid', $cardid);
        $this->assign('sysType', $this->getAppName());
        $this->display();
    }
    /**
     * 修改名片信息保存
     * @return [type] [description]
     */
    public function editCardDetail(){
        $name = I('post.name');
        $company = I('post.company');
        $address = I('post.address');
        $mobile = I('post.mobile');
        $telphone = I('post.telphone');
        $url = I('post.url');
        $cardid = I('post.cardid');
        $newInfo = array();
        //获取修改之前的名片信息
        $params['cardid'] = $cardid;
        $res = \AppTools::webService('\Model\WeChat\WeChat', 'getWechatCard', array('params'=>$params));
        $wechatsList = $this->analyShowVcard($res['data']['wechats']);
        $info = $wechatsList[0];
      // echo __FILE__.' LINE:'.__LINE__."\r\n".'<pre>',print_r($info,1);exit;
        $paramUpdate = array(
        		'name' => array($name),
        		'company_name' => array($company),
        		'address' => array($address),
        		'mobile' => $mobile,
        		'telephone' => $telphone,
        		'web' => $url,
        );
        log::write('File:'.__FILE__.' LINE:'.__LINE__." 参数:   \r\n".''.var_export($_POST,true));
        log::write('File:'.__FILE__.' LINE:'.__LINE__." 修改信息  param:   \r\n".'<pre>'.var_export($paramUpdate,true));
		$vcardJson = $this->analyUpdateVcard($res['data']['wechats'][0]['vcard'],$paramUpdate);
		//echo __FILE__.' LINE:'.__LINE__."\r\n".'<pre>',print_r(json_decode($vcardJson,true),1);exit;
		log::write('File:'.__FILE__.' LINE:'.__LINE__." 修改信息:   \r\n<pre>".''.var_export(json_decode($vcardJson,true),true));

        //整合姓名字符串 替换新的姓名
        /* $nameArr = explode(',',$info['FN']);
        $nameArr[0] = $name;
        $newInfo['name'] = implode(',', $nameArr);

        //公司字串
        $companyArr = explode('###',$info['ORG']);
        $companyArr[0] = $company;
        $newInfo['company'] = implode('###',$companyArr);

        //地址字符串
        $addressArr = explode(',',$info['ADR']);
        $addressArr[0] = $address;
        $newInfo['address'] = implode(',',$addressArr);

        $newInfo['telephone'] = implode(',',$telphone);
        $newInfo['mobile'] = implode(',',$mobile);
        $newInfo['companyurl'] = implode(',',$url); */
        $newInfo['cardid'] = $cardid;
        $newInfo['vcard'] = $vcardJson;

        $result = \AppTools::webService('\Model\WeChat\WeChat','editCardDetail',array('params'=>$newInfo));
        if($result && $result['status']==0){
            return "success";
        }else{
            return "fail";
        }
    }

    /**
     * 中国正常GCJ02坐标---->百度地图BD09坐标
     * 腾讯地图用的也是GCJ02坐标
     * @param double $lat 纬度
     * @param double $lng 经度
     */
    public function Convert_GCJ02_To_BD09($lat,$lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng;
        $y = $lat;
        $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta) + 0.0065;
        $lat = $z * sin($theta) + 0.006;
        return array('lng'=>$lng,'lat'=>$lat);
    }

    /**
     * 百度地图BD09坐标---->中国正常GCJ02坐标
     * 腾讯地图用的也是GCJ02坐标
     * @param double $lat 纬度
     * @param double $lng 经度
     * @return array();
     */
    public function Convert_BD09_To_GCJ02($lat,$lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
        return array('lng'=>$lng,'lat'=>$lat);
    }

    //记录日志到后台
    public function jsLog(){
    	$logInfo = array();
    	if($_POST){
    		foreach ($_POST as $k=>$v){
    			$logInfo[$k] = $v;
    		}
    	}
    	$logPath = C('LOG_PATH');
    	if(!is_dir($logPath)){
    		mkdir($logPath,0777,true);
    	}
    	$logPath .= 'voice_'.date('y_m_d').'.log';
    	log::write('File:'.__FILE__.' LINE:'.__LINE__."\r\n".'js 录音功能 '.var_export($logInfo,true),
    	Log::INFO,'',$logPath);
    }

    /**
     * 解析名片数据用于显示
     * @param unknown $list
     */
    public function analyShowVcard($list,$jons=1)
    {
    	if($list && $jons==1){
    		foreach ($list as $key=>$val){
    			$vcard = $val['vcard'] ? json_decode($val['vcard'],true) : array();
    			if(empty($val['vcard']) || empty($vcard['front'])){
    				continue;
    			}
    			$front = $vcard['front'];
    			$FN = $ORG = $ADR = $CELL = $TEL = $URL = $TITLE = $EMAIL = array();
    			$FN = $this->_getVcardValue($front,'name'); //人名
    			$TEL = $this->_getVcardValue($front,'mobile'); //手机
    			if(!empty($front['company'])){
    				foreach ($front['company'] as $company){
    					$ORG = $this->_getVcardValue($company,'company_name'); //公司名称
    					$ADR = $this->_getVcardValue($company,'address'); //地址
    					$CELL = $this->_getVcardValue($company,'telephone'); //电话
    					$URL = $this->_getVcardValue($company,'web'); //网址
    					$TITLE = $this->_getVcardValue($company,'job'); //职位
    					$EMAIL = $this->_getVcardValue($company,'email'); //邮箱
    				}
    			}
    			$list[$key]['FN'] = $FN;
    			$list[$key]['ORG'] = $ORG;
    			$list[$key]['ADR'] = $ADR;
    			$list[$key]['CELL'] = $CELL;
    			$list[$key]['TEL'] = $TEL;
    			$list[$key]['URL'] = $URL;
    			$list[$key]['TITLE'] = $TITLE;
    			$list[$key]['EMAIL'] = $EMAIL;
    		}
    	}
    	return $list;
    }

    /**
     * 获取名片json字符串中的value
     * param $dataSet 数据数组
     * param $jsonName 数据健名
     */
    private function _getVcardValue($dataSet,$jsonName)
    {
    	$rst = array();
    	if(isset($dataSet[$jsonName])){
    		foreach ($dataSet[$jsonName] as $dataElement){
    			$rst[] = $dataElement['value'];
    		}
    	}
    	return $rst;
    }

    /**
     * 保存修改后的数据到vcard json字符串中
     */
    public function analyUpdateVcard($vcardJson,$updatedParam=array())
    {
    	$rst = array();
    	$vcardArr = json_decode($vcardJson,true);
    	$front    = $vcardArr['front'];
    	$sysFileds = array('name','mobile','company_name','address','telephone','web'); //定义名片中有的所有属性,'email','fax','job'
    	$nameArr = isset($front['name'])?$front['name']:array(); //姓名
    	$mobileArr = isset($front['mobile'])?$front['mobile']:array(); //手机号
    	$companyArr = isset($front['company'])?$front['company']:array(); //公司
    	//修改名字
    	if($nameArr){
    		foreach ($nameArr as $key=>$value){
    			if($key > 0){
    				break;
    			}
    			$nameArr[$key]['value'] = $updatedParam['name'][0];
    		}
    	}
    	//修改手机号
    	if($mobileArr){
    		foreach ($mobileArr as $key=>$value){
    			$mobileArr[$key]['value'] = $updatedParam['mobile'][$key];
    		}
    	}
    	//修改公司相关信息
    	if($companyArr){
    		foreach ($companyArr as $key=>$company){
    			if($key>0){
    				break;
    			}
    			//公司名称信息修改
    			$company_nameArr = isset($company['company_name'])?$company['company_name']:array();
    			foreach ($company_nameArr as $k=>$v){
    				if($k > 0){
    					break;
    				}
    				$company_nameArr[$key]['value'] = $updatedParam['company_name'][0];
    			}
    			$companyArr[$key]['company_name'] = $company_nameArr;

    			//公司地址信息修改
    			$addressArr = isset($company['address'])?$company['address']:array();
    			foreach ($addressArr as $k=>$v){
    				if($k > 0){
    					break;
    				}
    				$addressArr[$key]['value'] = $updatedParam['address'][0];
    			}
    			$addressArr[$key]['$addressArr'] = $addressArr;

    			//修改电话号码
    			$telephoneArr = isset($company['telephone'])?$company['telephone']:array();
    			foreach ($telephoneArr as $k=>$v){
    				$telephoneArr[$k]['value'] = $updatedParam['telephone'][$k];
    			}
    			$companyArr[$key]['telephone'] = $telephoneArr;

    			//修改网址
    			$webArr = isset($company['web'])?$company['web']:array();
    			foreach ($webArr as $k=>$v){
    				$webArr[$k]['value'] = $updatedParam['web'][$k];
    			}
    			$companyArr[$key]['web'] = $webArr;
    		}
    	}

    	$front['name'] = $nameArr;
    	$front['mobile'] = $mobileArr;
    	$front['company'] = $companyArr;
    	$vcardArr['front'] = $front;
    	return json_encode($vcardArr);
    }
}

/* EOF */