 <div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> 
                    <h5>Prisoners Transfer</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php 
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
                            echo $this->Html->link('Outgoing Transfer List',array('action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini'));
                            //echo $this->Html->link('Discharge List',array('action'=>'transferFinalList'),array('escape'=>false,'class'=>'btn btn-success btn-mini'));
                        }
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                            
                            echo $this->Html->link('New Transfer Request',array('action'=>'add'),array('escape'=>false,'class'=>'btn btn-success btn-mini'));
                        }
                         ?>
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
                                        <div class="row" style="padding-bottom: 14px;">
                                            <div class="span12"></div>
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
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Remark <span id="require" style="display: none;"><?php echo $req; ?> </span>:</label>
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
                                    <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonerList, 'empty'=>'','id'=>'sprisoner_no'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Destination Station:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('transfer_to_station_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','id'=>'transfer_to_station_id'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Status:</label>
                                <div class="controls">
                                    <?php 
                                    $statusCore = Configure::read('STATUS');
                                    echo $this->Form->input('escorting_officer',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$statusCore['outgoing'], 'empty'=>'-- All Status --','required','id'=>'status',"default"=>$status));?>
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
                                    <?php echo $this->Form->input('escorting_officer',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$escortingOfficerList, 'empty'=>'','required','id'=>'escorting_officer'));?>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="form-actions" align="center">
                    <?php
                    echo $this->Html->link('Search',"javascript:;",array('escape'=>false,'class'=>'btn btn-success','onclick'=>"showData();")); 
                    ?>
                    <?php
                    echo $this->Html->link('Reset',"javascript:;",array('escape'=>false,'class'=>'btn btn-danger','onclick'=>"resetData('SearchIndexForm');")); 
                    ?>
                    </div>
                    <?php echo $this->Form->end();?> 
                     <div class="widget-content">
                        <div class="table-responsive" id="listingDiv">

                        </div>
                    </div>
                </div>                
                <div class="widget-content" id="listingDiv">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('select').select2('val', '');

    $('#status').select2('val', '<?php echo $status; ?>');
        showData();
    });
</script>
<?php
$ajaxUrl    = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'transferListAjax'));
$forwardUrl = $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'forwardTransfer'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();

        // process the search form
        $('#SearchIndexForm').submit(function(event) { 
            showData();
            event.preventDefault();
        });

        $('#verifyBtn').click(function(){
            if($('#verifyId').val()==0){
                forwardTransfer('', $('.verification_type:checked').val())
            }else{
                forwardTransfer($('#verifyId').val(), $('.verification_type:checked').val())
            }
        });
    });
    
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
            var msg = '';
            if (verification_type == 'Review Reject') {
                msg = 'Are you sure want to reject?';
            } else if (status == 'Reviewed') {
                msg = 'Are you sure want to verify the prisoner?';
            } else if (status == 'Approved') {
                msg = 'Are you sure want to approve?';
            } else if (status == 'Final Reject') {
                msg = 'Are you sure want to reject?';
            }else {
                msg = 'Are you sure to forward?';
            }
            AsyncConfirmYesNo(
                msg,
                'Yes',
                'No',
                function(){
                    var url = '".$forwardUrl."';
                    $.post(url, {'paramId':paramId, 'status':status,'remarks':$('#verify_remark').val()}, function(res) { 
                        if(res.trim() == 'SUCC'){
                            $('#verify').modal('hide');
                            $('#verify_remark').val('');
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

    function verifyPrisonerSetData(id){
        $('#verify').modal('show');
        $('#verifyId').val(id);
    }
    
    function resetData(id){
        $('#'+id)[0].reset();
        showData();
    }
 
",array('inline'=>false));
?>