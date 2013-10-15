<?php
/**
 * @author Stephen Roca
 * @since 10/08/2012
 */
App::import('model', 'Bill');
class BillAuthor extends AppModel
{
	public $belongsTo = array(
		'GradAuthor' => array(
			'className' => 'user',
			'foreignKey' => 'grad_auth_id'
		),
		'UndrAuthor' => array(
			'className' => 'user',
			'foreignKey' => 'undr_auth_id'
		)
	);

	public $validate = array(
		'undr_auth_id' => array(
			'rule' => array('authors'),
			'message' => 'You must specify an author.'
		),
		'grad_auth_id' => array(
			'rule' => array('authors'),
			'message' => 'You must specify an author.'
		),
	);

	public function authors($check)
	{
		$valid = true;
		if (isset($this -> data['Authors']))
		{
			if (key($check) == 'undr_auth_id')
			{
				if ($check['undr_auth_id'] == 0 && $this -> data['Authors']['category'] == 'Undergraduate')
				{
					$valid = false;
				}
			}
			else
			{
				if ($check['grad_auth_id'] == 0 && $this -> data['Authors']['category'] == 'Graduate')
				{
					$valid = false;
				}
			}
			if ($this -> data['Authors']['category'] == 'Joint')
			{
				if (key($check) == 'undr_auth_id' && $this -> data['Authors']['undr_auth_id'] == 0)
				{
					$valid = false;
				}
				else if (key($check) == 'grad_auth_id' && $this -> data['Authors']['grad_auth_id'] == 0)
				{
					$valid = false;
				}
			}
		}
		return $valid;
	}

}
?>
