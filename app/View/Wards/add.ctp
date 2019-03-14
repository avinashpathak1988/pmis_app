<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Manage Ward</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Ward List',array('action'=>'index'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Ward',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                     <label class="control-label">Prison Station Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('prison',array('div'=>false,'label'=>false,'placeholder'=>'Enter Ward','class'=>'span11 pmis_select','required','empty'=>'','options'=>$prisonlist));?>
                        </div>
                        <br>
                         <label class="control-label">Gender:</label>
                        <div class="controls">
                            <?php echo $this->Form->input('gender',array('div'=>false,'label'=>false,'placeholder'=>'Enter Gender','class'=>'span11 pmis_select','required'=> false,'empty'=>'','options'=>$genderList));?>
                        </div>
                        <br>
                    <div class="control-group">
                        <label class="control-label">Ward Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Ward','class'=>'form-control','required'));?>
                        </div>
                        <label class="control-label">Ward No<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('ward_no',array('div'=>false,'label'=>false,'placeholder'=>'Enter Ward No','class'=>'form-control','required'));?>
                        </div>
                        <label class="control-label">Ward Capacity<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('ward_capacity',array('div'=>false,'label'=>false,'placeholder'=>'Enter Ward Capacity','class'=>'form-control','required'));?>
                        </div>
                        <label class="control-label">Ward Type<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('ward_type',array('div'=>false,'label'=>false,'placeholder'=>'Enter Ward','class'=>'span11 pmis_select','required','empty'=>'','options'=>$wardlist));?>
                        </div>
                        
                        
                    </div>
                    <div class="control-group">
                        <label class="control-label">Is Enabled ?</label>
                        <div class="controls">
                            <?php echo $this->Form->input('is_enable',array('div'=>false,'label'=>false,'class'=>'form-control','required','options'=>$is_enables,'default'=>1,));?>
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
    $("#WardAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Ward][name]': {
                    required: true,
                },
                
            },
            messages: {
                'data[Ward][name]': {
                    required: "Please enter ward.",
                },
            },
               
    });
  });
  </script>