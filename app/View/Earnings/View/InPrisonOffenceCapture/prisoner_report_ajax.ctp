<style>
#forwardBtn
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>
<?php
if(is_array($datas) && count($datas)>0){
  if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'InPrisonOffenceCapture',
            'action'                    => 'prisonerReportAjax',
            'prisoner_id'               => $prisoner_id,
            'status'                    => $status,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "prisonerReportAjax/prisoner_id:$prisoner_id/status:$status";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPDF = $exUrl.'/reqType:PDF';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));

    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
?>
    </div>
</div>

<?php 
$btnName = Configure::read('SAVE');
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName = Configure::read('REVIEW');
  $isModal = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/DisciplinaryProceeding/punishmentList'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button> 
<?php
    }
?>
<?php
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        }
          ?> 
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>        
            <th>SL#</th>
            <th>Prisoner No.</th>
            <th>Prisoner Name</th>
            <th>Type of prisoner</th>
            <th>Place of offence</th>
            <th>Date of offence</th>
            <th>Charge contra to sub-section</th>
            <th>Prisons rules and Regulations</th>
            <th>Particulars of Offence</th>
            <th>Witness Prosecution</th>
            <th>Witness for the defense</th>
            <th>Finding and sentence</th>
            <th>Evidence</th>
            <th>Date of start of punishment</th>
            <th>Authority</th>
            <th>Adjudicating Officer</th>
            <th>Officer In Charge</th>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  // debug($data['DisciplinaryProceeding'])
?>
  <tr>        
      <td><?php echo $rowCnt; ?></td>
      <td><?php echo $funcall->getPrisonerNumber($data["DisciplinaryProceeding"]["prisoner_id"])?> </td>
      <td><?php echo $funcall->getPrisonerName($data["DisciplinaryProceeding"]["prisoner_id"])?> </td>
      <td><?php echo $funcall->getName($funcall->getName($data["DisciplinaryProceeding"]["prisoner_id"],"Prisoner","prisoner_type_id"),"PrisonerType","name"); ?> </td>
      <td><?php echo $data["DisciplinaryProceeding"]["offence_place"]; ?></td>
      <td><?php echo date("d-m-Y", strtotime($data["DisciplinaryProceeding"]["offence_date"])); ?></td>
      <td><?php //echo $data["DisciplinaryProceeding"]["offence_place"]; ?></td>
      <td><?php echo $funcall->getName($data['DisciplinaryProceeding']['rule_regulation_id'],"RuleRegulation","name"); ?></td>
      <td><?php echo $data["DisciplinaryProceeding"]["offence_descr"]; ?></td>
      <td><?php 

      if(isset($data["DisciplinaryProceeding"]["prosecutions_witness_prisoner_id"]) && $data["DisciplinaryProceeding"]["prosecutions_witness_prisoner_id"]!=''){
        $prisonerProsecutions = array();
        foreach (explode(",", $data["DisciplinaryProceeding"]["prosecutions_witness_prisoner_id"]) as $prosecutions_witness_key => $prosecutions_witness_value) {
            $prisonerProsecutions[] = $funcall->getName($prosecutions_witness_value,"Prisoner","prisoner_no");
        }
        echo implode(", ", $prisonerProsecutions);
      }

      ?></td>
      <td>
        <?php 
      if(isset($data["DisciplinaryProceeding"]["defense_witness_prisoner_id"]) && $data["DisciplinaryProceeding"]["defense_witness_prisoner_id"]!=''){
        $prisonerProsecutions = array();
        foreach (explode(",", $data["DisciplinaryProceeding"]["defense_witness_prisoner_id"]) as $prosecutions_witness_key => $prosecutions_witness_value) {
            $prisonerProsecutions[] = $funcall->getName($prosecutions_witness_value,"Prisoner","prisoner_no");
        }
        echo implode(", ", $prisonerProsecutions);
      } 
      ?></td>
      <td><?php echo $data["DisciplinaryProceeding"]["offence_descr"]; ?></td>
      <td><?php //echo $funcall->$data["DisciplinaryProceeding"]["id"]; ?></td>
      <td><?php echo ($data["InPrisonPunishment"]["punishment_start_date"]!='') ? date("d-m-Y", strtotime($data["InPrisonPunishment"]["punishment_start_date"])) : ''; ?></td>
      <td><?php //echo $data["InPrisonPunishment"]["punishment_start_date"]; ?></td>
      <td><?php echo $funcall->getName($data["DisciplinaryProceeding"]["adjusting_officer"],"User","name"); ?></td>
      <td><?php //echo $data["DisciplinaryProceeding"]["offence_descr"]; ?></td>
      
      </td>           
  </tr>
<?php
$rowCnt++;
}
?>
    </tbody>
</table>
<?php
echo $this->Form->end();
}else{
?>
...
<?php    
}
?>    
<?php if(@$file_type != 'pdf') { ?>
<script>
$(document).ready(function(){  
    $("#checkAll").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
    });
    $('input[type="checkbox"]').click(function(){
    var atLeastOneIsChecked = $('input[type="checkbox"]:checked').length;
    var is_checkall = $('input[id="checkAll"]:checked').length;
    if(is_checkall == 1 && atLeastOneIsChecked == 1)
    { 
        $('#checkAll').attr('checked', false);
        $('#forwardBtn').hide();
    }
    else if(atLeastOneIsChecked >= 1)
    {
        $('#forwardBtn').show();
    }
    else 
    {
        $('#forwardBtn').hide();
    }
    });
});
var btnName = '<?php echo $btnName;?>';
var isModal = '<?php echo $isModal;?>';
function ShowConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName+"?",
            btnName,
            'Cancel',
            MyYesFunction,
            MyNoFunction
        );
}

function MyYesFunction() {
  if(isModal == 1)
    {
      $('#verify').modal('show');
    }
    else 
    {
      $('#ApprovalProcessFormPunishmentListAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
</script>
<?php } ?>