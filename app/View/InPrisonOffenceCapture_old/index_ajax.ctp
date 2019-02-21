<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#sickListingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
        'controller'                => 'InPrisonOffenceCapture',
        'action'                    => 'indexAjax',   
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
    $exUrl = "/inPrisonOffenceCapture/indexAjax/prisoner_id:$prisoner_id/uuid:$uuid";
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
            <th>SL#</th>
            <th>Offence ID</th>
            <th>Offence Date</th>
            <th>Offence Type</th>
            <th>Offence Name</th>
            <th>Particulars of offence</th>
            <th>Reported by</th>
<?php
if(!isset($is_excel) && ($isAccess == 1)){
?> 
            <th colspan="2">Action</th>
<?php
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
        // debug($data);
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data["InPrisonOffenceCapture"]["offence_no"]?> </td>
            <td><?php echo date('d-m-Y', strtotime($data["InPrisonOffenceCapture"]["offence_date"]))?></td>
            <td><?php echo $data["InPrisonOffenceCapture"]["offence_type"]?> </td>
            <td><?php echo $data["InternalOffence"]["name"]?> </td>
            <td><?php echo $data["InPrisonOffenceCapture"]["offence_descr"]?></td>
            <td><?php echo $data['User']['name']; ?></td>
<?php
        if(!isset($is_excel) && ($isAccess == 1)){
            $offfence_record_id   = $data['InPrisonOffenceCapture']['id'];
            $offfence_record_uuid = $data['InPrisonOffenceCapture']['uuid'];
?>              
            <td>
            <?php
            if($data['InPrisonOffenceCapture']['status']=='Draft'){
                ?>
                <?php echo $this->Form->create('InPrisonOffenceCaptureEdit',array('url'=>'/inPrisonOffenceCapture/index/'.$uuid.'#offences','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $offfence_record_id)); ?>
                <?php echo $this->Form->button("<i class='icon-edit'></i>", array('label'=>false,'class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')",'escape'=>false)); 
                echo $this->Form->end();?> 
                <?php
            }
            ?> 
            </td>
            <td>
                <?php 
                if($data['InPrisonOffenceCapture']['status']=='Draft'){
                    echo $this->Form->button('<i class="icon-trash"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteOffenceRecords('$offfence_record_uuid');"));
                }
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
    ...
<?php    
}
?>                    