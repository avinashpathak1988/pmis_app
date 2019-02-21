<?php
 if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
    
?>
<style type="text/css">
  .btn{
    margin-bottom: 10px !important;
  }
</style>
 <div class="span5" style="margin-left:0px">
        <ul class="pagination" style="margin-left:5px">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#dischargeSummaryList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'DischargeBoardSummary',
                                                    'action'                => 'dischargeSummaryAjax',
                                                    
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "dischargeSummaryAjax";
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
  }
?>

    </div>
<div class="widget-box">
                                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                                     <h5> Discharge Board summary list</h5>
                                          <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                         </div>
                                </div>
                            </div>
<?php 
//Approval process start
$btnName = Configure::read('SAVE');
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/DischargeBoardSummary'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button>
<?php
}
//Approval process start
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        }
          ?>                       
<table class="table table-bordered data-table table-responsive formal-edu" id="cashidtbl">
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
          <?php }?>
            <th>Sr No.</th>
            <th>Prison</th>
            <th>Name</th>
            <th>Prisoner Number</th>

            <th>Superintendent</th>
            <th>Former Employment</th>
            <th>Addess on Discharge</th>
            <th>Wishes</th>
            
            <th>Vocational training</th>
            <th>General Remarks</th>
            <th>Cash Amount</th>
            <th>Date of Discharge</th>
            <!-- <th>Licence Expires</th> -->
            <th>Filled Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>



    </thead>

    <tbody>
        <?php 
        $count =1;
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
        foreach($datas as $data){
          $credit_id =$data['DischargeBoardSummary']['id'];

        ?> 
        <tr>
          <?php 
           if(!isset($is_excel)){
          ?>
            <td>
              <?php 
              if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE') && ($data[$modelName]['status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data[$modelName]['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data[$modelName]['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              ?>
            </td>
          <?php }?>
            <td><?php echo $count; ?></td>
            <td><?php echo $data['DischargeBoardSummary']['prison']; ?></td>
            <td><?php echo $data['DischargeBoardSummary']['name']; ?></td>
            <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>

            <td><?php echo $data['DischargeBoardSummary']['superintendent']; ?></td>
            <td><?php echo $data['DischargeBoardSummary']['former_employment']; ?></td>
            <td><?php echo $data['DischargeBoardSummary']['address_on_discharge']; ?></td>
            <td><?php echo $data['DischargeBoardSummary']['wishes']; ?></td>
            <!-- <td><?php echo $data['DischargeBoardSummary']['offer_of_help']; ?></td> -->
            <td><?php echo $data['DischargeBoardSummary']['vocational_training']; ?></td>
            <td><?php echo $data['DischargeBoardSummary']['general_remarks']; ?></td>
            <td><?php echo $data['DischargeBoardSummary']['cash_amount']; ?></td>
            <td><?php echo $data['DischargeBoardSummary']['earliest_date_of_discharge']; ?></td>
            <!-- <td><?php echo $data['DischargeBoardSummary']['licence_expires']; ?></td> -->
            <td><?php echo $data['DischargeBoardSummary']['filled_date']; ?></td>
            <td>
            <?php  if($data[$modelName]['status'] == 'Draft')
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
              <a href="javaScript:void(0);" class="btn btn-mini btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $data[$modelName]['status'];?></a>
              <?php 
            }?>
          </td>
            <td>
               <?php echo $this->Form->create('giyghgjh',array('url'=>'/DischargeBoardSummary/addDischargeSummary','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?>
              <?php 
                    echo $this->Form->end();
                    ?> 
              <?php  if($data[$modelName]['status'] == 'Draft')
            { ?>
              <?php echo $this->Form->create('DischargeBoardSummaryEdit',array('url'=>'/DischargeBoardSummary/addDischargeSummary','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['DischargeBoardSummary']['id']));
                    ?>
                    <button class="btn btn-success" type="submit" value="Edit" onclick="javascript:return confirm('Are you sure want to edit?')"><i class="icon icon-edit"></i></button>
                    <?php 
                    echo $this->Form->end();
                    ?> 

                   <?php echo $this->Form->create('DischargeBoardSummaryDelete',array('url'=>'/DischargeBoardSummary/index','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['DischargeBoardSummary']['id'])); ?>
                    <button class="btn btn-danger" type="submit" value="Delete" onclick="javascript:return confirm('Are you sure want to delete?')"><i class="icon icon-trash"></i></button>
                    <?php 
                    echo $this->Form->end();

                  }else{ ?>
                      <?php echo $this->Form->create('DischargeBoardSummaryView',array('url'=>'/DischargeBoardSummary/viewDischargeSummary','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['DischargeBoardSummary']['id']));
                    
                    ?>
                    <button class="btn btn-mini btn-success" type="submit" value="view" >View</button>
                    <?php 
                    echo $this->Form->end();
                    ?> 
                 <?php }
                     ?>
                     
                  </td>
            
        </tr>
        <?php 
         $count ++;
    } ?>
    </tbody>
</table> 
<?php
echo $this->Form->end(); ?>
<?php } ?>


<script type="text/javascript">
$(document).ready(function(){
  
  $('#verifyBtn').click(function(){
        if($("#ApprovalProcessFormDischargeSummaryAjaxForm").valid()){
            if( !confirm('Are you sure to save?')) {
                            return false;
            }
        }
    });
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
    $('#ApprovalProcessFormDischargeSummaryAjaxForm').submit();
  }
}
function MyNoFunction() {
    
}
</script>