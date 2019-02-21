<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
</div>
<table id="MedicalReportTable" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th></th>
			<th>UPS Region</th>
			<th>UPS District</th>
			<th >Prison Station</th>
			<th colspan="3">Convicts</th>
			<th colspan="3">Remand</th>
			<th colspan="3">Debtor</th>
			<th colspan="3">Condemned</th>
			<th>Grand Total</th>
		</tr>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
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
			<th></th>
		</tr>		
	</thead>
	<tbody>
<?php
	if(is_array($) && count($)>0){
	  $count = 0;
		foreach($offenceList as $offenceListKey=>$offenceListVal){
?>
<?php $count++; ?>
	<tr>
			<td>4</td>
			<td>44</td>			
			<td></td>	
			<td>rt</td>
			<td></td>
			<td></td>			
			<td></td>	
			<td></td>
			<td></td>
			<td>dgd</td>			
			<td></td>	
			<td></td>
			<td>454</td>
			<td></td>			
			<td></td>	
			<td>45</td>
			<td></td>		

		</tr>
		<tr>
			<td></td>
			<td></td>			
			<td></td>	
			<td></td>
			<td></td>
			<td></td>			
			<td></td>	
			<td></td>
			<td></td>
			<td></td>			
			<td></td>	
			<td></td>
			<td></td>
			<td></td>			
			<td></td>	
			<td></td>
			<td></td>		

		</tr>



<?php 
		}
	}
 ?>	
 
 </tbody>
</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
?>
