<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#gatepassListingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
            'url'                   => array(
            'controller'            => 'discharges',
            'action'                => 'gatepassAjax'
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
    </div>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
            <th><?php echo $this->Paginator->sort('Gate Pass No.'); ?></th>                
            <th><?php echo $this->Paginator->sort('Escort'); ?></th>
            <th><?php echo $this->Paginator->sort('Date'); ?></th>
            <th><?php echo $this->Paginator->sort('Permission is granted for'); ?></th>
            <th><?php echo $this->Paginator->sort('Purpose'); ?></th>
            <?php if($isAccess == 1){?>
                <th><?php echo __('Edit'); ?></th>
                <th><?php echo __('Delete'); ?></th>
            <?php }?>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
    // debug($data['GatePass']);
?>
        <tr>
            <td><?php echo $rowCnt; ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['GatePass']['gp_no'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($funcall->getName($data['GatePass']['user_id'],"User","name"))); ?>&nbsp;</td>
            <td>
            <?php 
            if($data['GatePass']['gp_date'] != '0000-00-00')
                echo ucwords(h(date('d-m-Y', strtotime($data['GatePass']['gp_date']))));
            else
                echo 'N/A';?>
            &nbsp;
            </td> 
            <td><?php echo ucwords(h($data['GatePass']['destination'])); ?>&nbsp;</td>            
            <td><?php echo ucwords(h($data['GatePass']['purpose'])); ?>&nbsp;</td>
            <?php if($isAccess == 1){?>
                <td class="actions">
                    <?php
                    if($data['GatePass']['status']=='Draft'){
                    ?>
                    <?php echo $this->Form->create('GatePassEdit',array('url'=>'/discharges/index/'.$uuid.'#gate_pass','admin'=>false));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['GatePass']['id'])); ?>
                    <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                    <?php
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if($data['GatePass']['status']=='Draft'){
                    ?>
                    <?php echo $this->Form->create('GatePassDelete',array('url'=>'/discharges/index/'.$uuid.'#gate_pass','admin'=>false));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['GatePass']['id'])); ?>
                    <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
                    <?php
                    }
                    ?>
                </td>
            <?php }?>
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