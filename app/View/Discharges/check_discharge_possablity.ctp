
<?php
if(isset($this->data['Discharge']['escape_date']) && $this->data['Discharge']['escape_date'] != ''){
    $this->request->data['Discharge']['escape_date'] = date('d-m-Y H:i', strtotime($this->data['Discharge']['escape_date']));
}
if(isset($this->data['Discharge']['bail_date']) && $this->data['Discharge']['bail_date'] != ''){
    $this->request->data['Discharge']['bail_date'] = date('d-m-Y', strtotime($this->data['Discharge']['bail_date']));
}
if(isset($this->data['Discharge']['execution_date']) && $this->data['Discharge']['execution_date'] != ''){
    $this->request->data['Discharge']['execution_date'] = date('d-m-Y H:i', strtotime($this->data['Discharge']['execution_date']));
}

if(isset($this->data['Discharge']['clearance']) && $this->data['Discharge']['clearance'] != ''){
    $this->request->data['Discharge']['clearance'] = explode(",", $this->data['Discharge']['clearance']);
}
if($discharge_type_id==5){
?>
<!-- this div using for eascape of prisoner -->
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Date & Time of escape<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->input('Discharge.escape_date',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Date of Escape','readonly'=>'readonly','class'=>'form-control mydatetimepicker1 span11','required', 'id'=>'escape_date',"title"=>"please provide the date and time of escape"));?>
            </div>
        </div>
    </div>                     
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Escaped From <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.escape_from',array('type'=>'select', 'div'=>false,'label'=>false,'options'=>array("Inside"=>"Inside","Outside"=>"Outside"),'class'=>'form-control','required',"title"=>"please select escape from"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Escaped Type<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.escape_type',array('type'=>'select', 'div'=>false,'label'=>false,'options'=>$escapeType,'empty'=>'-- Select Escape Type--','class'=>'form-control select2','required',"title"=>"please select escape type"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Total Sentence <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php
                $sentanceDetails = '';
                $lpd = (isset($prisonerDetails['Prisoner']['sentence_length']) && $prisonerDetails['Prisoner']['sentence_length']!='') ? json_decode($prisonerDetails['Prisoner']['sentence_length']) : array();
                    $remission = array();
                    if(isset($lpd) && count((array)$lpd)>0){
                        foreach ($lpd as $key => $value) {
                            if($key == 'days'){
                                $remission[2] = $value." ".$key;
                            }
                            if($key == 'years'){
                                $remission[0] = $value." ".$key;
                            }
                            if($key == 'months'){
                                $remission[1] = $value." ".$key;
                            }                        
                        }
                        ksort($remission);
                        $sentanceDetails = implode(", ", $remission); 
                    }            
                ?>
                <?php 
                echo $this->Form->input('Discharge.total_sentance',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Total Sentance','class'=>'form-control span11','required','readonly'=>false, 'id'=>'total_sentance',"title"=>"Please provide no. of sentance","value"=>$sentanceDetails));?>
            </div>
        </div>
    </div> 
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Place <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.place',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Place','class'=>'form-control span11','required', 'id'=>'place',"title"=>"Please provide place"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Manner Of Escape <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.manner_escape',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Manner Of Escape','class'=>'form-control span11','required',"title"=>"Please provide Manner Of Escape"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Person from whose custody escaped  :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.custody_escaped.',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$officerList, 'empty'=>'-- Select --','required','id'=>'custody_escaped','title'=>"Please select officer","multiple"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Labor employed into at admission  :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.labor_admission',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Labor employed into at admission','class'=>'form-control span11','required', 'id'=>'labor_admission',"title"=>"Please provide labor employed into at admission"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Labour Employed into on escape  :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.labor_escape',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Labour Employed into on escape','class'=>'form-control span11','required', 'id'=>'labor_escape',"title"=>"Please provide labour Employed into on escape"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,"title"=>"please upload document"));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }else{
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required',"title"=>"please upload document"));
                }                                                        
                ?>
            </div>
        </div>
    </div> 
<?php
}
/// Female Prisoner with an un-weaned child
if($discharge_type_id==9){
?>
<!-- this div using for eascape of prisoner -->
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in','onclick'=>"start()"));
                ?>
                <?php echo $this->Form->input('Discharge.is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','required','title'=>"Please verifiy from biometric","id"=>"link_biometric_verified"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Signature <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['signature']) && $this->data['Discharge']['signature']!=''){
                    echo $this->Form->input('Discharge.signature',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["signature"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));
                }else{
                   echo $this->Form->input('Discharge.signature',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required','title'=>'Please upload signature')); 
                }

                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,"title"=>"please upload document"));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }else{
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required',"title"=>"please upload document"));
                }                                                        
                ?>
            </div>
        </div>
    </div> 
<?php
}
//Grant of Prerogative of Mercy
if($discharge_type_id==7){
?>
<!-- this div using for eascape of prisoner -->
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in','onclick'=>"start()"));
                ?>
                <?php echo $this->Form->input('Discharge.is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','required','title'=>"Please verifiy from biometric","id"=>"link_biometric_verified"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Signature <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['signature']) && $this->data['Discharge']['signature']!=''){
                    echo $this->Form->input('Discharge.signature',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["signature"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));
                }else{
                   echo $this->Form->input('Discharge.signature',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required','title'=>'Please upload signature')); 
                }

                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,"title"=>"please upload document"));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }else{
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required',"title"=>"please upload document"));
                }                                                        
                ?>
            </div>
        </div>
    </div> 
<?php
}
//normal dischagre
if($discharge_type_id==2){
?>
    <!-- this div using for Normal Discharge -->
    <?php
    if(isset($prisonerDetails['Prisoner']['epd']) && $prisonerDetails['Prisoner']['epd']!='0000-00-00'){
    ?>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">EPD<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                $epd = date("d-m-Y",strtotime($prisonerDetails['Prisoner']['epd']));
                echo $this->Form->input('Discharge.epd',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'','readonly'=>'readonly','class'=>'form-control span11','required',"title"=>"Please provide epd",'value'=>$epd)); ?>
            </div>
        </div>
    </div>
    <?php
    }
    if(isset($prisonerDetails['Prisoner']['lpd']) && $prisonerDetails['Prisoner']['lpd']!='0000-00-00'){
    ?>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">LPD<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                $lpd = date("d-m-Y",strtotime($prisonerDetails['Prisoner']['lpd']));
                echo $this->Form->input('Discharge.lpd',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'','readonly'=>'readonly','class'=>'form-control span11','required',"title"=>"Please provide lpd",'value'=>$lpd)); ?>
            </div>
        </div>
    </div>
    <?php
    }
    if(isset($prisonerDetails['Prisoner']['remission']) && $prisonerDetails['Prisoner']['remission']!=''){
    ?>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Remission <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 

                $lpd = json_decode($prisonerDetails['Prisoner']['remission']);
                 // debug($lpd);
                $remission = array();
                if(isset($lpd) && count((array)$lpd)>0){
                    foreach ($lpd as $key => $value) {
                        if($key == 'days'){
                            $remission[2] = $value." ".$key;
                        }
                        if($key == 'years'){
                            $remission[0] = $value." ".$key;
                        }
                        if($key == 'months'){
                            $remission[1] = $value." ".$key;
                        }       
                        ksort($remission);                 
                    }
                }
                echo $this->Form->input('Discharge.remission',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'','readonly'=>'readonly','class'=>'form-control span11','required',"title"=>"Please provide remission",'value'=>implode(", ", $remission))); ?>
            </div>
        </div>
    </div>    
    <?php
    }
    ?>     
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Next Destination <?php echo $req; ?>:</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.next_destination',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Please provide next destination','required','type'=>'textarea','rows'=>2,'title'=>"Please provide next destination"));?>
            </div>
        </div>
    </div> 
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in','onclick'=>"start()"));
                ?>
                <?php echo $this->Form->input('Discharge.is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','required','title'=>"Please verifiy from biometric","id"=>"link_biometric_verified"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,"title"=>"please upload document"));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }else{
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required',"title"=>"please upload document"));
                }                                                        
                ?>
            </div>
        </div>
    </div>
    
<?php
}
//prisoner death
if($discharge_type_id==3){
?>
    <?php

    $medicalDeathDetails = $funcall->getDetails("MedicalDeathRecord",$prisonerDetails['Prisoner']['id']);
    
    // debug($this->request->data);
    ?>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Date & Time of Death :</label>
            <div class="controls">
                <?php 
                echo date("d-m-Y", strtotime($medicalDeathDetails['MedicalDeathRecord']['check_up_date'])); ?>
            </div>
        </div>
    </div>   
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Place of Death :</label>
            <div class="controls">
                <?php 
                echo $medicalDeathDetails['MedicalDeathRecord']['death_place']. " ";
                echo ($medicalDeathDetails['MedicalDeathRecord']['death_place']=='Out') ? $medicalDeathDetails['MedicalDeathRecord']['place_name'] : ''; ?>
            </div>
        </div>
    </div>  
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Cause of death :</label>
            <div class="controls">
                <?php 
               echo $medicalDeathDetails['MedicalDeathRecord']['death_cause']; ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Presumed Cause of death  :</label>
            <div class="controls">
                <?php 
               echo $medicalDeathDetails['MedicalDeathRecord']['death_cause']; ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Actual Cause of Death :</label>
            <div class="controls">
                <?php 
               echo $medicalDeathDetails['MedicalDeathRecord']['death_cause']; ?>
            </div>
        </div>
    </div>    
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Medical Officer :</label>
            <div class="controls">
                <?php
                echo $funcall->getName($medicalDeathDetails['MedicalDeathRecord']['medical_officer_id_death'],"User","name");
                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Death Certificate :</label>
            <div class="controls">
                <?php 
                echo $this->Html->link('View', '../files/prisnors/MEDICAL/'.$medicalDeathDetails['MedicalDeathRecord']['medical_from_attach'], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-left')); ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Upload Postmotorm Report  :</label>
            <div class="controls">
                <?php 
                echo ($medicalDeathDetails['MedicalDeathRecord']['attachment']=='') ? 'Not Uploaded' : $this->Html->link('View', '../files/prisnors/MEDICAL/'.$medicalDeathDetails['MedicalDeathRecord']['attachment'], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-left')); ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Upload Pathologist Report :</label>
            <div class="controls">
                <?php 
                echo ($medicalDeathDetails['MedicalDeathRecord']['pathologist_attach']=='') ? 'Not Uploaded' : $this->Html->link('View', '../files/prisnors/MEDICAL/'.$medicalDeathDetails['MedicalDeathRecord']['pathologist_attach'], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-left')); ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
     <div class="span6">
        <div class="control-group">
            <label class="control-label">SD No.<?php echo $req; ?>:</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.sd_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'alphaNumeric', 'placeholder'=>'Please provide sd no','required','type'=>'text','title'=>"Please provide sd no"));?>
            </div>
        </div>
    </div> 
    
    <?php
    /*
    ?>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Pathologists Signature <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['signature']) && $this->data['Discharge']['signature']!=''){
                    echo $this->Form->input('Discharge.signature',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["signature"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));
                }else{
                   echo $this->Form->input('Discharge.signature',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required','title'=>'Please upload signature')); 
                }

                ?>
            </div>
        </div>
    </div>
    <?php
    */
    ?>
<?php
}
//Release Execution
if($discharge_type_id==10){
?>
<!-- this div using for Release Execution -->
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Date & Time of Execution<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->input('Discharge.execution_date',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Date & Time of Execution','readonly'=>'readonly','class'=>'form-control mydatetimepicker1 span11','required',"title"=>"please provide the date and time of execution"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Issue date of Death Warrant<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->input('Discharge.death_warrant',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Issue date of Death Warrant','readonly'=>'readonly','class'=>'form-control mydate1 span11','required',"title"=>"please provide the issue date of death warrant"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Approving authority  :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.approving_authority',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select span11','type'=>'select','options'=>$officerList, 'empty'=>'-- Select --','required','title'=>"Please select officer"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,"title"=>"please upload document"));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }else{
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required',"title"=>"please upload document"));
                }                                                        
                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Cause Of Execution <?php echo $req; ?>:</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.cause_execution',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Cause Of Execution','required','type'=>'textarea','rows'=>2,'title'=>"Please provide Cause Of Execution"));?>
            </div>
        </div>
    </div> 
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Medical officer's Remark <?php echo $req; ?>:</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.medical_remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Medical officer\'s Remark','required','type'=>'textarea','rows'=>2,'title'=>"Please provide Medical officer's Remark"));?>
            </div>
        </div>
    </div>
<?php
}
//release on bail
if($discharge_type_id==1){
?>
<!-- this div using for Release Execution -->
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Date of Bail<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->input('Discharge.bail_date',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Date of Bail','readonly'=>'readonly','class'=>'form-control from_date span11','required',"title"=>"please provide the Date of bail"));?>
            </div>
        </div>
    </div>
    <?php
    if($prisonerDetails['Prisoner']['prisoner_type_id']!=1){
    ?>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">End Date of Bail<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->input('Discharge.end_bail_date',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Date of Bail','readonly'=>'readonly','class'=>'form-control to_date span11','required',"title"=>"please provide the end date of bail","required"=>true));?>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in','onclick'=>"start()"));
                ?>
                <?php echo $this->Form->input('Discharge.is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','required','title'=>"Please verifiy from biometric","id"=>"link_biometric_verified"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,"title"=>"please upload document"));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }else{
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required',"title"=>"please upload document"));
                }                                                        
                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Bail Remark <?php echo $req; ?>:</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.bail_remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Bail Remark','required','type'=>'textarea','rows'=>2,'title'=>"Please provide Medical officer's Remark"));?>
            </div>
        </div>
    </div>
<?php
}
// Discharge on reparted partha

if($discharge_type_id==12){
?>
<!-- this div using for Repartiation -->

    <div class="span6">
        <div class="control-group">
            <label class="control-label">Country<?php echo $req; ?> :</label>
            <div class="controls">
                 <?php echo $this->Form->input('Discharge.reparted_country',array('type'=>'select', 'div'=>false,'label'=>false,'options'=>$countryList,'empty'=>'-- Select Country --','class'=>'form-control select2','required',"title"=>"please select country"));?>
            </div>
        </div>
    </div>
     <div class="span6">
        <div class="control-group">
            <label class="control-label">Station <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.reparted_station',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Place','class'=>'form-control span11','required', 'id'=>'place',"title"=>"Please provide place"));?>
            </div>
        </div>
    </div>
    
   
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in','onclick'=>"start()"));
                ?>
                <?php echo $this->Form->input('Discharge.is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','required','title'=>"Please verifiy from biometric","id"=>"link_biometric_verified"));?>
            </div>
        </div>
    </div>
     <div class="span6">
        <div class="control-group">
            <label class="control-label">Reparted Order<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,"title"=>"please upload document"));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }else{
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required',"title"=>"please upload document"));
                }                                                        
                ?>
            </div>
        </div>
    </div> 
<?php
}
// prisoner on death starts partha 
if($discharge_type_id==3){
?>
<!-- this div using for Repartiation -->

  <div class="span6">
        <div class="control-group">
            <label class="control-label">Name <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.prison_death_name',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Name','class'=>'form-control span11','required'=>true, 'id'=>'prison_death_name',"title"=>"Please provide Name"));?>
            </div>
        </div>
    </div>
     <div class="span6">
        <div class="control-group">
            <label class="control-label">National Id No <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.prison_death_id',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'National Id No','class'=>'form-control span11','required', 'id'=>'prison_death_id',"title"=>"Please provide National Id No"));?>
            </div>
        </div>
    </div>
     <div class="span6">
        <div class="control-group">
            <label class="control-label">Telephone No <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.prison_death_telephone',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Telephone No','class'=>'form-control span11','required', 'id'=>'prison_death_telephone',"title"=>"Please provide Telephone No"));?>
            </div>
        </div>
    </div>
     <div class="span6">
        <div class="control-group">
            <label class="control-label">Place Of Residence <?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.prison_death_residence',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Place Of Residence','class'=>'form-control span11','required', 'id'=>'prison_death_residence',"title"=>"Please provide Place Of Residence"));?>
            </div>
        </div>
    </div>
     <div class="span6">
        <div class="control-group">
            <label class="control-label">District Of Residence<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php echo $this->Form->input('Discharge.prison_death_district',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'District Of Residence','class'=>'form-control span11','required', 'id'=>'prison_death_district',"title"=>"Please provide District Of Residence"));?>
            </div>
        </div>
    </div>
    
    
    
   
    
     
<?php
}

// prisoner on death ends partha


//Release on License to be at large
if($discharge_type_id==8){
?>
<!-- this div using for Release Execution -->    
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
            <div class="controls">
                <?php 
                echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in','onclick'=>"start()"));
                ?>
                <?php echo $this->Form->input('Discharge.is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','required','title'=>"Please verifiy from biometric","id"=>"link_biometric_verified"));?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs <?php echo MANDATORY; ?> :</label>
            <div class="controls"
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false,"title"=>"please upload document"));
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }else{
                    echo $this->Form->input('Discharge.attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required',"title"=>"please upload document"));
                }                                                        
                ?>
            </div>
        </div>
    </div>
<?php
}

if($discharge_type_id!=''){
    if($discharge_type_id!=5){
        $adddressReleasePlace =  (isset($prisonerDetails['Prisoner']['is_death']) && $prisonerDetails['Prisoner']['is_death']==1) ? 'Destination' : 'Address on release';
?>
<div class="span6">
    <div class="control-group">
        <label class="control-label"><?php echo (isset($prisonerDetails['Prisoner']['is_death']) && $prisonerDetails['Prisoner']['is_death']==1) ? 'Destination' : 'Address on release'; ?></label>
        <div class="controls">
            <?php echo $this->Form->input('Discharge.address_release',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>$adddressReleasePlace,'type'=>'textarea','rows'=>2,'title'=>"Please provide address"));?>
        </div>
    </div>
</div>
<?php
    if($prisonerDetails['Prisoner']['prisoner_type_id']==Configure::read("CONVICTED")){
    ?>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Clearance <?php echo MANDATORY; ?> :</label>
            <div class="controls uradioBtn">
                <?php
                $options = array();
                if($prisonerDetails['Prisoner']['is_long_term_prisoner']==0){
                    $options += array('PF4 Cleared'=>'PF4 Cleared');
                }else{
                    $options += array('PF3 Cleared' => 'PF3 Cleared');                    
                }
                $options += array('Property Cleared'=>'Property Cleared','Medical Cleared'=> 'Medical Cleared', 'Earning Cleared'=>'Earning Cleared','Stage Cleared'=>'Stage Cleared');
                // $selected = array(1, 3);
                echo $this->Form->input('Discharge.clearance', array('multiple' => 'checkbox', 'options' => $options, 'label'=>false,'div'=>false,'required'=>true,'hiddenField'=>false,'class'=>'form-control span11', 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ),'selected'=>$options ));                                             
                ?>
                <label for="data[Discharge][clearance][]" generated="true" class="error" style="display: none;">Please clear all module before discharge</label>
            </div>

        </div>
    </div>
    <?php
    }
}
?>
<div class="row-fluid">
                                        
    <div class="clearfix"></div> 
</div>
<div class="span12">
    <div class="form-actions" align="center">
        <?php echo $this->Form->button('Save', array('type'=>'submit','class'=>'btn btn-primary','div'=>false,'label'=>false))?>
        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetForm('DischargeIndexForm')")); ?>
        
        <style type="text/css" media="screen">
            .checker {
                float: left;
            }
            .form-control label{
                float: left;
            }
        </style>
    </div>
</div>
<?php
}
?>
<script type="text/javascript">
    $('.pmis_select').select2();
    $('#custody_escaped').select2();
    $('.mydatetimepicker1').datetimepicker({
        showMeridian: false,
        defaultTime:false,
        format: 'dd-mm-yyyy hh:ii',
        autoclose:true
    }).on('changeDate', function (ev) {
         $(this).datetimepicker('hide');
         $(this).blur();
    });

$('.mydate1').datepicker({
    showMeridian: false,
    defaultTime:false,
    format: 'dd-mm-yyyy',
    autoclose:true
}).on('changeDate', function (ev) {
     $(this).datepicker('hide');
     $(this).blur();
});

$('.from_date').datepicker({
    format: 'dd-mm-yyyy',
    startDate: new Date(),
    autoclose:true,
}).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('.to_date').datepicker('setStartDate', minDate);
     $(this).datepicker('hide');
     $(this).blur();
});
$('.to_date').datepicker({
    format: 'dd-mm-yyyy',
    autoclose:true,
}).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('.from_date').datepicker('setEndDate', minDate);
     $(this).datepicker('hide');
     $(this).blur();
});
</script>