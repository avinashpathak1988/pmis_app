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
            'controller'            => 'CourtlevelsController',
            'action'                => 'indexAjax',
            'court_level_name'             => $court_level_name,
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
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      
      <th>
        <?php                 
          echo $this->Paginator->sort('Courtlevel.name','Court Level Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Courtlevels','action' => 'indexAjax')));
          ?>
        </th>
        <th>        
        <?php                 
          echo $this->Paginator->sort('Courtlevel.created','Created Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Courtlevels','action' => 'indexAjax')));
          ?>
      </th>
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
     
      <td><?php if($data['Courtlevel']['name']!='')echo ucwords(h($data['Courtlevel']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>

     <!--  <td><?php echo date(Configure::read('UGANDA-DATE-TIME-FORMAT'), strtotime($data['Courtlevel']['created']));
      ?></td>  -->
      <td><?php echo (isset($data['Courtlevel']['created']) && $data['Courtlevel']['created'] != '0000-00-00') ? date(Configure::read('UGANDA-DATE-TIME-FORMAT') strtotime($data['Courtlevel']['created'])) :  Configure::read('NA'); ?>&nbsp;</td>  
     
         				
      
        <td class="actions">
          <?php echo $this->Form->create('CourtlevelEdit',array('url'=>'/courtlevels/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Courtlevel']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
          <?php echo $this->Form->end();?> 
        
          <?php echo $this->Form->create('CourtlevelDelete',array('url'=>'/courtlevels/index','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Courtlevel']['id'])); ?>
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