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

/**
 * 控制器基类
 * @author zhoumingxia
 *
 */
class BaseController extends Controller
{
	/**
	 *
	 * @const int
	 */
	const IS_ROLE_ADMIN = 0x01; // 后台管理员
    const IS_ROLE_MODERATOR = 0x02;  // 前台文章修改

    const PERMISSION_MAP = array (
        self::IS_ROLE_ADMIN         => 'is_administortar',
        self::IS_ROLE_MODERATOR     => 'is_moderator',
    );

    protected $categoryInfo = null;

	/**
	 * 移动端访问， 跳转到相应链接
	 * @param unknown $url
	 */
	public function mobileRedirect ($url)
	{
		if (is_mobile()) {
			HTTP::redirect($url);
		}
	}
	/**
	 * 查看模板文件中是否包含公用部分。 根据包含的公用部分， 准备相应的数据
	 * @param string $tplFile
	 * @param string $module 是哪个模块调用。 用来生成链接使用
	 */
	protected function _prepareDataByCheckingTplFile ($tplFile, $module='index')
	{
		// 导航
		if (View::is_output('block/content_nav_menu.php', $tplFile)) {
			View::assign('content_nav_menu', $this->model('menu')->getMenuListWithModuleInLink($module));
		}
		// 推荐的技能导航
		if (View::is_output('block/tag_nav_menu.php', $tplFile)) {
			View::assign('tag_nav_menu', $this->model('menu')->getTagMenuList($module));
		}

		// 边栏可能感兴趣的人
		if (View::is_output('block/sidebar_recommend_users_topics.php', $tplFile)) {
			View::assign('sidebar_recommend_users_topics', $this->model('system')->recommend_users_topics($this->user_id));
		}

		// 边栏热门用户
		if (View::is_output('block/sidebar_hot_users.php', $tplFile)) {
			View::assign('sidebar_hot_users', $this->model('system')->sidebar_hot_users($this->user_id, 5));
		}

		// 边栏热门话题
		if (View::is_output('block/sidebar_hot_topics.php', $tplFile)) {
			View::assign('sidebar_hot_topics', $this->model('system')->sidebar_hot_topics($this->categoryInfo['id']));
		}

		// 边栏专题
		if (View::is_output('block/sidebar_feature.php', $tplFile)) {
			View::assign('feature_list', $this->model('system')->feature_list());
		}
	}



	/**
	 * 根据id或者token获取分类
	 * @param unknown $idOrToken
	 */
	public function getCategoryInfoByIdOrToken($idOrToken)
	{
		$method = is_digits($_GET['category']) ? 'get_category_info' : 'get_category_info_by_url_token';

		return  $this->model('system')->$method($_GET['category']);
	}

	public function voteArticleById($type, $id)
	{

	}

	/**
	 *
	 * @param unknown $type
	 * @param unknown $id
	 */
	public function getVoteByArticleId ($type, $id)
	{

	}


	/**
	 * 获取分类 HTML 数据
	 */
	public function buildCategoryDropdownHtml($selectedId = 0, $prefix = '')
	{
		$itemModel = $this->model('category');

		$rootItems = $itemModel->getCategoryList('parent_id=0', 'id DESC', PHP_INT_MAX);
		if ($rootItems) {
		    $childItems    = $itemModel->getCategoryList('parent_id>0', 'id DESC', PHP_INT_MAX);
		} else {
			$childItems = array();
		}
		$_items = array();
		foreach ($childItems as $_key => $_item) {
			isset($_items[$_item['parent_id']]) OR $_items[$_item['parent_id']] = array() ;
			$_items[$_item['parent_id']][] = $_item;
		}
		$childItems = $_items;
		unset($_items);
		// 确保选中值为数组， 这样可以支持多重选定。
		if (! is_array($selectedId)) {
			$selectedId = array($selectedId);
		}

		$html = '';
		foreach ($rootItems AS $_rootItem) {
			$selected = '';
			if (in_array($_rootItem['id'], $selectedId)) {
				$selected =  'selected="selected"';
			}

			$html .= '<option class="root-level" value="' . $_rootItem['id'] . '"' . $selected . '>' . $_rootItem['title'] . '</option>';
			if (! isset($childItems[$_rootItem['id']])) {
				continue;
			}
			// 查看当前根话题下是否有子话题， 如果有子话题， 列出子话题
			foreach ($childItems[$_rootItem['id']] as $_childItem) {
				$selected = '';
				$childItems[$_rootItem['id']]['has_parent'] = true; // 标识item有父级
				if (in_array($_childItem['id'], $selectedId)) {
					$selected =  'selected="selected"';
				}
				$html .= '<option class="sub-level" value="' . $_childItem['id'] . '"' . $selected . '>' . $prefix . ' ' .$_childItem['title'] . '</option>';
			}
		}

		return $html;
	}

	/**
	 * 检查用户是否具有其中某个权限
	 * @param int $checkingRoleFlags 待检查的权限
	 * @return boolean
	 */
	protected function hasRolePermission ($checkingRoleFlags)
	{
		// if ( ($checkingRoleFlags & self::IS_ROLE_ADMIN)
		//  && $this->user_info['permission']['is_administortar']) {
		// 	return true;
		// }

		// if ( ($checkingRoleFlags & self::IS_ROLE_MODERATOR)
		//  && $this->user_info['permission']['is_moderator']) {
		// 	return true;
        // }

        foreach ($this::PERMISSION_MAP as $_key => $_value) {
            if ( ($checkingRoleFlags & $_key )
                && $this->user_info['permission'][$_value]) {
                return true;
            }
        }

		return false;
	}

	/**
	 * 检查用户是否具有相应的权限
	 * @param int $checkingRoleFlags 待检查的权限
	 */
	protected function checkPermission ($checkingRoleFlags)
	{
		if (! $this->hasRolePermission($checkingRoleFlags)) {
            if (defined('IN_AJAX') && IN_AJAX) {
		        H::ajax_json_output(Application::RSM(null, -1, Application::lang()->_t('你没有访问权限, 请重新登录')));
            } else {
                H::redirect_msg(Application::lang()->_t('你没有访问权限, 请重新登录'), '/');
            }
		}
	}

	/**
	 * 检查post参数， 然后GET重定向到指定URL
	 * @param unknown $redirectBaseUrl
	 * @param unknown $urlEncodeParamKeys
	 */
	protected function checkPostAndRedirect ($redirectBaseUrl, $urlEncodeParamKeys=array())
	{
		if ($_POST) {
			foreach ($_POST as $key => $val) {
				if (in_array($key, $urlEncodeParamKeys)) {
					$val = rawurlencode($val);
				}
				$param[] = $key . '-' . $val;
			}

			H::ajax_json_output(Application::RSM(array(
				'url' => get_js_url($redirectBaseUrl . implode('__', $param))
			), 1, null));
		}
	}

    /**
     * 根据文章类型和id获取附件列表
     * @param string $type 文章类型
     * @param int    $id   文章id
     */
    public function getAttachListByItemTypeAndId ($type, $id)
    {
    	$model = $this->model($type);
        if (! $itemInfo = $model->getById($id)) {
            H::ajax_json_output(Application::RSM(null, '-1', Application::lang()->_t('无法获取附件列表')));
        }

        if ($itemInfo['uid'] != $this->user_id AND ! $this->hasRolePermission(self::IS_ROLE_ADMIN | self::IS_ROLE_MODERATOR)) {
            H::ajax_json_output(Application::RSM(null, '-1', Application::lang()->_t('你没有权限编辑这个附件列表')));
        }

        if ($article_attach = $this->model('publish')->getAttachListByItemTypeAndId($type, $id)) {
            foreach ($article_attach as $attach_id => $val) {
                $article_attach[$attach_id]['class_name'] = $this->model('publish')->getCssClassByFileName($val['file_name']);

                $article_attach[$attach_id]['delete_link'] = get_js_url('/publish/ajax/remove_attach/attach_id-' . Application::crypt()->encode(json_encode(array(
                    'attach_id' => $attach_id,
                    'access_key' => $val['access_key']
                ))));

                $article_attach[$attach_id]['attach_id'] = $attach_id;
                $article_attach[$attach_id]['attach_tag'] = 'attach';
            }
        }

        H::ajax_json_output(Application::RSM(array(
            'attachs' => $article_attach
        ), 1, null));
    }

    /**
     * 为批量文件上传产生一个key值
     * @return string
     */
    public function getBatchUploadAccessKey ()
    {
    	static $key = null;
    	if (! $key) {
    	    $key = substr(md5($this->user_id . APP_START_TIME), 0, -12) . date('ymdHis', APP_START_TIME);
    	}

    	return $key;
    }

    public function processUploadAttach ($module, $batchKey, $filename)
    {
        if (get_setting('upload_enable') != 'Y' OR ! $module) {
            return false;
        }

        Application::upload()->initialize(array(
            'allowed_types' => get_setting('allowed_upload_types'),
            'upload_path'   => get_setting('upload_dir') . '/' . $module . '/' . gmdate('Ymd', APP_START_TIME),
            'is_image'      => FALSE,
            'max_size'      => get_setting('upload_size_limit')
        ));

        if (isset($_GET[$filename])) {
            Application::upload()->do_upload($_GET[$filename], file_get_contents('php://input'));
        } else if (isset($_FILES[$filename])) {
            Application::upload()->do_upload($filename);
        } else {
            return false;
        }
        // 检查上传结果
        $this->checkUploadFileResult();
        $upload_data = Application::upload()->data();

        if ($upload_data['is_image'] == 1) {
            foreach (Application::config()->get('image')->attachment_thumbnail AS $key => $val)
            {
                $thumb_file[$key] = getUploadPicFileNameBySize($val);

                Application::image()->initialize(array(
                    'quality'      => 90,
                    'source_image' => $upload_data['full_path'],
                    'new_image'    => $thumb_file[$key],
                    'width'        => $val['w'],
                    'height'       => $val['h']
                ))->resize();
            }
        }

        $cssClass = $this->model('publish')->getCssClassByFileName(basename($upload_data['full_path']));
		// 插入数据库
        $attach_id = $this->model('publish')
                          ->add_attach(
                                  $module,
                                  $upload_data['orig_name'],
                                  $batchKey,
                                  APP_START_TIME,
                                  basename($upload_data['full_path']),
                                  $upload_data['is_image'],
                                  $upload_data['file_type'],
                                  $cssClass
                            );

        $output = array(
            'success' => true,
            'delete_url' => get_js_url('/publish/ajax/remove_attach/attach_id-' . Application::crypt()->encode(json_encode(array(
                'attach_id'  => $attach_id,
                'access_key' => $batchKey
            )))),
            'attach_id'  => $attach_id,
            'attach_tag' => 'attach'

        );

        $attach_info = $this->model('publish')->getAttachById($attach_id);
        if ($attach_info['thumb']) {
            $output['thumb'] = $attach_info['thumb'];
        } else {
            $output['class_name'] = $cssClass;
        }
        $output['url'] = $attach_info['attachment'];

        //exit(htmlspecialchars(json_encode($output), ENT_NOQUOTES));
        H::ajax_json_output($output);
    }

    /**
     * 检查上传文件结果， 如出现错误， 只接输出json信息
     */
    public function checkUploadFileResult ()
    {
    	if (Application::upload()->get_error()) {
    	    switch (Application::upload()->get_error()) {
    	        case 'upload_invalid_filetype':
    	            H::ajax_json_output(Application::RSM(null, '-1', _t('文件类型无效')));
    	            break;

    	        case 'upload_invalid_filesize':
    	            H::ajax_json_output(Application::RSM(null, '-1', _t('文件尺寸过大, 最大允许尺寸为 %s KB', get_setting('upload_size_limit'))));
    	            break;

    	        default:
    	            H::ajax_json_output(Application::RSM(null, '-1', _t('错误代码') . ': ' . Application::upload()->get_error()));
    	            break;
    	    }
    	}

    	if (! Application::upload()->data()) {
    	    H::ajax_json_output(Application::RSM(null, '-1', _t('上传失败, 请与管理员联系')));
    	}
    }
    /**
     * 将上传的模块图片，生成不同尺寸图片
     * @param string $moduleName 模块名称
     * @param string $oldPicName 旧文件名称
     * @return multitype:string
     */
    public function resizeUploadedModulePic ($moduleName, $oldPicName=null)
    {
    	$picList = array();
    	$upload_data = Application::upload()->data();
    	if ($upload_data && $upload_data['is_image'] == 1) {
    	    $imageConfig = Application::config()->get('image')->{$moduleName . '_thumbnail'};
    	    foreach($imageConfig AS $key => $val) {
    	        $picList[$key] = getUploadPicFileNameBySize($val);

    	        Application::image()->initialize(array(
    	                        'quality'      => 90,
    	                        'source_image' => $upload_data['full_path'],
    	                        'new_image'    => $picList[$key],
    	                        'width'        => $val['w'],
    	                        'height'       => $val['h']
    	        ))->resize();

    	        if ($oldPicName) {
    	            @unlink(get_setting('upload_dir') . '/'.$moduleName.'/' . str_replace(getSizePartInPicFileName ($imageConfig['min']), getSizePartInPicFileName ($val), $oldPicName));
    	        }
    	    }

    	    if ($oldPicName) {
    	        @unlink(get_setting('upload_dir') . '/'.$moduleName.'/' . str_replace('_' . getSizePartInPicFileName ($imageConfig['min']), '', $oldPicName));
    	    }
    	}

    	return $picList;
    }

    /**
     * 输出json
     * @param unknown $data
     */
    public function jsonExit ($data)
    {
    	$option = defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 0;
    	exit(str_replace(array("\r", "\n", "\t"), '', json_encode($data, $option)));
    }

    public function jsonMsgExit ($info, $code, $msg)
    {
    	$this->jsonExit(Application::RSM($info, $code, $msg));
    }

    public function jsonErrExit ($msg)
    {
    	$this->jsonMsgExit(null, -1, $msg);
    }

}

/* EOF */
