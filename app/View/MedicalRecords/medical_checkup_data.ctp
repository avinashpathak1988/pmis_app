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
        'update'                    => '#checkupListingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'medicalRecords',
            'action'                => 'medicalCheckupData', 
            'status'           => $status,
            'prisoner_id'         => $prisoner_id, 
            'uuid'         => $uuid,  
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
    $exUrl = "medicalCheckupData/status:$status/prisoner_id:$prisoner_id/uuid:$uuid";
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/MedicalRecords/add#health_checkup'));?>
<?php echo $this->Form->input('modelname', array('type'=>'hidden','value'=>'MedicalCheckupRecord'))?>
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
 <div style="overflow-x:scroll;">               
<table class="table table-bordered data-table">
    <thead>
        <tr>
        
            <th>SL#</th>
            <th>
            <?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.check_up','Checkup Type',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?>
            </th>
            <th>
            <?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.prisoner_id','Prisoner No.',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?>
            
            </th>
            <th>
            <?php                 
                echo $this->Paginator->sort('Prisoner.fullname','Prisoner Name',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?>
            
            </th>
            <th>
            <?php                 
                echo $this->Paginator->sort('Prisoner.gender_id','Gender',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?>
            
            </th>
            <th>
            <?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.age','Age',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?>
            
            </th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.height_feet','Height (in cm)',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <!-- <th><?php                 
                //echo $this->Paginator->sort('MedicalCheckupRecord.height_inch','Height in inch',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th> -->
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.weight','Weight (in Kg) ',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.bmi','BMI',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.grade','BMI Classification',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.bmi_treatment_initial','BMI Treatment',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
             <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.blood_group','Blood Groups',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.tb','T.B Test',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.regimen','T.B Test Regenment',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.hiv','HIV Test',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.regenment','HIV Test Regenment',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.mental_case','Mental Case',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.other_disease','Other Diseases',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.follow_up','Folow Up Date',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <!-- <th style="text-align: left;">
              <?php                 
                //echo $this->Paginator->sort('MedicalCheckupRecord.status','Draft and Approve',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?>
              </th> -->
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
    $gender="";
    foreach($datas as $data){
        $display_status = Configure::read($data['MedicalCheckupRecord']['status']);
        $gender_id=$data["Prisoner"]["gender_id"];
        $med_checkup_id = $data['MedicalCheckupRecord']['id'];
        if($gender_id==2){$gender="Female";}
        else if($gender_id==1){$gender="Male";}
?>
        <tr>
        
            <td><?php 
                  
                  echo $rowCnt;
             ?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["check_up"];?></td>
             <td><?php 
             if ($data["Prisoner"]["is_death"]==1) {
                 echo $data["Prisoner"]["prisoner_no"]."(Death)";
             }else{
             echo $data["Prisoner"]["prisoner_no"];}

           ?></td>
             <td><?php echo $data["Prisoner"]["fullname"];?></td>
             <td><?php echo $gender;?></td>
             <td><?php echo $data["Prisoner"]["age"];?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["height_feet"]?></td>
             <!-- <td> <?php //echo $data["MedicalCheckupRecord"]["height_inch"]?></td> -->
             <td><?php echo $data["MedicalCheckupRecord"]["weight"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["bmi"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["grade"]?></td>
             <td><?php if ($data["MedicalCheckupRecord"]["bmi_treatment_initial"]!='') {
              echo $data["MedicalCheckupRecord"]["bmi_treatment_initial"];
             }else{
              echo Configure::read('NA');
             }?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["blood_group"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["tb"]?></td>
             <td><?php if ($data["MedicalCheckupRecord"]["regimen"]!='') {
               echo $data["MedicalCheckupRecord"]["regimen"];
             }else{echo Configure::read('NA');} ?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["hiv"]?></td>
             <?php
              if($data["MedicalCheckupRecord"]["hiv"]=="Positve"){
             ?>
             <td><?php echo $data["MedicalCheckupRecord"]["regenment"]?></td>
             <?php
              }
              else{
                ?>
                <td>N/A</td>
                <?php
              }
             ?>
             <td><?php echo $data["MedicalCheckupRecord"]["mental_case"]?></td>
             <td><?php if ($data["MedicalCheckupRecord"]["other_disease"]!='') {
               echo $data["MedicalCheckupRecord"]["other_disease"];
             }else{echo Configure::read('NA');}?></td>
            <td><?php echo date('m-d-Y', strtotime($data["MedicalCheckupRecord"]["follow_up"]))?></td>
            <!-- <td> -->
            <?php 
            // if($data["MedicalCheckupRecord"]['status'] == 'Draft')
            // {
            //   echo $display_status;
            // }
            // else 
            // {
            //   $status_info = '<b>Status: </b>'.$display_status.'<br>';
            //   if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
            //     $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
            //   if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
            //     $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
              <!-- <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php //echo $status_info;?>"><?php //echo $display_status;?></a> -->
              <?php 
            //}
            ?>
          <!-- </td> -->
            
            

<?php
        if(!isset($is_excel) && ($isAccess == 1 )){//&& $data["MedicalCheckupRecord"]['status'] == 'Draft'
            $medical_checkup_record_id   = $data['MedicalCheckupRecord']['id'];
            $medical_checkup_record_uuid = $data['MedicalCheckupRecord']['uuid'];
            if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
?>              
            
               <!--  <?php //echo $this->Form->create('MedicalCheckupRecordEdit',array('url'=>'/medicalRecords/add#health_checkup','admin'=>false,'id'=>'MedicalCheckupRecordEdit-'.$med_checkup_id));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalCheckupRecord']['id'])); ?>
                <?php //echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 

        
            <?php //echo $this->Form->create('MedicalCheckupDelete',array('url'=>'/medicalRecords/add#health_checkup/','admin'=>false, 'id'=>'MedicalCheckupDelete-'.$med_checkup_id));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalCheckupRecord']['id'])); ?>
                <?php //echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?> -->
              <td nowrap="nowrap">


                <?php if(true ) {
                if ($data["Prisoner"]["is_death"]!=1) {
                    ?>
                <?php echo $this->Form->create('MedicalCheckupRecordEdit',array('url'=>'/medicalRecords/add#health_checkup','admin'=>false));?> 
                <?php echo $this->Form->end();?>
              <?php echo $this->Form->create('MedicalCheckupRecordEdit',array('url'=>'/medicalRecords/add#health_checkup','admin'=>false, 'id'=>'MedicalCheckupRecordEdit'.$data['MedicalCheckupRecord']['id']));?> 
              <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalCheckupRecord']['id'])); ?>
              <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('type'=>'button','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return editForm(".$data['MedicalCheckupRecord']['id'].")")); ?> 
              <?php echo $this->Form->end();?>

             
                <?php }
                else{echo "Death Prisoner";}
                } ?>
                <?php //echo $this->Form->create('MedicalCheckupDelete',array('url'=>'/medicalRecords/add#health_checkup/','admin'=>false));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalCheckupRecord']['id'])); ?>
                <?php //echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>

                <!-- <?php //echo $this->Form->create('MedicalCheckupDelete',array('url'=>'/medicalRecords/add#health_checkup/','admin'=>false,'id'=>'MedicalCheckupRecord'.$data['MedicalCheckupRecord']['id']));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalCheckupRecord']['id'])); ?>
                <?php //echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>'deleteForm('.$data['MedicalCheckupRecord']['id'].')')); ?> -->

            </td>
<?php
          }
        }
        else{
          ?>
          <!-- <td>N/A</td> -->
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
?>
<?php if(@$file_type != 'pdf') {?>
<script>
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

function MyYesFunction() {
  if(isModal == 1)
  {
    $('#verify').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormMedicalCheckupDataForm').submit();
  }
}
function MyNoFunction() {
    
}

function editForm(id){
  var ids = id;
      AsyncConfirmYesNo(
                'Are you sure want to edit?',
                'Yes',
                'No',
                function(id){
                    $('#MedicalCheckupRecordEdit'+ids).submit();
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
                    $('#MedicalCheckupDelete').submit();
                },
                function(){
                    
                }
            );
  }
</script>
<?php } ?>
<?php 
}else{
echo Configure::read('NO-RECORD');    
}
?>                    
