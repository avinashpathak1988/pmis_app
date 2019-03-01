<?php

if(isset($this->data['Letterinout']['date']) && $this->data['Letterinout']['date'] != ''){
	$this->request->data['Letterinout']['date'] = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($this->data['Letterinout']['date']));
}
else
{
	$this->request->data['Letterinout']['date'] = Configure::read('UGANDA-CURRENT-DATE-FORMAT');
}
if(isset($this->data['Letterinout']['from']) && $this->data['Letterinout']['from'] != ''){
	$display1 = 'display:block';
}else{
	$display1 = 'display:none';

}
if(isset($this->data['Letterinout']['to']) && $this->data['Letterinout']['to'] != ''){
	$display2 = 'display:block';
}else{
	$display2 = 'display:none';

}
if(isset($this->data['Letterinout']['type']) && $this->data['Letterinout']['type'] != ''){
	$disabled = 'disabled';
}else{
	$disabled = 'notdisabled';

}
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add New Record Letters In/out </h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Letters in/out Record List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('Letterinout',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
					<?php echo $this->Form->input('prison_id', array('type'=>'hidden', 'value'=> $this->Session->read('Auth.User.prison_id')))?>
					<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Prisoner Number <?php echo MANDATORY; ?> :</label>
									<div class="controls">
										<?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$prisonerList, 'empty'=>'-- Select Prisoner Number --','required','id'=>'prisoner_no',"onchange"=>"checkPrivilage()"));?>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Letters Type<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
                                 $type = array('In'=>'In','Out'=>'Out');
								echo $this->Form->input('type',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'select',
								  'empty'=>'--Select letters type--',
								  'options'=> $type,
								  'readonly'=>'readonly',
								  'required',
								  'disabled'=>$disabled,"onchange"=>"checkPrivilage()"
								));
							 ?>
									</div>
								</div>
							</div> 
							
						</div>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Date<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
									   
								echo $this->Form->input('date',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'required','readonly'
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6 from" style="<?php echo $display1; ?>">
								<div class="control-group">
									<label class="control-label">From <?php echo MANDATORY; ?> :</label>
									<div class="controls">
										<?php echo $this->Form->textarea('from',array('div'=>false,'label'=>false,'placeholder'=>'Enter From ','class'=>'form-control alphdanumeric','required'));?>
									</div>
								</div>
							</div>
							<div class="span6 to" style="<?php echo $display2; ?>">
								<div class="control-group">
									<label class="control-label">To <?php echo MANDATORY; ?> :</label>
									<div class="controls">
										<?php
										 echo $this->Form->textarea('to',array('div'=>false,'label'=>false,'placeholder'=>'Enter To','type'=>'text','class'=>'form-control alpdhanumeric','required'));?>
									</div>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Censored By  :</label>
									<div class="controls">
										<?php echo $this->Form->input('censored_by',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$censored_by, 'empty'=>'-- Select Censored By --','id'=>'censored_by'));?>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Delivered By<?php echo MANDATORY; ?> :</label>
									<div class="controls">
										<?php echo $this->Form->input('delivered_by',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Delivered By','class'=>'form-control alpha','required'));?>
									</div>
								</div>
							</div>
						 
						</div>
						
						<div class="row-fluid">
							<div class="span6">
							<div class="control-group">
								<label class="control-label">Source of Letters<?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->textarea('source_of_letter',array('div'=>false,'label'=>false,'placeholder'=>'Enter Source of Letters','class'=>'form-control alphanumericone','required'));?>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Welfare officer Name :</label>
								<div class="controls">
									<?php 
									//debug($this->Session->read('Auth'));
									$authname = $this->Session->read('Auth.User.first_name')." ".$this->Session->read('Auth.User.last_name');
									echo $this->Form->text('welfare_of_name',array('div'=>false,'label'=>false,'placeholder'=>'Enter welfare officer name ','class'=>'form-control alpha','value'=>$authname,'readonly'));?>
								</div>
							</div>
						</div>
						</div>
						<?php
						if (isset($this->data['Letterinout']['id'])) {?>
						<div class="form-actions" align="center">
                		<button type="submit" class="btn btn-success" id="updateIdinout">Update</button>
              			<?php echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-danger ')); ?>
              			</div>
              			<?php } else {?>
						<div class="form-actions" align="center">
						                  <button type="submit" class="btn btn-success" id="submit">Save</button>

						</div>
						<?php }?>
					<?php echo $this->Form->end();?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$ajaxUrl      = $this->Html->url(array('controller'=>'Letterinouts','action'=>'getNextReceive'));
echo $this->Html->scriptBlock("
    function checkPrivilage(){
        var url = '".$ajaxUrl."';
        if($('#prisoner_no').val()!='' && $('#LetterinoutType').val()!=''){
        	url = url + '/prisoner_id:' + $('#prisoner_no').val();
	        url = url + '/letter_type:' + $('#LetterinoutType').val();
	        $.post(url, {}, function(res) {
	            if(res.trim()!=''){
	            	$('#prisoner_no').val('');
	            	$('#prisoner_no').select2('val','');
	            	$('#LetterinoutType').val('');
	            	dynamicAlertBox('Message', res);
	            }
	        });
        }    
    }
",array('inline'=>false));
?> 
<script type="text/javascript">
$(document).ready(function(){
	$('#prisoner_no').select2({placeholder: 'Select...'});
	$('#censored_by').select2({placeholder: 'Select...'});
	if($('#LetterinoutId').val()==''){
		$('#prisoner_no').select2('val','');
		$('#censored_by').select2('val','');
	}
	$("#LetterinoutAddForm").validate({     
      ignore: "",
        rules: {  
        	'data[Letterinout][type]': {
                required: true,
            },
            'data[Letterinout][prisoner_no]': {
                required: true,
            },
            'data[Letterinout][from]': {
                required: function(element){
                    return $("#LetterinoutType").val()=="In";
                }
            },
            'data[Letterinout][to]': {
                required: function(element){
                    return $("#LetterinoutType").val()=="Out";
                }
            },
            'data[Letterinout][delivered_by]': {
                required: true,
            },
            'data[Letterinout][source_of_letter]': {
                required: true,
            },
        },
   		messages: {
        	'data[Letterinout][type]': {
                 required: "Please select letters type",
            },
            'data[Letterinout][prisoner_no]': {
                required: "Please select Prisoner number",
            },
            'data[Letterinout][from]': {
                required: "Please enter from",
                // required: function(element){
                //     return $("#LetterinoutType").val()=="Out";
                // }
            },
            'data[Letterinout][to]': {
                required: "Please enter to",
                // required: function(element){
                //     return $("#LetterinoutType").val()=="In";
                // }
            },
            'data[Letterinout][delivered_by]': {
                required: "Please enter delivered by",
            },
            'data[Letterinout][source_of_letter]': {
                required: "Please enter delivered by",
            },
        },    
    });
});
$('#submit').click(function(){
        if($("#LetterinoutAddForm").valid()){
            if( !confirm('Are you sure to save?')) {
                return false;
            }
        }
    });

$('#updateIdinout').click(function(){
        if($("#LetterinoutAddForm").valid()){
            if( !confirm('Are you sure to update?')) {
                return false;
            }
        }
    });
$(document).on('change',"#LetterinoutType", function () {
	checkPrivilage();
	if($(this).val()=="In"){
		$('.from').show();
		$('.to').hide();
		$('#LetterinoutTo').val('');
		$('.to').removeAttr('required');

	}
	if($(this).val()=="Out"){
		$('.to').show();
		$('.from').hide();
		$('#LetterinoutFrom').val('');
		$('.from').removeAttr('required');
	}

});
</script>
