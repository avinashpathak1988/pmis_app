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
if(is_array($datasgatepass) && count($datasgatepass)>0){
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
              'controller'                => 'Reportreviews',
              'action'                    => 'getBookAjax',
        )+$searchData
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
   
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = $this->Html->url(array('controller'=>'Reportreviews','action'=>'getBookAjax')+$searchData,true);;
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
  $btnName = "Verify";
  $isModal = 0;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Gatepasses/gatepassList'));?>
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
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
        if(!isset($is_excel)){
          ?>
        <th>
            <?php 
            echo $this->Form->input('checkAll', array(
                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
            ));
            ?>
          </th>
          <?php
        }
    }
          ?>
            <th><?php echo $this->Paginator->sort('Sl no'); ?></th>  
            <th><?php echo $this->Paginator->sort('Prisoner_no'); ?></th>
            <th>
                <?php 
                echo $this->Paginator->sort('Prisoner.fullname',"Prisoner Name",array(
                    'update'                        => '#listingDiv',
                    'evalScripts'                   => true,
                        'url'                       => array(
                            'controller'                => 'Gatepasses',
                            'action'                    => 'gatepassListAjax',
                        )+$searchData
                )); 
                ?>    
            </th>
            <th><?php echo $this->Paginator->sort('Escort'); ?></th>
            <th><?php echo $this->Paginator->sort('Destination'); ?></th>              
            <th>
                <?php 
                echo $this->Paginator->sort('gp_no',"Gate Pass No.",array(
                    'update'                        => '#listingDiv',
                    'evalScripts'                   => true,
                        'url'                       => array(
                            'controller'                => 'Gatepasses',
                            'action'                    => 'gatepassListAjax',
                        )+$searchData
                )); 
                ?>
            </th>                
            <th><?php echo $this->Paginator->sort('Remarks'); ?></th>
            <th><?php echo $this->Paginator->sort('Out Time'); ?></th>
            <th><?php echo $this->Paginator->sort('In Time'); ?></th>
            
            
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datasgatepass as $data){
  $display_status = Configure::read($data['Gatepass']['status']);
  $prisonerDetails = $funcall->getPrisonerDetails($data['Gatepass']['prisoner_id']);
  //debug($data['Gatepass']);
?>
        <tr>
        <?php
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
            if(!isset($is_excel)){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['Gatepass']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Gatepass']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['Gatepass']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Gatepass']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['Gatepass']['status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Gatepass']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
            ?>
          </td>
          <?php
            }
          }
          ?>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $prisonerDetails["Prisoner"]["prisoner_no"]?> </td>
            <td><?php echo $prisonerDetails["Prisoner"]["first_name"]." ".$prisonerDetails["Prisoner"]["last_name"]?> </td> 
            <td><?php echo $data["Gatepass"]["escort_team"]?> </td> 
            <td><?php echo $prisonerDetails["Prisoner"]["first_name"]." ".$prisonerDetails["Prisoner"]["last_name"]?> </td> 
            <td><?php echo ucwords(h($data['Gatepass']['gp_no'])); ?>&nbsp;</td>            
            <td><?php echo "Remarks"; ?>&nbsp;</td>  
                  
            <td>
            <?php
             //echo date("Y-m-d H:i:s");
             //debug($this->Session->read('Auth.User.usertype_id')."--".Configure::read('GATEKEEPER_USERTYPE'));
            if(isset($data['Gatepass']['out_time']) && $data['Gatepass']['out_time']=='0000-00-00 00:00:00' && $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')){
                if(!isset($is_excel)){
                  // debug(strtotime(date("Y-m-d 17:00:00")));
                  // debug(strtotime(date("Y-m-d H:i:s")));
                  // debug(strtotime(date("Y-m-d 08:00:00")));
                  // debug($data['Gatepass']['gatepass_type']);
                  // debug(strtotime(date("Y-m-d 17:00:00")) < strtotime(date("Y-m-d H:i:s")));
                  // debug(strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 08:00:00")));
                  if((in_array($data['Gatepass']['gatepass_type'],array('Court Attendance','Transfer')) && strtotime(date("Y-m-d 17:00:00")) < strtotime(date("Y-m-d H:i:s"))) || in_array($data['Gatepass']['gatepass_type'],array('Court Attendance','Transfer')) && strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 07:00:00"))){
                    
                  }else{
                    ?>
                    <span id="link_biometric_span_out<?php echo $data['Gatepass']['id']; ?>"></span>
                    <?php 
                    echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning btn-mini','id'=>'link_biometric_button_out'.$data['Gatepass']['id'],"onclick"=>"checkData(".$data['Gatepass']['prisoner_id'].",".$data['Gatepass']['id'].",'out')"));

                    ?>&nbsp;
                    <?php
                  }
                }
            }else{
                echo (isset($data['Gatepass']['out_time']) && $data['Gatepass']['out_time']=='0000-00-00 00:00:00') ? '' : h(date("d-m-Y h:i A",strtotime($data['Gatepass']['out_time'])));
            }
                ?>
            
            </td>
            <td>
            <?php
            if(isset($data['Gatepass']['in_time']) && $data['Gatepass']['in_time']=='0000-00-00 00:00:00' && $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')  && $data['Gatepass']['gatepass_status']=='out' && $data['Gatepass']['is_verify']==1 && !in_array($data['Gatepass']['gatepass_type'], array('Discharge','Transfer'))){
                if(!isset($is_excel)){

                    ?>
                    <span id="link_biometric_span_in<?php echo $data['Gatepass']['id']; ?>"></span>
                    <?php 
                    echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning btn-mini','id'=>'link_biometric_button_in'.$data['Gatepass']['id'],"onclick"=>"checkData(".$data['Gatepass']['prisoner_id'].",".$data['Gatepass']['id'].",'in')"));

                    ?>&nbsp;
                    <?php

                }
            }else{
                echo (isset($data['Gatepass']['in_time']) && $data['Gatepass']['in_time']=='0000-00-00 00:00:00') ? '' : h(date("d-m-Y h:i A",strtotime($data['Gatepass']['in_time'])));
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
echo Configure::read("NO-RECORD");    
}
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
      $('#ApprovalProcessFormGatepassListAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}

</script>
<?php } ?>
<?php
if(is_array($datasvisitor) && count($datasvisitor)>0){
 // debug($datas);
  if(!isset($is_excel)){
?>
<style type="text/css">
  .prisoner-item-show{
    padding-left: 20px;
  }
</style>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Reportreviews',
            'action'                => 'getBookAjax',
        )+$searchData
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
    $exUrl = $this->Html->url(array('controller'=>'Visitors','action'=>'indexAjax')+$searchData,true);
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
  $btnName = "Verify";
  $isModal = 0;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
{
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Visitors/index'));?>
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
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
          <?php
          if($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")){
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
    }
          ?>
          
            <th><?php echo 'Sl no'; ?></th>  
            <th><?php echo 'Visitor Name'; ?></th>                
            <th><?php echo 'Category'; ?></th>                
            <th><?php echo 'Date'; ?></th>

            <th><?php echo 'Reason'; ?></th>
            <th><?php echo 'Prison Name'; ?></th>
            <th><?php echo 'To whom you are meeting'; ?></th>
            <th><?php echo 'Gate keeper Name'; ?></th>
            <th><?php echo 'Time In'; ?></th>
            <th><?php echo 'Time Out'; ?></th>
            <th><?php echo 'Duration'; ?></th>
            <th><?php echo 'Main Gate Time In'; ?></th>
            <th><?php echo 'Main Gate Time Out'; ?></th>
            <th><?php echo 'Main Gate Duration'; ?></th>

       <?php if($allowUpdate == 1 || $this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')  ) {?>
            <th>Return visitor Items</th>
            <th>Visitor Gatepass and Receipt</th>
            <?php } ?>
            <?php  if($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')){ ?>
            <?php } ?>
            <?php
            if(!isset($is_excel)){
            ?>
            <th><?php echo 'View'; ?></th>
            <?php
            if($this->Session->read('Auth.User.usertype_id')!=Configure::read("OFFICERINCHARGE_USERTYPE")){
            ?>
            <th width="8%"><?php echo 'Gate Action'; ?></th>
            <?php
            }
            if($funcall->checkMainGatekeeperExits()){
                ?>
                <th width="8%"><?php echo 'Action'; ?></th>
                <?php
              }
            }
            if($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")){
            ?>
            <th><?php echo 'Status'; ?></th>

            <?php
            }
            ?>
            <?php

            if($allowUpdate == 0 || $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')  ){?>
                <th width="8%"><?php echo 'Prisoner Item/Cash Details'; ?></th>

              <?php } ?>
        </tr>
    </thead>
<tbody>

<?php
//debug($datas);
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datasvisitor as $data){
  //debug($data);
  $display_status = Configure::read($data['Visitor']['status']);
  // debug($data);
?>
    <tr>
      <?php
      if($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")){
        if(!isset($is_excel)){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $data["Visitor"]['verify_status'] == 'Draft')
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Visitor']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              
            ?>
          </td>
          <?php
          }
      }
          ?>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo $funcall->getVisitorName($data['Visitor']['id']); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Visitor']['category'])); ?>&nbsp;</td>
      <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['Visitor']['date'])); ?>&nbsp;</td> 
      
      <td><?php echo ucwords(h($data['Visitor']['reason'])); ?>&nbsp;</td>
      <td><?php //echo $data['Visitor']['prisoner_no'];
      echo $funcall->getName($data["Visitor"]["prison_id"],'Prison','name');  ?>&nbsp;</td>
      <td><?php echo ($data['Visitor']['category']=='Visiting Prisoner') ? $funcall->getName($data["Visitor"]["name"],'Prisoner','fullname')."<br>(".$data['Visitor']['prisoner_no'].")": $data['Visitor']['to_whom'];  ?>&nbsp;</td>
      
      <td><?php echo ucwords(h($data['Visitor']['gate_keeper'])); ?>&nbsp;</td>                 
      <td><?php echo $data['Visitor']['time_in']; ?>&nbsp;</td>
      <td>
      
        <?php echo $data['Visitor']['time_out']; ?>
      </td>
      <td><?php 
        if($data['Visitor']['duration'] != ''){
          $duration = $data['Visitor']['duration'];
          $durationArray = explode(':', $duration);
          echo $durationArray[0]." Hr ".":".$durationArray[1]." Min";
        }
       ?></td>
      <td><?php echo $data['Visitor']['main_gate_in_time']; ?></td>
      <td><?php echo $data['Visitor']['main_gate_out_time']; ?></td>
      
       <td><?php
       if($data['Visitor']['main_gate_duration'] != ''){
          $duration = $data['Visitor']['main_gate_duration'];
          $durationArray = explode(':', $duration);
          echo $durationArray[0]." Hr ".":".$durationArray[1]." Min";
        }
       ?></td>
       <?php if($allowUpdate == 1 || $this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')  ) {?>
       <td>
                  <button type="button" class="btn btn-success" data-toggle="modal" onclick="setReturnForm('<?php echo $data['Visitor']['id']; ?>');" data-target="#returnNow">
                  Return Now
                </button>
       </td>
       <td>
                 <?php
            echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),'receipt/'.$data['Visitor']['id'], array("escape" => false,'target'=>"")))
            ?>
       </td>
       <?php }?>
       <?php  if($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')){ ?>
      
       <?php } ?>
       <?php
            if(!isset($is_excel)){
            ?>
       <td class="actions">            
          <?php
          echo $this->Html->link('<i class="icon icon-eye-open" ></i>',array(
                'action'=>'../visitors/view',
                $data['Visitor']['id']
            ),array(
                'escape'=>false,
                'class'=>'btn btn-success btn-mini'
            ));
            ?>
          </td>
          <?php
            if($this->Session->read('Auth.User.usertype_id')!=Configure::read("OFFICERINCHARGE_USERTYPE")){
            ?>
          <td>
              <?php 
              // echo $data['Visitor']['status'];
              if($data['Visitor']['status']=='IN' && $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')){
                echo $this->Form->button('Timein', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning btn-mini', 'onclick'=>"javascript:newTimeOut(".$data['Visitor']['id'].",'IN');"));
              }
              if($data['Visitor']['status']=='Gate IN' && $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')){
                echo $this->Form->button('Timeout', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning btn-mini', 'onclick'=>"javascript:newTimeOut(".$data['Visitor']['id'].",'OUT');"));
              }
              if($data['Visitor']['status']=='Gate Out' && $this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                echo $this->Form->button('Timeout', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning btn-mini', 'onclick'=>"javascript:newTimeOut(".$data['Visitor']['id'].",'OUT');"));
              }
              
              ?>
              &nbsp;&nbsp;&nbsp;
              <?php
              if($data['Visitor']['status']=='IN' && $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')){
                echo $this->Form->button('Missing', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger btn-mini', 'onclick'=>"javascript:newAlert(".$data['Visitor']['id'].");"));
              }
              if($data['Visitor']['status']=='Gate Out' && $this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                echo $this->Form->button('Missing', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger btn-mini', 'onclick'=>"javascript:newAlert(".$data['Visitor']['id'].");"));
              }
              ?>
          </td>
           <?php echo $this->Form->end();?>
          
          <?php
            }
            if($funcall->checkMainGatekeeperExits()){
              ?>

         <td class="actions">
          <?php
              if($data['Visitor']['status']=='IN' && $this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
               ?>
          <?php echo $this->Form->create('VisitorEdit',array('url'=>'/visitors/add','id'=>'VisitorEdit_'.$data['Visitor']['id'],'admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Visitor']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('type'=>'button','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return editForm(".$data['Visitor']['id'].")")); ?> 
          <?php echo $this->Form->end();?>

          <?php echo $this->Form->create('VisitorDelete',array('url'=>'/visitors/index','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Visitor']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('type'=>'button','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return deleteForm();")); ?>
           <?php echo $this->Form->end();?>
           <?php } ?>
      </td> 
     
      <?php
        }
      }
      if($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")){
      ?>
      <td>
            <?php 
            // debug($data);
            if($data["Visitor"]['verify_status'] == 'Draft')
            {
              echo $display_status;
            }
            else 
            {
              $status_info = '<b>Status: </b>'.$data["Visitor"]['verify_status'].'<br>';
              if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
                $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
              if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
                $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $data["Visitor"]['verify_status'];?></a>
              <?php 
          }
            }?>
          </td>
          <!-- aakash added code to recieve cash/Item -->
          <?php
        if($allowUpdate == 0 || $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')  ) { ?>
                <td>
                  <?php if($data['Visitor']['category'] == 'Private Visit') {?>
                  <button type="button" class="btn btn-success" data-toggle="modal" onclick="setRecieveForm('<?php echo $data['Visitor']['id']; ?>','<?php echo $data['Visitor']['prisoner_no']; ?>','<?php echo $data['Currency']['id']; ?>','<?php echo $data['Currency']['name']; ?>','<?php echo $data['Visitor']['pp_amount']; ?>','<?php echo $rowCnt?>','<?php echo $data['Visitor']['pp_cash_recieved']; ?>');" data-target="#recieveNow">
                  Recieve Now
                </button>
                <?php  } ?>
                  
                </td>
                
              <?php } ?> 
            <!-- aakash code end -->  
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>


<?php
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
<?php
$getVisitorRowAjaxUrl= $this->Html->url(array('controller'=>'Visitors','action'=>'getVisitorRow'));
$getVisitorItemAjaxUrl= $this->Html->url(array('controller'=>'Visitors','action'=>'getVisitorItem'));
?>

function setReturnForm(visitorId){
  
  $('#ReturnVIsitorItemIndexForm #ReturnVIsitorItemVisitorId').val(visitorId);
     url = <?php echo '\''.$getVisitorItemAjaxUrl.'\'' ?>;
      $.post(url, {visitorId:visitorId}, function(res) {
            if (res) {
              var allCollectedResponse = $(res).filter("#allCollectedResponse");
              
              $('#ReturnVIsitorItemIndexForm #returnItemDiv').html(res);
              if(allCollectedResponse.html() == 'true'){
                $('#ReturnVIsitorItemIndexForm #returnAllBtn').css('display','inline-block');
                $('#ReturnVIsitorItemIndexForm #returnBtn').css('display','none');

                
              }else{
                $('#ReturnVIsitorItemIndexForm #returnAllBtn').css('display','none');
                $('#ReturnVIsitorItemIndexForm #returnBtn').css('display','inline-block');

              }
                 

                 
                }
            });

} 
function setRecieptForm(visitorId){
   $('#visitorReceiptItemIndexForm #visitorReceiptItemVisitorId').val(visitorId);
     url = <?php echo '\''.$getVisitorItemAjaxUrl.'\'' ?>;
      $.post(url, {visitorId:visitorId}, function(res) {
            if (res) {
              var allCollectedResponse = $(res).filter("#allCollectedResponse");
              
              $('#visitorReceiptItemIndexForm #visitorReceiptDiv').html(res);
              if(allCollectedResponse.html() == 'true'){
                $('#returnAllBtn').css('display','inline-block');
                $('#returnBtn').css('display','none');

                
              }else{
                $('#returnAllBtn').css('display','none');
                $('#returnBtn').css('display','inline-block');

              }
                 

                 
                }
            });
}
function setRecieveForm(visitorId,prisoner_no,currencyId,currency, amt,rowCnt,amount_received){
  //console.log(rowCnt);

      rowCnt =rowCnt-1;
      $('#recieved_cash_detail').html(amt + "("+currency +")");
      //console.log(amount_received);
      if(amount_received ==1){
        $('#recieved_cash_check').parent().addClass('checked');
        $('#recieved_cash_check_msg').css('display','block');
        $('#recieved_cash_check_msg2').css('display','none');
      }else{
        $('#recieved_cash_check').parent().removeClass('checked');
        $('#recieved_cash_check_msg').css('display','none');
        $('#recieved_cash_check_msg2').css('display','block');
      }
      $('#RecieveItemCashIndexForm #row_id').val(rowCnt);
      //var data ='';
      url = <?php echo '\''.$getVisitorRowAjaxUrl.'\'' ?>;
      $.post(url, {visitorId:visitorId}, function(res) {
            if (res) {
              var allCollectedResponse = $(res).filter("#allCollectedResponse");
              console.log(res);
              console.log(allCollectedResponse.html());

              $('#RecieveItemCashIndexForm #visitorprisonerPropertyDiv').html(res);
              if(allCollectedResponse.html() == 'true'){
                $('#recievedAllBtn').css('display','inline-block');
                $('#recieveBtn').css('display','none');

                
              }else{
                $('#recievedAllBtn').css('display','none');
                $('#recieveBtn').css('display','inline-block');

              }
                  $('#RecieveItemCashIndexForm #prisoner_no').val(prisoner_no);
                  $('#RecieveItemCashIndexForm #visitor_id').val(visitorId);
                  $('#RecieveItemCashIndexForm #cash_amount').val(amt);
                  $('#RecieveItemCashIndexForm #cash_currency').val(currencyId);
                

                 
                }
            });
        } 


function MyYesFunction() {
  if(isModal == 1)
    {
      $('#verify').modal('show');
    }
    else 
    {
      $('#ApprovalProcessFormIndexAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
</script>
<?php } ?>  