<?php
/**
 * @author Stephen Roca
 * @since 06/30/2012
 */
$this->extend("/Common/common");
$this->start('sidebar');
$this->end();
$this->assign("title", "Resources");
$this->Html->addCrumb('Resources', '/resources');
$this->start('middle');

?>
<script>

</script>
<br>

<!--Utilizing 3rd party service CloudWard to pull info from Google Docs for Resources-->
<div id='my_div1'>
    <style scoped>
        @import '../css/reset_full.css'; /*ensures the css pulled from google sheets isnt overrided by the other css in jacketpages template*/
    </style>
    <div id='my_div'>
    </div>
</div>

<script src="../js/jquery.scoped.js"></script>
<script type="text/javascript">
    var ease_processor_response_element_id = 'my_div';
    document.write(unescape("%3Cscript src='https://snippets.cloudward.com/process?"
        + "cloudward_snippet_id=eca6eef832654fb9564ef9fd964a2969&"
        + escape(window.location.search.split("?")[1]) + "' type='text/javascript'%3E%3C/script%3E"));
    setTimeout("$.scoped()", 2000)
</script>

<!--<iframe src="https://docs.google.com/document/d/1KF2F-yRNgTBpk8LDkdw0VtzoQygcO1y0D-yCoxKkaxM/pub?embedded=true" width="100%" height="1000" frameborder="0"></iframe>-->

<?php
$this->end();
?>
