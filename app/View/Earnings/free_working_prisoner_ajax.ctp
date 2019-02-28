<?php 
if(is_array($datas) && count($datas)>0){
    
?>                    
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Prisoner Number</th>
            <th>Working Days</th>
            <?php
            if(!isset($is_excel)){
            ?>       
                <!-- <th>Action</th> -->
            <?php }?>             
        </tr>
    </thead>
    <tbody>
        <?php 
        $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
        $i = 0;
        $prisonerIDArray = '';
        $rowData = '';
        $prisonerData = '';
        foreach($datas as $data){
            $prisoner_id = $data['PrisonerAttendance']['prisoner_id'];
            $prisonerData = $funcall->getPrisonerEarningDetails($prisoner_id);
            $prisoner_uuid = $data['Prisoner']['uuid'];
            $prisoner_no = $data['Prisoner']['prisoner_no'];
            //echo '<pre>';print_r($prisonerData); exit;
            ?>
            <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
                <td>
                    <?php echo $rowCnt; ?>
                </td>
                <td>
                    <?php echo $this->Html->link($prisoner_no,array('controller'=>'earnings','action'=>'freeWorking/'.$prisoner_uuid),array('escape'=>false,'class'=>'btn btn-success',)); ?>
                </td>
                <td><?php if(isset($prisonerData['total_working_days']))echo $prisonerData['total_working_days'];?></td>
                
                <?php
                if(!isset($is_excel)){
                ?>       
                    <!-- <td> -->
                        <?php //echo $this->Html->link('View',array('action'=>'prisonerEarningDetails/31'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                    <!-- </td> -->
                <?php }?>             
            </tr>
        <?php 
            $rowCnt++;
            $i++;
        }?>
    </tbody>
</table>
<?php
//pagination start 
if(!isset($is_excel)){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#item_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Earnings',
            'action'                => 'freeWorkingPrisonerAjax'

        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:20px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "freeWorkingPrisonerAjax";
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
//pagination start 
}else{
?>
    ...
<?php    
}
?>                    