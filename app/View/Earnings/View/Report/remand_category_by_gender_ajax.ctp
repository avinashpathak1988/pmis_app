<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "remandCategoryByGenderAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
			<th>Sl. No.</th>
			<th>Status</th>
<?php
	if(is_array($genderArr) && count($genderArr)>0){
		foreach($genderArr as $genderKey=>$genderVal){
?>
			<th><?php echo h($genderVal)?></th>
<?php		
		}
	}
?>
			<th>Total</th>
		</tr>
	</thead>
<?php
	if(is_array($prisonArr) && count($prisonArr)>0){
?>	
	<tbody>
<?php
		$cnt = 0;
		$totalColArr = array();
		$totalSumArr = array();
		foreach($prisonArr as $prisonKey=>$prisonVal){
			$cnt++;
			$rowTotalArr = array();
?>
		<tr>
			<th><?php echo h($cnt)?></th>
			<th><?php echo h($prisonVal['name'])?></th>
<?php
			if(is_array($genderArr) && count($genderArr)>0){
				foreach($genderArr as $genderKey=>$genderVal){
					$rowTotalArr[] 				= isset($prisonVal[$genderKey])?$prisonVal[$genderKey]:0;
					$totalColArr[$genderKey][] 	= isset($prisonVal[$genderKey])?$prisonVal[$genderKey]:0;
?>
			<th align="center"><?php echo h(isset($prisonVal[$genderKey])?$prisonVal[$genderKey]:0)?></th>
<?php		
				}
			}
			$totalSumArr[] = array_sum($rowTotalArr);
?>
			<th><?php echo h(array_sum($rowTotalArr))?></th>
		</tr>
<?php
		}
?>		
		<tr>
			<th colspan="2">Total</th>
<?php
		if(is_array($genderArr) && count($genderArr)>0){
			foreach($genderArr as $genderKey=>$genderVal){
?>
			<th><?php echo h(isset($totalColArr[$genderKey])?array_sum($totalColArr[$genderKey]):0)?></th>
<?php		
			}
		}
?>
			<th><?php echo h(array_sum($totalSumArr))?></th>
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