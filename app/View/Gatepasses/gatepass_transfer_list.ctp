<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Gatepass Transfer List</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php //echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                    <?php echo $this->Form->create('GatePass',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoners</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoners --','options'=>$prisonerListData, 'class'=>'form-control', 'id'=>'prisoner_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Gatepass Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'date_from', 'readonly'=>true,'style'=>'width:150px;','value'=>date("d-m-Y")));?>
                                            To
                                            <?php echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'date_to', 'readonly'=>true,'style'=>'width:150px;','value'=>date("d-m-Y")));?>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Gatepass Type</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('gatepass_type',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Type --','options'=>$gatepassType, 'class'=>'form-control', 'id'=>'gatepass_type'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Gatepass Status</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('gatepass_status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Type --','options'=>array("Created"=>"Created","OUT"=>"OUT","IN"=>"IN"), 'class'=>'form-control', 'id'=>'gatepass_status'));?>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="span6" style="display:<?php echo ($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")) ? 'block': 'none'; ?>">
                                    <div class="control-group">
                                        <label class="control-label">Verify Status</label>
                                        <div class="controls">
                                            <?php
                                            $defaultSearch = array();
                                            if($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")){
                                                $defaultSearch = array("default"=>"Draft");
                                            }
                                            echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- All --','options'=>array("Draft"=>"Not Verified","Verified"=>"Verified"), 'class'=>'form-control','required', 'id'=>'status',"required"=>false)+$defaultSearch);?>
                                        </div>
                                    </div>
                                </div>
                                <?php /* ?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Status</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$statusListData, 'class'=>'form-control','required', 'id'=>'status','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                                <?php */ ?>
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                                
                            </div>
                            <?php echo $this->Form->end();?>
                     </div>           
                    <div class="table-responsive" id="listingDiv">

                    </div> 

                   <!--  recieve modal -->
                    <div class="modal fade" id="recieveNow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                          <?php echo $this->Form->create('RecieveItemCash',array('class'=>'form-horizontal'));?>
                          <?php echo $this->Form->input('transfer_id',array('div'=>false,'label'=>false,'type'=>'hidden','readonly','required'=>true));?>
                          <!-- <?php echo $this->Form->input('row_id',array('div'=>false,'label'=>false,'type'=>'hidden','readonly','required'=>true));?> -->
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Prisoner Item/Cash Details</h5> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                               
                              <div class="span12" >
                                  <div id="PhysicalPropertyDiv">
                                      
                                  </div>
                              </div>
                          
                      </div>
                      <div class="modal-footer">
                        <span  id="recievedAllBtn" style="color: green;">All Items Recieved</span>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="recieveBtn" onclick="submitRecieveItemCash()">Recieve</button>
                      </div>
                          <?php echo $this->Form->end();?>

                    </div>
                  </div>
                </div>   

             <!--    modal ends -->                
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$verifyAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'verify'));
$biometricSearchAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'getLastPunch'));
$ajaxUrl            = $this->Html->url(array('controller'=>'Gatepasses','action'=>'gatepassTransferListAjax'));
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$saveInverification    = $this->Html->url(array('controller'=>'Gatepasses','action'=>'saveInverification'));
$ajaxUrlSubmitRecieveItemCash =$this->Html->url(array('controller'=>'Gatepasses','action'=>'recieveTransferItemCash'));
echo $this->Html->scriptBlock("
    function verifyData(val,id){
        var a = confirm('Are you sure you want to verify this data ?');
        if(a){
            var url = '".$verifyAjax."/'+val+'/'+id;
            $.ajax({
                type: 'POST',
                url: url,
                success: function (res) {
                    if(res.trim()!='FAIL'){
                        $('#link_biometric_'+id).val(res.trim());
                        $('#link_biometric_span_'+id).html(res.trim());
                        $('#link_biometric_button_'+id).hide();
                    } 
                },
                async:false
            });
        }else{
            return false;
        }
        
    }
    function submitRecieveItemCash(){
        var url ='".$ajaxUrlSubmitRecieveItemCash."';
                $.post(url,$('#RecieveItemCashGatepassTransferListForm').serialize(), function(res) {
                    if (res) {
                        if(res.trim()=='success'){
                            console.log('Item/Cash Recieved Successfully !');
                        }else if(res.trim()=='failed'){
                            console.log('Failed to recieve !');
                        }else{
                            console.log(res);
                        }

                         $('#recieveNow').modal('toggle');
                     }
                    });
      }

    function submitInVerification(){
        $('#verifyBtn').hide();
        var url   = '".$saveInverification."';
        url = url + '/gatepassId:'+$('#gatepassId').val();
        url = url + '/inverification_time:'+$('#inverification_time').val();
        url = url + '/inverification_remark:'+$('#inverification_remark').val();
        $.post(url, {}, function(res) {
            $('#verifyBtn').show();
            showData();
        });
    }
    function checkData(val,id,type){
        var url = '".$biometricSearchAjax."/'+val+'/'+id+'/'+type;
        $.ajax({
            type: 'POST',
            url: url,
            success: function (res) {

                if(res.trim()!='FAIL'){
                    $('#link_biometric_'+type+id).val(res.trim());
                    $('#link_biometric_span_'+type+id).html(res.trim());
                    $('#link_biometric_button_'+type+id).hide();

                }else{
                    alert('Please press figure on biometric');
                }  
            },
            async:false
        });
    }

    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/prisoner_id:'+$('#prisoner_id').val();
        url = url + '/date_from:'+$('#date_from').val();
        url = url + '/date_to:'+$('#date_to').val();
        url = url + '/gatepass_type:'+$('#gatepass_type').val();
        url = url + '/gatepass_status:'+$('#gatepass_status').val();
        url = url + '/status:'+$('#status').val();
        // url = url + '/status:'+$('#status').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
            var usertype_id='".$this->Session->read('Auth.User.usertype_id')."';
            var user_typercpt='".Configure::read('RECEPTIONIST_USERTYPE')."';
            var user_typepoi='".Configure::read('PRINCIPALOFFICER_USERTYPE')."';
            var user_typeoiu='".Configure::read('OFFICERINCHARGE_USERTYPE')."';
         
         if(usertype_id==user_typercpt)
         {
            if($('#status').val()=='Saved' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                 $('td:first-child').each(function() {
                       $(this).remove();
                });
                 $('th:first-child').each(function() {
                       $(this).remove();
                });
            }
         }
         if(usertype_id==user_typepoi)
         {
            if($('#status').val()=='Reviewed' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                 $('td:first-child').each(function() {
                       $(this).remove();
                });
                 $('th:first-child').each(function() {
                       $(this).remove();
                });
            }
         }
         if(usertype_id==user_typeoiu)
         {
            if($('#status').val()=='Approved'){
                 $('td:first-child').each(function() {
                       $(this).remove();
                });
                 $('th:first-child').each(function() {
                       $(this).remove();
                });
            }
         }
        });           
    }
     $('.mytime').datetimepicker({ dateFormat: 'yy-mm-dd' });
",array('inline'=>false));
?>
<script type="text/javascript">
 $(document).ready(function(){

    $(document).on('click', '#RecieveItemCashGatepassTransferListForm .add_more_property', function(e){
        e.preventDefault();
        var classes = $('#RecieveItemCashGatepassTransferListForm .add_more_property span').attr('class');
        if(classes.indexOf('icon-plus') > -1){
          $('#RecieveItemCashGatepassTransferListForm #add_item_form').css('display','block');
          $('#RecieveItemCashGatepassTransferListForm .add_more_property span').removeClass('icon-plus');
          $('#RecieveItemCashGatepassTransferListForm .add_more_property span').addClass('icon-minus');
        }else{
          $('#RecieveItemCashGatepassTransferListForm #add_item_form').css('display','none');
          $('#RecieveItemCashGatepassTransferListForm .add_more_property span').removeClass('icon-minus');
          $('#RecieveItemCashGatepassTransferListForm .add_more_property span').addClass('icon-plus');
        }
    });

    $(document).on('click', '#RecieveItemCashGatepassTransferListForm .insert_property_item', function(e){
        e.preventDefault();
        var itemName = $('#RecieveItemCashGatepassTransferListForm  #newItemName').val();
        var itemQuantity = $('#RecieveItemCashGatepassTransferListForm  #newItemQuantity').val();
        var transfer_id = $('#RecieveItemCashGatepassTransferListForm  #RecieveItemCashTransferId').val();

        if(itemName == '' || itemQuantity == '' ){
          confirm("Please fill required fields");
        }else{
          addNewItem(itemName,itemQuantity,transfer_id);
        }
    });

    $(document).on('click', '#RecieveItemCashGatepassTransferListForm .add_more_cash', function(e){
        e.preventDefault();
        
        var classes = $('#RecieveItemCashGatepassTransferListForm .add_more_cash span').attr('class');
        if(classes.indexOf('icon-plus') > -1){
          $('#RecieveItemCashGatepassTransferListForm #add_more_cash_form').css('display','block');
          $('#RecieveItemCashGatepassTransferListForm .add_more_cash span').removeClass('icon-plus');
          $('#RecieveItemCashGatepassTransferListForm .add_more_cash span').addClass('icon-minus');
        }else{
          $('#RecieveItemCashGatepassTransferListForm #add_more_cash_form').css('display','none');
          $('#RecieveItemCashGatepassTransferListForm .add_more_cash span').removeClass('icon-minus');
          $('#RecieveItemCashGatepassTransferListForm .add_more_cash span').addClass('icon-plus');
        }
         console.log("here");
    });


    $(document).on('click', '#RecieveItemCashGatepassTransferListForm .insert_property_cash_item', function(e){
        e.preventDefault();
        var amount = $('#RecieveItemCashGatepassTransferListForm  #newItemAmount').val();
        var currency = $('#RecieveItemCashGatepassTransferListForm  #newItemCurrency').val();
        var transfer_id = $('#RecieveItemCashGatepassTransferListForm  #RecieveItemCashTransferId').val();

        if(amount == '' || currency == '' ){
          confirm("Please fill required fields");
        }else{
          addNewCashItem(amount,currency,transfer_id);
        }
    });



    $('#prisoner_id').select2('val', '');
    var defaultStatus = '<?php echo $default_status;?>';
    $('#status').select2('val', defaultStatus);
    $('#status option[value='+defaultStatus+']').attr('selected','selected');

    //$('#prisoner_id').select2('val', '');
    //$('#prisoner_id option[value='']').attr('selected','selected');
        showData();
        
    });
    $(document).on('click',"#btnsearchcash", function () { // button name
        showData();
    });
</script>