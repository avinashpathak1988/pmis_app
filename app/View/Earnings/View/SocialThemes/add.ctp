<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Manage Social Theme</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Manage Social Themes',array('action'=>'index'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('SocialTheme',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="control-group">
                        <label class="control-label">Social Theme<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Social Theme','class'=>'form-control','required'));?>
                        </div>
                         <!-- <label class="control-label">Theme Type<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('type',array('div'=>false,'label'=>false,'placeholder'=>'Enter Social Theme type','class'=>'form-control','required'));?>
                        </div> -->
                         <label class="control-label">Theme Type  :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('type',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','id'=>'theme_type','options'=>$themeTypes,'empty'=>'','required'=>false));?>
                                    </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Is Enabled ?</label>
                        <div class="controls">
                            <?php echo $this->Form->input('is_enable',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','required','options'=>array(0=>'No', 1=>'Yes'),'default'=>1,));?>
                        </div>
                    </div>
                    <div class="form-actions" align="center">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(function(){
    $("#SocialThemeAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[SocialTheme][name]': {
                    required: true,
                },
                 'data[SocialTheme][type]': {
                    required: true,
                },
            },
            messages: {
                'data[SocialTheme][name]': {
                    required: "This Field is Required.",
                },
                'data[SocialTheme][type]': {
                    required: "This Field is Required.",
                },
        
            },
               
    });
  });
  </script>