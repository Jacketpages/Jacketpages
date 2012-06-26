<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
 ?>
 <div class="paging">
    <?php echo $this -> Paginator -> prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled'));?>
    |
    <?php echo $this -> Paginator -> numbers();?>
    |
    <?php echo $this -> Paginator -> next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
    <br>
    <br>
    <?php
   echo $this -> Paginator -> counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of
{:count} total, starting on record {:start}, ending on {:end}', true)));
// Implement Ajax for this page.
echo $this -> Js -> writeBuffer();
    ?>
</div>