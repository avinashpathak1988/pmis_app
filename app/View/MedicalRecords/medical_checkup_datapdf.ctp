<?php
if(is_array($datas) && count($datas)>0){
    
?>

          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
               
<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Checkup Type</th>
            <th>Prisoner No.</th>
            <th>Prisoner Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Height</th>
            <th>Weight</th>
            <th>BMI</th>
            <th>T.B Test</th>
            <th>HIV Test</th>
            <th>Mental Case</th>
            <th>Other Diseases</th>
            <th>Folow Up Date</th>
            
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
        $gender_id=$data["Prisoner"]["gender_id"];
        if($gender_id==2){$gender="Female";}
        else if($gender_id==1){$gender="Male";}
?>
        <tr>
            <td><?php 
                  
                  echo $rowCnt;
             ?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["check_up"];?></td>
             <td><?php echo $data["Prisoner"]["prisoner_no"];?></td>
             <td><?php echo $data["Prisoner"]["fullname"];?></td>
             <td><?php echo $gender;?></td>
             <td><?php echo $data["Prisoner"]["age"];?></td>
             <td><?php echo $data["Prisoner"]["height_feet"]?> foot <?php echo $data["Prisoner"]["height_inch"]?> inch</td>
             <td><?php echo $data["MedicalCheckupRecord"]["weight"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["bmi"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["tb"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["hiv"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["mental_case"]?></td>
             <td><?php echo $data["MedicalCheckupRecord"]["other_disease"]?></td>
            <td><?php echo date('m-d-Y', strtotime($data["MedicalCheckupRecord"]["follow_up"]))?></td>

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