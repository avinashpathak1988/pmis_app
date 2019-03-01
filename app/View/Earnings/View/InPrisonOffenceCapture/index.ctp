<?php
if(isset($this->data['DisciplinaryProceeding']['offence_date']) && $this->data['DisciplinaryProceeding']['offence_date'] != ''){
    $this->request->data['InPrisonOffenceCapture']['offence_date'] = date('d-m-Y', strtotime($this->data['DisciplinaryProceeding']['offence_date']));
    $offenceDatedefault = date('d-m-Y', strtotime($this->data['DisciplinaryProceeding']['offence_date']));
}else{
    $offenceDatedefault = date('d-m-Y');
    // $this->request->data['DisciplinaryProceeding']['offence_date'] =  date('d-m-Y');
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
if(isset($this->data['DisciplinaryProceeding']['defence_witness_prisoner_id']) && $this->data['DisciplinaryProceeding']['defence_witness_prisoner_id']!='')
{
    $this->request->data['DisciplinaryProceeding']['defence_witness_prisoner_id']= explode(",", $this->data['DisciplinaryProceeding']['defence_witness_prisoner_id']);
}
if(isset($this->data['DisciplinaryProceeding']['defence_witness_staff_id']) && $this->data['DisciplinaryProceeding']['defence_witness_staff_id']!='')
{
    $this->request->data['DisciplinaryProceeding']['defence_witness_staff_id']= explode(",", $this->data['DisciplinaryProceeding']['defence_witness_staff_id']);
}
// debug($this->request->data['DisciplinaryProceeding']['defence_witness_prisoner_id']);
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
                           
                            <?php
                            // debug($offencesList);
                            // debug($punishmentProcessData);
                            if(true){
                                ?>
                                <li><a href="#disciplinaryProceedings" id="disciplinaryProceedingsDiv">Record Offence | Disciplinary Proceedings</a></li>
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
                           
                            <div id="disciplinaryProceedings">
                                <?php
                                 if(($isAccess == 1 && $disciplinaryProceedingCount == 0) || count($this->data)>0){?>
                                    <?php echo $this->Form->create('DisciplinaryProceeding',array('','class'=>'form-horizontal','type'=>'file'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden', ))?>
                                    <?php
                                    // debug($offencesProceedingList);
                                    ?>
                                    <div class="row" style="padding-bottom: 14px; margin-left:0;">
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
                                                    <?php echo $this->Form->input('offence_type',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>Configure::read("OFFENCETYPE"), 'empty'=>'-- Select Offence Type --','required','id'=>'offence_type','title'=>'Please select offence type',"onchange"=>"showOffences(this.value)"));?>
                                                </div>
                                            </div>
                                        </div>       
                                        <div class="clearfix"></div>                
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Offence Name<?php echo $req; ?> :</label>
                                                <div class="controls" id="offence_list">
                                                    <?php echo $this->Form->input('internal_offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Offences --','required','id'=>'internal_offence_id','title'=>'Please select offence name'));?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Rules and Regulations:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('rule_regulation_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$ruleRegulationList, 'empty'=>'-- Select --','required'=>false,'title'=>'Please select Rules and Regulations'));?>
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
                                       
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Compliant/Victim of Offence :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('offence_victim',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,'id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Particulars of offence </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('offence_descr',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,'id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                                </div>
                                            </div>
                                        </div>
                                       <!--  <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Place of Prisoner's Offence<?php //echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php //echo $this->Form->input('offence_place',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                                </div>
                                            </div>
                                        </div>       -->                                  
                                      
                                        
                                       
                                        
                                        <!-- starts Disciplinary Proceeding -->
                                 <div class="container-fluid">
                                        <div class="span12 secondDiv widget-box" id="disciplinary_pro" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; margin-left: 15px !important; display: none;">
                                        <div class="widget-title">
                                            <h5>Displinary Proceeding</h5>
                                        </div>
                                        <div class="widget-content">
                                            <!-- <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Offence Name<?php //echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php //echo $this->Form->input('disciplinary_proceeding_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offencesList, 'empty'=>'-- Select Offence Name --','required','title'=>'Please provide offence name','onchange'=>"showDecision()"));?>
                                                </div>
                                            </div>
                                        </div> -->
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Date of Hearing <?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('date_of_hearing',array('div'=>false,'label'=>false,'class'=>'form-control  span12 minCurrentDate','type'=>'text', 'placeholder'=>'Please Select Date Of Hearing','required'=>false,'readonly'=>true,'title'=>'Please Select Date Of Hearing','value'=>date("d-m-Y")));?>
                                                    </div>
                                                </div>
                                            </div> 
                                         
                                               <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Plea Type<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php 
                                                    // debug($this->data);
                                                    $pleaTypeList = array('Guilty'=>'Guilty','Not Guilty'=>'Not Guilty');
                                                    echo $this->Form->input('plea_type',array('div'=>false,'label'=>false,'class'=>'form-control span12','type'=>'select','options'=>$pleaTypeList, 'empty'=>'-- Select Plea Type --','required'=>false,'title'=>'Please provide offence name','onchange'=>"showDisciplinaryProceeding(this.value)", 'id'=>'plea_type'));?>
                                                </div>
                                            </div>
                                        </div>  
                                           
                                            
                                            
                                         
                                        </div>

                                        </div>



                                    </div>

                                </div>
                                <div class="container-fluid">
                                    <div class="span12 secondDiv widget-box" id="prosecution_id" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; margin-left: 15px !important; display: none;">
                                        <div class="widget-title">
                                            <h5>Prosecutions</h5>
                                        </div>
                                        <div class="widget-content">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Prosecutions evidence <?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('prosecutions_witness',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please Provide Prosecution evidence Details','title'=>'Please Provide Prosecution evidence Details','required'=>false));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Cross examination <?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('prosecutions_cross_examination',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'title'=>'Please provide cross examination', 'required'=>false));?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="container-fluid">
                                            <div class="row-fluid formSepBox"  style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;padding: 0px;">
                                                <div class="widget-title">
                                                    <h5>Witness</h5>
                                                </div>

                                                <div class="span4">
                                                    
                                                    <div class="control-group">
                                                        <label class="control-label" style="width: 66px;">Prisoner:</label>
                                                        <div class="controls" style="margin-left: 74px;">
                                                            <?php 
                                                            echo $this->Form->input('prosecutions_witness_prisoner_id',array('div'=>false,'label'=>false,'hiddenField'=>false,'class'=>'form-control span11 multiselectDropdown','multiple'=>'multiple','type'=>'select','required'=>false,'options'=>$prisonerDisciplinary, 'empty'=>'-- Select Prisoner --','title'=>'Please provide prosecutions witness prisoner'));?>
                                                            
                                                        </div>
                                                    </div>
                                                </div> 
                                         
                                                <div class="span4">
                                                    
                                                     <div class="control-group">
                                                            <label class="control-label" style="width: 66px;">Staffs:</label>
                                                           <div class="controls" style="margin-left: 74px;">
                                                                <?php 
                                                                echo $this->Form->input('prosecutions_witness_staff_id',array('div'=>false,'label'=>false,'hiddenField'=>false,'class'=>'form-control span11 multiselectDropdown','multiple'=>'multiple','type'=>'select','options'=>$staffDisciplinary, 'required'=>false,'empty'=>'-- Select Staff --',"title"=>"Please select prosecutions witness staff"));?>
                                                            </div>
                                                        </div>
                                                </div> 
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label class="control-label" style="width: 66px;">Other:</label>
                                                        <div class="controls" style="margin-left: 74px;">
                                                                <?php echo $this->Form->textfield('prosecutions_witness_other_text',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Evidence','class'=>'form-control','type'=>'text','id' => 'prosecutions_witness_other_text','required'=>false));?>
                                                        </div>
                                                    </div>
                                                </div> 

                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">
                                                       Attached Documentary Evidence
                                                        <!-- <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,gif) type summary_document!" id='example'></i> -->
                                                        :
                                                    </label>
                                                    <div class="controls">
                                                        <div id="prevImage_photo" class="">
                                                            <?php if(isset($this->request->data["Prisoner"]["prosecutions_documentary_evidence"]))
                                                            {?>
                                                                <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/discipline/<?php echo $this->request->data["Prisoner"]["prosecutions_documentary_evidence"];?>" data-lightbox="example-set"><img src="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["prosecutions_documentary_evidence"];?>" alt="" width="100px" height="100px"></a>
                                                            <?php }?>
                                                        </div>
                                                        
                                                        <?php echo $this->Form->input('prosecutions_documentary_evidence',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file', 'required'=>false,'title'=>'Please attach documentary evidence'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Result Of Ruling<?php echo $req; ?> :</label>
                                                    <div class="controls">

                                                        <?php 
                                                        $rullinglist = array('1' => 'No Case To Answer', '2' => 'Case To Answer' );

                                                        echo $this->Form->input('result_rulling',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','id'=>'result_rulling','options'=>$rullinglist, 'empty'=>'Select Result Of Ruling','onchange' => 'showRulling(this.value)','title'=>'Please select Result Of Ruling'));?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid">
                                    <div class="span12 secondDiv widget-box" id="rulling" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; margin-left: 15px !important; display: none;">
                                        <div class="widget-title">
                                            <h5>Defence</h5>
                                        </div>

                                              <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Defence evidence <?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('defense_witness',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'defense_witness', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please Provide defence evidence','title'=>'Please Provide defence evidence','required'=>false));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Cross examination <?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('cross_examination_defence',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','cols'=>30, 'rows'=>3,'style'=>"width:100%;",'title'=>'Please provide Cross examination','placeholder'=>'Please provide Cross examination'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="clearfix"></div>
                                            <div class="container-fluid">
                                                <div class="row-fluid formSepBox" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;padding: 0px;">
                                                    <div class="widget-title">
                                                        <h5>Witness</h5>
                                                    </div>
                                                    <div class="span4">
                                                        <div class="control-group">
                                                            <label class="control-label" style="width: 66px;">Prisoner:</label>
                                                            <div class="controls" style="margin-left: 74px;">
                                                                <?php 
                                                                echo $this->Form->input('defence_witness_prisoner_id',array('div'=>false,'label'=>false,'hiddenField'=>false,'class'=>'form-control span11 multiselectDropdown','multiple'=>'multiple','type'=>'select','options'=>$prisonerDisciplinary, 'empty'=>'-- Select Prisoner --','required'=>false,'title'=>'Please provide prosecutions witness prisoner'));
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <div class="span4">
                                                        <div class="control-group">
                                                            <label class="control-label" style="width: 66px;">Staffs:</label>
                                                            <div class="controls" style="margin-left: 74px;">
                                                            <?php 
                                                            echo $this->Form->input('defence_witness_staff_id',array('div'=>false,'label'=>false,'hiddenField'=>false,'class'=>'form-control span11 multiselectDropdown','multiple'=>'multiple','type'=>'select','options'=>$staffDisciplinary, 'empty'=>'-- Select Staff --','required'=>false,"title"=>"Please select prosecutions witness staff"));?>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <div class="span4">
                                                        <div class="control-group">
                                                            <label class="control-label" style="width: 66px;">Other:</label>
                                                            <div class="controls" style="margin-left: 74px;">
                                                                <?php echo $this->Form->textfield('defence_witness_other_text',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Evidence','class'=>'form-control','type'=>'text','id' => 'other_text','required'=>false));?>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid not-guilty-div" style="display: none;">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Rulers :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('rulers_text',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'rulers_text', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please Provide Defence Details','title'=>'Please Provide Defence Details'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Summary Cases <?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('summary_cases',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'summary_cases', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please Provide Defence Details','title'=>'Please Provide Defence Details'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">
                                                       Attached Documentary Evidence
                                                        <!-- <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,gif) type summary_document!" id='example'></i> -->
                                                        :
                                                    </label>
                                                    <div class="controls">
                                                        <div id="prevImage_photo" class="">
                                                            <?php if(isset($this->request->data["Prisoner"]["defence_documentary_evidence"]))
                                                            {?>
                                                                <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/discipline/<?php echo $this->request->data["Prisoner"]["defence_documentary_evidence"];?>" data-lightbox="example-set"><img src="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["defence_documentary_evidence"];?>" alt="" width="100px" height="100px"></a>
                                                            <?php }?>
                                                        </div>
                                                     
                                                        <div class="clear"></div>
                                                        <?php echo $this->Form->input('defence_documentary_evidence',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file', 'required'=>false,'title'=>'Please attache documentary evidence'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Judgement <?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php 
                                                        $rullinglist = array('1' => 'Convicted', '2' => 'Acquited' );

                                                        echo $this->Form->input('judgement_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','id'=>'judgement_id','options'=>$rullinglist, 'empty'=>'-- Select --','onchange'=>'showJudgement(this.value)','title'=>'Please select Result Of Ruling'));
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="container-fluid" id="conviction_id" style="display: none;background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;padding: 0px;">
                                            <div class="row-fluid" >
                                                <div class="span6">
                                                    
                                                     <div class="control-group">
                                                            <label class="control-label">Conviction<?php echo $req; ?> :</label>
                                                            <div class="controls">
                                                                <?php echo $this->Form->input('conviction_text',array('type'=>'textarea', 'div'=>false,'label'=>false,'id'=>'conviction_text','class'=>'form-control','required'=>false, 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please provide conviction','title'=>'Please provide conviction'));?>
                                                            </div>
                                                        </div>
                                                </div> 
                                                <div class="span6">
                                                    <div class="control-group">
                                                    <label class="control-label">Mitigation<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                    <?php echo $this->Form->input('mitigation',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false, 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'placeholder'=>'Please provide mitigation','title'=>'Please provide mitigation'));?>
                                                    </div>
                                                    </div>
                                                </div> 
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Right To Appeal Explained<?php echo $req; ?>:</label>
                                                        <div class="controls uradioBtn" >
                                                                <?php 

                                                                        $right_to_appeal = "No";
                                                                        if(isset($this->data['CauseList']['right_to_appeal']))
                                                                            $right_to_appeal = $this->data['CauseList']['right_to_appeal'];
                                                                        $options2= $mentalcaseList;
                                                                        $attributes2 = array(
                                                                            'legend' => false, 
                                                                            'value' => $right_to_appeal,
                                                                        );
                                                                        echo $this->Form->radio('right_to_appeal', $options2, $attributes2);
                                                
                                                              ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid not-guilty-div" style="display: none;">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">
                                                       Attached Summary of Ruling <?php echo $req; ?>
                                                        <!-- <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,gif) type summary_document!" id='example'></i> -->
                                                        :
                                                    </label>
                                                    <div class="controls">
                                                        <div id="prevImage_photo" class="">
                                                            <?php if(isset($this->request->data["Prisoner"]["summary_document"]))
                                                            {?>
                                                                <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/discipline/<?php echo $this->request->data["Prisoner"]["summary_document"];?>" data-lightbox="example-set"><img src="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["summary_document"];?>" alt="" width="100px" height="100px"></a>
                                                            <?php }?>
                                                        </div>
                                                       
                                                        <div class="clear"></div>
                                                        <?php echo $this->Form->input('summary_document',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'summary_document', 'required'=>false,'title'=>'Please attached summary of ruling'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <label class="control-label">Adjudicating Officer <?php echo $req; ?>:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('adjusting_officer',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$staffDisciplinary, 'empty'=>'','title'=>'Please select adjusting officer'));?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                        <!-- Ends Discplinary Proceeding -->
                                    <div class="form-actions" align="center">
                                        <?php  echo $this->Form->button('Add Discplinary Proceeding', array('type'=>'button', 'id'=>'add_id','div'=>false,'label'=>false, 'class'=>'btn btn-success','onclick' =>"addDisplinary()"));?>

                                        <?php 
                                       // debug($this->data['DisciplinaryProceeding']);
                                        if(isset($this->data['DisciplinaryProceeding']) && is_array($this->data['DisciplinaryProceeding']) && count($this->data['DisciplinaryProceeding'])>0){
                                            echo $this->Form->button('Update', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success'));
                                        }else{
                                            echo $this->Form->button('Submit', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success'));
                                        }

                                        ?>
                                       
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
                                                    <?php echo $this->Form->input('punishment_date',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Punishment  Date','required','readonly'=>'readonly','title'=>'Please provide punishment date','value'=>date("d-m-Y")));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Offence Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('disciplinary_proceeding_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offencesList, 'empty'=>'-- Select Offence Name --','required','title'=>'Please provide offence name','onchange'=>"showDecision()"));?>
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
                                                    <?php echo $this->Form->input('remarks',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'remarks', 'cols'=>30, 'rows'=>3,'style'=>"width:100%;",'title'=>'Please provide remarks'));?>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Full Diet:</label>
                                                <div class="controls">
                                                   <?php echo $this->Form->input('full_diet',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','id'=>'full_diet', 'style'=>''));?>
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

//debug($this->data['DisciplinaryProceeding']);
echo $internal_offence_id = (isset($this->data['DisciplinaryProceeding']['internal_offence_id']) && $this->data['DisciplinaryProceeding']['internal_offence_id']!='') ? $this->data['DisciplinaryProceeding']['internal_offence_id'] : '';
?>
<script type="text/javascript">
   // alert('5');
$(document).ready(function(){
    // alert('d');

    $(".multiselectDropdown").select2();
    <?php
    if(isset($this->data['DisciplinaryProceeding']) && is_array($this->data['DisciplinaryProceeding']) && count($this->data['DisciplinaryProceeding'])>0){
        ?>
        $('#offence_type').select2('val', '<?php echo $this->data['DisciplinaryProceeding']['offence_type']; ?>');
        $('#internal_offence_id').select2('val', '<?php echo $this->data['DisciplinaryProceeding']['internal_offence_id']; ?>');
        $('#offence_recorded_by').select2('val', '<?php echo $this->data['DisciplinaryProceeding']['offence_recorded_by']; ?>');

        // alert('1');
        
        showOffences('<?php echo $this->data['DisciplinaryProceeding']['offence_type']; ?>');
        // alert('2');
          
        <?php
        
    }
    if (isset($this->data['DisciplinaryProceeding']['plea_type']) && $this->data['DisciplinaryProceeding']['plea_type'] != '' ) {

    	?>
    	addDisplinary();
    	showDisciplinaryProceeding('<?php echo $this->data['DisciplinaryProceeding']['plea_type']; ?>');
    	<?php
    }
    if (isset($this->data['DisciplinaryProceeding']['result_rulling']) && $this->data['DisciplinaryProceeding']['result_rulling'] != '' ) {

    	?>
    	//showRulling(isdual); 
    	showRulling('<?php echo $this->data['DisciplinaryProceeding']['result_rulling']; ?>');
    	<?php
    }
     if (isset($this->data['DisciplinaryProceeding']['judgement_id']) && $this->data['DisciplinaryProceeding']['judgement_id'] != '' ) {

    	?>
    	//showRulling(isdual); 
    	showJudgement('<?php echo $this->data['DisciplinaryProceeding']['judgement_id']; ?>');
    	<?php
    }

    if(isset($this->data['InPrisonPunishment']) && is_array($this->data['InPrisonPunishment']) && count($this->data['InPrisonPunishment'])>0){
        ?>
        $('#InPrisonPunishmentInPrisonOffenceCaptureId').select2('val', '<?php echo $this->data['InPrisonPunishment']['disciplinary_proceeding_id']; ?>');
        $('#internal_punishment_id').select2('val', '<?php echo $this->data['InPrisonPunishment']['internal_punishment_id']; ?>');
        showDecision();
        <?php
    }

    if(isset($this->data['DisciplinaryProceeding']) && is_array($this->data['DisciplinaryProceeding']) && count($this->data['DisciplinaryProceeding'])>0){
        ?>
        //alert('<?php //echo $this->data['DisciplinaryProceeding']['disciplinary_proceeding_id']; ?>');
        $('#DisciplinaryProceedingInPrisonOffenceCaptureId').select2('val', <?php echo $this->data['DisciplinaryProceeding']['id']; ?>);

        <?php
    }
    ?>
});
$(function(){
   // alert('1');
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

    $("#DisciplinaryProceedingIndexForm").validate({
        ignore: "",
            rules: {  
                
            },
            messages: {
                
            }
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
$showoffenceUrl = $this->Html->url(array('controller'=>'InPrisonOffenceCapture','action'=>'showOffences'));
echo $this->Html->scriptBlock("
    var tab_param = '';
    var tabs;
    jQuery(function($) {
        showDisciplinaryProceedings();
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
    function showOffences(type){       
    // alert('3'); 
        var url = '".$showoffenceUrl."';
        $.post(url, {'offence_type':type}, function(res) {
            $('#offence_list').html(res);
            // alert('4');
            $('#internal_offence_id').val(".$internal_offence_id.");
            $('#internal_offence_id').select2('val', '".$internal_offence_id."');
        });                    
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
                        //showPunishmentRecords();
                        location.reload(true);
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
        var uuid        = '".$uuid."';
        var url         = '".$unishmentsPeriodUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/punishment_type:'+$('#internal_punishment_id').val();
        url = url + '/disciplinary_proceeding_id:'+$('#InPrisonPunishmentDisciplinaryProceedingId').val();
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
                        //showDisciplinaryProceedings();
                        location.reload(true);
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
    
});


function showDisciplinaryProceeding(val)
{
    if(val == 'Guilty')
    {
        showJudgement(1);
        $(".not-guilty-div").hide();
        $('#prosecution_id').hide();
        $('.guilty').show();
        $('#rulling').hide();
       // $('#conviction_id').show();
         $('#conviction_id').show();
        //$('#prosecution_id')
        //prosecutions_witness


       //DisciplinaryProceedingMitigation
        $("#conviction_text").attr("required", true);
        $("#DisciplinaryProceedingMitigation").attr("required", true);
        $("#prosecutions_witness").removeAttr("required");
        $("#DisciplinaryProceedingCrossExamination").removeAttr("required");
        $("#DisciplinaryProceedingProsecutionsWitness").removeAttr("required");
        $("#result_rulling").removeAttr("required");
        //$("#DisciplinaryProceedingProsecutionsWitnessPrisonerId").removeAttr("required");
       // $("#DisciplinaryProceedingProsecutionsWitnessStaffId").removeAttr("required");


       // $("#plea_type").attr("required", "true");
        // $(".not-guilty textarea").removeAttr("required");
        // $(".not-guilty select").removeAttr("required");
    }
    if(val == 'Not Guilty')
    {
        showJudgement('');
        $(".not-guilty-div").hide();
        $("#conviction_text").removeAttr("required");
        $("#DisciplinaryProceedingMitigation").removeAttr("required");
        $("#DisciplinaryProceedingProsecutionsWitness").attr("required", true);
        $("#result_rulling").attr("required", true);
        $('#prosecution_id').show();
        $('.guilty').hide();
    }

    if(val == '')
    {
       $('#prosecution_id').hide();
        $('.guilty').hide();
       
        $(".not-guilty input").attr("required", "true");
        $(".not-guilty textarea").attr("required", "true");
        $(".not-guilty select").attr("required", "true");
        $(".select2-search-field input").removeAttr("required");
        $("#DisciplinaryProceedingMitigation").removeAttr("required");
        $(".guilty textarea").removeAttr("required");
        $("#DisciplinaryProceedingProsecutionsWitness").removeAttr("required");
        $("#result_rulling").removeAttr("required");
    }
}
function addDisplinary() {
    $("#disciplinary_pro").toggle();
    var data = $('#add_id').html();
    document.getElementById('add_id').innerHTML = 'Hide Discplinary Proceeding';
    if(data.trim()=='Add Discplinary Proceeding'){
        $('#add_id').html('Hide Discplinary Proceeding');
        $("#plea_type").attr("required", "true");
    }else{
        $('#add_id').html('Add Discplinary Proceeding');
        $("#plea_type").removeAttr("required");
    }
}

function showRulling(isdual) 
{
    if (isdual == 1) 
    {
        $('.not-guilty-div').hide();
        $('#rulling').hide();
        $("#defense_witness").removeAttr("required");
        $("#DisciplinaryProceedingCrossExaminationDefence").removeAttr("required");
        $("#summary_cases").removeAttr("required");
        $("#judgement_id").removeAttr("required");
        $("#DisciplinaryProceedingAdjustingOfficer").removeAttr("required");
        $("#summary_document").removeAttr("required");
      
        $("#cross_examination_defence").removeAttr("required");
    }
    else 
    {
        $('.not-guilty-div').show();
        $('#rulling').show();
        $("#defense_witness").attr("required", true);
        $("#DisciplinaryProceedingCrossExaminationDefence").attr("required", true);
        $("#summary_cases").attr("required", true);
        $("#judgement_id").attr("required", true);
        $("#DisciplinaryProceedingAdjustingOfficer").attr("required", true);
        $("#summary_document").attr("required", true);

          // alert('2');
        $("#cross_examination_defence").attr("required", "true");   
    }
    if (isdual == '') 
    {
        $('#rulling').hide();
        $("#defense_witness").removeAttr("required");
        $("#cross_examination_defence").removeAttr("required");
        $("#DisciplinaryProceedingCrossExaminationDefence").removeAttr("required");
        $("#summary_cases").removeAttr("required");
        $("#judgement_id").removeAttr("required");
        $("#DisciplinaryProceedingAdjustingOfficer").removeAttr("required");
        $("#summary_document").removeAttr("required");
        $("#cross_examination_defence").removeAttr("required");
    } 
}
 function showJudgement(isdual) {
    if (isdual == 1) {
        $('#conviction_id').show();
        $("#conviction_text").attr("required", true);
        $("#DisciplinaryProceedingMitigation").attr("required", true);
    }else{
        $('#conviction_id').hide();
        $("#conviction_text").removeAttr("required");
        $("#DisciplinaryProceedingMitigation").removeAttr("required");
    }
 }
</script>