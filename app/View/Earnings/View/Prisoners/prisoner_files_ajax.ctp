<?php //debug($datas); //exit;
if(is_array($datas) && count($datas)>0){
  
  //get prisoner approval status 
  $prisoner_status = $funcall->getName($datas[0]['PrisonerCaseFile']['prisoner_id'],'Prisoner', 'status');
  //echo $prisoner_status; exit;
  ?>  
  <?php 
//Approval process start
$modelName = 'PrisonerCaseFile';
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

echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$datas[0]['Prisoner']['uuid'].'#admission_details'));

echo $this->Form->input('data_type',array('type'=>'hidden','value'=> 'admission_details'));
?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){?>

<button type="button" onclick="ShowCaseFileConfirmYesNo();" tabcls="next" id="forwardCaseFileBtn" class="btn btn-success btn-mini" style="display:none;"><?php echo $btnName;?></button>
<?php
}

//Approval process end
?>                 
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <?php 
           if(!isset($is_excel) && $prisoner_status == 'Approved'){
          ?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'caseFileCheckAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
          <?php }?>
            <th>SL#</th>
            <th>
                <?php 
                echo $this->Paginator->sort('admission_details.file_no','File No.',array('update'=>'#prisoner_files_listview','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'prisonerFilesAjax', 'prisoner_id' => $prisoner_id)));
                ?>
            </th>
            <th>
                <?php 
                echo $this->Paginator->sort('admission_details.case_file_no','Case File No.',array('update'=>'#prisoner_files_listview','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'prisonerFilesAjax', 'prisoner_id' => $prisoner_id)));
                ?>
            </th>
            <th>Court Category</th>
            <th>Court Name</th>
            <th>Status</th>
<?php
if(!isset($is_excel)){
?> 
            <th>Action</th>
<?php
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));

    foreach($datas as $data){

        $id = $data['PrisonerCaseFile']['id'];
        $puuid = $data['PrisonerCaseFile']['puuid'];
?>
        <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
            <?php 
           if(!isset($is_excel) && $prisoner_status == 'Approved'){
          ?>
            <td>
              <?php 
              if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data[$modelName]['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data[$modelName]['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              ?>
            </td>
          <?php }?>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data['PrisonerCaseFile']['file_no']; ?></td>
            <td><?php echo $data['PrisonerCaseFile']['case_file_no']; ?></td>
            <td><?php echo $data['Courtlevel']['name']; ?></td>
            <td><?php echo $data['CourtCase']['name']; ?></td>
            <td>
            <?php if($data[$modelName]['status'] == 'Draft')
            {
              echo $data[$modelName]['status'];
            }
            else 
            {
              $status_info = '<b>Status: </b>'.$data[$modelName]['status'].'<br>';
              if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
                $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
              if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
                $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $data[$modelName]['status'];?></a>
              <?php 
            }?>
          </td>
          <td>
<?php
        if(!isset($is_excel) )
        {
          $viewDetail = '<b>Court File No: </b>';
          $viewDetail .= (isset($data['PrisonerCaseFile']['court_file_no']) && $data['PrisonerCaseFile']['court_file_no']!='' ? $data['PrisonerCaseFile']['court_file_no'] : Configure::read('NA')).'<br>';

          $viewDetail .= '<b>Case File No: </b>'.$data['PrisonerCaseFile']['case_file_no'].'<br>';
          $viewDetail .= '<b>C.R.B No: </b>';
          $viewDetail .= ($data['PrisonerCaseFile']['crb_no']!='' ? $data['PrisonerCaseFile']['crb_no'] : Configure::read('NA')).'<br>';
          if($data['PrisonerCaseFile']['file_type'] == 'Convict')
          {
            $viewDetail .= '<b>Date Of Warrant: </b>'.date('d-m-Y', strtotime($data['PrisonerCaseFile']['date_of_warrant'])).'<br>';
          }
          $viewDetail .= '<b>Court Category: </b>'.$data['Courtlevel']['name'].'<br>';
          $viewDetail .= '<b>Court Name: </b>'.$data['CourtCase']['name'].'<br>';

          $viewDetail .= '<b>Jurisdiction area: </b>';
          $viewDetail .= (isset($data['Magisterial']['name']) && $data['Magisterial']['name']!='' ? $data['Magisterial']['name'] : Configure::read('NA')).'<br>';

          $judicialOfficerLevel = $funcall->getJudicialOfficerLevel($data['PrisonerCaseFile']['courtlevel_id']);

          $viewDetail .= '<b>'.$judicialOfficerLevel.': </b>';
          if($data['PrisonerCaseFile']['judicial_officer'] != '')
            $viewDetail .= $data['PrisonerCaseFile']['judicial_officer'].'<br>';
          else 
            $viewDetail .= Configure::read('NA').'<br>';

          if($data['PrisonerCaseFile']['highcourt_file_no'] != '')
            $viewDetail .= '<b>High Court File No: </b>'.$data['PrisonerCaseFile']['highcourt_file_no'].'<br>';
          //display file counts -- START --
          $viewDetail .= '<br>';
          if(isset($data['PrisonerOffence']) && count($data['PrisonerOffence']) > 0)
          {
            $cnt = 0;
            foreach($data['PrisonerOffence'] as $counts)
            {
              $cnt_no = $cnt+1;
              $viewDetail .=(count($data['PrisonerOffence']) == 1 ? '<b><u>Offence</u></b>' : '<b><u>Count-'.$cnt_no.'</u></b>');
              $viewDetail .= '<br>';

              $viewDetail .= '<b>Offence: </b>'.$funcall->getName($data['PrisonerOffence'][$cnt]['offence'],'Offence','name').'<br>';

              $viewDetail .= '<b>Section of law: </b>';
              if($data['PrisonerOffence'][$cnt]['section_of_law'] != '')
                $viewDetail .= $data['PrisonerOffence'][$cnt]['section_of_law'].'<br>';
              else 
                $viewDetail .= Configure::read('NA').'<br>';

              $viewDetail .= '<b>Offence category: </b>'.$funcall->getName($data['PrisonerOffence'][$cnt]['offence_category_id'],'OffenceCategory','name').'<br>';

              $viewDetail .= '<b>Place of offence: </b>';
              $viewDetail .=($data['PrisonerOffence'][$cnt]['place_of_offence'] != '' ? ucfirst($data['PrisonerOffence'][$cnt]['place_of_offence']) : Configure::read('NA'));
              $viewDetail .= '<br>';

              $viewDetail .= '<b>District of offence: </b>';
              $viewDetail .=($data['PrisonerOffence'][$cnt]['district_id'] != '' ? $funcall->getName($data['PrisonerOffence'][$cnt]['district_id'],'District','name') : Configure::read('NA'));

              $viewDetail .= '<br>';
              $viewDetail .= '<br>';
              $cnt++;
            }
          }
          //display file counts -- END -- 

          if($data['PrisonerCaseFile']['file_type'] == 'Convict')
          {

          }
          ?>
          <a href="javaScript:void(0);" class="pop btn btn-success" pageTitle="Case File Details" pageBody="<?php echo $viewDetail;?>">
              <i class="icon-eye-open"></i>
          </a>
          <?php 
          $isEdit = 0;
          $isDelete = 0;
          if($login_user_type_id == Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['status'] == 'Draft' || $data[$modelName]['status'] == 'Review-Reject' || $data[$modelName]['status'] == 'Approve-Reject') && $data[$modelName]['login_user_id'] == $login_user_id)
          {
              $isEdit = 1;
              $isDelete = 1;
          }
          if($login_user_type_id == Configure::read('OFFICERINCHARGE_USERTYPE') && $data[$modelName]['login_user_id'] == $login_user_id)
          {
              $isEdit = 1;
              $isDelete = 1;
          }
          if($isEdit == 1)
          {
            $editFormId = "'prisonerIdProofEditForm-".$id."'";
            // echo $this->Form->create('PrisonerDataEdit',array('url'=>'/Prisoners/edit/'.$puuid.'#id_proof_details','admin'=>false, 'id'=>$editFormId)); 
            // echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
            // echo $this->Form->input('pdata_type',array('type'=>'hidden','value'=> 'PrisonerCaseFile'));
            // echo $this->Form->button('<i class="icon-edit"></i>', array('label'=>'Edit','type'=>'submit', 'class'=>'btn btn-primary','div'=>false, 'onclick'=>'return editCaseFile('.$editFormId.')')); 
            
            // echo $this->Form->end();
          }
          if($isDelete == 1)
          {
            echo $this->Form->button('<i class="icon-trash"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"ShowDeleteCaseFileConfirm($id, 'deleteCaseFile');"));
          }?>
            
 
</td>
<?php 
}
  ?>
        </tr>
<?php
        $rowCnt++;
    }
?>
    </tbody>
</table>
<?php echo $this->Form->end(); 
echo $this->Js->writeBuffer();
//pagination -- START -- 
  if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#prisoner_files_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisoners',
            'action'                => 'prisonerFilesAjax',
            'prisoner_id'             => $prisoner_id,

        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
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
    $exUrl = "prisonerFilesAjax/prisoner_id:$prisoner_id";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    </div>
</div>
<?php
    }

//pagination -- END -- 
?>
<script type="text/javascript">
  //Edit Dynamic confirmation modal -- START --
function editCaseFile(formId) {
  
  AsyncConfirmYesNo(
      "Are you sure want to edit?",
      'Edit',
      'Cancel',
      function(){
        //alert(formId);
        //$('#'+formId).submit();
      },
      function(){}
  );
}
//Delete Dynamic confirmation modal -- START --
function ShowDeleteCaseFileConfirm(formId, funcName) 
{
  AsyncConfirmYesNo(
      "Are you sure want to delete?",
      'Delete',
      'Cancel',
      MyYesDeleteCaseFile,
      MyNoDeleteCaseFile,
      formId,
      'Delete',
      funcName
  );
}
function MyYesDeleteCaseFile(formId, funcName) 
{
  deleteCaseFile(formId);
}
function MyNoDeleteCaseFile() {
}
//Delete Dynamic confirmation modal -- END --

$(function(){
$("#ApprovalProcessFormprisonerFilesAjaxForm").validate({
     
      ignore: ".ignore, .select2-input",
            rules: {  
                'data[ApprovalProcessForm][type]': {
                    required: true,
                },
                'data[ApprovalProcessForm][remark]': {
                    maxlength: 146,
                },
            },
            messages: {
                'data[ApprovalProcessForm][type]': {
                    required: "Please choose verification type.",
                },
            },
               
    });
});
$(document).ready(function(){
  
  $('#verifyCaseFileBtn').click(function(){
        if($("#ApprovalProcessFormprisonerFilesAjaxForm").valid()){
            if( !confirm('Are you sure to save?')) {
                            return false;
            }
        }
    });
        $("#caseFileCheckAll").click(function(){ 
            $('#ApprovalProcessFormPrisonerFilesAjaxForm input:checkbox').not(this).prop('checked', this.checked);
        });
        $('#ApprovalProcessFormPrisonerFilesAjaxForm input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('#ApprovalProcessFormPrisonerFilesAjaxForm input[type="checkbox"]:checked').length;
          
          var is_checkall = $('#ApprovalProcessFormprisonerFilesAjaxForm input[id="caseFileCheckAll"]:checked').length;

          if(is_checkall == 1 && atLeastOneIsChecked == 1)
          { 
            $('#caseFileCheckAll').attr('checked', false);
            $('#forwardCaseFileBtn').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#forwardCaseFileBtn').show();
          }
          else 
          {
            $('#forwardCaseFileBtn').hide();
          }
        });
});
//Dynamic confirmation modal -- START --
var btnName = '<?php echo $btnName;?>';
var isModal = '<?php echo $isModal;?>';
function ShowCaseFileConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName+"?",
            btnName,
            'Cancel',
            MyYesCaseFileFunction,
            MyNoCaseFileFunction
        );
}

function MyYesCaseFileFunction() {
  if(isModal == 1)
  {
    $('#ApprovalProcessFormPrisonerFilesAjaxForm .verifyPopupModal').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormPrisonerFilesAjaxForm').submit();
  }
}
function MyNoCaseFileFunction() {
    
}
//Dynamic confirmation modal -- END --
</script>              
<?php 
}else{
?>
    ...
<?php    
}
?>    
