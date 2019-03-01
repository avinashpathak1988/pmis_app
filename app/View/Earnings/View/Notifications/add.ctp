<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add Notification</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Notification List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Notification',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Users<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('user_id',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'form-control','required','options'=>$prisonList, 'empty'=>'--Select User--'));?>
                                </div>
                            </div>
                        </div>                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">URL:<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('url_link',array('div'=>false,'label'=>false,'placeholder'=>'Enter URL','class'=>'form-control','required'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Content:<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('content',array('div'=>false,'label'=>false,'placeholder'=>'Enter Contents','class'=>'form-control','required'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Is Read?</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('is_read',array('div'=>false,'label'=>false,'class'=>'form-control','required','options'=>array(0=>'No', 1=>'Yes'),'default'=>1,));?>
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
    $("#NotificationAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Notification][content]': {
                    required: true,
                },
                'data[Notification][content]': {
                    required: true,
                },
           },
            messages: {
                'data[Notification][content]': {
                    required: "This Field is Required.",
                },
                'data[Notification][user_id]': {
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
