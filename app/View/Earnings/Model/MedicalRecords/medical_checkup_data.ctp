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
        'update'                    => '#sickListingDiv',
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
    <div class="col-sm-7 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "medicalCheckupData/status:$status/prisoner_id:$prisoner_id/uuid:$uuid";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));

    // echo '&nbsp;&nbsp;';

    //  $execPath = $_SERVER['SERVER_NAME']."/uganda/medicalRecords/medicalCheckupDatapdf/status:$status/prisoner_id:$prisoner_id/uuid:$uuid";
    // $note_name = 'initial_exit_checkup_'.date('d_m_Y').'pdf';
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
<table class="table table-bordered data-table">
    <thead>
        <tr>
        <?php
        if(!isset($is_excel)){
          if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE')){
          ?>
        <th>
            <?php echo $this->Form->input('checkAll', array(
                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
            ));?>
          </th>
          <?php
        }
      }
          ?>
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
                echo $this->Paginator->sort('MedicalCheckupRecord.height_feet','Height in feet',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.height_inch','Height in inch',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.weight','Weight',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.bmi','BMI',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.tb','T.B Test',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.hiv','HIV Test',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.regenment','Regenment',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
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
            <th style="text-align: left;">
              <?php                 
                echo $this->Paginator->sort('MedicalCheckupRecord.status','Status',array('update'=>'#checkupListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecords','action' => 'medicalCheckupData','status'=> $status,'prisoner_id'=> $prisoner_id,'uuid'=> $uuid)));
            ?>
              </th>
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
        if($gender_id==2){$gender="Female";}
        else if($gender_id==1){$gender="Male";}
?>
        <tr>
        <?php
        if(!isset($is_excel)){
          if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE')){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE') && ($data['MedicalCheckupRecord']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['MedicalCheckupRecord']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['MedicalCheckupRecord']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['MedicalCheckupRecord']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE') && ($data['MedicalCheckupRecord']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['MedicalCheckupRecord']['id'],'hiddenField' => false, 'label'=>false,
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
            <td><?php 
                  
                  echo $rowCnt;
             ?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["check_up"];?></td>
             <td><?php echo $data["Prisoner"]["prisoner_no"];?></td>
             <td><?php echo $data["Prisoner"]["fullname"];?></td>
             <td><?php echo $gender;?></td>
             <td><?php echo $data["Prisoner"]["age"];?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["height_feet"]?></td>
             <td> <?php echo $data["MedicalCheckupRecord"]["height_inch"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["weight"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["bmi"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["tb"]?></td>
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
             <td><?php echo $data["MedicalCheckupRecord"]["other_disease"]?></td>
            <td><?php echo date('m-d-Y', strtotime($data["MedicalCheckupRecord"]["follow_up"]))?></td>
            <td>
            <?php if($data["MedicalCheckupRecord"]['status'] == 'Draft')
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
            
            

<?php
        if(!isset($is_excel) && ($isAccess == 1 && $data["MedicalCheckupRecord"]['status'] == 'Draft')){
            $medical_checkup_record_id   = $data['MedicalCheckupRecord']['id'];
            $medical_checkup_record_uuid = $data['MedicalCheckupRecord']['uuid'];
            if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
?>              
            <td nowrap="nowrap">
                <?php echo $this->Form->create('MedicalCheckupRecordEdit',array('url'=>'/medicalRecords/add#health_checkup','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $medical_checkup_record_id)); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
            
            <?php echo $this->Form->create('MedicalCheckupDelete',array('url'=>'/medicalRecords/add#health_checkup/','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['MedicalCheckupRecord']['id'])); ?>
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
//Dynamic confirmation modal -- END --
</script>