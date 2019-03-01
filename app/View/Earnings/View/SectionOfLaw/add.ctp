<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Section Of Law</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('SectionOfLaws'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('SectionOfLaw',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Offence<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('offence_id',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'span11 pmis_select','required','options'=>$offenceList, 'empty'=>''));?>
                                </div>
                            </div>
                        </div>                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Section Of Law<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Section Of Law','class'=>'form-control','required'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Is Enabled ?</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('is_enable',array('div'=>false,'label'=>false,'class'=>'form-control','required','options'=>array(0=>'No', 1=>'Yes'),'default'=>1,));?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <script type="text/javascript">
function validateForm(){
    var errcount = 0;
    $('.validate').each(function(){
        if($(this).val() == ''){
            errcount++;
            $(this).addClass('error-text');
            $(this).removeClass('success-text'); 
        }else{
            $(this).removeClass('error-text');
            $(this).addClass('success-text'); 
        }        
    });        
    if(errcount == 0){            
        if(confirm('Are you sure want to save?')){  
            return true;            
        }else{               
            return false;           
        }        
    }else{   
        return false;
    }  
}
</script> -->
<script type="text/javascript">
$(function(){
    $("#SectionOfLawAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[SectionOfLaw][offence_id]': {
                    required: true,
                },
               'data[SectionOfLaw][name]': {
                    required: true,
                },
           },
            messages: {
                'data[SectionOfLaw][offence_id]': {
                    required: "This Field is Required.",
                },
                'data[SectionOfLaw][name]': {
                    required: "This Field is Required.",
                },
            },
               
    });
  });
</script>
