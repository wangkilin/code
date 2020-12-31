<?php
class main extends BaseController
{
    public function get_access_rule()
    {
        $rule_action['rule_type'] = "black"; //'black'黑名单,黑名单中的检查  'white'白名单,白名单以外的检查

        //$rule_action['actions'][] = 'index';
        //$rule_action['actions'][] = 'statistic';

        return $rule_action;
    }

    public function setup()
    {
    }

    public function index_action()
    {
        View::import_js('admin/js/echarts.js');
        // 获取最后一天的日期
        $maxDate = $this->model()->fetch_one('stock_lugutong','max(belong_date)', 'code = "' . $this->model()->quote($_GET['id']) . '"');
        if (! $maxDate) {
            HTTP::redirect('/lugutong/index_square/');
        }
        // 默认展示最后一天的数据
        $list = $this->model()->fetch_all('stock_lugutong', 'code = "' . $this->model()->quote($_GET['id']) . '" AND belong_date > SUBDATE("'.$maxDate.'", INTERVAL 1 MONTH)', 'belong_date DESC');
        if (! $list) {
            HTTP::redirect('/lugutong/index_square/');
        }
        View::assign('lastDate', $maxDate);
        View::assign('list', $list);
        View::output('stock/lugutong/statistic');
    }

    public function statistic_action ()
    {
        $this->index_action();
    }

    /**
     *
     */
    public function index_square_action ()
    {
        if (isset($_GET['date']) && date('Ymd',strtotime($_GET['date'])) == $_GET['date']) {
            // 获取最后一天的日期
            $maxDate = $this->model()->fetch_one('stock_lugutong','max(belong_date)', 'belong_date <= "' . $_GET['date'] . '"');
        } else {
            // 获取最后一天的日期
            $maxDate = $this->model()->fetch_one('stock_lugutong','max(belong_date)');
        }
        // 默认展示最后一天的数据
        $list = $this->model()->fetch_all('stock_lugutong', 'belong_date = "' . $maxDate . '"', 'code ASC');
        $codeList = array_column($list, 'code');
        $list = array_combine($codeList, $list);
        $prevDate = $this->model()->fetch_one('stock_lugutong','max(belong_date)', 'belong_date < "' . $maxDate .'"');
        // 默认展示最后一天的数据
        $prevDateList = $this->model()->fetch_all('stock_lugutong', 'belong_date = "' . $prevDate . '"', 'code ASC');
        $codeList = array_column($prevDateList, 'code');
        $prevDateList = array_combine($codeList, $prevDateList);
        $stockList = $this->model()->fetch_all('stock_code', 'belong_date = "' . $prevDate . '"');
        $codeList = array_column($stockList, 'code');
        $stockList = array_combine($codeList, $stockList);
        // 变换股票名称。 在香港网站的股票名称，有的和内地的名称不一致
        foreach($list as $_codeKey=>$_item) {
            $list[$_codeKey]['name'] = $stockList[$_codeKey]['name'];
        }

        View::assign('selectDate', $maxDate);
        View::assign('list', $list);
        View::assign('prevDateList', $prevDateList);
        View::output('stock/lugutong/list');
    }
}

/* EOF */
