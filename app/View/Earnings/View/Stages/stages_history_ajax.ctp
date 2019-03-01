<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#stageHistoryListDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Stages',
            'action'                => 'stagesHistoryAjax',   
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
    $exUrl = "/stages/stagesAssignAjax/prisoner_id:$prisoner_id/uuid:$uuid";
    $urlExcel = $exUrl.'/reqType:XLS';
	$urlDoc = $exUrl.'/reqType:DOC';
    $urlPDF = $exUrl.'/reqType:PDF';
	 $urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
?>
    </div>
</div>
<?php
    }
?>                    
<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Prisoner no.</th>
            <th>Stage</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Time spent in a specific stage</th>
            <th>Stage Type </th>
            <th>Probationary period </th>
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
        $historyData = $funcall->StageHistory->field("date_of_stage",array("id > "=>$data["StageHistory"]["id"],"prisoner_id"=>$data["StageHistory"]["prisoner_id"]));

?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $funcall->getName($data["StageHistory"]["prisoner_id"],'Prisoner','prisoner_no');?></td>
            <td><?php echo $data["Stage"]["name"] ?></td>
            <td><?php echo date('d-m-Y', strtotime($data["StageHistory"]["date_of_stage"]));?> </td>
            <td><?php echo (!$historyData) ? '' : date('d-m-Y', strtotime($historyData));?> </td>
           
         
            <td><?php 
                if($historyData){
                    echo (strtotime($historyData) - strtotime($data["StageHistory"]["date_of_stage"])) / (24*60*60) ." days";
                } ;?></td>
            <td><?php echo $data["StageHistory"]["type"] ;?></td>
            <?php
                if($data["StageHistory"]["probationary_period"]!=""){
            ?>
           <td><?php echo $data["StageHistory"]["probationary_period"] ;?></td>
           <?php
                }
                else{
                    ?>
                    <td>N/A</td>
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