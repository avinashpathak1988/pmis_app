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
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'PrisonsController',
            'action'                => 'indexAjax',
            'prison_name'             => $prison_name,

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
    $exUrl = "indexAjax/prison_name:$prison_name";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    </div>
</div>
<?php
    }
?>                    
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>dcfffSL#</th>
            <th>Region Area</th>
            <th>Name</th>
            <th>Code</th>
            <th>Capacity</th>
            <th>Date Of Opening</th>
            
<?php
if(!isset($is_excel)){
?>          <th>Is Enabled ?</th>
            <th>Actions</th>
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
            <td><?php echo $rowCnt; ?></td>
           
            <td><?php if($data['Prison']['state_id']!='')echo ucwords(h($data['Prison']['state_id']));else echo Configure::read('NA'); ?>&nbsp;</td>
            
            <td><?php if($data['Prison']['name']!='')echo ucwords(h($data['Prison']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
           
            <td><?php if($data['Prison']['code']!='')echo ucwords(h($data['Prison']['code']));else echo Configure::read('NA'); ?>&nbsp;</td>
            
            <td><?php if($data['Prison']['capacity']!='')echo ucwords(h($data['Prison']['capacity']));else echo Configure::read('NA'); ?>&nbsp;</td>
           
            <td><?php echo (isset($data['Prison']['date_of_opening']) && $data['Prison']['date_of_opening'] != '0000-00-00') ? ucwords(h(date("d-m-Y", strtotime($data['Prison']['date_of_opening'])))) :  Configure::read('NA'); ?>&nbsp;</td>  
<?php
        if(!isset($is_excel)){
?>              
            <td>
                                <?php
if($data['Prison']['is_enable'] == 1){
  echo $this->Html->link("Click To Disable",array(
    'controller'=>'prisons',
    'action'=>'disable',
    $data['Prison']['id']
  ),array(
    'escape'=>false,
    'class'=>'btn btn-primary btn-mini',
    'onclick'=>"return confirm('Are you sure you want to disable?');"
  ));
}else{
  echo $this->Html->link("Click To Enable",array(
    'controller'=>'prisons',
    'action'=>'enable',
    $data['Prison']['id']
  ),array(
    'escape'=>false,
    'class'=>'btn btn-danger btn-mini',
    'onclick'=>"return confirm('Are you sure you want to enable?');"
  ));
}
                                 ?>
                            </td>
           <td>
<?php
echo $this->Html->link('<i class="icon icon-edit"></i>',array(
  'action'=>'edit',
  $data['Prison']['id']
),array(
    'escape'=>false,
    'class'=>'btn btn-success btn-mini'
  ));
 ?>

 <?php

 // echo $this->Html->link('<i class="icon icon-trash"></i>',array(
 //     'action'=>'trash',
 //     $data['Prison']['id']
 //   ),array(
 //      'escape'=>false,
 //      'class'=>'btn btn-danger btn-mini',
 //      'onclick'=>"return confirm('Are you sure you want to delete?');" 
 //    ));

echo '&nbsp;'.$this->Html->link('<i class="icon icon-eye-open"></i>',array(
     'action'=>'detail',
     $data['Prison']['id']
   ),array(
      'escape'=>false,
      'class'=>'btn btn-success btn-mini'
    ));
 
  ?>
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
}else{
?>
    <span style="color:red;">No records found!</span>
<?php    
}
?>                    