<div class="container-fluid">
    <div class="row-fluid">
    	
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Manage Sub School Program</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(' Sub School Program list',array('action'=>'subcategory'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('SubSchoolProgram',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden','value'=>count($subSchoolProgram)>0? $subSchoolProgram['SubSchoolProgram']['id'] : ''))?>
                    <div class="control-group">
                        <label class="control-label">School Program</label>
                        <div class="controls">
                                    <?php echo $this->Form->input('school_program_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','id'=>'school_program_id','options'=>$parent,'empty' => '','value'=>count($subSchoolProgram)>0? $subSchoolProgram['SubSchoolProgram']['school_program_id'] : ''));?>
                        </div>
                    </div>
                   
                    <div class="control-group">
                        <label class="control-label">Sub Program Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter School Program','class'=>'form-control','required','value'=>count($subSchoolProgram)>0? $subSchoolProgram['SubSchoolProgram']['name'] : ''));?>
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
$savesubcategoryurl = $this->Html->url(array('controller'=>'SchoolPrograms','action'=>'addsubcategorysubmit')); ?>
  
<script type="text/javascript">
  $(function(){
    $("#SubSchoolProgramAddsubcategoryForm").validate({
     
      ignore: "",
            rules: {  
                'data[SubSchoolProgram][name]': {
                    required: true,
                },
                
            },
            messages: {
                'data[SubSchoolProgram][name]': {
                    required: "This Field is Required.",
                },
               
        
            },
               
    });
  });

        
        
  function savesubcategory(){
    //alert('here');
  $("#SubSchoolProgramAddsubcategoryForm").submit();
    if($("#SubSchoolProgramAddsubcategoryForm").valid()){
                var url = '<?php echo $savesubcategoryurl;?>';
                formData = $("#SubSchoolProgramAddsubcategoryForm").serialize();
                $.post(url,formData, function(res) {
                   // alert(res);
                   window.location = <?php echo $this->webroot; ?> + 'SchoolPrograms/subcategory';
                });   
        }
    
    }
  </script>