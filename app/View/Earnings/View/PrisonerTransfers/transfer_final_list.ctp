 <div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">  
                <div class="widget-title"> 
                    <h5>Discharge On Transfer List</h5>                    
                    <div style="float:right;padding-top: 7px;">
                        <!-- Verify Modal START -->
                        <?php
                        if(in_array($this->Session->read('Auth.User.usertype_id'),array(Configure::read('OFFICERINCHARGE_USERTYPE'),Configure::read('RPCS_USERTYPE'),Configure::read('COMMISSIONERGENERAL_USERTYPE')))){                           
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
                                        <?php echo $this->Form->input('verify_id', array('type'=>'hidden','id'=>'verifyId','value'=>0))?>  
                                        <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
                                        echo $this->Form->input('status', array('type'=>'hidden','id'=>'status','value'=>'Saved'));
                                        }else{
                                        echo $this->Form->input('status', array('type'=>'hidden','id'=>'status','value'=>''));
                                            }?>
                                                                             
                                        <?php 
                                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
                                        echo $this->Form->input('status', array('type'=>'hidden','id'=>'displayStatus','value'=>'Process'));
                                        }else{
                                        echo $this->Form->input('status', array('type'=>'hidden','id'=>'displayStatus','value'=>''));
                                            }?>                                        
                                        <div class="row" style="padding-bottom: 14px;">
                                            <div class="span12"></div>
                                            <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){?>
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Acknowledge<?php //echo $req; ?> :</label>
                                                    <div class="controls uradioBtn">
                                                        <?php
                                                        $options = array('Property' => 'Property', 'Earning'=>'Earning');
                                                        // $selected = array(1, 3);
                                                        echo $this->Form->input('Model.name', array('label'=>false,'multiple' => 'checkbox', 'options' => $options)); //, 'selected' => $selected
                                                        ?>
                                                        <div style="clear:both;"></div>
                                                        <div class="error-message" id="verification_type_err" style="display:none;">Verification type is required !</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php }else{?>
                                                <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Verification Type<?php echo $req; ?> :</label>
                                                    <div class="controls uradioBtn">
                                                        <?php 
                                                        $verification_type = array();
                                                        $default = array();
                                                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
                                                            $verification_type = array('Reviewed'=>'Verify','Review Reject'=>'Reject');
                                                            $default = array("default"=>"Reviewed");
                                                        }
                                                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                                                            $verification_type = array('Approved'=>'Approve','Final Reject'=>'Reject');    
                                                            $default = array("default"=>"Approved");
                                                        }
                                                        
                                                         echo $this->Form->radio('type', $verification_type,array("legend"=>false,'class'=>'verification_type')+$default); 
                                                        ?>
                                                        <div style="clear:both;"></div>
                                                        <div class="error-message" id="verification_type_err" style="display:none;">Verification type is required !</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php }?>
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
                        <!-- Verify Modal END -->
                    </div>
                </div> 
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prisoner Number:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonerList, 'empty'=>'','id'=>'sprisoner_no'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">From Station:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('transfer_from_station_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','id'=>'transfer_from_station_id'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Status:</label>
                                <div class="controls">
                                    <?php 
                                    $statusCore = Configure::read('STATUS');
                                    echo $this->Form->input('escorting_officer',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select span11','type'=>'select','options'=>$statusCore['discharge'], 'empty'=>'','required','id'=>'statusaa',"default"=>$status));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Transfer date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'date_from', 'readonly'=>true,'style'=>'width:42.5%;'));?>
                                    To
                                    <?php echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'date_to', 'readonly'=>true,'style'=>'width:42.5%;'));?>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Escorting Team:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('escorting_officer',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select span11','type'=>'select','options'=>$escortingOfficerList, 'empty'=>'','required','id'=>'escorting_officer'));?>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="form-actions" align="center">
                    <?php
                    echo $this->Html->link('Search',"javascript:;",array('escape'=>false,'class'=>'btn btn-success','onclick'=>"showData();")); 
                    ?>
                    </div>
                    <?php echo $this->Form->end();?> 
                     <div class="widget-content">
                        <div class="table-responsive" id="listingDiv">

                        </div>
                    </div>
                </div>             
                <!-- <div class="widget-content nopadding">                    
                    <div class="widget-content">
                        <div class="table-responsive" id="listingDiv">

                        </div>
                    </div>
                </div>  -->
            </div>
        </div>
    </div>
</div>
<style type="text/css">
.uradioBtn div, .uradioBtn div span input, .uradioBtn label {
    float: none !important;
    display: inline-block;
}
input[type="radio"], input[type="checkbox"] {
    margin: 5px;
}
.uradioBtn div {
    width : 175px;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
    $('select').select2('val', '');
    $('#statusaa').select2('val', '<?php echo $status; ?>');
        showData();
    
    }); 

</script>
<?php
$ajaxUrl    = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'transferFinalListAjax'));
$forwardUrl = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'forwardTransfer'));
$inTransferUrl = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'setDischargeStatus'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();

        // process the search form
        $('#SearchIndexForm').submit(function(event) { 
            showData();
            event.preventDefault();
        });

        $('#verifyBtn').click(function(){
            if(($('#ModelNamePropertyClose').is(':checked') && $('#ModelNameEarningClose').is(':checked')) || $('.verification_type:checked').val() !=''){
                if($('#verifyId').val()==0){
                    changeInTransferStatus('', '','');
                }else{
                    changeInTransferStatus($('#verifyId').val(), '','');
                }
            }else{
                alert('Please close all this.');
            }
        });
    });

    function verifyFunction(id){
        if(($('#ModelNamePropertyClose').is(':checked') && $('#ModelNameEarningClose').is(':checked')) || $('.verification_type:checked').val() !=''){
            if($('#verifyId'+id).val()==0){
                changeInTransferStatus('', '',id);
            }else{
                changeInTransferStatus($('#verifyId'+id).val(), '',id);
            }
        }else{
            alert('Please close all this.');
        }
    }
    
    function showData(){
        var url = '".$ajaxUrl."';
        if($('#sprisoner_no').val() != ''){
            var prisoner_no = $('#sprisoner_no').val().replace('/', '-')
            url = url + '/prisoner_no:'+prisoner_no;
        }
        url = url + '/date_from:'+$('#date_from').val();
        url = url + '/date_to:'+$('#date_to').val();
        url = url + '/transfer_from_station_id:'+$('#transfer_from_station_id').val();
        url = url + '/escorting_officer:'+$('#escorting_officer').val();
        url = url + '/status:'+$('#statusaa').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }

    //forward transfer 
    function forwardTransfer(paramId, status){
        var verification_type = $('.verification_type:checked').val();
        if((verification_type == 'Review Reject' || verification_type == 'Final Reject') && $('#verify_remark').val()==''){
            alert('Please enter remark for reject.');
            return false;
        }
        var matches = [];
        if(paramId==''){     
            $('input:checkbox.checkboxbutton').each(function () {
                if(this.checked){
                     matches.push(this.value);
                }
            });
        }else{
            matches.push(paramId);
        }
        paramId = matches;
        if(paramId){
            if(confirm('Are you sure to forward?')){
                var url = '".$forwardUrl."';
                $.post(url, {'paramId':paramId, 'status':status,'remarks':$('#verify_remark').val()}, function(res) { 
                    if(res == 'SUCC'){
                        $('#verify').modal('hide');
                        $('#verify_remark').val('');
                        showData();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

    function verifyPrisonerSetData(id){
        $('#verify').modal('show');
        $('#verifyId').val(id);
    }
    
    function resetData(id){
        $('#'+id)[0].reset();
        showData();
    }

    //In Transfer Status
    function changeInTransferStatus(paramId, status,id){
        var status=$('#status'+id).val();
        var displayStatus = $('#displayStatus'+id).val();
        if(status == ''){
            var status = $('.verification_type:checked').val();
            var displayStatus = $('.verification_type:checked').val();
        }
        
       
        var verify_remark = $('#verify_remark'+id).val();
        var closeVal = [];
        $('.property'+id+' input[type=checkbox]:checked').each(function () {
            closeVal.push(this.value);
        });

        var closeCashVal = [];
        $('.cash'+id+' input[type=checkbox]:checked').each(function () {
            closeCashVal.push(this.value);
        });

        var earning = $('.earning'+id).val();
        
        if(status==''){
            alert('Please Acknowledge');
            return false;
        }
        var matches = [];
        if(paramId==''){            
            $('input:checkbox.checkboxbutton').each(function () {
                if(this.checked){
                    // alert();
                    matches.push(this.value);
                }
            });
        }else{
            matches.push(paramId);
        }
        paramId = matches;

        if(paramId){
            if (status == 'Saved') {
                msg = 'Are you sure want to process?';
            } else if (status == 'Reviewed') {
                msg = 'Are you sure want to verify the prisoner?';
            } else if (status == 'Review Reject') {
                msg = 'Are you sure want to reject?';
            } else if (status == 'Approved') {
                msg = 'Are you sure want to approve?';
            } else if (status == 'Final Reject') {
                msg = 'Are you sure want to reject?';
            } else if (status == 'Higher Approved') {
                msg = 'Are you sure want to approve?';
            } else if (status == 'Higher Reject') {
                msg = 'Are you sure want to reject?';
            }else {
                msg = 'Are you sure to forward?';
            }

            AsyncConfirmYesNo(
                msg,
                'Yes',
                'No',
                function(){
                    var url = '".$inTransferUrl."';
                    $.post(url, {'paramId':paramId, 'status':status,'closeVal':closeVal,'closeCashVal':closeCashVal,'earning':earning,'verify_remark':verify_remark}, function(res) { 
                        if(res.trim() == 'SUCC'){
                            $('#verify'+id).modal('hide');
                            $('#verify_remark'+id).val('');
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
 
",array('inline'=>false));
?>