<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "admissionsSummaryAjax/prison_id:$prison_id/gender_id:$gender_id/from_date:$from_date/to_date:$to_date";
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
			<th rowspan="2"></th>
			<th colspan="2">Length of Sentence</th>
			<th colspan="2">Category of Status</th>
			<th rowspan="2">G/Total</th>
		</tr>
		<tr>
			<th>Short</th>
			<th>Long</th>
			<th>Adults</th>
			<th>Habitual</th>			
		</tr>
	</thead>
<?php
	if(is_array($prisonArr) && count($prisonArr)>0){
?>	
	<tbody>
<?php
		$shortTagArr 		= array();
		$longTagArr 		= array();
		$adultTagArr 		= array();
		$habitualTagArr 	= array();
		foreach($prisonArr as $genderKey=>$prisonVal){
			$shortCnt = isset($prisonVal[0])?$prisonVal[0]:0;
			$longCnt  = isset($prisonVal[1])?$prisonVal[1]:0;
			$adultCnt = isset($adultArr[$genderKey])?$adultArr[$genderKey]:0;
			$habitualCnt = isset($habitualArr[$genderKey])?$habitualArr[$genderKey]:0;

			$colTotal = $shortCnt + $longCnt + $adultCnt + $habitualCnt;

			$shortTagArr[] 	= $shortCnt;
			$longTagArr[]  	= $longCnt;
			$adultTagArr[] 	= $adultCnt;
			$habitualTagArr[] 	= $habitualCnt;			
?>
		<tr>
			<th><?php echo h($prisonVal['name'])?></th>
			<th><?php echo h($shortCnt)?></th>
			<th><?php echo h($longCnt)?></th>
			<th><?php echo h($adultCnt)?></th>
			<th><?php echo h($habitualCnt)?></th>
			<th><?php echo h($colTotal)?></th>			
		</tr>
<?php
		}
?>	
		<tr>
			<th>Total</th>
			<th><?php echo h(array_sum($shortTagArr))?></th>
			<th><?php echo h(array_sum($longTagArr))?></th>
			<th><?php echo h(array_sum($adultTagArr))?></th>
			<th><?php echo h(array_sum($habitualTagArr))?></th>
			<th><?php echo h(array_sum($shortTagArr) + array_sum($longTagArr) + array_sum($adultTagArr) + array_sum($habitualTagArr))?></th>
		</tr>
	</tbody>
<?php
	}
?>	
</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
?>