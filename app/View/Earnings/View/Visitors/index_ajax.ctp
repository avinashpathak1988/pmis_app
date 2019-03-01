<?php
if(is_array($datas) && count($datas)>0){
 // debug($datas);
  if(!isset($is_excel)){
?>
<style type="text/css">
  .prisoner-item-show{
    padding-left: 20px;
  }
  .button-gap{
    margin-bottom: 5px;
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
            'controller'            => 'Visitors',
            'action'                => 'indexAjax',
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
foreach($datas as $data){
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
                  <button type="button" class="btn btn-mini btn-success" data-toggle="modal" onclick="setReturnForm('<?php echo $data['Visitor']['id']; ?>');" data-target="#returnNow">
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
            <?php if($data['Visitor']['category'] == 'Private Visit') {?>
              <?php if($data['Visitor']['blacklisted'] == false){ ?>
                    <?php echo $this->Form->button('BLacklist', array('type'=>'button', 'div'=>false,'style'=>'margin-top:5px;', 'label'=>false, 'class'=>'btn btn-warning btn-mini button-gap', 'onclick'=>"javascript:blacklist(".$data['Visitor']['id'].");"));
                      ?>
              <?php }else{ ?>
                   <span style="margin-top: 5px;color: red;"> blacklisted </span>
              <?php } ?>
            
            <?php } ?>
          </td>
          <?php
            if($this->Session->read('Auth.User.usertype_id')!=Configure::read("OFFICERINCHARGE_USERTYPE")){
            ?>
          <td>
              <?php 
              // echo $data['Visitor']['status'];
              if($data['Visitor']['status']=='IN' && $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')){
                echo $this->Form->button('Timein', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning btn-mini button-gap', 'onclick'=>"javascript:newTimeOut(".$data['Visitor']['id'].",'IN');"));
              } ?>
              &nbsp;&nbsp;&nbsp;
              <?php
             
              if($data['Visitor']['status']=='Gate IN' && $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')){
                echo $this->Form->button('Timeout', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning btn-mini button-gap', 'onclick'=>"javascript:newTimeOut(".$data['Visitor']['id'].",'OUT');"));
              }
              if($data['Visitor']['status']=='Gate Out' && $this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                echo $this->Form->button('Timeout', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning btn-mini button-gap', 'onclick'=>"javascript:newTimeOut(".$data['Visitor']['id'].",'OUT');"));
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
                  <button type="button" class="btn btn-success btn-mini button-gap" data-toggle="modal" onclick="setRecieveForm('<?php echo $data['Visitor']['id']; ?>');" data-target="#recieveNow">
                  Recieve
                </button><br/>
                  <?php if(count($data['CanteenFoodItem']) == 0 && $data['Visitor']['main_gate_out_time'] == ''){?>
                  <?php echo $this->Form->button('Canteen', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning btn-mini', 'onclick'=>"javascript:addCanteenFood(".$data['Visitor']['id'].");"));
                 } 
               }?>
                  
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
$ajaxAddNewItemUrl =  $this->Html->url(array('controller'=>'Visitors','action'=>'ajaxAddNewItem'));
$ajaxAddNewCashItemUrl =$this->Html->url(array('controller'=>'Visitors','action'=>'ajaxAddNewCashItem'));
$ajaxBlacklistUrl = $this->Html->url(array('controller'=>'Visitors','action'=>'blacklistVisitor'));

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
function addNewItem(itemName,itemQuantity,visitor_id){
        var url =<?php echo '\''.$ajaxAddNewItemUrl.'\'' ?>;
        var data ={itemName:itemName,itemQuantity:itemQuantity,visitor_id:visitor_id}
                $.post(url,data, function(res) {
                    if (res) {
                       setRecieveForm(visitor_id);
                     }
                    });
      }
function addNewCashItem(amount,currency,visitor_id){
        var url =<?php echo '\''.$ajaxAddNewCashItemUrl.'\'' ?>;
        var data ={amount:amount,currency:currency,visitor_id:visitor_id}
                $.post(url,data, function(res) {
                    if (res) {
                       setRecieveForm(visitor_id);
                     }
                    });
      }
function blacklist(visitor_id){
        var url =<?php echo '\''.$ajaxBlacklistUrl.'\'' ?>;
        if(confirm("Are you sure you want to blacklist this visitor?")){
                var data ={visitor_id:visitor_id}
                $.post(url,data, function(res) {
                      //console.log(res);
                       showData();
                    });
        }
        
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
function setRecieveForm(visitorId){
  //console.log(rowCnt);

     /* rowCnt =rowCnt-1;
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
      $('#RecieveItemCashIndexForm #row_id').val(rowCnt);*/
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
                 /* $('#RecieveItemCashIndexForm #prisoner_no').val(prisoner_no);*/
                  $('#RecieveItemCashIndexForm #visitor_id').val(visitorId);
                  /*$('#RecieveItemCashIndexForm #cash_amount').val(amt);
                  $('#RecieveItemCashIndexForm #cash_currency').val(currencyId);*/
                

                 
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