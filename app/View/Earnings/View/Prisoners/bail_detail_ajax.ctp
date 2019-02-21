<?php 
if(is_array($datas) && count($datas)>0){

//Approval process start
$modelName = 'PrisonerBailDetail';
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

echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$datas[0]['PrisonerBailDetail']['puuid'].'#bail_details'));

echo $this->Form->input('data_type',array('type'=>'hidden','value'=> 'bail_details'));
?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="Bail_forwardBtn" class="btn btn-success btn-mini" style="display:none;"><?php echo $btnName;?></button>
<?php
}

//Approval process end

    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#offencecount_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisoners',
            'action'                => 'recaptureDetailAjax',
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
    $exUrl = "recaptureDetailAjax/prisoner_id:$prisoner_id";
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
?>                    
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <?php 
           if(!isset($is_excel)){
          ?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'Bail_CheckAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
          <?php }?>
            <th>SL#</th>
            
            <th>Bail Start Date</th>
            <th>Bail End Date</th>
            <th>Date of Renter To Prison</th>
            <th>Bail Cancel Date</th>
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

      $id = $data['PrisonerBailDetail']['id'];
      $puuid = $data['PrisonerBailDetail']['puuid'];
?>
        <tr>
            <?php 
           if(!isset($is_excel)){
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
            
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerBailDetail']['bail_start_date'])); ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerBailDetail']['bail_end_date'])); ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerBailDetail']['reenter_to_prison_date'])); ?></td>
            <td>
              <?php if(isset($data['PrisonerBailDetail']['bail_cancel_date']) && ($data['PrisonerBailDetail']['bail_cancel_date'] != '0000-00-00'))
                echo date('d-m-Y', strtotime($data['PrisonerBailDetail']['bail_cancel_date'])); 
              else echo 'N/A';?>
            </td>
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
        if(!isset($is_excel)){
            if($data[$modelName]['status'] == 'Draft'){
?>              
            
            <td>
                <?php echo $this->Form->create('PrisonerDataEdit',array('url'=>'/Prisoners/edit/'.$puuid.'#recaptured_details','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
                echo $this->Form->input('pdata_type',array('type'=>'hidden','value'=> 'PrisonerBailDetail'));
                ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
            
                   <?php echo $this->Form->button('Delete', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"ShowBailDeleteConfirm($id, 'deleteBail');"))?>
            </td>

  <?php
} }
  ?>
        </tr>
<?php
        $rowCnt++;
    }
?>
    </tbody>
</table>
<script type="text/javascript">
//Delete Dynamic confirmation modal -- START --
function ShowBailDeleteConfirm(formId, funcName) {
    AsyncConfirmYesNo(
            "Are you sure want to delete?",
            'Delete',
            'Cancel',
            MyYesBailDelete,
            MyNoBailDelete,
            formId,
            'Delete',
            funcName
        );
}
function MyYesBailDelete(formId, funcName) 
{
  deleteBail(formId);
}
function MyNoBailDelete() {
}
//Delete Dynamic confirmation modal -- END --

$(function(){
$("#ApprovalProcessFormBailDetailAjaxForm").validate({
     
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
        if($("#ApprovalProcessFormBailDetailAjaxForm").valid()){
            if( !confirm('Are you sure to save?')) {
                            return false;
            }
        }
    });
        $("#Bail_CheckAll").click(function(){
            $('#ApprovalProcessFormBailDetailAjaxForm input:checkbox').not(this).prop('checked', this.checked);
        });
        $('#ApprovalProcessFormBailDetailAjaxForm input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('#ApprovalProcessFormBailDetailAjaxForm input[type="checkbox"]:checked').length;
          
          var is_checkall = $('#ApprovalProcessFormBailDetailAjaxForm input[id="Bail_CheckAll"]:checked').length;

          if(is_checkall == 1 && atLeastOneIsChecked == 1)
          { 
            $('#Bail_CheckAll').attr('checked', false);
            $('#Bail_forwardBtn').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#Bail_forwardBtn').show();
          }
          else 
          {
            $('#Bail_forwardBtn').hide();
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
    $('.verifyPopupModal').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormBailDetailAjaxForm').submit();
  }
}
function MyNoFunction() {
    
}
//Dynamic confirmation modal -- END --
</script>          
<?php echo $this->Form->end(); 
echo $this->Js->writeBuffer();
}else{
?>
    ...
<?php    
}
?>                    