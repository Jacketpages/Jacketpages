<?php

// I would use Html::nestedList but the li's in the new theme don't all have the same class
// TODO: extend HTML helper to support this

echo $this->Html->div(null, null, array('id'=>'main-menu-wrapper'));

echo $this->Html->useTag('tagstart', 'ul', array('class'=>'menu'));

echo $this->Html->tag('li', $this->element('mainMenuBar/bills'), array('class' => 'expanded first'));
	echo $this->Html->tag('li', $this->element('mainMenuBar/organizations'),      array('class'=>'expanded'));
echo $this->Html->tag('li', $this->element('mainMenuBar/resources'), array('class' => 'expanded'));
	echo $this->Html->tag('li', $this->element('mainMenuBar/student_government'), array('class'=>'expanded'));
	echo $this->Html->tag('li', $this->element('mainMenuBar/administration'),     array('class'=>'expanded'));
echo $this->Html->tag('li', $this->element('mainMenuBar/account'), array('class' => 'expanded'));
	echo $this->Html->tag('li', $this->element('mainMenuBar/help'),               array('class'=>'leaf last'));
	
echo $this->Html->useTag('tagend', 'ul');

echo $this->Html->useTag('tagend', 'div');

?>
	