<!-- Verify Modal START -->
<div id="oic-verify" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                <h4 class="modal-title">Prisoner Verification</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Form->create('VerifyAdmittedPrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/verifyPrisoner','id'=>'verifyPrisoner'));?>
                <?php echo $this->Form->input('verify_id', array('type'=>'hidden','id'=>'oicVerifyId','value'=>0))?>
                <?php echo $this->Form->input('uuid',array('type'=>'hidden','id'=>'prisoner_uuid'));?>
                <div class="row" style="padding-bottom: 14px;">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Verification Type<?php echo $req; ?> :</label>
                            <div class="controls uradioBtn">
                            
                                <?php 

                                $verification_type = array();
                                $default = array();
                                
                                $verification_type = array('verify'=>'Verified','reject'=>'Reject');
                                $default = array("default"=>"verify");
                                
                                echo $this->Form->radio('type', $verification_type,array("legend"=>false,'class'=>'verification_type')+$default); 
                                ?>
                                <div style="clear:both;"></div>
                                <div class="error-message" id="verification_type_err" style="display:none;">Verification type is required !</div>
                            </div>
                        </div>
                    </div>
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Remark :</label>
                            <div class="controls uradioBtn">
                               <?php echo $this->Form->input('verify_remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Remark','id'=>'oic_verify_remark','rows'=>3,'required'=>false));?>
                               <div style="clear:both;"></div>
                                <div class="error-message" id="oic_verification_message_err" style="display:none;">Verification type is required !</div>
                            </div>
                        </div>
                    </div>                
                </div>
                <div class="form-actions" align="center" style="background:#fff;">
                    <?php echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','id'=>'oicVerifyBtn',"onclick"=>"verifyAdmittedPrisoner()"))?>
                </div>
                <?php echo $this->Form->end();?>
            </div>
        </div>
    </div>
</div>                       
<!-- Verify Modal END -->