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
            'action'                    => 'punishmentListAjax',
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
    $exUrl = "punishmentListAjax/prisoner_id:$prisoner_id/status:$status";
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/InPrisonOffenceCapture/punishmentList'));?>
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
            <th>Offence Name</th>
            <th>Start Date </th>
            <th>End Date</th>
            <th>Details</th>
            <th style="text-align: left;">Status</th>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = Configure::read($data['InPrisonPunishment']['status']);

  $prisonerDetails = $funcall->getPrisonerDetails($data['DisciplinaryProceeding']['prisoner_id']);
?>
        <tr>
        <?php
        if(!isset($is_excel)){
          ?>
            <td>
            <?php
            // debug($data['DisciplinaryProceeding']['prisoner_id']);
            // debug($data['DisciplinaryProceeding']['id']);
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['InPrisonPunishment']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['InPrisonPunishment']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['InPrisonPunishment']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['InPrisonPunishment']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['InPrisonPunishment']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['InPrisonPunishment']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE') && ($data['InPrisonPunishment']['status'] == 'Approved') && $data['InPrisonPunishment']['internal_punishment_id'] == Configure::read('DEMOTION-STAGE'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['InPrisonPunishment']['id'],'hiddenField' => false, 'label'=>false,
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
            
            <td><?php echo $funcall->getName($data['DisciplinaryProceeding']['internal_offence_id'],"InternalOffence","name") ?></td>
            <td><?php echo (isset($data["InPrisonPunishment"]["punishment_start_date"]) && $data["InPrisonPunishment"]["punishment_start_date"]!='') ? date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data["InPrisonPunishment"]["punishment_start_date"])) : 'NA'; ?></td>
            <td><?php echo (isset($data["InPrisonPunishment"]["punishment_end_date"]) && $data["InPrisonPunishment"]["punishment_end_date"]!='') ? date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data["InPrisonPunishment"]["punishment_end_date"])) : 'NA'; ?></td>
            
            <td>
              <!-- Trigger the modal with a button -->
              <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModal<?php echo $data['InPrisonPunishment']['id']; ?>">View Details</button>

              <!-- Modal -->
              <div id="myModal<?php echo $data['InPrisonPunishment']['id']; ?>" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Details</h4>
                    </div>
                    <div class="modal-body">
                      <table class="table table-bordered data-table table-responsive">
                          <tbody>
                              <tr>
                                <td><b>Punishment Type</b></td>
                                <td><?php echo $data["InternalPunishment"]["name"] ;?></td>
                              </tr>
                              <tr>
                                  <td><b>Remarks</b></td>
                                  <td><?php echo $data["InPrisonPunishment"]["remarks"] ;?></td>
                              </tr>
                              <tr>
                                  <td><b>Punishment Details</b></td>
                                  <td>
                                    <?php
                                    echo (isset($data["InPrisonPunishment"]["deducted_amount"]) && $data["InPrisonPunishment"]["deducted_amount"]!='') ? "Deducted Amount : ".$data["InPrisonPunishment"]["deducted_amount"] : '';
                                    if(isset($data["InPrisonPunishment"]["privilege_id"]) && $data["InPrisonPunishment"]["privilege_id"]!=''){
                                        $privilage = array();
                                        foreach (explode(",", $data["InPrisonPunishment"]["privilege_id"]) as $key => $value) {
                                            $privilage[] = $funcall->getName($value,"PrivilegeRight","name");
                                        }
                                        echo "Deducted Privilege : ".implode(", ", $privilage);
                                    }
                                    if(isset($data["InPrisonPunishment"]["loss_type"]) && $data["InPrisonPunishment"]["loss_type"]!=''){
                                        echo "Loss Type : ".$data["InPrisonPunishment"]["loss_type"]." <br>";
                                        echo (isset($data["InPrisonPunishment"]["duration_month"]) && $data["InPrisonPunishment"]["duration_month"]!='') ? "Duration : ".$data["InPrisonPunishment"]["duration_month"]." Months " : '';
                                        echo (isset($data["InPrisonPunishment"]["duration_days"]) && $data["InPrisonPunishment"]["duration_days"]!='') ? $data["InPrisonPunishment"]["duration_days"]." days" : '';
                                    }
                                    if(isset($data["InPrisonPunishment"]["demotion_ward_id"]) && $data["InPrisonPunishment"]["demotion_ward_id"]!=''){
                                        echo "Changed Ward : ".$funcall->getName($data["InPrisonPunishment"]["demotion_ward_id"],"Ward","name")."<br>";
                                        echo "Changed Cell : ".$funcall->getName($data["InPrisonPunishment"]["demotion_ward_cell_id"],"WardCell","cell_name");
                                    }
                                    if(isset($data["InPrisonPunishment"]["demotion_stage_id"]) && $data["InPrisonPunishment"]["demotion_stage_id"]!=''){
                                        echo "Changed Stage : ".$funcall->getName($data["InPrisonPunishment"]["demotion_stage_id"],"Stage","name");
                                    }
                                    ?>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
              </div>

            </td>
            
            <td>
            <?php if($data["InPrisonPunishment"]['status'] == 'Draft')
            {
              echo $display_status;
            }
            else 
            {
              $status_info = '<b>Status: </b>'.$display_status.'<br>';
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
        $('#ApprovalProcessFormPunishmentListAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
</script>
<?php } ?>