<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){   

  //get prisoner approval status 
  $prisoner_status = $funcall->getName($datas[0]['PrisonerPetition']['prisoner_id'],'Prisoner', 'status');

//Approval process start

$modelName = 'PrisonerPetition';
$btnName2 = Configure::read('SAVE');
$isModal2 = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName2 = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName2 = Configure::read('REVIEW');
  $isModal2 = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName2 = Configure::read('APPROVE');
  $isModal2 = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$datas[0]['PrisonerPetition']['puuid'].'#petition_tab'));
echo $this->Form->input('data_type',array('type'=>'hidden','value'=> 'petition_tab'));
?>
<?php if($isModal2 == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){?>

<button type="button" onclick="ShowPetitionConfirmYesNo();" tabcls="next" id="PetitionforwardBtn" style="display:none;" class="btn btn-success btn-mini"><?php echo $btnName2;?></button>
<?php
}
//Approval process start
?> 
<style type="text/css">
  #btnYesConfirmYesNo, #btnNoConfirmYesNo{display: inline-block !important;}
</style>
 <div style="overflow-x:scroll;">
            <!-- Modal -->
              <div id="myPetitionModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Petetion Result</h4>
                    </div>
                    <div class="modal-body">
                      <table class="table">
                        <div class="modal-body" id="show_details">
                       <?php  //echo $this->Form->create('Petetionre',array('class'=>'form-horizontal','url'=>'petetionResult', 'enctype'=>'multipart/form-data')); ?>
                                 <?php //echo $this->Form->end(); ?>

                    <?php  //echo $this->Form->create('Petetionresultnew',array('class'=>'form-horizontal','url'=>'petetionResult', 'enctype'=>'multipart/form-data')); ?>
                            <?php echo $this->Form->input('petition_id',array('type'=>'hidden', 'id'=>'petition_id')); ?>

                              <div class="span12">
                                    <div class="control-group ">

                                          <label class="control-label" style="margin-right: 10px">Petetion Result<?php echo $req; ?> </label>
                                          <div class="controls uradioBtn">
                                          <?php 
                                          $petetionresult = array('Discharge'=>'Discharge','Commutation of Sentence'=>'Commutation of Sentence');
                                          $button = "Discharge";
                                          $options2= $petetionresult;
                                          $attributes2 = array(
                                          'legend' => false, 
                                          'value' => $button,
                                          );
                                          echo $this->Form->radio('petition_result', $options2, $attributes2);
                                          ?>

                                          </div>
                                    </div>
                                </div> 
                       
                                <div class="form-actions " align="center" style="background:#fff;">
                                  <?php echo $this->Form->button('Save',array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success on', 'onclick'=>"javascript:savePetitionResult($('#petition_id').val());"))?>
                                </div>
                                 <?php //echo $this->Form->end(); ?>


                          </div> 
                      </table>
                
                   
                  </div>
                         
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>

<table class="table table-bordered data-table table-responsive">
    <thead>
            <?php 
           if(!isset($is_excel) && $prisoner_status == 'Approved'){
          ?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'PetitionCheckAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
          <?php }?>
            <th>SL#</th>
            <th>Petition Name</th>
            <th>Date of Petition</th>
            <th>Court Level</th>
            <th>Court Name</th>
            <th>Case File no</th>
            <th>High Court File No</th>
            <th>Offence</th>
            <th>Status</th>
            <th>Petetion Result</th>
<?php
if(!isset($is_excel)){
?> 
           <!--  <th>Action</th> -->
<?php
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));

    foreach($datas as $data){

        $id = $data['PrisonerPetition']['id'];
        $puuid = $data['PrisonerPetition']['puuid'];
        $offenceNames = $funcall->getPrisonerOffenceData($data['PrisonerPetition']['prisoner_id']);
?>
        <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
             <?php 
           if(!isset($is_excel) && $prisoner_status == 'Approved'){
          ?>
            <td>
              <?php 
              if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data[$modelName]['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data[$modelName]['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              ?>
            </td>
          <?php }?>
            <td><?php echo $rowCnt; ?></td>
            <td>
                <?php 
                echo $data['PrisonerPetition']['petition_name'];?>
                      <div id="pf98">
                                        <?php echo $this->Html->link('PF-98',array('controller'=>'ExtractPrisonersRecord','action'=>'add/'.$prisoner_id."/".$data['PrisonerPetition']['id']),array('escape'=>false,'class'=>'btn btn-success btn-mini', 'target'=>'_blank')); ?>
                      </div> 
            </td>
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerPetition']['petition_date'])); ?></td>
            <td>
                <?php 
                echo $data['PrisonerPetition']['courtlevel_id']? $data['PrisonerPetition']['courtlevel_id']: Configure::read('NA');
                ?>
            </td>
            <td>
                <?php 
                echo $data['PrisonerPetition']['court_id']? $data['PrisonerPetition']['court_id']: Configure::read('NA');
                ?>
            </td>
            <td>
            <?php 
           
            echo $funcall->getPrisonerFileData($data['PrisonerPetition']['prisoner_id']);
                ?>
            </td>
            <td>
                <?php 
                $highCourtfileNo = $funcall->getPrisonerHighCourtFileNo($data['PrisonerPetition']['prisoner_id']);
                  if ($highCourtfileNo!='') {
                  echo $highCourtfileNo;
                }else{ echo Configure::read('NA');}
               
                 ?>
            </td>
            <td>
                <?php 
                if ($offenceNames!='') {
                  echo $offenceNames;
                }else{ echo Configure::read('NA');}
               
                //echo $$offenceNames; ?>

            </td>
            <td>
            <?php if($data[$modelName]['status'] == 'Draft')
            {
              echo $data[$modelName]['status'];
            }
            else 
            {
              $status_info = '<b>Status: </b>'.$data[$modelName]['status'].'<br>';
              if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
                $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
              if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
                $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $data[$modelName]['status'];?></a>
              <?php 
            }?>
          </td>
          <td>
          <?php if($data['PrisonerPetition']['petition_result'] != '')
          {
            echo $data['PrisonerPetition']['petition_result'].'<br>';
            echo date('d-m-Y', strtotime($data['PrisonerPetition']['petition_result_date']));
          }
          else 
          {
            if($data['PrisonerPetition']['status'] == 'Approved' && $this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){?>
                <!-- Trigger the modal with a button -->
                <button type="button" class="btn btn-info mypetitionmodalopen" onclick="javascript:openPetitionResultModal('<?php echo $id;?>');">Set Result</button>
          <?php }
          }?>
          </td>
<?php
        if(!isset($is_excel)){
?>         
  <?php
}
  ?>
        </tr>
<?php
        $rowCnt++;
    }
?>
    </tbody>
</table>



               
<?php

//pagination start 
if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#petition_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisoners',
            'action'                => 'petitionAjax',
            'prisoner_id'             => $prisoner_id

        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
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
    $exUrl = "petitionAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    </div>
</div>
<?php
    }
    echo $this->Form->end();
    echo $this->Js->writeBuffer();
//pagination end 
?>
  <script type="text/javascript">
$(function(){
$("#ApprovalProcessFormPetitionAjaxForm").validate({
     
      ignore: ".ignore, .select2-input",
            rules: {  
                'data[ApprovalProcessForm][type]': {
                    required: true,
                },
                'data[ApprovalProcessForm][remark]': {
                    maxlength: 146,
                },
            },
            messages: {
                'data[ApprovalProcessForm][type]': {
                    required: "Please choose verification type.",
                },
            },
               
    });
});
function openPetitionResultModal(id)
{
  $('#myPetitionModal').modal('show');
  $('#petition_id').val(id);
}
$(document).ready(function(){
    
    
  $('#verifyBtn').click(function(){
        if($("#ApprovalProcessFormPetitionAjaxForm").valid()){
            if( !confirm('Are you sure to save?')) {
                            return false;
            }
        }
    });
        $("#PetitionCheckAll").click(function(){
            $('#ApprovalProcessFormPetitionAjaxForm input:checkbox').not(this).prop('checked', this.checked);
        });

  $('#ApprovalProcessFormPetitionAjaxForm input[type="checkbox"]').click(function(){
    
    var atLeastOneIsChecked = $('#ApprovalProcessFormPetitionAjaxForm input[type="checkbox"]:checked').length;
    var is_checkall2 = $('#ApprovalProcessFormPetitionAjaxForm input[id="PetitionCheckAll"]:checked').length;
    if(is_checkall2 == 1 && atLeastOneIsChecked == 1)
    { 
      $('#PetitionCheckAll').attr('checked', false);
      $('#PetitionforwardBtn').hide();
    }
    else if(atLeastOneIsChecked >= 1)
    {
      $('#PetitionforwardBtn').show();
    }
    else 
    {
      $('#PetitionforwardBtn').hide();
    }
  });
});
//Dynamic confirmation modal -- START --
var btnName2 = '<?php echo $btnName2;?>';
var isModal2 = '<?php echo $isModal2;?>';

function openMyModal(){

  
}
function ShowPetitionConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName2+"?",
            btnName2,
            'Cancel',
            PetitionMyYesFunction,
            PetitionMyNoFunction
        );
}

function PetitionMyYesFunction() {
  if(isModal2 == 1)
  {
    $('.verifyPopupModal').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormPetitionAjaxForm').submit();
  }
}
function PetitionMyNoFunction() {
    
}
//Dynamic confirmation modal -- END --
</script> 
<?php 
}else{
echo Configure::read('NO-RECORD');  
}
?>                    
