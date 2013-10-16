<?php
/**
 * @author Stephen Roca
 * @since 10/29/2012
 */
$this -> start('css');
echo $this -> Html -> css('lineitems');
$this -> end();
$this -> start('script');
echo $this -> Html -> script('lineitems/lineitemvalidation');
echo $this -> Html -> script('validation/validation');
$this -> end();
$this -> extend('/Common/common');
$this -> assign('title', 'Line Items - ' . $titleState);
$this -> Html -> addCrumb('View Bill', '/bills/view/'.$bill_id);
$this -> Html -> addCrumb('Line Items', $this->here);
$this -> start('middle');
echo '<div id="notification">';
echo "If you intend on submitting many line items, you may want to save your updates periodically since your login session may time out.";
echo '</div>'; 
echo $this -> element('multi_enter_line_items');

echo $this -> Form -> submit('Submit', array(
		'formnovalidate',
		'onclick' => 'openToolTips()',
		'style' => 'float:left;'
));
$this -> end();
?>