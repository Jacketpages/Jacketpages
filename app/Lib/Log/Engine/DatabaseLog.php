<?php
/**
 * @author Stephen Roca
 * @since 8/12/2013
 *
 * This is a database logger mainly used to store searched terms for analytic
 * purposes.
 */

App::uses('CakeLogInterface', 'Log');
class DatabaseLog implements CakeLogInterface
{
	public function __construct($options = array())
	{
		App::import('Model', 'Search');
		$this -> Log = new Search;
		$this -> scopes = $options['scopes'];
	}

	public function write($type, $message, $scope)
	{
		if (isset($this -> scopes) && in_array($scope, $this -> scopes))
		{
			$message = trim(strtolower($message));
			$count = $this -> Log -> find('count', array('conditions' => array('term' => $message)));
			if ($count > 0)
			{
				$data = $this -> Log -> find('first', array('conditions' => array('term' => $message)));
				$data['Search']['occurrences'] += 1;
				unset($data['Search']['last_searched']);
				$this -> Log -> save($data);
			}
			else
			{
				$this -> Log -> set('term', $message);
				$this -> Log -> set('occurrences', 1);
				$this -> Log -> save();
			}
		}
	}

}
