<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Approve Working Attendance</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row-fluid" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">From Date :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_from',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate span11','type'=>'text', 'placeholder'=>'Select From Date ','required','readonly'=>'readonly','id'=>'date_from'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                            <div class="control-group">
                                            <label class="control-label">To Date :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_to',array('div'=>false,'label'=>false,'class'=>'form-control to_date mydate span11','type'=>'text', 'placeholder'=>'Select To Date ','required','readonly'=>'readonly','id'=>'date_to'));?>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Working Party:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('working_party_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$workingPartyList, 'empty'=>'','required','id'=>'working_party_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Approval Status :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$approvalStatusList,'required'=>false, 'empty'=>'', 'style'=>'width:90%', 'id'=>'status', 'default'=>$default_status));?>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-actions" align="center">
                                    <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>'javascript:showPrisonersList();'))?>
                                    <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchApproveAttendancesForm')"))?>
                                </div>
                                <?php echo $this->Form->end();?>
                               <div id="listingDiv"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$ajaxUrl    = $this->Html->url(array('controller'=>'Earnings','action'=>'attendanceAjax'));
echo $this->Html->scriptBlock("
    
    function showPrisonersList()
    {
        var url = '".$ajaxUrl."';

        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        var working_party_id = $('#working_party_id').val();
        var status = $('#status').val();
        $.post(url, {'date_from':date_from,'date_to':date_to,'working_party_id':working_party_id,'status':status}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }

    window.onload = function() {
      showPrisonersList();
    }
       
",array('inline'=>false));
?>