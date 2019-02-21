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
            'controller'            => 'PropertyController',
            'action'                => 'indexAjax',
            'propertyitem_id'       => $propertyitem_id,
            'property_description'  => $property_description,
            'property_date'         => $property_date,
            'source'                => $source,
            'uuid'                  =>$uuid,
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
            <?php if($param=="Incoming" && ($isAccess == 1)){?>
            <th>Check</th>
            <?php } ?>
            <th><?php echo $this->Paginator->sort('Sl no'); ?></th>  
            <th><?php echo $this->Paginator->sort('Property Name'); ?></th>
            <th><?php echo $this->Paginator->sort('Property Description'); ?></th>
            <th><?php echo $this->Paginator->sort('Date'); ?></th>
            <th><?php echo $this->Paginator->sort('Source'); ?></th>
            <?php if($param=="Incoming" && ($isAccess == 1)){?>
            <th><?php echo __('Edit'); ?></th>
            <th><?php echo __('Delete'); ?></th>
             <?php } ?>
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));

    foreach($datas as $data){
?>
        <tr>
            <?php if($param=="Incoming" && ($isAccess == 1)){?>
            <td><input type="checkbox" class="propertycheckclass" name="chk[]" value="<?php echo $data['Property']['id'] ?>"> </td>
            <?php } ?>
            <td><?php echo $rowCnt; ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Propertyitem']['name'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Property']['property_description'])); ?>&nbsp;</td> 
            <td><?php echo h(date('d-m-Y', strtotime($data['Property']['property_date']))); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Property']['source'])); ?>&nbsp;</td>
            <?php if($param=="Incoming" && ($isAccess == 1)){?>
            <td class="actions">
                <?php echo $this->Form->create('PropertyEdit',array('url'=>'/properties/index/'.$uuid,'admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Property']['id'])); ?>
                <?php echo $this->Form->input('uuid',array('type'=>'hidden','value'=> $data['Property']['uuid'])); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
            </td>
            
            <td>
                <?php echo $this->Form->create('PropertyDelete',array('url'=>'/properties/index/'.$uuid,'admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Property']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
            </td>
            <?php }  ?>
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