<?php
/**
 * @author Stephen Roca
 * @since 06/21/2012
 */
// TODO grab the javascript from the admin_edit.ctp from JP and implement it here.
$this -> extend('/Common/common');
$this -> assign('title', 'Edit Organization');
$this -> Html -> addCrumb('All Organizations', '/organizations');
$this -> Html -> addCrumb('My Organizations', '/organizations/my_orgs/'.$this -> Session -> read('User.id'));
$this -> Html -> addCrumb($organization['Organization']['name'], '/organizations/view/'.$organization['Organization']['id']);
$this -> Html -> addCrumb('Edit Organization', $this->here);
$this -> start('middle');
echo $this -> Form -> create('Organization');
echo $this -> Form -> hidden('id');
echo $this -> Form -> input('name', array('label' => 'Name'));
echo $this -> Form -> input('status',array('label' => 'Status', 'options' => array(
      'Active' => 'Active/Good Standing',
      'Inactive' => 'Inactive',
      'Under Review' => 'Under Review',
      'Pending' => 'Pending'
   )));
echo $this -> Form -> input('Category.name', array('label' => 'Category','options' => array(
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
echo $this -> Form -> input('User.name', array('label' => 'Primary Contact','id' => 'primary_contact'));
echo $this -> Form -> input('User.email', array('label' => 'Priamry Contact Email','id' => 'primary_email'));
echo $this -> Form -> input('description', array('label' => 'Description','type' => 'textarea'));
echo $this -> Form -> input('website', array('label' => 'Website'));
echo $this -> Form -> input('meeting_info', array('label' => 'Meeting Information'));
echo $this -> Form -> input('meeting_frequency', array('label' => 'Meeting Frequency'));
echo $this -> Form -> input('annual_events', array('label' => 'Annual Events'));
echo $this -> Form -> input('dues', array('label' => 'Dues'));
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
