<div class="span6">
    <div class="control-group">
        <label class="control-label">Duration <?php echo $req; ?> :</label>
        <div class="controls">
            <label for="" class="span6"><?php echo $this->Form->input('InPrisonPunishment.duration_month',array('div'=>false,'label'=>false,'class'=>'form-control span6','type'=>'text', 'placeholder'=>'MM','title'=>"Month Require",'style'=>"","onkeyup"=>"checkMaxDays()","id"=>"duration_month"));?> Month &nbsp;&nbsp;&nbsp; </label>
            <label for="" class="span6">
            <?php echo $this->Form->input('InPrisonPunishment.duration_days',array('div'=>false,'label'=>false,'class'=>'form-control span6','type'=>'text', 'placeholder'=>'DD','title'=>"Days Require",'style'=>"","onkeyup"=>"checkMaxDays()","id"=>"duration_days"));?> Days</label>
        </div>
    </div>
</div> 

<?php
if(in_array($internal_offence_id, array(2))){
?>
<div class="span6">
    <div class="control-group">
        <label class="control-label">Total Earning <?php echo $req; ?> :</label>
        <div class="controls">
            <?php 
            $totalEarning = $funcall->getEarningAmount($prisoner_id);
            echo $totalEarning;
            echo $this->Form->input('InPrisonPunishment.current_amount',array('type'=>"hidden","value"=>$totalEarning,"id"=>"current_amount"));
            ?>
        </div>
    </div>
</div> 
<div class="span6">
    <div class="control-group">
        <label class="control-label">Deducted Amount <?php echo $req; ?> :</label>
        <div class="controls">
            <?php echo $this->Form->input('InPrisonPunishment.deducted_amount',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter deducted amount','required','readonly'=>'readonly','title'=>'Please provide duration','id'=>'deducted_amount'));?>
        </div>
    </div>
</div> 
<?php
}else{
    if($internal_offence_id!=7){
?>
<div class="span6">
    <div class="control-group">
        <label class="control-label">Punishment Start Date <?php echo $req; ?> :</label>
        <div class="controls">
            <?php echo $this->Form->input('InPrisonPunishment.punishment_start_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Punishment Start Date','required','readonly'=>'readonly','title'=>'Please provide start date','id'=>'punishment_start_date'));?>
        </div>
    </div>
</div> 
<div class="span6">
    <div class="control-group">
        <label class="control-label">Punishment End Date <?php echo $req; ?> :</label>
        <div class="controls">
            <?php echo $this->Form->input('InPrisonPunishment.punishment_end_date',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Punishment End Date','required','readonly'=>'readonly','title'=>'Please provide duration','id'=>'punishment_end_date'));?>
        </div>
    </div>
</div> 
<?php   
    }
}
if($internal_offence_id==3){
?>
<div class="span6">
    <div class="control-group">
        <label class="control-label">Current Stage <?php echo $req; ?> :</label>
        <div class="controls">
            <?php 
            echo $stageArr['current']['name'];
            echo $this->Form->input('InPrisonPunishment.current_stage_id',array('type'=>"hidden","value"=>$stageArr['current']['id']));
            ?>
        </div>
    </div>
</div> 
<div class="span6">
    <div class="control-group">
        <label class="control-label">Demotion Stage <?php echo $req; ?> :</label>
        <div class="controls">
            <?php 
            echo $stageArr['demotion']['name'];
            echo $this->Form->input('InPrisonPunishment.demotion_stage_id',array('type'=>"hidden","value"=>$stageArr['demotion']['id']));
            ?>
        
        </div>
    </div>
</div> 
<?php
}
if($internal_offence_id==4){
?>
<div class="span6">
    <div class="control-group">
        <label class="control-label">Current Grade <?php echo $req; ?> :</label>
        <div class="controls">
            <?php 
            echo $earningArr['current']['name'];
            echo $this->Form->input('InPrisonPunishment.current_stage_id',array('type'=>"hidden","value"=>$earningArr['current']['id']));
            ?>
        </div>
    </div>
</div> 
<div class="span6">
    <div class="control-group">
        <label class="control-label">Demotion Grade <?php echo $req; ?> :</label>
        <div class="controls">
            <?php 
            echo $earningArr['demotion']['name'];
            echo $this->Form->input('InPrisonPunishment.demotion_stage_id',array('type'=>"hidden","value"=>$earningArr['demotion']['id']));
            ?>
        
        </div>
    </div>
</div> 
<?php
}
if($internal_offence_id==7){
?>
<div class="span6">
    <div class="control-group">
        <label class="control-label">Loss Type <?php echo $req; ?> :</label>
        <div class="controls">
            <?php 
            $lossType = (isset($punishmentData['InPrisonPunishment']['loss_type']) && $punishmentData['InPrisonPunishment']['loss_type']!='') ? $punishmentData['InPrisonPunishment']['loss_type'] : '';
            echo $this->Form->input('InPrisonPunishment.loss_type',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array("Direct"=>"Direct","Indirect"=>"Indirect"), 'empty'=>'-- Select --','required','default'=>$lossType,'title'=>"Please loss type"));?>
        </div>
    </div>
</div> 
<div class="span6">
    <div class="control-group">
        <label class="control-label">Remission :</label>
        <div class="controls">
            <?php 
            $remission = json_decode($funcall->getName($prisoner_id,"Prisoner","remission"));
            echo (isset($remission->years) && $remission->years!='') ? $remission->years." years " : '';
            echo (isset($remission->months) && $remission->months!='') ? $remission->months." months " : '';
            echo (isset($remission->days) && $remission->days!='') ? $remission->days." days " : '';
            ?>
        </div>
    </div>
</div> 
<?php
}
if($internal_offence_id==6){
?>
<div class="span12">
    <div class="control-group">
        <label class="control-label">Privilages <?php echo $req; ?> :</label>
        <div class="controls">
            <?php
            if(isset($privilegesList) && is_array($privilegesList) && count($privilegesList)>0){
                foreach ($privilegesList as $key => $value) {
                    echo $this->Form->input('InPrisonPunishment.privilege_id.'.$key, 
                                array(
                                  'label'=>$value, 
                                  'type'=>'checkbox',
                                  'div' => false,
                                  'value'   => $key,
                                  'default' => $key,
                                  'hiddenField'=>false
                             ));
                }
            }else{
                echo "No any privilages given";
            }            
            ?>
        </div>
    </div>
</div> 
<?php
}
?>
<input type="hidden" name="max_days" id="max_days" value="<?php echo (isset($maxDays) && $maxDays!=0) ? $maxDays : ''; ?>">
<input type="hidden" name="earningRate" id="earningRate" value="<?php echo (isset($earningRate) && $earningRate!=0) ? $earningRate : 0; ?>">
<div class="clearfix"></div> 
<script type="text/javascript">
$(document).ready(function(){
    $('.mydate').datepicker({
        format: 'dd-mm-yyyy',
        autoclose:true
    }).on('changeDate', function (ev) {
        checkMaxDays();
        $(this).datepicker('hide');
        $(this).blur();
    });
});

function checkMaxDays(){
    var month_days = 0;
    var max_days = $("#max_days").val();
    var days = 0;
    if($("#duration_days").val()!=''){
        days = $("#duration_days").val();
    }
    if($("#duration_month").val()!=''){
        month_days = $("#duration_month").val() * 30;
    }
    var total = parseInt(month_days) + parseInt(days); 

    if($("#internal_punishment_id").val()==2){        
        $("#deducted_amount").val(total * parseFloat($("#earningRate").val()));
        if(parseFloat($("#current_amount").val()) < parseFloat($("#deducted_amount").val())){
            dynamicAlertBox('Message', 'no earning available');
            $("#deducted_amount").val('');
        }
    }

    // alert(total); 
    if(total == 0){
        $("#punishment_end_date").val('');
        $("#deducted_amount").val('');
    }else if(total > parseInt(max_days)){
        dynamicAlertBox('Message', 'As per rule, You may not give more than '+max_days+' days');
        $("#duration_month").val('');
        $("#duration_days").val('');        
    }else{
        if($("#punishment_start_date").val()!='' && ($("#duration_month").val()!='' || $("#duration_days").val()!='')){
            $("#punishment_end_date").val(addDays($("#punishment_start_date").val(),total));
        }        
    }
}

function addDays(startDate,numberOfDays){
    // alert('01/01/1988');
    var dd = startDate.split("-");
    var date = new Date(dd[1] + '/' + dd[0] + '/' +dd[2]);
    var newdate = new Date(date);

    newdate.setDate(newdate.getDate() + numberOfDays);
    var dd = newdate.getDate();
    var mm = newdate.getMonth() + 1;
    var y = newdate.getFullYear();
    if(mm < 10){
        mm = '0' + mm;
    }
    if(dd < 10){
        dd = '0' + dd;
    }
    var someFormattedDate = dd + '-' + mm + '-' + y;
    return someFormattedDate;
}
</script>
