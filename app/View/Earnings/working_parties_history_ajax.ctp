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


 ?>

        
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
           
            <th>SL#</th>
            <th>Prisoner No</th>
            <th>Prisoner Name</th>
            <th>working party</th>
            <th>From Date</th>
            <th>To Date</th>
           <!--  <th>Recent Working Party</th> -->
            
            <!-- <th>Approve Status</th> -->
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){

        $id = $data['WorkingPartyPrisoner']['id'];
        // $prisoner_id = $data['Prisoner']['id'];
       
        ?>
        <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
            <?php
            if(!isset($is_excel)){
            ?>   
              
             
            <?php } ?>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $funcall->getName($data['WorkingPartyPrisoner']['prisoner_id'],'Prisoner', 'prisoner_no');?></td>
            <td><?php echo $funcall->getName($data['WorkingPartyPrisoner']['prisoner_id'],'Prisoner', 'first_name');?></td>
            <td><?php echo $funcall->getName($data['WorkingPartyPrisoner']['working_party_id'], 'WorkingParty', 'name'); ?></td>
            <td><?php echo date('d-m-Y', strtotime($funcall->getPrisonerAttendance($data['WorkingPartyPrisoner']['prisoner_id'],$data['WorkingPartyPrisoner']['working_party_id']))); ?></td>
            
            <td><?php echo date('d-m-Y', strtotime($funcall->getPrisonerAttendanceEndDate($data['WorkingPartyPrisoner']['prisoner_id'],$data['WorkingPartyPrisoner']['working_party_id']))); ?></td>
           <!--  <td><?php //echo $data['WorkingPartyPrisoner']['name']; ?></td> -->
           
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

} else{
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
               