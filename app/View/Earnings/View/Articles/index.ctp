
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add Prohibited Articles </h5>
					<div style="float:right;padding-top: 7px;">
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php 
					echo $this->Form->create('Article',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden','value'=>1))?>
						<div class="row-fluid">
							<div class="span12">
								<div class="control-group">
									<label class="control-label">Name<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('name',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'textarea',
								  'rows'=>15,
								  'cols'=>200,
								  'maxlength'=>1000,
								  'required',
								  'value'=> $article['Article']['name']

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
