<style>
#forwardBtn
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>
<style type="text/css">
              th, td{border: 1px solid black;}
           </style>
<?php
if(is_array($datas) && count($datas)>0){

?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
        
            <th>Sl no</th>                
            <th>Prisoner Name</th>
            <th>Production Warrent No.</th>
            <th>Offences</th>
            <th>Next Hearing Date</th>
            <th>Magisterial Area</th>
            <th>Court</th>
            <th>Case No.</th>
            <th style="text-align: left;">
              Status
              </th>
            <?php if($isAccess == 1){?>
                <!-- <th><?php// echo __('Edit'); ?></th> -->
                <!-- <th><?php //echo __('Delete'); ?></th> -->
            <?php }?>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = Configure::read($data['Courtattendance']['status']);
?>
        <tr>
        
            <td><?php echo $rowCnt; ?>&nbsp;</td>
            <td><?php echo $data['Prisoner']['first_name'].' '.$data['Prisoner']['middle_name'].' '.$data['Prisoner']['last_name']; ?></td>
            <td><?php echo ucwords(h($data['Courtattendance']['production_warrent_no'])); ?>&nbsp;</td> 
            <td><?php echo $offence_name=$funcall->getOffenceName($data['Courtattendance']['offence_id']);?></td>
            <td><?php echo date('m-d-Y H:i', strtotime($data['Courtattendance']['attendance_date'])); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['Magisterial']['name'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Court']['name'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Courtattendance']['case_no'])); ?>&nbsp;</td>
            <td>
            <?php 
              echo $display_status;
            
           ?>
          </td>
           
        </tr>
<?php
$rowCnt++;
}
?>
    </tbody>
</table>
<?php
echo $this->Form->end();
}else{
?>
<span style="color:red;">No records found!</span>
<?php    
}
?>    

<script>
$(document).ready(function(){
  
        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $('input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('input[type="checkbox"]:checked').length;
          var is_checkall = $('input[id="checkAll"]:checked').length;
          if(is_checkall == 1 && atLeastOneIsChecked == 1)
          { 
            $('#checkAll').attr('checked', false);
            $('#forwardBtn').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#forwardBtn').show();
          }
          else 
          {
            $('#forwardBtn').hide();
          }
        });
});
</script>