<style type="text/css">
	.tooltips {
    position: relative;
    display: inline-block;
  
}
.required label:after { content:"*";color: red; }

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
#officialSubCategory{
	display: none;
}
#officialSubCategoryOther{
	display:none;
}
#privateSubCategory{
	display:none;
}
#privateSubCategoryOther{
	display:none;
}
</style>
<?php
//debug($this->request->data);
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
	$kindisplay = '';
	if($category == 'Private Visit'){
		$editdisplay1 = 'display:block;';
		$editdisplay2 = 'display:none;';

		$editdisplay = 'display:block;';
		$prisondisplay = 'display:block;';
		$kindisplay = 'display:block;';
		$prisonerItemsDisplay = 'display:block;';
		$display='display:block;';

	}
	if($category == 'Official Visit'){
		$display='display:block;';

		$editdisplay2 = 'display:none;';
		$editdisplay1 = 'display:block;';
		//$editdisplay = 'display:block;';
		$editdisplay3 = 'display:none;';
		$editdisplay4 = 'display:block;';
		$editdisplay = 'display:none;';
		$title = '';
		$required = 'false';
		$prisondisplay = 'display:block;';
		$kindisplay = 'display:none;';
		$prisonerItemsDisplay = 'display:none;';
	}
	else{
		$display='display:none;';

		$editdisplay = '';
		$editdisplay1 = '';
		$editdisplay2 = '';
		$editdisplay3 = '';
		$editdisplay4 = '';
		$title = 'Please select prisoner name.';
		$required = 'false';
		$prisondisplay = 'display:none;';
		$kindisplay = '';

	}	

}else{
	$display='display:none;';
	$editdisplay = '';
	$editdisplay1 = '';
	$editdisplay2 = '';
	$editdisplay3 = '';
    $editdisplay4 = '';
    $view = '';
    $title = 'Please select prisoner name.';
    $required = 'false';
    $prisondisplay = 'display:none;';
	$prisonerItemsDisplay = 'display:none;';

}
//debug($_SESSION);
//debug($this->request->data);
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
					<?php echo $this->Form->input('checked_allowed', array('id'=>'checked_allowed','type'=>'hidden','value'=>'0'))?>

					<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Visitor Category<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
							    $visitor = array('Private Visit' =>'Private Visit',
							    	             'Official visit'=>'Official visit',
							    	             'Visiting Baracks' => 'Visiting Baracks',
							    	             'Visiting Justices'=>'Visiting Justices'
							    	             // 'NGO'               => 'NGO'
							    	             );
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
							<div class="span6">
								<div class="control-group" id="officialSubCategory">
									<label class="control-label">Visitor Sub Category<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
							    $visitorsubcat = array('Govt officials' =>'Govt officials',
							    	             'NGO'=>'NGO',
							    	             'Lawyer'=>'Lawyer',
							    	             'Private Sector'=>'Private Sector',
							    	             'Foreign visitor'=>'Foreign visitor',
							    	             'Driver'=>'Driver/Suppliers',
							    	             'Staff'=>'Staff',
							    	             'Other'=>'Other'
							    	             // 'NGO'               => 'NGO'
							    	             );
								echo $this->Form->input('subcategory',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'select',
								  'id'=>'official_subcat',
								  'empty'=>'--Select Visitor subcategory--',
								  'options'=> $visitorsubcat,
								   
								));
							 ?>
									</div>
								</div>
								<div class="control-group" id="privateSubCategory">
									<label class="control-label">Visitor Sub Category<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
							    $privatesubcat = array(
							    	             'Relative'=>'Relative',
							    	             'Friend'=>'Friend',
							    	             'Other'=>'Other'
							    	             // 'NGO'               => 'NGO'
							    	             );
								echo $this->Form->input('subcategory',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'select',
								  'id'=>'private_subcat',
								  'empty'=>'--Select Visitor subcategory--',
								  'options'=> $privatesubcat,
								   
								));
							 ?>
									</div>
								</div>
							<!-- 	<div class="control-group" id="officialSubCategoryOther">
									<label class="control-label">Specify Other :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('other_sub',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'class'=>'form-control span11',
								  ));
							 ?>
									</div>
								</div> -->
								<div class="control-group" id="privateSubCategoryOther">
									<label class="control-label">Specify Other :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('other_sub',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'class'=>'form-control span11',
								  ));
							 ?>
									</div>
								</div>
							</div>
							<div class="span12">

							<div class="form-actions cont_btn" align="center" >
								<button type="button" class="btn btn-success" onclick="showFields()">Continue</button>
							</div>
							</div>
							<?php
							if(true){//$this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')
							 ?>
							 <div class="row-fluid">
							 		
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
								  'title'=>'Please select prison name',
								  'onChange'	=> "getPrisoner(this.value),getWhomToMeetUsers(this.value)",
								));
							 ?>
									</div>
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
									<label class="control-label">Bag No. :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('bag_no',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'number',
								  'class'=>'form-control span11',
								 
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
							
						</div>
						</div>
						<div class="row-fluid">
							<?php $vehicleTypelist =array('Private'=>'Private','Government'=>'Government'); ?>
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Vehicle Type :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('vehicle_type',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'select',
								  'empty'=>'--Select Vehicle Type--',
								  'options'=> $vehicleTypelist,
								  'title'=>'Please select vehicle type',
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
							<div class="control-group">
								<label class="control-label">Vehicle No. :</label>
								<div class="controls">
									<?php
									//debug($gateKeepers);
									 echo $this->Form->input('vehicle_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','maxlength'=>'15'));?>
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
									<?php echo $this->Form->input('contact_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','required','maxlength'=>'10'));?>
								</div>
							</div>
						</div>
						
						</div> 
						<div class="row-fluid">
							    
							<div class="span6">
							<div class="control-group">
								<label class="control-label">Voucher No. :</label>
								<div class="controls">
									<?php
									//debug($gateKeepers);
									 echo $this->Form->input('voucher_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','maxlength'=>'15'));?>
								</div>
							</div>
						</div>
						<div class="span6 baracks_div" style="display: none;">
							<div class="control-group">
								<label class="control-label">Barack No. <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('barack_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 ','type'=>'text','maxlength'=>'10'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
							<div class="span6 whom" style="<?php echo $display; ?><?php echo $editdisplay1; ?><?php echo $editdisplay4; ?>">
								<div class="control-group">
									<label class="control-label">To whom you are meeting :</label>
									<div class="controls">
									   <?php echo $this->Form->input('to_whom',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'select','options'=>$whomToMeetList,
									   'id'=>'to_whom','title'=>'Please select whom to meet'));?>
									</div>
								</div>
							</div>     
						</div>
						<?php 
// if(isset($this->request->data['Visitor']['category']) && $this->request->data['Visitor']['category'] =='Visiting Prisoner' || $this->request->data['Visitor']['category'] =='Official/Relatives'){
?>
					
						<div class="row-fluid thirdDiv" style="<?php echo $display; ?><?php echo $editdisplay; ?>">
										<div class="tooltips view" style="color: red;font-size: 13px;font-weight: 600;margin-top: 1px;margin-left:12px;cursor: help;<?php echo $view; ?>">Prisoner Details
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
							<div class="span6 pn">
								<div class="control-group">
									<label class="control-label">Prisoner Name <?php echo MANDATORY; ?> :</label>
									<div class="controls" id="prisonerListDiv">
									   <?php //echo $this->Form->input('name',array('div'=>false,'label'=>false,'onChange'=>'getPrisonernumer(this.value),getKinDetail(this.value)','class'=>'form-control span11','type'=>'select','options'=>$prisonernameList, 'empty'=>'-- Select Prisoner Name --','id'=>'prisoner_no',$title,'style'=>'width:200px;','title'=>'Please choose prisoner name'));?>
									   Please select Prison Name
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
						<span class="tooltiptexts">
						  <h5>Visitor Items</h5>
						</span>
						<div class="row-fluid secondDiv" style="<?php echo $display; ?>">
						<?php echo $this->element('visitor-items');?>
						</div>
						<div id="vehicleItemsDivWrap">
								<span class="tooltiptexts">
								  <h5>Vehicle Items</h5>
								</span>
								<div class="row-fluid secondDiv" style="<?php echo $display; ?>">
								<?php echo $this->element('vehicle-items');?>
								</div>
						</div>
						
						<span class="tooltiptexts" id="prisonerItemHeading" >
						  <h5>Prisoner Items to be collected</h5>
						</span>
						<div class="row-fluid secondDiv" id="prisonerItemForm" >
						<?php echo $this->element('visitor-prisoner-items');?>
						</div>

							

						<?php echo $this->element('visitor-prisoner-cash-items');?>

						<div class="row-fluid fourthDiv" style="<?php echo $display; ?>">
                                <center><h4>Visitor Details</h4></center>

						<?php echo $this->element('visitor-details');?>
						</div>
						<div class="row-fluid kinDiv" style="<?php echo (isset($kindisplay)) ? $kindisplay : ''; ?>">
                                <center><h4>Visitor Details</h4></center>
							
						<?php echo $this->element('kin-details');?>
						</div>
						<div class="row-fluid article" style="<?php echo $display; ?>">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Prohibited Articles :</label>
									<div class="controls">
									   <div class="tooltips" style="color: red;font-size: 13px;font-weight: 600;margin-top: 4px;cursor: help;">Information
  <span class="tooltiptexts">
  <h3>Prohibited Articles :</h3>
  <?php echo $article['Article']['name']; ?>
</span>
</div>

									</div>
								</div>
							</div>     
							<div class="span6 rpp" style="<?php echo $display; ?><?php echo $editdisplay; ?><?php echo $editdisplay3; ?>">
							<div class="control-group">
								<label class="control-label">Registration of Personal Property  :</label>
								<div class="controls">
									<?php echo $this->Form->input('Personal_property',array('div'=>false,'label'=>false,'placeholder'=>'Enter Registration of Personal Property','class'=>'form-control span11 alphanumericone','id'=>'Personal_property','title'=>'Please enter registration of personal property'));?>
								</div>
							</div>
						</div>
						</div>
						

						</div>
						<div class="form-actions prison" align="center" style="<?php echo $display; ?>">
						<?php echo $this->Form->input('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
						</div>
					
					<?php echo $this->Form->end();?>


					<!-- modal to choose NAT ID -->
						<div id="myIdDetailModal" class="modal fade" role="dialog">
						    <div class="modal-dialog">
						    	<?php echo $this->Form->create('NatIdForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
						        <div class="modal-content">
						            <div class="modal-header">
						                <button type="button" class="close" data-dismiss="modal">X</button>
						                <h4 class="modal-title">Add Visitor ID details</h4>
						            </div>
						            <div class="modal-body">
						            	<div>Not allowed to visit today .PLease fill NatId details to validate with Visitor pass.</div>
						                <div class="row-fluid">
						                    <div class="span10">
						                        <div class="control-group">
						                            <label class="control-label">Nat.ID Type<?php echo $req; ?>  :</label>
						                            <div class="controls">
						                                <?php echo $this->Form->input('nat_id_type',array('div'=>false,'label'=>false,'class'=>'form-control relation','style'=>'','type'=>'select','empty'=>'--Select--','options'=>$natIdList,'required','onChange'=>'selectNatId(this.value)','title'=>'Please select national id type'));?>
						                                </div>
						                        </div>                        
						                    </div>
						                </div>
						                <div class="row-fluid">
						                    <div class="span10">
						                        <div class="control-group">
						                            <label class="control-label">Nat.ID No<?php echo $req; ?>  :</label>
						                            <div class="controls">

                                				<?php echo $this->Form->input('.nat_id',array('div'=>false,'label'=>false,'class'=>'form-control alphanumeri','type'=>'text','required', 'placeholder'=>'Visitor Nat.Id No.','style'=>''));?>
						                            </div>
						                        </div>                        
						                    </div>                        
						                </div> 
						                <div class="form-actions" align="center">
						                    <?php echo $this->Form->input('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'cashsubmit_desroye','formnovalidate'=>true))?>
						                </div>                               
						            </div>
						            <div class="modal-footer">
						                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						            </div>
						        </div>
								<?php echo $this->Form->end();?>

						    </div>
						</div>
					<!-- modal ends -->
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
$ajaxUrlgetWhomToMeetUsers =$this->Html->url(array('controller'=>'Visitors','action'=>'getWhomToMeetUsers'));
$getPropertyTypeAjax = $this->Html->url(array('controller'=>'Properties','action'=>'getPropertyType'));
$checkIfAllowedToVisitAjax = $this->Html->url(array('controller'=>'Visitors','action'=>'checkIfAllowedToVisit'));



$id = '';
if(isset($this->request->data['Visitor']['name']) && $this->request->data['Visitor']['name'] !=''){
	$id = $this->request->data['Visitor']['name'];
}
?>
<script type="text/javascript">
$(document).ready(function(){
	var cat = $('#VisitorCategory').val();

	if(cat == 'Official visit'){

			$('#officialSubCategory').css('display','block');
			$('#privateSubCategory').css('display','none');
			var subcat = $('#official_subcat').val();

			if(subcat == 'Other'){
				$('#privateSubCategoryOther').css('display','block');
			}else{
				$('#privateSubCategoryOther').css('display','none');
			}
			showFields();


	}else if(cat =='Private Visit'){
			$('#privateSubCategory').css('display','block');
			$('#officialSubCategory').css('display','none');
			var subcat = $('#private_subcat').val();

			if(subcat == 'Other'){
				$('#privateSubCategoryOther').css('display','block');
			}else{
				$('#privateSubCategoryOther').css('display','none');
			}
		showFields();


	}else if(cat == 'Visiting Baracks'){
			$('#privateSubCategoryOther').css('display','none');
		$('#privateSubCategory').css('display','none');

		$('#officialSubCategory').css('display','none');
		$('#private_subcat').attr('disabled','disabled');
		$('#official_subcat').attr('disabled','disabled');


		showFields();
	}else if(cat == 'Visiting Justices'){
		$('#privateSubCategoryOther').css('display','none');
		$('#privateSubCategory').css('display','none');

		$('#officialSubCategory').css('display','none');
		$('#private_subcat').attr('disabled','disabled');
		$('#official_subcat').attr('disabled','disabled');


		showFields();
	}

$('#visitorPrisonerCashItemsDiv .visitorItemEntry input').on('blur',function(){
		var checkData = checkIfDataExist3();
		console.log(checkData);

	if(checkData == 'true'){
		$('#visitorPrisonerCashItemsDiv .visitorItemEntry input').attr('required','required');
		$('#visitorPrisonerCashItemsDiv .visitorItemEntry select').attr('required','required');
		$('#visitorPrisonerCashItemsDiv .select2-input').removeAttr('required');
		
	}else{

		$('#visitorPrisonerCashItemsDiv .visitorItemEntry input').removeAttr('required');
		$('#visitorPrisonerCashItemsDiv .visitorItemEntry select').removeAttr('required');
	}

});
$('#visitorPrisonerCashItemsDiv .visitorItemEntry select').on('change',function(){
		var checkData = checkIfDataExist3();
		console.log(checkData);
	if(checkData == 'true'){
		$('#visitorPrisonerCashItemsDiv .visitorItemEntry input').attr('required','required');
		$('#visitorPrisonerCashItemsDiv .visitorItemEntry select').attr('required','required');
		$('#visitorPrisonerCashItemsDiv .select2-input').removeAttr('required');

	}else{

		$('#visitorPrisonerCashItemsDiv .visitorItemEntry input').removeAttr('required');
		$('#visitorPrisonerCashItemsDiv .visitorItemEntry select').removeAttr('required');
	}

});
$('#visitorPrisonerItemsDiv .visitorItemEntry input').on('blur',function(){
		var checkData = checkIfDataExist2();
		console.log(checkData);

	if(checkData == 'true'){
		$('#visitorPrisonerItemsDiv .visitorItemEntry input').attr('required','required');
		$('#visitorPrisonerItemsDiv .visitorItemEntry select').attr('required','required');
		$('#visitorPrisonerItemsDiv .select2-input').removeAttr('required');

	}else{

		$('#visitorPrisonerItemsDiv .visitorItemEntry input').removeAttr('required');
		$('#visitorPrisonerItemsDiv .visitorItemEntry select').removeAttr('required');
	}

});
$('#visitorPrisonerItemsDiv .visitorItemEntry select').on('change',function(){
		var checkData = checkIfDataExist2();
		console.log(checkData);
	if(checkData == 'true'){
		$('#visitorPrisonerItemsDiv .visitorItemEntry input').attr('required','required');
		$('#visitorPrisonerItemsDiv .visitorItemEntry select').attr('required','required');
		$('#visitorPrisonerItemsDiv .select2-input').removeAttr('required');

	}else{

		$('#visitorPrisonerItemsDiv .visitorItemEntry input').removeAttr('required');
		$('#visitorPrisonerItemsDiv .visitorItemEntry select').removeAttr('required');
	}

});
$('#visitorItemsDiv .visitorItemEntry input').on('blur',function(){
		var checkData = checkIfDataExist();
		console.log(checkData);

	if(checkData == 'true'){
		$('#visitorItemsDiv .visitorItemEntry input').attr('required','required');
		$('#visitorItemsDiv .visitorItemEntry select').attr('required','required');
		$('#visitorItemsDiv .select2-input').removeAttr('required');

	}else{

		$('#visitorItemsDiv .visitorItemEntry input').removeAttr('required');
		$('#visitorItemsDiv .visitorItemEntry select').removeAttr('required');
	}

});
$('#visitorItemsDiv .visitorItemEntry select').on('change',function(){
		var checkData = checkIfDataExist();
		console.log(checkData);
	if(checkData == 'true'){
		$('#visitorItemsDiv .visitorItemEntry input').attr('required','required');
		$('#visitorItemsDiv .visitorItemEntry select').attr('required','required');
		$('#visitorItemsDiv .select2-input').removeAttr('required');

		
	}else{

		$('#visitorItemsDiv .visitorItemEntry input').removeAttr('required');
		$('#visitorItemsDiv .visitorItemEntry select').removeAttr('required');
	}

});

$('#VisitorCategory').on('change',function(){
	var cat = $('#VisitorCategory').val();
	$('#privateSubCategoryOther').css('display','none');

	if(cat == 'Official visit'){
			$('#officialSubCategory').css('display','block');
			$('#privateSubCategory').css('display','none');
			//$('#VisitorOtherSub').css('display','none');
			$('#official_subcat').removeAttr('disabled');
			$('#private_subcat').attr('disabled','disabled');

			$('#s2id_official_subcat').removeClass('select2-container-disabled');
			$('#official_subcat').select2();

	}else if(cat =='Private Visit'){
			$('#privateSubCategory').css('display','block');
			$('#officialSubCategory').css('display','none');
			//$('#VisitorOtherSub').css('display','none');
			$('#private_subcat').removeAttr('disabled');
			$('#official_subcat').attr('disabled','disabled');
			$('#s2id_private_subcat').removeClass('select2-container-disabled');
			$('#private_subcat').select2();
		
	}else if(cat =='Visiting Baracks'){
		$('#privateSubCategoryOther').css('display','none');
		$('#privateSubCategory').css('display','none');

		$('#officialSubCategory').css('display','none');
		$('#private_subcat').attr('disabled','disabled');
		$('#official_subcat').attr('disabled','disabled');



	}else if(cat == 'Visiting Justices'){
		$('#privateSubCategoryOther').css('display','none');
		$('#privateSubCategory').css('display','none');

		$('#officialSubCategory').css('display','none');
		$('#private_subcat').attr('disabled','disabled');
		$('#official_subcat').attr('disabled','disabled');
	}
	

	});


	$('#official_subcat').on('change',function(){
	var cat = $('#VisitorCategory').val();
	var subcat = $('#official_subcat').val();
	console.log(subcat);

	if(subcat == 'Other'){

				$('#privateSubCategoryOther').css('display','block');


		}else{
				$('#privateSubCategoryOther').css('display','none');


		}
	});
	$('#private_subcat').on('change',function(){
	var cat = $('#VisitorCategory').val();
	var subcat = $('#private_subcat').val();
	console.log(subcat);

		if(subcat == 'Other'){

				$('#privateSubCategoryOther').css('display','block');

		}else{
				$('#privateSubCategoryOther').css('display','none');

		}
	});
	

	
	
	<?php
	if(!$funcall->hasMainGate($this->Session->read('Auth.User.prison_id'))){
		?>
		 getPrisoner('<?php echo $this->Session->read('Auth.User.prison_id'); ?>');
		<?php
	}
	?>


	$('.kinDiv').hide();
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
		var cat = $('#VisitorCategory').val();

		if(cat == 'Official visit' ){
			$('.relationship_div').css('display','none');
		}else if(cat == 'Private Visit'){

			var subcat = $('#private_subcat').val();
			$('.relationship_div').css('display','block');
			if(subcat != 'Relative'){

				$('.relationship_div').css('display','none');
			}
		}
		});	
	
	$('#prisoner_no').select2();
	$('#name').select2();
	$('#pp_cash').select2();
	$('#to_whom').select2();
	$('.relation').select2();
	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });



		$('#NatIdFormAddForm').on('submit',function(e){
			e.preventDefault();
			var natIdType = $('#NatIdFormNatIdType').val();
			var natIdNo = $('#NatIdFormNatId').val();
			var prisoner_id = $('#prisoner_no').val();
			$('#VisitorName0NatIdType').val(natIdType);
			$('#VisitorName0NatId').val(natIdNo);

			    	var url = '<?php echo $checkIfAllowedToVisitAjax; ?>';
					$.post(url,{ 'prisoner_id':prisoner_id,'natIdType':natIdType,'natId':natIdNo }, function(res) {
					$('#myIdDetailModal').modal('hide');

					if(res.trim() == 'allowed'){
							$('#checked_allowed').val(1);
		                	dynamicAlertBox('Message','Visitor is allowed to meet this prisoner');
							//$('#VisitorAddForm').submit();
					}else if(res.trim() == 'not allowed'){
		                	dynamicAlertBox('Message','Visitor is Not allowed to meet this prisoner');
					}else{
		                	dynamicAlertBox('Message',res.trim());
					}


			});
					$('#myIdDetailModal').modal('hide');

		});

	//form submit
/*	$('#VisitorAddForm').on('submit',function(e){
		//e.preventDefault();
		var cat = $('#VisitorCategory').val();
		var checked_allowed = $('#checked_allowed').val();
			if(cat == 'Private Visit'){

				if(0 == checked_allowed){
					e.preventDefault();
					//alert('checking');
					var prisoner_id = $('#prisoner_no').val();
					var natIdType = $('#VisitorName0NatIdType').val();
					var natId = $('#VisitorName0NatId').val();
			    	var url = '<?php echo $checkIfAllowedToVisitAjax; ?>';
			    	if(natId == undefined || natId == ''){
			    		
						var natId = $('#visit_nat_id_no').val();
						var natIdType = $('#visit_nat_id').val();

			    	}

						if(prisoner_id != ''){
							$.post(url,{ 'prisoner_id':prisoner_id,'natIdType':natIdType,'natId':natId }, function(res) {
					            if(res.trim() == 'allowed'){
					            	$('#checked_allowed').val(1);
					 				$('#VisitorAddForm').submit();
					            }else{
									alert('Not allowed to visit');
					            }

				        	});
						}else{

							alert('Please Select Prisoner');
						}
						
				
				}
			}
		

	});*/
	
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

function checkIfDataExist(){
	var itemName = $('#VisitorItem0Item').val();
	var quantity = $('#VisitorItem0Quantity').val();
	var weight = $('#VisitorItem0Weight').val();
	var weightUnit = $('#VisitorItem0WeightUnit').val();
	//console.log('itemName' + itemName +',qua'+quantity +',we' + weight +',uni' +weightUnit );
	if(itemName != '' || quantity != '' || weight != '' || weightUnit !=''){
		return 'true';
	}else{
		return 'false';
	}
	
}
function checkIfDataExist2(){
	var itemName = $('#item_id2').val();
	var quantity = $('#VisitorPrisonerItem0Quantity').val();
	var weight = $('#VisitorPrisonerItem0Weight').val();
	var weightUnit = $('#VisitorPrisonerItem0WeightUnit').val();
	//console.log('itemName' + itemName +',qua'+quantity +',we' + weight +',uni' +weightUnit );
	if(itemName != '' || quantity != '' || weight != '' || weightUnit !=''){
		return 'true';
	}else{
		return 'false';
	}
	
}
function checkIfDataExist3(){
	var cash_detail = $('#cash_details').val();
	var amount = $('#pp_amount').val();
	var cash = $('#pp_cash').val();
	//console.log('itemName' + itemName +',qua'+quantity +',we' + weight +',uni' +weightUnit );
	if(cash_detail != '' || amount != '' || cash != ''){
		return 'true';
	}else{
		return 'false';
	}
	
}
function selectedProperty(id){
   // alert(id);
   if(id == 0){
    var propId = $('#item_id2').val();
    var updateElem = 'VisitorPrisonerItem'+id+'PropertyType';

   }else{
    var propId = $('#VisitorPrisonerItem'+id+'item_type').val();
    var updateElem = 'VisitorPrisonerItem'+id+'property_type';

   }

   if(id != ''){
   		$('#'+updateElem).attr('required','required');
   }else{
   		$('#'+updateElem).removeAttr('required');
   }
    var url = '<?php echo $getPropertyTypeAjax; ?>';
    $.post(url, { 'id':propId }, function(res) {
            $('#'+updateElem).html('');
             var match = res.split(',');
            var opt = '';
            if(res == 'allowed'){
                opt += '<option value="In Use">In Use</option>';
                opt += '<option value="In Store">In Store</option>';
                 $('#'+updateElem).html(opt);
                $('#'+updateElem).val('In Use');
                $('#'+updateElem).change();
                $('#'+updateElem).removeAttr('readonly');
                $('#'+updateElem).removeAttr('disabled');

            }else if(match[0] == 'prohibited'){
                opt += '<option value="'+match[1]+'">'+match[1]+'</option>';
                $('#'+updateElem).html(opt);
                $('#'+updateElem).val(match[1]);
                $('#'+updateElem).change();
                $('#'+updateElem).attr('readonly','readonly');
                $('#'+updateElem).attr('disabled','disabled');


            }else{
                 $('#'+updateElem).html(opt);
            }

        });
    }

function getWhomToMeetUsers(prison_id){
        var url ='<?php echo $ajaxUrlgetWhomToMeetUsers ?>';
    $.post(url, {'prison_id':prison_id}, function(res) {
        if (res) {

            //$('#respo').html(res);
            //alert('hi');
            $('#to_whom').html(res);
            //console.log(res);
             }
    });
        }       
function makeFieldMandatory(item){
	
	$('#'+item).attr('required','required');
	$('#'+item).prop('required',true);
	$('#'+item).parent().parent().addClass('required');

}
function makeFieldNonMandatory(item){
	$('#'+item).removeAttr('required');
	$('#'+item).prop('required',false);
	$('#'+item).parent().parent().removeClass('required');
}
function makeVisitorFieldMandatory(item){
	$('#'+item).attr('required','required');
	$('#'+item).prop('required',true);
	$('#'+item).parent().addClass('required');
}
function makeVisitorFieldNonMandatory(item){
	$('#'+item).removeAttr('required');
	$('#'+item).prop('required',false);
	$('#'+item).parent().removeClass('required');
	$('#'+item).parent().parent().removeClass('required');

}
function selectNatId(id){
	if(id== 1){
		$('.nid').addClass('numeric');
	}else{
		$('.nid').removeClass('numeric');
	}
}
function showFields(){
	var cat = $('#VisitorCategory').val();
	if(cat != '' || cat != undefined){

	}
	var prison_id =$('#VisitorPrisonId').val();
	if(prison_id != ''){
		getPrisoner(prison_id);

	}

			$('#visit_first_name').attr('disabled','disabled');
			$('#visit_nat_id').attr('disabled','disabled');
			$('#visit_nat_id_no').attr('disabled','disabled');
	
		if(cat == 'Official visit' ){

			$('#visit_first_name').removeAttr('disabled');
			$('#visit_nat_id').removeAttr('disabled');
			$('#visit_nat_id_no').removeAttr('disabled');
			//makeFieldMandatory('VisitorItem0Quantity');
			makeFieldNonMandatory('VisitorVehicleNo');
			makeFieldNonMandatory('Personal_property');
			makeFieldMandatory('to_whom');
			makeVisitorFieldMandatory('visit_nat_id_no');
			makeVisitorFieldMandatory('visit_nat_id');
			makeVisitorFieldMandatory('visit_first_name');


			makeVisitorFieldNonMandatory('VisitorName0Name');
			makeVisitorFieldNonMandatory('VisitorName0NatIdType');
			makeVisitorFieldNonMandatory('VisitorName0NatId');
			$('.baracks_div').hide();
			makeVisitorFieldNonMandatory('VisitorBarackNo');
			makeVisitorFieldNonMandatory('VisitorVehicleType');

			makeVisitorFieldNonMandatory('VehicleItem0VoucherNo');
			makeVisitorFieldNonMandatory('VehicleItem0Item');
			makeVisitorFieldNonMandatory('VehicleItem0Quantity');
			makeVisitorFieldNonMandatory('VehicleItem0Description');
			$('#vehicleItemsDivWrap').hide();

			
			
			
			$('.relationship_div').css('display','none');
			$('.visitorPrisonerCashItemsWrapper').css('display','none');
			

			$('.relationship_select_div').css('display','none');
			$('#prisonerItemHeading').css('display','none');
			$('#prisonerItemForm .span12').css('display','none');
			$('#visitorPrisonerItemsDiv').css('display','none');
			$('#private_subcat').attr('disabled','disabled');
			//$('#official_subcat').removeAttr('required');


			var subcat = $('#official_subcat').val();

			if(subcat == 'Driver'){
					$('.prison').show();
					$('.firstDiv').show();
					$('.secondDiv').show();
					$('.thirdDiv').hide();
					$('.fourthDiv').show();
					$('.address').show();
					$('.contact').show();
					$('.cashdetails').show();
					$('.article').show();
					$('#visitorPrisonerCashItemsDiv').hide();
					$('.rpp').hide();
					$('.whom').show();
				    $('.kinDiv').show();
					makeFieldMandatory('VisitorVehicleNo');
					makeFieldMandatory('VisitorVehicleType');

			makeFieldMandatory('VehicleItem0VoucherNo');
			makeFieldMandatory('VehicleItem0Item');
			makeFieldMandatory('VehicleItem0Quantity');
			makeFieldMandatory('VehicleItem0Description');
			$('#vehicleItemsDivWrap').show();

				}else if(subcat == 'NGO'){
					$('.prison').show();
					$('.firstDiv').show();
					$('.secondDiv').show();
					$('.thirdDiv').hide();
					$('.fourthDiv').show();
					$('.address').show();
					$('.contact').show();
					$('.cashdetails').show();
					$('.article').show();
					$('#visitorPrisonerCashItemsDiv').hide();
					$('.rpp').hide();
					$('.kinDiv').show();
					$('.whom').show();
			

			}else if(subcat != '' && subcat != undefined && subcat != 'Other'){
					$('.prison').show();
					$('.firstDiv').show();
					$('.secondDiv').show();
					$('.thirdDiv').hide();
					$('.fourthDiv').show();
					$('.address').show();
					$('.contact').show();
					$('.cashdetails').show();
					$('.article').show();
					$('.rpp').hide();
					$('.whom').show();
					  $('.kinDiv').show();

			}else if(subcat == 'Other'){
				othertext =$('#VisitorOtherSub').val();
				if(othertext != ''){
						$('.prison').show();
						$('.firstDiv').show();
						$('.secondDiv').show();
						$('.thirdDiv').hide();
						$('.fourthDiv').show();
						$('.address').show();
						$('.contact').show();
						$('.cashdetails').show();
						$('.article').show();
						$('.rpp').hide();
						$('.whom').show();
					    $('.kinDiv').show();

				}else{
					alert('Please Enter Subcategory other');
				}

			}else{
				alert('Select Subcategory of Visitor');

			}

			

		}else if(cat == 'Private Visit' ){

			makeVisitorFieldNonMandatory('VehicleItem0VoucherNo');
			makeVisitorFieldNonMandatory('VehicleItem0Item');
			makeVisitorFieldNonMandatory('VehicleItem0Quantity');
			makeVisitorFieldNonMandatory('VehicleItem0Description');
			makeVisitorFieldNonMandatory('VisitorVehicleType');

			$('#vehicleItemsDivWrap').hide();

			//makeFieldMandatory('VisitorItem0Quantity');
			makeFieldNonMandatory('VisitorVehicleNo');
			makeFieldMandatory('Personal_property');
			makeFieldNonMandatory('to_whom');

			makeVisitorFieldNonMandatory('visit_nat_id_no');
			makeVisitorFieldNonMandatory('visit_nat_id');
			makeVisitorFieldNonMandatory('visit_first_name');


			makeVisitorFieldMandatory('VisitorName0Name');
			makeVisitorFieldMandatory('VisitorName0NatIdType');
			makeVisitorFieldMandatory('VisitorName0NatId');
			$('.baracks_div').hide();
			makeVisitorFieldNonMandatory('VisitorBarackNo');
			makeVisitorFieldNonMandatory('VisitorVehicleType');


			$('#official_subcat').attr('disabled','disabled');
			$('#private_subcat').removeAttr('required');
			$('.visitorPrisonerCashItemsWrapper').css('display','block');

			$('.relationship_div').css('display','block');
			$('#visitorPrisonerItemsDiv').css('display','block');
			$('#prisonerItemHeading').css('display','block');
			$('#prisonerItemForm .span12').css('display','block');
			$('#visitorPrisonerItemsDiv').css('display','block');
			var subcat = $('#private_subcat').val();
		
			if(subcat != 'Relative'){
				$('.relationship_div').css('display','none');
			}

			if(subcat != '' && subcat != undefined && subcat != 'Other'){
				//alert(subcat);
				$('.relationship_select').attr('disabled','disabled');
				$('.relationship_select').css('display','none');
				$('.relationship_select_div').css('display','none');
				$('.prison').css('display','block');
				$('.firstDiv').show();
				$('.firstDiv').show();
				$('.prison').show();
				$('.secondDiv').show();
				$('.thirdDiv').show();
				$('.fourthDiv').hide();
				$('.address').show();
				$('.contact').show();
				$('.cashdetails').show();
				$('.article').show();
				$('#visitorPrisonerCashItemsDiv').show();
				$('.app').show();
				$('.whom').hide();
				$('.rpp').show();

			}else if(subcat == 'Other'){

				othertext =$('#VisitorOtherSub').val();
				if(othertext != ''){
					$('.relationship_select').attr('disabled','disabled');
					$('.relationship_select').css('display','none');
					$('.relationship_select_div').css('display','none');
					$('.prison').css('display','block');
					$('.firstDiv').show();
					$('.firstDiv').show();
					$('.prison').show();
					$('.secondDiv').show();
					$('.thirdDiv').show();
					$('.fourthDiv').hide();
					$('.address').show();
					$('.contact').show();
					$('.cashdetails').show();
					$('.article').show();
					$('#visitorPrisonerCashItemsDiv').show();
					$('.app').show();
					$('.whom').hide();
					$('.rpp').show();

				}else{
					alert('Please Enter Subcategory other');
				}

			}else{
				alert('Select Subcategory of Visitor');

			}
				var VisitorPrisonerNo = $('#VisitorPrisonerNo').val();
				if(prison_id != '' && VisitorPrisonerNo != ''){
					$('.fourthDiv').show();
					/*
					makeVisitorFieldNonMandatory('prisoner_no');
					$('#prisonerListDiv').css('display','none');
					$('.pn').css('display','none');*/


				}
		}else if(cat == 'Visiting Baracks'){

			makeVisitorFieldNonMandatory('VehicleItem0VoucherNo');
			makeVisitorFieldNonMandatory('VehicleItem0Item');
			makeVisitorFieldNonMandatory('VehicleItem0Quantity');
			makeVisitorFieldNonMandatory('VehicleItem0Description');
			$('#vehicleItemsDivWrap').hide();
			makeVisitorFieldNonMandatory('VisitorVehicleType');

			makeFieldNonMandatory('VisitorVehicleNo');
			makeFieldNonMandatory('Personal_property');
			makeFieldMandatory('to_whom');
			makeVisitorFieldMandatory('visit_nat_id_no');
			makeVisitorFieldMandatory('visit_nat_id');
			makeVisitorFieldMandatory('visit_first_name');


			makeVisitorFieldNonMandatory('VisitorName0Name');
			makeVisitorFieldNonMandatory('VisitorName0NatIdType');
			makeVisitorFieldNonMandatory('VisitorName0NatId');
			
			makeVisitorFieldMandatory('VisitorBarackNo');
			
			
			$('.relationship_div').css('display','none');
			$('.visitorPrisonerCashItemsWrapper').css('display','none');
			

			$('.relationship_select_div').css('display','none');
			$('#prisonerItemHeading').css('display','none');
			$('#prisonerItemForm .span12').css('display','none');
			$('#visitorPrisonerItemsDiv').css('display','none');
			$('#private_subcat').attr('disabled','disabled');
			$('#official_subcat').attr('disabled','disabled');

			$('.prison').show();
						$('.firstDiv').show();
						$('.secondDiv').show();
						$('.thirdDiv').hide();
						$('.fourthDiv').show();
						$('.address').show();
						$('.contact').show();
						$('.cashdetails').show();
						$('.article').show();
						$('.rpp').hide();
						$('.whom').show();
					    $('.kinDiv').show();
					    $('.baracks_div').show();

					    


		}else if(cat == 'Visiting Justices'){
			makeVisitorFieldNonMandatory('VisitorVehicleType');

			makeVisitorFieldNonMandatory('VehicleItem0VoucherNo');
			makeVisitorFieldNonMandatory('VehicleItem0Item');
			makeVisitorFieldNonMandatory('VehicleItem0Quantity');
			makeVisitorFieldNonMandatory('VehicleItem0Description');
			$('#vehicleItemsDivWrap').hide();

			makeFieldNonMandatory('VisitorVehicleNo');
			makeFieldNonMandatory('Personal_property');
			makeFieldMandatory('to_whom');
			makeVisitorFieldMandatory('visit_nat_id_no');
			makeVisitorFieldMandatory('visit_nat_id');
			makeVisitorFieldMandatory('visit_first_name');


			makeVisitorFieldNonMandatory('VisitorName0Name');
			makeVisitorFieldNonMandatory('VisitorName0NatIdType');
			makeVisitorFieldNonMandatory('VisitorName0NatId');
			
			makeVisitorFieldNonMandatory('VisitorBarackNo');
			
			
			$('.relationship_div').css('display','none');
			$('.visitorPrisonerCashItemsWrapper').css('display','none');
			

			$('.relationship_select_div').css('display','none');
			$('#prisonerItemHeading').css('display','none');
			$('#prisonerItemForm .span12').css('display','none');
			$('#visitorPrisonerItemsDiv').css('display','none');
			$('#private_subcat').attr('disabled','disabled');
			$('#official_subcat').attr('disabled','disabled');

			$('.prison').show();
						$('.firstDiv').show();
						$('.secondDiv').show();
						$('.thirdDiv').hide();
						$('.fourthDiv').show();
						$('.address').show();
						$('.contact').show();
						$('.cashdetails').show();
						$('.article').show();
						$('.rpp').hide();
						$('.whom').show();
					    $('.kinDiv').show();
					    $('.baracks_div').hide();

					    

		}
		else{
			alert('Select Category of Visitor');
		}
	
		$('select').select2();
		$('#NatIdFormNatIdType').select2('destroy');
}

function validateForm(){
	
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

			makeVisitorFieldMandatory('VisitorName0Name');
			makeVisitorFieldMandatory('VisitorName0NatIdType');
			makeVisitorFieldMandatory('VisitorName0NatId');
				
            }

        });
    }
}
function getRcvVisiter(prisoner_id) 
{ 
    

    if(prisoner_id != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'Visitors','action'=>'getNextReceive'));?>/'+prisoner_id;
    
        /*$.post(strURL,{},function(data){  
            
            if(data.trim()!='') { 
                dynamicAlertBox('Message', data);
            }
        });*/
    }
    if(prisoner_id != ''){
    				var natIdType = '';
					var natId = '';
			    	var url = '<?php echo $checkIfAllowedToVisitAjax; ?>';
			$.post(url,{ 'prisoner_id':prisoner_id,'natIdType':natIdType,'natId':natId }, function(res) {
			if(res.trim() == 'allowed'){
					$('#checked_allowed').val(1);
                	dynamicAlertBox('Message','Visitor is allowed today to meet this prisoner');
					//$('#VisitorAddForm').submit();
			}else if(res.trim() == 'not allowed'){
					$('#myIdDetailModal').modal('show');
				$('#NatIdFormNatIdType').select2('destroy');

			}else{
                	dynamicAlertBox('Message',res.trim());
			}


				});
	}

}
function getPrisoner(prison_id) 
{ 
    if(prison_id != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'Visitors','action'=>'getPrisoner'));?>';
    
        $.post(strURL,{"prison_id":prison_id},function(data){  
            $('#prisonerListDiv').html(data);
           <?php if(isset($this->request->data['Visitor']['id'])){?>
            	$('#prisonerListDiv #prisoner_no').select2('val',<?php echo $this->data['Visitor']['prisoner_id']; ?>);
            	//$('#prisonerListDiv #prisoner_no').select2('readonly');
            	getPrisonernumer(<?php echo $this->data['Visitor']['prisoner_id'] ?>);
           <?php } ?> 	
           
           //getPrisonernumer
            var cat = $('#VisitorCategory').val();

				if(cat == 'Official visit' ){
					$('#prisoner_no').removeAttr('required');
					$('#prisoner_no').prop('required',false);
				}else if(cat == 'Private Visit'){
					$('#prisoner_no').attr('required','required');
					$('#prisoner_no').prop('required',true);
				}
        });


    }
}
function getKinDetail(name) 
{ 
    $('#VisitorPrisonerNo').val('');
    if(name != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'visitors','action'=>'getKinDetail'));?>';
    
        $.post(strURL,{"name":name},function(data){  
            if(data) { 
           $('.kinDiv').show();
           $(".fourthDiv").html('');

           $("#kinDetails").html(data);
           var cat = $('#VisitorCategory').val();

				if(cat == 'Private Visit'){
					var subcat = $('#private_subcat').val();
						//makeFieldMandatory('#kinDetails #VisitorName0Name');
					if(subcat != 'Relative'){
						$('.relationship_div').css('display','none');
					}
				}else{
						$('.relationship_div').css('display','none');
				}
       }else{
       	  $('.kinDiv').show();
       }
        });
    }else{
    	$('.kinDiv').hide();
    	$("#kinDetails").html('');
    }
}
<?php if(count($this->request->data) == 0){ ?>

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
                'data[Visitor][contact_no]': {
                    required: true,
                },
                'data[Visitor][address]': {
                    required: true,
                },
                /*'data[VisitorName][0][name]':{
                	required: true,
                },
                'data[VisitorName][0][lname]':{
                	required:true,
                },
                'data[VisitorName][0][nat_id_type]':{
                	required:true,
                },
                'data[VisitorName][0][nat_id]':{
                	required:true,
                },*/
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
                'data[Visitor][contact_no]': {
                    required: "Please Enter Contact number",
                },
                'data[Visitor][address]': {
                    required: "Please Enter Address",
                },
                /*'data[VisitorName][0][name]':{
                	required:"PLease Enter Name",
                },
                'data[VisitorName][0][lname]':{
                	required:"PLease Enter Last Name",
                },
                'data[VisitorName][0][nat_id_type]':{
                	required:"PLease Select Id Type",
                },
                'data[VisitorName][0][nat_id]':{
                	required:"PLease Enter Nat Id number",
                },*/
                
                
            }    
    });
       
</script>
