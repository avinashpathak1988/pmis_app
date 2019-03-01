<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#DischargeOnEscapeListingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
            'url'                   => array(
            'controller'            => 'DischargesController',
            'action'                => 'DischargeEscapeAjax',
            'date_of_escape'     => $date_of_escape,
            
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
            <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
            <th><?php echo $this->Paginator->sort('Date of Escape'); ?></th>
            <th><?php echo $this->Paginator->sort('Time of Escape'); ?></th>
            <th><?php echo $this->Paginator->sort('Place'); ?></th>
            <th><?php echo $this->Paginator->sort('Person from whose custody escaped'); ?></th>
            <th><?php echo $this->Paginator->sort('Escaped from inside or outside'); ?></th>
            <th><?php echo $this->Paginator->sort('Date of Recapture'); ?></th>
            <th><?php echo $this->Paginator->sort('Sentence'); ?></th>
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
?>
        <tr>
            <td><?php echo $rowCnt; ?>&nbsp;</td>
            <td><?php echo ucwords(h(date('d-m-Y', strtotime($data['DischargeEscape']['date_of_escape'])))); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['DischargeEscape']['time_of_escape'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['DischargeEscape']['place'])); ?>&nbsp;</td>
            <td>
                <?php echo ucwords(h($data['DischargeEscape']['person_whom_custody_escaped'])); ?>&nbsp;
            </td> 
            <td>
                <?php echo ucwords(h($data['DischargeEscape']['escaped_inside_outside'])); ?>
            </td>
            <td><?php echo ucwords(h(date('d-m-Y', strtotime($data['DischargeEscape']['date_of_recapture'])))); ?>&nbsp;</td> 
            <td>
               <?php echo ucwords(h($data['PrisonerSentenceDetail']['sentence'])); ?>
            </td>
            <?php if($isAccess == 1){?>
                <td class="actions">
                    <?php echo $this->Form->create('DischargeEscapeEdit',array('url'=>'/discharges/index/'.$uuid.'#discharge_on_escape','admin'=>false));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['DischargeEscape']['id'])); ?>
                    <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                </td>
                <td>
                    <?php echo $this->Form->create('DischargeEscapeDelete',array('url'=>'/discharges/index/'.$uuid.'#discharge_on_escape','admin'=>false));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['DischargeEscape']['id'])); ?>
                    <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
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