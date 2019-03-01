<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Generate GatePass No</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row-fluid" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Date<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('attendance_date',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Date of Attendance','required','readonly'=>'readonly','default'=>date('d-m-Y')));?>
                                                <div class="error-message nodisplay" id="attendance_date_err">Attendance date is required.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                      
                                        <div class="control-group">
                                            <label class="control-label">Working Party <?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('working_party_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$workingPartyList, 'empty'=>array('0' => '-- Select Working Party --'),'required','id'=>'working_party_id'));?>
                                                <div class="error-message nodisplay" id="working_party_err">Working Party is required.</div>
                                            </div>
                                        </div>
                                    </div>
                                    </div> 
                                  

                              <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'onclick'=>'javascript:showPrisoners();'))?>
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
$ajaxUrl    = $this->Html->url(array('controller'=>'Earnings','action'=>'gatepassAjax'));
echo $this->Html->scriptBlock("
    
    function showPrisoners(){
        var url = '".$ajaxUrl."';

        var attendance_date = $('#attendance_date').val();
        var working_party_id = $('#working_party_id').val();
        if(attendance_date == '' || working_party_id == '')
        { 
            $.post(url, {}, function(res) {
                if (res) {
                    $('#listingDiv').html(res);
                }
            });
        }
        else 
        {
            $.post(url, {'attendance_date':attendance_date,'working_party_id':working_party_id}, function(res) {
                if (res) {
                    $('#listingDiv').html(res);
                }
            });
        }
    }

    window.onload = function() {
        showPrisoners();
    }
       
",array('inline'=>false));
?>