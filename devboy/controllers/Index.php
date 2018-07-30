<?php
class Index extends Controller
{
	public function init ()
	{
		echo 'Class is ready!';
	}

	public function testAction ()
	{
	    $data = '<hr/> <a href=""><?php echo "hello world!"; ?></a>';
	    $this->show($data);
		echo 'Test action is called!';
		$config = & loadClass('Config');
		$model = & loadClass('Model', '', 'users');
		importClass('Db_Query');
		$query = array('action'=>'SELECT',
				       'from'  => 'system_setting',

		);
		$oQuery = new Db_Query($model->getDb(), $model->getPrefix());
		$query = $oQuery->select('system_setting')
		                ->where('id<20')
		                ->orWhere('id>?', 90)
		                ->where(array('id%7 = ?'=>0))
		                ->makeSql();
		echo $query;
		$result = $model->query($query);
		//$result = $model->query('SELECT * FROM aws_system_setting');
		var_dump($model->getDb(),$result);
		//$model = & loadClass('Model', '', $config->get('db'));
		$a = array('a'=>'a', 'b'=>'a', 2=>'a');
		$b = array('b'=>'b', 'c'=>'b', 2=>'b');
		//var_dump($a+$b);
		//var_dump(array_merge($a, $b));
		//var_dump($model->getDb());
		$this->display();
	}

	public function indexAction()
	{
		$this->load->view('welcome_message');
	}
}
