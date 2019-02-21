<?php //echo '<pre>'; print_r($prisonerAttendanceList); exit;
if(is_array($datas) && count($datas)>0){
$btnName = Configure::read('SAVE');
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName = Configure::read('REVIEW');
  $isModal = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
$methodName = 'approveAttendances';
$modelName = 'PrisonerAttendance';    

{?>
  <!-- Verify Modal START -->
                         
  <!-- Verify Modal END -->
<?php }?>
        
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
           
            <th>SL#</th>
            <th>
                <?php 
                echo $this->Paginator->sort('PrisonerAttendance.attendance_date','Attendance Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Earnings','action' => 'attendanceAjax', 'status' => $status, 'working_party_id'=>$working_party_id, 'date_from' => $date_from, 'date_to' => $date_to)));
                ?>
            </th>
            <th>
                <?php 
                echo $this->Paginator->sort('Prisoner.prisoner_no','Prisoner No',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Earnings','action' => 'attendanceAjax', 'status' => $status, 'working_party_id'=>$working_party_id, 'date_from' => $date_from, 'date_to' => $date_to)));
                ?>
            </th>
            <th>
                <?php 
                echo $this->Paginator->sort('Prisoner.name','Prisoner Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Earnings','action' => 'attendanceAjax', 'status' => $status, 'working_party_id'=>$working_party_id, 'date_from' => $date_from, 'date_to' => $date_to)));
                ?>
            </th>
            <th>Present Status</th>
            <th>Working party</th>
            <!-- <th>Approve Status</th> -->
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){

        $id = $data['PrisonerAttendance']['id'];
        $prisoner_id = $data['Prisoner']['id'];
        $display_status = Configure::read($data[$modelName]['status']);
        ?>
        <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
            <?php
            if(!isset($is_excel)){
            ?>   
              
             
            <?php } ?>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerAttendance']['attendance_date'])); ?></td>
            <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
            <td><?php echo $data['Prisoner']['fullname']; ?></td>
            <td><?php echo isset($data['PrisonerAttendance']['is_present']) && $data['PrisonerAttendance']['is_present']==1?'Present':'Absent'; ?></td>
            <td><?php echo $data['WorkingParty']['name']; ?></td>
           <!--  <td>
                <?php if($data[$modelName]['status'] == 'Draft')
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
          </td> -->
        </tr>
<?php
        $rowCnt++;
    }
?>
    </tbody>
</table>
<?php echo $this->Form->end();?>
<?php
//pagination start 
if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Earnings',
            'action'                => 'attendanceListAjax'

        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "attendanceListAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
	$urlPDF = $exUrl.'/reqType:PDF';
	$urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download Pdf")),$urlPDF, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
	?>
    </div>
</div>
<?php
    }
//pagination end 
}else{
echo Configure::read('NO-RECORD');   
}
$ajaxUrl    = $this->Html->url(array('controller'=>'Earnings','action'=>'attendanceListAjax '));
?> 
<?php if(@$file_type != 'pdf'){?>
<script type="text/javascript">
$(document).ready(function(){
         
       
});

</script>
<?php } ?>  
               