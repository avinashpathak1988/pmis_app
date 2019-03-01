<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                
                    <h5>Prisoner Transfer Request List</h5> 
               
                    <div style="float:right;padding-top: 7px;padding-right:5px;">
                        <?php
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') ||  $this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                            echo $this->Html->link('Final Transfer List',array('action'=>'transferList'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); 
                            echo "&nbsp;&nbsp;&nbsp;";
                            echo $this->Html->link('New Transfer Request',array('action'=>'add'),array('escape'=>false,'class'=>'btn btn-success btn-mini'));
                        }
                        ?>
                        <div id="verify" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                                        <h4 class="modal-title">Prisoner Verification</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $this->Form->create('VerifyPrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/verifyPrisoner','id'=>'verifyPrisoner'));?>
                                        <?php echo $this->Form->input('uuid',array('type'=>'hidden','id'=>'prisoner_uuid'));?>
                                        <div class="row" style="padding-bottom: 14px;">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Verification Type<?php echo $req; ?> :</label>
                                                    <div class="controls uradioBtn">
                                                        <?php 
                                                        $verification_type = array();
                                                        $default = array();
                                                        if(true){
                                                            $verification_type = array('verify'=>'Verified','reject'=>'Reject');
                                                            $default = array("default"=>"verify");
                                                        }
                                                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                                                            $verification_type = array('approve'=>'Approve','reject'=>'Reject');    
                                                            $default = array("default"=>"approve");
                                                        }
                                                        
                                                         echo $this->Form->radio('type', $verification_type,array("legend"=>false,'class'=>'verification_type')+$default); 
                                                        ?>
                                                        <div style="clear:both;"></div>
                                                        <div class="error-message" id="verification_type_err" style="display:none;">Verification type is required !</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Remark :</label>
                                                    <div class="controls uradioBtn">
                                                       <?php echo $this->Form->input('verify_remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Remark','id'=>'verify_remark','rows'=>3,'required'=>false));?>
                                                       <div style="clear:both;"></div>
                                                        <div class="error-message" id="verification_message_err" style="display:none;">Verification type is required !</div>
                                                    </div>
                                                </div>
                                            </div>                
                                        </div>
                                        <div class="form-actions" align="center" style="background:#fff;">
                                            <?php echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','id'=>'verifyBtn'))?>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php
                    echo $this->Form->create('Search',array('class'=>'form-horizontal'));
                        ?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner Number:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonerList, 'empty'=>'-- All Prisoner Number --','id'=>'sprisoner_no'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Destination Station:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('transfer_to_station_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonList, 'empty'=>'-- All Station --','id'=>'transfer_to_station_id'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Status:</label>
                                    <div class="controls">
                                        <?php 
                                        $statusCore = Configure::read('STATUS');
                                        echo $this->Form->input('escorting_officer',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$statusCore['outgoing'], 'empty'=>'-- All Status --','id'=>'status',"default"=>$status));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Transfer date :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'date_from', 'readonly'=>true,'style'=>'width:43%;'));?>
                                        To
                                        <?php echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'date_to', 'readonly'=>true,'style'=>'width:43%;'));?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Escorting Team:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('escorting_officer',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$escortingOfficerList, 'empty'=>'-- All Escorting Team --','required','id'=>'escorting_officer'));?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="form-actions" align="center">
                        <?php //echo $this->Form->input('Search', array('type'=>'botton', 'class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>"return showData();"))?>
                        <?php
                        echo $this->Html->link('Search',"javascript:;",array('escape'=>false,'class'=>'btn btn-success','onclick'=>"showData();")); 
                        ?>
                        <?php
                        echo $this->Html->link('Reset',"javascript:;",array('escape'=>false,'class'=>'btn btn-danger','onclick'=>"resetData('SearchIndexForm');")); 
                    ?>
                    </div>
                    <?php echo $this->Form->end();
                    ?>
                </div>                                
                <div id="listingDiv" class="widget-content"></div>                         
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('select').select2('val', '');
    $('#status').select2('val', '<?php echo (isset($status) && $status!='') ? $status : ''; ?>');
    showData();
});
 
</script>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'indexAjax'));
$deleteUrl = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'deleteTransfer'));
$forwardUrl = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'forwardTransfer'));
$inTransferUrl = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'setTransferInStatus'));
echo $this->Html->scriptBlock("  
    function showData(){
        var url = '".$ajaxUrl."';
        if($('#sprisoner_no').val() != ''){
            var prisoner_no = $('#sprisoner_no').val().replace('/', '-')
            url = url + '/prisoner_no:'+prisoner_no;
        }
        url = url + '/date_from:'+$('#date_from').val();
        url = url + '/date_to:'+$('#date_to').val();
        url = url + '/transfer_to_station_id:'+$('#transfer_to_station_id').val();
        url = url + '/escorting_officer:'+$('#escorting_officer').val();
        url = url + '/status:'+$('#status').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }

    //delete transfer
    function deleteTransfer(paramId){
        if(paramId){
            if(confirm('Are you sure want to delete?')){
                var url = '".$deleteUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res.trim() == 'SUCC'){
                        showData();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

    function ShowConfirmYesNo(msg) {
        AsyncConfirmYesNo(
                msg,
                'Save',
                'Cancel',
                MyYesFunction,
                MyNoFunction
            );
    }

    function MyYesFunction(paramId,status) {
        
    }
    function MyNoFunction() {
        
    }

    //forward transfer 
    function forwardTransfer(paramId, status){
        var matches = [];
        if(paramId==''){            
            $('input:checkbox.checkbox').each(function () {
                if(this.checked){
                     matches.push(this.value);
                }
            });
        }else{
            matches.push(paramId);
        }
        paramId = matches;
        if(paramId){
            var msg = '';
            if (status == 'Saved') {
                msg = 'Are you sure want to Add the prisoner to final list?';
            } else if (status == 'Process') {
                msg = 'Are you sure want to Transfer now the prisoner?';
            } else {
                msg = 'Are you sure to forward?';
            }
            AsyncConfirmYesNo(
                msg,
                'Yes',
                'No',
                function(){
                    var url = '".$forwardUrl."';        
                    $.post(url, {'paramId':paramId, 'status':status}, function(res) { 
                        if(res.trim() == 'SUCC'){
                            showData();
                        }else{
                            dynamicAlertBox('Message', 'Invalid request, please try again!');
                        }
                    });
                },
                function(){
                    
                }
            );
        }
    }

    //In Transfer Status
    function changeInTransferStatus(paramId, status, displayStatus){
        if(paramId){
            if(confirm('Are you sure to '+displayStatus+'?')){
                var url = '".$inTransferUrl."';
                $.post(url, {'paramId':paramId, 'status':status}, function(res) { 
                    if(res.trim() == 'SUCC'){
                        showData();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

    function resetData(id){
        $('#'+id)[0].reset();
        $('#status').select2('val', '');
        $('#sprisoner_no').select2('val', '');
        $('#transfer_to_station_id').select2('val', '');
        $('#escorting_officer').select2('val', '');
        showData();
    }

",array('inline'=>false));
?>
