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
            'controller'            => 'PhysicalLockupsController',
            'action'                => 'indexAjax',
            'status'=>$status,
            'folow_from'=>$folow_from,
            'folow_to'=>$folow_to,
            'prioner_type_d_search'=>$prioner_type_d_search,
            'lock_type_searchs'=>$lock_type_searchs
           
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
    
      $exUrl = "indexAjax/status:$status/folow_from:$folow_from/folow_to:$folow_to/prioner_type_d_search:$prioner_type_d_search/lock_type_searchs:$lock_type_searchs";
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Physicallockups/index'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button>
<table id="districtTable" class="table table-bordered table-striped table-responsive ">
  <thead>
    <tr>
      <th>
            <?php echo $this->Form->input('checkAll', array(
                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
            ));?>
          </th>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th>
        <?php                 
                echo $this->Paginator->sort('PhysicalLockup.prisoner_type_id','Prisoner Type',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Physicallockups','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'prioner_type_d_search'=> $prioner_type_d_search,'lock_type_searchs'=> $lock_type_searchs)));
            ?>
        </th>
      
      <th>
        <?php                 
                echo $this->Paginator->sort('PhysicalLockup.lockup_type_id','Lock Type',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Physicallockups','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'prioner_type_d_search'=> $prioner_type_d_search,'lock_type_searchs'=> $lock_type_searchs)));
            ?>
      </th>
       <th>
        <?php                 
                echo $this->Paginator->sort('PhysicalLockup.lock_date','Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Physicallockups','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'prioner_type_d_search'=> $prioner_type_d_search,'lock_type_searchs'=> $lock_type_searchs)));
            ?>
        </th>
      <th><?php                 
                echo $this->Paginator->sort('PhysicalLockup.no_of_female','Males',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Physicallockups','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'prioner_type_d_search'=> $prioner_type_d_search,'lock_type_searchs'=> $lock_type_searchs)));
            ?></th>
      <th><?php                 
                echo $this->Paginator->sort('PhysicalLockup.no_of_female','Females',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Physicallockups','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'prioner_type_d_search'=> $prioner_type_d_search,'lock_type_searchs'=> $lock_type_searchs)));
            ?></th>
       <th><?php                 
                echo $this->Paginator->sort('PhysicalLockup.total','Total',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Physicallockups','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'prioner_type_d_search'=> $prioner_type_d_search,'lock_type_searchs'=> $lock_type_searchs)));
            ?></th>
      <th>Remarks</th>
      
      <th><?php                 
                echo $this->Paginator->sort('PhysicalLockup.status','Status',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Physicallockups','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'prioner_type_d_search'=> $prioner_type_d_search,'lock_type_searchs'=> $lock_type_searchs)));
            ?></th>
      
      <?php
      if($this->Session->read('Auth.User.usertype_id')!=Configure::read('PRINCIPALOFFICER_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE')){
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
  $display_status = Configure::read($data['PhysicalLockup']['status']);
?>
    <tr>
      <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['PhysicalLockup']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['PhysicalLockup']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['PhysicalLockup']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['PhysicalLockup']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['PhysicalLockup']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['PhysicalLockup']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else{
                echo "N/A";
              }
            ?>
          </td>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['PrisonerType']['name'])); ?>&nbsp;</td> 
      
      <td><?php echo ucwords(h($data['LockupType']['name'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h(date('d-m-Y',strtotime($data['PhysicalLockup']['lock_date'])))); ?>&nbsp;</td> 
      <td><?php echo ucwords(h($data['PhysicalLockup']['no_of_male'])); ?>&nbsp;</td>
      
      <td><?php echo ucwords(h($data['PhysicalLockup']['no_of_female'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['PhysicalLockup']['no_of_female']+$data['PhysicalLockup']['no_of_male'])); ?>&nbsp;</td>
     
      <td><?php echo h($data['PhysicalLockup']['remarks']); ?>&nbsp;</td>
      <td>
            <?php if($data["PhysicalLockup"]['status'] == 'Draft')
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
      if($this->Session->read('Auth.User.usertype_id')!=Configure::read('PRINCIPALOFFICER_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE')){
      ?>
        <td class="actions">
          <?php if($data["PhysicalLockup"]['status'] == 'Draft')
          {?>
            <?php //echo $this->Form->create('PhysicalLockupEdit',array('url'=>'/physicallockups/index','admin'=>false));?> 
            <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['PhysicalLockup']['id'])); ?>
            <?php //echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 

             <?php

                      echo $this->Html->link('<i class="icon icon-trash"></i>',array(
                           'action'=>'delete',
                           $data['PhysicalLockup']['id']
                         ),array(
                            'escape'=>false,
                            'class'=>'btn btn-danger btn-mini',
                            'onclick'=>"return confirm('Are you sure you want to delete?');"
                          ));
                       
                        ?>
            <?php echo $this->Form->end();?>
          <?php }?>
        </td>
       <?php
    }
    else
    {
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
</script>