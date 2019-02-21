<?php //ebug($appSalt); ?>
<?php echo $this->Form->create('User',array('class'=>'form center-block')); ?>
<?php echo $this->Form->input('appSalt',array('type'=>'hidden','id'=>'appSalt','label'=>false,'value' =>$appSalt,'div'=>false));?>
<?php
    $fist_value = rand(1,9);
    $sec_value  = rand(1,9);
    $sum        = (int)$fist_value + (int)$sec_value;
    $_SESSION['random_number'] = md5($sum);
?>
<?php echo $this->Form->input('hd',array('value'=>$_SESSION['random_number'],'type'=>'hidden')); ?>
<div class="control-group normal_text"> 
    <h3 style="visibility: hidden;">LOGIN</h3>
</div>
<h3 style="text-align: center;">LOGIN</h3>
<div class="control-group">
    <div class="controls">
        <div class="main_input_box">
            <span class="add-on bg_lg"><i class="icon-user"> </i></span>
            <?php echo $this->Form->input('username',array('div'=>false,'label'=>false,'required','placeholder'=>'Enter Username','type'=>'text', 'id'=>'username', 'autocomplete'=>'off'));?>
        </div>
    </div>
</div>
<div class="control-group">
    <div class="controls">
        <div class="main_input_box">
            <span class="add-on bg_ly"><i class="icon-lock"></i></span>
            <?php echo $this->Form->input('password',array('type'=>'password','required','placeholder'=>'Enter Password', 'autocomplete'=>'off','div'=>false,'label'=>false,'id'=>'UserPassword', 'value'=>'password'));?>
        </div>
    </div>
</div>
<div class="control-group">
    <div class="controls">
 
        <div class="main_input_box" id="captch_regen" style="width:30%; clear:both; float:left;" >
            <img src="<?php echo $this->webroot; ?>img/cap/<?php echo hash('ripemd160', $fist_value)?>.gif" alt="<?php echo hash('ripemd160', $fist_value)?>" height="20" width="18"/> 
            <img src="<?php echo $this->webroot; ?>img/cap/plus.png" alt="+" height="18" width="18" /> 
            <img src="<?php echo $this->webroot; ?>img/cap/<?php echo hash('ripemd160', $sec_value)?>.gif" align="<?php echo hash('ripemd160', $sec_value)?>" height="20" width="18"/>
        </div>
        <div class="refresh-log">
            <a id="refresh_capctcha" style="cursor:pointer" class="tip-bottom" data-original-title="Click here to regenerate captcha">
                <i class="icon-refresh" style="font-size:s20px; color:#ff9600;"></i>
            </a>
        </div>
  
    </div>
</div>
<div class="control-group">
    <div class="controls">
        <div class="main_input_box">
            <span class="add-on bg_ly"><i class="icon-lock"></i></span>
            <?php echo $this->Form->input('captcha',array('div'=>false, 'label'=>false, 'type'=>'text', 'required', 'placeholder'=>'Enter Captcha', 'style'=>'', 'id'=>'user_captcha', 'value'=>$sum));?>
        </div>
    </div>
</div>
<div class="form-actions">
    <span class="pull-right">
        <?php echo $this->Form->button('Login',array('type'=>'submit','class'=>"btn btn-success",'onclick'=>"javascript:return beforeLogin();",'div'=>false,'label'=>false,'formnovalidate'=>true))?>
    </span>
    <span class="pull-left">
        <?php  echo $this->Html->link(
    'Forgot Passsword?',
    '/users/forgotpassword'
    
);?>
    </span>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
function beforeLogin(){

        var enteredPwd = $("#UserPassword").val();
        var md5Pwd     = MD5(enteredPwd);
        var getSaltVal = $("#appSalt").val();
        var saltedPwd  = MD5(md5Pwd+getSaltVal);
        $("#UserPassword").val('');
        $("#appSalt").val(saltedPwd);
        return true;
}
$(document).on('click', '#refresh_capctcha', function(){
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo $this->Html->url(array('controller'=>'users','action'=>'captcharegenerate'));?>",
        data: {

        },
        cache: true,
        beforeSend: function(){  
            $('#captch_regen').html('<div style="text-align:center;"><i class="fa fa-spinner fa-spin" style="font-size: 43px;color: #0044CC"></i></div>');
        },
        success: function (data) {
            $('#captch_regen').html(data.captchavar);
            $('#UserHd').val(data.random_number);
            $('#user_captcha').val(data.result)               
        },
        error: function (errormessage) {
            alert(errormessage.responseText);
        }
    });
});
</script>