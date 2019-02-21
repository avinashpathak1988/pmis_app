<?php
  App::uses('Spps', 'Model');
  $this->Spps=new Spps();
 ?>
 <?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "SppsAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
			<th>Stage</th>
      <th colspan="2">Sex</th>
      <th>Total</th>
		</tr>
	</thead>
	<tbody>
    <tr>
      <td></td>
      <td>Male</td>
      <td>Female</td>
      <td></td>
    </tr>
    <?php
    $sql1="";
    $sql2="";
    if($prison_id != ''){
      $sql1=" and prison_id='".$prison_id."' ";
    }
    if($from_date != '' && $to_date != ''){
      $fd=explode('-',$from_date);
      $fd=$fd[2].'-'.$fd[1].'-'.$fd[0];
      $td=explode('-',$to_date);
      $td=$td[2].'-'.$td[1].'-'.$td[0];
      $sql2=" and date_of_assign between '".$fd."' and '".$td."' ";
    }
     ?>
<?php
$j=0;
$grand_total_male=0;
$grand_total_female=0;
$grand_total=0;
    $stages=$this->Spps->query("select * from stages where is_enable=1 order by stage_order");
foreach($stages as $stage){
$j++;
  ?>
  <tr>
    <td><?php echo $stage['stages']['name']; ?></td>
    <td>
        <?php
        $male_count=0;
            $male_count=$this->Spps->query("select count(*) as 'total' from spps where
            gender_id=1 and stage_id='".$stage['stages']['id']."' and is_approve=1
            and is_enable=1 and is_trash=0 and present_status=1".$sql1.$sql2);
            $male_count=$male_count[0][0]['total'];
            $grand_total_male=$grand_total_male + $male_count;
            echo $male_count;
         ?>
    </td>
    <td>
        <?php
        $female_count=0;
        $female_count=$this->Spps->query("select count(*) as 'total' from spps where
        gender_id=2 and stage_id='".$stage['stages']['id']."' and is_approve=1
        and is_enable=1 and is_trash=0 and present_status=1".$sql1.$sql2);
        $female_count=$female_count[0][0]['total'];
        $grand_total_female=$grand_total_female + $female_count;
        echo $female_count;
         ?>
    </td>
    <td>
      <?php
      $total_count=0;
      $total_count=$male_count + $female_count;
      $grand_total=$grand_total + $total_count;
        echo $total_count;
       ?>

    </td>
  </tr>
  <?php
}
 ?>
  <tr>
    <td><strong>Total</strong></td>
    <td><strong><?php echo $grand_total_male; ?></strong></td>
    <td><strong><?php echo $grand_total_female; ?></strong></td>
    <td><strong><?php echo $grand_total; ?></strong></td>
  </tr>
	</tbody>

</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
?>
