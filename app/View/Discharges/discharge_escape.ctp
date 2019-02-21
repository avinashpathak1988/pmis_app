<?php
if(isset($this->data['DischargeEscape']['date_of_escape']) && $this->data['DischargeEscape']['date_of_escape'] != ''){
    $this->request->data['DischargeEscape']['date_of_escape'] = date('d-m-Y', strtotime($this->data['DischargeEscape']['date_of_escape']));
}
if(isset($this->data['DischargeEscape']['date_of_recapture']) && $this->data['DischargeEscape']['date_of_recapture'] != ''){
    $this->request->data['DischargeEscape']['date_of_recapture'] = date('d-m-Y', strtotime($this->data['DischargeEscape']['date_of_recapture']));
}
?>
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
                    <h5>Discharge Escape Records</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('controller'=>'prisoners', 'action'=>'details',$uuid),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php echo $this->Form->create('DischargeEscape',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                        <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date of Escape <?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('date_of_escape',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Date of Escape','readonly'=>'readonly','class'=>'form-control mydate','required', 'id'=>'date_of_escape'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Time of Escape :</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('time_of_escape',array('div'=>false,'label'=>false,'class'=>'form-control span11 mytime','type'=>'text', 'placeholder'=>'Enter Schedule Time ','required','readonly'=>'readonly','id'=>'attendance_time'));?>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Place :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('place',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control','required', 'id'=>'place_of_death'));?>
                                    </div>
                                </div>
                            </div>                     
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Person from whose custody escaped :</label>
                                    <div class="controls">
                                       <?php echo $this->Form->textarea('person_whom_custody_escaped',array('div'=>false,'label'=>false,'class'=>'form-control','required', 'id'=>'cause_death'));?>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Escaped from inside or outside :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('escaped_inside_outside',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control','required', 'id'=>'escaped_inside_outside'));?>
                                    </div>
                                </div>
                            </div>                     
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date of Recapture :</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('date_of_recapture',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Date of recapture','readonly'=>'readonly','class'=>'form-control mydate','required', 'id'=>'date_of_recapture'));?>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Sentences :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('sentence_id',array('type'=>'select', 'div'=>false,'label'=>false,'type'=>'select','empty'=>'--Select Sentences--','options'=>$sentences,'class'=>'form-control','required', 'id'=>'medicalOfficers'));?>
                                    </div>
                                </div>
                            </div> 
                            <div class="clearfix"></div> 
                        </div>
                        <div class="form-actions" align="center">
                            <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
                        </div>
                        <?php echo $this->Form->end();?>
                    </div>
                    <div class="table-responsive" id="listingDiv">

                    </div>                     
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'discharges','action'=>'DischargeEscapeAjax'));

echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
        
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        var uuid  = '".$uuid."';
        url = url + '/date_of_escape:'+$('#date_of_escape').val();
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }

    

",array('inline'=>false));
?>