<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Record person In/out </h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Person in/out Record List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Personinout',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                       <?php
                                echo $this->Form->input('person_in_out_date',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'text',
                                  'class'=>'datepicker',
                                  'data-date-format'=>"dd-mm-yyyy",
                                  'readonly'=>'readonly',
                                  'required',
                                ));
                             ?>
                                    </div>
                                </div>
                            </div>     
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Name:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Name','class'=>'form-control','required'));?>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Time In<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('time_in',array('div'=>false,'label'=>false,'placeholder'=>'Enter Time In','type'=>'text','class'=>'datetimepicker form-control','readonly'=>'readonly','required'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Time Out<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('time_out',array('div'=>false,'label'=>false,'placeholder'=>'Enter Time  Out','type'=>'text','class'=>'datetimepicker form-control','readonly'=>'readonly' ,'required'));?>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Gate Keeper Name :</label>
                                <div class="controls">
                                   <?php echo $this->Form->input('gate_keeper_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$gateKeepers,'empty'=>'---Select Gate Keeper---','placeholder'=>'Select Gate Keeper','id'=>'first_name'));?>
                                </div>
                            </div>
                        </div>
                         <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Reason :</label>
                                <div class="controls">
                                    <?php echo $this->Form->textarea('reason',array('div'=>false,'label'=>false,'placeholder'=>'Enter Reason ','class'=>'form-control','required'));?>
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
<script type="text/javascript">

$(document).ready(function(){
    $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
   $('.datetimepicker').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss',today:'true'});
});

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
</script>
