<table class="listing">
    <?php
    echo $this->Html->tableheaders(array(
        'Number',
        'Title',
        'GLR'
    ), array('class' => 'links'));

    for ($i = 0; $i < sizeof($bills); $i++) {
        $bill = $bills[$i];
        //$bill_total = $bill_totals[$i][0];

        echo $this->Html->tableCells(array(
            $this->Html->link($bill['Bills']['number'], array(
                'controller' => 'bills',
                'action' => 'view',
                $bill['Bills']['id']
            )),
            $this->Html->link($bill['Bills']['title'], array(
                'controller' => 'bills',
                'action' => 'view',
                $bill['Bills']['id']
            )),
            $this->Number->currency($bill['Bills']['glr'])
        ));
    }
    ?>
</table>