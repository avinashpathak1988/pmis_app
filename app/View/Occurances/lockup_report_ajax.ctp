<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="col-sm-5">
        

    </div>
</div>
<?php
foreach($finalLockupDataList as $key1=>$lockuplist){
  //debug($rows);
?>
  <h4 class="text-center" style="background: #efefef;padding: 10px;"><?php echo $funcall->getName($key1,'LockupType','name') ?></h4>
  <?php 
  //echo '<pre>'; print_r($rows); exit;
  if(count($lockuplist) == 0){

    echo '...';
  }
  else 
  {?>
    <table id="districtTable" class="table table-bordered">
      <thead>
        <tr>
        <th rowspan="2" colspan="2"></th>
        <?php
          /*foreach($datas as $key=>$rows){debug($rows);
            }*/
        ?>
        <th colspan="3">Convict</th>
        <th colspan="3">Condemned</th>
        <th colspan="3">Remand</th>
        <th colspan="3">Debtor</th>
        <th colspan="3">Lodger</th>
    </tr>
    <tr style="background-color: rosybrown;">
        <td>Male</td>
        <td>Female</td>
        <td>Total</td>
        <td>Male</td>
        <td>Female</td>
        <td>Total</td>
        <td>Male</td>
        <td>Female</td>
        <td>Total</td>
        <td>Male</td>
        <td>Female</td>
        <td>Total</td>
        <td>Male</td>
        <td>Female</td>
        <td>Total</td>
    </tr>
      </thead>
    <tbody>
    <?php //debug($lockuplist);
    $i='';
      foreach ($lockuplist as $lockuplistchildkey => $lockuplistchildvalue) {//debug($lockuplistchildvalue);
        foreach ($lockuplistchildvalue as $newkey => $value) {
       //debug($value);
          
      ?>
      <tr>
          <?php 
        if($i==$lockuplistchildkey && $i!=''){?>

        <?php }else{?>
          <td rowspan="2"><?php echo $lockuplistchildkey;?></td>
        <?php }?>

        <td><?php echo $newkey;?></td>
        <?php //foreach ($value as $newkey1 => $newvalue) {//
         // debug($value);?>
            <td><?php echo isset($value[2]['Male'])?$value[2]['Male']:'';?></td>
            <td><?php echo isset($value[2]['Female'])?$value[2]['Female']:'';?></td>
            <td><?php echo isset($value[2]['Total'])?$value[2]['Total']:'';?></td>
            <td><?php echo isset($value[4]['Male'])?$value[4]['Male']:'';?></td>
            <td><?php echo isset($value[4]['Female'])?$value[4]['Female']:'';?></td>
            <td><?php echo isset($value[4]['Total'])?$value[4]['Total']:'';?></td>
            <td><?php echo isset($value[1]['Male'])?$value[1]['Male']:'';?></td>
            <td><?php echo isset($value[1]['Female'])?$value[1]['Female']:'';?></td>
            <td><?php echo isset($value[1]['Total'])?$value[1]['Total']:'';?></td>
            <td><?php echo isset($value[3]['Male'])?$value[3]['Male']:'';?></td>
            <td><?php echo isset($value[3]['Female'])?$value[3]['Female']:'';?></td>
            <td><?php echo isset($value[3]['Total'])?$value[3]['Total']:'';?></td>
            <td><?php echo isset($value[5]['Male'])?$value[5]['Male']:'';?></td>
            <td><?php echo isset($value[5]['Female'])?$value[5]['Female']:'';?></td>
            <td><?php echo isset($value[5]['Total'])?$value[5]['Total']:'';?></td>
        <?php //}?>
      </tr> 
    <?php $i=$lockuplistchildkey;
  }
        
    }//}?>
     
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