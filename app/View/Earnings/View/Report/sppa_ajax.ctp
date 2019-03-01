<?php
  App::uses('Spps', 'Model');
  $this->Spps=new Spps();
 ?>
 <?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "SppaAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
			<th>Age Group</th>
      <th colspan="5">Stage</th>
      <th></th>
		</tr>
	</thead>
	<tbody>
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
     <tr>
       <td></td>
       <?php
       $stages=$this->Spps->query("select * from stages where is_enable=1 order by stage_order");
   foreach($stages as $stage){
      ?>
          <td><?php echo $stage['stages']['name']; ?></td>
      <?php
   }
        ?>

       <td>Total</td>
     </tr>

     <?php
        $age_group=array(
          '18-21','22-30','31-40','41-50','50+'
        );
        $total=0;
        foreach($age_group as $age){
          $sql3="";
          ?>
              <tr>
                <td><?php echo $age; ?></td>
                <td>
<?php
$row_count=0;
    $count1=0;
    $count2=0;
    $count3=0;
    $count4=0;
    $count5=0;
    if($age != '50+'){
      $a=explode('-',$age);
      $a1=$a[0];
      $a2=$a[1];
      $sql3=" and age >= '".$a1."' and age <= '".$a2."'";
    }else{
      $sql3=" and age >= '".$age."' ";
    }

    $count1=$this->Spps->query("select count(*) as 'total' from spps where
            stage_id=1 and is_approve=1
            and is_enable=1 and is_trash=0 and present_status=1".$sql1.$sql2.$sql3);
            $count1=$count1[0][0]['total'];
            echo $count1;
 ?>
                </td>
                <td>
<?php
$count2=$this->Spps->query("select count(*) as 'total' from spps where
        stage_id=2 and is_approve=1
        and is_enable=1 and is_trash=0 and present_status=1".$sql1.$sql2.$sql3);
        $count2=$count2[0][0]['total'];
        echo $count1;
 ?>
                </td>
                <td>
<?php
$count3=$this->Spps->query("select count(*) as 'total' from spps where
        stage_id=3 and is_approve=1
        and is_enable=1 and is_trash=0 and present_status=1".$sql1.$sql2.$sql3);
        $count3=$count3[0][0]['total'];
        echo $count3;
 ?>
                </td>
                <td>
<?php
$count4=$this->Spps->query("select count(*) as 'total' from spps where
        stage_id=4 and is_approve=1
        and is_enable=1 and is_trash=0 and present_status=1".$sql1.$sql2.$sql3);
        $count4=$count4[0][0]['total'];
        echo $count4;
 ?>
                </td>
                <td>
                  <?php
                  $count5=$this->Spps->query("select count(*) as 'total' from spps where
                          stage_id=5 and is_approve=1
                          and is_enable=1 and is_trash=0 and present_status=1".$sql1.$sql2.$sql3);
                          $count5=$count5[0][0]['total'];
                          echo $count5;
                   ?>
                </td>
                <td>
<?php
$row_count=$count1 + $count2 + $count3 + $count4 + $count5;
$total=$total + $row_count;
echo $row_count;
 ?>
                </td>
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
