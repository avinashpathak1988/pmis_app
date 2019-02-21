<div id="child-release" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                <h4 class="modal-title">Update Status</h4>
            </div>
            <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
            <?php echo $this->Form->input('id',array("type"=>"hidden","id"=>"child_detail_id"));?>
            <div class="modal-body">
                <div class="row" style="padding:0 0 0 28px">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Status <?php echo $req; ?> :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('judgment_status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Cause List --','options'=>array("Plea taking"=>"Plea taking","Cross Examination"=>"Cross Examination","Ruling"=>"Ruling","Defense"=>"Defense","Re-Examination"=>"Re-Examination"), 'class'=>'form-control','required','title'=>'Please select judgment status'));?>
                            </div>

                        </div>
                    </div>
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Judgment.<?php echo $req; ?> :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('judgment',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Cause List --','options'=>array("Convicted"=>"Convicted","Acquitted"=>"Acquitted"), 'class'=>'form-control','required','title'=>'Please select judgment'));?>
                            </div>
                        </div>
                    </div>             
                </div>
                <div class="form-actions" align="center" style="background:#fff;">
                    <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','id'=>'verifyBtn'))?>
                </div>
            </div>
            <?php echo $this->Form->end();?>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\,\.\s]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");
     
    $("#CourtattendanceIndexAjaxForm").validate({
        ignore: "",
        
    });    
});
</script>