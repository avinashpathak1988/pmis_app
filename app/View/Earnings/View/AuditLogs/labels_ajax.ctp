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
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'AuditLogs',
            'action'                => 'labelsAjax',
            'from_date'             => $from_date,
            'to_date'               => $to_date,
            'model'                 => $model,
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
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Last Modify By</th>
            <th>Last Modify Date</th>
            <th>Model Name</th>
            <th>Column</th>
            <th>Label</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
        $label_id = $data['Label']['id'];                                                  
?>
        <tr id="trid<?php echo $label_id?>">
            <td><?php echo h($rowCnt); ?></td>
            <td><?php echo h(isset($data['User']['name'])?$data['User']['name']:'--') ?></td>
            <td><?php echo h($data['Label']['model_name']) ?></td>
            <td><?php echo date('d-m-Y H:i:s a', strtotime($data['Label']['modified'])); ?></td>
            <td><?php echo $data['Label']['column']; ?></td>
            <td>
                <?php echo $this->Form->input('label', array('type'=>'text', 'id'=>'label'.$label_id, 'class'=>'form-controll', 'label'=>false,'div'=>false, 'value'=>$data['Label']['label']))?>
            </td>
            <td>
                <?php echo $this->Html->link('Update', 'javascript:void(0);', array('escape'=>false,'class'=>'btn btn-success', 'onclick'=>"javascript:updateLabel($label_id);"))?>
            </td>
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