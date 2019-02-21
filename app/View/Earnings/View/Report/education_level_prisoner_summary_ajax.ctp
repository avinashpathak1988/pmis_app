<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "educationLevelPrisonerSummaryAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
			<th colspan="3">Convicts</th>
			<th colspan="3">Remands</th>
			<th colspan="3">Debtors</th>
			<th rowspan="2">G/Total</th>
		</tr>
		<tr>
			<th>Males</th>
			<th>Females</th>
			<th>Total</th>
			<th>Males</th>	
			<th>Females</th>
			<th>Total</th>
			<th>Males</th>	
			<th>Females</th>
			<th>Total</th>								
		</tr>
	</thead>
<?php
	if(is_array($datas) && count($datas)>0){
?>	
	<tbody>
<?php
		$totalConvictedMaleArr 		= array();
		$totalConvictedFemaleArr 	= array();
		$totalConvictedArr 			= array();
		$totalRemandMaleArr 		= array();
		$totalRemandFemaleArr 		= array();
		$totalRemandArr 			= array();
		$totalDebtorMaleArr 		= array();
		$totalDebtorFemaleArr 		= array();	
		$totalDebtorArr 			= array();	
		$grandTotalArr 				= array();
		foreach($datas as $key=>$val){
			$total_convicted 			= (int)$val[0]['convicted_males'] + (int)$val[0]['convicted_females'];
			$total_remand    			= (int)$val[0]['remand_males'] + (int)$val[0]['remand_females'];
			$total_debtor    			= (int)$val[0]['debtor_males'] + (int)$val[0]['debtor_females'];
			$gtotal 		 			= (int)$total_convicted + (int)$total_remand + (int)$total_debtor;
			$totalConvictedMaleArr[] 	= $val[0]['convicted_males'];
			$totalConvictedFemaleArr[] 	= $val[0]['convicted_females'];
			$totalConvictedArr[] 		= $total_convicted;
			$totalRemandMaleArr[] 		= $val[0]['remand_males'];
			$totalRemandFemaleArr[] 	= $val[0]['remand_females'];
			$totalRemandArr[] 			= $total_remand;
			$totalDebtorMaleArr[] 		= $val[0]['debtor_males'];
			$totalDebtorFemaleArr[] 	= $val[0]['debtor_females'];
			$totalDebtorArr[] 			= $total_debtor;	
			$grandTotalArr[] 			= $gtotal;			
?>
		<tr>
			<th><?php echo h($val['LevelOfEducation']['name'])?></th>
			<th><?php echo h($val[0]['convicted_males'])?></th>
			<th><?php echo h($val[0]['convicted_females'])?></th>
			<th><?php echo h($total_convicted)?></th>
			<th><?php echo h($val[0]['remand_males'])?></th>
			<th><?php echo h($val[0]['remand_females'])?></th>
			<th><?php echo h($total_remand)?></th>
			<th><?php echo h($val[0]['debtor_males'])?></th>
			<th><?php echo h($val[0]['debtor_females'])?></th>
			<th><?php echo h($total_debtor)?></th>
			<th><?php echo h($gtotal)?></th>						
		</tr>
<?php
		}
?>	
		<tr>
			<th>Total</th>
			<th><?php echo h(array_sum($totalConvictedMaleArr))?></th>
			<th><?php echo h(array_sum($totalConvictedFemaleArr))?></th>
			<th><?php echo h(array_sum($totalConvictedArr))?></th>
			<th><?php echo h(array_sum($totalRemandMaleArr))?></th>
			<th><?php echo h(array_sum($totalRemandFemaleArr))?></th>
			<th><?php echo h(array_sum($totalRemandArr))?></th>
			<th><?php echo h(array_sum($totalDebtorMaleArr))?></th>
			<th><?php echo h(array_sum($totalDebtorFemaleArr))?></th>
			<th><?php echo h(array_sum($totalDebtorArr))?></th>
			<th><?php echo h(array_sum($grandTotalArr))?></th>
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