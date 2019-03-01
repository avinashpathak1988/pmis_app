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
    <div class="col-sm-7 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "medicalSickData/status:$status/prisoner_id:$prisoner_id/uuid:$uuid";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    // echo '&nbsp;&nbsp;';

    //  $execPath = $_SERVER['SERVER_NAME']."/uganda/medicalRecords/medicalSickDatapdf/status:$status/prisoner_id:$prisoner_id/uuid:$uuid";
    // $note_name = 'clinical_attendance_'.date('d_m_Y').'pdf';
    // $note_path = WWW_ROOT.DS.'printpdf/'.$note_name;
    // $html2Pdfcmd = "xvfb-run -a wkhtmltopdf $execPath $note_path";
    // shell_exec($html2Pdfcmd);
    // if(file_exists($note_path)){
    //     chmod($note_path, 0777);
    // }
    // $pathtodownload="../printpdf/".$note_name;
    // echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$pathtodownload, array("target"=>"_blank","escape" => false)));
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
  <?php echo $this->element('verify-modal');?>                       
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
<table class="table table-bordered data-table">
    <thead>
        <tr>
            <?php
        if(!isset($is_excel)){
          if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE')){
          ?>
        <th>
            <?php echo $this->Form->input('checkAll', array(
                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAllclinicalattendance',
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
                echo $this->Paginator->sort('MedicalSickRecord.attendance','Attendence Description',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.compliant','Compliant',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.examination','Examination',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.digonosis','Deferential Digonosis',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.disease_id','Lab Test',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.results','Results',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.digonosis_dx','Digonosis(Dx)',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.treatement_rx','Treatement (Rx)',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.radiology','Radiology',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.drug_description','Durg Description Prescribed',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalSickRecord.status','Status',array('update'=>'#sickListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalSickData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th>Attachment</th>
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
            <td><?php echo date('m-d-Y', strtotime($data["MedicalSickRecord"]["check_up_date"]))?></td>
            <td><?php echo $data["MedicalSickRecord"]["attendance"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["compliant"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["examination"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["digonosis"]?></td>
            <td><?php echo $data["Disease"]["name"]?> </td>
            <td><?php echo $data["MedicalSickRecord"]["results"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["digonosis_dx"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["treatement_rx"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["radiology"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["drug_description"]?></td>
            <td>
            <?php if($data["MedicalSickRecord"]['status'] == 'Draft')
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
            <td>
                <?php echo $this->Html->link('View Attachment', '../files/prisnors/MEDICAL/'.$data["MedicalSickRecord"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary'))?>
            </td>
<?php
         if(!isset($is_excel) && ($isAccess == 1 && $data["MedicalSickRecord"]['status'] == 'Draft')){
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
           
                <?php echo $this->Form->create('MedicalSickRecordDelete',array('url'=>'/medicalRecords/add#sick/','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalSickRecord']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
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
<?php
echo $this->Form->end();
}else{
?>
    <span style="color:red;">No records found!</span>
<?php    
}
?>                    


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
</script>