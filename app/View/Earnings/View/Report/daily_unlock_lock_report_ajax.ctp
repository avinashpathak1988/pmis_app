<?php
if(is_array($datas) && count($datas)>0){
	 if(!isset($is_excel)){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "dailyUnlockLockReportAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
}
?>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Category</th>
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
        $maleArr        = array();
        $femaleArr      = array();
        $totGenderArr   = array();
		foreach($datas as $key=>$val){
            $totGender      = $val[0]['males'] + $val[0]['females'];
            $maleArr[]      = $val[0]['males'];
            $femaleArr[]    = $val[0]['females'];
            $totGenderArr[] = $totGender;
?>
		<tr>
			<th><?php echo h($val['PrisonerType']['name'])?></th>
			<th><?php echo h($val[0]['males'])?></th>
			<th><?php echo h($val[0]['females'])?></th>
			<th><?php echo h($totGender)?></th>	
		</tr>
<?php
		}
?>	
        <tr>
            <th>Total</th>
            <th><?php echo h(array_sum($maleArr))?></th>
            <th><?php echo h(array_sum($femaleArr))?></th>
            <th><?php echo h(array_sum($totGenderArr))?></th>
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