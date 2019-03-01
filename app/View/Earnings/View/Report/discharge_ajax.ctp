
<style type="text/css">
.threedays td
{   
    background-color: #FF0000 !important;
    color: white;
}

.twodays td
{   
    background-color: #0000FF !important;
    color: white;
}

.onedays td
{   
    background-color: #663399 !important;
    color: white;
}

.threedays
{   
    background-color: #FF0000;
    color: white;
}

.twodays
{   
    background-color: #0000FF;
    color: white;
}

.onedays
{   
    background-color: #663399;
    color: white;
}
</style>
<?php
  App::uses('Appard', 'Model');
  $this->Appard=new Appard();
 ?>
<?php

if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "dischargeAjax/prison_id:$prison_id/prisoner_name:$prisoner_name";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
	$urlPdf = $exUrl.'/reqType:PDF';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
	echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download Pdf")),$urlPdf, array("escape" => false)));
	?>
</div>
<?php ?>
<div>
    <span class="threedays"><?php echo "For 3 days"; ?></span>
    <span class="twodays"><?php echo "For 2 days"; ?></span>
    <span class="onedays"><?php echo "For 1 days"; ?></span>
    
</div>

<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
        <tr>
            <th>Prisoner No</th>
            <th>Prisoner Name</th>
            <th>Date of Release</th>     
            <th>Remaing Days</th>
            <th>LPD</th>
            <th>EPD</th>
        </tr>
	</thead>
	<tbody>
<?php

	foreach($datas as $data){
        // debug($data);
         $days = round(((strtotime($data['Prisoner']['dor']) - (strtotime(date('d-m-Y')))) / 86400)); 
?>
    <tr class="<?php echo ($days == 3) ? 'threedays' : ''; ?> <?php echo ($days == 2) ? 'twodays' : ''; ?> <?php echo ($days == 1) ? 'onedays' : ''; ?>">
        <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
        <td><?php echo $data['Prisoner']['fullname']; ?></td>
        <td><?php echo ( $data['Prisoner']['dor']!=NULL) ? date("d-m-Y", strtotime($data['Prisoner']['dor'])) : ''; ?></td>
        
        <td>
            <?php 
            $days = round(((strtotime($data['Prisoner']['dor']) - (strtotime(date('d-m-Y')))) / 86400));
            echo $days." days";
            ?>
        </td>
        <td><?php echo ($data['Prisoner']['lpd']!='0000-00-00') ? date("d-m-Y", strtotime($data['Prisoner']['lpd'])) : '';?></td>
        <td><?php echo ($data['Prisoner']['epd']!='0000-00-00') ? date("d-m-Y", strtotime($data['Prisoner']['epd'])) : ''; ?></td>
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
