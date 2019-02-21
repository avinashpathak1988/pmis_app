<div id="verify" class="modal fade verifyPopupModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                <h4 class="modal-title">Verification Process</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="padding-bottom: 14px;">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Verification Type<?php echo $req; ?> :</label>
                            <div class="controls uradioBtn">
                                <?php 
                                $verification_type = array();
                                $default = array();
                                $currentController = $this->params['controller'];
                                $user_type1 = Configure::read('RECEPTIONIST_USERTYPE');
                                $user_type2 = Configure::read('PRINCIPALOFFICER_USERTYPE');
                                $user_type3 = Configure::read('OFFICERINCHARGE_USERTYPE');
                                $user_type4 = '';
                                if($currentController == 'MedicalRecords' || $currentController == 'medicalRecords' || $currentController == 'RecordFood' || $currentController == 'recordFood')
                                {
                                    $user_type1 = Configure::read('MEDICALOFFICE_USERTYPE');
                                    $user_type2 = Configure::read('OFFICERINCHARGE_USERTYPE');
                                    $user_type3 = Configure::read('COMMISSIONERGENERAL_USERTYPE');
                                }
                                if($currentController == 'InPrisonOffenceCapture')
                                {
                                    $user_type4 = Configure::read('COMMISSIONERGENERAL_USERTYPE');
                                }
                                if($this->Session->read('Auth.User.usertype_id')==$user_type2){
                                    $verification_type = array('Reviewed'=>'Reviewed','Review-Rejected'=>'Reject');
                                    $default = array("default"=>"Reviewed");
                                }
                                if($this->Session->read('Auth.User.usertype_id')==$user_type3){
                                    $verification_type = array('Approved'=>'Approve','Approve-Rejected'=>'Reject');    
                                    $default = array("default"=>"Approved");
                                }
                                if($this->Session->read('Auth.User.usertype_id')==$user_type4){
                                    $verification_type = array('Final-Approved'=>'Approve','Final-Rejected'=>'Reject');    
                                    $default = array("default"=>"Final-Approved");
                                }
                                //echo $this->Form->radio('type', $verification_type,array("legend"=>false,'class'=>'verification_type radio')+$default, 'onclick'=>'checkVerifyType(this.value);'); 
                                echo $this->Form->radio('type', $verification_type,array("legend"=>false,'class'=>'verification_type radio', 'onclick'=>'checkVerifyType(this.value);')+$default);
                                ?>
                                <div style="clear:both;"></div>
                                <div class="error-message" id="verification_type_err" style="display:none;">Verification type is required !</div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Remark <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                        <div class="controls uradioBtn">
                           <?php echo $this->Form->input('remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Remark','id'=>'remark','rows'=>3,'required'=>false));?>
                           <div style="clear:both;"></div>
                            <div class="error-message" id="verification_message_err" style="display:none;">Verification type is required !</div>
                        </div>
                    </div>         
                </div>
                <?php if($this->Session->read('Auth.User.usertype_id')==$user_type2){?>
                <hr>
                <div class="row" style="padding: 0px 0px 0px 60px;">
                <h5>Prisons Form 20</h5>
                
                <div class="span12">
                        <div class="control-group">
                            <label class="control-label">  Whether friends are able and willing to receive and support the prisoner if discharged :<?php echo $req; ?> </label>
                            <div class="controls">
                               <?php echo $this->Form->input('MedicalReleasepf.prisoner_supporter',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control','required'));?>
                            </div>
                        </div>
                </div>
                  <div class="span12">
                        <div class="control-group">
                            <label class="control-label">The prisoner’s own wishes :<?php echo $req; ?> </label>
                            <div class="controls">
                                <?php echo $this->Form->input('MedicalReleasepf.prisoner_wishes',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text', 'placeholder'=>'Prisoner’s own wishes','required',));?>
                            </div>
                        </div>
                    </div>                             
                    
                    <div class="clearfix"></div> 
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label"> Whether or not it is possible that the prisoner will again engage in crime :<?php echo $req; ?> </label>
                            <div class="controls">
                                <?php echo $this->Form->input('MedicalReleasepf.prisoner_crime',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'));?>
                            </div>
                        </div>
                    </div>
                     <div class="span12">
                        <div class="control-group">
                            <label class="control-label"> Whether in case of prisoner being without home or friends, there is any hospital or other suitable institution to which prisoner could be removed :</label>
                            <div class="controls">
                               <?php echo $this->Form->input('MedicalReleasepf.prisoner_relocation',array('div'=>false,'label'=>false,'type'=>'text','class'=>'form-control','required'));?>
                            </div>
                        </div>
                    </div>
                                                        
                    <div class="clearfix"></div>
                    
                </div>
                <?php }?>
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
function checkVerifyType(val)
{
    // alert(val);
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