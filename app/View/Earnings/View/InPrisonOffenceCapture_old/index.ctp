<style type="text/css">
    .form-horizontal .control-label{width: 210px;}
    .form-horizontal .controls{margin-left: 225px;}
</style>

<?php
if(isset($this->data['InPrisonOffenceCapture']['offence_date']) && $this->data['InPrisonOffenceCapture']['offence_date'] != ''){
    $this->request->data['InPrisonOffenceCapture']['offence_date'] = date('d-m-Y', strtotime($this->data['InPrisonOffenceCapture']['offence_date']));
    $offenceDatedefault = date('d-m-Y', strtotime($this->data['InPrisonOffenceCapture']['offence_date']));
}else{
    $offenceDatedefault = date('d-m-Y');
   // $this->request->data['InPrisonOffenceCapture']['offence_date'] =  date('d-m-Y');
}

if(isset($this->data['InPrisonPunishment']['punishment_date']) && $this->data['InPrisonPunishment']['punishment_date'] != ''){
    $this->request->data['InPrisonPunishment']['punishment_date'] = date('d-m-Y', strtotime($this->data['InPrisonPunishment']['punishment_date']));
}
if(isset($this->data['InPrisonPunishment']['punishment_start_date']) && $this->data['InPrisonPunishment']['punishment_start_date'] != ''){
    $this->request->data['InPrisonPunishment']['punishment_start_date'] = date('d-m-Y', strtotime($this->data['InPrisonPunishment']['punishment_start_date']));
}
if(isset($this->data['InPrisonPunishment']['punishment_end_date']) && $this->data['InPrisonPunishment']['punishment_end_date'] != ''){
    $this->request->data['InPrisonPunishment']['punishment_end_date'] = date('d-m-Y', strtotime($this->data['InPrisonPunishment']['punishment_end_date']));
}
if(isset($this->data['DisciplinaryProceeding']['date_of_hearing']) && $this->data['DisciplinaryProceeding']['date_of_hearing'] != ''){
    $this->request->data['DisciplinaryProceeding']['date_of_hearing'] = date('d-m-Y', strtotime($this->data['DisciplinaryProceeding']['date_of_hearing']));
}
if(isset($this->data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id']) && $this->data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id']!='')
{
    $this->request->data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id']= explode(",", $this->data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id']);
}
if(isset($this->data['DisciplinaryProceeding']['prosecutions_witness_staff_id']) && $this->data['DisciplinaryProceeding']['prosecutions_witness_staff_id']!='')
{
    $this->request->data['DisciplinaryProceeding']['prosecutions_witness_staff_id']= explode(",", $this->data['DisciplinaryProceeding']['prosecutions_witness_staff_id']);
}
if(isset($this->data['DisciplinaryProceeding']['defense_witness_prisoner_id']) && $this->data['DisciplinaryProceeding']['defense_witness_prisoner_id']!='')
{
    $this->request->data['DisciplinaryProceeding']['defense_witness_prisoner_id']= explode(",", $this->data['DisciplinaryProceeding']['defense_witness_prisoner_id']);
}
if(isset($this->data['DisciplinaryProceeding']['defense_witness_staff_id']) && $this->data['DisciplinaryProceeding']['defense_witness_staff_id']!='')
{
    $this->request->data['DisciplinaryProceeding']['defense_witness_staff_id']= explode(",", $this->data['DisciplinaryProceeding']['defense_witness_staff_id']);
}
?>
<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
.guilty, .not-guilty
{
    display:none;
}
.highlight-form-section
{
    background: #eeeeee; 
}
.highlight-form-section
{
    background: #eeeeee; 
    margin-bottom: 5px;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
         <div id="commonheader"></div>

        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Discipline Records</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <ul class="nav nav-tabs">
                            <!-- <li><a href="#health_checkup">Health Checkup</a></li> -->
                            <li><a href="#offences" id="offencesDiv">Record Offences</a></li>
                            <?php
                            // debug($offencesList);
                            // debug($punishmentProcessData);
                            if(count($offencesList) > 0 || count($punishmentProcessData) > 0){
                                ?>
                                <li><a href="#disciplinaryProceedings" id="disciplinaryProceedingsDiv">Disciplinary Proceedings</a></li>
                                <li><a href="#punishments" id="punishmentsDiv">Punishments</a></li>
                                <?php
                            }
                            ?>
                            <!-- <li class="pull-right controls"> -->
                            <?php
                            if(count($offencesList) > 0 || count($punishmentProcessData) > 0){
                                ?>
                            <li class="controls pull-right" style="margin-top: -40px;">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li>
                            <?php
                            }
                            ?>
                        </ul>
                        <div class="tabscontent">
                            <!-- <div id="health_checkup">
                                
                            </div> -->
                            <div id="offences">
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('InPrisonOffenceCapture',array('class'=>'form-horizontal','type'=>'file'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden', ))?>
                                    <div class="row" style="padding-bottom: 14px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of Offence <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('offence_date',array('div'=>false,'label'=>false,'class'=>'form-control pastdate span11','type'=>'text', 'placeholder'=>'Enter Date of Offence','required','readonly'=>'readonly','id'=>'offence_date','title'=>'Please select offence date','value'=>$offenceDatedefault));?>
                                                </div>
                                            </div>
                                        </div>       
                                        <div class="span6">
                                           <div class="control-group">
                                                <label class="control-label">Offence Type<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('offence_type',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>Configure::read("OFFENCETYPE"), 'empty'=>'-- Select Offence Type --','required','id'=>'offence_type','title'=>'Please select offence type'));?>
                                                </div>
                                            </div>
                                        </div>       
                                        <div class="clearfix"></div>                
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Offence Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('internal_offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offenceList, 'empty'=>'-- Select Offences --','required','id'=>'internal_offence_id','title'=>'Please select offence name'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Reported by<?php echo $req; ?> :</label>
                                                 <div class="controls">
                                                    <?php echo $this->Form->input('offence_recorded_by',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$userList, 'empty'=>'-- Select Name --','required','id'=>'offence_recorded_by','title'=>'Please select user name'));?>    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Compliant/Victim of Offence <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('offence_victim',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Place of Prisoner Offence<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('offence_place',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                                </div>
                                            </div>
                                        </div>                                        
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Particulars of offence <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('offence_descr',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php 
                                        if(isset($this->data['InPrisonOffenceCapture']) && is_array($this->data['InPrisonOffenceCapture']) && count($this->data['InPrisonOffenceCapture'])>0){
                                            echo $this->Form->button('Update', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true,));
                                        }else{
                                            echo $this->Form->button('Submit', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true,));
                                        }
                                        ?>
                                        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetForm('InPrisonOffenceCaptureIndexForm')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                <div class="table-responsive" id="sickListingDiv">

                                </div>
                            </div>
                            <div id="disciplinaryProceedings">
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('DisciplinaryProceeding',array('','class'=>'form-horizontal','type'=>'file'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden', ))?>
                                    <div class="row" style="padding-bottom: 14px; margin-left:0;">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Plea Type<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php 
                                                    // debug($this->data);
                                                    $pleaTypeList = array('Guilty'=>'Guilty','Not Guilty'=>'Not Guilty');
                                                    echo $this->Form->input('plea_type',array('div'=>false,'label'=>false,'class'=>'form-control span12','type'=>'select','options'=>$pleaTypeList, 'empty'=>'-- Select Plea Type --','required','title'=>'Please provide offence name','onchange'=>"showDisciplinaryProceeding(this.value)", 'id'=>'plea_type'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                        $guilty_display = 'style="display:none;"';
                                        $not_guilty_display = 'style="display:none;"';
                                        $common_pleatype_display = 'style="display:none;"';

                                        if(isset($this->data['DisciplinaryProceeding']) && !empty($this->data['DisciplinaryProceeding']['id']))
                                        {
                                            if($this->data['DisciplinaryProceeding']['plea_type'] == 'Guilty')
                                            {
                                                $guilty_display = '';
                                                $common_pleatype_display = '';
                                            }
                                            if($this->data['DisciplinaryProceeding']['plea_type'] == 'Not Guilty')
                                            {
                                                $not_guilty_display = '';
                                                $common_pleatype_display = '';
                                            }
                                        }
                                        
                                        ?>
                                          <div class="span6 guilty not-guilty" <?php echo $common_pleatype_display;?>>
                                                <div class="control-group">
                                                    <label class="control-label">Date of Hearing <?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('date_of_hearing',array('div'=>false,'label'=>false,'class'=>'form-control mydate span12','type'=>'text', 'placeholder'=>'Please Select Date Of Hearing','required','readonly'=>'readonly','title'=>'Please Select Date Of Hearing'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="span6  guilty not-guilty" <?php echo $common_pleatype_display;?>>
                                                <div class="control-group">
                                                    <label class="control-label">Evident Summary <?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('summary',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'summary', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please provide summary'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6 not-guilty" <?php echo $not_guilty_display;?>>
                                                <div class="control-group">
                                                    <label class="control-label">
                                                        Summary Document 
                                                        <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,gif) type summary_document!" id='example'></i>
                                                        :
                                                    </label>
                                                    <div class="controls">
                                                        <div id="prevImage_photo" class="">
                                                            <?php if(isset($this->request->data["Prisoner"]["summary_document"]))
                                                            {?>
                                                                <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/discipline/<?php echo $this->request->data["Prisoner"]["summary_document"];?>" data-lightbox="example-set"><img src="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["summary_document"];?>" alt="" width="100px" height="100px"></a>
                                                            <?php }?>
                                                        </div>
                                                        <span id="preview_panel__photo" class="img_preview_panel">
                                                            <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="prev_photo" src="#" class="img_prev" /></a>
                                                            <span id="remove_photo" class="remove_img" onclick="removePreview('summary_document');">[X]</span>
                                                        </span>
                                                        <div class="clear"></div>
                                                        <?php echo $this->Form->input('summary_document',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'summary_document', 'required'=>false, 'onchange'=>'readImage(this,"summary_document");'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span12 not-guilty highlight-form-section" <?php echo $not_guilty_display;?>>
                                                <!-- <div class="widget-title"> 
                                                    <h5>Prosecutions witness </h5>
                                                </div> -->
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Prosecutions witness <?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('prosecutions_witness',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'prosecutions_witness', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please provide prosecutions witness'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Prisoner<?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php 

                                                            echo $this->Form->input('prosecutions_witness_prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 multiselectDropdown','multiple'=>'multiple','type'=>'select','options'=>$prisonerDisciplinary, 'empty'=>'-- Select Prisoner --','required'=>false,"id"=>"prosecutions_witness_prisoner_id"));?>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Staff<?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php 
                                                            echo $this->Form->input('prosecutions_witness_staff_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 multiselectDropdown','multiple'=>'multiple','type'=>'select','options'=>$staffDisciplinary, 'empty'=>'-- Select Staff --','required'=>false));?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6  not-guilty" <?php echo $not_guilty_display;?>>
                                                <div class="control-group">
                                                    <label class="control-label">Cross examination <?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('cross_examination',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'judgment', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'title'=>'Please provide judgment'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6  not-guilty" <?php echo $not_guilty_display;?>>
                                                <div class="control-group">
                                                    <label class="control-label">Document
Uploaded Ruling <?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('ruling_document',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'brief_facts', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please provide brief facts'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span12 not-guilty highlight-form-section" <?php echo $not_guilty_display;?>>
                                                <!-- <div class="widget-title"> 
                                                    <h5>Prosecutions witness </h5>
                                                </div> -->
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Defense witness <?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('defense_witness',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'defense_witness', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please provide Defense witness'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Prisoner<?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php 
                                                            echo $this->Form->input('defense_witness_prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 multiselectDropdown','multiple'=>'multiple','type'=>'select','options'=>$prisonerDisciplinary, 'empty'=>'-- Select Prisoner --','required'=>false));?>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Staff<?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php 
                                                            echo $this->Form->input('defense_witness_staff_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 multiselectDropdown','multiple'=>'multiple','type'=>'select','options'=>$staffDisciplinary, 'empty'=>'-- Select Staff --','required'=>false));?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6  not-guilty" <?php echo $not_guilty_display;?>>
                                                <div class="control-group">
                                                    <label class="control-label">Judgment <?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('judgment',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'judgment', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'title'=>'Please provide judgment'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6  not-guilty" <?php echo $not_guilty_display;?>>
                                                <div class="control-group">
                                                    <label class="control-label">Brief facts <?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('brief_facts',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'brief_facts', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please provide brief facts'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6 guilty not-guilty" <?php echo $common_pleatype_display;?>>
                                                <div class="control-group">
                                                    <label class="control-label">Mitigation :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('mitigation',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,'id'=>'summary', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please provide mitigation'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6  not-guilty" <?php echo $not_guilty_display;?>>
                                                <div class="control-group">
                                                    <label class="control-label">Adjusting Officer <?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('adjusting_officer',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$staffDisciplinary, 'empty'=>'-- Select Adjusting Officer--'));?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="form-actions" align="center">

                                        <?php 
                                        if(isset($this->data['DisciplinaryProceeding']) && is_array($this->data['DisciplinaryProceeding']) && count($this->data['DisciplinaryProceeding'])>0){
                                            echo $this->Form->button('Update', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success'));
                                        }else{
                                            echo $this->Form->button('Submit', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success'));
                                        }

                                        ?>
                                        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetForm('DisciplinaryProceedingIndexForm')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                <div class="table-responsive" id="DisciplinaryProceedingDivs">

                                </div>             
                            </div>
                            <div id="punishments">
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('InPrisonPunishment',array('','class'=>'form-horizontal','type'=>'file'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden', ))?>
                                    <div class="row" style="padding-bottom: 14px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Punishment Date <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('punishment_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Punishment  Date','required','readonly'=>'readonly','title'=>'Please provide punishment date'));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Offence Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('in_prison_offence_capture_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offencesList, 'empty'=>'-- Select Offence Name --','required','title'=>'Please provide offence name','onchange'=>"showDecision()"));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                           <div class="control-group">
                                                <label class="control-label">Punishment Type<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('internal_punishment_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$punishmentsList, 'empty'=>'-- Select Offences --','required','id'=>'internal_punishment_id','title'=>'Please select punishment type','onchange'=>"showDecision()"));?>
                                                </div>
                                            </div>
                                        </div>                                
                                        <span id="periods">
                                                
                                        </span>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Remarks <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('remarks',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'remarks', 'cols'=>30, 'rows'=>3,'style'=>"",'title'=>'Please provide remarks'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                    </div>
                                    <div class="form-actions" align="center">

                                        <?php 
                                        if(isset($this->data['InPrisonPunishment']) && is_array($this->data['InPrisonPunishment']) && count($this->data['InPrisonPunishment'])>0){
                                            echo $this->Form->button('Update', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true,));
                                        }else{
                                            echo $this->Form->button('Submit', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true,));
                                        }

                                        ?>
                                        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetForm('InPrisonPunishmentIndexForm')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                <div class="table-responsive" id="punishmentsDivs">

                                </div>             
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// debug($this->data);
?>
<script type="text/javascript">
$(document).ready(function(){
    <?php
    if(isset($this->data['InPrisonOffenceCapture']) && is_array($this->data['InPrisonOffenceCapture']) && count($this->data['InPrisonOffenceCapture'])>0){
        ?>
        $('#offence_type').select2('val', '<?php echo $this->data['InPrisonOffenceCapture']['offence_type']; ?>');
        $('#internal_offence_id').select2('val', '<?php echo $this->data['InPrisonOffenceCapture']['internal_offence_id']; ?>');
        $('#offence_recorded_by').select2('val', '<?php echo $this->data['InPrisonOffenceCapture']['offence_recorded_by']; ?>');
        <?php
    }

    if(isset($this->data['InPrisonPunishment']) && is_array($this->data['InPrisonPunishment']) && count($this->data['InPrisonPunishment'])>0){
        ?>
        $('#InPrisonPunishmentInPrisonOffenceCaptureId').select2('val', '<?php echo $this->data['InPrisonPunishment']['in_prison_offence_capture_id']; ?>');
        $('#internal_punishment_id').select2('val', '<?php echo $this->data['InPrisonPunishment']['internal_punishment_id']; ?>');
        showDecision();
        <?php
    }
    ?>
});
$(function(){
    $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\,\.\s]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");
     
    $("#InPrisonOffenceCaptureIndexForm").validate({
        ignore: "",
        rules: {
            'data[InPrisonOffenceCapture][offence_descr]': {
                loginRegex: true,
                maxlength: 250
            },
        },
        messages: {
            'data[InPrisonOffenceCapture][offence_descr]': {
                loginRegex: "Offence Description must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                maxlength: "Please enter no more than 255 characters.",
            },
        }, 
    });

    $("#InPrisonPunishmentIndexForm").validate({     
      ignore: "",
            rules: {
                'data[InPrisonPunishment][remarks]': {
                    loginRegex: true,
                    maxlength: 250
                },
            },
            messages: {
                'data[InPrisonPunishment][remarks]': {
                    loginRegex: "Remarks must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    maxlength: "Please enter no more than 255 characters.",
                },
            }, 
    });
});
function setValues(){
    <?php
    if(isset($this->data['InPrisonPunishment']) && is_array($this->data['InPrisonPunishment']) && count($this->data['InPrisonPunishment'])>0){
    ?>
    $('#duration_month').val('<?php echo $this->data['InPrisonPunishment']['duration_month']; ?>');
    $('#duration_days').val('<?php echo $this->data['InPrisonPunishment']['duration_days']; ?>');
    $('#punishment_start_date').val('<?php echo $this->data['InPrisonPunishment']['punishment_start_date']; ?>');
    $('#punishment_end_date').val('<?php echo $this->data['InPrisonPunishment']['punishment_end_date']; ?>');
    $('#deducted_amount').val('<?php echo $this->data['InPrisonPunishment']['deducted_amount']; ?>');
    <?php
    }
    ?>
}
</script>
<?php
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$offenceUrl         = $this->Html->url(array('controller'=>'InPrisonOffenceCapture','action'=>'indexAjax'));
$deleteoffenceUrl   = $this->Html->url(array('controller'=>'InPrisonOffenceCapture','action'=>'deleteOffences'));
$punishmentsUrl   = $this->Html->url(array('controller'=>'InPrisonOffenceCapture','action'=>'showPunishmentsRecords'));
$deletePunishmentsUrl   = $this->Html->url(array('controller'=>'InPrisonOffenceCapture','action'=>'deletePunishmentsRecords'));
$unishmentsPeriodUrl   = $this->Html->url(array('controller'=>'InPrisonOffenceCapture','action'=>'getPeriod'));
$disciplinaryProceedingUrl = $this->Html->url(array('controller'=>'InPrisonOffenceCapture','action'=>'disciplinaryProceedingAjax'));
$deleteDisciplinaryProceeding = $this->Html->url(array('controller'=>'InPrisonOffenceCapture','action'=>'deleteDisciplinaryProceeding'));

echo $this->Html->scriptBlock("
    var tab_param = '';
    var tabs;
    jQuery(function($) {
        $('select').select2('val','');
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });
        showOffenceRecords(); 
        showCommonHeader();
       // showCommonHeader();
        $('#offencesDiv').on('click', function(e){
            console.log('clicked');
           showOffenceRecords();
        });
        $('#punishmentsDiv').on('click', function(e){
            showPunishmentRecords();
        });
        $('#disciplinaryProceedingsDiv').on('click', function(e){
            showDisciplinaryProceedings();
        });
        
        var cururl = window.location.href;
        var urlArr = cururl.split('/');
        var param = '';
        for(var i=0; i<urlArr.length;i++){
            param = urlArr[i];
        }
        if(param != ''){
            var paramArr = param.split('#');
            for(var i=0; i<paramArr.length;i++){
                tab_param    = paramArr[i];
            }
        }
        console.log(tab_param);
        if(tab_param == 'offences'){
            showOffenceRecords();
        }else if(tab_param == 'punishments'){
            showPunishmentRecords();
        }
        else if(tab_param == 'disciplinaryProceedings'){
            showDisciplinaryProceedings();
        }

        // $('.pastdate').datepicker({
        //     format: 'dd-mm-yyyy',
        //     autoclose:true,
        //     // minDate: new Date(),
        //     minDate: '0',
        // }).on('changeDate', function (ev) {
        //      $(this).datepicker('hide');
        //      $(this).blur();
        // });
    });
    function showOffenceRecords(){
        var prisoner_id = ".$prisoner_id.";
        var uuid        = '".$uuid."';
        var url         = '".$offenceUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            if (res) {
                $('#sickListingDiv').html(res);
            }
        }); 
    }
    function deleteOffenceRecords(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteoffenceUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    if(res == 'SUCC'){
                        showOffenceRecords();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    function showPunishmentRecords(){
        var prisoner_id = ".$prisoner_id.";
        var uuid        = '".$uuid."';
        var url         = '".$punishmentsUrl."';
        url             = url + '/prisoner_id:'+prisoner_id;
        url             = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            if (res) {
                $('#punishmentsDivs').html(res);
            }
        });         
    }
    function deletePunishmentRecords(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deletePunishmentsUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    if(res == 'SUCC'){
                        showPunishmentRecords();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
//common header
    function showCommonHeader(){
        var prisoner_id = ".$prisoner_id.";;
        console.log(prisoner_id);  
        var uuid        = '".$uuid."';
        var url         = '".$commonHeaderUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
           
            if (res) {
                $('#commonheader').html(res);
            }
        }); 
    }

    function showDecision(){
        var prisoner_id = ".$prisoner_id.";
        console.log(prisoner_id);  
        var uuid        = '".$uuid."';
        var url         = '".$unishmentsPeriodUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/punishment_type:'+$('#internal_punishment_id').val();
        url = url + '/in_prison_offence_capture_id:'+$('#InPrisonPunishmentInPrisonOffenceCaptureId').val();
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {  
            if (res) {
                if(res.trim()!='FAIL'){
                    $('#periods').html(res);
                    setValues();
                }else{
                    $('#periods').html('');
                }            
            }
        }); 
    }
    //show disciplinary proceedings 
    function showDisciplinaryProceedings()
    {
        var prisoner_id = ".$prisoner_id.";
        var uuid        = '".$uuid."';
        var url         = '".$disciplinaryProceedingUrl."';
        url             = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            if (res) {
                $('#DisciplinaryProceedingDivs').html(res);
            }
        });         
    }
    //delete disciplinary proceedings 
    function deleteDisciplinaryProceeding(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteDisciplinaryProceeding."';
                $.post(url, {'paramId':paramId}, function(res) {
                    if(res == 'SUCC'){
                        showDisciplinaryProceedings();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    
",array('inline'=>false));

?> 
<script>
$( document ).ready(function() {
    $(".multiselectDropdown").select2();
});

$(function(){
    //validate disciplinary proceeding form 
    $("#DisciplinaryProceedingIndexForm").validate({
     
        ignore: "",
            rules: {  
                'data[DisciplinaryProceeding][plea_type]': {
                    required: true,
                },
                'data[DisciplinaryProceeding][date_of_hearing]': {
                    required: function(element){
                        return $("#plea_type").val()!='';
                    }
                },
                'data[DisciplinaryProceeding][summary]': {
                    required: function(element){
                        return $("#plea_type").val()!='';
                    },
                    loginRegex: true,
                    maxlength: 250
                },
                'data[DisciplinaryProceeding][summary_document]': {
                    // required: function(element){
                    //     return $("#plea_type").val()=="Not Guilty";
                    // },
                    extension: "png|jpg|jpeg|gif|PNG|JPG|JPEG|GIF",
                    filesize: 2000000,
                },
                // 'data[DisciplinaryProceeding][prosecutions_witness]': {
                //     required: function(element){
                //         return $("#plea_type").val()==0;
                //     }
                // },
                // 'data[DisciplinaryProceeding][prosecutions_witness_prisoner_id]': {
                //     required: function(element){
                //         return $("#plea_type").val()==0;
                //     }
                // },
                // 'data[DisciplinaryProceeding][prosecutions_witness_staff_id]': {
                //     required: function(element){
                //         return $("#plea_type").val()==0;
                //     }
                // },
                // 'data[DisciplinaryProceeding][defence_witness]': {
                //     required: function(element){
                //         return $("#plea_type").val()==0;
                //     }
                // },
                // 'data[DisciplinaryProceeding][defence_witness_prisoner_id]': {
                //     required: function(element){
                //         return $("#plea_type").val()==0;
                //     }
                // },
                // 'data[DisciplinaryProceeding][defence_witness_staff_id]': {
                //     required: function(element){
                //         return $("#plea_type").val()==0;
                //     }
                // }
            },
            messages: {
                'data[DisciplinaryProceeding][plea_type]': {
                    required: "Please select plea type.",
                },
                'data[DisciplinaryProceeding][date_of_hearing]': {
                    required: "Please select date of hearing.",
                },
                'data[DisciplinaryProceeding][summary]': {
                    required: "Please provide evident summary.",
                    loginRegex: "Summary must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    maxlength: "Please enter no more than 250 characters."
                },
                'data[DisciplinaryProceeding][summary_document]': {
                    //required: "Please upload summary document.",
                    extension: "Please upload (jpg,jpeg,png,gif) type photo",
                    filesize:"File size must be 2MB."
                },
                // 'data[DisciplinaryProceeding][prosecutions_witness]': {
                //     required: "Provide prosecutions witness"
                // },
                // 'data[DisciplinaryProceeding][prosecutions_witness_prisoner_id]': {
                //     required: "Please select prisoner"
                // },
                // 'data[DisciplinaryProceeding][prosecutions_witness_staff_id]': {
                //     required: "Please select staff"
                // },
                // 'data[DisciplinaryProceeding][prosecutions_witness]': {
                //     required: "Provide prosecutions witness"
                // },
                // 'data[DisciplinaryProceeding][prosecutions_witness_prisoner_id]': {
                //     required: "Please select prisoner"
                // },
                // 'data[DisciplinaryProceeding][prosecutions_witness_staff_id]': {
                //     required: "Please select staff"
                // },
                // 'data[DisciplinaryProceeding][defence_witness]': {
                //     required: "Provide defence witness"
                // },
                // 'data[DisciplinaryProceeding][defence_witness_prisoner_id]': {
                //     required: "Please select prisoner"
                // },
                // 'data[DisciplinaryProceeding][defence_witness_staff_id]': {
                //     required: "Please select staff"
                // }
            }
    });
});
function showDisciplinaryProceeding(val)
{
    if(val == 'Guilty')
    {
        $('.not-guilty').hide();
        $('.guilty').show();
    }
    if(val == 'Not Guilty')
    {
        $('.guilty').hide();
        $('.not-guilty').show();
    }
}
</script>