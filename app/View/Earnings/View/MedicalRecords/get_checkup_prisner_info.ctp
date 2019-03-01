<?php echo $this->Form->input('MedicalCheckupRecord.prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select prisoner --','options'=>$prisonerListData1, 'class'=>'form-control','required', 'id'=>'prisoner_id'));?>
<?php
if(empty($prisonerListData1)){
	$msg="No prisoner found for ".$check_up." check up";
}
else{
	$msg="";
}
?>
<div style="margin-top:5px;"><label class="err_msg" style="color: #f00;"><?php echo $msg;?></label></div>