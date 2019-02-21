<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Biometrics Map</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Biometrics List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('BiometricMap',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Biometric Id<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php 
                                        $static = array('one'=>'partha', 'two'=>'sarathi');
                                    echo $this->Form->input('biometric_id',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'form-control','required', 'empty'=>'--Select Biometric Id--','options'=>$static));?>
                                </div>
                            </div>
                        </div>                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">User<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                   <?php
                                  //$sataic_user = array('two' =>'admin' , 'four' => 'superamdin'); 
                                   echo $this->Form->input('usertype_id',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'form-control','required','empty'=>'--Select User--', 'options' => $userList));?>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Prison</label>
                                <div class="controls">
                                   <?php 
                                   //$static_prisoon = array('1' => '5', '2' => '6');

                                   echo $this->Form->input('prison_id',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'form-control','required','options'=>$prisonsList, 'empty'=>'--Select Prison--'));?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
 <script type="text/javascript">
  $(function(){
    $("#DistrictAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[District][name]': {
                    required: true,
                },
                'data[District][name]': {
                    required: true,
                },
           },
            messages: {
                'data[District][name]': {
                    required: "This Field is Required.",
                },
                'data[District][state_id]': {
                    required: "This Field is Required.",
                },
            },
               
    });
  });
  </script> 
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
