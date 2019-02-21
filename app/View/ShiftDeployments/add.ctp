<?php
if(isset($this->data['ShiftDeployment']['shift_date']) && $this->data['ShiftDeployment']['shift_date'] != ''){
    $this->request->data['ShiftDeployment']['shift_date'] = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($this->data['ShiftDeployment']['shift_date']));
}else{
	$this->request->data['ShiftDeployment']['shift_date'] =  Configure::read('UGANDA-CURRENT-DATE-FORMAT');
}
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>
					<?php
					if(isset($this->request->data['Shift']['id']) && ($this->request->data['Shift']['id'] != 0))
					{
						echo 'Edit Shift Deployment';
					}
					else 
					{
						echo 'Add Shift Deployment';
					}
					?>
					</h5>
					<div style="float:right;padding-top: 7px;">
						
						<?php echo $this->Html->link(__('Shift Deployment List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('ShiftDeployment',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
					<?php echo $this->Form->input('prison_id', array('type'=>'hidden','value'=>$this->Session->read('Auth.User.prison_id')))?>
						<div class="row-fluid">
							  
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Shift Id<?php echo MANDATORY; ?>:</label>
									<div class="controls">
										<?php echo $this->Form->input('shift_id',array('div'=>false,'label'=>false,'type'=>'select','options'=>$shiftList,'empty'=>'','placeholder'=>'Select Shift','class'=>'span11 pmis_select','required'=>false,'onchange'=>'getForceList(this.value)'));?>
									</div>
								</div>
							</div>
							<div class="span6">
	                            <div class="control-group">
	                                <label class="control-label">Area of Deployment<?php echo $req; ?> :</label>
	                                <div class="controls">
	                                    <?php echo $this->Form->input('deploy_area',array('div'=>false,'label'=>false,'type'=>'select','options'=>$deploymentlist,'empty'=>'','class'=>'span11 pmis_select'));?>
	                                </div>
	                            </div>
	                        </div>
							 
                        	<div class="clear"></div>
                        	<div class="row-fluid"> 
                        	<div class="span6">
	                            <div class="control-group">
	                                <label class="control-label">Date<?php echo $req; ?> :</label>
	                                <div class="controls">
	                                    <?php echo $this->Form->input('shift_date',array('div'=>false,'label'=>false,	'type'=>'text','class'=>'mydate','readonly'=>'readonly','required'=>false));?>
	                                </div>
	                            </div>
	                        </div> 
	                        <div class="span6">
	                            <div class="control-group">
	                                <label class="control-label">Force <?php echo $req; ?> :</label>
	                                <div class="controls">
	                                    <?php 
                                            echo $this->Form->input('user_id',array('div'=>false,'multiple'=>true,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$forceList, 'empty'=>'-- Select Force Id --','required'=>false,'id'=>'force_id','hiddenField'=>false));
	                                    //echo $this->Form->input('user_id',array('div'=>false,'multiple'=>true,'label'=>false,'type'=>'select','options'=>$forceList,'empty'=>'-- Select Force Id --','placeholder'=>'Select Force Id','class'=>'form-control','required'=>false,'hiddenField'=>false));?>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="clearfix"></div>
                        	<div class="row-fluid">  
	                        <div class="span6">
	                            <div class="control-group">
	                                <label class="control-label">Staff Deploy At :</label>
	                                <div class="controls">
	                                    <?php echo $this->Form->input('deploy_staff',array('div'=>false,'label'=>false,	'type'=>'text','placeholder'=>'Enter Staff Deploy At'));?>
	                                </div>
	                            </div>
	                        </div> 
	                        </div>
						</div>
					   
						
					<div class="form-actions" align="center">
						 <button type="submit" class="btn btn-success" id="submit">Save</button>
					</div>
					<?php echo $this->Form->end();?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#force_id').select2();

	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	$('.datetimepicker').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss',today:'true',endDate:today});
});
function getForceList(shiftid){
	//alert(shiftid);
	var shifUt_date = $('#ShiftDeploymentShiftDate').val();
	var strRL = '<?php echo $this->Html->url(array('controller'=>'ShiftDeployments','action'=>'forceList'));?>';
    $.post(strURL,{"shift_id":shiftid,shift_date:shift_date},function(data){  
        
        if(data) { 
            $('#force_id').html(data); 
            /*if(id == 1)
            {
                $('#country_id').val(1);
            }
            else 
            {
                $('#country_id').val(0);
            }
            $('#country_id').select2();*/
        }
        else
        {
            alert("Error...");  
        }
        //call function on country change 
        /*onCountryChange($('#country_id').val());
        showDistricts($('#country_id').val());*/
    });
}
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
$(function(){
    $("#ShiftDeploymentAddForm").validate({
     
      ignore: "",
            rules: {  
               'data[ShiftDeployment][shift_id]': {
                    required: true,
                },
           },
            messages: {
                'data[ShiftDeployment][shift_id]': {
                    required: "This Field is Required.",
                },
            },
               
    });
  });

$('#submit').click(function(){
        if($("#ShiftDeploymentAddForm").valid()){
            if( !confirm('Are you sure to save?')) {
                return false;
            }
        }
    });
</script>
