<?php //echo '<pre>'; print_r($datas); exit;
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
            'controller'            => 'PrisonerTransfers',
            'action'                => 'indexAjax'
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right" style="padding-top:30px;">
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
      <th></th>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th><?php echo $this->Paginator->sort('Prisoner Number'); ?></th>
      <th><?php echo $this->Paginator->sort('Prisoner Name'); ?></th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
$i = 0;
foreach($datas as $data){
    $prisoner_id = $data['Prisoner']['id'];
?>
    <tr>
      <td>
        <?php echo $this->Form->input('PrisonerTransfer.'.$i.'.prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','value'=>$prisoner_id,'required'=>false));
        //echo $this->Form->input('PrisonerAttendance.'.$i.'.prisoner_id', array(
          //    'type'=>'checkbox', 'value'=>$prisoner_id,'hiddenField' => false, 'label'=>false,
          //    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
        //));
        ?>
      </td>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
      <td><?php echo $data['Prisoner']['fullname'];?></td>
    </tr>
<?php
  $rowCnt++;
  $i++;
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