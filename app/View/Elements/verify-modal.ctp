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
                                $currentAction = $this->params['action'];
                                // debug($currentController);
                                // debug($currentAction);
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
                                if($currentController == 'ExtractPrisonersRecord'){
                                    $user_type4 = Configure::read('COMMISSIONERGENERAL_USERTYPE');
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
                                if($this->Session->read('Auth.User.usertype_id')==$user_type2 &&  $currentAction=='showMedicalSeriousIllRecords'){
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
                            
                    <?php 
                    //echo $currentController;
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $currentController=='propertyitems'){ ?>
                            <div class="control-group">
                                <label class="control-label">Prohibited property <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                <div class="controls uradioBtn">
                                   <?php 
                                   $Prohibited = array('Allowed'=>'Allowed items','Prohibited'=>'Prohibited items');
                                   echo $this->Form->radio('is_provided', $Prohibited,array("legend"=>false,'class'=>'verification_type radio','required'=>true,'onclick'=>'getPropertyType(this.value)'));?>
                                   <div style="clear:both;"></div>
                                    
                                </div>
                            </div>
                            <div class="control-group prohibited" style="display:none;">
                                <label class="control-label">Property Type:</label>
                                <div class="controls">
                                    <?php 
                                    $nokList1=array('Destroyed' => 'Destroyed','In Store' => 'In Store');
                                    echo $this->Form->input('property_type_prohibited',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$nokList1, 'empty'=>'-- Select property type --','required'=>false,'onchange'=>'getwitness(this.value)'));?>
                                </div>
                            </div> 
                        
                    <?php } ?>
                    
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
<script type="text/javascript">
    $(document).ready(function(){


        $('#PropertyitemIsAllowed').on('change',function(){
            var res = $(this).val();
            if(res == 1){
                $('#PropertyitemIsProhibited').val(0).change();

            }else{
                $('#PropertyitemIsProhibited').val(1).change();
            }
        });

        $('#PropertyitemIsProhibited').on('change',function(){
            var res = $(this).val();
            if(res == 1){
                $('#PropertyitemIsAllowed').val(0);
            }else{
                $('#PropertyitemIsAllowed').val(1);
            }
        });


    });

function getPropertyType(val){
    if(val=='Prohibited'){
        $('.prohibited').show();
        $('#PropertyitemPropertyTypeProhibited').attr('required','required');
      
    }else if(val=='Allowed'){
        $('.prohibited').hide();
        $('#PropertyitemPropertyTypeProhibited').removeAttr('required');

    }

}
</script>