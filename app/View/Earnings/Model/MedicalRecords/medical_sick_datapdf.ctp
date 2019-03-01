<style>
#forwardBtnclinicalattendance
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>
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
            <th>Prisoner No.</th>
            <th>Checkup Date</th>
            <th>Attendence Description</th>
            <th>Compliant</th>
            <th>Examination</th>
            <th>Deferential Digonosis</th>
            <th>Lab Test</th>
            <th>Results</th>
            <th>Digonosis(Dx)</th>
            <th>Treatement (Rx)</th>
            <th>Radiology</th>
            <th>Durg Description Prescribed</th>
            <th>Status</th>
           
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
        $display_status = Configure::read($data['MedicalSickRecord']['status']);
?>
        <tr>
           
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data["Prisoner"]["prisoner_no"];?></td>
            <td><?php echo date('m-d-Y', strtotime($data["MedicalSickRecord"]["check_up_date"]))?></td>
            <td><?php echo $data["MedicalSickRecord"]["attendance"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["compliant"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["examination"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["digonosis"]?></td>
            <td><?php echo $data["Disease"]["name"]?> </td>
            <td><?php echo $data["MedicalSickRecord"]["results"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["digonosis_dx"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["treatement_rx"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["radiology"]?></td>
            <td><?php echo $data["MedicalSickRecord"]["drug_description"]?></td>
            <td>
            <?php if($data["MedicalSickRecord"]['status'] == 'Draft')
            {
              echo $display_status;
            }
            else 
            {
              $status_info = '<b>Status: </b>'.$display_status.'<br>';
              if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
                $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
              if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
                $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $display_status;?></a>
              <?php 
            }?>
          </td>
 
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
    <span style="color:red;">No records found!</span>
<?php    
}
?>                    


