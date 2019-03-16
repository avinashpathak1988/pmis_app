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
            'controller'                => 'Discharges',
            'action'                    => 'gatepassListAjax',
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
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "gatepassListAjax/prisoner_id:$prisoner_id/status:$status";
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
$btnName = "Generate Gatepass";
$isModal = 1;
// if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
// {
//   $btnName = Configure::read('SAVE');
// }
// else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
// {
//   $btnName = Configure::read('REVIEW');
//   $isModal = 1;
// }
// else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
// {
//   $btnName = Configure::read('APPROVE');
//   $isModal = 1;
// }
echo $this->Form->create('Gatepass',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/discharges/gatepassList'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('gatepass-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" style="margin:3px 1px;"><?php echo $btnName;?></button> 
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
            <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
            <th><?php echo $this->Paginator->sort('Prisoner_no'); ?></th>
            <th><?php echo $this->Paginator->sort('Prisoner Name'); ?></th>
            <th><?php echo $this->Paginator->sort('Discharge date'); ?></th>
            <th><?php echo $this->Paginator->sort('Discharge Type'); ?></th>
            <th><?php echo $this->Paginator->sort('Supported Docs'); ?></th>
            <th><?php echo $this->Paginator->sort('View Details'); ?></th>
            <th style="text-align: left;">Gatepass Details</th>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = Configure::read($data['Discharge']['status']);
  $prisonerDetails = $funcall->getPrisonerDetails($data['Discharge']['prisoner_id']);
?>
        <tr>
        <?php
        if(!isset($is_excel)){
          ?>
            <td>
            <?php      
            if(count($data['Gatepass'])==0){
              echo $this->Form->input('Gatepass.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Discharge']['id'],'hiddenField' => false, 'label'=>false,
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
            <td><?php echo ucwords(h(date('d-m-Y', strtotime($data['Discharge']['discharge_date'])))); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['DischargeType']['name'])); ?>&nbsp;</td>
            
            <td>
                <?php echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary btn-mini'))?>
            </td>
            <td>
             <?php if(@$file_type != 'pdf') { ?>
               <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModal<?php echo $data['Discharge']['id']; ?>" onclick="showDetails(<?php echo $data['Discharge']['discharge_type_id']; ?>,<?php echo $data['Discharge']['id']; ?>,<?php echo $data['Discharge']['prisoner_id']; ?>)">View Details</button>

            <!-- Modal -->
            <div id="myModal<?php echo $data['Discharge']['id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Details</h4>
                  </div>
                  <div class="modal-body" id="show_details<?php echo $data['Discharge']['id']; ?>">
                    <p><?php echo $data['Discharge']['id']; ?></p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
            <?php } ?>
            </td>
            <td>
            <?php
            if(isset($data['Gatepass'][0]) && is_array($data['Gatepass'][0]) && count($data['Gatepass'][0])>0){
              
              $status_info = '<b>Gatepass No. : </b>'.$data['Gatepass'][0]['gp_no'].'<br>';
              $status_info .= '<b>Gatepass Date : </b>'.date("d-m-Y", strtotime($data['Gatepass'][0]['gp_date'])).'<br>';
              $status_info .= '<b>Escort : </b>'.$funcall->getName($data['Gatepass'][0]['escort_team'],"User","name").'<br>';
              $status_info .= '<b>Permission Granted for : </b>'.$data['Gatepass'][0]['permission_granted'].'<br>';
              $status_info .= '<b>Purpose : </b>'.$data['Gatepass'][0]['purpose'].'<br>';
            
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Gatepass Info" pageBody="<?php echo $status_info;?>">Gatepass Details</a>
              <?php
            }
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
...
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
      $('#ApprovalProcessFormDischargeListAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
</script>
<?php } ?>