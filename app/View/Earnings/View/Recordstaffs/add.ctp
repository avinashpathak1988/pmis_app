<?php
if(isset($this->request->data['RecordStaff']['time_in']) && $this->request->data['RecordStaff']['time_in'] !=''){
  $time = $this->request->data['RecordStaff']['time_in'];
  //$timeout = $this->request->data['RecordStaff']['time_out'];
    $timeout = date("h:i:s");
}else{
  $time = date("h:i:s");
  $timeout = '';
}
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Record of staff </h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Staff Record List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('RecordStaff',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <?php echo $this->Form->input('prison_id', array('type'=>'hidden', 'value'=> $this->Session->read('Auth.User.prison_id')))?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date <?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                       <?php
                                       $datadate = date('d-m-Y');
                                          echo $this->Form->input('recorded_date',array(
                                            'div'=>false,
                                            'label'=>false,
                                            'type'=>'text',
                                            //'class'=>'datepicker',
                                            'data-date-format'=>"dd-mm-yyyy",
                                            'readonly'=>'readonly',
                                            'required',
                                            'value'=>$datadate
                                          ));
                                       ?>
                                    </div>
                                </div>
                            </div>     
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Force No <?php echo MANDATORY; ?>:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('force_no',array('div'=>false,'label'=>false,'placeholder'=>'Enter Force Number','class'=>'form-control','required'=>true,'title'=>"Please enter force number"));?>
                                    </div>
                                </div>
                            </div>
                        </div>
                      <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Time In <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php 
                                    echo $this->Form->input('time_in',array('div'=>false,'label'=>false,'placeholder'=>'Enter Time In','type'=>'text',
                                    'class'=>'form-control',
                                    //'class'=>'timepicker1 form-control',
                                    'readonly'=>'readonly',
                                    'required',
                                    'value'=>$time
                                    ));?>
                                </div>
                            </div>
                        </div>
                        <!--<div class="span6">
                            <div class="control-group">
                                <label class="control-label">Time Out :</label>
                                <div class="controls">
                                    <?php 
                                    // echo $this->Form->input('time_out',array('div'=>false,'label'=>false,'placeholder'=>'Enter Time  Out',
                                    //    'type'=>'text',
                                    //    //'class'=>'form-control',
                                    //    'class'=>'timepicker1 form-control',
                                    //    'readonly'=>'readonly',
                                    //    'value'=>$timeout
                                    //    ));
                                       ?>
                                </div>
                            </div>
                        </div>-->
                        </div>
                        <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Staff Category <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php
                                         echo $this->Form->input('staff_category_id',array(
                                           'div'=>false,
                                           'label'=>false,
                                           'options'=>$staffcategory_id,
                                           'empty'=>'',
                                           'required',
                                           'class'=>'span11 pmis_select',
                                         ));
                                     ?>
                                </div>
                            </div>
                        </div>
                         <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Reason<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->textarea('reason',array('div'=>false,'label'=>false,'placeholder'=>'Enter Reason ','class'=>'form-control','required'));?>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="row-fluid">
                        <?php $pid = $_SESSION['Auth']['User']['prison_id']; ?>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Station :</label>
                                <div class="controls">
                                    <?php echo $this->Form->text('station',array('div'=>false,'label'=>false,'placeholder'=>'Enter Station ','readonly'=>'readonly','class'=>'form-control','require','value'=>$funcall->getPrisonerStation($pid)));?>
                                </div>
                            </div>
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
// $(document).ready(function(){
//     $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
   
//         var today = new Date();
        
//     $('.datetimepicker').datetimepicker({format: 'hh:ii:ss',today:'true',endDate:today});
// });

// function validateForm(){
//     var errcount = 0;
//     $('.validate').each(function(){
//         if($(this).val() == ''){
//             errcount++;
//             $(this).addClass('error-text');
//             $(this).removeClass('success-text'); 
//         }else{
//             $(this).removeClass('error-text');
//             $(this).addClass('success-text'); 
//         }        
//     });        
//     if(errcount == 0){            
//         if(confirm('Are you sure want to save?')){  
//             return true;            
//         }else{               
//             return false;           
//         }        
//     }else{   
//         return false;
//     }  
// }
     
        $("#RecordStaffAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[RecordStaff][time_in]': {
                    required: true,
                },
                'data[RecordStaff][staff_category_id]':{
                    required: true,
                },
                'data[RecordStaff][reason]':{
                    required: true,
                }
            },
       messages: {
                'data[RecordStaff][time_in]': {
                    required: "Please enter time in",
                },
                'data[RecordStaff][staff_category_id]':{
                    required: "Please select staff category",
                },
                'data[RecordStaff][reason]':{
                    required: "Please enter reason",
                }
            },
               
    });
</script>
