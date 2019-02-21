<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add Non Formal Program Module</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Program Module List',array('action'=>'programModule'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('NonFormalProgramModule',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden','value'=>count($subSchoolProgram)>0? $subSchoolProgram['NonFormalProgramModule']['id'] : ''))?>
                    <div class="control-group">
                        <label class="control-label">Parent</label>
                        <div class="controls">
                                    <?php echo $this->Form->input('program_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','id'=>'school_program_id','options'=>$parent,'empty' => '','value'=>count($subSchoolProgram)>0? $subSchoolProgram['NonFormalProgramModule']['program_id'] : ''));?>
                        </div>
                    </div>
                   
                    <div class="control-group">
                        <label class="control-label">Program Module Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter School Program','class'=>'form-control','required','value'=>count($subSchoolProgram)>0? $subSchoolProgram['NonFormalProgramModule']['name'] : ''));?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Is Enabled ?</label>
                        <div class="controls">
                            <?php echo $this->Form->input('is_enable',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','required','options'=>array(0=>'No', 1=>'Yes'),'default'=>1,));?>
                        </div>
                    </div>
                    <div class="form-actions" align="center">
                        <button type="button" formnovalidate="true" onclick="savesubcategory();" class="btn btn-success">Save</button>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$savemoduleurl = $this->Html->url(array('controller'=>'NonFormalPrograms','action'=>'addModuleSubmit')); ?>
  
<script type="text/javascript">
  $(function(){
    $("#NonFormalProgramModuleAddProgramModuleForm").validate({
     
      ignore: "",
            rules: {  
                'data[NonFormalProgramModule][name]': {
                    required: true,
                },
                
            },
            messages: {
                'data[NonFormalProgramModule][name]': {
                    required: "This Field is Required.",
                },
               
        
            },
               
    });
  });

        
        
  function savesubcategory(){
    //alert('here');
  $("#NonFormalProgramModuleAddProgramModuleForm").submit();
    if($("#NonFormalProgramModuleAddProgramModuleForm").valid()){
                var url = '<?php echo $savemoduleurl;?>';
                formData = $("#NonFormalProgramModuleAddProgramModuleForm").serialize();
                $.post(url,formData, function(res) {
                   // alert(res);
                   window.location = <?php echo $this->webroot; ?>+ 'NonFormalPrograms/programModule';

                });   
        }
    
    }
  </script>