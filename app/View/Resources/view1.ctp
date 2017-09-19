<?php
/**
 * @author Decker Onken
 */
$this->extend("/Common/common");
$this->assign("title", "Guides/Documents");
$this->Html->addCrumb('Resources', '/resources');
$this->Html->addCrumb('Guides/Documents', $this->here);
$this->start('middle');
?>

<script>
    /*var firstTime;
     if (!firstTime) {
     $('#content').html(function(index,html){// There are CSS styles that apply to an div with class middle_full and this is the only way i can get it to stop applying random css stuff to the imported html.
     firstTime = true;
     return html;//.replace("middle_full", "middle_full2");
     });
     }*/
</script>

<script src="../js/jquery.scoped.js"></script>
<script type="text/javascript">
    var test;
    if (!test) {
        $(document).ready(function (e) { //I know this violates MVC but I don't care rn sorry
            $.get("https://docs.google.com/document/d/1KF2F-yRNgTBpk8LDkdw0VtzoQygcO1y0D-yCoxKkaxM/pub?embedded=true", function (data) {
                $("#embed").append(data.replace("<style ", "<style scoped ")); //Only fixes the issue in firefox
            });

            $('#content').html(function (index, html) {// There are CSS styles that apply to an div with class middle_full and this is the only way i can get it to stop applying random css stuff to the imported html.
                test = true;
                return html.replace("middle_full", "middle_full2").replace("https://www.google.com/url?q=", "");
            });

            setTimeout("$.scoped()", 50); //the @import call on the google doc html can take a little delay to process so it needs this timeout before it is scoped
        });
    }
</script>

<div id="embed" style="display: block !important; margin: auto !important">
    <style scoped>
        @import '../css/reset_full.css'; /*ensures the css pulled from google sheets isnt overrided by the other css in jacketpages template*/
    </style>
</div>


<script>
    //$.scoped();
</script>

<?php
$this->end();
?>
