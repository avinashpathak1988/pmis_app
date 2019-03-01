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
            'controller'            => 'Privileges',
            'action'                => 'indexAjax',
            'prison_id'              => $prison_id,
            'stage_id'              => $stage_id, 
            'privilege_right_id'     => $privilege_right_id,
            'interval_week'          => $interval_week,
            'duration_min'           => $duration_min,  
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} ')
));
?>
    </div>
</div>
<table id="EscortTeamTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th>Prison</th>
      <th>Stage Name</th>
      <th>Privilege Right</th>
      <th>Durations</th>
      <th>Interval Week</th>
      <th><?php echo __('Action'); ?></th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $EscortTeam){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo $funcall->getName($EscortTeam['Privilege']['prison_id'],"Prison","name");?>&nbsp;</td> 
      <td><?php echo $funcall->getName($EscortTeam['Privilege']['stage_id'],"Stage","name");?>&nbsp;</td>	
      <td><?php echo $funcall->getName($EscortTeam['Privilege']['privilege_right_id'],"PrivilegeRight","name");?>&nbsp;</td> 
      <td><?php echo ucwords(h($EscortTeam['Privilege']['duration_min'])); ?>&nbsp;</td> 
      <td><?php echo ucwords(h($EscortTeam['Privilege']['interval_week'])); ?>&nbsp;</td> 
      <td>
          <?php echo $this->Form->create('PrivilegeEdit',array('url'=>'/Privileges/add','admin'=>false));?> 
        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $EscortTeam['Privilege']['id'])); ?>
        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        <?php echo $this->Form->end();?> 

            <?php echo $this->Form->create('PrivilegeDelete',array('url'=>'/Privileges/index','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $EscortTeam['Privilege']['id'])); ?>
            <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
            <?php echo $this->Form->end();?>


      </td>
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>
<?php
echo $this->Js->writeBuffer();
}else{
?>
...
<?php    
}
?>    