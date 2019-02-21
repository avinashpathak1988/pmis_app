<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Users',
            'action'                => 'backupDatabaseAjax',
            'from_date'             => $from_date,
            'to_date'               => $to_date,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "backupDatabaseAjax/from_date:$from_date/to_date:$to_date";
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
<table id="example2" class="table table-bordered table-hover table-responsive">
    <thead>
        <tr>
            <th width="5%" class="text-center">SL#</th>
            <th>Download</th>
            <th>created</th>
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
?>
        <tr>
            <td class="text-center"><?php echo $rowCnt; ?></td>
            <td>
                <?php echo $this->Html->link('Download','/files/Backup/'.$data['DatabaseBackup']['name'],array('class'=>'btn btn-primary btn-mini','target'=>'_blank'));?>
            </td>
            <td><?php echo h(date('d-m-Y H:i:s a', strtotime($data['DatabaseBackup']['created']))); ?></td>
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
    <span style="color:red;font-weight:bold;">No Record Found!!</span>
<?php    
}
?>