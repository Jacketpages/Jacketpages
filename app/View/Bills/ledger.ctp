<?php
/**
 * @author Michael Ellis
 * @since 10/02/2013
 */
 $this -> Paginator -> options(array(
	'update' => '#forupdate',
	'indicator' => '#indicator',
	'evalScripts' => true,
	'before' => $this -> Js -> get('#listing') -> effect('fadeOut', array('buffer' => false)),
	'complete' => $this -> Js -> get('#listing') -> effect('fadeIn', array('buffer' => false)),
));
$this -> Html -> addCrumb($org_name, '/organizations/view/'.$org_id);
$this -> Html -> addCrumb('Finance Ledger', $this -> here);
$this -> extend("/Common/list");
$this -> start('sidebar');
$this -> end();
$this -> assign("title", "Finance Ledger");
$this -> start('listing');

if (empty($bills)) {
    echo $this->Html->tag('h1', $org_name . " has no passed bills.");
}

foreach($bills as $bill) {

    echo $this -> Html -> tag('h1', $bill['bill']['Bill']['number'] . " - " . $bill['bill']['Bill']['title']);

    $this->set('totals', $bill['totals']);
    $this->set('states', $bill['states']);

    // select the rightmost tab with line items
    /*if (!empty($bill['final'])) {
        $selectedIndex = 6;
    } else if (!empty($bill['conference'])) {
        $selectedIndex = 4;
    } else if (!empty($bill['undergraduate'])) {
        $selectedIndex = 3;
    } else if (!empty($bill['graduate'])) {
        $selectedIndex = 2;
    } else if (!empty($bill['jfc'])) {
        $selectedIndex = 1;
    } else if (!empty($bill['submitted'])) {
        $selectedIndex = 0;
    } else {
        // default, first tab
        $selectedIndex = 0;
    }*/

    $selectedIndex = 6;

    ?>
    <script>
        $(function () {
            $(".tabs").tabs();
            $(".tabs").tabs("option", "active", <?php echo $selectedIndex; ?>);
        });
    </script>
    <?php if ($bill['bill']['Bill']['type'] != 'Resolution') { ?>
        <div id="tabs" class="tabs">
            <ul>
                <li>
                    <a href="#tabs-1">Submitted</a>
                </li>
                <li>
                    <a href="#tabs-2">JFC</a>
                </li>
                <li>
                    <a href="#tabs-3">Graduate</a>
                </li>
                <li>
                    <a href="#tabs-4">Undergraduate</a>
                </li>
                <li>
                    <a href="#tabs-5">Conference</a>
                </li>
                <li>
                    <a href="#tabs-6">All</a>
                </li>
                <li>
                    <a href="#tabs-7">Final</a>
                </li>
            </ul>
            <?php
            echo $this->Html->tag('div', $this->element('lineItemDetails', array(
                'lineitems' => $bill['submitted'],
                'showAll' => 0,
                'first' => 1,
                'form_state' => 'Submitted'
            )), array('id' => 'tabs-1'));
            echo $this->Html->tag('div', $this->element('lineItemDetails', array(
                'lineitems' => $bill['jfc'],
                'showAll' => 0,
                'eligibleStates' => array('Submitted' => 'Submitted'),
                'form_state' => 'JFC'
            )), array('id' => 'tabs-2'));
            echo $this->Html->tag('div', $this->element('lineItemDetails', array(
                'lineitems' => $bill['graduate'],
                'showAll' => 0,
                'eligibleStates' => array(
                    'Submitted' => 'Submitted',
                    'JFC' => 'JFC',
                    'Undergraduate' => 'Undergraduate'
                ),
                'form_state' => 'Graduate'
            )), array('id' => 'tabs-3'));
            echo $this->Html->tag('div', $this->element('lineItemDetails', array(
                'lineitems' => $bill['undergraduate'],
                'showAll' => 0,
                'eligibleStates' => array(
                    'Submitted' => 'Submitted',
                    'JFC' => 'JFC',
                    'Graduate' => 'Graduate'
                ),
                'form_state' => 'Undergraduate'
            )), array('id' => 'tabs-4'));
            echo $this->Html->tag('div', $this->element('lineItemDetails', array(
                'lineitems' => $bill['conference'],
                'showAll' => 0,
                'eligibleStates' => array(
                    'Submitted' => 'Submitted',
                    'JFC' => 'JFC',
                    'Graduate' => 'Graduate',
                    'Undergraduate' => 'Undergraduate'
                ),
                'form_state' => 'Conference'
            )), array('id' => 'tabs-5'));
            echo $this->Html->tag('div', $this->element('lineItemDetails', array(
                'lineitems' => $bill['all'],
                'showAll' => 1
            )), array('id' => 'tabs-6'));
            echo $this->Html->tag('div', $this->element('lineItemDetails', array(
                'lineitems' => $bill['final'],
                'showAll' => 0,
                'eligibleStates' => array(
                    'Submitted' => 'Submitted',
                    'JFC' => 'JFC',
                    'Graduate' => 'Graduate',
                    'Undergraduate' => 'Undergraduate',
                    'Conference' => 'Conference'
                ),
                'form_state' => 'Final'
            )), array('id' => 'tabs-7'));
            ?>
        </div>
    <?php }
    }
//echo $this -> element('paging');
//echo $this -> Html -> tag('h1', 'Budgets');
$this -> end();
?>