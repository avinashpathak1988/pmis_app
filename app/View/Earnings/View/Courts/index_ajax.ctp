<?php
    if(isset($is_excel)){
      ?>
      <style type="text/css">
          th, td{border: 1px solid black;}
       </style>
      <?php
    }
      ?> 
<?php
if(is_array($datas) && count($datas)>0){
  if(!isset($is_excel)){
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
            'controller'            => 'Courts',
            'action'                => 'indexAjax',
            'court_name'             => $court_name,
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
<?php
    $exUrl = "indexAjax/court_name:$court_name";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPrint = $exUrl.'/reqType:PRINT';
    $urlPdf = $exUrl.'/reqType:PDF';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"print")),$urlPdf, array("escape" => false,'target'=>"_blank")));
    echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"print")),$urlPrint, array("escape" => false,'target'=>"_blank")));
    echo '&nbsp;&nbsp;';

     
?>
    </div>
</div>
<?php
}
?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th>
        <?php                 
          echo $this->Paginator->sort('Court.name','Court Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Courts','action' => 'indexAjax')));
          ?>
      </th>
      
      <th>
         <?php                 
          echo $this->Paginator->sort('Court.court_code','Court Code',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Courts','action' => 'indexAjax')));
          ?>
        </th>
       <th><?php                 
          echo $this->Paginator->sort('Court.date_of_opening','Date Of Opening',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Courts','action' => 'indexAjax')));
          ?>
        </th>
      <th>
        <?php                 
          echo $this->Paginator->sort('Magisterial.name','Jurisdiction Area Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Courts','action' => 'indexAjax')));
          ?>
      </th>
      
      <th>
        <?php                 
          echo $this->Paginator->sort('Courtlevel.name','Court Level',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Courts','action' => 'indexAjax')));
          ?>
      </th>
      <th>
        <?php                 
          echo $this->Paginator->sort('Court.phone_no','Phone No',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Courts','action' => 'indexAjax')));
          ?>
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('Court.physical_address','Physical Address',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Courts','action' => 'indexAjax')));
          ?>
        
      </th>
      <?php
      if(!isset($is_excel)){
      ?>
      <th><?php echo __('Action'); ?></th>
      <?php
      }
      ?>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      
      <td><?php if($data['Court']['name']!='')echo ucwords(h($data['Court']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>    
      <td><?php if($data['Court']['court_code']!='')echo ucwords(h($data['Court']['court_code']));else echo Configure::read('NA'); ?>&nbsp;</td>

      <td><?php echo (isset($data['Court']['date_of_opening']) && $data['Court']['date_of_opening'] != '0000-00-00') ? ucwords(h(date("d-m-Y", strtotime($data['Court']['date_of_opening'])))) :  Configure::read('NA'); ?>&nbsp;</td>    
         
      <td><?php if($data['Magisterial']['name']!='')echo ucwords(h($data['Magisterial']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>  
      
      <td><?php if($data['Courtlevel']['name']!='')echo ucwords(h($data['Courtlevel']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>      
      <td><?php if($data['Court']['phone_no']!='')echo ucwords(h($data['Court']['phone_no']));else echo Configure::read('NA'); ?>&nbsp;</td>    
      <td><?php if($data['Court']['physical_address']!='')echo ucwords(h($data['Court']['physical_address']));else echo Configure::read('NA'); ?>&nbsp;</td>
        <?php
      if(!isset($is_excel)){
      ?> 				
      
        <td class="actions">
          <?php echo $this->Form->create('CourtEdit',array('url'=>'/courts/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Court']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
          <?php echo $this->Form->end();?> 
        
          <?php echo $this->Form->create('CourtDelete',array('url'=>'/courts/index','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Court']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
          <?php echo $this->Form->end();?>
      </td>
      <?php
      }
      ?>
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