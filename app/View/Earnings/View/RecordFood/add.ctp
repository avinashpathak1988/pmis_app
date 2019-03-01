<?php
if(isset($this->data['RecordFood']['date']) && $this->data['RecordFood']['date'] != ''){
$this->request->data['RecordFood']['date'] = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($this->data['RecordFood']['date']));
}else{
$this->request->data['RecordFood']['date'] = Configure::read('UGANDA-CURRENT-DATE-FORMAT');
$this->request->data['RecordFood']['prison_code'] = $funcall->getName($_SESSION['Auth']['User']['prison_id'],"Prison","code");
}


// if(isset($this->data['RecordFood']['date']) && $this->data['RecordFood']['date'] != ''){
//     $this->request->data['RecordFood']['date'] = date('d-m-Y', strtotime($this->data['RecordFood']['date']));
// }

?>
<style type="text/css">
	.mySelect select{width: 90% !important;}
</style>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add New Food Report</h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Food Report List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('RecordFood',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
					<?php echo $this->Form->input('prison_id', array('type'=>'hidden','value'=> $this->Session->read('Auth.User.prison_id')))?>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Date :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('date',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'class'=>'form-control span11',
								  'id'=>'food_date',
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
									<label class="control-label">Name Of Station  :</label>
									<div class="controls mySelect">
										<?php echo $this->Form->input('prison_station_name',array('div'=>false,'label'=>false,'onChange'=>'getPrisonInfo(this.value)','class'=>'form-control span11','type'=>'select','options'=>$prisonList,'required','id'=>'prison_station_name'));?>
									</div>
								</div>
							</div>
						</div>
					   <div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Prison Station Id:</label>
								<div class="controls">
									<?php echo $this->Form->input('prison_code',array('div'=>false,'label'=>false,'placeholder'=>'Enter Prison Code','class'=>'form-control span11','required','id'=>'prison_code','readonly'));?>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Report Type <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php 
									$reportTypes = array('Before Cooking'=>'Before Cooking','After Cooking'=>'After Cooking');
									echo $this->Form->input('report_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$reportTypes, 'empty'=>'-- Select Report Type --','required'=>true,'title'=>'Report Type is empty','id'=>'report_type'));?>
								</div>
							</div>
						</div>
						</div>
						
						
						<div class="row-fluid">
						
						 <div class="span6">
							<div class="control-group">
								<label class="control-label">Comment On Food<?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->textarea('comment_on_food',array('div'=>false,'label'=>false,'placeholder'=>'Enter Comment On Food ','class'=>'form-control span11','required'));?>
								</div>
							</div>
						</div>
						<div class="span6">
								<div class="control-group">
									<label class="control-label">Meal Type<?php echo MANDATORY; ?> :</label>
									<div class="controls">
										<?php 
									$mealTypes = array('Breakfast'=>'Breakfast','Lunch'=>'Lunch','Dinner'=>'Dinner');
									echo $this->Form->input('meal_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$mealTypes, 'empty'=>'-- Select Meal Type --','required','id'=>'meal_type'));?>
									</div>
								</div>
							</div>
						</div>
						<div class="row-fluid">

						<div class="span6">
						<label class="control-label">Choose Ratings :</label>
						<div class="controls">

							<?php echo $this->Form->input('rating', array('type'=>'hidden','class'=>"ratingEvent rating5"))?>
    						<!-- <input type="text" class="ratingEvent rating5" /> -->
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
	//$('.rating').rating();
	//alert($('#RecordFoodRating').val());
    $('.ratingEvent').rating({ rateEnd: function (v) { $('#RecordFoodRating').val(v); } });
	//$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	
});
  $(function(){
    $("#RecordFoodAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[RecordFood][report_type]': {
                    required: true,
                },
                'data[RecordFood][comment_on_food]': {
                    required: true,
                },
                'data[RecordFood][meal_type]': {
                    required: true,
                },
           },
            messages: {
                'data[RecordFood][report_type]': {
                    required: "This Field is Required.",
                },
                'data[RecordFood][comment_on_food]': {
                    required: "This Field is Required.",
                },
                 'data[RecordFood][meal_type]': {
                    required: "This Field is Required.",
                },
            },
               
    });
  });




function getPrisonInfo(id) 
{ 
    $('#prison_code').val('');
    if(id != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'app','action'=>'getPrisonInfo'));?>';
    
        $.post(strURL,{"prison_id":id},function(data){  
            
            if(data) { 

                var obj = jQuery.parseJSON(data);
                $('#prison_code').val(obj.prison_code); 
            }
        });
    }
}
</script>
