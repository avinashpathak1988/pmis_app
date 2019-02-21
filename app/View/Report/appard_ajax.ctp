<?php
  App::uses('Appard', 'Model');
  $this->Appard=new Appard();
 ?>
<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "appardAjax/prison_id:$prison_id/prisoner_name:$prisoner_name";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
</div>
<font color="green">Green: 3 days or less left</font> &nbsp;&nbsp;||&nbsp;&nbsp;<font color="blue">Blue: 1 Day or less left</font>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Sentence Type</th>
			<th>Gender</th>
      <th>Prison</th>
			<th>Prisoner Name</th>
      <th>Prisoner Number</th>
			<th>Offence</th>
			<th>CRB No.</th>
      <th>Days Remaining</th>
      <th>LPD</th>
      <th>EPD</th>
		</tr>
	</thead>
	<tbody>
<?php
//echo "<pre>";
//print_r($datas);
	foreach($datas as $data){
    $color="black";
    if($data['Appard']['release_date'] <= 3 && $data['Appard']['release_date'] > 0){
      $color="green";
    }
    if($data['Appard']['release_date'] <= 1 && $data['Appard']['release_date'] > 0){
      $color="blue";
    }
?>
		<tr style="color:<?php echo $color; ?>">
      <td>
          <?php
          $st=$this->Appard->query("select name from view_sentence_type where sentence_id='".$data['Appard']['sentence_id']."'");
          echo $st[0]['view_sentence_type']['name'];
           ?>
      </td>
			<td><?php echo $data['Appard']['gender_name']; ?></td>
      <td><?php echo $data['Appard']['name']." (".$data['Appard']['code'].")"; ?></td>
			<td><?php echo $data['Appard']['prisoner_name']; ?></td>
      <td><?php echo $data['Appard']['prisoner_no']; ?></td>
			<td>
<?php
  $offences=$data['Appard']['offence'];
  $o=explode(',',$offences);
foreach($o as $o1){
  $on=$this->Appard->query("select name from offences where id='".$o1."'");
  echo $on[0]['offences']['name']."<br>";
}
 ?>
      </td>
			<td><?php echo $data['Appard']['crb_no']; ?></td>
      <td><?php echo $data['Appard']['release_date']; ?></td>
      <td><?php echo $data['Appard']['lpd'];?></td>
      <td><?php echo $data['Appard']['epd']; ?></td>
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
