<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   ========================================
|   by WeCenter Software
|   © 2011 - 2014 WeCenter. All Rights Reserved
|   http://www.wecenter.com
|   ========================================
|   Support: WeCenter@qq.com
|
+---------------------------------------------------------------------------
*/

if (! defined('iCodeBang_Com'))
{
    die();
}

class article extends AdminController
{
    public function setup()
    {
        View::assign('menu_list', $this->model('admin')->fetch_menu_list('article/list'));
    }

    public function list_action()
    {
        if ($this->is_post())
        {
            foreach ($_POST as $key => $val)
            {
                if ($key == 'start_date' OR $key == 'end_date')
                {
                    $val = base64_encode($val);
                }

                if ($key == 'keyword' OR $key == 'user_name')
                {
                    $val = rawurlencode($val);
                }

                $param[] = $key . '-' . $val;
            }

            H::ajax_json_output(Application::RSM(array(
                'url' => get_js_url('/admin/article/list/' . implode('__', $param))
            ), 1, null));
        }


        $where = array();

        if ($_GET['keyword'])
        {
            $where[] = "(`title` LIKE '%" . $this->model('article')->quote($_GET['keyword']) . "%')";
        }

        if ($_GET['start_date'])
        {
            $where[] = 'add_time >= ' . strtotime(base64_decode($_GET['start_date']));
        }

        if ($_GET['end_date'])
        {
            $where[] = 'add_time <= ' . strtotime('+1 day', strtotime(base64_decode($_GET['end_date'])));
        }

        if ($_GET['user_name'])
        {
            $user_info = $this->model('account')->get_user_info_by_username($_GET['user_name']);

            $where[] = 'uid = ' . intval($user_info['uid']);
        }

        if ($_GET['comment_count_min'])
        {
            $where[] = 'comments >= ' . intval($_GET['comment_count_min']);
        }

        if ($_GET['answer_count_max'])
        {
            $where[] = 'comments <= ' . intval($_GET['comment_count_max']);
        }

        if ($articles_list = $this->model('article')->fetch_page('article', implode(' AND ', $where), 'id DESC', $_GET['page'], $this->per_page))
        {
            $search_articles_total = $this->model('article')->found_rows();
        }

        if ($articles_list)
        {
            foreach ($articles_list AS $key => $val)
            {
                $articles_list_uids[$val['uid']] = $val['uid'];
            }

            if ($articles_list_uids)
            {
                $articles_list_user_infos = $this->model('account')->getUsersByIds($articles_list_uids);
            }

            foreach ($articles_list AS $key => $val)
            {
                $articles_list[$key]['user_info'] = $articles_list_user_infos[$val['uid']];
            }
        }

        $url_param = array();

        foreach($_GET as $key => $val)
        {
            if (!in_array($key, array('app', 'c', 'act', 'page')))
            {
                $url_param[] = $key . '-' . $val;
            }
        }

        View::assign('pagination', Application::pagination()->initialize(array(
            'base_url' => get_js_url('/admin/article/list/') . implode('__', $url_param),
            'total_rows' => $search_articles_total,
            'per_page' => $this->per_page
        ))->create_links());

        $this->crumb(Application::lang()->_t('文章管理'), 'admin/article/list/');

        View::assign('articles_count', $search_articles_total);
        View::assign('list', $articles_list);

        View::output('admin/article/list');
    }
}