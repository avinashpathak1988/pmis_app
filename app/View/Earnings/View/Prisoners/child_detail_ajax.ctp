<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    
//get prisoner approval status 
  $prisoner_status = $funcall->getName($datas[0]['PrisonerChildDetail']['prisoner_id'],'Prisoner', 'status');
//Approval process start

$modelName = 'PrisonerChildDetail';
$btnName3 = Configure::read('SAVE');
$isModal3 = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName3 = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName3 = Configure::read('REVIEW');
  $isModal3 = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName3 = Configure::read('APPROVE');
  $isModal3 = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$datas[0]['PrisonerChildDetail']['puuid'].'#child_details'));
echo $this->Form->input('data_type',array('type'=>'hidden','value'=> 'child_details'));?>
<?php if($isModal3 == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="ChildForwardBtn" style="display:none;" class="btn btn-success btn-mini"><?php echo $btnName3;?></button>
<?php
}
//Approval process start
?>                   
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <?php 
           if(!isset($is_excel) && $prisoner_status == 'Approved'){
          ?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'ChildCheckAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
          <?php }?>
            <th>SL#</th>
            <th>Name Of Child</th>
            <th>Photo</th>
            <th>Father Number</th>
            <th>Medical Document</th>
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

      $id = $data['PrisonerChildDetail']['id'];
      $puuid = $data['PrisonerChildDetail']['puuid'];
      $child_medical_record = 'N/A';
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
            <td><?php echo $data['PrisonerChildDetail']['name']; ?></td>
            <td class="img75"><a class="example-image-link preview_image" href="<?php echo $this->webroot; ?>files/childs/photo/<?php echo $data["PrisonerChildDetail"]["child_photo"];?>" data-lightbox="example-set">
                <?php $child_image = 'N/A';
                if($data["PrisonerChildDetail"]["child_photo"] != '')
                {
                    $child_image = Configure::read('BASE_URL').'files/childs/photo/'.$data['PrisonerChildDetail']['child_photo'];
                    echo $this->Html->image('../files/childs/photo/'.$data["PrisonerChildDetail"]["child_photo"], array('escape'=>false, 'class'=>'img', 'alt'=>''));
                }
                ?></a>
            </td>
            <td><?php if ($data['PrisonerChildDetail']['father_name']!='') {
             echo $data['PrisonerChildDetail']['father_name'];
            } else{echo Configure::read('NA');} ?></td>
            <td class="img75">
                <?php 
                if($data["PrisonerChildDetail"]["child_medical_document"] != '')
                {
                    //echo $this->Html->image('../files/childs/medical_document/'.$data["PrisonerChildDetail"]["child_medical_document"], array('escape'=>false, 'class'=>'img', 'alt'=>'', 'width'=>'100'));
                    echo $child_medical_record = $this->Html->link($this->Html->image('../files/childs/medical_document/'.$data["PrisonerChildDetail"]["child_medical_document"],
array("alt" => "Child Photo", 'class'=>'img', 'alt'=>'')), '../files/childs/medical_document/'.$data["PrisonerChildDetail"]["child_medical_document"], array('class'
=>'regular', 'escape' => false, 'target'=>'_blank'));
                }
                ?>
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
          }?>              
            
            <td>
                <?php 
                $viewDetail = '<b>Name Of Child: </b>'.$data['PrisonerChildDetail']['name'].'<hr>';
                $viewDetail .= "<b>Father's Name: </b>";
                if ($data['PrisonerChildDetail']['father_name']!='') {
                 $viewDetail .=$data['PrisonerChildDetail']['father_name'].'<br>';
                }else{$viewDetail .=Configure::read('NA').'<hr>';}
                $viewDetail .= "<b>Mother's Name: </b>";
                if ($data['PrisonerChildDetail']['mother_name']!='') {
                 $viewDetail .=$data['PrisonerChildDetail']['mother_name'].'<br>';
                }else{$viewDetail .=Configure::read('NA').'<hr>';}
                 $viewDetail .= "<b>Relationship with child: </b>";
                if ($data['PrisonerChildDetail']['relation_with_child']!='') {
                 $viewDetail .=$data['PrisonerChildDetail']['relation_with_child'].'<br>';
                }else{$viewDetail .=Configure::read('NA').'<hr>';}
                
               
                $viewDetail .= "<b>Date Of Birth: </b>".date('d-m-Y', strtotime($data['PrisonerChildDetail']['dob'])).'<hr>';
                $viewDetail .= "<b>Place Of Birth: </b>".$data['PrisonerChildDetail']['birth_place'].'<hr>';
                $viewDetail .= "<b>District Of Birth: </b>".$data['District']['name'].'<hr>';
                 $viewDetail .= "<b>Born In Hospital: </b>";
                if ($data['PrisonerChildDetail']['hospital_name']!='') {
                  $viewDetail .= $funcall->getName($data['PrisonerChildDetail']['hospital_name'],'Hospital','name').'<hr>';
                }else{$viewDetail .=Configure::read('NA').'<hr>';}
                
                $viewDetail .= "<b>Gender: </b>".$data['Gender']['name'].'<hr>';
                $viewDetail .= "<b>Child Medical Condition: </b>";
                if ($data['PrisonerChildDetail']['medical_cond']!='') {
                  $viewDetail .=$data['PrisonerChildDetail']['medical_cond'].'<br>';
                }else{$viewDetail .=Configure::read('NA').'<hr>';}
                $viewDetail .= "<b>Child Physical Condition: </b>";
                if ($data['PrisonerChildDetail']['physical_cond']!='') {
                  $viewDetail .=$data['PrisonerChildDetail']['physical_cond'].'<br>';
                }else{$viewDetail .=Configure::read('NA').'<hr>';}
                $viewDetail .= "<b>Child Description: </b>";
                if ($data['PrisonerChildDetail']['child_desc']!='') {
                  $viewDetail .=$data['PrisonerChildDetail']['child_desc'].'<br>';
                }else{$viewDetail .=Configure::read('NA').'<hr>';}
                //$viewDetail .= "<b>Child Medical Record: </b>".$child_medical_record.'<hr>';
                //$viewDetail .= "<b>Child Photo: </b><img src='".$child_image."' width='100'/>";
                ?>
                <a href="javaScript:void(0);" class="pop btn btn-success" pageTitle="Child Details" pageBody="<?php echo $viewDetail;?>">
                    <i class="icon-eye-open"></i>
                </a>

                <?php 
                //if($editPrisoner == 1){
                    if($isEdit == 1)
                    {
                        echo $this->Form->create('PrisonerDataEdit',array('url'=>'/Prisoners/edit/'.$puuid.'#child_details','admin'=>false));?> 
                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
                        echo $this->Form->input('pdata_type',array('type'=>'hidden','value'=> 'PrisonerChildDetail'));
                        ?>
                        <?php echo $this->Form->button('<i class="icon-edit"></i>',array('label'=>'Edit','type'=>'submit','class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); 
                        echo $this->Form->end();
                    }
                    if($isDelete == 1){?>
                        <?php echo $this->Form->button('<i class="icon-trash"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteChild('$id');"));
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
<?php echo $this->Form->end();
echo $this->Js->writeBuffer();
//pagination start 
if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#prisonerchilddata_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisoners',
            'action'                => 'childDetailAjax',
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
    $exUrl = "childDetailAjax/prisoner_id:$prisoner_id";
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
//?>
<script type="text/javascript">
var btnName3 = '<?php echo $btnName3;?>';
var isModal3 = '<?php echo $isModal3;?>';
$(function(){
    if(isModal3 == 1){
        $("#ApprovalProcessFormChildDetailAjaxForm").validate({
             
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
    }
});
$(document).ready(function(){
  
  $('#verifyBtn').click(function(){
        if($("#ApprovalProcessFormChildDetailAjaxForm").valid()){
            if( !confirm('Are you sure to save?')) {
                            return false;
            }
        }
    });
    $("#ChildCheckAll").click(function(){
        $('#ApprovalProcessFormChildDetailAjaxForm input:checkbox').not(this).prop('checked', this.checked);
    });

  $('#ApprovalProcessFormChildDetailAjaxForm input[type="checkbox"]').click(function(){
    
    var atLeastOneIsChecked = $('#ApprovalProcessFormChildDetailAjaxForm input[type="checkbox"]:checked').length;
    var is_checkall2 = $('#ApprovalProcessFormChildDetailAjaxForm input[id="ChildCheckAll"]:checked').length;
    if(is_checkall2 == 1 && atLeastOneIsChecked == 1)
    { 
      $('#ChildCheckAll').attr('checked', false);
      $('#ChildForwardBtn').hide();
    }
    else if(atLeastOneIsChecked >= 1)
    {
      $('#ChildForwardBtn').show();
    }
    else 
    {
      $('#ChildForwardBtn').hide();
    }
  });
});
//Dynamic confirmation modal -- START --

function ShowConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName3+"?",
            btnName3,
            'Cancel',
            ChildMyYesFunction,
            ChildMyNoFunction
        );
}

function ChildMyYesFunction() { 
  if(isModal3 == 1)
  {
    $('#verify').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormChildDetailAjaxForm').submit();
  }
}
function ChildMyNoFunction() {
    
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