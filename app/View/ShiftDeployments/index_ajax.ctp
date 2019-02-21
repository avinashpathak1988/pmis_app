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
        'controller'                => 'ShiftDeployments',
        'action'                    => 'indexAjax',
        'shift_id'                  => $shift_id,
        'force_id'                  => $force_id,
        'deploy_area'               => $deploy_area

                         
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
    <?php
    
      $exUrl = "indexAjax/shift_id:$shift_id/force_id:$force_id/deploy_area:$deploy_area";
      $urlExcel = $exUrl.'/reqType:XLS';
      $urlDoc = $exUrl.'/reqType:DOC';
      $urlPDF = $exUrl.'/reqType:PDF';
   $urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
  echo '&nbsp;&nbsp;';
  echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
    ?>
    </div>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>   
      <th><?php echo $this->Paginator->sort('Shift Date'); ?></th>
      <th><?php echo $this->Paginator->sort('Shift'); ?></th>
      <th><?php echo $this->Paginator->sort('Area Of Deployment'); ?></th>
      <th><?php echo $this->Paginator->sort('Force Number'); ?></th>
      
      <?php  if ($this->Session->read('Auth.User.usertype_id')!=2) {
       
       ?>
      <th><?php echo __('Action'); ?></th>
      <?php } ?>

      
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){//debug($data);
?>
    <tr>
      
      <td><?php echo $rowCnt; ?>&nbsp;</td>     
       <td><?php echo (isset($data['ShiftDeployment']['shift_date']) && $data['ShiftDeployment']['shift_date'] != '0000-00-00') ? ucwords(h(date("d-m-Y", strtotime($data['ShiftDeployment']['shift_date'])))) :  Configure::read('NA'); ?>&nbsp;</td> 
      
      <td><?php if($data['Shift']['name']!='')echo ucwords(h($data['Shift']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
      
       <td><?php if($data['AreaOfDeployment']['name']!='')echo ucwords(h($data['AreaOfDeployment']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>

      <td><?php $forceIds = explode(',', $data['ShiftDeployment']['user_id']);
      //debug($forceIds);
      $forceno='';
                for ($i=0; $i < count($forceIds); $i++) { 
                  //echo $forceIds[$i];
                  $forceno .= $funcall->getName($forceIds[$i],'User','force_number').',';//$data['User']['force_number']
                }
                echo rtrim($forceno,',');
       ?>&nbsp;</td>   
      

         <?php  if ($this->Session->read('Auth.User.usertype_id')!=2) {
       
         ?>
     
         				
      
        <td class="actions">
          <?php echo $this->Form->create('ShiftDeploymentEdit',array('url'=>'/ShiftDeployments/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['ShiftDeployment']['id'])); ?>
          
          <?php echo $this->Form->end();?>
        
          <?php echo $this->Form->create('ShiftDeploymentDelete',array('url'=>'/ShiftDeployments/index','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['ShiftDeployment']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
          <?php echo $this->Form->end();?>
      </td>
      <?php } ?>
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
...
<?php    
}
?>    