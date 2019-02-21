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
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'RecordFood',
            'action'                => 'indexAjax',
            'from'                  => $from,
            'to'                    => $to,  
            'status'                => $status,
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/recordFood'));?>
<?php echo $this->Form->input('modelname', array('type'=>'hidden','value'=>'RecordFood'))?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button>
<?php
   // }
?>
<div style="overflow-x:scroll;">
<table id="districtTable" class="table table-bordered table-striped">
  <thead>
    <tr>
    <th>
            <?php echo $this->Form->input('checkAll', array(
                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
            ));?>
          </th>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th><?php                 
                echo $this->Paginator->sort('RecordFood.date','Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'RecordFood','action' => 'indexAjax','status'=> $status,'from'=> $from,'to'=> $to)));
            ?></th>
      <th><?php                 
                echo $this->Paginator->sort('RecordFood.prison_code','Prison Station Code',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'RecordFood','action' => 'indexAjax','status'=> $status,'from'=> $from,'to'=> $to)));
            ?></th>
      <th><?php                 
                echo $this->Paginator->sort('Prison.name','Name of the Station',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'RecordFood','action' => 'indexAjax','status'=> $status,'from'=> $from,'to'=> $to)));
            ?></th>
      <th><?php                 
                echo $this->Paginator->sort('RecordFood.comment_on_food','Comment On Food',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'RecordFood','action' => 'indexAjax','status'=> $status,'from'=> $from,'to'=> $to)));
            ?></th>
      <th><?php                 
                echo $this->Paginator->sort('RecordFood.report_type','Report Type',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'RecordFood','action' => 'indexAjax','status'=> $status,'from'=> $from,'to'=> $to)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('RecordFood.rating','Rating',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'RecordFood','action' => 'indexAjax','status'=> $status,'from'=> $from,'to'=> $to)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('RecordFood.meal_type','Meal Type',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'RecordFood','action' => 'indexAjax','status'=> $status,'from'=> $from,'to'=> $to)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('RecordFood.status','Status',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'RecordFood','action' => 'indexAjax','status'=> $status,'from'=> $from,'to'=> $to)));
            ?></th>
     <?php
      if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
            { 
     ?>
          <th width="13%">Action</th>
      <?php
      }
      ?>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = Configure::read($data['RecordFood']['status']);
?>
    <tr>
    <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE') && ($data['RecordFood']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['RecordFood']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['RecordFood']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['RecordFood']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE') && ($data['RecordFood']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['RecordFood']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else{
                echo "N/A";
              }
            ?>

          </td>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo date('d-m-Y',strtotime($data['RecordFood']['date'])); ?>&nbsp;</td> 
     
        <td><?php if($data['RecordFood']['prison_code']!='')echo ucwords(h($data['RecordFood']['prison_code']));else echo Configure::read('NA'); ?>&nbsp;</td>  

        <td><?php if($data['Prison']['name']!='')echo ucwords(h($data['Prison']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>  

        <td><?php if($data['RecordFood']['comment_on_food']!='')echo ucwords(h($data['RecordFood']['comment_on_food']));else echo Configure::read('NA'); ?>&nbsp;</td>  
        <td><?php if($data['RecordFood']['report_type']!='')echo ucwords(h($data['RecordFood']['report_type']));else echo Configure::read('NA'); ?>&nbsp;</td>  
        <td><?php if($data['RecordFood']['rating']!='')echo ucwords(h($data['RecordFood']['rating']));else echo Configure::read('NA'); ?>&nbsp;</td>
        <td><?php if($data['RecordFood']['meal_type']!='')echo ucwords(h($data['RecordFood']['meal_type']));else echo Configure::read('NA'); ?>&nbsp;</td>  
      <td>
            <?php if($data["RecordFood"]['status'] == 'Draft')
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
              <a href="javaScript:void(0);" class="pop btn-mini btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $display_status;?></a>
              <?php 
            }?>
          </td>
       <?php  				
      if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
                          ?>
        <td class="actions">
            <!-- <?php //echo $this->Form->create('RecordFoodEdit',array('url'=>'/recordFood/add','admin'=>false));?> 
            <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['RecordFood']['id'])); ?>
            <?php //echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        
            <?php //echo $this->Form->create('RecordFoodDelete',array('url'=>'/recordFood/index','admin'=>false));?> 
            <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['RecordFood']['id'])); ?>
            <?php //echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?> -->
            <?php echo $this->Form->create('RecordFoodEdit',array('url'=>'/recordFood/add','admin'=>false,'id'=>'RecordFoodEdit'));?> 
              <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['RecordFood']['id'])); ?>
              <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('type'=>'button','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return editForm();")); ?> 
              <?php echo $this->Form->end();?>

              <?php echo $this->Form->create('RecordFoodDelete',array('url'=>'/recordFood/index','admin'=>false, 'id'=>'RecordFoodDelete'));?> 
              <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['RecordFood']['id'])); ?>
              <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('type'=>'button','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return deleteForm();")); ?>
                <?php echo $this->Form->end();?>
      </td>
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
}else{
?>
<span style="color:red;font-weight:bold;">No Record Found!!</span>
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
    $('#ApprovalProcessFormIndexAjaxForm').submit();
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
                    $('#RecordFoodEdit').submit();
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
                    $('#RecordFoodDelete').submit();
                },
                function(){
                    
                }
            );
  }
</script>   