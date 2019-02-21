<?php $judicialOfficerLevel = 'Presiding Judicial Officer';?>
<div class="span12 case_list" id="<?php echo $case_key;?>_case_file" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;">
    <button class="btn btn-add btn-remove btn-danger offence-remove-btn" type="button" style="padding: 8px 8px;float: right;position: absolute;right: -2px;" id="<?php echo $case_key;?>-offence-remove-btn" onclick="removeCase('<?php echo $case_key;?>');">
        <span class="icon icon-minus"></span>
    </button>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Court File No<span id="<?php echo $case_key;?>_court_file_no_reqd"><?php echo $req;?></span> :</label>
            <div class="controls">
                <?php echo $this->Form->input('PrisonerCaseFile.'.$case_key.'.court_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text','placeholder'=>"Enter Court File No",'required','id'=>$case_key.'_court_file_no', 'maxlength'=>'30', 'title'=>'Court File No is required.'));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Case File No. <?php echo $req; ?>:</label>
            <div class="controls">
                <?php echo $this->Form->text('PrisonerCaseFile.'.$case_key.'.case_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'CASE FILE No.','required','id'=>$case_key.'_case_file_no', 'maxlength'=>'30', 'title'=>'Case File No is required.'));?>
            </div>
        </div>
    </div> 
    <div class="clearfix"></div>  
    <div class="span6">
        <div class="control-group">
            <label class="control-label">C.R.B No<span id="<?php echo $case_key;?>_crb_no_reqd" class="hidden"><?php echo $req;?></span>:</label>
            <div class="controls">
                <?php echo $this->Form->input('PrisonerCaseFile.'.$case_key.'.crb_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Enter C.R.B No.','required'=>false,'id'=>$case_key.'_crb_no', 'maxlength'=>'30', 'title'=>'C.R.B No is required.'));?> 
            </div>
        </div>
    </div> 
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Date Of Warrant <?php echo $req; ?>:</label>
            <div class="controls">
                <?php echo $this->Form->text('PrisonerCaseFile.'.$case_key.'.date_of_warrant',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text', 'placeholder'=>'Date Of Warrant','required','id'=>$case_key.'_date_of_warrant','readonly', 'title'=>'Select date of warrant'));?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Court Category<?php echo $req; ?>:</label>
            <div class="controls">
                <?php echo $this->Form->input('PrisonerCaseFile.'.$case_key.'.courtlevel_id',array('div'=>false,'label'=>false,'onChange'=>'getCourtList(this.value, '.$case_key.')','class'=>'form-control span11 courtlevel_id','type'=>'select','options'=>$courtLevelList, 'empty'=>'','required','id'=>$case_key.'_courtlevel_id', 'title'=>'Select Court Category'));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Court Name<?php echo $req; ?>:</label>
            <div class="controls">
                <?php echo $this->Form->input('PrisonerCaseFile.'.$case_key.'.court_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 court_id','type'=>'select','options'=>$courtList, 'onChange'=>'getCourtDetails(this.value, '.$case_key.')','empty'=>'','required','id'=>$case_key.'_court_id', 'title'=>'Select court name'));?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="span6">
        <div class="control-group">
            <?php if(isset($prisoner_type_id) && $prisoner_type_id == Configure::read('REMAND'))
            {?>
                <label class="control-label">Jurisdiction area<?php echo $req;?>:</label>
                <div class="controls">
                    <?php
                        echo $this->Form->input('PrisonerCaseFile.'.$case_key.'.magisterial_id',array(
                          'div'=>false,
                          'label'=>false,
                          'type'=>'select',
                          'class'=>'form-control span11',
                          'options'=>$magisterialList, 'empty'=>'',
                          'required','title'=>"Please select Jurisdiction area","id"=>$case_key."_magisterial_id",
                          'title'=>'Select Jurisdiction area'
                        ));
                     ?>
                </div>
            <?php }
            else {?>
                <label class="control-label">Jurisdiction area:</label>
                <div class="controls">
                    <?php
                        echo $this->Form->input('PrisonerCaseFile.'.$case_key.'.magisterial_id',array(
                          'div'=>false,
                          'label'=>false,
                          'type'=>'select',
                          'options'=>$magisterialList, 'empty'=>'',
                          'required'=>false,'title'=>"Please select Jurisdiction area","id"=>$case_key."_magisterial_id"
                        ));
                     ?>
                </div>
            <?php }?>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <?php if(isset($prisoner_type_id) && $prisoner_type_id == Configure::read('REMAND'))
            {?>
                <label class="control-label">Presiding Judicial Officer<?php echo $req;?>:</label>
            <?php }
            else {?> 
                <label class="control-label" id="<?php echo $case_key;?>_magistrate_level">
                    Presiding Judicial Officer:
                </label>
            <?php }?>
            <div class="controls" id="<?php echo $case_key;?>_judges">
            <?php 
            if(isset($prisoner_type_id) && $prisoner_type_id == Configure::read('REMAND'))
            {?>
                
                <div class="">
                    <?php echo $this->Form->text('PrisonerCaseFile.'.$case_key.'.judicial_officer.',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp','type'=>'text', 'placeholder'=>$judicialOfficerLevel,'id'=>$case_key.'_judicial_officer','required'=>false,'required', 'title'=>'Enter Presiding Judicial Officer'));?>
                </div>
        <?php }
            else {?>
                
                <div class="">
                    <?php echo $this->Form->text('PrisonerCaseFile.'.$case_key.'.judicial_officer.',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp','type'=>'text', 'placeholder'=>$judicialOfficerLevel,'required'=>false,'required'=>false,'id'=>$case_key.'_judicial_officer'));?>
                </div>
        <?php }?>
            </div>
        </div>
        <button class="btn btn-success hidden judges_btn" type="button" style="padding: 8px 8px; float:right;" id="<?php echo $case_key;?>_judges_btn" onclick="addJudge('<?php echo $case_key;?>');">
            <span class="icon icon-plus"></span>
        </button>
        <button class="btn btn-danger btn-add judges_btn hidden" type="button" style="padding: 8px 8px; float:right;" id="<?php echo $case_key;?>_judges_remove_btn" onclick="removeJudge('<?php echo $case_key;?>');">
            <span class="icon icon-minus"></span>
        </button>
    </div>
    <div class="clearfix"></div>
    <div class="span6 hidden" id="<?php echo $case_key;?>_highcourt_file_no_reqd">
        <div class="control-group">
            <label class="control-label">High Court File No<?php echo $req;?>:</label>
            <div class="controls">
                <?php echo $this->Form->input('PrisonerCaseFile.'.$case_key.'highcourt_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Enter High Court File No.','required'=>false,'id'=>$case_key.'_highcourt_file_no', 'maxlength'=>'30', 'title'=>' High Court File No is required.'));?> 
            </div>
        </div>
    </div>
    <!-- Offence detail START -->
    <div class="row-fluid secondDiv widget-box" style="margin: 0;padding-bottom: 0;     border-bottom: 0;">
        <div class="widget-title">
            <h5>Offence Details</h5>
        </div>
        <div class="widget-content" style="    padding-bottom: 0;">
            <!-- Multiple offence start -->
            <div class="row-fluid secondDiv" id="<?php echo $case_key;?>_offence_list">                
                <div class="span12 offence_list" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;" id="<?php echo $case_key.'_'.$key.'_offence_list';?>">
                    <?php 
                    echo $this->Form->input($nameFormat.'.id',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>$idFormat.'_id'));?>

                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label"><span class="countno" id="<?php echo $case_key;?>-count-<?php echo $key;?>">Offence</span><?php echo $req;?>:</label>
                            <div class="controls">
                                <?php 
                                echo $this->Form->input($nameFormat.'.offence',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select span11','type'=>'select','options'=>$offenceList, 'empty'=>'','required', 'id'=>$idFormat.'offence_id', 'title'=>'Please select Offence.', 'onchange'=>'getSOLaws(this.value,'.$case_key.','.$key.');', 'title'=>'Select Offence.'));?>
                            </div>
                        </div>
                    </div>  
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Section Of Law
                                <span class="hidden" id="<?php echo $idFormat;?>section_of_law_id_div"><?php echo $req; ?></span> :</label>
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
                                echo $this->Form->input($nameFormat.'.offence_category_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offenceCategoryList, 'empty'=>'','required', 'title'=>'Please select Offence Category.', 'id'=>$idFormat.'offence_category_id'));?>
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
            </div>
            <div class="clearfix"></div>
            <div class="">
                <span class="input-group-btn">
                    <button class="btn btn-success btn-add offence-btn-add" type="button" style="padding: 8px 8px;" id="<?php echo $case_key;?>-add-offence" onclick="addOffence('<?php echo $case_key;?>');">
                        Add Count
                    </button>
                </span>
            </div>
            <!-- Multiple offence start -->
        </div>
    </div>
    <!-- Offence detail END --> 
</div>
<script>
$(function(){
    var count = '<?php echo $case_key;?>';
    $("#"+count+"_date_of_warrant").datepicker({
        format: 'dd-mm-yyyy',
        autoclose:true,
        endDate: new Date(),
    }).on('changeDate', function (ev) {
         $(this).datepicker('hide');
         $(this).blur();
    });

});
</script>