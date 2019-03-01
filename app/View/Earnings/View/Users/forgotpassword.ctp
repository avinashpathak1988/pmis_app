<?php echo $this->Form->create('User',array('class'=>'form center-block')); ?>

<div class="control-group normal_text"> 
    <h3>Forgot Password</h3>
</div>
<div class="control-group">
    <div class="controls">
        <div class="main_input_box">
            <span class="add-on bg_lg"><i class="icon-envelope"> </i></span>
            <?php echo $this->Form->input('mail_id',array('div'=>false,'label'=>false,'required','placeholder'=>'Enter Account Email Id','type'=>'text','required'=>'required', 'id'=>'email_id', 'autocomplete'=>'off'));?>
        </div>
    </div>
</div>

<div class="form-actions">
    <span class="pull-right">
        <?php echo $this->Form->button('Submit',array('type'=>'submit','class'=>"btn btn-success",'div'=>false,'label'=>false,'formnovalidate'=>true))?>
    </span>
    <span class="pull-left">
        <?php  echo $this->Html->link(
    'Login Here?',
    '/users/'
    
);?>
    </span>
</div>
<?php echo $this->Form->end(); ?>
