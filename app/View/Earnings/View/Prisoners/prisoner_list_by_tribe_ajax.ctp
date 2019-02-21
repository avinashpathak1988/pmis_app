<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="col-sm-5">
        

    </div>
</div>
<table  id="districtTable" class="table table-bordered table-striped table-responsive" >
  <thead>
    <tr>
       <th rowspan="2">Sl No</th>
       <th rowspan="2">Tribe</th>                
       <th colspan="3">Convicts </th>
       <th colspan="3">Remands</th>
       <th rowspan="2">G Total</th>
    </tr>
    <tr>
        <td>Males</td>
        <td>Females</td>
        <td>Total</td>
        <td>Males</td>
        <td>Females</td>
        <td>Total</td>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt =1;
foreach($datas as $data){ ?>
    <tr><!-- change this as per the convits or remands --> 
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['tribes']['tribe'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data[0]['male'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data[0]['female'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data[0]['male']+$data[0]['female'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data[0]['male'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data[0]['female'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data[0]['male']+$data[0]['female'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data[0]['male']+$data[0]['male']+$data[0]['female']+$data[0]['female'])); ?>&nbsp;</td>
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