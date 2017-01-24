<?php
if ($lace)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		$this -> Paginator -> sort('NAME', 'Name'),
		'OrgSync',/*
		'Status',
		array('Forms Date' => array('width' => '85px')),
		array('Advisor Date' => array('width' => '85px')),
		array('Constitution Date' => array('width' => '110px')),*/
		''
		//''
	), array('class' => 'links'));
	foreach ($organizations as $organization)
	{
		$summary = $organization['Organization']['website'];
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
            $this -> Html -> link(
                $summary,
                $summary,
                ['class' => 'button', 'target' => '_blank']
            ),
			/*$organization['Organization']['status'],
			$organization['Organization']['alcohol_form'],
			$organization['Organization']['advisor_date'],
			$organization['Organization']['constitution_date'],*/
			$this -> Html -> link(__('Edit', true), array(
				'action' => 'edit',
				$organization['Organization']['id']
			))
			/*
			$this -> Html -> link(__('Delete', true), array(
				'action' => 'delete',
				$organization['Organization']['id']
			), null, sprintf(__('Are you sure you want to delete this organization?', true)))
			*/
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
	), array('class' => 'links'));
	foreach ($organizations as $organization)
	{
		//$logo = $this -> Html -> image($organization['Organization']['logo_path'], array('style' => 'width:60px;max-width:none;'));
		$summary = $organization['Organization']['website'];
		//$summary = Sanitize::html($summary, array('remove' => TRUE));
		if (strlen($summary) > 200)
		{
			$summary = substr($summary, 0, strrpos(substr($summary, 0, 200), ' ')) . '...';
		}
		echo $this -> Html -> tableCells(array(
			//$logo,
			$this -> Html -> link($organization['Organization']['name'], array(
				'action' => 'view',
				$organization['Organization']['id']
			)),
            $this -> Html -> link(
                $summary,
                $summary,
                ['class' => 'button', 'target' => '_blank']
            ))
		);
	}
	echo $this -> Html -> tableEnd();
}
?>