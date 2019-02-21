<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){   

  //get prisoner approval status 
  $prisoner_status = $funcall->getName($datas[0]['PrisonerKinDetail']['prisoner_id'],'Prisoner', 'status');

//Approval process start

$modelName = 'PrisonerKinDetail';
$btnName2 = Configure::read('SAVE');
$isModal2 = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName2 = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName2 = Configure::read('REVIEW');
  $isModal2 = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName2 = Configure::read('APPROVE');
  $isModal2 = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$datas[0]['PrisonerKinDetail']['puuid'].'#kin_details'));
echo $this->Form->input('data_type',array('type'=>'hidden','value'=> 'kin_details'));
?>
<?php if($isModal2 == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){?>

<button type="button" onclick="ShowKinConfirmYesNo();" tabcls="next" id="KinforwardBtn" style="display:none;" class="btn btn-success btn-mini"><?php echo $btnName2;?></button>
<?php
}
//Approval process start
?> 
<style type="text/css">
  #btnYesConfirmYesNo, #btnNoConfirmYesNo{display: inline-block !important;}
</style>

<table class="table table-bordered data-table table-responsive">
    <thead>
            <?php 
           if(!isset($is_excel) && $prisoner_status == 'Approved'){
          ?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'KinCheckAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
          <?php }?>
            <th>SL#</th>
            <th>First Name</th>
            <th>Surname</th>
            <th>Relationship</th>
            <th>Phone No</th>
            <th>Village</th>
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

      $id = $data['PrisonerKinDetail']['id'];
      $puuid = $data['PrisonerKinDetail']['puuid'];
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
            <td><?php echo $data['PrisonerKinDetail']['first_name']; ?></td>
            <td><?php if ($data['PrisonerKinDetail']['last_name']!='') {
                  echo $data['PrisonerKinDetail']['last_name'];
             
            }else{echo Configure::read('NA');}  ?></td>
            <td><?php if(isset($data['Relationship']['name']))echo $data['Relationship']['name']; ?></td>
            <td>
                <?php 
                if(isset($data['CountryPhoneCode']['country_code']))
                    echo $data['CountryPhoneCode']['country_code'].' ';
                echo $data['PrisonerKinDetail']['phone_no']; 
                if(isset($data['PrisonerKinDetail']['phone_no2']) && ($data['PrisonerKinDetail']['phone_no2'] != ''))
                {
                    if(isset($data['CountryPhoneCode2']['country_code']))
                        echo '<br>'.$data['CountryPhoneCode2']['country_code'].' ';
                    echo $data['PrisonerKinDetail']['phone_no2'];
                }?>
            </td>
            <td><?php if ($data['PrisonerKinDetail']['village']!='') {
             echo $data['PrisonerKinDetail']['village'];
            }else{echo Configure::read('NA');}  ?></td>
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
?>              
            
            <td>
			  <table>
                <?php 
                $viewDetail = '<b>First Name: </b>'.$data['PrisonerKinDetail']['first_name'].'<br>';
                $viewDetail .= "<b>Middle Name: </b>";
                if ($data['PrisonerKinDetail']['middle_name']!='') {
                 $viewDetail .=$data['PrisonerKinDetail']['middle_name'].'<br>';
                }else{$viewDetail .=Configure::read('NA').'<br>';}
                
                
                $viewDetail .= "<b>Surname: </b>";
                if ($data['PrisonerKinDetail']['last_name']!='') {
                  $viewDetail .= $data['PrisonerKinDetail']['last_name'].'<br>';
                }else{
                  $viewDetail .= Configure::read('NA').'<br>';
                }
                
                $viewDetail .= "<b>Relationship: </b>".$data['Relationship']['name'].'<br>';
                $viewDetail .= "<b>Sex: </b>".$data['Gender']['name'].'<br>';
                $viewDetail .= "<b>National Id Number: </b>";
                if ($data['PrisonerKinDetail']['national_id_no']!='') {
                  $viewDetail .= $data['PrisonerKinDetail']['national_id_no'];
                }else{$viewDetail .= Configure::read('NA').'<br>'; }
                $viewDetail .= "<b>Phone Number: </b>".$data['CountryPhoneCode']['country_code'].' '.$data['PrisonerKinDetail']['phone_no'].'<br>';
                if(!empty($data['PrisonerKinDetail']['phone_no2']))
                    $viewDetail .= $data['CountryPhoneCode2']['country_code'].' '.$data['PrisonerKinDetail']['phone_no2'].'<br>';
                $viewDetail .= "<b>Physical Address: </b>";
                if ( $data['PrisonerKinDetail']['physical_address']) {
                   $viewDetail .= $data['PrisonerKinDetail']['physical_address'].'<br>';
                }else{ $viewDetail .= Configure::read('NA').'<br>';}
                $viewDetail .= "<b>Village: </b>";
                if ($data['PrisonerKinDetail']['village']) {
                 $viewDetail .= $data['PrisonerKinDetail']['village'].'<br>';
                }else{ $viewDetail .= Configure::read('NA').'<br>';}
                 $viewDetail .= "<b>Parish: </b>";
                 if ($data['PrisonerKinDetail']['parish']) {
                   $viewDetail .= $data['PrisonerKinDetail']['parish'].'<br>';
                 }else{ $viewDetail .= Configure::read('NA').'<br>';}
                $viewDetail .= "<b>Gombolola: </b>";
                if ($data['PrisonerKinDetail']['gombolola']) {
                $viewDetail .= $data['PrisonerKinDetail']['gombolola'].'<br>';
                }else{ $viewDetail .= Configure::read('NA').'<br>';}
                $viewDetail .= "<b>District: </b>";
                if ($data['District']['name']) {
                $viewDetail .= $data['District']['name'].'<br>';
                }else{ $viewDetail .= Configure::read('NA').'<br>';}

                $viewDetail .= "<b>Name Of Chief: </b>";
                if ($data['PrisonerKinDetail']['chief_name']) {
                $viewDetail .= $data['PrisonerKinDetail']['chief_name'].'<br>';
                }else{ $viewDetail .= Configure::read('NA').'<br>';}
                $viewDetail .= "<b>Passport No: </b>";
                if ($data['PrisonerKinDetail']['passport_no']) {
                $viewDetail .= $data['PrisonerKinDetail']['passport_no'].'<br>';
                }else{ $viewDetail .= Configure::read('NA').'<br>';}
                 $viewDetail .= "<b>Voter ID No: </b>";
                if ($data['PrisonerKinDetail']['voter_id_no']) {
                $viewDetail .= $data['PrisonerKinDetail']['voter_id_no'].'<br>';
                }else{$viewDetail .= Configure::read('NA').'<br>';
                }
              
                ?>
                <a href="javaScript:void(0);" class="pop btn btn-success" pageTitle="Kin Details" pageBody="<?php echo $viewDetail;?>">
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
                  echo $this->Form->create('PrisonerDataEdit',array('url'=>'/Prisoners/edit/'.$puuid.'#kin_details','admin'=>false, 'id'=>'KinEdit'));?> 
                  <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
                  echo $this->Form->input('pdata_type',array('type'=>'hidden','value'=> 'PrisonerKinDetail'));
                  ?>
                  <?php echo $this->Form->button('<i class="icon-edit"></i>',array('label'=>'Edit','type'=>'submit','class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); 
                  echo $this->Form->end();
                }
                if($isDelete == 1)
                { 
                  echo $this->Form->button('<i class="icon-trash"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteKin('$id');")); 
                } 
        
                ?>
			  </table>
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
        'update'                    => '#prisonerkindata_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisoners',
            'action'                => 'kinDetailAjax',
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
    $exUrl = "kinDetailAjax/prisoner_id:$prisoner_id";
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
$(function(){
$("#ApprovalProcessFormKinDetailAjaxForm").validate({
     
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
        if($("#ApprovalProcessFormKinDetailAjaxForm").valid()){
            if( !confirm('Are you sure to save?')) {
                            return false;
            }
        }
    });
        $("#KinCheckAll").click(function(){
            $('#ApprovalProcessFormKinDetailAjaxForm input:checkbox').not(this).prop('checked', this.checked);
        });

  $('#ApprovalProcessFormKinDetailAjaxForm input[type="checkbox"]').click(function(){
    
    var atLeastOneIsChecked = $('#ApprovalProcessFormKinDetailAjaxForm input[type="checkbox"]:checked').length;
    var is_checkall2 = $('#ApprovalProcessFormKinDetailAjaxForm input[id="KinCheckAll"]:checked').length;
    if(is_checkall2 == 1 && atLeastOneIsChecked == 1)
    { 
      $('#KinCheckAll').attr('checked', false);
      $('#KinforwardBtn').hide();
    }
    else if(atLeastOneIsChecked >= 1)
    {
      $('#KinforwardBtn').show();
    }
    else 
    {
      $('#KinforwardBtn').hide();
    }
  });
});
//Dynamic confirmation modal -- START --
var btnName2 = '<?php echo $btnName2;?>';
var isModal2 = '<?php echo $isModal2;?>';
function ShowKinConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName2+"?",
            btnName2,
            'Cancel',
            KinMyYesFunction,
            KinMyNoFunction
        );
}

function KinMyYesFunction() {
  if(isModal2 == 1)
  {
    $('.verifyPopupModal').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormKinDetailAjaxForm').submit();
  }
}
function KinMyNoFunction() {
    
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
