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
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
<?php
if(isset($datas) && is_array($datas) && count($datas)>0){
?>
<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>PPCash List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add  PPCash'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'url'                       => array(
            'controller'            => 'PPcashesController',
            'action'                => 'index',
      
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
    </div>
</div>

<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th>Sl no</th>                
      <th>Name</th>
      <th>Action</th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt =1;
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
?>
    <tr>

      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo h($data['PPCash']['name']); ?>&nbsp;</td>
        <td class="actions">
          <?php echo $this->Form->create('PPCashEdit',array('url'=>'/PPCash/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['PPCash']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
          <?php echo $this->Form->end();?>

          <?php echo $this->Form->create('PPCashDelete',array('url'=>'/PPCash/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['PPCash']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
          <?php echo $this->Form->end();?>
      </td>
      
				

      <?php
      $rowCnt++;

    }
      ?>
    </tr>
<?php
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
</div>
</div>
</div>
</div>
