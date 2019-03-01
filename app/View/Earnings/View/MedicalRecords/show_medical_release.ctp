<link rel="stylesheet" href="<?php echo $this->webroot?>forms/css/style.css">
<style>
#forwardBtnRecommend
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>
<?php
if(is_array($datas) && count($datas)>0){//debug($datas);
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#medicalReleaseListingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'medicalRecords',
            'action'                => 'showMedicalRelease',
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
    $exUrl = "showMedicalRelease/status:$status/prisoner_id:$prisoner_id/uuid:$uuid";
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/MedicalRecords/add#release_recom'));?>
<?php echo $this->Form->input('modelname', array('type'=>'hidden','value'=>'MedicalRelease'))?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-release-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtnRecommend" class="btn btn-success btn-mini"><?php echo $btnName;?></button>

<!-- <button type="submit" tabcls="next" id="forwardBtnRecommend" class="btn btn-success btn-mini" <?php //if($isModal == 1){?> data-toggle="modal" data-target="#verify"<?php }?>><?php //echo $btnName;?></button> -->
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
                echo $this->Paginator->sort('MedicalRelease.prisoner_id','Prisoner No.',array('update'=>'#medicalReleaseListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalRelease','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalRelease.check_up_date','Date of recommendation',array('update'=>'#medicalReleaseListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalRelease','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('Prisoner.first_name','Prisoner Name',array('update'=>'#medicalReleaseListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalRelease','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalRelease.status','Status',array('update'=>'#medicalReleaseListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalRelease','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
          <!--     <th><?php                 
               // echo $this->Paginator->sort('MedicalRelease.presentation_id','Presentation',array('update'=>'#medicalReleaseListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalRelease','prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
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
        $display_status = Configure::read($data['MedicalRelease']['status']);
?>

        <tr>
        <?php
        if(!isset($is_excel)){
          if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE')){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE') && ($data['MedicalRelease']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['MedicalRelease']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['MedicalRelease']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['MedicalRelease']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE') && ($data['MedicalRelease']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['MedicalRelease']['id'],'hiddenField' => false, 'label'=>false,
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
            <td><?php echo date('d-m-Y', strtotime($data["MedicalRelease"]["check_up_date"]))?></td>
            <td><?php echo $data["Prisoner"]["first_name"].' '.$data["Prisoner"]["last_name"]?> </td>
            <td>
            <?php if($data["MedicalRelease"]['status'] == 'Draft')
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

              <button type="button" onclick="ShowView('<?php echo $data['MedicalRelease']['id']?>');" class="btn btn-primary">View</button>
              <?php 
            }?>
          </td>
          <!-- <td><?php //echo $data['MedicalRelease']['presentation_id'];?></td> -->
<?php
        if(!isset($is_excel) && ($isAccess == 1)){
            $medical_serious_ill_record_id   = $data['MedicalRelease']['id'];
            $medical_serious_ill_record_uuid = $data['MedicalRelease']['uuid'];
            if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE')){
?>              
            <td>
                <?php if($data["MedicalRelease"]['status'] == 'Draft') {?>
                 <?php echo $this->Form->create('MedicalReleaseEdit',array('url'=>'/medicalRecords/add#release_recom/','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalRelease']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                <button type="button" onclick="ShowView('<?php echo $data['MedicalRelease']['id']?>');" class="btn btn-success btn-mini">View</button>
                <?php }else{?>
                    <?php //echo $this->element('view-modal');?> 
                    <button type="button" onclick="ShowView('<?php echo $data['MedicalRelease']['id']?>');" class="btn btn-success btn-mini">View</button>

                   <?php //echo $this->Form->button('View',array('type'=>'button','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return editForm();")); ?>
                <?php }?>
                <?php //echo $this->Form->create('MedicalSeriousIllRecordDelete',array('url'=>'/medicalRecords/add#seriouslyill/','admin'=>false));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalSeriousIllRecord']['id'])); ?>
                <?php //echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?> 
                 <?php if($data["MedicalRelease"]['status'] == 'Draft') {?>

              <?php echo $this->Form->create('MedicalReleaseDelete',array('url'=>'/medicalRecords/add#release_recom/','admin'=>false, 'id'=>'MedicalReleaseDelete'));?> 
              <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalRelease']['id'])); ?>
              <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('type'=>'button','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return deleteForm();")); ?>
                <?php echo $this->Form->end();?>
                <?php } ?>

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
?>
...
<?php    
}
?>    
<!--///////////////////////////View Modal/////////////////////////////////////////-->

<div id="view" class="modal fade verifyPopupModal" role="dialog">
    <div class="modal-dialog" style="width: 1212px!important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                <h4 class="modal-title">View</h4>
            </div>
            <div class="modal-body View">

            </div>
        </div>
    </div>
</div>
<!--//////////////////////view modal ends/////////////////////////////////-->
<?php if(@$file_type != 'pdf') { ?>                
<script>
$(document).ready(function(){
  
        $("#checkAllRecommend").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $('input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('input[type="checkbox"]:checked').length;
          var is_checkAllRecommend = $('input[id="checkAllRecommend"]:checked').length;
          if(is_checkAllRecommend == 1 && atLeastOneIsChecked == 1)
          { 
            $('#checkAllRecommend').attr('checked', false);
            $('#forwardBtnRecommend').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#forwardBtnRecommend').show();
          }
          else 
          {
            $('#forwardBtnRecommend').hide();
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
    $('#ApprovalProcessFormShowMedicalReleaseForm').submit();
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
                    $('#MedicalReleaseEdit').submit();
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
                    $('#MedicalReleaseDelete').submit();
                },
                function(){
                    
                }
            );
  }
  function ShowView(id){
    $.ajax({
        type: "POST",
        url: "<?php echo $this->Html->url(array('controller'=>'MedicalRecords','action'=>'getMedicalReleaseViewAjax'));?>",
        data: {
            id: id,
        },
        cache: true,
        beforeSend: function()
        {  
            //$('tbody').html('');
        },
        success: function (data) {
            $('.View').html(data);
            $('#view').modal('show');
        },
        error: function (errormessage) {
            alert(errormessage.responseText);
        }
    });
    //
  }
</script>
<?php } ?>