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
            'controller'                => 'InPrisonOffenceCaptureController',
            'action'                    => 'approveConfineListAjax',
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
    $exUrl = "approveConfineListAjax/prisoner_id:$prisoner_id/status:$status";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPDF = $exUrl.'/reqType:PDF';
	$urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
     echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
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
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
{
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/InPrisonOffenceCapture/approveConfineList'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" style="margin:3px 1px;"><?php echo $btnName;?></button> 
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
        <?php
        if(!isset($is_excel)){
          ?>
        <th>
            <?php echo $this->Form->input('checkAll', array(
                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
            ));?>
          </th>
          <?php
        }
          ?>
            <th>SL#</th>
            <th>Prisoner No.</th>
            <th>Prisoner Name</th>
            <th>Punishment Date</th>
            <th>Start Date </th>
            <th>End Date</th>
            <th>Confinement status</th>
            <th style="text-align: left;">Status</th>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = ($data['InPrisonPunishmentConfinement']['status']=='Final-Approved') ? 'Approved' : Configure::read($data['InPrisonPunishmentConfinement']['status']);
  $prisonerDetails = $funcall->getPrisonerDetails($data['InPrisonPunishment']['prisoner_id']);
?>
        <tr>
        <?php
        if(!isset($is_excel)){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['InPrisonPunishmentConfinement']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['InPrisonPunishmentConfinement']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['InPrisonPunishmentConfinement']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['InPrisonPunishmentConfinement']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['InPrisonPunishmentConfinement']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['InPrisonPunishmentConfinement']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE') && ($data['InPrisonPunishmentConfinement']['status'] == 'Approved') && $data['InPrisonPunishmentConfinement']['internal_punishment_id'] == Configure::read('DEMOTION-STAGE'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['InPrisonPunishmentConfinement']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
            ?>
          </td>
          <?php
          }
          ?>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $prisonerDetails["Prisoner"]["prisoner_no"]?> </td>
            <td><?php echo $prisonerDetails["Prisoner"]["first_name"]." ".$prisonerDetails["Prisoner"]["last_name"]?> </td>  
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data["InPrisonPunishment"]['punishment_date'])); ?></td>          
            
            <td><?php echo (isset($data["InPrisonPunishment"]["punishment_start_date"]) && $data["InPrisonPunishment"]["punishment_start_date"]!='') ? date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data["InPrisonPunishment"]["punishment_start_date"])) : 'NA'; ?></td>
            <td><?php echo (isset($data["InPrisonPunishment"]["punishment_end_date"]) && $data["InPrisonPunishment"]["punishment_end_date"]!='') ? date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data["InPrisonPunishment"]["punishment_end_date"])) : 'NA'; ?></td>
            
            <td> <?php if($data["InPrisonPunishmentConfinement"]['approval_status'] !='')
            {
              echo $data["InPrisonPunishmentConfinement"]['approval_status'];
            }?></td>
            
            <td>
            <?php if($data["InPrisonPunishmentConfinement"]['status'] == 'Draft')
            {
              echo $display_status;
            }
            else 
            {
              $status_info = 'dd<b>Status: </b>'.$display_status.'<br>';
              if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
                $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
              if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
                $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $display_status;?></a>
              <?php 
            }?>
            
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
      $('#ApprovalProcessFormApproveConfineListAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
</script>
<?php } ?>