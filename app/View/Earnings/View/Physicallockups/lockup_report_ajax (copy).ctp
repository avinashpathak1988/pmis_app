<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="col-sm-5">
        

    </div>
</div>
<?php
foreach($datas as $key=>$rows){
  
?>
  <h4 class="text-center" style="background: #efefef;padding: 10px;"><?php echo $key ?></h4>
  <?php 
  //echo '<pre>'; print_r($rows); exit;
  if(count($rows) == 0){

    echo '...';
  }
  else 
  {?>
    <table id="districtTable" class="table table-bordered table-responsive">
      <thead>
        <tr>   
          <th>Prisoner Type </th>
          <th>Male</th>
          <th>Female</th>
          <th>Total</th>
        </tr>
      </thead>
    <tbody>
      <?php 
      $totalCont = array();
      foreach($rows as $data){ 
        $totalCont[] =  $data[0]['males'] + $data[0]['female'];
        ?>
        <tr> 
          <td><?php echo ucwords(h($data['prisoner_types']['prisoner_type'])); ?>&nbsp;</td> 
          <td><?php echo h($data[0]['males']); ?>&nbsp;</td>
          <td><?php echo h($data[0]['female']); ?>&nbsp;</td>
          <td><?php echo h($data[0]['males']+($data[0]['female'])); ?>&nbsp;</td>
        </tr>
    <?php } ?>
        <tr> 
          <td colspan="4" style="text-align: center;color: red;">
            <?php
            // echo $totalPrisoner;
            if(array_sum($totalCont)!=$totalPrisoner){
                ?>
                Incorrect, It is not matched with prisoner count with application.
                <?php
            }
            ?>
          </td>
        </tr>
      </tbody>
    </table>

  <?php }
    }?>
<?php
}else{
?>
<span style="color:red;font-weight:bold;">No Record Found!!</span>
<?php    
}
?>    