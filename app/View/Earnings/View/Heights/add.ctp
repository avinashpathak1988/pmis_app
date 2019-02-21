<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Manage Height</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Manage Height',array('action'=>'index'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Height',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="control-group">
                        <label class="control-label">Height<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Height','class'=>'form-control','required'));?>
                        </div>
                        <label class="control-label">Height Type<?php echo MANDATORY; ?> :</label>
                        <div class="controls uradioBtn">
                            <?php 
                            $options=array('Centimetre'=>'Centimetre');//,'Inch'=>'Inch'
                            $attributes=array('legend'=>false);
                            echo $this->Form->radio('height_type',$options,$attributes);?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Is Enabled ?</label>
                        <div class="controls">
                            <?php echo $this->Form->checkbox('is_enable',array('div'=>false,'label'=>false,'class'=>'form-control','required','options'=>$is_enables,'default'=>1,));?>
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
    $("#HeightAddForm").validate({
     
      ignore: "",
            rules: {  
               'data[Height][name]': {
                    required: true,
                },
           },
            messages: {
                'data[Height][name]': {
                    required: "This Field is Required.",
                },
            },
               
    });
  });
</script>