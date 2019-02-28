<?php
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
            'url'                       => array(
            'controller'            => 'EarningRates',
            'action'                => 'assignGradeAjax',
           
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
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th><?php echo $this->Paginator->sort('Priosner Number'); ?></th>
      
      <th><?php echo $this->Paginator->sort('Grade'); ?></th>
       <th><?php echo $this->Paginator->sort('Assignment Date'); ?></th>
      
      <th><?php echo $this->Paginator->sort('Remarks'); ?></th>
      
      
      
      <!-- <th><?php echo __('Edit'); ?></th> -->
      <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
      {?>
        <th><?php echo __('Delete'); ?></th>
      <?php }?>
      
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data)
{
  //$id = $data['EarningGradePrisoner']['id'];
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Prisoner']['prisoner_no'])); ?>&nbsp;</td> 
      
      <td><?php echo ucwords(h($data['EarningGrade']['name'])); ?>&nbsp;</td>
      
      <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['EarningGradePrisoner']['assignment_date'])); ?>&nbsp;</td>
      
      <td><?php echo ucwords(h($data['EarningGradePrisoner']['remarks'])); ?>&nbsp;</td>
     
         				
      
        <!-- <td class="actions">
          <?php echo $this->Form->create('EarningGradePrisonerEdit',array('url'=>'/EarningGrades/assignGrades','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['EarningGradePrisoner']['id'])); ?>
          <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        </td>-->
        <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {?>
          <td>
            <?php 
            echo $this->Form->create('EarningGradePrisonerDelete',array('url'=>'/EarningRates/assignGrades','admin'=>false));
            echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['EarningGradePrisoner']['id']));
            echo $this->Form->button('<i class="icon-trash"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"deleteAssignGrade();"));
            echo $this->Form->end();
            ?>
          </td> 
        <?php }?>
        
    </tr>
<?php
$rowCnt++;
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
function deleteAssignGrade(formId) {
  
  AsyncConfirmYesNo(
      "Are you sure want to delete?",
      'Delete',
      'Cancel',
      function(){
        $('#EarningGradePrisonerDeleteAssignGradeAjaxForm').submit();
      },
      function(){}
  );
}
</script>    