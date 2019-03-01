<?php
// $selected = array(1, 3);
if(isset($escortingOfficerList) && is_array($escortingOfficerList) && count($escortingOfficerList)>0){
	// echo $this->Form->input('EscortTeam.members', array('label'=>false,'multiple' => 'checkbox','selected' => $selected, 'options' => $escortingOfficerList)); //, 'selected' => $selected
	// debug($selected);
	echo $this->Form->input('EscortTeam.members.', array('type'=>'select','label'=>false,'required'=>true,'multiple','default' => $selected, 'options' => $escortingOfficerList,"title"=>"Please select members")); //, 'selected' => $selected

}else{
	echo "Please select prison name";
}

?>
<script type="text/javascript">
	$("#EscortTeamMembers").select2();
</script>