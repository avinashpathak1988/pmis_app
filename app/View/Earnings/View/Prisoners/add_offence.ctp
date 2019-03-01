<div class="span12 offence_list" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;" id="<?php echo $case_key.'_'.$key.'_offence_list';?>">

    <button class="btn btn-add btn-remove btn-danger offence-remove-btn" type="button" style="padding: 8px 8px;float: right;position: absolute;right: -2px;" id="<?php echo $case_key;?>-offence-remove-btn" onclick="removeOffence('<?php echo $case_key;?>','<?php echo $key;?>');">
        <span class="icon icon-minus"></span>
    </button>

    <?php 
    echo $this->Form->input($nameFormat.'.id',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>$idFormat.'_id'));?>

    <div class="span6">
        <div class="control-group">
            <label class="control-label"><span class="countno" id="<?php echo $case_key;?>-count-<?php echo $key;?>">Count-<?php echo $key+1;?></span><?php echo $req;?>:</label>
            <div class="controls">
                <?php 
                echo $this->Form->input($nameFormat.'.offence',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select span11 debtor','type'=>'select','options'=>$offenceList, 'empty'=>'','required', 'id'=>$idFormat.'offence_id', 'title'=>'Please select Offence.', 'onchange'=>'getSOLaws(this.value,'.$case_key.','.$key.');', 'title'=>'Select Offence.'));?>
            </div>
        </div>
    </div>  
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Section Of Law
                <span id="<?php echo $idFormat;?>section_of_law_id_div" class="hidden"><?php echo $req; ?></span> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->input($nameFormat.'.section_of_law',array('div'=>false,'label'=>false,'multiple'=>true,'class'=>'form-control span11 pmis_select','type'=>'select','required'=>false, 'id'=>$idFormat.'section_of_law_id', 'title'=>'Select Section Of law.'));?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Offence Category<?php echo $req; ?> :</label>
            <div class="controls">
                <?php 
                $idname = "'admission'";
                echo $this->Form->input($nameFormat.'.offence_category_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 debtor','type'=>'select','options'=>$offenceCategoryList, 'empty'=>'','required', 'title'=>'Please select Offence Category.', 'id'=>$idFormat.'offence_category_id'));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Place of Offence:</label>
            <div class="controls">
                <?php echo $this->Form->input($nameFormat.'.place_of_offence',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Place of Offence",'required'=>false,'maxlength'=>'30'));?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="span6">
       <div class="control-group">
            <label class="control-label">District of offence:</label>
            <div class="controls">
                
                <?php echo $this->Form->input($nameFormat.'.district_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$allDistrictList, 'empty'=>'','required'=>false, 'id'=>$idFormat.'district_id'));?>
            </div>
        </div>
    </div>
    <!-- <div class="span6">
        <div class="control-group">
            <label class="control-label">Date & Time of Offence:</label>
            <div class="controls">
                <?php //echo $this->Form->input($nameFormat.'.time_of_offence',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Date & Time of Offence",'required'=>false, 'maxlength'=>'30', 'readonly', 'id'=>$idFormat.'time_of_offence'));?>
            </div>
        </div>
    </div> -->
    <!-- <div class="clearfix"></div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Reported by(Staff/Prisoner):</label>
            <div class="controls">
                <?php //echo $this->Form->input($nameFormat.'.reported_by',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Reported by(Staff/Prisoner)",'required'=>false, 'maxlength'=>'30'));?>
            </div>
        </div>
    </div> -->
    <!-- <div class="span6">
        <div class="control-group">
            <label class="control-label">Victims/Complainant:</label>
            <div class="controls">
                <?php //echo $this->Form->input($nameFormat.'.victim_complaint',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Victims/Complainant offence",'required'=>false, 'maxlength'=>'30'));?>
            </div>
        </div>
    </div> -->
</div>
<script>
    $(function(){
        var count = '<?php echo $case_key;?>';
        var ofnc_count = '<?php echo $key;?>';
        
        $('.pmis_select').select2({
            placeholder: "-- Select --",
            allowClear: false
        });
        //alert("#"+count+"_"+ofnc_count+"_time_of_offence");
        $("#"+count+"_"+ofnc_count+"_time_of_offence").datetimepicker({
            showMeridian: false,
            defaultTime:true,
            format: 'dd-mm-yyyy hh:ii',
            autoclose:true,
            endDate: new Date(),
            
        }).on('changeDate', function (ev) {
             $(this).datetimepicker('hide');
             $(this).blur();
        });
    });
</script>