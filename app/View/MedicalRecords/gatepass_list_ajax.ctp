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
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'Discharges',
            'action'                    => 'gatepassListAjax',
            'prisoner_id'               => $prisoner_id,
            'status'=>$status,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} ')
));
?>
<?php
    $exUrl = "gatepassListAjax/prisoner_id:$prisoner_id/status:$status";
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
$btnName = "Generate Gatepass";
$isModal = 1;
// if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
// {
//   $btnName = Configure::read('SAVE');
// }
// else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
// {
//   $btnName = Configure::read('REVIEW');
//   $isModal = 1;
// }
// else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
// {
//   $btnName = Configure::read('APPROVE');
//   $isModal = 1;
// }
echo $this->Form->create('Gatepass',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/MedicalRecords/gatepassList'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('gatepass-modal');?>                       
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
            <th><?php                 
                echo $this->Paginator->sort('MedicalSeriousIllRecord.prisoner_id','Prisoner No.',array('update'=>'#seriousIllListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalSeriousIllRecords','status'=> $status,'prisoner_id'=> $prisoner_id)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSeriousIllRecord.check_up_date','Date of recommendation',array('update'=>'#seriousIllListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalSeriousIllRecords','status'=> $status,'prisoner_id'=> $prisoner_id)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSeriousIllRecord.priority','Priority',array('update'=>'#seriousIllListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalSeriousIllRecords','status'=> $status,'prisoner_id'=> $prisoner_id)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSeriousIllRecord.medical_officer_id_other','Medical Officer Name',array('update'=>'#seriousIllListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalSeriousIllRecords','status'=> $status,'prisoner_id'=> $prisoner_id)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSeriousIllRecord.hospital_id','Hospital Name',array('update'=>'#seriousIllListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalSeriousIllRecords','status'=> $status,'prisoner_id'=> $prisoner_id)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSeriousIllRecord.category_id','Recomendation Category',array('update'=>'#seriousIllListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalSeriousIllRecords','status'=> $status,'prisoner_id'=> $prisoner_id)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSeriousIllRecord.remark','Cause of Recommendetion
',array('update'=>'#seriousIllListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'showMedicalSeriousIllRecords','status'=> $status,'prisoner_id'=> $prisoner_id)));
            ?></th>
            <th style="text-align: left;width:10%">Gatepass Details</th>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = Configure::read($data['MedicalSeriousIllRecord']['status']);
  $prisonerDetails = $funcall->getPrisonerDetails($data['MedicalSeriousIllRecord']['prisoner_id']);
?>
        <tr>
        <?php
        if(!isset($is_excel)){
          ?>
            <td>
            <?php      
            if(count($data['Gatepass'])==0){
              echo $this->Form->input('Gatepass.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['MedicalSeriousIllRecord']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
            }
            ?>
          </td>
          <?php
          }
          ?>
            <td><?php echo $rowCnt; ?></td>         
            <td><?php if($data["Prisoner"]["prisoner_no"]!='')echo ucwords(h($data["Prisoner"]["prisoner_no"]));else echo Configure::read('NA'); ?>&nbsp;</td>  
            <td><?php echo date('m-d-Y', strtotime($data["MedicalSeriousIllRecord"]["check_up_date"]))?></td>
          
             <td><?php if($data["MedicalSeriousIllRecord"]["priority"]!='')echo ucwords(h($data["MedicalSeriousIllRecord"]["priority"]));else echo Configure::read('NA'); ?>&nbsp;</td>           
             <td><?php if($data["User"]["name"]!='')echo ucwords(h($data["User"]["name"]));else echo Configure::read('NA'); ?>&nbsp;</td> 
            <td><?php echo $funcall->getName($data["Medi`calSeriousIllRecord"]["hospital_id"],'Hospital','name');?></td>
            <td><?php echo $funcall->getName($data["MedicalSeriousIllRecord"]["category_id"],'Category','name');?></td>
            <td><?php if ($data["MedicalSeriousIllRecord"]["remark"]!='') {
               echo $data["MedicalSeriousIllRecord"]["remark"];
             }else{echo ('NA');} ?></td>
            <td>
            <?php
            if(isset($data['Gatepass'][0]) && is_array($data['Gatepass'][0]) && count($data['Gatepass'][0])>0){
              
              $status_info = '<b>Gatepass No. : </b>'.$data['Gatepass'][0]['gp_no'].'<br>';
              $status_info .= '<b>Gatepass Date : </b>'.date("d-m-Y", strtotime($data['Gatepass'][0]['gp_date'])).'<br>';
              $status_info .= '<b>Escort : </b>'.$funcall->getName($data['Gatepass'][0]['escort_team'],"User","name").'<br>';
              $status_info .= '<b>Permission Granted for : </b>'.$data['Gatepass'][0]['permission_granted'].'<br>';
              $status_info .= '<b>Purpose : </b>'.$data['Gatepass'][0]['purpose'].'<br>';
            
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Gatepass Info" pageBody="<?php echo $status_info;?>">Gatepass Details</a>
              <?php
            }
            ?>
              
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
echo $this->Js->writeBuffer();
}else{
?>
...
<?php    
}
?>    
<?php if(@$file_type != 'pdf') {?>
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
      $('#ApprovalProcessFormDischargeListAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
</script>
<?php } ?>