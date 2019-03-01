<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Manage School Program</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Manage School Program',array('action'=>'index'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('SchoolProgram',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <!-- <div class="control-group">
                        <label class="control-label">Parent</label>
                        <div class="controls">
                                    <?php echo $this->Form->input('parent_id',array('div'=>false,'label'=>false,'class'=>'form-control validate','id'=>'parent_id','options'=>$parent,'onchange' => 'Javascript:getdata()','empty' => '--Select--'));?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Sub-Parent</label>
                        <div class="controls">
                                    <?php echo $this->Form->input('sub_parent_id',array('div'=>false,'label'=>false,'class'=>'form-control validate','id'=>'sub_parent_id','options'=>$sub_parent,'empty' => '--Select--'));?>
                        </div>
                    </div> -->
                    <div class="control-group">
                        <label class="control-label">School Program<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter School Program','class'=>'form-control','required'));?>
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
    $("#SchoolProgramAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[SchoolProgram][name]': {
                    required: true,
                },
                
            },
            messages: {
                'data[SchoolProgram][name]': {
                    required: "This Field is Required.",
                },
               
        
            },
               
    });
  });
  </script>