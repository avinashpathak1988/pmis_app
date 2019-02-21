<?php
  App::uses('Appard', 'Model');
  $this->Appard=new Appard();
 ?>
<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "dischargeLongAjax/prison_id:$prison_id/prisoner_name:$prisoner_name";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
	$urlPdf = $exUrl.'/reqType:PDF';
    echo ($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
	echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download Pdf")),$urlPdf, array("escape" => false)));
	?>
</div>

<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
        <tr>
            <th>Prisffoner No</th>
            <th>Prisoner Name</th>
            <th>Date of Release</th>     
            <th>LPD</th>
            <th>EPD</th>
            <th>Discahrge Summary Board</th>
        </tr>
	</thead>
	<tbody>
<?php
	foreach($datas as $data){
        // debug($data);
?>
    <tr>
        <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
        <td><?php echo $data['Prisoner']['fullname']; ?></td>
        <td><?php echo ( $data['Prisoner']['dor']!=NULL) ? date("d-m-Y", strtotime($data['Prisoner']['dor'])) : ''; ?></td>
        
     
        <td><?php echo ($data['Prisoner']['lpd']!='0000-00-00') ? date("d-m-Y", strtotime($data['Prisoner']['lpd'])) : '';?></td>
        <td><?php echo ($data['Prisoner']['epd']!='0000-00-00') ? date("d-m-Y", strtotime($data['Prisoner']['epd'])) : ''; ?></td>
        <td><?php echo $this->Html->link("Discharge Summary",array("controller"=>"report","action"=>"dischargeSummary",$data['Prisoner']['id']), array("escape" => false,'class'=>'btn btn-mini btn-success')); ?></td>
    </tr>
<?php
	}
?>
	</tbody>

</table>
<?php
}else{
	echo  Configure::read('NO-RECORD');
}
?>
