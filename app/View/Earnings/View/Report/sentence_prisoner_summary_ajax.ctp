<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "sentencePrisonerSummaryAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
			<th></th>
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
		$maleArr 	= array();
		$femaleArr 	= array();
		$gtotalArr 	= array();
		foreach($datas as $key=>$val){
			$gender_total 	= $val[0]['males'] + $val[0]['females'];
			$maleArr[] 		= $val[0]['males'];
			$femaleArr[] 	= $val[0]['females'];
			$gtotalArr[] 	= $gender_total;
?>
		<tr>
			<th><?php echo h($val['SentenceOf']['name'])?></th>
			<th><?php echo h($val[0]['males'])?></th>
			<th><?php echo h($val[0]['females'])?></th>
			<th><?php echo h($gender_total)?></th>						
		</tr>
<?php
		}
?>	
		<tr>
			<th>Total</th>
			<th><?php echo h(array_sum($maleArr))?></th>
			<th><?php echo h(array_sum($femaleArr))?></th>
			<th><?php echo h(array_sum($gtotalArr))?></th>
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