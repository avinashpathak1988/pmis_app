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
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'courtattendances',
            'action'                    => 'courtsscheduleListAjax',
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
    <div class="span7 text-right" style="padding-top:20px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
<?php
    $exUrl = "courtsscheduleListAjax/prisoner_id:$prisoner_id/status:$status";
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Courtattendances/courtscheduleList'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" style="margin:3px 1px;"><?php echo $btnName;?></button>
<!-- <button type="submit" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" <?php //if($isModal == 1){?> data-toggle="modal" data-target="#verify"<?php //}?>><?php //echo $btnName;?></button> -->
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
            <th>Sl no</th>                
            <th>Prisoner No</th>
            <th>Prisoner Name</th>
           <th>Case File No</th>
            <th>Court Level</th>
            <th>Court Name</th>
            <th>Offences</th>
          
           
            <th style="text-align: left;">
              Action
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
        <?php
        if(!isset($is_excel)){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['Courtattendance']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Courtattendance']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['Courtattendance']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Courtattendance']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['Courtattendance']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Courtattendance']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
            ?>
          </td>
          <?php
          }
          ?>
            <td><?php echo $rowCnt; ?>&nbsp;</td>          
            <td><?php if($data['Prisoner']['prisoner_no']!='')echo ucwords(h($data['Prisoner']['prisoner_no']));else echo Configure::read('NA'); ?>&nbsp;</td>  
            <td><?php echo $data['Prisoner']['first_name'].' '.$data['Prisoner']['middle_name'].' '.$data['Prisoner']['last_name']; ?></td>
            <td><?php echo $funcall->getMultivalue($data['Courtattendance']['case_no'],"PrisonerCaseFile","case_file_no"); ?>&nbsp;</td> 
            <td><?php echo $funcall->getName($data['Courtattendance']['court_level'],"Courtlevel","name"); ?>&nbsp;</td>
            <td><?php echo $funcall->getName($data['Courtattendance']['court_id'],"Court","name"); ?>&nbsp;</td> 
            
            <td><?php echo $funcall->getMultivalue($data['Courtattendance']['offence_id'],"Offence","name");?>&nbsp;</td>
            
            <td>
            <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModal<?php echo $data['Courtattendance']['id']; ?>">View</button>
			
			 <!-- Modal -->
            <div id="myModal<?php echo $data['Courtattendance']['id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">View Details</h4>
                  </div>
				
                  <div class="modal-body">
				    <table class="table table-bordered data-table table-responsive">
                        <tbody>
                            <tr>
                                <td><b>CRB No.</b></td>
                                <td><?php echo $data['Courtattendance']['crb_no'];?></td> 
                            </tr>
                            <tr>
                                <td><b>Court Level</b></td>
                                <td><?php echo $funcall->getName($data['Courtattendance']['court_level'],"Courtlevel","name");?></td>
                            </tr>
                            <tr>
                                <td><b>Court Name</b></td>
                                <td><?php echo $funcall->getName($data['Courtattendance']['court_id'],"Court","name");?></td>
                            </tr>
							 <tr>
                                <td><b>Court File No</b></td>
                                <td><?php echo $data['Courtattendance']['court_file_no'];?></td>
                            </tr>
							 <tr>
                                <td><b>High Court File No</b></td>
                                <td><?php echo $data['Courtattendance']['high_court_file_no'];?></td>
                            </tr>
							<tr>
                                <td><b>Date of Court</b></td>
                                <td><?php echo date('d-m-Y',strtotime($data['Courtattendance']['court_date']));?></td>
                            </tr>
							 <tr>
                                <td><b>File No</b></td>
                                <td><?php echo $funcall->getMultivalue($data['Courtattendance']['case_no'],"PrisonerCaseFile","case_file_no");?></td>
                            </tr>
							 <tr>
                                <td><b>Offence</b></td>
                                <td><?php echo $funcall->getMultivalue($data['Courtattendance']['offence_id'],"Offence","name");?></td>
                            </tr>
							 <tr>
                                <td><b>Count</b></td>
                                <td><?php echo $data['Courtattendance']['offence_count'];?></td>
                            </tr>
							 
							 <tr>
                                <td><b>Presiding Judge</b></td>
                                <td><?php echo $data['Courtattendance']['presiding_judge'];?></td>
                            </tr>
							 <tr>
                                <td><b>Reason For Court</b></td>
                                <td><?php echo $data['Courtattendance']['reason'];?></td>
                            </tr>
                        </tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
				 
                </div>

              </div>
            </div>
			<!-- modal end-->	
          </td>
            <?php if($isAccess == 1){?>
                <!-- <td class="actions"> -->
                <?php //echo $this->Form->create('CourtattendanceEdit',array('url'=>'/courtattendances/index/'.$uuid,'admin'=>false));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Courtattendance']['id'])); ?>
                <?php //echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                <!-- </td> -->
                <!-- <td> -->
                <?php //echo $this->Form->create('CourtattendanceDelete',array('url'=>'/courtattendances/index/'.$uuid,'admin'=>false));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Courtattendance']['id'])); ?>
                <?php //echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
                <!-- </td> -->
            <?php }?>
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
echo Configure::read("NO-RECORD"); 
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
//Dynamic confirmation modal -- START --
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
    $('#ApprovalProcessFormCourtsscheduleListAjaxForm').submit();
  }
}
function MyNoFunction() {
    
}
//Dynamic confirmation modal -- END --
</script>
<?php } ?>