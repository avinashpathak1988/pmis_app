<?php if (is_array($show_data) && count($show_data)>0) {
	
 ?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th>Area Of Deployment</th>
      <th>Force Number</th>
    </tr>
  </thead>
	<tbody>
	<?php  
		foreach ($show_data as $key => $value) {

	?>
		<tr>
			<td><?php echo ucwords(h($value['AreaOfDeployment']['name']));?></td>
			<td><?php //echo $funcall->getName($value['ShiftDeployment']['user_id'],'User','force_number');?>
				<?php $forceIds = explode(',', $value['ShiftDeployment']['user_id']);
			      //debug($forceIds);
			      $forceno='';
			                for ($i=0; $i < count($forceIds); $i++) { 
			                  //echo $forceIds[$i];
			                  $forceno .= $funcall->getName($forceIds[$i],'User','force_number').',';//$data['User']['force_number']
			                }
			                echo rtrim($forceno,',');
			       ?>
			</td>
<?php
}
?>
	</tbody>
</table>
<?php
}else{
	echo "No Area Of Deployment is saved !!";
?>

<?php    
}
?>    