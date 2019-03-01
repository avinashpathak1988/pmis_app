<style type="text/css">
	.tooltips {
    position: relative;
    display: inline-block;
  
}

.tooltips .tooltiptexts {
    visibility: hidden;
    width: 660px;
    background-color: #f2dede;
    color: #333333;
    padding: 23px 17px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin: 5px;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltips .tooltiptexts::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 94%;
    margin-left: 5px;
    border-width: 13px;
    border-style: solid;
    border-color: #f2dede transparent transparent transparent;
}

.tooltips:hover .tooltiptexts {
    visibility: visible;
    opacity: 1;
}

</style>
<?php
// if(isset($this->request->data) && $this->request->data !=''){
// 	$this->request->data['Visitor']['date'] = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($this->data['Visitor']['date']));
// }
if(isset($this->request->data['Visitor']['id']) && $this->request->data['Visitor']['id']!=''){
	$disabled = 'disabled';
	$placeholder = '';
	$display = '';
	$date = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($this->data['Visitor']['date']));
}
else{
 $disabled = 'false';	
 $placeholder = date("h:i");
 $display = 'display:none;';
 //$this->request->data['Visitor']['time_in'] = date('h:m');
  $date = Configure::read('UGANDA-CURRENT-DATE-FORMAT');
}
if(isset($this->request->data['Visitor']['category']) && $this->request->data['Visitor']['category'] !=''){
	$category = $this->request->data['Visitor']['category'];
	$view = 'display:none;';
	if($category == 'NGO'){
		$editdisplay = 'display:none;';
		$prisondisplay = 'display:block;';
	}
	if($category == 'Visiting Prisoner'){
		$editdisplay1 = 'display:none;';
		$editdisplay = 'display:block;';
		$prisondisplay = 'display:block;';
	}
	if($category == 'Official-Relatives'){
		$editdisplay2 = 'display:none;';
		$editdisplay1 = 'display:block;';
		//$editdisplay = 'display:block;';
		$editdisplay3 = 'display:none;';
		$editdisplay4 = 'display:block;';
		$editdisplay = 'display:none;';
		$title = '';
		$required = 'false';
		$prisondisplay = 'display:block;';
	}
	else{
		$editdisplay = '';
		$editdisplay1 = '';
		$editdisplay2 = '';
		$editdisplay3 = '';
		$editdisplay4 = '';
		$title = 'Please select prisoner name.';
		$required = 'true';
		$prisondisplay = 'display:none;';
	}

}else{
	$editdisplay = '';
	$editdisplay1 = '';
	$editdisplay2 = '';
	$editdisplay3 = '';
    $editdisplay4 = '';
    $view = '';
    $title = 'Please select prisoner name.';
    $required = 'true';
    $prisondisplay = 'display:none;';
}
//debug($_SESSION);
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add New Record Visitors </h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Visitors Record List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('Visitor',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
					<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Visitor Category<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
							    $visitor = array('Visiting Prisoner' =>'Visiting Prisoner',
                                                 'Official-Relatives'=>'Official/Relatives',
                                                 'NGO'               => 'NGO');
								echo $this->Form->input('category',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'select',
								  'empty'=>'--Select Visitor Category--',
								  'options'=> $visitor,
								   $disabled
								));
							 ?>
									</div>
								</div>
							</div> 
							<?php
							if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
							 ?>
							<div class="span6 prison" style="<?php echo $prisondisplay ?>">
								<div class="control-group">
									<label class="control-label">Prison Name<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('prison_id',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'select',
								  'empty'=>'--Select Prison Name--',
								  'options'=> $prisonList,
								  'required',
								  'title'=>'Please select prison name'
								));
							 ?>
									</div>
								</div>
							</div>    
							<?php } ?> 
						</div>
						<div class="firstDiv" style="<?php echo $display; ?>">
						<div class="row-fluid first">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Date Of Visitation<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('date',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'class'=>'span11',
								  'data-date-format'=>"dd-mm-yyyy",
								  'readonly'=>'readonly',
								  'required',
								  'value'=>$date
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
							<div class="control-group">
								<label class="control-label">Time In <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('time_in',array('div'=>false,'label'=>false,'class'=>'form-control span11','placeholder'=> $placeholder,'readonly'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Reason For Visitation<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('reason',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'textarea',
								  'class'=>'form-control span11 alphanumericone',
								  'maxlength'=>'100',
								  'rows'=>'2',
								  'cols'=>'2',
								  'required',
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
							<div class="control-group">
								<label class="control-label">Gate Keeper Name <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php
									 echo $this->Form->input('gate_keeper',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','value'=>$_SESSION['Auth']['User']['name'],'id'=>'first_name','title'=>'Please select gate keeper.','readonly'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Bag No.<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('bag_no',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'class'=>'form-control span11 alphanumericone',
								  'required',
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
							<div class="control-group">
								<label class="control-label">Vehicle No. <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php
									//debug($gateKeepers);
									 echo $this->Form->input('vehicle_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required','maxlength'=>'15'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
							<div class="span6 address" style="<?php echo $display; ?>">
								<div class="control-group">
									<label class="control-label">Address<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('address',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','type'=>'text','required','title'=>'Please enter address'));?>
									</div>
								</div>
							</div>     
							<div class="span6 contact" style="<?php echo $display; ?>">
							<div class="control-group">
								<label class="control-label">Contact No. <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('contact_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','required','maxlength'=>'25'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
							<div class="span6 whom" style="<?php echo $display; ?><?php echo $editdisplay1; ?><?php echo $editdisplay4; ?>">
								<div class="control-group">
									<label class="control-label">To whom you are meeting <?php echo MANDATORY; ?>:</label>
									<div class="controls">
									   <?php echo $this->Form->input('to_whom',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','id'=>'to_whom'));?>
									</div>
								</div>
							</div>     
						</div>
						
						<div class="row-fluid secondDiv" style="<?php echo $display; ?>">
						<?php echo $this->element('visitor-items');?>
						</div>
<?php 
// if(isset($this->request->data['Visitor']['category']) && $this->request->data['Visitor']['category'] =='Visiting Prisoner' || $this->request->data['Visitor']['category'] =='Official/Relatives'){
?>
						<!-- <div class="row-fluid thirdDiv" style="<?php echo $display; ?><?php echo $editdisplay; ?>">
							<div class="span6 pn">
								<div class="control-group">
									<label class="control-label">Prisoner Number <?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'onChange'=>'getPrisonerStationInfo(this.value)','class'=>'form-control span11','type'=>'select','options'=>$prisonerList, 'empty'=>'-- Select Prisoner Number --','required','id'=>'prisoner_no','title'=>'Please select prisoner no.'));?>
									</div>
								</div>
							</div>     
							<div class="span6 n">
							<div class="control-group ">
								<label class="control-label">Prisoner Name <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Name','class'=>'form-control span11','id'=>'name','readonly','title'=>'Please enter name.','required','title'=>'Please select prisoner name.'));?>
								</div>
							</div>
						</div>
						</div> -->
						<div class="row-fluid thirdDiv" style="<?php echo $display; ?><?php echo $editdisplay; ?>">
							<div class="span6 pn">
								<div class="control-group">
									<label class="control-label">Prisoner Name <?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'onChange'=>'getPrisonernumer(this.value)','class'=>'form-control span11','type'=>'select','options'=>$prisonernameList, 'empty'=>'-- Select Prisoner Name --','id'=>'prisoner_no',$title,'style'=>'width:200px;'));?>
									</div>
									<div class="tooltips view" style="color: red;font-size: 13px;font-weight: 600;margin-top: 1px;margin-left:12px;cursor: help;<?php echo $view; ?>">Details
  <span class="tooltiptexts">
  <h3>Prisoner Details</h3>
  Father Name: 
  <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','readonly','id'=>'father_name','style'=>'width:200px;background:transparent;border:1px solid transparent;'));?>
  Mother Name: 
  <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','readonly','id'=>'mother_name','style'=>'width:200px;background:transparent;border:1px solid transparent;'));?>
  Date Of Birth:
  <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','readonly','id'=>'date_of_birth','style'=>'width:200px;background:transparent;border:1px solid transparent;'));?>
  Place Of Birth: 
  <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','readonly','id'=>'place_of_birth','style'=>'width:200px;background:transparent;border:1px solid transparent;'));?>
</span>
</div>
								</div>
							</div>     
							<div class="span6 n">
							<div class="control-group ">
								<label class="control-label">Prisoner Number <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'placeholder'=>'Enter Name','class'=>'form-control span11','readonly'));?>
								</div>
							</div>
						</div>
						</div>
<?php //} ?>
						<div class="row-fluid">    
							
						<div class="row-fluid fourthDiv" style="<?php echo $display; ?>">
						<?php echo $this->element('visitor-details');?>
						</div>
						</div>
						<div class="row-fluid">
							<!-- <div class="span6 contact" style="<?php echo $display; ?>">
								<div class="control-group">
									<label class="control-label">Contact No. <?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php echo $this->Form->input('contact_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','required','maxlength'=>'10'));?>
									</div>
								</div>
							</div>  -->    
							<div class="span6 cashdetails" style="<?php echo $display; ?>">
							<div class="control-group">
								<label class="control-label">Cash Details <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('cash_details',array('div'=>false,'label'=>false,'placeholder'=>'Enter Name','class'=>'form-control span11 alphanumericone','id'=>'cash_details','required'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid ppcash" style="<?php echo $display; ?><?php echo $editdisplay; ?><?php echo $editdisplay2; ?>">
							<div class="span6 pp">
								<div class="control-group">
									<label class="control-label">Type of pp cash :</label>
									<div class="controls">
									   <?php echo $this->Form->input('pp_cash',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','id'=>'pp_cash','empty'=>'--Select--','options'=>$ppcash,'required','title'=>'Please select type of pp cash'));?>
									</div>
								</div>
							</div>     
							<div class="span6 app">
							<div class="control-group">
								<label class="control-label">Amount Given In PP Cash <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('pp_amount',array('div'=>false,'label'=>false,'placeholder'=>'Enter Name','class'=>'form-control span11 numeric','id'=>'pp_amount','required'=>'Please enter PP cash amount','required'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid article" style="<?php echo $display; ?>">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Prohibited Articles :</label>
									<div class="controls">
									   <div class="tooltips" style="color: red;font-size: 13px;font-weight: 600;margin-top: 4px;cursor: help;">Information
  <span class="tooltiptexts">
  <h3>Important Note</h3>
  <?php echo $article['Article']['name']; ?>
</span>
</div>

									</div>
								</div>
							</div>     
							<div class="span6 rpp" style="<?php echo $display; ?><?php echo $editdisplay; ?><?php echo $editdisplay3; ?>">
							<div class="control-group">
								<label class="control-label">Registration of Personal Property <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('Personal_property',array('div'=>false,'label'=>false,'placeholder'=>'Enter Name','class'=>'form-control span11 alphanumericone','id'=>'Personal_property','required','title'=>'Please enter registration of personal property'));?>
								</div>
							</div>
						</div>
						</div>
						

						</div>
						
						
					<div class="form-actions" align="center">
						<?php echo $this->Form->input('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
					</div>
					<?php echo $this->Form->end();?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
$id = '';
if(isset($this->request->data['Visitor']['name']) && $this->request->data['Visitor']['name'] !=''){
	$id = $this->request->data['Visitor']['name'];
}
?>
<script type="text/javascript">
$(document).ready(function(){
	getPrisonernumer('<?php echo $id; ?>');
	$('.view').hide();
	if($('#prisoner_no').val() == ''){
		$('.view').hide();
	}else{
		$('.view').show();
	}
	$(document).on('change',"#prisoner_no", function () {
	if($(this).val()!=""){
		$('.view').show();
	}else{
		$('.view').hide();
	}

});	
	$('#prisoner_no').select2();
	$('#name').select2();
	$('#pp_cash').select2();
	$('.relation').select2();
	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	var cat = $('#VisitorCategory').val();
	if(cat == 'NGO'){
		$('#pp_cash').removeAttr('required','required');
		$('#pp_amount').removeAttr('required','required');
		$('#Personal_property').removeAttr('required','required');
		$('#prisoner_no').removeAttr('required','required');
		$('#name').removeAttr('required','required');
		$('.pp').hide();
		$('.app').hide();
		$('.rpp').hide();
		$('#VisitorName0Photo').removeAttr('required','required');
		$('#VisitorName1Photo').removeAttr('required','required');
		$('#VisitorName2Photo').removeAttr('required','required');
		$('#VisitorName3Photo').removeAttr('required','required');
		$('#VisitorName4Photo').removeAttr('required','required');
		$('#VisitorName5Photo').removeAttr('required','required');
		$('#VisitorName6Photo').removeAttr('required','required');
		$('#VisitorName7Photo').removeAttr('required','required');
		$('#VisitorName8Photo').removeAttr('required','required');
		$('#VisitorName9Photo').removeAttr('required','required');
	}
	if(cat == 'Official-Relatives'){
		$('#pp_cash').removeAttr('required','required');
		$('#pp_amount').removeAttr('required','required');
		$('#Personal_property').removeAttr('required','required');
		$('#VisitorName0Photo').removeAttr('required','required');
		$('#VisitorName1Photo').removeAttr('required','required');
		$('#VisitorName2Photo').removeAttr('required','required');
		$('#VisitorName3Photo').removeAttr('required','required');
		$('#VisitorName4Photo').removeAttr('required','required');
		$('#VisitorName5Photo').removeAttr('required','required');
		$('#VisitorName6Photo').removeAttr('required','required');
		$('#VisitorName7Photo').removeAttr('required','required');
		$('#VisitorName8Photo').removeAttr('required','required');
		$('#VisitorName9Photo').removeAttr('required','required');
	}
	if(cat == 'Visiting Prisoner'){
		$('.whom').hide();
		$('#VisitorName0Photo').removeAttr('required','required');
		$('#VisitorName1Photo').removeAttr('required','required');
		$('#VisitorName2Photo').removeAttr('required','required');
		$('#VisitorName3Photo').removeAttr('required','required');
		$('#VisitorName4Photo').removeAttr('required','required');
		$('#VisitorName5Photo').removeAttr('required','required');
		$('#VisitorName6Photo').removeAttr('required','required');
		$('#VisitorName7Photo').removeAttr('required','required');
		$('#VisitorName8Photo').removeAttr('required','required');
		$('#VisitorName9Photo').removeAttr('required','required');
		// $('#pp_amount').removeAttr('required','required');
		// $('#Personal_property').removeAttr('required','required');
	}
});

       $('#VisitorVehicleNo').keyup(function()
    {
        var your = $(this).val();
        re = /[`~!@#$%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/gi;
        var isSpl = re.test(your);
        if(isSpl)
        {
            var no_spl = your.replace(/[`~!@#$%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/gi, '');
            $(this).val(no_spl);
        }
    });

function validateForm(){
	// var errcount = 0;
	// $('.validate').each(function(){
	// 	if($(this).val() == ''){
	// 		errcount++;
	// 		$(this).addClass('error-text');
	// 		$(this).removeClass('success-text'); 
	// 	}else{
	// 		$(this).removeClass('error-text');
	// 		$(this).addClass('success-text'); 
	// 	}        
	// });        
	// if(errcount == 0){    
	// 	// if(confirm('Are you sure want to save?')){  
	// 	// 	return true;            
	// 	// }else{               
	// 	// 	return false;           
	// 	// }  
	// 	AsyncConfirmYesNo(
 //                'Are you sure want to save',
 //                'Yes',
 //                'No',
 //                function(){
 //                    $('#VisitorAddForm').submit(); 
 //                },
 //                function(){
 //                    //return false; 
 //                }
 //            );      
	// }else{   
	// 	return false;
	// }  
}
function getPrisonerStationInfo(id) 
{ 
    $('#name').val('');
    if(id != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'app','action'=>'getPrisonerStationInfo'));?>';
    
        $.post(strURL,{"prisoner_id":id},function(data){  
            
            if(data) { 

                var obj = jQuery.parseJSON(data);
                $('#name').val(obj.prisoner_name);
            }
        });
    }
}
function getPrisonernumer(name) 
{ 
    $('#VisitorPrisonerNo').val('');
    if(name != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'app','action'=>'getPrisonernumer'));?>';
    
        $.post(strURL,{"name":name},function(data){  
            
            if(data) { 
                var obj = jQuery.parseJSON(data);
                $('#VisitorPrisonerNo').val(obj.prisoner_no);
                $('#father_name').val(obj.father_name);
                $('#mother_name').val(obj.mother_name);
                $('#date_of_birth').val(obj.date_of_birth);
                $('#place_of_birth').val(obj.place_of_birth);
            }
        });
    }
}
<?php if(count($this->request->data) == 0){ ?>
$(document).on('change',"#VisitorCategory", function () {
	// if($(this).val()=="NGO"){
	// 	 $('.pn').hide();
	// 	 $('.n').hide();
	// 	 $('.wh').show();
	// 	// $('#PrisonerinoutTimeIn').removeAttr('required');
	// 	// $('#PrisonerinoutDate').attr('required','required');
	// 	// $('#category').attr('required','required');
	// 	// $('#prisoner_no').attr('required','required');
	// 	// $('#name').attr('required','required');
	// 	// $('#PrisonerinoutTimeOut').attr('required','required');
	// 	// $('#PrisonerinoutDestination').attr('required','required');
	// 	// $('#PrisonerinoutStaffEscortDetails').attr('required','required');
	// 	// $('#PrisonerinoutGatePassNo').attr('required','required');
	// 	// $('#first_name').attr('required','required');
	// 	// $('#to_whom').attr('required','required');
	// 	// $('#PrisonerinoutReason').attr('required','required');
	// 	// $('.in').hide();
	//     //$('#PrisonerinoutTimeIn').val('');

	// }else{
	// 	$('.pn').show();
	// 	$('.n').show();
	// 	$('.wh').hide();
	// }
	if($(this).val()=="Visiting Prisoner"){
		$('.firstDiv').show();
		$('.prison').show();
		$('.secondDiv').show();
		$('.thirdDiv').show();
		$('.fourthDiv').show();
		$('.address').show();
		$('.contact').show();
		$('.cashdetails').show();
		$('.article').show();
		$('.ppcash').show();
		$('.app').show();
		$('.whom').hide();
		$('.rpp').show();
		$('#pp_cash').attr('required','required');
		$('#pp_amount').attr('required','required');
		$('#Personal_property').attr('required','required');
		$('#prisoner_no').attr('required','required');
		$('#name').attr('required','required');
		//$('#to_whom').removeAttr('required','required');
		//$('.error').hide();
		//$('label[for="to_whom"]').hide()
	}
	if($(this).val()=="Official-Relatives"){
		$('.prison').show();
		$('.firstDiv').show();
		$('.secondDiv').show();
		$('.thirdDiv').hide();
		$('.fourthDiv').show();
		$('.address').show();
		$('.contact').show();
		$('.cashdetails').show();
		$('.article').show();
		$('.ppcash').hide();
		$('.app').hide();
		$('.rpp').hide();
		$('.whom').show();
		// $('.pp').hide();
		// $('.app').hide();
		$('#pp_cash').removeAttr('required','required');
		$('#pp_amount').removeAttr('required','required');
		$('#Personal_property').removeAttr('required','required');
		$('#to_whom').removeAttr('required','required');
		//$('.error').hide();
		//$('label[for="to_whom"]').hide()
	}
	if($(this).val()=="NGO"){
		$('.prison').show();
		$('.firstDiv').show();
		$('.secondDiv').show();
		$('.thirdDiv').hide();
		$('.fourthDiv').show();
		$('.address').show();
		$('.contact').show();
		$('.cashdetails').show();
		$('.article').show();
		$('.ppcash').hide();
		$('.app').hide();
		$('.rpp').hide();
		$('.whom').show();
		$('#pp_cash').removeAttr('required','required');
		$('#pp_amount').removeAttr('required','required');
		$('#Personal_property').removeAttr('required','required');
		$('#prisoner_no').removeAttr('required','required');
		$('#name').removeAttr('required','required');
		// $('.pp').hide();
		// $('.app').hide();
		//$('#to_whom').removeAttr('required','required');
		//$('.error').hide();
		//$('label[for="to_whom"]').hide()
	}
	if($(this).val()==""){
		$('.prison').hide();
		$('.firstDiv').hide();
		$('.secondDiv').hide();
		$('.thirdDiv').hide();
		$('.fourthDiv').hide();
		$('.address').hide();
		$('.contact').hide();
		$('.cashdetails').hide();
		$('.article').hide();
		$('.ppcash').hide();
		$('.whom').hide();
	}
	// if($(this).val()=="Official/Relatives"){
	// 	$('.pn').hide();
	// 	$('.n').hide();
		
	// }
});
$(document).on('change',"#prisoner_no", function () {
	if($(this).val()!=""){
		$('.view').show();
	}else{
		$('.view').hide();
	}

});	
<?php } ?>
	$("#VisitorAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Visitor][category]': {
                    required: true,
                },
                'data[Visitor][date]': {
                    required: true,
                },
                'data[Visitor][reason]': {
                    required: true,
                },
                'data[Visitor][bag_no]': {
                    required: true,
                },
                'data[Visitor][vehicle_no]': {
                    required: true,
                },
                //-------Item Name--------------
                'data[VisitorItem][0][item]': {
                    required: true,
                },
                'data[VisitorItem][1][item]': {
                    required: true,
                },
                'data[VisitorItem][2][item]': {
                    required: true,
                },
                'data[VisitorItem][3][item]': {
                    required: true,
                },
                'data[VisitorItem][4][item]': {
                    required: true,
                },
                'data[VisitorItem][5][item]': {
                    required: true,
                },
                'data[VisitorItem][6][item]': {
                    required: true,
                },
                'data[VisitorItem][7][item]': {
                    required: true,
                },
                'data[VisitorItem][8][item]': {
                    required: true,
                },
                'data[VisitorItem][9][item]': {
                    required: true,
                },
                //-------Item Name--------------
                //-------Item Quantity----------
                'data[VisitorItem][0][quantity]': {
                    required: true,
                },
                'data[VisitorItem][1][quantity]': {
                    required: true,
                },
                'data[VisitorItem][2][quantity]': {
                    required: true,
                },
                'data[VisitorItem][3][quantity]': {
                    required: true,
                },
                'data[VisitorItem][4][quantity]': {
                    required: true,
                },
                'data[VisitorItem][5][quantity]': {
                    required: true,
                },
                'data[VisitorItem][6][quantity]': {
                    required: true,
                },
                'data[VisitorItem][7][quantity]': {
                    required: true,
                },
                'data[VisitorItem][8][quantity]': {
                    required: true,
                },
                'data[VisitorItem][9][quantity]': {
                    required: true,
                },
                //---------Visitor Name------------
                'data[VisitorName][0][name]': {
                    required: true,
                },
                'data[VisitorName][1][name]': {
                    required: true,
                },
                'data[VisitorName][2][name]': {
                    required: true,
                },
                'data[VisitorName][3][name]': {
                    required: true,
                },
                'data[VisitorName][4][name]': {
                    required: true,
                },
                'data[VisitorName][5][name]': {
                    required: true,
                },
                'data[VisitorName][6][name]': {
                    required: true,
                },
                'data[VisitorName][7][name]': {
                    required: true,
                },
                'data[VisitorName][8][name]': {
                    required: true,
                },
                'data[VisitorName][9][name]': {
                    required: true,
                },
                //---------Visitor Name------------
                //---------Visitor Relationship------------
                'data[VisitorName][0][relation]': {
                    required: true,
                },
                'data[VisitorName][1][relation]': {
                    required: true,
                },
                'data[VisitorName][2][relation]': {
                    required: true,
                },
                'data[VisitorName][3][relation]': {
                    required: true,
                },
                'data[VisitorName][4][relation]': {
                    required: true,
                },
                'data[VisitorName][5][relation]': {
                    required: true,
                },
                'data[VisitorName][6][relation]': {
                    required: true,
                },
                'data[VisitorName][7][relation]': {
                    required: true,
                },
                'data[VisitorName][8][relation]': {
                    required: true,
                },
                'data[VisitorName][9][relation]': {
                    required: true,
                },
                //---------Visitor Relationship------------
                //---------Visitor photo----------------
                'data[VisitorName][0][photo]': {
                     <?php 
                        if(!isset($this->request->data['VisitorName'][0]['photo']))
                      {?>
                        required: true,
                     <?php }?>
                      
                },
                'data[VisitorName][1][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][1]['photo']))
                      {?>
                        required: true,
                     <?php }?>
                },
                'data[VisitorName][2][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][2]['photo']))
                      {?>
                        required: true,
                     <?php }?>
                },
                'data[VisitorName][3][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][3]['photo']))
                      {?>
                        required: true,
                     <?php }?>
                },
                'data[VisitorName][4][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][4]['photo']))
                      {?>
                        required: true,
                     <?php }?>
                },
                'data[VisitorName][5][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][5]['photo']))
                      {?>
                        required: true,
                     <?php }?>
                },
                'data[VisitorName][6][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][6]['photo']))
                      {?>
                        required: true,
                     <?php }?>
                },
                'data[VisitorName][7][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][7]['photo']))
                      {?>
                        required: true,
                     <?php }?>
                },
                'data[VisitorName][8][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][8]['photo']))
                      {?>
                        required: true,
                     <?php }?>
                },
                'data[VisitorName][9][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][9]['photo']))
                      {?>
                        required: true,
                     <?php }?>
                },
                //---------Visitor photo------------
                //---------Visitor Id no----------------
                'data[VisitorName][0][nat_id]': {
                    required: true,
                },
                'data[VisitorName][1][nat_id]': {
                    required: true,
                },
                'data[VisitorName][2][nat_id]': {
                    required: true,
                },
                'data[VisitorName][3][nat_id]': {
                    required: true,
                },
                'data[VisitorName][4][nat_id]': {
                    required: true,
                },
                'data[VisitorName][5][nat_id]': {
                    required: true,
                },
                'data[VisitorName][6][nat_id]': {
                    required: true,
                },
                'data[VisitorName][7][nat_id]': {
                    required: true,
                },
                'data[VisitorName][8][nat_id]': {
                    required: true,
                },
                'data[VisitorName][9][nat_id]': {
                    required: true,
                },
                //---------Visitor id no------------
                'data[Visitor][contact_no]': {
                    required: true,
                },
                'data[Visitor][cash_details]': {
                    required: true,
                },
                'data[Visitor][Personal_property]': {
                	// required: true,
                 //    required: function(element){
                 //        return $("#Personal_property").val()=="Visiting Prisoner";
                 //    }
                },
                'data[Visitor][to_whom]': {
                    required: function(element){
                        return $("#VisitorCategory").val()!="Visiting Prisoner";
                    }
                },
                'data[Visitor][pp_cash]': {
                	// required: true,
                 //    required: function(element){
                 //        return $("#pp_cash").val()=="Visiting Prisoner";
                 //    }
                },
                'data[Visitor][pp_amount]': {
                	// required: true,
                 //    required: function(element){
                 //        return $("#pp_amount").val()=="Visiting Prisoner";
                 //    }
                },
  

            },
       messages: {
                'data[Visitor][category]': {
                    required: "Please select visitor category",
                },
                'data[Visitor][date]': {
                    required: "Please choose date of visitation",
                },
                'data[Visitor][reason]': {
                    required: "Please enter reason for visitation",
                },
                'data[Visitor][bag_no]': {
                    required: "Please enter bag no.",
                },
                'data[Visitor][vehicle_no]': {
                    required: "Please enter vehicle no.",
                },
                //-------Item Name-------------------
                'data[VisitorItem][0][item]': {
                    required: "Please enter item name",
                },
                'data[VisitorItem][1][item]': {
                    required: "Please enter item name",
                },
                'data[VisitorItem][2][item]': {
                    required: "Please enter item name",
                },
                'data[VisitorItem][3][item]': {
                    required: "Please enter item name",
                },
                'data[VisitorItem][4][item]': {
                    required: "Please enter item name",
                },
                'data[VisitorItem][5][item]': {
                    required: "Please enter item name",
                },
                'data[VisitorItem][6][item]': {
                    required: "Please enter item name",
                },
                'data[VisitorItem][7][item]': {
                    required: "Please enter item name",
                },
                'data[VisitorItem][8][item]': {
                    required: "Please enter item name",
                },
                'data[VisitorItem][9][item]': {
                    required: "Please enter item name",
                },
                //-------Item Name------------------
                //-------Item Quantity--------------
                'data[VisitorItem][0][quantity]': {
                     required: "Please enter quantity",
                },
                'data[VisitorItem][1][quantity]': {
                     required: "Please enter quantity",
                },
                'data[VisitorItem][2][quantity]': {
                     required: "Please enter quantity",
                },
                'data[VisitorItem][3][quantity]': {
                     required: "Please enter quantity",
                },
                'data[VisitorItem][4][quantity]': {
                     required: "Please enter quantity",
                },
                'data[VisitorItem][5][quantity]': {
                     required: "Please enter quantity",
                },
                'data[VisitorItem][6][quantity]': {
                     required: "Please enter quantity",
                },
                'data[VisitorItem][7][quantity]': {
                     required: "Please enter quantity",
                },
                'data[VisitorItem][8][quantity]': {
                     required: "Please enter quantity",
                },
                'data[VisitorItem][9][quantity]': {
                     required: "Please enter quantity",
                },
                //-------Item Quantity--------------
                //---------Visitor Name------------
                'data[VisitorName][0][name]': {
                    required: "Please enter visitor name",
                },
                'data[VisitorName][1][name]': {
                    required: "Please enter visitor name",
                },
                'data[VisitorName][2][name]': {
                    required: "Please enter visitor name",
                },
                'data[VisitorName][3][name]': {
                    required: "Please enter visitor name",
                },
                'data[VisitorName][4][name]': {
                    required: "Please enter visitor name",
                },
                'data[VisitorName][5][name]': {
                    required: "Please enter visitor name",
                },
                'data[VisitorName][6][name]': {
                    required: "Please enter visitor name",
                },
                'data[VisitorName][7][name]': {
                    required: "Please enter visitor name",
                },
                'data[VisitorName][8][name]': {
                    required: "Please enter visitor name",
                },
                'data[VisitorName][9][name]': {
                    required: "Please enter visitor name",
                },
                //---------Visitor Name------------
                //---------Visitor Relationship------------
                'data[VisitorName][0][relation]': {
                    required: "Please select visitor relationship",
                },
                'data[VisitorName][1][relation]': {
                    required: "Please select visitor relationship",
                },
                'data[VisitorName][2][relation]': {
                    required: "Please select visitor relationship",
                },
                'data[VisitorName][3][relation]': {
                    required: "Please select visitor relationship",
                },
                'data[VisitorName][4][relation]': {
                    required: "Please select visitor relationship",
                },
                'data[VisitorName][5][relation]': {
                    required: "Please select visitor relationship",
                },
                'data[VisitorName][6][relation]': {
                    required: "Please select visitor relationship",
                },
                'data[VisitorName][7][relation]': {
                    required: "Please select visitor relationship",
                },
                'data[VisitorName][8][relation]': {
                    required: "Please select visitor relationship",
                },
                'data[VisitorName][9][relation]': {
                    required: "Please select visitor relationship",
                },
                //---------Visitor Relationship------------
                //---------Visitor photo------------
                'data[VisitorName][0][photo]': {
                    
                     <?php 
                        if(!isset($this->request->data['VisitorName'][0]['photo']))
                      {?>
                        required: "Please choose visitor photo",
                     <?php }?>

                     
                },
                'data[VisitorName][1][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][1]['photo']))
                      {?>
                        required: "Please choose visitor photo",
                     <?php }?>
                },
                'data[VisitorName][2][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][2]['photo']))
                      {?>
                        required: "Please choose visitor photo",
                     <?php }?>
                },
                'data[VisitorName][3][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][3]['photo']))
                      {?>
                        required: "Please choose visitor photo",
                     <?php }?>
                },
                'data[VisitorName][4][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][4]['photo']))
                      {?>
                        required: "Please choose visitor photo",
                     <?php }?>
                },
                'data[VisitorName][5][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][5]['photo']))
                      {?>
                        required: "Please choose visitor photo",
                     <?php }?>
                },
                'data[VisitorName][6][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][6]['photo']))
                      {?>
                        required: "Please choose visitor photo",
                     <?php }?>
                },
                'data[VisitorName][7][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][7]['photo']))
                      {?>
                        required: "Please choose visitor photo",
                     <?php }?>
                },
                'data[VisitorName][8][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][8]['photo']))
                      {?>
                        required: "Please choose visitor photo",
                     <?php }?>
                },
                'data[VisitorName][9][photo]': {
                    <?php 
                        if(!isset($this->request->data['VisitorName'][9]['photo']))
                      {?>
                        required: "Please choose visitor photo",
                     <?php }?>
                },
                //---------Visitor photo----------------
                //---------Visitor Id no----------------
                'data[VisitorName][0][nat_id]': {
                    required: "Please enter visitor national id no",
                },
                'data[VisitorName][1][nat_id]': {
                    required: "Please enter visitor national id no",
                },
                'data[VisitorName][2][nat_id]': {
                    required: "Please enter visitor national id no",
                },
                'data[VisitorName][3][nat_id]': {
                    required: "Please enter visitor national id no",
                },
                'data[VisitorName][4][nat_id]': {
                    required: "Please enter visitor national id no",
                },
                'data[VisitorName][5][nat_id]': {
                    required: "Please enter visitor national id no",
                },
                'data[VisitorName][6][nat_id]': {
                    required: "Please enter visitor national id no",
                },
                'data[VisitorName][7][nat_id]': {
                    required: "Please enter visitor national id no",
                },
                'data[VisitorName][8][nat_id]': {
                    required: "Please enter visitor national id no",
                },
                'data[VisitorName][9][nat_id]': {
                    required: "Please enter visitor national id no",
                },
                //---------Visitor id no------------
                'data[Visitor][contact_no]': {
                    required: "Please enter contact no",
                },
                'data[Visitor][cash_details]': {
                    required: "Please enter cash details",
                },
                'data[Visitor][Personal_property]': {
                    required: "Please enter registration of personal property",
                },
                'data[Visitor][to_whom]': {
                    required: "Please enter To whom you are meeting",
                },
                'data[Visitor][pp_cash]': {
                    required: "Please select type of pp cash",
                },
                'data[Visitor][pp_amount]': {
                    required: "Please enter amount given in PP cash",
                },
            },
               
    });
</script>
