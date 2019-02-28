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
            'controller'            => 'Earnings',
            'action'                => 'assignSkillAjax',
           
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
    </div>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th><?php echo $this->Paginator->sort('Prisoner Number'); ?></th>
      
      <th><?php echo $this->Paginator->sort('Skill'); ?></th>
       <th><?php echo $this->Paginator->sort('Assignment Date'); ?></th>
       <th><?php echo $this->Paginator->sort('good conduct'); ?></th>
       <th><?php echo __('Edit'); ?></th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo $funcall->getName($data['AssignSkill']['prisoner_id'],"Prisoner","prisoner_no");?>&nbsp;</td> 
      
      <td><?php echo $funcall->getName($data['AssignSkill']['assign_skill_id'],"SkillSet","name");?>&nbsp;</td>
      
       <td><?php echo (isset($data['AssignSkill']['assignment_date']) && $data['AssignSkill']['assignment_date'] != '0000-00-00') ? ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['AssignSkill']['assignment_date'])))) :  Configure::read('NA'); ?>&nbsp;</td>
       <td>
         <?php if ($data['AssignSkill']['is_conduct']==1) {
          echo "Yes";

          
         }else{echo "No";} ?>
       </td>

      <td class="actions">
          <?php echo $this->Form->create('AssignSkillEdit',array('url'=>'/Earnings/assignSkill','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['AssignSkill']['id'])); ?>
          <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        </td>
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