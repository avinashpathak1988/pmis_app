<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Visitors Record List</h5>
                    <?php if($allowUpdate){ ?>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add Visitors  Record'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                    <?php } ?>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row prison-visitor">
                        <div class="span6 prison-visitor-inn">
                            <div class="control-group">
                                <label class="control-label">Visit Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'from', 'readonly'=>true,'style'=>'width:150px;',"value"=>date("d-m-Y")));?>
                                    To
                                    <?php echo $this->Form->input('to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'to', 'readonly'=>true,'style'=>'width:150px;',"value"=>date("d-m-Y")));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6" style="display:<?php echo ($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")) ? 'block': 'none'; ?>">
                            <div class="control-group">
                                <label class="control-label">Status</label>
                                <div class="controls">
                                    <?php
                                    $defaultSearch = array();
                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")){
                                        $defaultSearch = array("default"=>"Draft");
                                    }
                                    echo $this->Form->input('verify_status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- All --','options'=>array("Draft"=>"Not Verified","Verified"=>"Verified"), 'class'=>'form-control','required', 'id'=>'verify_status')+$defaultSearch);?>
                                </div>
                            </div>
                        </div>
                    </div>
     
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
                    </div>
                    <?php echo $this->Form->end();?> 
                    <div class="table-responsive" id="listingDiv">

                    </div>
                </div>

                <!-- modal to recieve Item /Cash aakash -->
                <div class="modal fade" id="recieveNow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                          <?php echo $this->Form->create('RecieveItemCash',array('class'=>'form-horizontal'));?>
                          <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'type'=>'hidden','readonly','required'=>true,'id'=>'prisoner_no'));?>
                          <?php echo $this->Form->input('visitor_id',array('div'=>false,'label'=>false,'type'=>'hidden','readonly','required'=>true,'id'=>'visitor_id'));?>
                          <?php echo $this->Form->input('row_id',array('div'=>false,'label'=>false,'type'=>'hidden','readonly','required'=>true,'id'=>'row_id'));?>
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Prisoner Item/Cash Details</h5> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                               
                              <div class="span12" >
                                  <div id="visitorprisonerPropertyDiv">
                                      
                                  </div>
                              </div>
                          
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="recieveBtn" onclick="submitRecieveItemCash()">Recieve</button>
                        <span  id="recievedAllBtn" style="color: green;">All Items Recieved</span>
                      </div>
                          <?php echo $this->Form->end();?>

                    </div>
                  </div>
                </div>
                <!-- Recieve now modal end -->
                <div class="modal fade" id="visitorReciept" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                          <?php echo $this->Form->create('visitorReceiptItem',array('class'=>'form-horizontal'));?>
                          
                          <?php echo $this->Form->input('visitor_id',array('div'=>false,'label'=>false,'type'=>'hidden','readonly','required'=>true));?>
                          
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Prisoner Item/Cash Details</h5> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                               
                              <div class="span12" >
                                  <div id="visitorReceiptDiv">
                                      
                                  </div>
                              </div>
                          
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="printBtn" onclick="printReceipt()">Print</button>
                        <span  id="returnAllBtn" style="color: green;">All Items Returned</span>
                      </div>
                          <?php echo $this->Form->end();?>

                    </div>
                  </div>
                </div>

                <!-- Reciept modal end -->
                <div class="modal fade" id="returnNow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                          <?php echo $this->Form->create('ReturnVIsitorItem',array('class'=>'form-horizontal'));?>
                          
                          <?php echo $this->Form->input('visitor_id',array('div'=>false,'label'=>false,'type'=>'hidden','readonly','required'=>true));?>
                          
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Prisoner Item/Cash Details</h5> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                               
                              <div class="span12" >
                                  <div id="returnItemDiv">
                                      
                                  </div>
                              </div>
                          
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="returnBtn" onclick="submitReturnItem()">Return</button>
                        <span  id="returnAllBtn" style="color: green;">All Items Returned</span>
                      </div>
                          <?php echo $this->Form->end();?>

                    </div>
                  </div>
                </div>

                <!-- Return Modal end -->
                 <div class="modal fade" id="addCanteenFood" tabindex="-1" role="dialog" aria-labelledby="addCanteenFoodLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">

                          <?php echo $this->Form->create('CanteenFoodItem',array('class'=>'form-horizontal'));?>
                          
                          <?php echo $this->Form->input('Visitor.visitor_id',array('div'=>false,'label'=>false,'type'=>'hidden','readonly','required'=>true));?>
                          
                      <div class="modal-header">
                        <h5 class="modal-title" id="addCanteenFoodLabel">Canteen Food Items</h5> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                              <?php echo $this->element('canteen-food');?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="returnBtn" onclick="submitCanteenFood()">Save</button>
                      </div>
                          <?php echo $this->Form->end();?>

                    </div>
                  </div>
                </div>
                <!-- Canteen Food modal end -->


            </div>
        </div>
    </div>
</div>

<?php
$gatekeeper = ($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')) ? 'gatekeeper' : 'main gatekeeper';
$ajaxUrl      = $this->Html->url(array('controller'=>'Visitors','action'=>'indexAjax'));
$timeoutUrl   = $this->Html->url(array('controller'=>'Visitors','action'=>'timeout'));
$newtimeoutUrl= $this->Html->url(array('controller'=>'Visitors','action'=>'newTimeout'));
$alertUrl   = $this->Html->url(array('controller'=>'Visitors','action'=>'alert'));
$newalertUrl   = $this->Html->url(array('controller'=>'Visitors','action'=>'newAlert'));
$ajaxUrlSubmitRecieveItemCash = $this->Html->url(array('controller'=>'Visitors','action'=>'recieveItemCash'));
$ajaxUrlSubmitReturnVisitorItem =  $this->Html->url(array('controller'=>'Visitors','action'=>'returnVisitorItem'));
$ajaxUrlSubmitCanteenFoodItem =  $this->Html->url(array('controller'=>'Visitors','action'=>'submitCanteenFood'));



echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });

    function submitRecieveItemCash(){
        var url ='".$ajaxUrlSubmitRecieveItemCash."';
                $.post(url,$('#RecieveItemCashIndexForm').serialize(), function(res) {
                    if (res) {
                        if(res=='success'){
                            console.log('Item/Cash Recieved Successfully !');
                        }else if(res=='failed'){
                            console.log('Failed to recieve !');
                        }else{
                            console.log(res);
                        }

                         $('#recieveNow').modal('toggle');
                     }
                    });
      }

      function submitCanteenFood(){
        var url ='".$ajaxUrlSubmitCanteenFoodItem."';
                $.post(url,$('#CanteenFoodItemIndexForm').serialize(), function(res) {
                    if (res) {
                        if(res=='success'){
                            console.log('Canteen Food svaed Successfully !');
                        }else if(res=='failed'){
                            console.log('Failed to save !');
                        }else{
                            console.log(res);
                        }
                        $('#CanteenFoodItemIndexForm :input').val('');
                        location.reload();
                     }

                    });
      }

      function printReceipt(){
         var divToPrint = document.getElementById('visitorReceiptDiv');
       var popupWin = window.open('', '_blank', 'width=300,height=300');
       popupWin.document.open();
       popupWin.document.write('<html><body onload=\"window.print()\">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
      }
      function submitReturnItem(){
        var url ='".$ajaxUrlSubmitReturnVisitorItem."';
                $.post(url,$('#ReturnVIsitorItemIndexForm').serialize(), function(res) {
                    if (res) {
                        if(res=='success'){
                            console.log('Items  Returned Successfully !');
                        }else if(res=='failed'){
                            console.log('Failed to recieve !');
                        }

                         $('#returnNow').modal('toggle');
                     }
                    });
      }

    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/from:' + $('#from').val();
        url = url + '/to:' + $('#to').val();
        url = url + '/verify_status:' + $('#verify_status').val();
        $.post(url, {}, function(res) {
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
            }
        });    
    }

    function timeOut(visitor_id){
        if(visitor_id){
            AsyncConfirmYesNo(
                'Are you sure want update time for this visitor?',
                'Yes',
                'No',
                function(){
                   var url = '".$timeoutUrl."';
                    url = url + '/visitor_id:'+visitor_id;
                    
                    $.post(url, {}, function(res) {
                        if(res == 'SUCC'){
                            location.reload();
                        }else{
                            //alert('Invalid request, please try again!');
                            dynamicAlertBox('Error','Invalid request, please try again!');
                        }
                    });
                },
                function(){
                    
                }
            );
        }
    }

    function newTimeOut(visitor_id, type){
        var msg = '';
        if(type == 'IN'){
            var msg = 'do you want to check in??';
        }
        if(type == 'OUT'){
            var msg = 'do you want to check out??';
        }
        if(visitor_id){
            AsyncConfirmYesNo(
                msg,
                'Yes',
                'No',
                function(){
                   var url = '".$newtimeoutUrl."';
                    url = url + '/visitor_id:'+visitor_id;
                    
                    $.post(url, {}, function(res) {
                        if(res == 'SUCC'){
                            showData();
                        }else{
                            //alert('Invalid request, please try again!');
                            dynamicAlertBox('Error','Invalid request, please try again!');
                        }
                    });
                },
                function(){
                    
                }
            );
        }
    }

    function addCanteenFood(visitor_id){
        //console.log(visitor_id);
        $('#CanteenFoodItemIndexForm #VisitorVisitorId').val(visitor_id);
        $('#addCanteenFood').modal('show');
    }
    function alert(visitor_id){
        if(visitor_id){
            AsyncConfirmYesNo(
                'Are you sure want send alert to ".$gatekeeper."?',
                'Yes',
                'No',
                function(){
                   var url = '".$alertUrl."';
                    url             = url + '/visitor_id:'+visitor_id;
                    
                    $.post(url, {}, function(res) {
                        if(res == 'SUCC'){
                             dynamicAlertBox('Success','Alert Send To ".$gatekeeper."!')
                        }else{
                            //alert('Invalid request, please try again!');
                            dynamicAlertBox('Error','Invalid request, please try again!');
                        }
                    });
                },
                function(){
                    
                }
            );
        }
    }
    

    function newAlert(visitor_id){
        if(visitor_id){
            AsyncConfirmYesNo(
                'Are you sure want send alert to ".$gatekeeper."?',
                'Yes',
                'No',
                function(){
                   var url = '".$newalertUrl."';
                    url             = url + '/visitor_id:'+visitor_id;
                    
                    $.post(url, {}, function(res) {
                        if(res == 'SUCC'){
                             dynamicAlertBox('Success','Alert Send To ".$gatekeeper."!')
                        }else{
                            //alert('Invalid request, please try again!');
                            dynamicAlertBox('Error','Invalid request, please try again!');
                        }
                    });
                },
                function(){
                    
                }
            );
        }
    }
",array('inline'=>false));
?>  
<script type="text/javascript">
$(document).ready(function(){
    $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
});
$(document).ready(function(){
   // $('.to').datepicker({ dateFormat: 'dd-mm-yy' });

    $(document).on('click', '#RecieveItemCashIndexForm .add_more_property', function(e){
        e.preventDefault();
        var classes = $('#RecieveItemCashIndexForm .add_more_property span').attr('class');
        if(classes.indexOf('icon-plus') > -1){
          $('#RecieveItemCashIndexForm #add_item_form').css('display','block');
          $('#RecieveItemCashIndexForm .add_more_property span').removeClass('icon-plus');
          $('#RecieveItemCashIndexForm .add_more_property span').addClass('icon-minus');
        }else{
          $('#RecieveItemCashIndexForm #add_item_form').css('display','none');
          $('#RecieveItemCashIndexForm .add_more_property span').removeClass('icon-minus');
          $('#RecieveItemCashIndexForm .add_more_property span').addClass('icon-plus');
        }
    });
     $(document).on('click', '#RecieveItemCashIndexForm .insert_property_item', function(e){
        e.preventDefault();
        var itemName = $('#RecieveItemCashIndexForm  #newItemName').val();
        var itemQuantity = $('#RecieveItemCashIndexForm  #newItemQuantity').val();
        var visitor_id = $('#RecieveItemCashIndexForm  #visitor_id').val();

        if(itemName == '' || itemQuantity == '' ){
          confirm("Please fill required fields");
        }else{
          addNewItem(itemName,itemQuantity,visitor_id);
        }
    });

     $(document).on('click', '#RecieveItemCashIndexForm .add_more_cash', function(e){
        e.preventDefault();
        
        var classes = $('#RecieveItemCashIndexForm .add_more_cash span').attr('class');
        if(classes.indexOf('icon-plus') > -1){
          $('#RecieveItemCashIndexForm #add_more_cash_form').css('display','block');
          $('#RecieveItemCashIndexForm .add_more_cash span').removeClass('icon-plus');
          $('#RecieveItemCashIndexForm .add_more_cash span').addClass('icon-minus');
        }else{
          $('#RecieveItemCashIndexForm #add_more_cash_form').css('display','none');
          $('#RecieveItemCashIndexForm .add_more_cash span').removeClass('icon-minus');
          $('#RecieveItemCashIndexForm .add_more_cash span').addClass('icon-plus');
        }
         console.log("here");
    });

     $(document).on('click', '#RecieveItemCashIndexForm .insert_property_cash_item', function(e){
        e.preventDefault();
        var amount = $('#RecieveItemCashIndexForm  #newItemAmount').val();
        var currency = $('#RecieveItemCashIndexForm  #newItemCurrency').val();
        var visitor_id = $('#RecieveItemCashIndexForm  #visitor_id').val();

        if(amount == '' || currency == '' ){
          confirm("Please fill required fields");
        }else{
          addNewCashItem(amount,currency,visitor_id);
        }
    });
      

});

function editForm(id){
    console.log(id);
      AsyncConfirmYesNo(
                'Are you sure want to edit?',
                'Yes',
                'No',
                function(){
                    $('#VisitorEdit_'+id).submit();
                },
                function(){
                    
                }
            );
  }
  function deleteForm(){
      AsyncConfirmYesNo(
                'Are you sure want to delete?',
                'Yes',
                'No',
                function(){
                    $('#VisitorDeleteIndexAjaxForm').submit();
                },
                function(){
                    
                }
            );
  }
 
</script>











