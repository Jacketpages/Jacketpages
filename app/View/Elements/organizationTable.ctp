<?php
if ($admin)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		$this -> Paginator -> sort('NAME', 'Name'),
		'Description',
		'Status',
		'AAIDPF Date',
		'',
		''
	), array('class' => 'links'));
	foreach ($organizations as $organization)
	{
		$summary = $organization['Organization']['description'];
		//$summary = Sanitize::html($summary, array('remove' => TRUE));
		if (strlen($summary) > 200)
		{
			$summary = substr($summary, 0, strrpos(substr($summary, 0, 200), ' ')) . '...';
		}
		echo $this -> Html -> tableCells(array(
			$this -> Html -> link($organization['Organization']['name'], array(
				'action' => 'view',
				$organization['Organization']['id']
			)),
			$summary,
			$organization['Organization']['status'],
			'',
			$this -> Html -> link(__('Edit', true), array(
				'action' => 'edit',
				$organization['Organization']['id']
			)), $this -> Html -> link(__('Delete', true), array(
				'action' => 'delete',
				$organization['Organization']['id']
			), null, sprintf(__('Are you sure you want to delete this organization?', true)))
		));
	}
	echo $this -> Html -> tableEnd();
}
else
{

	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'',
		$this -> Paginator -> sort('NAME', 'Name'),
		'Description',
		'',
		''
	), array('class' => 'links'));
	foreach ($organizations as $organization)
	{
		if (strlen($organization['Organization']['logo_path']) < 1)
		{
			$logo = $this -> Html -> image('/img/default_logo.gif', array('width' => '60'));
		}
		else
		{
			$logo = $this -> Html -> image('/organizations/getLogo/' . 
				$organization['Organization']['id']
			, array('width' => '60'));
		}
		$summary = $organization['Organization']['description'];
		//$summary = Sanitize::html($summary, array('remove' => TRUE));
		if (strlen($summary) > 200)
		{
			$summary = substr($summary, 0, strrpos(substr($summary, 0, 200), ' ')) . '...';
		}
		echo $this -> Html -> tableCells(array(
			$logo,
			$this -> Html -> link($organization['Organization']['name'], array(
				'action' => 'view',
				$organization['Organization']['id']
			)),
			$summary,
			$this -> Html -> link(__('Edit', true), array(
				'action' => 'edit',
				$organization['Organization']['id']
			)),
			$this -> Html -> link(__('Delete', true), array(
				'action' => 'delete',
				$organization['Organization']['id']
			), null, sprintf(__('Are you sure you want to delete this organization?', true)))
		));
	}
	echo $this -> Html -> tableEnd();
}
?>