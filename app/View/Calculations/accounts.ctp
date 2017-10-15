<?php
/**
 * @author Decker Onken
 * @since 10/05/2017
 */

$this->Html->addCrumb('Account Summaries', '/calculations');
$this->extend("/Common/list");
$this->assign("title", "FY" . $fy . " Account Summaries");
$this->start('search');

?>

    <div id="accordion">
        <a href="#">Fiscal Year</a>
        <div>
            <div style="float: left; width: 45%;">
                <ul>
                    <?php
                    echo $this->Form->create('Accounts');
                    echo $this->Form->input('fy', array(
                        'label' => 'FY',
                        'options' => $fys /*array(
						1 => 'FY14',
						2 => 'FY15',
						3 => 'FY16',
						4 => 'FY17',
						5 => 'FY18' //TODO make scalable
					)*/
                    ));
                    ?>
                </ul>
            </div>
            <div style="float: right; width: 45%;">
                <?php
                echo $this->Form->submit('Submit', array('div' => array('style' => 'display:inline-block')));
                echo $this->Form->submit('Clear', array(
                    'div' => array('style' => 'display:inline-block'),
                    'name' => 'submit'
                ));
                ?>
            </div>
            <div style="clear:both"></div>
        </div>
    </div>

    <div id="middle_full">
        <?php

        use Ghunti\HighchartsPHP\Highchart;
        use Ghunti\HighchartsPHP\HighchartJsExpr;
        use Ghunti\HighchartsPHP\HighchartOption;

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
            'size' => "100%%"
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
        //------------------------------------------------------------------------
        //Line Graph

        $lineChart = new Highchart();

        $lineChart->chart = array(
            'renderTo' => 'lineGraph',
            'type' => 'line',
            'marginRight' => 175,
            'marginBottom' => 75
        );

        $lineChart->plotOptions->series = array(
            'connectNulls' => true
        );

        $lineChart->title = array(
            'text' => 'Joint Account Balances during FY' . $fy,
            'x' => -20
        );

        $lineChart->xAxis->categories = array_column($tuesdays, 'date_nonzero');

        /*$lineChart->yAxis = array(
            'title' => array(
                'text' => 'Balance ($)'
            ),
            'plotLines' => array(
                array(
                    'value' => 0,
                    'width' => 1,
                    'color' => '#808080'
                )
            ),
            array(
                'title' => array(
                    'text' => 'Balance ($)'
                ),
                'plotLines' => array(
                    array(
                        'value' => 0,
                        'width' => 1,
                        'color' => '#808080'
                    )
                )
            )
        );*/
        $leftYaxis = new HighchartOption();
        $rightYaxis = new HighchartOption();

        $leftYaxis->labels->formatter = new HighchartJsExpr("function() {
    return '$' + this.value.toLocaleString(); }");
        $rightYaxis->labels->formatter = new HighchartJsExpr("function() {
    return '$' + this.value.toLocaleString(); }");

        $leftYaxis->title->text = "Prior Year ($)";
        $leftYaxis->labels->style->color = "#EEB211";//#89A54E";
        $leftYaxis->title->style->color = "#EEB211";//#89A54E";
        $leftYaxis->min = 0;

        $rightYaxis->title->text = "Capital Outlay ($)";
        $rightYaxis->title->style->color = "#072f59";
        $rightYaxis->labels->style->color = "#072f59";
        $rightYaxis->min = 0;
        $rightYaxis->opposite = 1;

        $lineChart->yAxis = array(
            $leftYaxis,
            $rightYaxis
        );

        $lineChart->legend = array(
            'layout' => 'vertical',
            'align' => 'right',
            'verticalAlign' => 'top',
            'x' => -10,
            'y' => 100,
            'borderWidth' => 0
        );


        $lineChart->series[] = array(
            'name' => 'PY',
            'data' => array_column($tuesdays, 'py_balance'),
            'color' => "#EEB211"
        );
        $lineChart->series[] = array(
            'name' => 'CO',
            'data' => array_column($tuesdays, 'co_balance'),
            'color' => "#072f59",
            'yAxis' => 1,
        );

        $lineChart->tooltip->formatter = new HighchartJsExpr(
            "function() { return '<b>'+ this.series.name +' Balance</b><br/>'+ this.x +': $'+ this.y.toLocaleString();}");
        ?>

        <div style="text-align: center; width: 100%">
            <div id="pyChart" style="min-width: 191px; height: 225px; width:25%; display:inline-block; float: left;"></div>
            <div id="coChart" style="min-width: 191px; height: 225px; width:25%; display:inline-block; float: left;"></div>
            <div id="ulrChart" style="min-width: 191px; height: 225px; width:25%; display:inline-block; float: left;"></div>
            <div id="glrChart" style="min-width: 191px; height: 225px; width:25%; display:inline-block; float: left;"></div>
        </div>

        <?php
        echo $this->Html->tag('h1', 'FY' . $fy . ' Allocation over Time');
        ?>

        <div id="lineGraph"> <!--style="height:600px; width: 100%"-->
        </div>

        <script src="http://code.highcharts.com/highcharts.src.js"></script>
        <script type="text/javascript">
            $(function () {
                $("#accordion").accordion({
                    collapsible: true,
                    heightStyle: "content",
                    <?php echo (isset($openAccordion) && $openAccordion) ? '' : 'active : false'; // if $openAccordion is true, open it. default close ?>
                });
            });

            <?php echo $chart->render("chart1"); ?>
            <?php echo $coChart->render("chart2"); ?>
            <?php echo $ulrChart->render("chart3"); ?>
            <?php echo $glrChart->render("chart4"); ?>
            <?php echo $lineChart->render("lineCHart"); ?>

            //Remove the Highcharts logo and link in the bottom right
            var e = document.getElementsByClassName('highcharts-credits');
            [...e
            ].forEach(remove);

            function remove(item) {
                item.outerHTML = "";
            }
        </script>
</div>

<?php
$this->end();
?>