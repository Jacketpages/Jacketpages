<div id="middle_full">
    <?php
    use Ghunti\HighchartsPHP\Highchart;
    use Ghunti\HighchartsPHP\HighchartJsExpr;

    echo $this->Html->tag('h1', 'Current FY' . $fy . ' Account Summary');

    $chart = new Highchart();
    $chart->chart->renderTo = "pyChart";
    $chart->chart->type = "pie";
    $chart->title->text = "Prior Year";
    $chart->plotOptions->pie->shadow = false;
    $chart->tooltip->formatter = new HighchartJsExpr(
        "function() {
    return '<b>' + this.point.name + '</b>: $' + this.y.toLocaleString(); }");
    $data = array(
        array(
            'name' => 'Remaining',
            'y' => (int)($totals['initial']['py'] - $totals['allocated']['py']),
            'color' => '#E6E6E6'
        ),
        array(
            'name' => 'Allocated',
            'y' => (int)$totals['allocated']['py'],
            'color' => '#072f59'
        ),
    );
    $chart->series[] = array(
        'name' => "PY",
        'data' => $data,
        'size' => "100%"
    );
    //----------------------------------------------------------------------
    $coChart = new Highchart();
    $coChart->chart->renderTo = "coChart";
    $coChart->chart->type = "pie";
    $coChart->title->text = "Capital Outlay";
    $coChart->plotOptions->pie->shadow = false;
    $coChart->tooltip->formatter = new HighchartJsExpr(
        "function() {
        return '<b>' + this.point.name + '</b>: $' + this.y.toLocaleString(); }");
    $coData = array(
        array(
            'name' => 'Remaining',
            'y' => (int)($totals['initial']['co'] - $totals['allocated']['co']),
            'color' => '#E6E6E6'
        ),
        array(
            'name' => 'Allocated',
            'y' => (int)$totals['allocated']['co'],
            'color' => '#072f59'
        ),
    );
    $coChart->series[] = array(
        'name' => "CO",
        'data' => $coData,
        'size' => "100%"
    );
    //----------------------------------------------------------------------
    $ulrChart = new Highchart();
    $ulrChart->chart->renderTo = "ulrChart";
    $ulrChart->chart->type = "pie";
    $ulrChart->title->text = "ULR";
    $ulrChart->plotOptions->pie->shadow = false;
    $ulrChart->tooltip->formatter = new HighchartJsExpr(
        "function() {
            return '<b>' + this.point.name + '</b>: $' + this.y.toLocaleString(); }");
    $ulrData = array(
        array(
            'name' => 'Remaining',
            'y' => (int)($totals['initial']['ulr'] - $totals['allocated']['ulr']),
            'color' => '#E6E6E6'
        ),
        array(
            'name' => 'Allocated',
            'y' => (int)$totals['allocated']['ulr'],
            'color' => '#072f59'
        ),
    );
    $ulrChart->series[] = array(
        'name' => "ULR",
        'data' => $ulrData,
        'size' => "100%"
    );
    //----------------------------------------------------------------------
    $glrChart = new Highchart();
    $glrChart->chart->renderTo = "glrChart";
    $glrChart->chart->type = "pie";
    $glrChart->title->text = "GLR";
    $glrChart->plotOptions->pie->shadow = false;
    $glrChart->tooltip->formatter = new HighchartJsExpr(
        "function() {
                return '<b>' + this.point.name + '</b>: $' + this.y.toLocaleString(); }");
    $glrData = array(
        array(
            'name' => 'Remaining',
            'y' => (int)($totals['initial']['glr'] - $totals['allocated']['glr']),
            'color' => '#E6E6E6'
        ),
        array(
            'name' => 'Allocated',
            'y' => (int)$totals['allocated']['glr'],
            'color' => '#072f59'
        ),
    );
    $glrChart->series[] = array(
        'name' => "GLR",
        'data' => $glrData,
        'size' => "100%"
    );
    ?>

    <table>
        <col width="25%">
        <col width="25%">
        <col width="25%">
        <col width="25%">
        <tr>
            <td style="vertical-align:middle">
                <div id="pyChart"></div>
            </td>
            <td style="vertical-align:middle">
                <div id="coChart"></div>
            </td>
            <td style="vertical-align:middle">
                <div id="ulrChart"></div>
            </td>
            <td style="vertical-align:middle">
                <div id="glrChart"></div>
            </td>
        </tr>

    </table>

    <?php
    echo $this->Html->tag('h1', 'FY' . $fy . ' Allocation over Time');
    ?>

    <script src="http://code.highcharts.com/highcharts.src.js"></script>
    <script type="text/javascript">
        <?php echo $chart->render("chart1"); ?>
        <?php echo $coChart->render("chart2"); ?>
        <?php echo $ulrChart->render("chart3"); ?>
        <?php echo $glrChart->render("chart4"); ?>
    </script>
</div>