<?php
if(is_array($datas) && count($datas)>0){
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
            'controller'                => 'MedicalRecord',
            'action'                    => 'mentalCaseAjax',
          
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
   $exUrl = "mentalCaseAjax";
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

<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
            <th><?php echo $this->Paginator->sort('Sl no'); ?></th> 
			<th>Prisoner No.</th>
			<th>Name Of Prisoner</th>
            <th>Mental Cases</th>
            <th>Certified</th>
            <th>Remark Date</th>
			<th>Remarks</th>
		</tr>
	</thead>
	<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
	foreach($datas as $data){
	?>
  <tr>
    <td><?php echo $rowCnt; ?>&nbsp;</td>
    <td> <?php echo $funcall->getName($data['MentalCase']['prisoner_id'],"Prisoner","prisoner_no");?>
        &nbsp;</td>
   
    <td><?php echo $data['MentalCase']['prisoner_name']; ?></td>
    <td><?php echo $data['MentalCase']['mental_case']; ?></td>
    <td><?php echo $data['MentalCase']['certified_case']; ?></td>
    <td><?php echo date('d-m-Y',strtotime($data['MentalCase']['date'])); ?></td>
    <td><?php echo $data['MentalCase']['remarks']; ?></td>
   </tr>
<?php
$rowCnt++;
	}
?>

	</tbody>

</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
echo $this->Js->writeBuffer();
?>
