<?php

if(isset($this->data['Callinout']['call_date']) && $this->data['Callinout']['call_date'] != ''){
	$this->request->data['Callinout']['call_date'] = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($this->data['Callinout']['call_date']));
}
else
{
	$this->request->data['Callinout']['call_date'] = Configure::read('UGANDA-CURRENT-DATE-FORMAT');
}
if(isset($this->data['Callinout']['from']) && $this->data['Callinout']['from'] != ''){
	$display1 = 'display:block';
}else{
	$display1 = 'display:none';

}
if(isset($this->data['Callinout']['to']) && $this->data['Callinout']['to'] != ''){
	$display2 = 'display:block';
}else{
	$display2 = 'display:none';

}
if(isset($this->data['Callinout']['type']) && $this->data['Callinout']['type'] != ''){
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
					<h5>Add New Record Calls In/out </h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Calls in/out Record List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('Callinout',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
					<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Calls Type<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
										$type = array('In'=>'In','Out'=>'Out');
										echo $this->Form->input('type',array(
											'div'=>false,
											'label'=>false,
											'type'=>'select',
											'empty'=>'--Select calls type--',
											'options'=> $type,
											'required',
											'onchange'=>"checkPrivilage()",
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
									   $datadate = date('d-m-Y');
								echo $this->Form->input('call_date',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  ///'class'=>'datepicker',
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
								<label class="control-label">Prisoner Number <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$prisonerList, 'empty'=>'','required','id'=>'prisoner_no',
								  'onchange'=>"checkPrivilage()"));?>
								</div>
							</div>
						</div>
						</div>
					   <div class="row-fluid">
						<div class="span6 from" style="<?php echo $display1; ?>">
							<div class="control-group">
								<label class="control-label">From <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->textarea('from',array('div'=>false,'label'=>false,'placeholder'=>'Enter From ','class'=>'form-control alphanumericone','required'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
						<div class="span6 to" style="<?php echo $display2; ?>">
							<div class="control-group">
								<label class="control-label">To <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php
									 echo $this->Form->textarea('to',array('div'=>false,'label'=>false,'placeholder'=>'Enter To','type'=>'text','class'=>'form-control alphanumericone','required'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Phone number :</label>
								<div class="controls">
									<?php echo $this->Form->input('code',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Code','class'=>'form-control','required'=>false,'id'=>'code','style'=>'width:80px;','maxlength'=>'6'));?>
									<?php echo $this->Form->input('no',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Phone Number','class'=>'form-control numeric','required'=>false,'id'=>'no','style'=>'width:240px;'));?>
									<?php echo $this->Form->input('phone_no',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Phone Number','class'=>'form-control numeric','required'=>false,'id'=>'phone_no','style'=>'width:240px;display:none;'));?>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Handled By<?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('delivered_by',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Handled By','class'=>'form-control','required'));?>
								</div>
							</div>
						</div>
						 <div class="span6">
							<div class="control-group">
								<label class="control-label">Duration:</label>
								<div class="controls">
									<?php echo $this->Form->input('duration',array('div'=>false,'label'=>false,'type'=>'hidden','placeholder'=>'Enter Duration','class'=>'form-control timepicker1','required'=>false));?>
									<?php echo $this->Form->input('hr',array('div'=>false,'label'=>false,'type'=>'number','placeholder'=>'Hour','class'=>'form-control','required'=>false,'style'=>'width:105px;','id'=>'hr','min'=>'0','max'=>'5'));?>
									<?php echo $this->Form->input('min',array('div'=>false,'label'=>false,'type'=>'number','placeholder'=>'Minute','class'=>'form-control','required'=>false,'style'=>'width:105px;','id'=>'min','min'=>'0','max'=>'59'));?>
									<?php echo $this->Form->input('sec',array('div'=>false,'label'=>false,'type'=>'number','placeholder'=>'Second','class'=>'form-control','required'=>false,'style'=>'width:105px;','id'=>'sec','min'=>'0','max'=>'59'));?>
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
									echo $this->Form->text('welfare_off_name',array('div'=>false,'label'=>false,'placeholder'=>'Enter welfare officer name ','class'=>'form-control alpha','value'=>$authname,'readonly'));?>
								</div>
							</div>
						</div>
						<div class="clearfix">
							
						</div>
						<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Call Content :</label>
								<div class="controls">
									<?php 
									echo $this->Form->textarea('call_content',array('div'=>false,'label'=>false,'placeholder'=>'Enter call content','type'=>'text','class'=>'form-control alphanumericone','required'=>false,'maxlength'=>1000));
									?>
								</div>
							</div>
						</div>
						</div>
					</div>
					
					<?php if(isset($this->data['Callinout']['id'])) {?>	
					<div class="form-actions" align="center">
                	<button type="submit" class="btn btn-success" id="updateCallInout">Update</button>
              		<?php echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-danger ')); ?>
              		</div>
              		<?php } else{?>	
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Callinouts','action'=>'getNextReceive'));
echo $this->Html->scriptBlock("
    function checkPrivilage(){
        var url = '".$ajaxUrl."';
        if($('#prisoner_no').val()!='' && $('#CallinoutType').val()!=''){
        	url = url + '/prisoner_id:' + $('#prisoner_no').val();
	        url = url + '/letter_type:' + $('#CallinoutType').val();
	        $.post(url, {}, function(res) {
	            if(res.trim()!=''){
	            	$('#prisoner_no').val('');
	            	$('#prisoner_no').select2('val','');
	            	$('#CallinoutType').val('');
	            	dynamicAlertBox('Message', res);
	            }
	        });
        }    
    }
",array('inline'=>false));
?> 
<script type="text/javascript">
$(document).ready(function(){
	// $('#prisoner_no').select2();
	    $("#hr").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	    $("#min").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	    $("#sec").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	$('.datetimepicker').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss',today:'true',endDate:today});
});


	$("#CallinoutAddForm").validate({
     
      ignore: "",
            rules: {  
            	'data[Callinout][type]': {
                    required: true,
                },
                'data[Callinout][prisoner_no]': {
                    required: true,
                },
                'data[Callinout][from]': {
                    //required: true,
                    required: function(element){
                        return $("#CallinoutType").val()=="In";
                    }
                },
                'data[Callinout][to]': {
                    //required: true,
                    required: function(element){
                        return $("#CallinoutType").val()=="Out";
                    }
                },
                'data[Callinout][delivered_by]': {
                    required: true,
                },
            },
       messages: {
            	'data[Callinout][type]': {
                    required: "Please select call type",
                },
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
$(document).on('change',"#CallinoutType", function () {
	// if($(this).val()=="NGO"){
	// 	 $('.pn').hide();
	// 	 $('.n').hide();
	// 	 $('.wh').show();
	// 	// $('#PrisonerinoutTimeIn').removeAttr('required');
	// 	// $('#PrisonerinoutDate').attr('required','required');
	// 	// $('#category').attr('required','required');

	if($(this).val()=="In"){
		$('.from').show();
		$('.to').hide();
		$('#CallinoutTo').val('');
		$('.to').removeAttr('required');

	}
	if($(this).val()=="Out"){
		$('.to').show();
		$('.from').hide();
		$('#CallinoutFrom').val('');
		$('.from').removeAttr('required');

	}

});

$('#code, #no').bind('keypress blur', function() {
        
    $('#phone_no').val($('#code').val() + ' ' +
                             $('#no').val() );
});

// $('#hr, #min, #sec').bind('keypress blur', function() {
        
//     $('#CallinoutDuration').val($('#hr').val() + ' ' +
//                              $('#min').val() + ' ' +
//                              $('#sec').val() + ' ' +
//                              );
// });
$(function () {
   $( "#hr" ).change(function() {
      var max = parseInt($(this).attr('max'));
      var min = parseInt($(this).attr('min'));
      if ($(this).val() > max)
      {
          $(this).val(max);
      }
      else if ($(this).val() < min)
      {
          $(this).val(min);
      }       
    }); 
})
$(function () {
   $( "#min" ).change(function() {
      var max = parseInt($(this).attr('max'));
      var min = parseInt($(this).attr('min'));
      if ($(this).val() > max)
      {
          $(this).val(max);
      }
      else if ($(this).val() < min)
      {
          $(this).val(min);
      }       
    }); 
})
$(function () {
   $( "#sec" ).change(function() {
      var max = parseInt($(this).attr('max'));
      var min = parseInt($(this).attr('min'));
      if ($(this).val() > max)
      {
          $(this).val(max);
      }
      else if ($(this).val() < min)
      {
          $(this).val(min);
      }       
    }); 
})
  $('#code').keyup(function()
    {
        var your = $(this).val();
        re = /[a-z`~!@#$%^&*()_|\=?;:'",.<>\{\}\[\]\\\/]/gi;
        var isSpl = re.test(your);
        if(isSpl)
        {
            var no_spl = your.replace(/[a-z`~!@#$%^&*()_|\=?;:'",.<>\{\}\[\]\\\/]/gi, '');
            $(this).val(no_spl);
        }
    });
  $('#submit').click(function(){
        if($("#CallinoutAddForm").valid()){
            if( !confirm('Are you sure to Save?')) {
                return false;
            }
        }
    });
  
   $('#updateCallInout').click(function(){
        if($("#CallinoutAddForm").valid()){
            if( !confirm('Are you sure to Update?')) {
                return false;
            }
        }
    });
</script>
