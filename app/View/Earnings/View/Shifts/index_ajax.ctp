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
            'controller'            => 'ShiftsController',
            'action'                => 'indexAjax',
            'name'      => $name,
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
    </div>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo __('SL#'); ?></th>   
      <th>
        <?php                 
          echo $this->Paginator->sort('Shift.name','Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Shifts','action' => 'indexAjax')));
          ?>
      </th>
      <th>
        <?php                 
          echo $this->Paginator->sort('Shift.start_time','Start Time',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Shifts','action' => 'indexAjax')));
          ?>
      </th>
      <th>
        <?php                 
          echo $this->Paginator->sort('Shift.end_time','End Time',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Shifts','action' => 'indexAjax')));
          ?>
      </th>
      <th><?php echo __('Enable'); ?></th>    
      <th><?php echo __('Action'); ?></th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>     
       
      <td><?php if($data['Shift']['name']!='')echo ucwords(h($data['Shift']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
      <td><?php echo ($data['Shift']['start_time']!='') ? date('h:i A', strtotime($data['Shift']['start_time'])) : ''; ?>&nbsp;</td> 
      <td><?php echo ($data['Shift']['end_time']!='') ? date('h:i A', strtotime($data['Shift']['end_time'])) : ''; ?>&nbsp;</td> 
      <td>
      <?php 
      if($data['Shift']['is_enable'] == 1)
      {
        echo 'Yes';
      }
      else
      {
        echo 'No';
      }
      ?>
      </td>   				
      
      <td class="actions">
        <?php echo $this->Form->create('ShiftEdit',array('url'=>'/Shifts/add','admin'=>false));?> 
        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Shift']['id'])); ?>
        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        <?php echo $this->Form->end();?>
        
        <?php echo $this->Form->create('ShiftDelete',array('url'=>'/Shifts/index','admin'=>false));?> 
        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Shift']['id'])); ?>
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