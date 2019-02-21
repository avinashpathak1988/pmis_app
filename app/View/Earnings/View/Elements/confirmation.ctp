<?php //debug($this->data['Prisoner']['id']);
$appeal_file_no = $funcall->getAppealCaseFile($this->data['Prisoner']['id']);
?>
<div class="" style="padding-bottom: 14px;">
    <div class="row-fluid secondDiv widget-box" style="padding-bottom: 14px; background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Confirmation</h5>
        </div>
        <div class="widget-content">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">File no <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php 
                        echo $this->Form->input('case_file_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','onChange'=>'getAppealCount(this.value)','type'=>'select','options'=>$appeal_file_no, 'empty'=>'','required', 'title'=>'Case File is required.')); ?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Count <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$offenceIdList, 'empty'=>'','required'=>false));?>
                    </div>
                </div>
            </div> 
            <div class="clearfix"></div>
             <div class="row-fluid widget-box hidden" style="padding-bottom: 14px; background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;" id="confirmation_count_details">
                <div class="widget-content">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Case File No<?php echo $req; ?>:</label>
                            <div class="controls">
                                
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Offence<?php echo $req; ?>:</label>
                            
                        </div>
                    </div> 
                    <div class="clearfix"></div> 
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Sentence<?php echo $req; ?>:</label>
                            <div class="controls">
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="span6" id="submission_date_div">
                        <div class="control-group">
                            <label class="control-label">Date of Cofirmation <?php echo $req; ?>:</label>
                            <div class="controls">
                                <?php echo $this->Form->input('submission_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','readonly','type'=>'text', 'placeholder'=>'Date of Submission','required'=>false,'id'=>'submission_date', 'maxlength'=>'15', 'title'=>'Please select Date of Submission'));?>
                            </div>
                        </div>
                    </div>
           
           
            <!-- <div class="clearfix"></div>  -->
           
           
            <!-- Appeal status fields END  -->
        </div>
    </div>
</div>
<script>
$(function(){
    getAppealStatusFields($('#appeal_status').val());
});
function getAppealCount(id){
  var strURL = '<?php echo $this->Html->url(array('controller'=>'App','action'=>'getAppealCount'));?>/'+id;
  $.post(strURL,{},function(data){
      if(data) { 
          $('#PrisonerSentenceAppealOffenceId').html(data);
      }
      else
      {
          alert("Error...");  
      }
  });
}
function getAppealStatusFields(appeal_status)
{
    if(appeal_status != '' && appeal_status != 'Cause List')
    {
        $('#appeal_courtlevel_id').prop('required','required');
        $('#appeal_court_id').prop('required','required');
        $('#submission_date').prop('required','required');
        
        $('#appeal_no_div').show();
        $('#submission_date_div').show();
        if(appeal_status == 'Notes of appeal')
        {
            $('#appeal_no_div').val('');
            $('#appeal_no_div').hide();
            $('#submission_date').prop('required','required');
        }
        if(appeal_status == 'Pending Hearing of Appeal')
        {
            $('#submission_date_div').val('');
            $('#submission_date_div').hide();
            $('#submission_date').prop('required','');
        }
        $('.appealStatusDiv').removeClass('hidden');
    }
    else 
    {
        $('#appeal_courtlevel_id').prop('required','');
        $('#appeal_court_id').prop('required','');
        $('#submission_date').prop('required','');
        $('.appealStatusDiv').addClass('hidden');
    }
}
</script>