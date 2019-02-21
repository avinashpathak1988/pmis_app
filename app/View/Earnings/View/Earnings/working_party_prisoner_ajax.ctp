<style>
/* The container */
.Check {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    /*font-size: 22px;*/
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    float: left;
}

/* Hide the browser's default checkbox */
.Check input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom checkbox */
.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 22px;
    width: 30px;
    background-color: green;
}

/* On mouse-over, add a grey background color */
.Check:hover input ~ .checkmark {
    /*background-color: #ccc;*/
}

/* When the checkbox is checked, add a blue background */
.Check input:checked ~ .checkmark {
    background-color: red;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.Check input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.Check .checkmark:after {
    left: 12px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
.bookInfo{
  color: #666;
  border: 1px solid #ddd;
  position: relative;
  margin-left: -5px;
  padding: 0px 10px;
  margin-right: 10px;
  font-size: 13px;
  height: 22px;
  font-weight: 600;
}
</style>
<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#workingpartyprisoner_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Earnings',
            'action'                => 'workingPartyPrisonerAjax'

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
    $exUrl = "workingPartyPrisonerAjax";
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
?>   
<?php 
$btnName = 'Save';
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName = 'Save';
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName = 'Review';
  $isModal = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = 'Approve';
  $isModal = 1;
}
$methodName = 'assignPrionsers';
$modelName = 'WorkingPartyPrisoner';
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Earnings/'.$methodName));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" tabcls="next" onclick="ShowConfirmYesNo();" id="forwardBtn" class="btn btn-success btn-mini" ><?php echo $btnName;?></button>                     
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>

            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
            <th>SL#</th>
            <th>Working Party</th>
            <th>Date Of Assign</th>
            <th>Prisoner No</th>
            <th>Start Date</th>
            <th>End Date</th>
            
            <th>Approve Status</th>
            <th>Prisoner Approval</th>
<?php
if(!isset($is_excel)){
?> 
            <th>Actions</th>
<?php
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));

    foreach($datas as $data){
      //echo '<pre>'; print_r($data); 
      $id = $data['WorkingPartyPrisoner']['id'];
      
      $uuid = $data['WorkingPartyPrisoner']['uuid'];
      $display_status = Configure::read($data[$modelName]['status']);
      $assigned_prisoners_count = 0;
      $assigned_prisoners = $funcall->getPrisonerNos($data['WorkingPartyPrisoner']['prisoner_id']);
      $assigned_prisoners_ids = $funcall->getPrisonerIDs($data['WorkingPartyPrisoner']['prisoner_id']);
      $approve_prisoner = explode(',',$assigned_prisoners_ids);
      //debug($approve_prisoner);
      if(!empty($assigned_prisoners))
      {
        $assigned_prisoners_array = explode(',',$data['WorkingPartyPrisoner']['prisoner_id']);
        $assigned_prisoners_count = count($assigned_prisoners_array);
        //echo $assigned_prisoners_count;
        $isWorkingPartyTransfer = $funcall->isWorkingPartyTransfer($id, $assigned_prisoners_count);
        //exit;
        ?>
        <tr>
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
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data['WorkingParty']['name']; ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['WorkingPartyPrisoner']['assignment_date'])); ?></td>
            <td>
              <?php echo $assigned_prisoners;?>
            </td>
            <td><?php echo date('d-m-Y', strtotime($data['WorkingPartyPrisoner']['start_date'])); ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['WorkingPartyPrisoner']['end_date'])); ?></td>
            
            <td>
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
              <a href="javaScript:void(0);" class="pop btn-mini btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $display_status;?></a>
              <?php  
            }?>
          </td>
          <td>
          <?php
        if(!isset($is_excel) && (($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && $data['WorkingPartyPrisoner']['status']!='Reviewed') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $data['WorkingPartyPrisoner']['status']!='Approved'))){
?>

<button type="button" class="btn btn-mini btn-info" data-toggle="modal" data-target="#myModal_<?php echo $data['WorkingPartyPrisoner']['id']?>">Prisoner approval status</button>
              <div id="myModal_<?php echo $data['WorkingPartyPrisoner']['id']?>" class="modal fade verifyPopupModal" role="dialog">
                    <div class="modal-dialog">
                    
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                                <h4 class="modal-title">Prisoner approval status</h4>
                            </div>
                            <div class="modal-body">
                                <div class="" style="padding-bottom: 14px;">
                                    <?php 
                                        foreach ($approve_prisoner as $key => $value) {
                                            $prisoner_no_working=$funcall->getName($value,'Prisoner','prisoner_no');
                                          ?>
                                          <div>
                                          <label style="background-color: #e4f0e6;"><?php echo $prisoner_no_working.'<br>'?></label>
                                                        <?php  $options = array(
                                                          '1' => 'Approve',
                                                          '2' => 'Reject'
                                                          );

                                                          $attributes = array(
                                                          'legend' => false,
                                                          'required'=>'required',
                                                          'default'=>'No',
                                                          'label'=>false,
                                                          'default'=>'1'
                                                          );

                                                          echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.WorkingPartyPrisonerApprove.'.$key.'.prisoner_no',array('type'=>'hidden','value'=> $prisoner_no_working));
                                                          echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.WorkingPartyPrisonerApprove.'.$key.'.prisoner_id',array('type'=>'hidden','value'=> $value));

                                                          echo $this->Form->radio('ApprovalProcess.'.$rowCnt.'.WorkingPartyPrisonerApprove.'.$key.'.is_approve', $options, $attributes).'<br>';
                                                          ?>
                                            </div>
                                                      
                                       <?php }?>
                                </div>
                                <div class="form-actions" align="center" style="background:#fff;">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
          <?php }?>
          </td>
          <?php
        if(!isset($is_excel) && ($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))){
?>              
            
            <td>
              <?php if(in_array($data[$modelName]['status'], array('Draft','Review-Rejected','Approve-Rejected')))
              {?>
                <?php 
                $editFormID = "'ApprovalProcessFormWorkingPartyPrisonerAjaxForm'";
                echo $this->Form->create('workingPartyPrisonerEdit',array('url'=>'/earnings/assignPrionsers','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
                ?>
                <?php echo $this->Form->button("<i class='icon-edit'></i>",array('label'=>false,'type'=>'button','class'=>'btn btn-primary','div'=>false, 'onclick'=>"editForm(".$editFormID.");")); 
                echo $this->Form->end();?> 
               <?php echo $this->Form->button("<i class='icon-remove'></i>", array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteworkingPartyPrisoner('$id');"))?>
              <?php }
              else if($data[$modelName]['status'] == 'Approved')
                {
                  if($data['WorkingParty']['open_status'] == 1 && $data['WorkingPartyPrisoner']['end_date'] >= date('Y-m-d') && $isWorkingPartyTransfer == 1){
                    $j=0;
                    $prisonerID = explode(',', $data['WorkingPartyPrisoner']['prisoner_id']);
                    $prisoner_count = count($prisonerID);
                    foreach ($prisonerID as $key => $value) {
                      //echo $value;
                      $prisoner = $funcall->getWorkingPartyList($value);
                      
                      if($prisoner == 0){
                        $j++;
                      }

                    }
                      echo $this->Html->link('Transfer','/earnings/transfer/'.$uuid,array('escape'=>false,'class'=>'btn btn-success btn-mini'));
                    if($j > 0){
                      echo $this->Html->link('Reject','/earnings/reject/'.$uuid,array('escape'=>false,'class'=>'btn btn-danger btn-mini'));
                    }
                    
                  }
                    
                }?>
            </td>
  <?php
}
  ?>

        </tr>
<?php }
        $rowCnt++;
    }
?>
    </tbody>
</table>
<?php 
echo $this->Form->end();
echo $this->Js->writeBuffer();
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
////Edit 
function editForm(formID) {
    AsyncConfirmYesNo(
            "Are you sure want to edit?",
            'Edit',
            'Cancel',
            function()
            {
              $('#'+formID).submit();
            },
            function(){}
        );
}
var btnName = '<?php echo $btnName;?>';
var isModal = '<?php echo $isModal;?>';
function ShowConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName+"?",
            btnName,
            'Cancel',
            MyYesFunction2,
            MyNoFunction
        );
}

function MyYesFunction2() {
  if(isModal == 1)
    {
      $('#verify').modal('show');
    }
    else 
    {
      $('#ApprovalProcessFormWorkingPartyPrisonerAjaxForm').submit();
    }
}
function MyNoFunction() {
     
}
//Dynamic confirmation modal -- END --
</script>  
<?php } ?>
<?php 
}else{
?>
    ...
<?php    
}
?>   

