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
                    <?php if($this->Session->read('Auth.User.usertype_id')==$user_type3){?>
                        <?php /* ?>
                    <!--  <div class="control-group">
                        <label class="control-label">Prohibited property <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                        <div class="controls uradioBtn">
                           <?php 
                           //$Prohibited = array('Allowed'=>'Allowed items','Prohibited'=>'Prohibited items');
                           //echo $this->Form->radio('PhysicalPropertyItem.is_provided', $Prohibited,array("legend"=>false,'class'=>'verification_type radio','onclick'=>'getPropertyType(this.value)'));?>
                           <div style="clear:both;"></div>
                            <div class="error-message" id="verification_message_err" style="display:none;">Verification type is required !</div>
                        </div>
                    </div> 
                    <div class="control-group allowed" style="display:none;">
                        <label class="control-label">Property Type:</label>
                        <div class="controls">
                            <?php 
                            // $nokList=array('In Use' => 'In Use','In Store' => 'In Store');
                            // echo $this->Form->input('PhysicalPropertyItem.property_type',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$nokList, 'empty'=>'-- Select property type --','required'=>true));?>
                        </div>
                    </div>  
                    <div class="control-group prohibited" style="display:none;">
                        <label class="control-label">Property Type:</label>
                        <div class="controls">
                            <?php 
                            // $nokList1=array('Destroy' => 'Destroyed','In Store' => 'In Store');
                            // echo $this->Form->input('PhysicalPropertyItem.property_type_prohibited',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$nokList1, 'empty'=>'-- Select property type --','required'=>true,'onchange'=>'getwitness(this.value)'));?>
                        </div>
                    </div>  
                    <div class="control-group destroyed" style="display:none;">
                        <label class="control-label">Witness Name:</label>
                        <div class="controls">
                            <?php 
                            // echo $this->Form->input('PhysicalPropertyItem.property_witness',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$witnessList,'multiple'=>'multiple', 'empty'=>'-- Select witness --','required'=>true,'hiddenField'=>false));?>
                        </div>
                    </div> 
                    <div class="control-group destroyed" style="display:none;">
                            <label class="control-label">Date Time<?php echo $req; ?> :</label>
                            <div class="controls">
                            <?php
                            // $property_date_time= date('d-m-Y H:i');
                            // if(isset($this->data["PhysicalPropertyItem"]["destroy_date"])){
                            //         $check_up_date=date("d-m-Y H:i", strtotime($this->data["PhysicalPropertyItem"]["destroy_date"]));
                                    
                            //     }
                            ?>
                            
                            <?php 
                            //echo $this->Form->input('PhysicalPropertyItem.destroy_date',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Date Time','required','readonly'=>'readonly','id'=>'destroy_date','value'=>$property_date_time));?>
                            </div>
                    </div> 
                    <div class="control-group destroyed" style="display:none;">
                            <label class="control-label">Upload Pciture Destroyed Item:</label>
                            <div class="controls">
                            <?php //echo $this->Form->input('PhysicalPropertyItem.photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'photo','data-id'=>'0', 'onchange'=>'readURL(this);', 'required'=>false));?>
                            </div>
                            <div id='"prevImage_0' class="">
                            <?php //$is_photo = '';
                                if(isset($this->request->data["PhysicalPropertyItem"]["photo"]))
                                {
                                    $is_photo = 1;
                                    ?>
                                   <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/physicalitems/<?php echo $this->request->data["PhysicalPropertyItem"]["photo"];?>" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/physicalitems/<?php echo $this->request->data["PhysicalPropertyItem"]["photo"];?>" alt="" width="150px" height="150px"></a>
                                <?php }?>
                            </div>
                            <span id="previewPane" class="img_preview_panel">
                                <a class="example-image-link prevImage_0" href="" data-lightbox="example-set"><img id="img_prev_0" src="#" class="img_prev_0" alt="" /></a>
                                <span id="x" class="remove_img">[X]</span>
                            </span>
                    </div>
                    <div class="control-group destroyed" style="display:none;">
                        <label class="control-label">Mode of Destruction <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                        <div class="controls">
                           <?php echo $this->Form->input('destruction_mode',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Mode of Destruction','id'=>'destruction_mode','rows'=>3,'required'=>false));?>
                        </div>
                    </div> --><?php */ ?>
                    <?php }?>
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
function getPropertyType(val){
if(val=='Allowed'){
    $('.allowed').show();
    $('.prohibited').hide();
    $('.destroyed').hide();
    $('.allowed input').attr("required","required");
    $('.allowed select').attr("required","required");
    $('.prohibited input').removeAttr("required");
    $('.prohibited select').removeAttr("required");
    $('.destroyed input').removeAttr("required");
    $('.destroyed select').removeAttr("required");
}else if(val=='Prohibited'){
    $('.allowed').hide();
    $('.prohibited').show();
    $('.allowed input').removeAttr("required");
    $('.allowed select').removeAttr("required");
    $('.prohibited input').attr("required","required");
    $('.prohibited select').attr("required","required");
    $('.destroyed input').removeAttr("required");
    $('.destroyed select').removeAttr("required");
    $('.destroyed').hide();
}else{
    $('.allowed').hide();
    $('.prohibited').hide();
    $('.destroyed').hide();
    $('.allowed select').removeAttr("required");
    $('.allowed input').removeAttr("required");
    $('.prohibited input').removeAttr("required");
    $('.prohibited select').removeAttr("required");
    $('.destroyed input').removeAttr("required");
    $('.destroyed select').removeAttr("required");
}
}
function getwitness(val){
    if(val=='Destroy'){
        $('.destroyed').show();
        $('.destroyed input').attr("required","required");
        $('.destroyed select').attr("required","required");
    }else{
        $('.destroyed').hide();
        $('.destroyed input').removeAttr("required");
        $('.destroyed select').removeAttr("required");
    }
}
function readURL(input) {
   // var dataId = $(this).attr('data-id');
   // alert(dataId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img_prev_0')
            .attr('src', e.target.result)
            .width(100);
            $('#img_prev_0').closest('.prevImage_0').attr('href', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
    else {
      var img = input.value;
        $('#img_prev_0').attr('src',img).width(100);
    }
    $('#prevImage_0').hide();
    $('#img_prev_0').show();
    $("#x").show().css("margin-right","10px");
}
  $("#x").click(function() {
    $('#photo').val("");
    $("#img_prev_0").attr("src",'');
    $('#img_prev_0').hide();
    $("#x").hide();  
    $('span.filename').html('');
    $('#prevImage_0').show();
  });
</script>