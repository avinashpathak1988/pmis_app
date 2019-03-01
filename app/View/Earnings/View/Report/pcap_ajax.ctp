<?php
  App::uses('Lopal', 'Model');
  $this->Appard=new Lopal();
 ?>
<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "pcapAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
			<th>Prison</th>
			<th>Prisoner Name</th>
      <th>Prisoner Number</th>
			<th>Stage</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($datas as $data){
?>
  <tr>
    <td><?php echo $data['Pcap']['name']." (".$data['Pcap']['code'].")"; ?></td>
    <td><?php echo $data['Pcap']['prisoner_name']; ?></td>
    <td><?php echo $data['Pcap']['prisoner_no']; ?></td>
    <td><?php echo $data['Pcap']['stage_name']; ?></td>
  </tr>
<?php
	}
?>
	</tbody>

</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
?>
