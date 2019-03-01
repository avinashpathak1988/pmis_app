<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Holiday</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Holiday List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Holiday',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">     
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Description<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('description',array('div'=>false,'label'=>false,'placeholder'=>'Enter Description','class'=>'form-control','required'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Date<?php echo MANDATORY; ?> :</label>
                                 <div class="controls">
                                    <?php echo $this->Form->input('holiday_date', array('type'=>'text','class'=>'form-control holiday_date','id'=>'holiday_date','div'=>false,'label'=>false,'placeholder'=>'Select Date'))?>
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
    $("#HolidayAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Holiday][description]': {
                    required: true,
                },
				 'data[Holiday][holiday_date]': {
                    required: true,
                },
           },
            messages: {
                'data[Holiday][description]': {
                    required: "Description Field Required.",
                },
				 'data[Holiday][holiday_date]': {
                    required: "Date Field Required.",
                },
            },
               
    });
  });
  
$(document).ready(function(){
    $('.holiday_date').datepicker({ dateFormat: 'yy-mm-dd' });
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
</script>
 -->