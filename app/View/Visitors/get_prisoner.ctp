<?php echo $this->Form->input('Visitor.prisoner_id',array('div'=>false,'label'=>false,'onChange'=>'getPrisonernumer(this.value),getKinDetail(this.value),getRcvVisiter(this.value)','class'=>'form-control span11','type'=>'select','options'=>$prisonernameList, 'empty'=>'-- Select Prisoner Name --','id'=>'prisoner_no','style'=>'width:200px;','title'=>'Please choose prisoner name','value'=>'<?php echo isset($this->request->data["visitor"]["prisoner_id"])?$this->request->data["visitor"]["prisoner_id"]:"" ?>'));?>
<script type="text/javascript">
$('select').select2();

</script>