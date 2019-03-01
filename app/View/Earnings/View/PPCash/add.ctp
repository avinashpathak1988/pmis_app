<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add New PP Cash </h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('PP Cash Record List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('PPCash',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Name<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('name',array(
								  'div'=>false,
								  'label'=>false,
								  'placeholder'=>'Enter Name',
								  'type'=>'text',
								  'required',
								));
							 ?>
									</div>
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
$(document).ready(function(){
	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	$('.datetimepicker').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss',today:'true',endDate:today});
});


	$("#CallinoutAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Callinout][prisoner_no]': {
                    required: true,
                },
                'data[Callinout][from]': {
                    required: true,
                },
                'data[Callinout][to]': {
                    required: true,
                },
                'data[Callinout][delivered_by]': {
                    required: true,
                },
            },
       messages: {
                'data[Callinout][prisoner_no]': {
                    required: "Please select Prisoner number",
                },
                'data[Callinout][from]': {
                    required: "Please enter from",
                },
                'data[Callinout][to]': {
                    required: "Please enter to",
                },
                'data[Callinout][delivered_by]': {
                    required: "Please select handled by",
                },
            },
               
    });

</script>
