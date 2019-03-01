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
            'controller'            => 'Internalffences',
            'action'                => 'indexAjax',
            'offencename'             => $offencename,      
            'offence_type'             => $offence_type,      
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
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
      <th><?php echo $this->Paginator->sort('Sl no'); ?>
      </th>                
      <th>
        <?php                 
          echo $this->Paginator->sort('InternalOffence.name','Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'InternalOffence','action' => 'indexAjax')));
          ?>
      </th>
      <th>
        <?php                 
          echo $this->Paginator->sort('InternalOffence.offence_type','Offence Type',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'InternalOffence','action' => 'indexAjax')));
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
       <td><?php if($data['InternalOffence']['name']!='')echo ucwords(h($data['InternalOffence']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>        
     
      <td><?php if($data['InternalOffence']['offence_type']!='')echo ucwords(h($data['InternalOffence']['offence_type']));else echo Configure::read('NA'); ?>&nbsp;</td>   	 				
      <td>
<?php 
    if($data['InternalOffence']['is_enable'] == '1'){
        echo "<font color=green>Yes</font>"; 
    }else{
        echo "<font color=red>No</font>"; 
    }
?>
      </td>
      <td class="actions">
        <?php echo $this->Form->create('InternalOffenceEdit',array('url'=>'/InternalOffence/add','admin'=>false));?> 
        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['InternalOffence']['id'])); ?>
        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        <?php echo $this->Form->end();?>
        
        <?php echo $this->Form->create('InternalOffenceDelete',array('url'=>'/InternalOffence/index','admin'=>false));?> 
        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['InternalOffence']['id'])); ?>
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