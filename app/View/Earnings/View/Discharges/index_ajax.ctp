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
            'url'                   => array(
                'controller'            => 'Discharges',
                'action'                => 'indexAjax',
                'discharge_date'     => $discharge_date,
                'discharge_type_id'     => $discharge_type_id,
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
            <th><?php echo $this->Paginator->sort('Date of Discharge'); ?></th>
            <th><?php echo $this->Paginator->sort('Discharge Type'); ?></th>
            <th><?php echo $this->Paginator->sort('Supported Docs'); ?></th>
            <th>View Details</th>
            <?php if($isAccess == 1){?>
                <!-- <th><?php //echo __('Edit'); ?></th> -->
                <th><?php echo __('Delete'); ?></th>
            <?php }?>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
    // debug($data);
?>
        <tr>
            <td><?php echo $rowCnt; ?>&nbsp;</td>
            <td><?php echo ucwords(h(date('d-m-Y', strtotime($data['Discharge']['discharge_date'])))); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['DischargeType']['name'])); ?>&nbsp;</td>            
            <td>
                <?php echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary'))?>
            </td>
            <td>
            <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal<?php echo $data['Discharge']['id']; ?>" onclick="showDetails(<?php echo $data['Discharge']['discharge_type_id']; ?>,<?php echo $data['Discharge']['id']; ?>,<?php echo $data['Discharge']['prisoner_id']; ?>)">View Details</button>

            <!-- Modal -->
            <div id="myModal<?php echo $data['Discharge']['id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Details</h4>
                  </div>
                  <div class="modal-body d-modal" id="show_details">
                    <p><?php //echo $data['Discharge']['id']; ?></p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
                
            </td>
            <?php if($isAccess == 1){?>
                <?php /* ?><td class="actions">
                <?php
                if($data['Discharge']['status']=='Draft'){
                    ?>
                    <?php echo $this->Form->create('DischargeEdit',array('url'=>'/discharges/index/'.$uuid,'admin'=>false));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Discharge']['id'])); ?>
                    <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                <?php 
                }
                ?>
                </td>
                <?php */ ?>
                <td>
                    <?php
                    if($data['Discharge']['status']=='Draft'){
                    ?>
                    <?php echo $this->Form->create('DischargeDelete',array('url'=>'/discharges/index/'.$uuid,'admin'=>false));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Discharge']['id'])); ?>
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