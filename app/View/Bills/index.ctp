<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
$this -> extend("/Common/list");
$this -> start('sidebar');
echo $this -> Html -> nestedList(array(
	$this -> Html -> link('Create New Bill', array('action' => 'add')),
	$this -> Html -> link('Export FY Data', array(
		'admin' => false,
		'action' => 'export'
	))
), array(), array('id' => 'underline'));
$this -> end();
$this -> assign("title", "Bills");
$this -> start('search');
?>
<div id="alphabet">
    <div id="leftHalf">
        <?php
      echo $this -> Form -> create();
      echo $this -> Form -> input('keyword', array(
         'label' => array('text' => 'Search', 'style' => 'display:inline'),
         'id' => 'search',
         'default' => $this -> Session -> read('Search.keyword'),
         'width' => '80%'
      ));
        ?>
    </div>
    <div id="rightHalf">
        <ul>
            <?php
            // TODO Clean up this whole alphabet thing. Is there an easier way?
            // set up alphabet
            $alpha = range('A', 'Z');
            for ($i = 0; $i < count($alpha); $i++)
            {
               echo "<li>\n";
               echo $this -> Html -> link($alpha[$i], array(
                  'controller' => strtolower($this -> params['controller']),
                  'action' => 'index',
                  strtolower($alpha[$i])
               ));
               echo "&nbsp";
               echo "</li>\n";
            }
            echo "<li>\n";
            echo $this -> Html -> link('ALL', array(
               'controller' => strtolower($this -> params['controller']),
               'action' => 'index'
            ));
            ?>
            </li>
        </ul>
    </div>
</div>
<?php
$this -> end();
$this -> start('listing');
echo $this -> element('bills\index\billsTable', array('bills' => $bills));
echo $this -> element('paging');
$this -> end();
?>