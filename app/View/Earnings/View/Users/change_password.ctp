<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- <div class="header"><h4 class="title">Change Password</h4></div> -->
                <div class="content">
                <?php echo $this->Form->create('User');?>
                <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Old Password</label><?php echo $req?>
                                <?php echo $this->Form->input('old_password',array('div'=>false,'label'=>false,'type'=>'password','id'=>'current_password','class'=>'form-control','placeholder'=>'Enter old password','maxlength'=>250,'onblur'=>'javascript:checkCurrentPassword(this.value);'));?>
                            </div>
                        </div>
                        <div class="col-sm-6" id="old_pass">

                        </div>
                    </div>
                    <div class="row">                   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>New Password</label><?php echo $req?>
                                <?php echo $this->Form->input('new_password',array('div'=>false,'label'=>false,'type'=>'password','id'=>'new_password','class'=>'form-control validate','placeholder'=>'Enter New Password','maxlength'=>250,'disabled'=>'disabled','onblur'=>'javascript:passwordComplexicity(this.value);'));?>
                            </div>
                        </div>
                        <div class="col-sm-6" id="pass_complex">

                        </div>                         
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirm Password</label><?php echo $req?>
                                <?php echo $this->Form->input('conf_password',array('div'=>false,'label'=>false,'type'=>'password','id'=>'confirmation_password','class'=>'form-control validate','placeholder'=>'Enter Confirm Password','maxlength'=>250,'disabled'=>'disabled','onblur'=>'javascript:compPassword(this.value);'));?>
                            </div>
                        </div>
                        <div class="col-sm-6" id="conf_pass_complex">

                        </div>                           
                    </div>
                    <?php echo $this->Form->button('Submit', array('type'=>'submit','id'=>'save_changes','class'=>'btn btn-info btn-fill pull-right','div'=>false,'label'=>false,'formnovalidate'=>true,'onclick'=>'javascript:return validateForm();','disabled'=>'disabled'))?>
                    <div class="clearfix"></div>
                <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function checkCurrentPassword(thisVal){
    if(thisVal){
        var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'checkCurrentPassword'))?>";
        $.post(url,{'cur_pass':thisVal},function(res){
            if(res.trim() == 'SUCC'){
                $('#new_password').attr('disabled',false);
                $('#new_password').focus(); 
                $('#old_pass').html('<span class="grn2">Correct Password</span>');
            }else{
                $('#old_pass').html('<span class="red2">Incorrect Password</span>');
                $('#new_password').attr('disabled',true);
                $('#confirmation_password').attr('disabled', true);
                $('#save_changes').attr('disabled', true);
                $('#current_password').focus();
            }   
        });            
    }else{
        $('#old_pass').html('');
        $('#new_password').attr('disabled', true);
        $('#confirmation_password').attr('disabled', true);
        $('#save_changes').attr('disabled', true);
        $('#current_password').focus();            
    }
}
function passwordComplexicity(thisVal){
    if(thisVal){
        var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'PasswordComplexicity'))?>";
        $.post(url,{'new_pass':thisVal},function(result){
            if(result == 'SUCC'){
                $('#pass_complex').html('<span class="grn2">Password is Strong</span>');
                $('#confirmation_password').attr('disabled',false);
                $('#confirmation_password').focus();                            
            }else if(result == 'MIN'){
                $('#pass_complex').html('<span class="red2">Password length should be greater than 8 charcters</span>');
                $('#confirmation_password').attr('disabled',true);
                $('#save_changes').attr('disabled', true);
                $('#new_password').focus();                            
            }else{
                $('#pass_complex').html('<span class="red2">Password is Weak.<br/>[Must contain Upper case,Lowercase,Numeric]</span>');
                $('#confirmation_password').attr('disabled',true);
                $('#save_changes').attr('disabled', true);
                $('#new_password').focus();                        
            }
        });
    }else{
        $('#confirmation_password').attr('disabled',true);
        $('#save_changes').attr('disabled', true);
        $('#new_password').focus();                 
    }
}
function compPassword(thisVal){
    if(thisVal != '' && $('#new_password').val() != ''){
        if(thisVal != $('#new_password').val()){
            $('#conf_pass_complex').html('<span class="red2">New Password & Confirm Password Mismatched</span>');
            $('#confirmation_password').val('');
            $('#confirmation_password').focus();                
        }else{
            $('#conf_pass_complex').html('');
            $('#save_changes').attr('disabled',false);
            $('#save_changes').focus();
        }
    }
}
function validateForm(){
    var errCnt = 0;
    $('.validate').each(function(){
        if($(this).val() == ''){
            $(this).addClass('error-field');
            errCnt++;
        }else{
            $(this).removeClass('error-field');
        }
    });
    if(errCnt == 0){
        return true;
    }else{
        return false;
    }
}
</script>