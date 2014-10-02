<?php

class TimeboundResourcesController extends AppController
{

	public function add()
	{
		if ($this -> isSGAExec())
		{
			if ($this -> request -> is('post'))
			{
				$this -> request -> data['TimeboundResource']['name'] = 'Budget';
				$this -> request -> data['TimeboundResource']['alias'] = '20' . ($this -> getFiscalYear() + 2);
				$this -> TimeboundResource -> create();
				if ($this -> TimeboundResource -> save($this -> request -> data))
				{

				}
			}
			$db = ConnectionManager::getDataSource('default');
			$this -> set('budgetWindow', $this -> TimeboundResource -> find('first', array(
				'conditions' => array('name' => 'Budget'),
				'order' => array('TimeboundResource.end_time desc')
			)));
		}
		else {
			$this ->redirect($this ->referer());
		}
	}

	public function delete($id)
	{
		if ($id != null && $this -> isSGAExec())
		{
			$this -> TimeboundResource -> delete($id);
		}
		$this -> redirect(array('action' => 'add'));
	}

}
