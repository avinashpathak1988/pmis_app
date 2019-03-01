<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    // $this->Paginator->options(array(
    //     'update'                    => '#stageAssignListDiv',
    //     'evalScripts'               => true,
    //     'before'                    => '$("#lodding_image").show();',
    //     'complete'                  => '$("#lodding_image").hide();',
    //     'url'                       => array(
    //         'controller'            => 'StagesController',
    //         'action'                => 'stagesAssignAjax',   
    //     )
    // ));         
    // echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    // echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    // echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    // echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right">
<?php
// echo $this->Paginator->counter(array(
//     'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
// ));
?>
<?php
    // $exUrl = "/stages/stagesAssignAjax/prisoner_id:$prisoner_id/uuid:$uuid";
    // $urlExcel = $exUrl.'/reqType:XLS';
    // $urlDoc = $exUrl.'/reqType:DOC';
    // echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    // echo '&nbsp;&nbsp;';
    // echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
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
            <th>Date of Assign</th>
            <th> Stage </th>
            
            <th>Comment</th>
            
<?php
if(!isset($is_excel) && ($isAccess == 1)){
?> 
            <th>Edit</th>
            <th>Delete</th>
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
            <td><?php echo date('d-m-Y', strtotime($data["StageAssign"]["date_of_assign"]));?> </td>
            <td><?php echo $data["Stage"]["name"] ?></td>
         
            <td><?php echo $data["StageAssign"]["comment"] ;?></td>
           
            
           
<?php
        if(!isset($is_excel) && ($isAccess == 1)){
            $stageAssign_id   = $data['StageAssign']['id'];
            $stageAssign_uuid = $data['StageAssign']['uuid'];
?>              
            <td>
              
                <?php echo $this->Form->create('StageAssignEdit',array('url'=>'/stages/stagesAssign/'.$uuid.'#StageAssign','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $stageAssign_id)); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
            </td>
            <td>
                <?php echo $this->Form->button('Delete', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteStageAssignRecords('$stageAssign_uuid');"))?>
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