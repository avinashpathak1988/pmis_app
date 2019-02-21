<?php
if(is_array($datas) && count($datas)>0){

?>                    
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Prisoner Name</th>
            <th>Punch In</th>
            <th>Punch Out</th>         
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = 1;
    foreach($datas as $data){
        // pr($data);
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $funcall->getName($data['EMPLOYEEID'],"Prisoner","prisoner_no");?> </td>
            <td><?php echo date("d-m-Y h:i A", strtotime($data['INtime'])) ;?></td>
            <td><?php echo date("d-m-Y h:i A", strtotime($data['OutTime'])) ;?></td>
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