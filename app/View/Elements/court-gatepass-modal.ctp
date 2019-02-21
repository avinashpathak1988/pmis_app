<div id="verify" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                <h4 class="modal-title">Gatepass</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="padding-bottom: 14px;">
                    <div class="control-group">
                        <label class="control-label">Date<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('gp_date',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Select Date','readonly'=>'readonly','class'=>'form-control span11','required', 'id'=>'gp_date','value'=>date("d-m-Y")));?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Escort <?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('escort_team',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$teamList, 'empty'=>'-- Select Team --','required','title'=>'Please select escort team'));?>    
                        </div>
                    </div>
                    <div class="control-group" style="display: none;">
                        <label class="control-label">Permission is <br>granted for <?php echo $req; ?>:</label>
                        <div class="controls uradioBtn">
                           <?php echo $this->Form->input('permission_granted',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Permission is granted for','rows'=>3,'required'=>false,'value'=>'court attendence'));?>
                           <div style="clear:both;"></div>
                            <div class="error-message" id="verification_message_err" style="display:none;">Verification type is required !</div>
                        </div>
                    </div>
                    <div class="control-group" style="display: none;">
                        <label class="control-label">Purpose <?php echo $req; ?>:</label>
                        <div class="controls uradioBtn">
                           <?php echo $this->Form->input('purpose',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter purpose','rows'=>3,'required'=>false,'value'=>'court attendence'));?>
                           <div style="clear:both;"></div>
                            <div class="error-message" id="verification_message_err" style="display:none;">Verification type is required !</div>
                        </div>
                    </div>         
                </div>
                <div class="form-actions" align="center" style="background:#fff;">
                    <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','id'=>'verifyBtn'))?>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .controls.uradioBtn input {
        float: left;
    }
    .controls.uradioBtn label {
        margin-right: 10px;
    }
</style>
<script>
$('.mydate').datepicker({
    format: 'dd-mm-yyyy',
    autoclose:true
}).on('changeDate', function (ev) {
     $(this).datepicker('hide');
     $(this).blur();
});
function checkVerifyType(val)
{
    alert(val);
    if(val == 'Review-Rejected' || val == 'Approve-Rejected')
    {
        $('#remark').attr('required',true);
    }
    else 
    {
        $('#remark').attr('required',false);
    }
}
</script>