<?php
class main extends BaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = 'white';

        $rule_action['actions'][] = 'square';
        $rule_action['actions'][] = 'index';
        $rule_action['actions'][] = 'build_ocr_text';

        return $rule_action;
    }

    public function index_action()
    {
        if ($_GET['notification_id']) {
            $this->model('notify')->read_notification($_GET['notification_id'], $this->user_id);
        }

        // 手机端请求
        if (is_mobile()) {
            HTTP::redirect('/m/course/' . $_GET['id']);
        }
        if (is_numeric($_GET['id'])) {
            $itemInfo = $this->model('course')->getById($_GET['id']);
        } else {
            $itemInfo = $this->model('course')->getCourseByToken($_GET['id']);
        }
        // 指定文章没有找到
        if (! $itemInfo) {
            HTTP::error_404();
        }
        // 文章有附件
        if ($itemInfo['has_attach']) {
            $itemInfo['attachs'] = $this->model('publish')->getAttachListByItemTypeAndId('course', $itemInfo['id'], 'min');

            $itemInfo['attachs_ids'] = FORMAT::parse_attachs($itemInfo['content'], true);
        }
        // 文章内容做bbc转换
        $itemInfo['content'] = FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($itemInfo['content'])));
        // 查看本人是否为此文章投票
        if ($this->user_id) {
            $itemInfo['vote_info'] = $this->model('article')->getVoteByArticleId('article', $itemInfo['id'], null, $this->user_id);
        }
        // 获取全部投票的用户
        $itemInfo['vote_users'] = $this->model('article')->getVoteUsersByArticleId('article', $itemInfo['id'], 1, 10);

        View::assign('itemInfo', $itemInfo);

        //$articleTags = $this->model('tag')->getTagsByArticleIds($itemInfo['id'], 'course');
        if ($articleTags) {
            View::assign('article_topics', $articleTags);
            $tagIds = array_keys($articleTags);
        }

        View::assign('reputation_topics', $this->model('people')->get_user_reputation_topic($itemInfo['user_info']['uid'], $user['reputation'], 5));

        $this->crumb($itemInfo['title'], '/article/' . $itemInfo['id']);

        View::assign('human_valid', human_valid('answer_valid_hour'));

        if ($_GET['item_id']) {
            $comments[] = $this->model('article')->get_comment_by_id($_GET['item_id']);
        } else {
            $comments = $this->model('article')->get_comments($itemInfo['id'], $_GET['page'], 100);
        }

        if ($comments AND $this->user_id) {
            foreach ($comments AS $key => $val) {
                $comments[$key]['vote_info'] = $this->model('article')->getVoteByArticleId('comment', $val['id'], 1, $this->user_id);
                $comments[$key]['message'] = $this->model('question')->parse_at_user($val['message']);

            }
        }

        if ($this->user_id)
        {
            View::assign('user_follow_check', $this->model('follow')->user_follow_check($this->user_id, $itemInfo['uid']));
        }

        View::assign('question_related_list', $this->model('question')->get_related_question_list(null, $itemInfo['title']));

        $this->model('article')->update_views($itemInfo['id']);

        View::assign('comments', $comments);
        View::assign('comments_count', $itemInfo['comments']);

        View::assign('human_valid', human_valid('answer_valid_hour'));

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/article/id-' . $itemInfo['id']),
            'total_rows' => $itemInfo['comments'],
            'per_page' => 100
        ))->create_links());

        View::set_meta('keywords', implode(',', $this->model('system')->analysis_keyword($itemInfo['title'])));

        View::set_meta('description', $itemInfo['title'] . ' - ' . cjk_substr(str_replace("\r\n", ' ', strip_tags($itemInfo['message'])), 0, 128, 'UTF-8', '...'));

        View::assign('attach_access_key', md5($this->user_id . time()));

        $recommend_posts = $this->model('posts')->get_recommend_posts_by_topic_ids($article_topic_ids);

        if ($recommend_posts) {
            foreach ($recommend_posts as $key => $value) {
                if ($value['id'] AND $value['id'] == $itemInfo['id']) {
                    unset($recommend_posts[$key]);

                    break;
                }
            }

            View::assign('recommend_posts', $recommend_posts);
        }

        View::output('course/index');
    }

    public function index_square_action()
    {
        if (isset($_FILES, $_FILES['attach'], $_FILES['attach']['tmp_name'])
           && is_file($_FILES['attach']['tmp_name']) && $_FILES['attach']['type']=='application/pdf' ) {
            //var_dump($_FILES['attach']);
            $tmpDir = TEMP_PATH . 'ocr/' . uniqid(date('Ymd-').rand(1, 100000));
            mkdir($tmpDir, 0777, true);
            $destination = $tmpDir . '/' .basename($_FILES['attach']['tmp_name']) . '.pdf';
            move_uploaded_file($_FILES['attach']['tmp_name'], $destination);
            //echo system('pdf2image');
            $cwd = getcwd();
            chdir($tmpDir);
            $command = sprintf(Application::config()->get('aliyun')->commandConvertPdfToPng, $destination);
            exec($command, $output, $return);
            //var_dump($command, $output, $return);
            chdir($cwd);
            $images = glob(realpath($tmpDir) . '/*.png');
            //var_dump($images);
            $appKey    = Application::config()->get('aliyun')->appKey;
            $appSecret = Application::config()->get('aliyun')->appSecret;
            $aliyunRequester = & loadClass('Aliyun_ApiCurlRequest', ['appKey'=>$appKey, 'appSecret'=>$appSecret]);
            foreach ($images as $_imageFile) {
                $response = $aliyunRequester->ocrAdcanced($_imageFile);
                if ($response->getHttpStatusCode()=='200' && $response->getBody()) {
                    $response = json_decode($response->getBody(), true);

                    $var = var_export($response, true);
                    error_log($var, 3, $_imageFile.'.php');
                    //var_dump(json_decode($response->getBody(), true) );
                }
            }
        }
        View::assign('article_list', $article_list);

        View::output('test/square');
    }

    public function build_ocr_text_action ()
    {
        $newOcrText = array();
        $ocrText = require(TEMP_PATH . 'ocr/20180901-501005b8a4799115f8/aliyunOcr-000001.png.php');
        $ocrText = require(TEMP_PATH . 'ocr/20180901-501005b8a4799115f8/aliyunOcr-000002.png.php');
        $ocrText = require(TEMP_PATH . 'ocr/20180901-754335b8a4f35aa212/aliyunOcr-000001.png.php');
        //$ocrText = require(TEMP_PATH . 'ocr/20180901-754335b8a4f35aa212/aliyunOcr-000002.png.php');
        $ocrText = $ocrText['prism_wordsInfo'];
        foreach ($ocrText as $_key => $_ocrInfo) {
            $minX = $minY = null;
            $maxX = $maxY = null;
            foreach ($_ocrInfo['pos'] as $_posInfo) {
                if (! isset($minX)) {
                    $minX = $_posInfo['x'];
                    $minY = $_posInfo['y'];
                    $maxX = $_posInfo['x'];
                    $maxY = $_posInfo['y'];
                }
                $minX = $_posInfo['x'] > $minX ? $minX : $_posInfo['x'];
                $minY = $_posInfo['y'] > $minY ? $minY : $_posInfo['y'];
                $maxX = $_posInfo['x'] > $maxX ? $_posInfo['x'] : $maxX;
                $maxY = $_posInfo['y'] > $maxY ? $_posInfo['y'] : $maxY;
            }
            $ocrText[$_key]['pos'] = ['minX'=>$minX, 'minY'=>$minY, 'maxX'=>$maxX, 'maxY'=>$maxY, 'size'=>$maxY-$minY];
        }

        //var_dump($ocrText);
        $textBlock = [];
        $i = count($ocrText);
        for ($j=0; $j<$i; $j++) {
            if (! $textBlock) {
                $textBlock[0] = [$ocrText[$j]];
                continue;
            }

            foreach ($textBlock as $_k => $_blocks) {
                foreach ($_blocks as $_block) {
                    // 合并 同一行 ？，两条数据 y轴坐标差不多 < 5px ？，a的x轴末尾坐标 和 b的x轴开头坐标距离不超过 3个字距离；
                    // 两条数据的字体大小， 差不多。 因为是识别的字体， 字体大小有偏差
                    // 合并后，将最大坐标位置， 需要重新计算 ？
                    if ($ocrText[$j]['pos']['maxY'] - abs($_block['pos']['maxY'])  < 5
                     && abs($ocrText[$j]['pos']['minX'] - $_block['pos']['maxX']) < $_block['pos']['size'] * 3
                     && abs($_block['pos']['size'] - $_block['pos']['size']) / $_block['pos']['size'] < 1/8
                     ) {
                          $textBlock[$_k][] = $ocrText[$j];
                          continue 3;
                    }


                    // 合并同一段落？ 两条数据， x轴开头位置距离不超过3个字距离， a的y轴最大坐标 和 b 的y轴最小坐标， 在1个字范围
                    if (abs($ocrText[$j]['pos']['minY'] - $_block['pos']['maxY'])  < $_block['pos']['size'] * 1.5
                     && abs($ocrText[$j]['pos']['minX'] - $_block['pos']['minX'])  < $_block['pos']['size'] * 3
                     && abs($_block['pos']['size'] - $_block['pos']['size']) / $_block['pos']['size'] < 1/8
                     ) {
                         $textBlock[$_k][] = $ocrText[$j];
                         continue 3;
                    }

                }
            }

            $textBlock[] = [$ocrText[$j]];

        }

        foreach ($textBlock as & $_blocks) {
            $text = '';
            foreach ($_blocks as $_block) {
                $text .= trim($_block['word'], '.·');
            }
            $_blocks = $text;
        }

        var_dump($textBlock);
        View::output('test/square');
    }
}
