<?php echo $this->Form->input('DisciplinaryProceeding.internal_offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offenceList, 'empty'=>'-- Select Offences --','required','id'=>'internal_offence_id','title'=>'Please select offence name'));?>
<script type="text/javascript">
$('#internal_offence_id').select2();
</script>