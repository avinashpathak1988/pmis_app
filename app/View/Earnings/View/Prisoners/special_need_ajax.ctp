<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
   
//get prisoner approval status 
  $prisoner_status = $funcall->getName($datas[0]['PrisonerSpecialNeed']['prisoner_id'],'Prisoner', 'status');
//Approval process start
$modelName = 'PrisonerSpecialNeed';
$btnName4 = Configure::read('SAVE');
$isModal4 = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName4 = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName4 = Configure::read('REVIEW');
  $isModal4 = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName4 = Configure::read('APPROVE');
  $isModal4 = 1;
}

echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$datas[0]['PrisonerSpecialNeed']['puuid'].'#special_needs'));

echo $this->Form->input('data_type',array('type'=>'hidden','value'=> 'special_needs'));
?>
<?php if($isModal4 == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardSpecialNeedBtn" class="btn btn-success btn-mini" style="display: none;"><?php echo $btnName4;?></button>
<?php
}

//Approval process end
//?>                   
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <?php 
            if(!isset($is_excel) && $prisoner_status == 'Approved'){
            ?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'specialNeedCheckAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
            <?php }?>
            <th>SL#</th>
            <th>Prison Station Name</th>
            <th>Prisoner Number</th>
            <th>Type of Disability</th>
            <th>Subcategory Disability</th>
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

      $id = $data['PrisonerSpecialNeed']['id'];
      $uuid = $data['PrisonerSpecialNeed']['puuid'];
?>
        <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
            <?php 
            if(!isset($is_excel) && $prisoner_status == 'Approved')
            {?>
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
            <td><?php echo $prison_name; ?></td>
            <td><?php echo $data['PrisonerSpecialNeed']['prisoner_no']; ?></td>
            <td>
                <?php 
                if($data['PrisonerSpecialNeed']['type_of_disability'] != 0)
                    echo $data['Disability']['name']; 
                else 
                    echo 'N/A';?>
            </td>
            <td><?php echo $data['SpecialCondition']['name']; ?></td>
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
<?php
        if(!isset($is_excel))
        {
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

?>              
            
            <td>
                <?php 
                $viewDetail = '<b>Prison Station Name: </b>'.$prison_name.'<br>';
                $viewDetail .= "<b>Prisoner Number: </b>".$data['PrisonerSpecialNeed']['prisoner_no'].'<br>';
                $viewDetail .= "<b>Type of Disability: </b>".$data['SpecialCondition']['name'].'<br>';
                $viewDetail .= "<b>Subcategory Disability: </b>".$data['Disability']['name'].'<br>';
                ?>
                <a href="javaScript:void(0);" class="pop btn btn-success" pageTitle="Special Needs" pageBody="<?php echo $viewDetail;?>">
                    <i class="icon-eye-open"></i>
                </a>
                <?php 
                //if($editPrisoner == 1){
                if($isEdit == 1)
                {
                    echo $this->Form->create('PrisonerDataEdit',array('url'=>'/Prisoners/edit/'.$uuid.'#special_needs','admin'=>false));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
                    echo $this->Form->input('pdata_type',array('type'=>'hidden','value'=> 'PrisonerSpecialNeed'));
                    ?>
                    <?php echo $this->Form->button('<i class="icon-edit"></i>',array('label'=>'Edit','class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); 
                    echo $this->Form->end();
                }
                if($isDelete == 1){?> 
                    <?php echo $this->Form->button('<i class="icon-trash"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteSpecialNeed('$id');"));
                }
                //}?>
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
<?php
echo $this->Form->end(); 
echo $this->Js->writeBuffer();
//pagination start 
 if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#specialneed_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisoners',
            'action'                => 'specialNeedAjax',
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
    $exUrl = "specialNeedAjax/prisoner_id:$prisoner_id";
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
//pagination end 
?>
<script type="text/javascript">
//Delete Dynamic confirmation modal -- START --
function ShowDeleteConfirm(formId, funcName) {
    AsyncConfirmYesNo(
            "Are you sure want to delete?",
            'Delete',
            'Cancel',
            MyYesDelete,
            MyNoDelete,
            formId,
            'Delete',
            funcName
        );
}
function MyYesDelete(formId, funcName) 
{
  deleteIdProof(formId);
}
function MyNoDelete() {
}
//Delete Dynamic confirmation modal -- END --

$(function(){
$("#ApprovalProcessFormSpecialNeedAjaxForm").validate({
     
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
  
  $('#verifyBtn').click(function(){
        if($("#ApprovalProcessFormSpecialNeedAjaxForm").valid()){
            if( !confirm('Are you sure to save?')) {
                            return false;
            }
        }
    });
        $("#specialNeedCheckAll").click(function(){
            $('#ApprovalProcessFormSpecialNeedAjaxForm input:checkbox').not(this).prop('checked', this.checked);
        });
        $('#ApprovalProcessFormSpecialNeedAjaxForm input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('#ApprovalProcessFormSpecialNeedAjaxForm input[type="checkbox"]:checked').length;
          
          var is_checkall = $('#ApprovalProcessFormSpecialNeedAjaxForm input[id="specialNeedCheckAll"]:checked').length;

          if(is_checkall == 1 && atLeastOneIsChecked == 1)
          { 
            $('#specialNeedCheckAll').attr('checked', false);
            $('#forwardSpecialNeedBtn').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#forwardSpecialNeedBtn').show();
          }
          else 
          {
            $('#forwardSpecialNeedBtn').hide();
          }
        });
});
//Dynamic confirmation modal -- START --
var btnName4 = '<?php echo $btnName4;?>';
var isModal4 = '<?php echo $isModal4;?>';
function ShowConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName4+"?",
            btnName4,
            'Cancel',
            MyYesSpecialNeedFunction,
            MyNoSpecialNeedFunction
        );
}

function MyYesSpecialNeedFunction() {
  if(isModal == 1)
  {
    $('.verifyPopupModal').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormSpecialNeedAjaxForm').submit();
  }
}
function MyNoSpecialNeedFunction() {
    
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