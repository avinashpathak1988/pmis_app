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
            'controller'            => 'Compounds',
            'action'                => 'indexAjax',
            'prison_id'              => $prison_id,
            'name'              => $name,      
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
<table id="CompoundTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th>
        
        <?php                 
          echo $this->Paginator->sort('Compound.name','Compound',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Compounds','action' => 'indexAjax')));
          ?>
      </th>
      
     
      <th>Prisons</th>
      <th><?php echo $this->Paginator->sort('is_enable'); ?></th>
      <th><?php echo __('Action'); ?></th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $Compound){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>    
      <td><?php if($Compound['Compound']['name']!='')echo ucwords(h($Compound['Compound']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
      <td><?php 
      $team = array();
      if(isset($Compound['Compound']['prison_id']) && $Compound['Compound']['prison_id']!=''){
        foreach (explode(",", $Compound['Compound']['prison_id']) as $key => $value) {
          $team[] = $funcall->getName($value,"Prison","name");
        }
        echo implode(", ", $team);
       
      } 
      ?>&nbsp;</td>   				
      <td><?php if($Compound['Compound']['is_enable'] == '1'){
      echo "<font color=green>Yes</font>"; 
      }else{
      echo "<font color=red>No</font>"; 
      }?>&nbsp;
      </td>
      <td class="actions">
        
        <?php echo $this->Form->create('CompoundEdit',array('url'=>'/Compounds/add','admin'=>false));?> 
        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $Compound['Compound']['id'])); ?>
        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini onlyIcon','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        <?php echo $this->Form->end();?> 

            <?php echo $this->Form->create('CompoundDelete',array('url'=>'/Compounds/index','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $Compound['Compound']['id'])); ?>
            <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini onlyIcon','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
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