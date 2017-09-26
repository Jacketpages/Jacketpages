<table class="listing">
    <?php
    echo $this->Html->tableheaders(array(
        $this->Paginator->sort('number', 'Number'),
        $this->Paginator->sort('title', 'Title'),
        $this->Paginator->sort('org', 'Org'),
        $this->Paginator->sort('py', 'PY'),
        $this->Paginator->sort('co', 'CO'),
        $this->Paginator->sort('tot', 'Total')
    ), array('class' => 'links'));

    for ($i = 0; $i < sizeof($bills); $i++) {
        $bill = $bills[$i];
        $bill_total = $bill_totals[$i][0];

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
            $this->Html->link($bill['Organizations']['name'], array(
                'controller' => 'organizations',
                'action' => 'view',
                $bill['Bills']['org_id']
            )),
            $this->Number->currency($bill_total['PY']),
            $this->Number->currency($bill_total['CO']),
            $this->Number->currency($bill_total['TOTAL'])
        ));
    }
    ?>
</table>
<script>
    $(function () {
        $("#accordion").accordion({
            collapsible: true,
            heightStyle: "content",
            <?php echo (isset($openAccordion) && $openAccordion) ? '' : 'active : false'; // if $openAccordion is true, open it. default close ?>
        });
    });
</script>