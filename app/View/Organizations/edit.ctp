<?php
/**
 * @author Stephen Roca
 * @since 06/21/2012
 */
// TODO grab the javascript from the admin_edit.ctp from JP and implement it here.
$this -> extend('/Common/common');
$this -> assign('title', 'Edit Organization');
$this -> start('middle');
debug($organization);
echo $this -> Form -> create('Organization');
echo $this -> Form -> hidden('ID');
echo $this -> Form -> input('NAME', array('label' => 'Name'));
echo $this -> Form -> input('STATUS',array('label' => 'Status', 'options' => array(
      'Active' => 'Active/Good Standing',
      'Inactive' => 'Inactive',
      'Under Review' => 'Under Review',
      'Pending' => 'Pending'
   )));
echo $this -> Form -> input('Category.NAME', array('label' => 'Category','options' => array(
      'CPC Sorority' => 'CPC Sorority',
      'Cultural/Diversity' => 'Cultural/Diversity',
      'Departmental Sponsored' => 'Departmental Sponsored',
      'Departments' => 'Departments',
      'Governing Boards' => 'Governing Boards',
      'Honor Society' => 'Honor Society',
      'IFC Fraternity' => 'IFC Fraternity',
      'Institute Recognized' => 'Institute Recognized',
      'MGC Chapter' => 'MGC Chapter',
      'None' => 'None',
      'NPHC Chapter' => 'NPHC Chapter',
      'Production/Performance/Publication' => 'Production/Performance/Publication',
      'Professional/Departmental' => 'Professional/Departmental',
      'Recreational/Sports/Leisure' => 'Recreational/Sports/Leisure',
      'Religious/Spiritual' => 'Religious/Spiritual',
      'Residence Hall Association' => 'Residence Hall Association',
      'Service/Political/Educational' => 'Service/Political/Educational',
      'Student Government' => 'Student Government',
      'Umbrella' => 'Umbrella'
   )));
echo $this -> Form -> input('User.NAME', array('label' => 'Primary Contact','id' => 'primary_contact'));
echo $this -> Form -> input('User.EMAIL', array('label' => 'Priamry Contact Email','id' => 'primary_email'));
echo $this -> Form -> input('DESCRIPTION', array('label' => 'Description','type' => 'textarea'));
echo $this -> Form -> input('WEBSITE', array('label' => 'Website'));
echo $this -> Form -> input('METNG_INFO', array('label' => 'Meeting Information'));
echo $this -> Form -> input('METNG_FREQU', array('label' => 'Meeting Frequency'));
echo $this -> Form -> input('ANNUL_EVNTS', array('label' => 'Annual Events'));
echo $this -> Form -> input('DUES', array('label' => 'Dues'));
?>
<div id='date'>
    <?php
   echo $this -> Form -> input('aadipf_date', array(
      'type' => 'text',
      'minYear' => date("Y") - 1,
      'maxYear' => date("Y"),
      'dateFormat' => 'MDY',
      'label' => 'Acknowledgement of Alcohol & Illegal Drug Policy Form Date',
      'id' => 'datepicker'
   ));
    ?>
</div>
</fieldset>
<?php echo $this -> Form -> end(__('Submit', true));?>
<script>
   $(function() {
      $( "#datepicker" ).datepicker({
         showButtonPanel: true
      });
   });
   </script>
   <?php
   $this -> end();
?>
