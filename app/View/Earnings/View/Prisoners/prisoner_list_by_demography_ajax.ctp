<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="col-sm-5">
        

    </div>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th>Sl no</th>                
      <th>Station </th>
      <th>Males</th>
      <th>Females</th>
      <th>Total</th>
    </tr>
  </thead>
<tbody>
<?php  
$rowCnt =1;
foreach($datas as $data){ ?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['prisons']['name'])); ?>&nbsp;</td> 
     
      <td><?php echo ucwords(h($data[0]['males'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data[0]['females'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data[0]['males']+$data[0]['females'])); ?>&nbsp;</td>
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>
<?php
}else{
?>
<span style="color:red;font-weight:bold;">No Record Found!!</span>
<?php    
}
?>    