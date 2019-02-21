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
<table id="districtTable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Sl. No.</th>
			<th>offence</th>
		
<?php
	if(is_array($genderArr) && count($genderArr)>0){
		foreach($genderArr as $genderKey=>$genderVal){
?>
			<th><?php echo h($genderVal)?></th>
<?php		
		}
	}
?>
		
		</tr>
	</thead>
<?php
	if(is_array($offenceList) && count($offenceList)>0){
	  $count = 0;
		foreach($offenceList as $offenceListKey=>$offenceListVal){
?>
	<tr>
			<th><?php echo $count?></th>
			<th><?php echo $offenceListVal?></th>
		</tr>

<?php 
		}
	}
 ?>	
</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
?>