<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Approve Working Attendances</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row-fluid" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Attendance Date :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_from',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate span11','type'=>'text', 'placeholder'=>'Select From Date ','required','readonly'=>'readonly','id'=>'date_from'));?>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Working Party:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('working_party_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$workingPartyList, 'empty'=>'','required','id'=>'working_party_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                   
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Attendance Status :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('attendancestatus',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>array('1'=>'Present', '2'=> 'Absent'),'required'=>false, 'empty'=>'', 'style'=>'width:90%', 'id'=>'attendancestatus'));?>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner No:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonerAttendanceList, 'empty'=>'','required','id'=>'prisoner_no'));?>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <br>
                                 <div class="row-fluid">
                                   
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Name :</label>
                                          <div class="controls">
                                                <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonerAttendanceNameList, 'empty'=>'','required','id'=>'prisoner_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <br>
                                <div class="form-actions" align="center">
                                    <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>'javascript:showPrisonersList();'))?>
                                    <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData()"))?>
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
$ajaxUrl    = $this->Html->url(array('controller'=>'Earnings','action'=>'attendanceListAjax'));
echo $this->Html->scriptBlock("

    function resetData() {
        // alert(1);
         $('select').select2('val', '');
         showPrisonersList();
    }
    
    function showPrisonersList()
    {
        var url = '".$ajaxUrl."';

        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        var working_party_id = $('#working_party_id').val();
        var attendancestatus = $('#attendancestatus').val();
        var prisoner_no = $('#prisoner_no').val();
        var prisoner_name = $('#prisoner_name').val();
        $.post(url, {'date_from':date_from,'date_to':date_to,'working_party_id':working_party_id,'attendancestatus':attendancestatus,'prisoner_no':prisoner_no,'prisoner_name':prisoner_name}, function(res) {
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