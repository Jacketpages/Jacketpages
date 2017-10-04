<table class="listing">
    <?php
    echo $this->Html->tableheaders(array(
        'Number',
        'Title',
        'Org',
        'PY',
        'CO',
        'Total'
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
            $this->Html->link($bill['Bills']['org_name'], array(
                'controller' => 'organizations',
                'action' => 'view',
                $bill['Bills']['org_id']
            )),
            $this->Number->currency($bill['Bills']['py']),
            $this->Number->currency($bill['Bills']['co']),
            $this->Number->currency($bill['Bills']['tot'])
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
    //TODO - Add custom sort as pagination wont work due to the information from 3 tables
</script>