
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Manage Social Programs</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Manage Social Programs',array('action'=>'index'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <?php 
                //debug($this->request->data);
                if(isset($this->data['SocialProgram']['program_no'])){
                    echo $pno;
                    $pno == $this->data['SocialProgram']['program_no'];
                }
                ?>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('SocialProgram',array('class'=>'form-horizontal'
                      ));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="control-group">
                        <label class="control-label">Program Number<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('program_no',array('div'=>false,'label'=>false,'placeholder'=>'Enter Program No.','class'=>'form-control','value'=>$pno,'readonly'));?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Program Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('program_name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Program Name','class'=>'form-control'));?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Start Date<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('start_date',array('div'=>false,'label'=>false,'placeholder'=>'Enter Start Date','type'=>'text','class'=>'form-control from_date','readonly'=>true));?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">End Date<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('end_date',array('div'=>false,'label'=>false,'placeholder'=>'Enter End Date ','type'=>'text','class'=>'form-control to_date','readonly'=>true));?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Comment<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('comment',array('div'=>false,'label'=>false,'placeholder'=>'Write Your Comment Here','class'=>'form-control'));?>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Social Program Level ID<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                    <?php echo $this->Form->input('program_level_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','id'=>'program_level_id','options'=>$List,'empty'=>''));?>
                                    </div>
                            </div>

                            <div class="control-group">
                        <label class="control-label">Social Program Cat. ID<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                    <?php echo $this->Form->input('program_category_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','id'=>'program_category_id','options'=>$Listing,'empty'=>''));?>
                                    </div>
                            </div>

                    <div class="control-group">
                        <label class="control-label">Is Enabled ?</label>
                        <div class="controls">
                            <?php echo $this->Form->input('is_enable',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','required','options'=>array(0=>'No', 1=>'Yes'),'default'=>1));?>
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
    $('.from_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true,
        
                                                   
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('.to_date').datepicker('setStartDate', minDate);
         $(this).datepicker('hide');
         $(this).blur();
    });
    $('.to_date').datepicker({
        format: 'dd-mm-yyyy',
        autoclose:true,
        
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('.from_date').datepicker('setEndDate', minDate);
         $(this).datepicker('hide');
         $(this).blur();
    });
    $("#SocialProgramAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[SocialProgram][program_no]': {
                    required: true,
                },
               'data[SocialProgram][program_name]': {
                    required: true,
                },
                'data[SocialProgram][start_date]': {
                    required: true,
                },
                'data[SocialProgram][end_date]': {
                    required: true,
                },
                'data[SocialProgram][comment]': {
                    required: true,
                },
                'data[SocialProgram][program_level_id]': {
                    required: true,
                },
                'data[SocialProgram][program_category_id]': {
                    required: true
                }

            },
            messages: {
                'data[SocialProgram][program_no]': {
                    required: "This Field is Required.",
                },
                'data[SocialProgram][program_name]': {
                    required: "This Field is Required.",
                },
                'data[SocialProgram][start_date]': {
                    required: "This Field is Required.",
                },
               'data[SocialProgram][end_date]': {
                    required: "This Field is Required.",
                },
                'data[SocialProgram][comment]': {
                    required: "This Field is Required.",
                },
                'data[SocialProgram][program_level_id]': {
                    required: "This Field is Required.",
                },
               'data[SocialProgram][program_category_id]': {
                    required: "This Field is Required."
                }
        
            }
               
    });
  });
  </script>
  