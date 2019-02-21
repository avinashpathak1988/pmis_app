<style>
#forwardBtn
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
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'courtattendances',
            'action'                    => 'courtsTrackingReportAjax',
            // 'magisterial_id'            => $magisterial_id,
            // 'court_id'                  => $court_id,
            // 'prison_id'            => $prison_id,
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
    //magisterial_id:$magisterial_id/court_id:$court_id/prison_id:$prison_id
    $exUrl = "courtsTrackingReportAjax/";
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
<?php
if(isset($is_excel)){
    ?>
    <style type="text/css">
      th, td{border: 1px solid black;}
    </style>
    <?php
}
?> 
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Sl no</th>                
            <th>Prisoner No.</th>
            <th>Prisoner Name</th>
            <th>Prisoner Sub Type</th>
            <th>Admission Date</th>
            <th>No of day(Stay)</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
	$days = '';
    foreach($datas as $data){
	if(isset($data['Prisoner']['doa']) && $data['Prisoner']['doa'] != '0000-00-00')
	{
        $days = round((((strtotime(date('d-m-Y'))) - strtotime($data['Prisoner']['doa'])) / 86400));
	}	
    ?>
        <tr>
            <td><?php echo $rowCnt; ?>&nbsp;</td>
            <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
            <td><?php echo $data['Prisoner']['first_name'].' '.$data['Prisoner']['middle_name'].' '.$data['Prisoner']['last_name']; ?></td>
            <td><?php echo $funcall->getName($data['PrisonerOffence']['offence_category_id'],"OffenceCategory","name"); ?></td>
            <td><?php 
			if(isset($data['Prisoner']['doa']) && $data['Prisoner']['doa'] != '0000-00-00')
			{
					echo date("d-m-Y",strtotime($data['Prisoner']['doa'])); 
			}
			?>
			
			</td>
            <td>
                <?php 
                if($data['PrisonerOffence']['offence_category_id'] == 1){
				
					if($days > 0)
                    echo $days ." days";
                }
                if($data['PrisonerOffence']['offence_category_id'] == 2){
					if($days > 0)
                    echo $days ." days";
                }
                ?>
            </td>
        </tr>
    <?php
	$days = '';
    $rowCnt++;
    }
    ?>
    </tbody>
</table>
<?php
echo $this->Form->end();
}else{
echo Configure::read("NO-RECORD"); 
}
?>    
