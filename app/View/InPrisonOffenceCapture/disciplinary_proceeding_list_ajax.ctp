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
<?php //debug($datas); exit;
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
            'action'                    => 'disciplinaryProceedingAjax',
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
<?php
    $exUrl = "disciplinaryProceedingListAjax/prisoner_id:$prisoner_id/status:$status";
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/InPrisonOffenceCapture/disciplinaryProceedingList'));?>
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
<table id="districtTable" class="table table-bordered table-striped">
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
            <th>Offence Name</th>
            <th>Plea Type</th>
            <th>Date Of hearing</th>
            <th style="text-align: left;">Status</th>
            <th>View Details</th>
        </tr>
    </thead>
    <tbody>
<?php 
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = Configure::read($data['DisciplinaryProceeding']['status']);
  $prisonerDetails = $funcall->getPrisonerDetails($data['DisciplinaryProceeding']['prisoner_id']);
  //debug($data);
?>
        <tr>
        <?php
        if(!isset($is_excel)){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['DisciplinaryProceeding']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['DisciplinaryProceeding']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['DisciplinaryProceeding']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['DisciplinaryProceeding']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['DisciplinaryProceeding']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['DisciplinaryProceeding']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
            ?>
          </td>
          <?php
          }
          ?>
            <td><?php echo $rowCnt; ?></td>
          
            <td><?php if($prisonerDetails["Prisoner"]["prisoner_no"]!='')echo ucwords(h($prisonerDetails["Prisoner"]["prisoner_no"]));else echo Configure::read('NA'); ?>&nbsp;</td> 

           <td><?php echo $prisonerDetails["Prisoner"]["first_name"]." ".$prisonerDetails["Prisoner"]["last_name"]?> </td>             
              
            <td><?php echo $funcall->getName($data['DisciplinaryProceeding']['internal_offence_id'],"InternalOffence","name");?></td>          
            <td><?php if($data['DisciplinaryProceeding']["plea_type"]!='')echo ucwords(h($data['DisciplinaryProceeding']["plea_type"]));else echo Configure::read('NA'); ?>&nbsp;</td>             
             <td><?php echo (isset($data['DisciplinaryProceeding']["date_of_hearing"]) && $data['DisciplinaryProceeding']["date_of_hearing"] != '0000-00-00') ? ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['DisciplinaryProceeding']["date_of_hearing"])))) :  Configure::read('NA'); ?>&nbsp;</td>
            
            <td>
            <?php if($data['DisciplinaryProceeding']['status'] == 'Draft')
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
          <td> <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModal<?php echo $data['DisciplinaryProceeding']['id']; ?>">View Details</button>

            <!-- Modal -->
           <div id="myModal<?php echo $data['DisciplinaryProceeding']['id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog" style="height: calc(100vh - 15%);overflow-y: scroll;">

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
                                <td><b>Date Of Offence</b></td>
                                <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['DisciplinaryProceeding']["offence_date"]))?></td>
                                <td><b>Offence Type</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["offence_type"]?></td>
                            </tr>
                            <tr>
                                <td><b>Offence Name</b></td>
                                <td><?php echo $funcall->getName($data['DisciplinaryProceeding']['internal_offence_id'],"InternalOffence","name");?></td> 
                                <td><b>Rule And Regulations</b></td>
                                <td><?php echo $funcall->getName($data['DisciplinaryProceeding']['rule_regulation_id'],"RuleRegulation","name");?></td>
                            </tr>
                            <tr>
                                <td><b>Reported By</b></td>
                                <td><?php echo $funcall->getName($data['DisciplinaryProceeding']['offence_recorded_by'],"User","name");?></td>
                                <td><b>Compliant/Victim of Offence</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["offence_victim"]?></td>
                            </tr>
                             <tr>
                                <td><b>Particular Of Offence</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["offence_descr"]?></td>
                                <td></td>
                                <td></td>
                            </tr>
                           
                        </tbody>
                    </table>
                  </div>
                  <div class="modal-header">
                    <h4 class="modal-title">Discplinary Proceeding</h4>
                  </div>
                   <div class="modal-body">
                    <table class="table table-bordered data-table table-responsive">
                        <tbody>
                            <tr>
                                <td><b>Date Of hearing</b></td>
                                <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['DisciplinaryProceeding']["date_of_hearing"]))?></td>
                                <td><b>Plea Type</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["plea_type"]?></td>
                            </tr>
                             <tr>
                                <td><b>Conviction</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["conviction_text"]?></td>
                                 <td><b>Mitigation</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["mitigation"]?></td>

                            </tr>
                            <tr>
                                <td><b>Prosecutions evidence</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["prosecutions_witness"]?></td>
                                 <td><b>Cross examination</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["prosecutions_cross_examination"]?></td>

                            </tr>
                            <tr>
                                <td><b>Prisoner</b></td>
                              <!--    <td><?php //echo $funcall->getName($data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id'],"Prisoner","prisoner_no");?></td>  -->
                                <td>
                                   <?php $prisoner_names=explode(',',$data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id']);
                                      $prisoner_name='';
                                      foreach ($prisoner_names as $key => $value) {
                                        $prisoner_name .= $funcall->getName($value,'Prisoner','prisoner_no').',';
                                      }
                                      echo rtrim($prisoner_name,',');?>
                                </td>
                                       

                                   <!-- <td><?php //echo $data['DisciplinaryProceeding']['defence_witness_prisoner_id'] ; ?></td>  -->
                                 <td><b>Staffs</b></td>
                                 <!-- <td><?php //echo $funcall->getName($data['DisciplinaryProceeding']['prosecutions_witness_staff_id'],"User","name");?></td> -->
                                 <td> <?php $prisoner_names=explode(',',$data['DisciplinaryProceeding']['prosecutions_witness_staff_id']);
                                      $prisoner_name='';
                                      foreach ($prisoner_names as $key => $value) {
                                        $prisoner_name .= $funcall->getName($value,'User','name').',';
                                      }
                                      echo rtrim($prisoner_name,',');?>
                                        
                                  </td>

                            </tr>
                            <tr>
                                <td><b>Other</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["prosecutions_witness_other_text"]?></td>
                                <td><b>Documentary Evidence</b></td>
                                <td> <?php if(isset($data["DisciplinaryProceeding"]["prosecutions_documentary_evidence"]))
                                  {?>
                                      <a class="example-image-link btn btn-success" target="_blank" href="<?php echo $this->webroot; ?>app/webroot/files/disciplinary_summary_document/<?php echo $data["DisciplinaryProceeding"]["prosecutions_documentary_evidence"];?>">View</a>
                                  <?php }?></td>
                            </tr>
                            <tr>
                                <td><b>Result Of Ruling</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["result_rulling"]?></td>
                                <td><b>Defence evidence</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["defense_witness"]?></td>
                            </tr>
                             <tr>
                                <td><b>Cross Examination</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["cross_examination_defence"]?></td>
                                <td><b>Prisoner</b></td>
                               <!--  <td><?php// echo $funcall->getName($data['DisciplinaryProceeding']['defence_witness_prisoner_id'],"Prisoner","prisoner_no");?></td> -->
                                <td>
                                   <?php $prisoner_names=explode(',',$data['DisciplinaryProceeding']['defence_witness_prisoner_id']);
                                      $prisoner_name='';
                                      foreach ($prisoner_names as $key => $value) {
                                        $prisoner_name .= $funcall->getName($value,'Prisoner','prisoner_no').',';
                                      }
                                      echo rtrim($prisoner_name,',');?>
                                </td>
                            </tr>
                            <tr>
                               <td><b>Staffs</b></td>
                               
                                 <td> <?php $prisoner_names=explode(',',$data['DisciplinaryProceeding']['defence_witness_staff_id']);
                                      $prisoner_name='';
                                      foreach ($prisoner_names as $key => $value) {
                                        $prisoner_name .= $funcall->getName($value,'User','name').',';
                                      }
                                      echo rtrim($prisoner_name,',');?>
                                        
                                  </td>
                               <td><b>Others</b></td>
                               <td><?php echo $data['DisciplinaryProceeding']["defence_witness_other_text"]?></td>
                            </tr>
                             <tr>
                                <td><b>Rulers</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["rulers_text"]?></td>
                                <td><b>Summary Cases</b></td>
                                <td><?php echo $data['DisciplinaryProceeding']["summary_cases"]?></td>
                            </tr>
                            <tr>
                                <td><b>Documentary Evidence</b></td>
                                <td> <?php if(isset($data["DisciplinaryProceeding"]["defence_documentary_evidence"]))
                                  {?>
                                      <a class="example-image-link btn btn-success" target="_blank" href="<?php echo $this->webroot; ?>app/webroot/files/disciplinary_summary_document/<?php echo $data["DisciplinaryProceeding"]["defence_documentary_evidence"];?>">View</a>
                                  <?php }?></td>
                                <td><b>Judgement</b></td>
                                <td><?php if ($data['DisciplinaryProceeding']["judgement_id"] == 1) {
                                  echo "Convicted";
                                 
                                }
                                if ($data['DisciplinaryProceeding']["judgement_id"] == 2) {
                                  echo "Acquited";
                                 
                                } ?></td>
                            </tr>
                           <tr>
                             <td><b>Conviction</b></td>
                             <td><?php echo $data['DisciplinaryProceeding']["conviction_text"]?></td>
                             <td><b>Mitigation</b></td>
                             <td><?php echo $data['DisciplinaryProceeding']["mitigation"]?></td>
                          </tr>
                           <tr>
                                <td><b>Summary of Ruling</b></td>
                                <td> <?php if(isset($data["DisciplinaryProceeding"]["summary_document"]))
                                  {?>
                                      <a class="example-image-link btn btn-success" target="_blank" href="<?php echo $this->webroot; ?>app/webroot/files/disciplinary_summary_document/<?php echo $data["DisciplinaryProceeding"]["summary_document"];?>">View</a>
                                  <?php }?></td>
                                <td><b>Adjusting Officer</b></td>
                                <td><?php echo $funcall->getName($data['DisciplinaryProceeding']['adjusting_officer'],"User","name");?></td>
                            </tr>
                           
                        </tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div></td>

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
      $('#ApprovalProcessFormDisciplinaryProceedingListAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
</script>
<?php } ?>