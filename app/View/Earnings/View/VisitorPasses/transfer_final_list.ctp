 <div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">  
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
                <div class="widget-content nopadding">                    
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
$ajaxUrl    = $this->Html->url(array('controller'=>'VisitorPasses','action'=>'transferFinalListAjax'));
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