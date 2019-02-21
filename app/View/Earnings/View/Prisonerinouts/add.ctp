<?php
if(isset($this->data['Prisonerinout']['date']) && $this->data['Prisonerinout']['date'] != ''){
    $this->request->data['Prisonerinout']['date'] = date('d-m-Y', strtotime($this->data['Prisonerinout']['date']));
}
?>
<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
#s2id_nationality_name2
{
    margin-top: 15px;
}
</style>
<?php
$data = $this->request->data;
//debug($this->request->data); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add New Record Prisoner In/out </h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Prisoner and Persons in/out Record List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('Prisonerinout',array('class'=>'form-horizontal','id'=>'prisonerInOutForm'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
						<div class="row">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Date<?php echo MANDATORY; ?> </label>
									<div class="controls">
									   <?php
									        $datadate = date('d-m-Y');
											echo $this->Form->input('date',array(
											  'div'=>false,
											  'label'=>false,
											  'type'=>'text',
											  //'class'=>'form-control mydate span11',
											  'class'=>'form-control span11',
											  'data-date-format'=>"dd-mm-yyyy",
											  'readonly'=>'readonly',
											  'required',
											  'title'=>'Please choose date.',
											  'value'=>$datadate
											));
										 ?>
									</div>
								</div>
							</div>   
							<div class="span6 prisners">
								<div class="control-group">
									<label class="control-label">Prisoner Number<?php echo MANDATORY; ?></label>
									<div class="controls">
										<?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'onChange'=>'getPrisonerStationInfo(this.value)','class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonerList, 'empty'=>'','required','title'=>'Please select prisoner no.'));?>
								</div>
								</div>
							</div>

							<div class="span6">
								<div class="control-group">
									<!-- <label class="control-label">Category<?php //echo MANDATORY; ?></label> --> 
									<div class="controls">
										<?php echo $this->Form->input('category',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden','value'=>"Prisoners",'required','id'=>'category','title'=>'Please select category.',));?>
									</div>
								</div>
							</div>

							
						</div>
					    <div class="row">
					   		
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Name <?php echo MANDATORY; ?> </label>
									<div class="controls">
										<?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Name','class'=>'form-control span11','required','id'=>'name','readonly','title'=>'Please enter name.'));?>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Destination<?php echo MANDATORY; ?> </label>
									<div class="controls">
										<?php echo $this->Form->input('destination',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter destination','class'=>'form-control span11 alphanumeric','required','title'=>'Please enter destination.'));?>
									</div>
								</div>
							</div>
						</div>						
						<div class="row">
							<div class="span6 whom">
								<div class="control-group">
									<label class="control-label">To whom you are<br>meeting  </label>
									<div class="controls">
										<?php echo $this->Form->input('to_whom',array('div'=>false,'label'=>false,'placeholder'=>'Enter Name','class'=>'form-control span11 alpha','title'=>'Please enter to whom you are meeting.'));?>
									</div>
								</div>
							</div>
							 <div class="span6">
								<div class="control-group">
									<label class="control-label">Reason </label>
									<div class="controls">
										<?php echo $this->Form->textarea('reason',array('div'=>false,'label'=>false,'placeholder'=>'Enter Reason ','class'=>'form-control span11','title'=>'Please enter reason.'));?>
									</div>
								</div>
							</div>
						</div>
						<?php if(isset($this->data['Prisonerinout']['id'])) { ?>
                                     <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Update', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true,'id'=>'updateId'))?>
                                        
                                        
								<?php echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-danger ')); ?>
								 </div>

                                    
                                <?php }else {?>
						<div class="form-actions" align="center">
						<?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,))?>
						</div>
						<?php } ?>
					<?php echo $this->Form->end();?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
$('.in').hide();
$('#pmis_loader').hide();
	
	if($('#prisoner_no').val() != '')
	{
		// alert(1);
		getPrisonerStationInfo($('#prisoner_no').val());
	}
	var category=$('#category option:selected').val();
});

if($('#PrisonerinoutId').val() != ''){
	$("#category").attr("disabled", true);

}else{
	$("#category").attr("disabled", false);
}

if($('#category').val() == 'Persons'){
	$('.prisners').hide();

}
if($('#category').val() == 'Prisoners'){
	$('#PrisonerinoutTimeIn').removeAttr('required');
	$('.in').hide();

}



$('#submit').click(function(){
        if($("#prisonerInOutForm").valid()){
            if( !confirm('Are you sure to save?')) {
                return false;
            }
        }
    });

$('#updateId').click(function(){
        if($("#prisonerInOutForm").valid()){
            if( !confirm('Are you sure to Update?')) {
                return false;
            }
        }
    });



/////////////////////////////////////////////////////
// function validateForm(){
// 	var errcount = 0;
// 	$('.validate').each(function(){
// 		if($(this).val() == ''){
// 			errcount++;
// 			$(this).addClass('error-text');
// 			$(this).removeClass('success-text'); 
// 		}else{
// 			$(this).removeClass('error-text');
// 			$(this).addClass('success-text'); 
// 		}        
// 	});        
// 	if(errcount == 0){            
// 		if(confirm('Are you sure want to save?')){  
// 			return true;            
// 		}else{               
// 			return false;           
// 		}        
// 	}else{   
// 		return false;
// 	}  
// }
function getPrisonerStationInfo(id) 
{ 
    // $('#name').val('');
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
$(function(){
        $("#PrisonerinoutAddForm").validate({
     
      		ignore: "",
      	});
});


</script>
