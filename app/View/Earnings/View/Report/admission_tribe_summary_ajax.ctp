<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "admissionTribeSummaryAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
			<th rowspan="2">Tribe</th>
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
		$convictmaleArr 	= array();
		$convictfemaleArr 	= array();
		$gConvictArr 		= array();
		$remandmaleArr 		= array();
		$remandfemaleArr 	= array();
		$gRemandArr 		= array();
		$debtormaleArr 		= array();
		$debtorfemaleArr 	= array();	
		$gDebtorArr 		= array();	
		$gTotalTribe 		= array();
		foreach($datas as $key=>$val){
			$convict_total 			= (int)$val[0]['convicted_males'] + (int)$val[0]['convicted_females'];
			$convictmaleArr[] 		= $val[0]['convicted_males'];
			$convictfemaleArr[] 	= $val[0]['convicted_females'];
			$gConvictArr[] 			= $convict_total;
			$remand_total 			= (int)$val[0]['remand_males'] + (int)$val[0]['remand_females'];
			$remandmaleArr[] 		= $val[0]['remand_males'];
			$remandfemaleArr[] 		= $val[0]['remand_females'];
			$gRemandArr[] 			= $remand_total;
			$debtor_total 			= (int)$val[0]['debtor_males'] + (int)$val[0]['debtor_females'];
			$debtormaleArr[] 		= $val[0]['debtor_males'];
			$debtorfemaleArr[] 		= $val[0]['debtor_females'];
			$gDebtorArr[] 			= $debtor_total;
			$total_tribe            = (int)$convict_total + (int)$remand_total + (int)$debtor_total;	
			$gTotalTribe[] 			= $total_tribe;			
			//$gtotalArr[] 			= $gender_total;
?>
		<tr>
			<th><?php echo h($val['Tribe']['name'])?></th>
			<th><?php echo h($val[0]['convicted_males'])?></th>
			<th><?php echo h($val[0]['convicted_females'])?></th>
			<th><?php echo h($convict_total)?></th>
			<th><?php echo h($val[0]['remand_males'])?></th>
			<th><?php echo h($val[0]['remand_females'])?></th>
			<th><?php echo h($remand_total)?></th>	
			<th><?php echo h($val[0]['debtor_males'])?></th>
			<th><?php echo h($val[0]['debtor_females'])?></th>
			<th><?php echo h($debtor_total)?></th>		
			<th><?php echo h($total_tribe)?></th>												
		</tr>
<?php
		}
?>	
		<tr>
			<th>Total</th>
			<th><?php echo h(array_sum($convictmaleArr))?></th>
			<th><?php echo h(array_sum($convictfemaleArr))?></th>
			<th><?php echo h(array_sum($gConvictArr))?></th>
			<th><?php echo h(array_sum($remandmaleArr))?></th>
			<th><?php echo h(array_sum($remandfemaleArr))?></th>
			<th><?php echo h(array_sum($gRemandArr))?></th>
			<th><?php echo h(array_sum($debtormaleArr))?></th>
			<th><?php echo h(array_sum($debtorfemaleArr))?></th>
			<th><?php echo h(array_sum($gDebtorArr))?></th>		
			<th><?php echo h(array_sum($gTotalTribe))?></th>				
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