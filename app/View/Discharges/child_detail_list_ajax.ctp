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
<?php
if(is_array($datas) && count($datas)>0){
  if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'Discharges',
            'action'                    => 'childDetailListAjax',
            'prisoner_id'               => $prisoner_id,
            'status'=>$status,
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
    $exUrl = "childDetailListAjax/prisoner_id:$prisoner_id/status:$status";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPDF = $exUrl.'/reqType:PDF';
	$urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
	?>
    </div>
</div>

<?php 
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/discharges/childDetailList'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button> 
<?php
    }
?>
<?php
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        }
          ?> 
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
        <?php
        if(!isset($is_excel)){
          ?>
        <th>
            <?php echo $this->Form->input('checkAll', array(
                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
            ));?>
          </th>
          <?php
        }
          ?>
            <th>SL#</th>
            <th>Prisoner No</th>
            <th>Prisoner Name</th>
            <th>Child Name</th>
            <th>Father Name</th>
            <th>Gender</th>
            <th>Place Of Birth</th>
            <th>View Details</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
    // debug($data);
  $display_status = Configure::read($data['PrisonerChildDetail']['status']);
  $prisonerDetails = $funcall->getPrisonerDetails($data['PrisonerChildDetail']['prisoner_id']);
?>
        <tr>
        <?php
        if(!isset($is_excel)){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['PrisonerChildDetail']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['PrisonerChildDetail']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['PrisonerChildDetail']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['PrisonerChildDetail']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['PrisonerChildDetail']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['PrisonerChildDetail']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
            ?>
          </td>
          <?php
          }
          ?>
            <td><?php echo $rowCnt; ?></td>
            
            <td><?php echo $prisonerDetails["Prisoner"]["prisoner_no"]?> </td>
            <td><?php echo $prisonerDetails["Prisoner"]["first_name"]." ".$prisonerDetails["Prisoner"]["last_name"]?> </td>
            <td><?php echo $data['PrisonerChildDetail']['name']; ?></td>
            <td><?php echo $data['PrisonerChildDetail']['father_name']; ?></td>
            <td><?php echo $data['Gender']['name']; ?></td>
            <td><?php echo $data['District']['name']; ?></td>
            <td>
            <?php if(@$file_type != 'pdf') { ?>
                <?php
                $details = "<table class='table-responsive'>";
                $details .= "<tr><td><b>Date of Birth :</b> </td><td>".date("d-m-Y",strtotime($data['PrisonerChildDetail']['dob']))."</td></tr>";
                $details .= "<tr><td><b>District Of Birth :</b> </td><td>".$data['District']['name']."</td></tr>";
                $details .= "<tr><td><b>Child Medical Condition :</b> </td><td>".$data['PrisonerChildDetail']['medical_cond']."</td></tr>";
                $details .= "<tr><td><b>Child Physical Condition :</b> </td><td>".$data['PrisonerChildDetail']['physical_cond']."</td></tr>";
                if(isset($data["PrisonerChildDetail"]["child_medical_document"]) && $data["PrisonerChildDetail"]["child_medical_document"]!=''){
                    $details .= "<tr><td><b>Child Medical Record :</b> </td><td><a href='../files/childs/medical_document/".$data["PrisonerChildDetail"]["child_medical_document"]."' target='_blank' class='btn btn-success btn-sm' >View</a></td></tr>";
                }
                if(isset($data["PrisonerChildDetail"]["child_photo"]) && $data["PrisonerChildDetail"]["child_photo"]!=''){
                    $details .= "<tr><td><b>Child Photo :</b> </td><td><img src='../files/childs/photo/".$data["PrisonerChildDetail"]["child_photo"]."' width='100' ></td></tr>";
                }
                if(isset($data['PrisonerChildDetail']['name_of_rcv_person']) && $data['PrisonerChildDetail']['name_of_rcv_person']!=''){
                    $details .= "<tr><td colspan='2'><b><h4>Discharge Information</h4></b></td></tr>";
                    $details .= "<tr><td><b>Receive Person :</b> </td><td>".$data['PrisonerChildDetail']['name_of_rcv_person']."</td></tr>";
                    $details .= "<tr><td><b>Contact No :</b> </td><td>".$data['PrisonerChildDetail']['contact_no_of_rcv_person']."</td></tr>";
                    $details .= "<tr><td><b>Date Of Handover :</b> </td><td>".date("d-m-Y", strtotime($data['PrisonerChildDetail']['date_of_handover']))."</td></tr>";
                    if(isset($data['PrisonerChildDetail']['probation_report']) && $data['PrisonerChildDetail']['probation_report']!=''){
                        $details .= "<tr><td><b>Probation Report :</b> </td><td><a href='../files/childs/medical_document/".$data['PrisonerChildDetail']['probation_report']."' class='btn btn-success btn -sm' target='_blank'>View</a></td></tr>";
                    }
                    $details .= "<tr><td><b>Handover Comment :</b> </td><td>".$data['PrisonerChildDetail']['handover_comment']."</td></tr>";
                }                
                $details .= "</table>";
                ?>
                <a href="javaScript:void(0);" class="pop btn btn-mini btn-success" pageTitle="View Details" pageBody="<?php echo $details; ?>">View Details</a>
                <?php } ?>
            </td>
            <td>
            <?php if($data["PrisonerChildDetail"]['status'] == 'Draft')
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
echo $this->Form->end();
}else{
?>
<?php echo Configure::read('NO-RECORD');?>
<?php    
}
?>    
<?php if(@$file_type != 'pdf') { ?>
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
var btnName = '<?php echo $btnName;?>';
var isModal = '<?php echo $isModal;?>';
function ShowConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName+"?",
            btnName,
            'Cancel',
            MyYesFunction,
            MyNoFunction
        );
}

function MyYesFunction() {
  if(isModal == 1)
    {
      $('#verify').modal('show');
    }
    else 
    {
      $('#ApprovalProcessFormChildDetailListAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
</script>
<?php } ?>