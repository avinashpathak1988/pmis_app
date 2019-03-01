<style>
#forwardBtnclinicalattendance
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
        'update'                    => '#sickListingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'medicalRecords',
            'action'                => 'medicalSickData', 
            'status'                => $status,
            'prisoner_id'           => $prisoner_id, 
            'uuid'                  => $uuid, 
            'patient_type'          => $patient_type,
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
    $exUrl = "medicalSickData/status:$status/prisoner_id:$prisoner_id/uuid:$uuid/prison_id:$prison_id/patient_type:$patient_type";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
	$urlPdf = $exUrl.'/reqType:PDF';
	$urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download Pdf")),$urlPdf, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
	?>
    </div>
</div>

<?php 
$btnName = Configure::read('SAVE');
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
{
  $btnName = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = Configure::read('REVIEW');
  $isModal = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
{
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/MedicalRecords/add#sick'));?>
<?php echo $this->Form->input('modelname', array('type'=>'hidden','value'=>'MedicalSickRecord'))?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('sick-verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtnclinicalattendance" class="btn btn-success btn-mini"><?php echo $btnName;?></button>
<!-- 
<button type="submit" tabcls="next" id="forwardBtnclinicalattendance" class="btn btn-success btn-mini" <?php //if($isModal == 1){?> data-toggle="modal" data-target="#verify"<?php }?>><?php //echo $btnName;?></button> -->
<?php
    //}
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
  <div style="overflow-x:scroll;">                
<table class="table table-bordered data-table">
    <thead>
        <tr>
            <?php
            if(!isset($is_excel)){
                if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE')){
                ?>
                <th>
                    <?php echo $this->Form->input('checkAll', array(
                          'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAllRecommend',
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                    ));?>
                </th>
                <?php
                }
            }
            ?>
            <th>SL#</th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.prisoner_id','Prisoner No.',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.check_up_date','Checkup Date',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.attendance','Attendance Description',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.checkup_type','Patient Type',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.compliant','Presenting Compliant',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.examination','Examination',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.digonosis','Deferential Diagnosis',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.disease_id','Lab Test',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.results','Results',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.digonosis_dx','Diagnosis(Dx)',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.treatement_rx','Treatement (Rx)',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.radiology','Radiology',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
             <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.bmi_treatment_id','BMI Treatment',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.drug_description','Prescription',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.nutrition_status','Nutrition Status',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.prisoner_state_id','State of Prisoner',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.restricted_prisoner','Restricted Prisoner',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
             <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.remarks_restricted_text','Restricted Prisoner Remarks',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.status','Status',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <?php  if(!isset($is_excel)){ ?>
            <th>Attachment</th>
            <?php } ?>
<?php
if(!isset($is_excel) && ($isAccess == 1)){
    if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
?> 
            <th>Action</th>
            
<?php
}
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
        $display_status = Configure::read($data['MedicalSickRecord']['status']);
?>
        <tr>
            <?php
            if(!isset($is_excel)){
              if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE')){
              ?>
                <td>
                <?php
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE') && ($data['MedicalSickRecord']['status'] == 'Draft'))
                { 
                      echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                          'type'=>'checkbox', 'value'=>$data['MedicalSickRecord']['id'],'hiddenField' => false, 'label'=>false,
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                      ));
                 }
                 else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['MedicalSickRecord']['status'] == 'Saved'))
                  {
                    echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                          'type'=>'checkbox', 'value'=>$data['MedicalSickRecord']['id'],'hiddenField' => false, 'label'=>false,
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                      ));
                  }
                  else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE') && ($data['MedicalSickRecord']['status'] == 'Reviewed'))
                  {
                    echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                          'type'=>'checkbox', 'value'=>$data['MedicalSickRecord']['id'],'hiddenField' => false, 'label'=>false,
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                      ));
                  }
                  else{
                    echo 'N/A';
                  }
                ?>
              </td>
              <?php
            }
              }
              ?>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data["Prisoner"]["prisoner_no"];?></td>
            <td><?php echo date(Configure::read("UGANDA-DATE-FORMAT"), strtotime($data["MedicalSickRecord"]["check_up_date"]))?></td>
            <td><?php echo $data["MedicalSickRecord"]["attendance"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["checkup_type"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["compliant"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["examination"]?></td>
            <td><?php 
            if ($data["MedicalSickRecord"]["digonosis"]!='') {
              echo $data["MedicalSickRecord"]["digonosis"];
            }else{
              echo Configure::read('NA');
            }
            ?>


           </td>
            <td>
              <?php 
              if ($data['MedicalSickRecord']['disease_id']!='') {
                 $deasesid = explode(',', $data['MedicalSickRecord']['disease_id']);
                   $forceno='';
                for ($i=0; $i < count($deasesid); $i++) { 
                  $forceno .= $funcall->getName($deasesid[$i],'Disease','name').',';//$data['User']['force_number']
                }
                echo rtrim($forceno,',');
              }else{
                echo Configure::read('NA');
              }
             ?></td>
            <td><?php 
            if ($data["MedicalSickRecord"]["results"]!='') {
              echo $data["MedicalSickRecord"]["results"];
            }else{
              echo Configure::read('NA');

            }
            ?></td>
            <td><?php echo $data["MedicalSickRecord"]["digonosis_dx"]?></td>
            <td><?php if ($data["MedicalSickRecord"]["treatement_rx"]!='') {
             echo $data["MedicalSickRecord"]["treatement_rx"];
            }else{
              echo Configure::read('NA');

            }
            ?></td>
            <td><?php if ($data["MedicalSickRecord"]["radiology"]!='') {
            echo $data["MedicalSickRecord"]["radiology"];
            }else{
              echo Configure::read('NA');
            }
            ?></td>
            <td><?php if ($data["MedicalSickRecord"]["bmi_treatment_id"]!='') {
             echo $data["MedicalSickRecord"]["bmi_treatment_id"];
            }else{
              echo Configure::read('NA');
            } ?></td>
            <td><?php echo $data["MedicalSickRecord"]["drug_description"]?></td>
            <td><?php if ($data["MedicalSickRecord"]["nutrition_status"]!='') {
             echo $data["MedicalSickRecord"]["nutrition_status"];
            }else{
              echo Configure::read('NA');
            } ?></td>
            <td><?php if ($data["MedicalSickRecord"]["prisoner_state_id"]!='') {
             echo $funcall->getName($data["MedicalSickRecord"]["prisoner_state_id"],"PrisonerState","name");
            }else{
              echo Configure::read('NA');
            }  ?></td>

            <td>
            <?php
                if($data['MedicalSickRecord']['restricted_prisoner'] == 0){
                echo "<font color='red'>No</font>";
                }
                else if($data['MedicalSickRecord']['restricted_prisoner'] == 1){
                echo "<font color='green'>Yes</font> ";   
                // echo (isset($data['MedicalSickRecord']['restricted_work']) && $data['MedicalSickRecord']['restricted_work']!='') ? "(".$data['MedicalSickRecord']['restricted_work'].")" : '';
                }
                else{
                    echo "";
                }
            ?>
            </td>
            <td>
              <?php if ($data["MedicalSickRecord"]["remarks_restricted_text"]!='') {
                echo $data["MedicalSickRecord"]["remarks_restricted_text"];
              }else{
                 echo Configure::read('NA');
              } ?>
            </td>
            <td>
               <?php 

               if($data["MedicalSickRecord"]['checkup_type']== 'In Patient'){
              if($data["MedicalSickRecord"]['status'] == 'Draft')
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
              }
            
            }else{
               echo Configure::read('NA');
            }
           
            ?>
          </td>
          <?php if(!isset($is_excel)) {?>
            <td>
              <?php echo ($data["MedicalSickRecord"]["attachment"]!='') ? $this->Html->link('View', '../files/prisnors/MEDICAL/'.$data["MedicalSickRecord"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary btn-mini')) : 'Not Uploaded'; ?>
            </td>
            <?php } ?>
<?php
         if(!isset($is_excel) && ($isAccess == 1)){// && $data["MedicalSickRecord"]['status'] == 'Draft'
            $medical_checkup_record_id   = $data['MedicalSickRecord']['id'];
            $medical_checkup_record_uuid = $data['MedicalSickRecord']['uuid'];
            if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
            $medical_sick_record_id   = $data['MedicalSickRecord']['id'];
            $medical_sick_record_uuid = $data['MedicalSickRecord']['uuid'];
?>              
            <td>

                <?php echo $this->Form->create('MedicalSickRecordEdit',array('url'=>'/medicalRecords/add#sick','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $medical_sick_record_id)); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                <?php /* ?>
                <?php //echo $this->Form->create('MedicalSickRecordDelete',array('url'=>'/medicalRecords/add#sick/','admin'=>false));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalSickRecord']['id'])); ?>
                <?php //echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?> -->
                <?php if($data['MedicalSickRecord']['status'] == 'Draft'){ ?>

                <?php echo $this->Form->create('MedicalSickRecordEdit',array('url'=>'/medicalRecords/add#sick','admin'=>false,'id'=>'MedicalSickRecordEdit'.$data['MedicalSickRecord']['id']));?> 
              <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalSickRecord']['id'])); ?>
              <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('type'=>'button','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return editForm(".$data['MedicalSickRecord']['id'].");")); ?> 
              <?php echo $this->Form->end();?>
                
              <?php echo $this->Form->create('MedicalSickRecordDelete',array('url'=>'/medicalRecords/add#sick/','admin'=>false, 'id'=>'MedicalSickRecordDelete'));?> 
              <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalSickRecord']['id'])); ?>
              <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('type'=>'button','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return deleteForm();")); ?>
                <?php echo $this->Form->end();?>
                <?php */ ?>
              <?php 
              // debug($data["MedicalSickRecord"]['is_discharge'] == 'Approved');
              if(!isset($is_excel) && $data["MedicalSickRecord"]['checkup_type']== 'In Patient' && $data["MedicalSickRecord"]['status'] == 'Approved' && $data["MedicalSickRecord"]['is_discharge']== 'no') {?>

              <button type="button" onclick="ShowDischargeConfirmYesNo(<?php echo $data['MedicalSickRecord']['id']; ?>);" tabcls="next" id="dischargeBtnclinicalattendance" class="btn btn-success btn-mini">Discharge</button>
              <?php
              }
              ?>
                
            </td>
<?php
          }
        }
         else{
          ?>
         <!--  <td>N/A</td> -->
          <?php
        }
?>

        </tr>
<?php
        $rowCnt++;
        echo $this->Js->writeBuffer();
    }
?>
    </tbody>
</table>
</div>
<?php
echo $this->Form->end();
}else{
echo Configure::read('NO-RECORD');       
}
?>                    

<?php if(@$file_type != 'pdf') { ?>
<script>
$(document).ready(function(){
  
        $("#checkAllclinicalattendance").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $('input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('input[type="checkbox"]:checked').length;
          var is_checkAllclinicalattendance = $('input[id="checkAllclinicalattendance"]:checked').length;
          if(is_checkAllclinicalattendance == 1 && atLeastOneIsChecked == 1)
          { 
            $('#checkAllclinicalattendance').attr('checked', false);
            $('#forwardBtnclinicalattendance').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#forwardBtnclinicalattendance').show();
          }
          else 
          {
            $('#forwardBtnclinicalattendance').hide();
          }
        });
});
//Dynamic confirmation modal -- START --
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

function ShowDischargeConfirmYesNo(id) {
    var $confirm = $("#modalConfirmYesNo");
    $confirm.modal('show');
    $("#lblMsgConfirmYesNo").html("Are you sure want to discharge?");
    $('#btnYesConfirmYesNo').html("Yes");
    $('#btnNoConfirmYesNo').html("No");
    //$('#btnYesConfirmYesNo').css('display','block');
    //$('#btnNoConfirmYesNo').css('display','block');
    $('#btnYesConfirmYesNo').show();
    $('#btnNoConfirmYesNo').show();
    $("#btnYesConfirmYesNo").off('click').click(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Html->url(array('controller'=>'MedicalRecords','action'=>'restoreWard'));?>",
            data: {
                id: id,
            },
            cache: true,
            beforeSend: function()
            {  
                //$('tbody').html('');
            },
            success: function (data) {
                $confirm.modal("hide");
                if(data.trim() != 'FAIL'){
                    showMedicalSickRecords();
                } 
            },
            error: function (errormessage) {
                alert(errormessage.responseText);
            }
        });      
    });
    $("#btnNoConfirmYesNo").off('click').click(function () {
        noFn();
        $confirm.modal("hide");
    });

     
}


function MyYesFunction() {
  if(isModal == 1)
  {
    $('#verify').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormMedicalSickDataForm').submit();
  }
}
function MyNoFunction() {
    
}
//Dynamic confirmation modal -- END --
function editForm(value){
      AsyncConfirmYesNo(
                'Are you sure want to edit?',
                'Yes',
                'No',
                function(){
                    alert(value);
                    // $(aaaa).submit();
                },
                function(){
                    
                }
            );
  }
  function deleteForm(){
      AsyncConfirmYesNo(
                'Are you sure want to delete?',
                'Yes',
                'No',
                function(){
                    $('#MedicalSickRecordDelete').submit();
                },
                function(){
                    
                }
            );
  }
</script>
<?php } ?>