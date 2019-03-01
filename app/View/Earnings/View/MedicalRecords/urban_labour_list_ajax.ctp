<link rel="stylesheet" href="<?php echo $this->webroot?>forms/css/style.css">
<style>
#forwardBtnRecommend
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>
<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'MedicalRecords',
            'action'                => 'urbanLabourListAjax',
            // 'status'           => $status,
            'prisoner_id'         => $prisoner_id, 
            // 'uuid'         => $uuid,     
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
    $exUrl = "urbanLabourListAjax/from_date:$from_date/prisoner_id:$prisoner_id/to_date:$to_date";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
	$urlPdf = $exUrl.'/reqType:PDF';
	$urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
	echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download Pdf")),$urlPdf, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
   ?>
    </div>
</div>
<?php }?>          
<?php
  if(isset($is_excel)){
    ?>
    <style type="text/css">
        th, td{border: 1px solid black;}
     </style>
    <?php
  }
?>
<div style="overflow-x:scroll;">      
<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>SL#</th>
            <th><?php                 
                echo $this->Paginator->sort('UnfitHistory.prisoner_id','Prisoner No.',array('update'=>'#ListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecord','action' => 'urbanLabourListAjax','prisoner_id'=> $prisoner_id)));
            ?></th>
            <th>Prisoner Name<?php                 
                //echo $this->Paginator->sort('RestrictionHistory.prisoner_id','Prisoner Name',array('update'=>'#ListingDiv','evalScripts' => true,'url'=>array('controller' => 'report','action' => 'getRestrictedPrisonerListAjax','prisoner_id'=> $prisoner_id)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('UnfitHistory.from_date','From Date',array('update'=>'#ListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecord','action' => 'urbanLabourListAjax','from_date'=> $from_date)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('UnfitHistory.to_date','To Date',array('update'=>'#ListingDiv','evalScripts' => true,'url'=>array('controller' => 'MedicalRecord','action' => 'urbanLabourListAjax','to_date'=> $to_date)));
            ?></th>
            
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
            <td><?php echo $funcall->getName($data["UnfitHistory"]["prisoner_id"],'Prisoner','prisoner_no');?></td>
            <td><?php  
                 echo $funcall->getName($data["UnfitHistory"]["prisoner_id"],'Prisoner','first_name')." ".$funcall->getName($data["UnfitHistory"]["prisoner_id"],'Prisoner','middle_name')." ".$funcall->getName($data["UnfitHistory"]["prisoner_id"],'Prisoner','last_name');?></td>
            <td><?php echo date('d-m-Y',strtotime($data["UnfitHistory"]["from_date"]))?> </td>
            <td><?php  if (date('d-m-Y',strtotime($data["UnfitHistory"]["to_date"]))=="01-01-1970") {
                echo "NA";
                
            }else{
                 echo date('d-m-Y',strtotime($data["UnfitHistory"]["to_date"]));

            } ?></td>
        </tr>
        
<?php
        $rowCnt++;
        echo $this->Js->writeBuffer();
    }
?>
    </tbody>
</table>
</div>
<?php }?>