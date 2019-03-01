<?php
if(is_array($datas) && count($datas)>0){
?>
<?php if(@$file_type == '') { ?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        'before'                      => '$("#lodding_image").show();',
        'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'reportreviews',
            'action'                    => 'stagePromotionAjax',
          
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
   //$exUrl = "indexAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
   $exUrl = "prisonerStageAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlpdf = $exUrl.'/reqType:PDF';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlpdf, array("escape" => false)));
?>
    </div>
</div>
<?php } ?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Sl No#</th>
            <th>Prisoner Number</th>
            <th>Prisoner Name</th>
            <th>Due for Promotion from  Stage</th>
            <!-- <th>Length of imprisonment remaining</th> -->
            <th>To Stage</th>
            <th>Due Restoration of From Stage</th>
            <th>To Stage</th>
        </tr>
    </thead>
    <tbody>
<?php
//debug($datas);
    $rowcnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
        $stageCondi = $this->requestAction('/Stages/checkStagePromotion/'.$data['Prisoner']['id'].'/'.Configure::read('STAGE-IV'));
        // $stageCondi['button']
        if($stageCondi['button']){
    ?>
  <tr>
    <td><?php echo $rowcnt; ?></td>
    <td><?php echo $data['Prisoner']['prisoner_no'];?></td>
    <td><?php echo $data['Prisoner']['first_name'].' '. $data['Prisoner']['middle_name']; ?></td>
    <?php
    if($funcall->getName($data['0']['stage_history_id'],"StageHistory","type")=='Stage Demotion'){
        ?>
        <td></td>
        <td></td>
        <td><?php  echo $funcall->getName($funcall->getStageHistory($data['0']['stage_history_id']),"Stage","name");?></td>
        <td><?php echo $funcall->getName($funcall->getStageHistory($data['0']['stage_history_id']) + 1,"Stage","name");?></td>
        <?php
    }else{
        ?>
        <td><?php  echo $funcall->getName($funcall->getStageHistory($data['0']['stage_history_id']),"Stage","name");?></td>
        <td><?php echo $funcall->getName($funcall->getStageHistory($data['0']['stage_history_id']) + 1,"Stage","name");?></td>
        <td></td>
        <td></td>
        <?php
    }
    ?>
   </tr>
<?php
        
        $rowcnt++;
        }

    }
?>
    </tbody>

</table>
<?php
}else{
    echo Configure::read('NO-RECORD'); 
}
echo $this->Js->writeBuffer();
?>
