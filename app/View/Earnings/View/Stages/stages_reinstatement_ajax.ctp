<?php
if(is_array($datas) && count($datas)>0){
    // if(!isset($is_excel)){
?>
<!-- <div class="row">
    <div class="col-sm-5">
        <ul class="pagination"> -->
<?php
    // $this->Paginator->options(array(
    //     'update'                    => '#stageDemotionDiv',
    //     'evalScripts'               => true,
    //     'before'                    => '$("#lodding_image").show();',
    //     'complete'                  => '$("#lodding_image").hide();',
    //     'url'                       => array(
    //         'controller'            => 'StagesController',
    //         'action'                => 'stagesReinstatementAjax',   
    //     )
    // ));         
    // echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    // echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    // echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    // echo $this->Js->writeBuffer();
?>
        <!-- </ul>
    </div>
    <div class="col-sm-7 text-right" style="padding-top:30px;"> -->
<?php
// echo $this->Paginator->counter(array(
//     'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
// ));
?>
<?php
    // $exUrl = "/stages/stagesReinstatementAjax/prisoner_id:$prisoner_id/uuid:$uuid";
    // $urlExcel = $exUrl.'/reqType:XLS';
    // $urlDoc = $exUrl.'/reqType:DOC';
    // echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    // echo '&nbsp;&nbsp;';
    // echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    <!-- </div>
</div> -->
<?php
   // }
?>                    
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Date of Reinstatement</th>
            <th>Probationary period</th>
            <th>Stage reinstated to </th>
            <th>Reason</th>
            
<?php
if(!isset($is_excel) && ($isAccess == 1)){
?> 
            <th>Action</th>
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
            <td><?php echo date('d-m-Y', strtotime($data["StageReinstatement"]["reinstatement_date"]));?> </td>
            <td><?php echo $data["StageReinstatement"]["probationary_period"] ?></td>
            <td><?php echo $data["Stage"]["name"] ;?></td>
            
            <td><?php echo $data["StageReinstatement"]["comment"] ;?></td>
           
            
           
<?php
        if(!isset($is_excel) && ($isAccess == 1)){
            $stagesReinstatement_id   = $data['StageReinstatement']['id'];
            $stagesReinstatement_uuid = $data['StageReinstatement']['uuid'];
            $stage_reinstated_to = $data['StageReinstatement']['stage_reinstated_to'];
            $prisoner_id = $data['StageReinstatement']['prisoner_id'];
?>              

            <td>
                <?php 
                if($data['StageReinstatement']['status']!='Approved'){
                    echo $this->Form->button('Remove From Reinstatement List', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteStageReinstatementRecords('$stagesReinstatement_id','$stagesReinstatement_uuid','$stage_reinstated_to','$prisoner_id');"));
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
echo Configure::read("NO-RECORD"); 
}
?>                    