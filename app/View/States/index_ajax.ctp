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
            'controller'            => 'states',
            'action'                => 'indexAjax',
            'statename'             => $statename,
            'geographical_region_id'=> $geographical_region_id,
           
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
    </div>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>  
       <th>
        <?php                 
          echo $this->Paginator->sort('GeographicalRegion.name','Geographical Region',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'GeographicalRegion','action' => 'indexAjax')));
          ?>
        </th>
        <th>
        <?php                 
          echo $this->Paginator->sort('State.name','UPS Region',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'States','action' => 'indexAjax')));
          ?>
      </th>              
     
      <th><?php echo $this->Paginator->sort('is_enable'); ?></th>
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
      <td><?php echo $funcall->getName($data['State']['geographical_region_id'],'GeographicalRegion','name'); ?>&nbsp;</td>  
      <td><?php echo ucwords(h($data['State']['name'])); ?>&nbsp;</td>	
      				
      <td>
          <?php 
              if($data['State']['is_enable'] == '1'){
                  echo "<font color=green>Yes</font>"; 
              }else{
                  echo "<font color=red>No</font>"; 
              }
          ?>
      </td>
      <td class="actions">
        <?php echo $this->Form->create('StateEdit',array('url'=>'/states/add','admin'=>false));?> 
        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['State']['id'])); ?>
        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        <?php echo $this->Form->end();?> 
        
            <?php echo $this->Form->create('StateDelete',array('url'=>'/states/index','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['State']['id'])); ?>
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
<span style="color:red;font-weight:bold;">No Record Found!!</span>
<?php    
}
?>    