<style>
#forwardBtnDeath
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
        'update'                    => '#deathListingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'medicalRecords',
            'action'                => 'showMedicalDeathRecords',  
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
<?php
    $exUrl = "showMedicalDeathRecords/status:$status/prisoner_id:$prisoner_id/uuid:$uuid/prison_id:$prison_id";
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/MedicalRecords/add#death'));?>
<?php echo $this->Form->input('modelname', array('type'=>'hidden','value'=>'MedicalDeathRecord'))?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtnDeath" class="btn btn-success btn-mini"><?php echo $btnName;?></button>
<!-- <button type="submit" tabcls="next" id="forwardBtnDeath" class="btn btn-success btn-mini" <?php //if($isModal == 1){?> data-toggle="modal" data-target="#verify"<?php //}?>><?php //echo $btnName;?></button> -->
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
            <th><?php                 
                echo $this->Paginator->sort('MedicalDeathRecord.prisoner_id','Prisoner No.',array('update'=>'#deathListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalDeathRecords','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalDeathRecord.prisoner_id','Cause of Death',array('update'=>'#deathListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalDeathRecords','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalDeathRecord.prisoner_id','Date and Time of Death',array('update'=>'#deathListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalDeathRecords','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <!-- <th><?php                 
                //echo $this->Paginator->sort('MedicalDeathRecord.prisoner_id','Time of Death',array('update'=>'#deathListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalDeathRecords','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th> -->
            <th><?php                 
                echo $this->Paginator->sort('MedicalDeathRecord.prisoner_id','Place of Death',array('update'=>'#deathListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalDeathRecords','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalDeathRecord.prisoner_id','Name of Place',array('update'=>'#deathListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalDeathRecords','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalDeathRecord.prisoner_id','Medical Personnel',array('update'=>'#deathListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalDeathRecords','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <?php if(!isset($is_excel)) { ?>
            <th>Medical Form</th>
            <th>Postmotorm Report</th>
            <th>Pathologist Report</th>
            <?php } ?>
            <!-- <th><?php                 
                //echo $this->Paginator->sort('MedicalDeathRecord.status','Status',array('update'=>'#deathListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalDeathRecords','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th> -->
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
        $display_status = Configure::read($data['MedicalDeathRecord']['status']);
?>
        <tr>
        
          
            <td><?php echo $rowCnt; ?></td>
          
            <td><?php if($data["Prisoner"]["prisoner_no"]!='')echo ucwords(h($data["Prisoner"]["prisoner_no"]));else echo Configure::read('NA'); ?>&nbsp;</td>             
            <td><?php if($data["MedicalDeathRecord"]["death_cause"]!='')echo ucwords(h($data["MedicalDeathRecord"]["death_cause"]));else echo Configure::read('NA'); ?>&nbsp;</td>              
            <td><?php echo date("d-m-Y H:i",strtotime($data['MedicalDeathRecord']['check_up_date']))?></td>

            

            <!-- <td><?php //echo $data["MedicalDeathRecord"]["time_of_death"]?> </td> -->
          
            <td><?php if($data["MedicalDeathRecord"]["death_place"]!='')echo ucwords(h($data["MedicalDeathRecord"]["death_place"]));else echo Configure::read('NA'); ?>&nbsp;</td>  
            <?php
              if($data["MedicalDeathRecord"]["place_name"]!=""){
                ?>
                <td><?php echo $data["MedicalDeathRecord"]["place_name"]?></td>
                <?php
              }
              else{
                ?>
                <td>N/A</td>
                <?php
              }
            ?>
            
            <td><?php echo $data["User"]["name"]?></td>
           <?php if(!isset($is_excel)) { ?>
            <td>
                <?php echo $this->Html->link('View', '../files/prisnors/MEDICAL/'.$data["MedicalDeathRecord"]["medical_from_attach"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary btn-mini'))?>
            </td>
            <td>
                <?php echo ($data["MedicalDeathRecord"]["attachment"]!='') ? $this->Html->link('View', '../files/prisnors/MEDICAL/'.$data["MedicalDeathRecord"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary btn-mini')) : 'Not Uploaded'; ?>
            </td>
            <td>
                <?php echo ($data["MedicalDeathRecord"]["pathologist_attach"]!='') ? $this->Html->link('View', '../files/prisnors/MEDICAL/'.$data["MedicalDeathRecord"]["pathologist_attach"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary btn-mini')) : 'Not Uploded'; ?>
            </td>
            <?php } ?>
            <!-- <td>
            <?php 
            // if($data["MedicalDeathRecord"]['status'] == 'Draft')
            // {
            //   echo $display_status;
            // }
            // else 
            // {
              // $status_info = '<b>Status: </b>'.$display_status.'<br>';
              // if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
              //   $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
              // if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
              //   $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
               <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php //echo $status_info;?>"><?php //echo $display_status;?></a> 
              <?php 
            // }
            ?>
          </td> -->
<?php
        if(!isset($is_excel) && ($isAccess == 1)){
            $medical_death_record_id   = $data['MedicalDeathRecord']['id'];
            $medical_death_record_uuid = $data['MedicalDeathRecord']['uuid'];
            if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
?>              
            

              <td nowrap="nowrap">
                <?php 

               
                  if ($data["MedicalDeathRecord"]["attachment"]=="") {
                ///if(true){//$data['MedicalDeathRecord']['status'] == 'Draft' ?>
              <?php echo $this->Form->create('MedicalDeathRecordEdit',array('url'=>'/medicalRecords/add#death','admin'=>false));?> 
              <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalDeathRecord']['id'])); ?>
              <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('type'=>'submit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
              <?php echo $this->Form->end();?>
              <?php }else{
                if ($data["MedicalDeathRecord"]["is_final_save"]!=1) {
                  echo $this->Form->create('MedicalDeathfinalsaveId',array('url'=>'/medicalRecords/add#death','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalDeathRecord']['id'])); ?>
                <?php echo $this->Form->button('Final Save',array('class'=>'btn btn-warning btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to final save?')"));

                echo $this->Form->end();
                }
                 
              } ?>

             
            
              <?php 
              echo $this->Html->link('PF22',array(
                            'action'=>'../medicalRecords/medicalPdfDownload',
                            $data['Prisoner']['id']
                        ),array(
                            'escape'=>false,
                            'class'=>'btn btn-primary btn-mini',
                            'style'=>'margin-left:10px;'
                        ));
              ?>
              <?php /* ?>
              <?php echo $this->Form->create('MedicalDeathDelete',array('url'=>'/medicalRecords/add#death/','admin'=>false, 'id'=>'MedicalDeathDelete'));?> 
              <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalDeathRecord']['id'])); ?>
              <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('type'=>'button','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return deleteForm();")); ?>
                <?php echo $this->Form->end();?>
                <?php } ?>
              <?php */ ?>
                <!-- <?php //echo $this->Form->create('MedicalDeathRecordEdit',array('url'=>'/medicalRecords/add#death','admin'=>false));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $medical_death_record_id)); ?>
                <?php //echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
            
                <?php //echo $this->Form->create('MedicalDeathDelete',array('url'=>'/medicalRecords/add#death/','admin'=>false));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalDeathRecord']['id'])); ?>
                <?php //echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?> -->
                 
         
                
            </td>
<?php
          }
        }
         else{
          ?>
          <td>N/A</td>
          <?php
        }
?>
          
        
        
        </tr>
<?php
echo $this->Js->writeBuffer();
        $rowCnt++;
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
<?php if(@$file_type != 'pdf') {?>
<script>
$(document).ready(function(){
  
        $("#checkAllDeath").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $('input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('input[type="checkbox"]:checked').length;
          var is_checkAllDeath = $('input[id="checkAllDeath"]:checked').length;
          if(is_checkAllDeath == 1 && atLeastOneIsChecked == 1)
          { 
            $('#checkAllDeath').attr('checked', false);
            $('#forwardBtnDeath').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#forwardBtnDeath').show();
          }
          else 
          {
            $('#forwardBtnDeath').hide();
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

function MyYesFunction() {
  if(isModal == 1)
  {
    $('#verify').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormShowMedicalDeathRecordsForm').submit();
  }
}
function MyNoFunction() {
    
}
//Dynamic confirmation modal -- END --

function editForm(){
      AsyncConfirmYesNo(
                'Are you sure want to edit?',
                'Yes',
                'No',
                function(){
                    $('#MedicalDeathRecordEdit').submit();
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
                    $('#MedicalDeathDelete').submit();
                },
                function(){
                    
                }
            );
  }

</script> 
<?php } ?>                   