<?php
  App::uses('Bmiview', 'Model');
  $this->Bmiview=new Bmiview();
 ?>
<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "bmiAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
			<th>Date</th>
			<th>Prison</th>
			<th>Prisoner</th>
			<th>BMI</th>
			<th>Increase/Decrease</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($datas as $data){
?>
		<tr>
			<td><?php echo date('m/d/Y',strtotime($data['Bmiview']['med_modified'])); ?></td>
      <td><?php echo $data['Bmiview']['name']." (".$data['Bmiview']['code'].")"; ?></td>
      <td><?php echo $data['Bmiview']['prisoner_name']." (".$data['Bmiview']['prisoner_no'].")" ?></td>
      <td><?php echo $data['Bmiview']['bmi'] ?></td>
      <td>
<?php
      $d=$this->Bmiview->find("first",array(
        'conditions'=>array(
          'Bmiview.id'=>$data['Bmiview']['id'],
          'Bmiview.med_modified <'=>$data['Bmiview']['med_modified'],
        ),
        'order'=>array(
            'Bmiview.med_modified'=>'DESC'
        )
      ));
    //  echo "<pre>";
    //  print_r($d);
    if(@d['Bmiview']['bmi'] > 0){
      if(@d['Bmiview']['bmi'] < $data['Bmiview']['bmi']){
        echo "+ve ".($data['Bmiview']['bmi'] - @d['Bmiview']['bmi']);
      }
      if(@d['Bmiview']['bmi'] > $data['Bmiview']['bmi']){
        echo "-ve ".(@d['Bmiview']['bmi'] - $data['Bmiview']['bmi']);
      }
    }
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
