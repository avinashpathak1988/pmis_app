<?php echo $this->Form->create('User');?>
<div class="control-group normal_text"> 
    <h3><img src="<?php echo $this->webroot; ?>logo.png" alt="Logo" /></h3>
</div>
<div class="control-group">
    <div class="controls">
        <div class="main_input_box">
            <span class="add-on bg_lg"><i class="icon-user"> </i></span>
            <?php echo $this->Form->input('login_id',array('div'=>false,'label'=>false,'required','placeholder'=>'Enter Login ID','type'=>'text'));?>
        </div>
    </div>
</div>
<div class="control-group">
    <div class="controls">
        <div class="main_input_box">
            <span class="add-on bg_ly"><i class="icon-lock"></i></span>
            <?php echo $this->Form->input('password',array('div'=>false,'label'=>false,'required','placeholder'=>'Password'));?>
        </div>
    </div>
</div>
<div class="form-actions">
    <span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">Lost password?</a></span>
    <span class="pull-right">
        <button type="submit" class="btn btn-success">Login</submit>
    </span>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Form->create('recoverform', array('id'=>'recoverform'));?>
    <p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>
    <div class="controls">
        <div class="main_input_box">
            <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address" />
        </div>
    </div>
    <div class="form-actions">
        <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; Back to login</a></span>
        <span class="pull-right"><a class="btn btn-info"/>Reecover</a></span>
    </div>
<?php echo $this->Form->end(); ?>