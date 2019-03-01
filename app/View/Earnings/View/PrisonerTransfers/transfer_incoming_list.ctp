 <div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">  
                <div class="widget-title"> 
                    <h5>Incoming Transfer List</h5>                    
                    <div style="float:right;padding-top: 7px;">
                        <!-- Verify Modal START -->
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
                                        echo $this->Form->input('statusaaa', array('type'=>'hidden','id'=>'displayStatus','value'=>'Acknowledged'));
                                        }elseif ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')) {
                                            echo $this->Form->input('statusaaa', array('type'=>'hidden','id'=>'displayStatus','value'=>'verify'));
                                        }elseif ($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')) {
                                            echo $this->Form->input('statusaaa', array('type'=>'hidden','id'=>'displayStatus','value'=>'verify'));
                                        }else{
                                        echo $this->Form->input('status', array('type'=>'hidden','id'=>'displayStatus','value'=>''));
                                            }?>                                        
                                        <div class="row" style="padding-bottom: 14px;">
                                             <div class="span12"></div>
                                            <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){?>
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Acknowledge <?php //echo $req; ?> :</label>
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
                                                            $verification_type = array('Reviewed'=>'Reviewed','Review Reject'=>'Reject');
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
                    <?php /* ?><div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prisoner No. :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('sprisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'sprisoner_no', 'style'=>'width:200px;'));?>
                                </div>
                            </div>
                        
                            <div class="control-group">
                                <label class="control-label">Prisoner Name :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alfa','type'=>'text','placeholder'=>'Enter Prisoner Name','id'=>'prisoner_name', 'style'=>'width:200px;'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Prisoner Type:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prisoner_type_id',array('div'=>false,'label'=>false,'onChange'=>'showPrisonerSubType(this.value)','class'=>'form-control span11','type'=>'select','options'=>$prisonerTypeList, 'empty'=>'-- Select Prisoner Type --','required','id'=>'prisoner_type_id'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">EPD :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'epd_from', 'readonly'=>true,'style'=>'width:150px;'));?>
                                    To
                                    <?php echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'epd_to', 'readonly'=>true,'style'=>'width:150px;'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Age between:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('age_from',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','id'=>'age_from', 'style'=>'width:100px;'));?>
                                    &
                                    <?php echo $this->Form->input('age_to',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','id'=>'age_to', 'style'=>'width:100px;'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Prisoner Sub Type:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Prisoner Sub Type --','required','id'=>'prisoner_sub_type_id'));?>
                                </div>
                            </div>
                            <!-- <div class="control-group">
                                <label class="control-label">Displinary Action:</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Prisoner Sub Type --','required','id'=>'prisoner_sub_type_id'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Court Attendence:</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Prisoner Sub Type --','required','id'=>'prisoner_sub_type_id'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remaining Sentence:</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Prisoner Sub Type --','required','id'=>'prisoner_sub_type_id'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Offence:</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Prisoner Sub Type --','required','id'=>'prisoner_sub_type_id'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Stage:</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Prisoner Sub Type --','required','id'=>'prisoner_sub_type_id'));?>
                                </div>
                            </div> -->
                        </div>
                        <div class="span12" align="center" valign="center">
                            <?php echo $this->Form->input('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'onclick'=>"showData()"))?>
                            <?php echo $this->Form->input('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchListviewForm')"))?>
                        </div>                        
                    </div> <?php */ ?>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prisoner Number:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonerList, 'empty'=>'-- All Prisoner Number --','id'=>'sprisoner_no'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">From Prison:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('transfer_from_station_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonList, 'empty'=>'-- All Station --','id'=>'transfer_from_station_id'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Status:</label>
                                <div class="controls">
                                    <?php 
                                    echo $this->Form->input('escorting_officer',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array('Draft'=>'Draft','Saved'=>'Saved','Reviewed'=>'Reviewed','Review Reject'=>'Review Reject','Approved'=>'Approved','Final Reject'=>'Final Reject'), 'empty'=>'-- All Status --','id'=>'statusaa',"default"=>$status));?>
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
                                    <?php echo $this->Form->input('escorting_officer',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$escortingOfficerList, 'empty'=>'-- All Escorting Officer --','required','id'=>'escorting_officer'));?>
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
<?php 
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
?>
.uradioBtn div {
    width : 175px;
}
<?php
}
?>
</style>
<script type="text/javascript">
$(document).ready(function(){
    $('select').select2('val', '');
    $('#statusaa').select2('val', '<?php echo $status; ?>');
        showData();
    }); 
</script>
<?php
$savePropertyAjax = $this->Html->url(array('controller'=>'Gatepasses','action'=>'updatePhysicalProperty'));
$ajaxUrl    = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'transferIncomingListAjax'));
$forwardUrl = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'forwardTransfer'));
$inTransferUrl = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'setTransferInStatus'));
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
        var displayStatus = $('#displayStatus').val();
        if(status == ''){
            var status = $('.verification_type:checked').val();
            var displayStatus = $('.verification_type:checked').val();
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
    function changeInTransferStatus(paramId, status, id){
        var status=$('#status'+id).val();
        if(status == ''){
            var status = $('.verification_type'+id+':checked').val();
        }
        
        var displayStatus = $('#displayStatus'+id).val();
        var verify_remark = $('#verify_remark'+id).val();
        // var closeVal = '';
        // if($('#ModelNamePropertyClose').is(':checked') && $('#ModelNameEarningClose').is(':checked')){
        //     var closeVal = $('#ModelNamePropertyClose').val() + ', ' + $('#ModelNameEarningClose').val();
        // }
        var closeVal = [];
        $('.property'+id+' input[type=checkbox]:checked').each(function () {
            closeVal.push(this.value);
        });
        var closeCashVal = [];
        $('.cash'+id+' input[type=checkbox]:checked').each(function () {
            closeCashVal.push(this.value);
        });

        var earning = $('.earning'+id).val();
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
            AsyncConfirmYesNo(
                'Are you sure to '+displayStatus+'?',
                'Yes',
                'No',
                function(){
                    var url = '".$inTransferUrl."';
                    $.post(url, {'paramId':paramId, 'status':status,'closeVal':closeVal,'closeCashVal':closeCashVal,'earning':earning,'verify_remark':verify_remark}, function(res) { 
                        if(res.trim() == 'SUCC'){
                            $('#verify').modal('hide');
                            $('#verify_remark').val('');
                            showData();
                        }else{
                            alert('Invalid request, please try again!');
                        }
                    });
                },
                function(){
                    
                }
            );
        }
    }

    function saveProperty(id){
        AsyncConfirmYesNo(
            'Are you sure to forward?',
            'Yes',
            'No',
            function(){
                $.ajax({
                    type : 'POST',
                    url : '".$savePropertyAjax."',
                    data : $('#PropertyData'+id).serialize(),
                    success: function (data) {
                        if(data.trim()=='SUCC'){
                            alert(data);
                            $('#myModalRcv'+id).modal('hide');
                            alert(data);
                            showData(); 
                        }
                    }
                });
            },
            function(){
                
            }
        )
    }
 
",array('inline'=>false));
?>