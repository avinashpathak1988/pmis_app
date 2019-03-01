<?php
$url = '/LodgerStations';
if($lodger_type=='out')
{
    $url = '/LodgerStations/index/out';
}
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
            'url'                   => array(
            'controller'            => 'LodgerStations',
            'action'                => 'indexAjax',
            'prisoner_id'          => $prisoner_id,
            'date_of_lodging'      => $date_of_lodging,
            'original_prison'      => $original_prison,
            'destination_prison'   => $destination_prison,
            'from_date'   => $from_date,
            'to_date'   => $to_date,
            // 'lodger_type'          => $lodger_type,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
    </div>
</div>
<?php 
$btnName = 'Save';
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName = 'Save';
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName = 'Review';
  $isModal = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = 'Approve';
  $isModal = 1;
}
$modelName  = 'LodgerStation';
$methodName = 'index';
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/LodgerStations/'.$methodName));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="submit" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" <?php if($isModal == 1){?> data-toggle="modal" data-target="#verify"<?php }?>><?php echo $btnName;?></button>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
            <th><?php echo 'Sl no'; ?></th>                
            <th><?php echo 'Origin Station'; ?></th>
            <th><?php echo 'Prisoner Number'; ?></th>
            <th><?php echo 'Destination Station'; ?></th>
            <th><?php echo 'Date & Time of Arrival'; ?></th>
            <?php
            if(isset($lodger_type) && $lodger_type=='out'){
            ?>
            <th><?php echo 'Date & Time of Departure'; ?></th>
            <th><?php echo 'Duration of Stay'; ?></th>     
            <?php
            }
            ?>       
            <th><?php echo 'Reason'; ?></th>        
            <?php
            if(isset($lodger_type) && $lodger_type=='out'){
            ?>    
            <th><?php echo __('Status'); ?></th>
            <?php
            }
            ?> 
            <th><?php echo __('Approval Status'); ?></th>
            <!-- <th><?php //echo __('Edit'); ?></th>
            <th><?php //echo __('Delete'); ?></th> -->
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
    if($data['LodgerStation']['parent_id']!='' && isset($lodger_type) && $lodger_type=='out'){
        $lodgerData = $funcall->getLodgerData($data['LodgerStation']['parent_id']);
    }
    $credit_id = $data[$modelName]['id'];
    $display_status = Configure::read($data[$modelName]['status']);
?>
        <tr>
            <td>
              <?php 
              if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data[$modelName]['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data[$modelName]['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              ?>
            </td>
            <td><?php echo $rowCnt; ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['OriginStation']['name'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Prisoner']['prisoner_no'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['DestinationStation']['name'])); ?>&nbsp;</td>
            <td>
            <?php 
            if($data['LodgerStation']['date_of_lodging'] != '0000-00-00')
                echo ucwords(h(date('d-m-Y h:i A', strtotime($data['LodgerStation']['date_of_lodging']))));
            else
                echo 'N/A';
            ?>
            &nbsp;
            </td> 
            <?php
            if(isset($lodger_type) && $lodger_type=='out'){
            ?>
            <td>
            <?php 
            ?>
            </td> 
            <td>
            <?php 
            
            ?>
            </td> 
            <?php
            }
            ?>
            <td><?php echo ucwords(h($data['LodgerStation']['reason'])); ?>&nbsp;</td>     
            <?php
            if(isset($lodger_type) && $lodger_type=='out'){
            ?>       
            <td><?php echo ucwords(h($data['LodgerStation']['status'])); ?>&nbsp;</td>  
            <?php
            }
            ?>          
            <td>
            <?php if($data[$modelName]['status'] == 'Draft')
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
          <!-- <td class="actions"><?php /* ?>
                <?php echo $this->Form->create('LodgerStationEdit',array('url'=>$url,'admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['LodgerStation']['id'])); ?>
                <?php echo $this->Form->button('Edit',array('label'=>false,'class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                <?php echo $this->Form->end();?><?php */ ?>
            </td>
            <td>
              <?php /* ?>
              <?php
              if($data[$modelName]['status'] == 'Draft'){
              ?>
                <?php echo $this->Form->create('LodgerStationDelete',array('url'=>$url,'admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['LodgerStation']['id'])); ?>
                <?php echo $this->Form->button('Delete',array('label'=>false,'class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
                <?php echo $this->Form->end();?>
              <?php
              }
              ?>
              <?php */ ?>
            </td> -->
        </tr>
<?php
        $rowCnt++;
    }
?>
    </tbody>
</table>
<?php
echo $this->Form->end();
}else{
?>
    ...
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
</script>   