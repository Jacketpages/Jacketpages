<?php

/**
 * @author Stephen Roca
 * @since 07/02/2012
 */
class Resources extends AppModel
{
    public $validate = array(
        'name' => array('declared' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Must be numbers and letters and cannot be blank.',
        ),),
        'email' => array('declared' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Must be numbers and letters and cannot be blank.',
        ),),
    );
}

?>
