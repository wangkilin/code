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

define('IN_MOBILE', true);

class english extends BaseController
{
	public function get_access_rule()
	{
		// 全部允许进入
		$rule_action['rule_type'] = 'black';
		$rule_action['actions'] = array();

		return $rule_action;
	}

	public function setup()
	{
		View::import_clean();

		View::import_css(array(
			'mobile/css/english_mobile.css'
		));

		View::import_js(array(
			'js/jquery.2.js',
			'js/jquery.form.js',
			'mobile/js/framework.js',
			'mobile/js/icb-mobile.js',
            'mobile/js/app.js',
            'js/global.js',
			'mobile/js/icb-mobile-template.js',
			'js/icb_template.js',
		));

		if (in_weixin())
		{
			$noncestr = mt_rand(1000000000, 9999999999);

			View::assign('weixin_noncestr', $noncestr);
			//echo get_setting('weixin_app_id'), get_setting('weixin_app_secret');
			$jsapi_ticket = $this->model('openid_weixin_weixin')->get_jsapi_ticket($this->model('openid_weixin_weixin')->get_access_token(get_setting('weixin_app_id'), get_setting('weixin_app_secret')));

			$url = ($_SERVER['HTTPS'] AND !in_array(strtolower($_SERVER['HTTPS']), array('off', 'no'))) ? 'https' : 'http';

			$url .= '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

			View::assign('weixin_signature', $this->model('openid_weixin_weixin')->generate_jsapi_ticket_signature(
				$jsapi_ticket,
				$noncestr,
				TIMESTAMP,
				$url
			));
		}
	}
	/**
	 * 课程分类
	 */
	public function category_action()
	{
		$this->crumb(Application::lang()->_t('教程'), '/m/english/');

		View::assign('categoryList', $this->model('category')->getCategoryList(null, null, PHP_INT_MAX));

		//View::assign('content_nav_menu', $this->model('menu')->getMenuListWithModuleInLink('course'));

		View::output('m/english/category.php');
	}
	/**
	 * 我的页面主页
	 */
	public function home_action()
	{
		if (!$this->user_id)
		{
			//HTTP::redirect('/m/');
		}

		$this->crumb(Application::lang()->_t('我的'), '/m/english/home/');
		View::assign('body_class', 'homeBody');

		View::output('m/english/home');
	}

	/**
	 * 交作业
	 */
	public function homework_action()
	{
	    if (! $_GET['id']) {
	        HTTP::redirect('/m/english/');
	    }
	    $course = $this->model('course')->getById($_GET['id']);

	    // 指定文章没有找到
	    if (! $course) {
	        HTTP::error_404();
	    }
	    View::import_css('js/jPlayer-2.9.2/dist/skin/blue.monday/css/jplayer.blue.monday.min.css');
	    View::import_js(array(
	                    'js/jPlayer-2.9.2/dist/jplayer/jquery.jplayer.min.js',
	                    'http://res.wx.qq.com/open/js/jweixin-1.2.0.js'
	    ));

	    $homeworks = $this->model('homework')->getByCourseId($_GET['id']);
		$this->crumb(Application::lang()->_t('交作业'), '/m/english/homeworks/');

		View::assign('item', $course);
		View::assign('itemList', $homeworks);
		View::assign('body_class', 'homeworkBody');

		$this->display('m/english/homework.php');
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
	 * 保存答案
	 */
	public function ajax_save_answer_action ()
	{
	    if (! $_GET['id']) {
	        HTTP::redirect('/m/english/');
	    }
	    $course = $this->model('course')->getById($_GET['id']);

	    // 指定文章没有找到
	    if (! $course) {
	        HTTP::error_404();
	    }

	    importClass('ConvertFormat', INC_PATH . 'Wechat/');
	    importClass('Request', INC_PATH . 'Wechat/');
	    importClass('WechatListener', INC_PATH . 'Wechat/');
	    importClass('MyWechatHandler', INC_PATH . 'Wechat/');

	    $options = array('decodeResponseMode' => \Request::DECODE_MODE_ARRAY,
	                     'logger'             => 'trace',
	    );
	    $this->wechatRequest =  $this->getWechatRequester()
	                                 ->setOptions($options)
	                                 ;
	    error_log(print_r($_POST, true), 3, '/tmp/log.log');
	    // 添加新的课后作业
	    foreach ($_POST['homework_answer'] as $_homeworkId => $_weixinVoiceId) {
	        $decodeResponseMode = $this->wechatRequest->decodeResponseMode;
	        $this->wechatRequest->decodeResponseMode = Request::DECODE_MODE_TEXT;
	        $voiceContent = $this->wechatRequest->getTmpMediaById($_weixinVoiceId);
	        $this->wechatRequest->decodeResponseMode = $decodeResponseMode;


	        $dir = '/english_answer/' . gmdate('Ymd', APP_START_TIME);

	        Application::upload()->initialize(array(
	                        'allowed_types' => '*',
	                        'upload_path'   => get_setting('upload_dir') . $dir,
	                        'is_image'      => TRUE,
	                        'max_size'      => get_setting('upload_avatar_size_limit'),
	                        'file_name'      => $_homeworkId.'.amr'
	        ))->do_upload('upload_file', $voiceContent);
	        // 检查上传结果
	        $this->checkUploadFileResult();
	        $uploadData = Application::upload()->data();
	        $filePath = $dir . '/' . $uploadData['file_name'];

	        echo htmlspecialchars(json_encode(array(
	                        'success' => true,
	                        'thumb'   => get_setting('upload_url') . $filePath,
	                        'file'    => $filePath,
	        )), ENT_NOQUOTES);

	        $this->model('homeworkAnswer')->add(
	                array(
	                    'homework_id'      => $_homeworkId,
	                    'wechat_media_id'  => $_weixinVoiceId,
                        'uid'              => $this->user_id,
                        'file'             => $filePath,
	                )
	         );
	    }

	    H::ajax_json_output(Application::RSM(array(
	                    'url' => get_js_url('/')
	    ), 1, null));
	}

	/**
	 * 作业列表
	 */
	public function homeworks_action ()
	{
	    View::assign('body_class', 'homeworksBody');
		$this->display('m/english/homeworks.php');
	}
	/**
	 * 学习报告
	 */
	public function report_action ()
	{
	    View::assign('body_class', 'reportBody');
		$this->display('m/english/report.php');
	}
	/**
	 * 课程分类
	 */
	public function index_action ()
	{
		$where = 'is_recommend = 1';
		$todayCourses = $this->model('course')->getCourseList($where);
		View::assign('courseList', (array) $todayCourses);
		View::assign('body_class', 'indexBody');

		View::output('m/english/index.php');
	}
	/**
	 * 课程列表
	 */
	public function list_action ()
	{
		$where = array();
		if (isset($_GET['category'])) {
		    $_GET['category'] = intval($_GET['category']);
		    $where[] = 'parent_id = ' . $_GET['category'];
		}
		if (isset($_POST['q'])) {
		    $where [] = '(title like "%' . $this->model('course')->quote($_POST['q']) .'%"
                       OR content like "%' . $this->model('course')->quote($_POST['q']) .'%")';
		}
		$courseList = $this->model('course')->getCourseList(join(' AND ', $where), null, 20);
		$this->assign('list', $courseList);
		View::assign('body_class', 'listBody');

		$this->display('m/english/list.php');
	}
	/**
	 * 课程详情
	 */
	public function show_action()
	{
	    if (! $_GET['id']) {
			HTTP::redirect('/m/english/');
		}
		View::import_css('js/jPlayer-2.9.2/dist/skin/blue.monday/css/jplayer.blue.monday.min.css');
		View::import_js(array('js/jPlayer-2.9.2/dist/jplayer/jquery.jplayer.min.js'));

		$this->crumb(Application::lang()->_t('课程详情'));

		$course = $this->model('course')->getById($_GET['id']);

		// 指定文章没有找到
		if (! $course) {
		    HTTP::error_404();
		}
		// 更细阅读数 + 1
		$this->model('course')->addViews($_GET['id']);
		$this->model('category')->addViews($course['parent_id']);
		$historyInfo = $this->model('userReadHistory')
		                    ->getByUidAndItemId($this->user_id, $_GET['id'], 'course');

		// 文章内容做bbc转换
		//$course['content'] = FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($course['content'])));
		$course['content'] = FORMAT::parse_attachs(FORMAT::parse_bbcode($course['content']));

		$this->assign('item', $course);
		$this->assign('historyInfo', $historyInfo);
		View::assign('body_class', 'showBody');
		$this->display('m/english/show.php');
	}
	/**
	 * 成为付费会员
	 */
	public function pay_action ()
	{
		$this->crumb(Application::lang()->_t('付费会员'));
		$this->display('m/english/pay.php');
	}

	public function setCourseRead_action ()
	{
	    if (! $_GET['id'] || !($course = $this->model('course')->getById($_GET['id']))) {
	        return;
	    }
	    HTTP::setHeaderNoCache();
	    $historyInfo = $this->model('userReadHistory')
	                        ->getByUidAndItemId($this->user_id, $_GET['id'], 'course');

	    $data = array('page_position' => $_POST['page_position'],
	                  'uid'           => $this->user_id,
	                  'item_id'       => $_GET['id'],
	                  'item_type'     => 'course'
	    );
	    if ($historyInfo) {
	        $this->model('userReadHistory')
	             ->update ($historyInfo['id'], $data, array('page_position'=>true));
	    } else {
	        $this->model('userReadHistory')->add ($data);
	    }

	}

}
