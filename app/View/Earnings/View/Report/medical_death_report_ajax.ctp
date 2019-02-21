<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "medicalDeathReportAjax/prison_id:$prison_id/gender_id:$gender_id/from_date:$from_date/to_date:$to_date";
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
			<th rowspan="2">Sentence</th>
			<th rowspan="2">Station</th>
			<th colspan="2">Male</th>
			<th colspan="2">Female</th>
			<th rowspan="2">G/Total</th>
		</tr>
		<tr>
			<th>Death In</th>
			<th>Death Out</th>
			<th>Death In</th>
			<th>Death Out</th>			
		</tr>
	</thead>
	<tbody>
<?php
		$colTotalArr 		= array();
		$indeathMaleArr 	= array();
		$outdeathMaleArr 	= array();
		$indeathFemaleArr 	= array();
		$outdeathFemaleArr 	= array();
	foreach($datas as $key=>$val){
			$colTotal = $val[0]['in_death_male'] + $val[0]['out_death_male'] + $val[0]['in_death_female'] + $val[0]['out_death_female'];
			$colTotalArr[] 			= $colTotal;
			$indeathMaleArr[] 		= $val[0]['in_death_male'];
			$outdeathMaleArr[]  	= $val[0]['out_death_male'];
			$indeathFemaleArr[] 	= $val[0]['in_death_female'];
			$outdeathFemaleArr[] 	= $val[0]['out_death_female'];			
?>
		<tr>
			<th><?php echo h($val['PrisonerType']['name'])?></th>
			<th><?php echo h($val['Prison']['name'])?></th>
			<th><?php echo h($val[0]['in_death_male'])?></th>
			<th><?php echo h($val[0]['out_death_male'])?></th>
			<th><?php echo h($val[0]['in_death_female'])?></th>
			<th><?php echo h($val[0]['out_death_female'])?></th>
			<th><?php echo h($colTotal)?></th>			
		</tr>
<?php
	}
?>	
		<tr>
			<th colspan="2">Total</th>
			<th><?php echo h(array_sum($indeathMaleArr))?></th>
			<th><?php echo h(array_sum($outdeathMaleArr))?></th>
			<th><?php echo h(array_sum($indeathFemaleArr))?></th>
			<th><?php echo h(array_sum($outdeathFemaleArr))?></th>
			<th><?php echo h(array_sum($colTotalArr))?></th>
		</tr>
	</tbody>

</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
?>