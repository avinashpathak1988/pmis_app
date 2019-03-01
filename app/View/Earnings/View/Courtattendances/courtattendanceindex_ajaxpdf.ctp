<?php
if(is_array($datas) && count($datas)>0){
   
?>


          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>

<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
        
            <th>Sl no</th>                
            <th>Production Warrent No.</th>
            <th>Offences</th>
            <th>Next Hearing Date</th>
            <th>Magisterial Area</th>
            <th>Court</th>
            <th>Case No.</th>
            <th style="text-align: left;">
              Status
              </th>
              
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
            <td><?php echo ucwords(h($data['Courtattendance']['production_warrent_no'])); ?>&nbsp;</td> 
            <td><?php echo $offence_name=$funcall->getOffenceName($data['Courtattendance']['offence_id']);?></td>
            <td><?php echo date('m-d-Y H:i', strtotime($data['Courtattendance']['attendance_date'])); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['Magisterial']['name'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Court']['name'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Courtattendance']['case_no'])); ?>&nbsp;</td>
            <td><?php echo $display_status;?></td>
           
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
...
<?php    
}
?>    