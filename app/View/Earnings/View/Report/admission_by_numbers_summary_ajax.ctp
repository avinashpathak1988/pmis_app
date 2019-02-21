<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "admissionByNumbersSummaryAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th rowspan="3">Offence</th>
			<th colspan="12">Age Group</th>
			<th rowspan="3">Total</th>
		</tr>
		<tr>
			<th colspan="3">1st Time</th>
			<th colspan="3">2nd Time</th>
			<th colspan="3">3rd time</th>
			<th colspan="3">Nth Time</th>
		</tr>
		<tr>
			<th>Male</th>
			<th>Female</th>
			<th>Total</th>
			<th>Male</th>
			<th>Female</th>
			<th>Total</th>
			<th>Male</th>
			<th>Female</th>
			<th>Total</th>
			<th>Male</th>
			<th>Female</th>
			<th>Total</th>									
		</tr>
	</thead>
	<tbody>
<?php
	$firstMaleArr 	= array();
	$firstFemaleArr 	= array();
	$firstTotalArr 		= array();
	$thirdMaleArr 		= array();
	$thirdFemaleArr 	= array();
	$thirdTotalArr 		= array();
	$nthMaleArr 		= array();
	$nthFemaleArr 		= array();	
	$nthTotalArr 		= array();	
	$totalArr 			= array();
	foreach($datas as $key=>$val){
		$numberData 		= $funcall->getNumberofConviction($val['Offence']['id']);
		$first_total 		= $numberData[0][0]['first_time_males'] + $numberData[0][0]['first_time_females'];
		$firstMaleArr[]   	= $numberData[0][0]['first_time_males'];
		$firstFemaleArr[] 	= $numberData[0][0]['first_time_females'];
		$firstTotalArr[]  	= $first_total;
		$second_total 		= $numberData[0][0]['second_time_males'] + $numberData[0][0]['second_time_females'];
		$secondMaleArr[]   	= $numberData[0][0]['second_time_males'];
		$secondFemaleArr[] 	= $numberData[0][0]['second_time_females'];
		$secondTotalArr[]  	= $second_total;		
		$third_total 		= $numberData[0][0]['third_time_males'] + $numberData[0][0]['third_time_females'];
		$thirdMaleArr[]   	= $numberData[0][0]['third_time_males'];
		$thirdFemaleArr[] 	= $numberData[0][0]['third_time_females'];
		$thirdTotalArr[]  	= $third_total;
		$nth_total 			= $numberData[0][0]['nth_time_males'] + $numberData[0][0]['nth_time_females'];
		$nthMaleArr[]   	= $numberData[0][0]['nth_time_males'];
		$nthFemaleArr[] 	= $numberData[0][0]['nth_time_females'];
		$nthTotalArr[]  	= $nth_total;		
		$total 				= (int)$first_total + (int)$second_total + (int)$third_total + (int)$nth_total;
		$totalArr[] 		= $total;
?>
		<tr>
			<th><?php echo h($val['Offence']['name'])?></th>
			<th><?php echo h($numberData[0][0]['first_time_males'])?></th>
			<th><?php echo h($numberData[0][0]['first_time_females'])?></th>
			<th><?php echo h($first_total)?></th>
			<th><?php echo h($numberData[0][0]['second_time_males'])?></th>
			<th><?php echo h($numberData[0][0]['second_time_females'])?></th>
			<th><?php echo h($second_total)?></th>
			<th><?php echo h($numberData[0][0]['third_time_males'])?></th>
			<th><?php echo h($numberData[0][0]['third_time_females'])?></th>
			<th><?php echo h($third_total)?></th>
			<th><?php echo h($numberData[0][0]['nth_time_males'])?></th>
			<th><?php echo h($numberData[0][0]['nth_time_females'])?></th>
			<th><?php echo h($nth_total)?></th>
			<th><?php echo h($total)?></th>			
		</tr>
<?php
	}
?>	
		<tr>
			<th>Total</th>
			<th><?php echo h(array_sum($firstMaleArr))?></th>
			<th><?php echo h(array_sum($firstFemaleArr))?></th>
			<th><?php echo h(array_sum($firstTotalArr))?></th>
			<th><?php echo h(array_sum($secondMaleArr))?></th>
			<th><?php echo h(array_sum($secondFemaleArr))?></th>
			<th><?php echo h(array_sum($secondTotalArr))?></th>
			<th><?php echo h(array_sum($thirdMaleArr))?></th>
			<th><?php echo h(array_sum($thirdFemaleArr))?></th>
			<th><?php echo h(array_sum($thirdTotalArr))?></th>
			<th><?php echo h(array_sum($nthMaleArr))?></th>
			<th><?php echo h(array_sum($nthFemaleArr))?></th>
			<th><?php echo h(array_sum($nthTotalArr))?></th>
			<th><?php echo h(array_sum($totalArr))?></th>				
		</tr>
	</tbody>
</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
?>