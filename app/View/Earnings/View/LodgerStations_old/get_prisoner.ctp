<?php echo $this->Form->input($model_name.'.prisoner_id',array('div'=>false,'label'=>false,'onChange'=>'getPrisonernumer(this.value),getKinDetail(this.value)','class'=>'form-control span11','type'=>'select','options'=>$prisonernameList, 'empty'=>'-- Select Prisoner Name --','id'=>'prisoner_no','style'=>'width:200px;','title'=>'Please choose prisoner name'));?>
<script type="text/javascript">
$('select').select2();
</script>