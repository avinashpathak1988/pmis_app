<div id="child-release" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                <h4 class="modal-title">Release Process</h4>
            </div>
            <?php echo $this->Form->create('PrisonerChildDetail',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
            <?php echo $this->Form->input('id',array("type"=>"hidden","id"=>"child_detail_id"));?>
            <?php echo $this->Form->input('status',array("type"=>"hidden","value"=>"Draft"));?>
            <div class="modal-body">
                <div class="row" style="padding:0 0 0 28px">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Receive Person <?php echo $req; ?> :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('name_of_rcv_person',array('div'=>false,'label'=>false,'class'=>'form-control span11 alfa','type'=>'text','placeholder'=>"Receive Person",'required'=>true,'id'=>'name_of_rcv_person','title'=>"Please provide receive person name !"));?>
                            </div>

                        </div>
                    </div>
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Contact No.<?php echo $req; ?> :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('contact_no_of_rcv_person',array('div'=>false,'label'=>false,'class'=>'form-control span11 mobile','type'=>'text','placeholder'=>"Contact No",'required'=>true,'id'=>'contact_no_of_rcv_person',"title"=>"Please provide receive person contact no !"));?>
                            </div>
                        </div>
                    </div>
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Date Of Handover<?php echo $req; ?> :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('date_of_handover',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>"Handover Date",'required'=>true,'id'=>'date_of_handover',"readonly"=>true,"title"=>"Please select handover date"));?>
                            </div>
                        </div>
                    </div>
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Probation report <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,doc,docx,pdf) type photo!" id='example'></i>:</label>
                            <div class="controls">
                                <?php echo $this->Form->input('probation_report',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'probation_report', 'onchange'=>'readImage(this,"probation_report");','required'=>false));?>
                            </div>
                        </div>
                    </div>
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Handover Comment :</label>
                            <div class="controls uradioBtn">
                               <?php echo $this->Form->input('handover_comment',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Remark','id'=>'remark','rows'=>3,'required'=>true,"title"=>"Please provide handover comment"));?>
                               <div style="clear:both;"></div>
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
     
    $("#PrisonerChildDetailChildDetailAjaxForm").validate({
        ignore: "",
        rules: {
            'data[PrisonerChildDetail][handover_comment]': {
                loginRegex: true,
                maxlength: 250
            },
        },
        messages: {
            'data[PrisonerChildDetail][handover_comment]': {
                loginRegex: "remarks must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                maxlength: "Please enter no more than 255 characters.",
            },
        }, 
    });    
});
</script>