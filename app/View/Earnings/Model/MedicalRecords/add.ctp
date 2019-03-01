<?php
if(isset($this->data['Prisoner'])){
    $this->request->data['MedicalCheckupRecord']['prisoner_name']=$this->data['Prisoner']['fullname'];
    $this->request->data['MedicalCheckupRecord']['age']=$this->data['Prisoner']['age'];
    $gender_id=$this->data["Prisoner"]["gender_id"];
    if($gender_id==2){$this->request->data['MedicalCheckupRecord']["gender"]="Female";}
    else if($gender_id==1){$this->request->data['MedicalCheckupRecord']["gender"]="Male";}
    $height_feet=$this->data["Prisoner"]["height_feet"];
    $height_inch=$this->data["Prisoner"]["height_inch"];
    $this->request->data['MedicalCheckupRecord']['height']=$height_feet." foot ".$height_inch." inch";   
}
?>
<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
    
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Medical Records</h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <ul class="nav nav-tabs">
                            <li><a href="#health_checkup" id="medicalChekupDiv">Initial and Exit Check up</a></li>
                            <li><a href="#sick" id="medicalSickDiv">Clinical Attendence</a></li>
                            <li><a href="#seriouslyill" id="medicalSeriousIllDiv">Recommend For Release</a></li>
                            <li><a href="#death" id="medicalDeathDiv">Death</a></li>
                            
                            <!-- <li class="pull-right controls"> -->
                            <li class="controls pull-right">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="tabscontent">


                            <div id="health_checkup">
                                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                    <h5>Initial and Exit Check up</h5>
                                    <a class="toggleBtn" href="#searchWorkingParty" data-toggle="collapse" htmlshwattr="health_checkup"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){?>
                                        <div style="float:right;padding-top: 3px;">
                                            <?php echo $this->Html->link('Add Initial and Exit Check up','#addWorkingParty',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse", 'htmlshwattr'=>'health_checkup','id'=>"initial_exit_checkup")); ?>
                                        </div>
                                    <?php }?>
                                </div>
                              <div id="addWorkingParty" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;"<?php }?>>
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('MedicalCheckupRecord',array('class'=>'form-horizontal','type'=>'file'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                
                                    
                                    <div class="row" style="padding: 0px 60px;">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Check up<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                   <?php echo $this->Form->input('check_up',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Check up --','options'=>$checkupData, 'class'=>'form-control','required', 'id'=>'check_up'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner No.<?php echo $req; ?> :</label>
                                                <div class="controls" id="priscontent">
                                                   <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select prisoner --','options'=>$prisonerListData1, 'class'=>'form-control','required', 'id'=>'prisoner_id'));?>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                                                   
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner Name<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Prisoner Name','id'=>'prisoner_name','readonly'=>'readonly'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Gender<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('gender',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Gender','id'=>'gender','readonly'=>'readonly'));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div> 
                                         
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Age<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('age',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Age','id'=>'age','readonly'=>'readonly'));?>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Height<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php //echo $this->Form->input('height',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Height','id'=>'height','readonly'=>'readonly'));?>
                                                    <div class="span6">
                                                        <?php echo $this->Form->input('height_feet',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$heightInFeetList, 'empty'=>array('0'=>'-- Height in Feet --'),'required'=>false,'id'=>'height_feet'));?>
                                                    </div>
                                                    <div class="span6">
                                                        <?php echo $this->Form->input('height_inch',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$heightInInchList, 'empty'=>array('0'=>'-- Height in Inch --'),'required'=>false,'id'=>'height_inch'));?>
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                        
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Weight<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('weight',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Weight','id'=>'weight'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">BMI<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('bmi',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'BMI','id'=>'bmi'));?>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                         
                                        <div class="clearfix"></div>

                                        
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">T.B Test<?php echo $req; ?> </label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $tb = "Nagetive";
                                                    if(isset($this->data['MedicalCheckupRecord']['tb']))
                                                        $tb = $this->data['MedicalCheckupRecord']['tb'];
                                                    $options2= $tbList;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $tb,
                                                    );
                                                    echo $this->Form->radio('tb', $options2, $attributes2);
                                                    ?>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">HIV Test<?php echo $req; ?> </label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $hiv = "Nagetive";
                                                    if(isset($this->data['MedicalCheckupRecord']['hiv']))
                                                        $hiv = $this->data['MedicalCheckupRecord']['hiv'];
                                                    if($hiv=="Nagetive"){$display_style="style='display:none'";}
                                                    if($hiv=="Positve"){$display_style="style='display:block'";}
                                                    $options2= $tbList;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $hiv,
                                                        'onChange'=>'showregenment(this.value)',
                                                    );
                                                    echo $this->Form->radio('hiv', $options2, $attributes2);
                                                    ?>
                                                    <?php echo $this->Form->input('regenment',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Regenment','id'=>'regenment',$display_style,'title'=>'Plese enter regenment'));?>
                                                </div>
                                            </div>
                                        </div>
                                         
                                        <div class="clearfix"></div>
                                        

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Mental Case<?php echo $req; ?> </label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $mental_case = "No";
                                                    if(isset($this->data['MedicalCheckupRecord']['mental_case']))
                                                        $mental_case = $this->data['MedicalCheckupRecord']['mental_case'];
                                                    $options2= $mentalcaseList;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $mental_case,
                                                    );
                                                    echo $this->Form->radio('mental_case', $options2, $attributes2);
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Other Diseases</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('other_disease',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Other Diseases','id'=>'other_disease'));?>
                                                   
                                                </div>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>

                                        
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Folow Up Date<?php echo $req; ?></label>
                                                <div class="controls">
                                                <?php
                                                $follow_up=date('m-d-Y');
                                                    if(isset($this->data["MedicalCheckupRecord"]["follow_up"])){
                                                        $follow_up=$this->data["MedicalCheckupRecord"]["follow_up"];
                                                        $parts = explode('-',$follow_up);
                                                        
                                                        $follow_up =$parts[1].'-'.$parts[2].'-'.$parts[0];
                                                    }
                                                ?>
                                                    <?php echo $this->Form->input('follow_up',array('div'=>false,'label'=>false,'class'=>'form-control span11 mydate1','type'=>'text','placeholder'=>'Folow Up Date','id'=>'follow_up','value'=>$follow_up));?>
                                                </div>
                                            </div>
                                        </div>
                                         
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure to save?')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                </div>
                                <div class="collapse <?php if($isEdit == 0){ echo "in";}?>" id="searchWorkingParty"  <?php if($isEdit == 1){?> style="height: 0px;"<?php }?>>
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding: 0px 60px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner No.</label>
                                        <div class="controls">
                                            <?php //echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select prisoner --','options'=>$prisonerListData, 'class'=>'form-control','required', 'id'=>'prisoner_id_serch'));?>
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'prisoner_id_serch', 'style'=>'width:200px;','maxlength'=>'500'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Folow Up Date</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('folow_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'folow_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('folow_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'folow_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Age between</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('age_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide Age','id'=>'age_from', 'required'=>false,'style'=>'width:100px;'));?>
                                            &
                                            <?php echo $this->Form->input('age_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide Age','id'=>'age_to', 'required'=>false,'style'=>'width:100px;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Height</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('hgt_ft',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide height in feet','id'=>'hgt_ft', 'required'=>false,'style'=>'width:130px;'));?>
                                            
                                            <?php echo $this->Form->input('hgt_inch',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide height in inch','id'=>'hgt_inch', 'required'=>false,'style'=>'width:130px;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Weight</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('weight_search',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide weight in KG','id'=>'weight_search', 'required'=>false,'style'=>'width:200px;'));?>
                                            
                                           
                                        </div>
                                    </div>
                                </div>

                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Status</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'status','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                                
                            </div>
                    <?php echo $this->Form->end();?>
                     
                                <div class="table-responsive" id="checkupListingDiv">

                                </div>
                                </div>
                            </div> 
                            <div id="sick">

                                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                    <h5>Clinical Attendance</h5>
                                    <a class="toggleBtn" href="#searchClinicalAttendance" data-toggle="collapse" htmlshwattr="sick"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){?>
                                        <div style="float:right;padding-top: 3px;">
                                            <?php echo $this->Html->link('Add Clinical Attendance','#addClinicalAttendance',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse", 'htmlshwattr'=>"sick",'id'=>'sick_div')); ?>
                                        </div>
                                    <?php }?>
                                </div>
                                <div id="addClinicalAttendance" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;"<?php }?>>
                            
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('MedicalSickRecord',array('class'=>'form-horizontal','type'=>'file'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    
                                   
                                    <div class="row" style="padding: 0px 60px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner No.<?php echo $req; ?> </label>
                                                <div class="controls">
                                                   <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoner --','options'=>$prisonerListData, 'class'=>'form-control','required', 'id'=>'prisoner_id_attendance'));?>
                                                </div>
                                            </div>
                                        </div>  
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of checkup<?php echo $req; ?> </label>
                                                <div class="controls">
                                                <?php
                                                $check_up_date="";
                                                    if(isset($this->data["MedicalSickRecord"]["check_up_date"])){
                                                        $check_up_date1=$this->data["MedicalSickRecord"]["check_up_date"];
                                                        $parts = explode('-',$check_up_date1);
                                                        
                                                        $check_up_date =$parts[1].'-'.$parts[2].'-'.$parts[0];
                                                    }
                                                ?>
                                                    <?php echo $this->Form->input('check_up_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate1 span11','type'=>'text', 'placeholder'=>'Enter Date of Checkup','required','readonly'=>'readonly','value'=>$check_up_date));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        
                                        
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Attendence Description<?php echo $req; ?> </label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $attendance = "New Attendence";
                                                    if(isset($this->data['MedicalSickRecord']['attendance']))
                                                        $attendance = $this->data['MedicalSickRecord']['attendance'];
                                                    $options2= $attendanceList;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $attendance,
                                                    );
                                                    echo $this->Form->radio('attendance', $options2, $attributes2);
                                                    ?>
                                                    </div>
                                            </div>
                                        </div>
                                                                     
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Compliant<?php echo $req; ?> </label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('compliant',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'compliant', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="clearfix"></div>

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Examination<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('examination',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Examination','required'));?>
                                                    </div>
                                            </div>
                                        </div>                             
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Deferential Digonosis<?php echo $req; ?> </label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('digonosis',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'digonosis', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        

                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Lab Test<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('disease_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$diseaseList, 'empty'=>'-- Select Lab Test --','required','id'=>'disease_id'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Results<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('results',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'results', 'cols'=>30, 'rows'=>3));?>
                                                    </div>
                                            </div>
                                        </div>                             
                                        
                                        
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Digonosis (Dx)<?php echo $req; ?> </label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('digonosis_dx',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'digonosis_dx', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Treatement (Rx)<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('treatement_rx',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'treatement_rx', 'cols'=>30, 'rows'=>3));?>
                                                    </div>
                                            </div>
                                        </div>                             
                                        

                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Radiology<?php echo $req; ?> </label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('radiology',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'radiology', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Durg Description Prescribed<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('drug_description',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'drug_description', 'cols'=>30, 'rows'=>3));?>
                                                    </div>
                                            </div>
                                        </div>                             
                                        
                                        
                                        <div class="clearfix"></div>
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Attachment<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('attachment',array('type'=>'file','div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'attachment'));?>
                                                    <br /><span>(upload jpg,jpeg,png,gif,pdf type file)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure to save?')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                            </div>
                                <div class="collapse <?php if($isEdit == 0){ echo "in";}?>" id="searchClinicalAttendance" <?php if($isEdit == 1){?> style="height: 0px;"<?php }?>>
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding: 0px 60px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner No.</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'prisoner_id_serch_attendance', 'style'=>'width:200px;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date of checkup</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('sick_checkup_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'sick_checkup_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('sick_checkup_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'sick_checkup_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Attendence Description</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('attendence_search',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Attendence Description --','options'=>$attendence_description_search, 'class'=>'form-control','required', 'id'=>'attendence_search'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Lab Test</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('lab_test_search',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Lab Test --','options'=>$diseaseList, 'class'=>'form-control','required', 'id'=>'lab_test_search'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Status</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'status_sick','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchsick" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                                
                            </div>
                    <?php echo $this->Form->end();?>
                     
                                <div class="table-responsive" id="sickListingDiv">

                                </div>
                                </div>
                            </div>
                            <div id="seriouslyill">

                            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                    <h5>Recommend For Release</h5>
                                    <a class="toggleBtn" href="#searchRecommendForRelease" data-toggle="collapse" htmlshwattr="seriouslyill"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){?>
                                        <div style="float:right;padding-top: 3px;">
                                            <?php echo $this->Html->link('Add Recommend For Release','#addRecommendForRelease',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse", 'htmlshwattr'=>"seriouslyill",'id'=>"seriouslyilldiv")); ?>
                                        </div>
                                    <?php }?>
                                </div>
                                <div id="addRecommendForRelease" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;"<?php }?>>
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('MedicalSeriousIllRecord',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    
                                    
                                    <div class="row" style="padding: 0px 60px;">
                                    <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner No.<?php echo $req; ?> </label>
                                                <div class="controls">
                                                   <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoner --','options'=>$prisonerListData, 'class'=>'form-control','required', 'id'=>'prisoner_id_recommend'));?>
                                                </div>
                                            </div>
                                    </div>
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of recommendation<?php echo $req; ?> </label>
                                                <div class="controls">
                                                <?php
                                                $check_up_date="";
                                                    if(isset($this->data["MedicalSeriousIllRecord"]["check_up_date"])){
                                                        $check_up_date1=$this->data["MedicalSeriousIllRecord"]["check_up_date"];
                                                        $parts = explode('-',$check_up_date1);
                                                        
                                                        $check_up_date =$parts[1].'-'.$parts[2].'-'.$parts[0];
                                                    }
                                                ?>
                                                    <?php echo $this->Form->input('check_up_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate1 span11','type'=>'text', 'placeholder'=>'Enter Date of Checkup','required','readonly'=>'readonly','value'=>$check_up_date));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Priority<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('priority',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$priorityList, 'empty'=>'-- Select Priority --','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Medical Officer Name<?php echo $req; ?> </label>
                                                <div class="controls">
                                                   <?php echo $this->Form->input('medical_officer_id_other',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Medical Officer --','options'=>$medicalOfficerListData, 'default'=>$this->Session->read('Auth.User.id'), 'class'=>'form-control','required', 'id'=>'medical_officer_id_other'));?>
                                                </div>
                                            </div>
                                        </div>
                                                                            
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Recomendation Category<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('hospital_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$hospitalList, 'empty'=>'-- Select Recomendation Category --','required','id'=>'hospital_id'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Cause of Recommendetion </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('remark',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false,'formnovalidate'=>true, 'class'=>'btn btn-success', 'onclick'=>"javascript:return confirm('Are you sure to save?')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                </div>
                                 <div class="collapse <?php if($isEdit == 0){ echo "in";}?>" id="searchRecommendForRelease" <?php if($isEdit == 1){?> style="height: 0px;"<?php }?>>
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding: 0px 60px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner No.</label>
                                        <div class="controls">
                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'prisoner_id_searchrecommend', 'style'=>'width:200px;'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date of recommendation</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('recommendation_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'recommendation_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('recommendation_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'recommendation_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Priority</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('priority_searched',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$priorityList, 'empty'=>'-- Select Priority --','required','id'=>'priority_searched'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Medical Officer Name</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('medical_off_ser',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Medical Officer --','options'=>$medicalOfficerListData, 'default'=>$this->Session->read('Auth.User.id'), 'class'=>'form-control','required', 'id'=>'medical_off_ser'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Recomendation Category</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('hos_id_search',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$hospitalList, 'empty'=>'-- Select Recomendation Category --','required','id'=>'hos_id_search'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Status</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'statusseriousIll','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchrecommend" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                                
                            </div>
                    <?php echo $this->Form->end();?>
                     
                                <div class="table-responsive" id="seriousIllListingDiv">

                                </div>  
                                </div>                              
                            </div>
                            <div id="death">
                            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                    <h5>Death</h5>
                                    <a class="toggleBtn" href="#searchDeath" data-toggle="collapse" htmlshwattr="death"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){?>
                                        <div style="float:right;padding-top: 3px;">
                                            <?php echo $this->Html->link('Add Death','#addDeath',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse", "htmlshwattr"=>"death",'id'=>"deathdiv")); ?>
                                        </div>
                                    <?php }?>
                                </div>
                                <div id="addDeath" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;"<?php }?>>
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('MedicalDeathRecord',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    
                                                                    
                                    <div class="row" style="padding: 0px 60px;">
                                    <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner No.<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                   <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoners --','options'=>$prisonerListData, 'class'=>'form-control','required', 'id'=>'prisoner_id_death'));?>
                                                </div>
                                            </div>
                                    </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Cause Of Death<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('death_cause',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'cause', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of Death<?php echo $req; ?> :</label>
                                                <div class="controls">

                                                <?php
                                                $check_up_date="";
                                                    if(isset($this->data["MedicalDeathRecord"]["check_up_date"])){
                                                        $check_up_date1=$this->data["MedicalDeathRecord"]["check_up_date"];
                                                        $parts = explode('-',$check_up_date1);
                                                        
                                                        $check_up_date =$parts[1].'-'.$parts[2].'-'.$parts[0];
                                                    }
                                                ?>
                                                    <?php echo $this->Form->input('check_up_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate1 span11','type'=>'text', 'placeholder'=>'Enter Date of Death','required','readonly'=>'readonly','value'=>$check_up_date));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Time of Death<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('time_of_death',array('div'=>false,'label'=>false,'class'=>'form-control timepicker1 span11','type'=>'text', 'placeholder'=>'Enter Time of Death','required','readonly'=>'readonly'));?>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="clearfix"></div>  
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Place of Death:<?php echo $req; ?> :</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $death_place = "In";
                                                    if(isset($this->data['MedicalDeathRecord']['death_place']))
                                                        $death_place = $this->data['MedicalDeathRecord']['death_place'];
                                                    if($death_place=="In"){$display_style="style='display:none'";}
                                                    if($death_place=="Out"){$display_style="style='display:block'";}
                                                    $options2= $death_placeList;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $death_place,
                                                        'onChange'=>'showPlace(this.value)',
                                                    );
                                                    echo $this->Form->radio('death_place', $options2, $attributes2);
                                                    ?>
                                                    <?php echo $this->Form->input('place_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Place Name','id'=>'place_name',$display_style));?>
                                                </div>
                                            </div>
                                        </div>                         
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Medical Officer<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('medical_officer_id_death',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisnors --','options'=>$medicalOfficerListData, 'default'=>$this->Session->read('Auth.User.id'), 'class'=>'form-control','required', 'id'=>'medical_officer_id_death'));?>
                                                </div>
                                            </div>
                                        </div>
                                         
                                        <div class="clearfix"></div>                           
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Upload Medical Form<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('pathologist_attach',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'));?>
                                                    <br /><span>(upload jpg,jpeg,png,gif,pdf type file)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Upload Postmotorm Report<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'=>false));?>
                                                    <br /><span>(upload jpg,jpeg,png,gif,pdf type file)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false,'class'=>'btn btn-success', 'onclick'=>"javascript:return confirm('Are you sure to save?')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                </div>
                                <div class="collapse <?php if($isEdit == 0){ echo "in";}?>" id="searchDeath" <?php if($isEdit == 1){?> style="height: 0px;"<?php }?>>
                                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                            <div class="row" style="padding: 0px 60px;">
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Prisoner No.</label>
                                                        <div class="controls">
                                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'prisoner_id_searchdeath', 'style'=>'width:200px;'));?>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Date of Death</label>
                                                        <div class="controls">
                                                            
                                                            <?php echo $this->Form->input('death_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'death_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                                            To
                                                            <?php echo $this->Form->input('death_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'death_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Medical Officer Name</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('medi_off_death',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Medical Officer --','options'=>$medicalOfficerListData, 'default'=>$this->Session->read('Auth.User.id'), 'class'=>'form-control','required', 'id'=>'medi_off_death'));?>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Status</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'statusdeath','default'=>$default_status));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div> 
                                            </div>
                                            <div class="form-actions" align="center">
                                            <button id="btnsearchdeath" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                                                
                                            </div>
                                    <?php echo $this->Form->end();?>
                                     
                                <div class="table-responsive" id="deathListingDiv"></div>
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
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$medicalChekupUrl         = $this->Html->url(array('controller'=>'medicalRecords','action'=>'medicalCheckupData'));
$deleteMedicalChekupUrl   = $this->Html->url(array('controller'=>'medicalRecords','action'=>'deleteMedicalCheckupRecords'));
$medicalSickUrl         = $this->Html->url(array('controller'=>'medicalRecords','action'=>'medicalSickData'));
$deleteMedicalSickUrl   = $this->Html->url(array('controller'=>'medicalRecords','action'=>'deleteMedicalSickRecords'));
$medicalSeriousIllUrl   = $this->Html->url(array('controller'=>'medicalRecords','action'=>'showMedicalSeriousIllRecords'));
$deleteMedicalSeriUrl   = $this->Html->url(array('controller'=>'medicalRecords','action'=>'deleteMedicalSeriousillRecords'));
$medicalDeathUrl        = $this->Html->url(array('controller'=>'medicalRecords','action'=>'showMedicalDeathRecords'));
$deleteMedicalDeathUrl  = $this->Html->url(array('controller'=>'medicalRecords','action'=>'deleteMedicalDeathRecords'));
echo $this->Html->scriptBlock("
    var tab_param = '';
    var tabs;
    jQuery(function($) {
        $('.toggleBtn').click(function(){
            var htmlshwattr=$(this).attr('htmlshwattr');
            $(this).closest('#'+htmlshwattr).find('.collapse.in').css('height','0');
            $(this).closest('#'+htmlshwattr).find('.collapse.in').removeClass('in');
         });
        if($('#MedicalCheckupRecordId').val()==''){
           // $('#prisoner_id_serch').select2('val','');
            $('#prisoner_id').select2('val', '');
            $('#check_up').select2('val','');
            $('#prisoner_name').val('');
            $('#gender').val('');
            $('#age').val(''); 
            $('#height').val('');
        } 
        if($('#MedicalSickRecordId').val()==''){
            $('#prisoner_id_attendance').select2('val', '');
            $('#attendence_search').select2('val', '');
            $('#lab_test_search').select2('val', '');
            //$('#prisoner_id_serch_attendance').select2('val','');
            $('#disease_id').select2('val','');
        }   
        if($('#MedicalSeriousIllRecordId').val()==''){
            $('#prisoner_id_recommend').select2('val', '');
            $('#prisoner_id_searchrecommend').select2('val','');
            //$('#MedicalSeriousIllRecordDiseaseId').select2('val','');
            $('#recomendation_category').select2('val','');
            $('#MedicalSeriousIllRecordPriority').select2('val','');
            $('#hospital_id').select2('val','');
            $('#priority_searched').select2('val','');
            $('#hos_id_search').select2('val','');
        }
        if($('#MedicalDeathRecordId').val()==''){
            $('#prisoner_id_death').select2('val', '');
            $('#prisoner_id_searchdeath').select2('val','');
            $('#medical_officer_id_death').select2('val','".$this->Session->read('Auth.User.id')."');
            $('#prisoner_id_searchdeath').select2('val','');
            //$('#medi_off_death').select2('val','');
            
        }

        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });
        
        
        $('#medicalChekupDiv').on('click', function(e){
           showMedicalChekupRecords();
        }); 
        $('#medicalSickDiv').on('click', function(e){
           showMedicalSickRecords();
        });
        $('#medicalSeriousIllDiv').on('click', function(e){
            showMedicalSeriousIllRecords();
        });
        $('#medicalDeathDiv').on('click', function(e){
            showMedicalDeathRecords();
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
        if(tab_param == 'health_checkup'){
            showMedicalChekupRecords();
        }
        else if(tab_param == 'sick'){
            showMedicalSickRecords();
        }else if(tab_param == 'seriouslyill'){
            showMedicalSeriousIllRecords();
        }else if(tab_param == 'death'){
            showMedicalDeathRecords();
        }
        else{
            showMedicalChekupRecords();
        }
          
    });
    function showMedicalChekupRecords()
    {
       
        var url         = '".$medicalChekupUrl."';
        if($('#prisoner_id_serch').val() != ''){
            var prisoner_id = $('#prisoner_id_serch').val().replace('/', '-');
            prisoner_id = prisoner_id.replace('/', '-');
            url = url + '/prisoner_id:'+prisoner_id;
        }
        //url = url + '/prisoner_id:'+$('#prisoner_id_serch').val();
        url = url + '/age_from:'+$('#age_from').val();
        url = url + '/age_to:'+$('#age_to').val();
        url = url + '/folow_from:'+$('#folow_from').val();
        url = url + '/folow_to:'+$('#folow_to').val();
        url = url + '/hgt_ft:'+$('#hgt_ft').val();
        url = url + '/hgt_inch:'+$('#hgt_inch').val();
        url = url + '/weight_search:'+$('#weight_search').val();
        url = url + '/status:'+$('#status').val();
        url = url + '/uuid:".$uuid."';
        
        $.post(url, {}, function(res) {
            if (res) {
                $('#checkupListingDiv').html(res);
                var usertype_id='".$this->Session->read('Auth.User.usertype_id')."';
                var user_typercpt='".Configure::read('MEDICALOFFICE_USERTYPE')."';
                var user_typepoi='".Configure::read('PRINCIPALOFFICER_USERTYPE')."';
                var user_typeoiu='".Configure::read('OFFICERINCHARGE_USERTYPE')."';
             
                 if(usertype_id==user_typercpt)
                 {
                    if($('#status').val()=='Saved' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typepoi)
                 {
                    if($('#status').val()=='Reviewed' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typeoiu)
                 {
                    if($('#status').val()=='Approved'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
            }
        }); 
    }
    function deleteMedicalChekupRecord(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteMedicalChekupUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    if(res == 'SUCC'){
                        showMedicalChekupRecords();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    function showMedicalSickRecords(){
        var prisoner_id = '';
        var uuid        = '';
        var url         = '".$medicalSickUrl."';
        if($('#prisoner_id_serch_attendance').val() != ''){
            var prisoner_id = $('#prisoner_id_serch_attendance').val().replace('/', '-');
            prisoner_id = prisoner_id.replace('/', '-');
            url = url + '/prisoner_id:'+prisoner_id;
        }
       // url = url + '/prisoner_id:'+$('#prisoner_id_serch').val();

        url = url + '/sick_checkup_from:'+$('#sick_checkup_from').val();
        url = url + '/sick_checkup_to:'+$('#sick_checkup_to').val();
       url = url + '/attendence_search:'+$('#attendence_search').val();
        url = url + '/lab_test_search:'+$('#lab_test_search').val();

        url = url + '/status:'+$('#status_sick').val();
        url = url + '/uuid:".$uuid."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#sickListingDiv').html(res);
                var usertype_id='".$this->Session->read('Auth.User.usertype_id')."';
                var user_typercpt='".Configure::read('MEDICALOFFICE_USERTYPE')."';
                var user_typepoi='".Configure::read('PRINCIPALOFFICER_USERTYPE')."';
                var user_typeoiu='".Configure::read('OFFICERINCHARGE_USERTYPE')."';
                
                 if(usertype_id==user_typercpt)
                 {
                    if($('#status_sick').val()=='Saved' || $('#status_sick').val()=='Approved' || $('#status_sick').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typepoi)
                 {
                    if($('#status_sick').val()=='Reviewed' || $('#status_sick').val()=='Approved' || $('#status_sick').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typeoiu)
                 {
                    if($('#status_sick').val()=='Approved'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
            }
        }); 
    }
    function deleteMedicalSickRecords(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteMedicalSickUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    if(res == 'SUCC'){
                        showMedicalSickRecords();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    function showMedicalSeriousIllRecords(){
        var prisoner_id = '';
        var uuid        = '';
        var url         = '".$medicalSeriousIllUrl."';
        if($('#prisoner_id_searchrecommend').val() != ''){
            var prisoner_id = $('#prisoner_id_searchrecommend').val().replace('/', '-');
            prisoner_id = prisoner_id.replace('/', '-');
            url = url + '/prisoner_id:'+prisoner_id;
        }
       // url = url + '/prisoner_id:'+$('#prisoner_id_serch').val();

            url = url + '/recommendation_from:'+$('#recommendation_from').val();
            url = url + '/recommendation_to:'+$('#recommendation_to').val();
            url = url + '/priority_searched:'+$('#priority_searched').val();
            url = url + '/medical_off_ser:'+$('#medical_off_ser').val();
            url = url + '/hos_id_search:'+$('#hos_id_search').val();
            url = url + '/status:'+$('#statusseriousIll').val();
            url = url + '/uuid:".$uuid."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#seriousIllListingDiv').html(res);
                var usertype_id='".$this->Session->read('Auth.User.usertype_id')."';
                var user_typercpt='".Configure::read('MEDICALOFFICE_USERTYPE')."';
                var user_typepoi='".Configure::read('PRINCIPALOFFICER_USERTYPE')."';
                var user_typeoiu='".Configure::read('OFFICERINCHARGE_USERTYPE')."';
             
                 if(usertype_id==user_typercpt)
                 {
                    if($('#statusseriousIll').val()=='Saved' || $('#statusseriousIll').val()=='Approved' || $('#statusseriousIll').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typepoi)
                 {
                    if($('#statusseriousIll').val()=='Reviewed' || $('#statusseriousIll').val()=='Approved' || $('#statusseriousIll').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typeoiu)
                 {
                    if($('#statusseriousIll').val()=='Approved'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
            }
        });         
    }
    function deleteMedicalSeriousillRecords(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteMedicalSeriUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    if(res == 'SUCC'){
                        showMedicalSeriousIllRecords();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    function showMedicalDeathRecords(){
        var prisoner_id = '';
        var uuid        = '';
        var url         = '".$medicalDeathUrl."';
        if($('#prisoner_id_searchdeath').val() != ''){
            var prisoner_id = $('#prisoner_id_searchdeath').val().replace('/', '-');
            prisoner_id = prisoner_id.replace('/', '-');
            url = url + '/prisoner_id:'+prisoner_id;
        }
        url = url + '/death_from:'+$('#death_from').val();
        url = url + '/death_to:'+$('#death_to').val();
        url = url + '/medi_off_death:'+$('#medi_off_death').val();
        //url = url + '/prisoner_id:'+$('#prisoner_id_searchdeath').val();
        url = url + '/status:'+$('#statusdeath').val();
        url = url + '/uuid:".$uuid."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#deathListingDiv').html(res);
                var usertype_id='".$this->Session->read('Auth.User.usertype_id')."';
                var user_typercpt='".Configure::read('MEDICALOFFICE_USERTYPE')."';
                var user_typepoi='".Configure::read('PRINCIPALOFFICER_USERTYPE')."';
                var user_typeoiu='".Configure::read('OFFICERINCHARGE_USERTYPE')."';
             
                 if(usertype_id==user_typercpt)
                 {
                    if($('#statusdeath').val()=='Saved' || $('#statusdeath').val()=='Approved' || $('#statusdeath').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typepoi)
                 {
                    if($('#statusdeath').val()=='Reviewed' || $('#statusdeath').val()=='Approved' || $('#statusdeath').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typeoiu)
                 {
                    if($('#statusdeath').val()=='Approved'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
            }
        });        
    }
    function deleteMedicalDeathRecords(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteMedicalDeathUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    if(res == 'SUCC'){
                        showMedicalDeathRecords();
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

$(function(){

     $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\,\.\s]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");
     
        $("#MedicalSeriousIllRecordAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[MedicalSeriousIllRecord][prisoner_id]': {
                    required: true,
                },
                'data[MedicalSeriousIllRecord][check_up_date]': {
                    required: true,
                },
                'data[MedicalSeriousIllRecord][priority]': {
                    required: true,
                },
                'data[MedicalSeriousIllRecord][medical_officer_id_other]': {
                    required: true,
                },
                'data[MedicalSeriousIllRecord][hospital_id]': {
                    required: true,
                },
                'data[MedicalSeriousIllRecord][remark]': {
                    required: true,
                },
                
            },
            messages: {
                'data[MedicalSeriousIllRecord][prisoner_id]': {
                    required: "Please select prisoner no.",
                },
                'data[MedicalSeriousIllRecord][check_up_date]': {
                    required: "Please select check up date.",
                },
                'data[MedicalSeriousIllRecord][priority]': {
                    required: "Please select priority.",
                },
                'data[MedicalSeriousIllRecord][medical_officer_id_other]': {
                    required: "Please select medical officer.",
                },
                'data[MedicalSeriousIllRecord][hospital_id]': {
                    required: "Please select hospital.",
                },
                'data[MedicalSeriousIllRecord][remark]': {
                    required: "Please enter cause of recommendetion .",
                },
            }, 
    });
  });
 $(document).on('click',"#btnsearchcash", function () { // button name
        showMedicalChekupRecords();
 });
 
 $(document).on('click',"#btnsearchsick", function () { // button name
        showMedicalSickRecords();
 });
 $(document).on('click',"#btnsearchrecommend", function () { // button name
        showMedicalSeriousIllRecords();
 });
$(document).on('click',"#btnsearchdeath", function () { // button name
        showMedicalDeathRecords();
});
function showregenment(ishiv)
{
    if(ishiv == "Positve")
    {
        $('#regenment').show();
        $('#regenment').attr('required','required');
    }
    else 
    {
        $('#regenment').hide();
        $('#regenment').val('');
        $('#regenment').removeAttr('required');
    }
}
function showPlace(isplace)
{
    if(isplace == "Out")
    {
        $('#place_name').show();
    }
    else 
    {
        $('#place_name').hide();
        $('#place_name').val('');
    }
}

//check if is dual citizen is clicked
$(document).ready(function () {
    if ($('#MedicalCheckupRecordHivPositve:checked').val() == "Positve")
    {
        $('#regenment').show();
        $('#regenment').attr('required','required');
    }
    else 
    {
        $('#regenment').hide();
        $('#regenment').removeAttr('required');
    }
    if ($('#MedicalDeathRecordDeathPlaceOut:checked').val() == "Positve")
    {
        $('#place_name').show();
    }
    else 
    {
        $('#place_name').hide();
    }
});
$(document).on('change', '#prisoner_id', function(){
  var prisoner_id=$(this).val();
  $.ajax(
  {
      type: "POST",
      dataType: "json",
      url: "<?php echo $this->Html->url(array('controller'=>'MedicalRecords','action'=>'getPrisnerInfo'));?>",
      data: {
          prisoner_id: prisoner_id,
      },
      cache: true,
      beforeSend: function()
      {  
        //$('tbody').html('');
      },
      success: function (data) {
        $('#prisoner_name').val(data.prisoner_name);
        $('#gender').val(data.gender);
        $('#age').val(data.age);
        //var height=data.height_feet+" foot "+data.height_inch+" inch";
        
        $("#height_feet").select2('val',data.height_feet);
        $("#height_inch").select2('val',data.height_inch);
      },
      error: function (errormessage) {
        alert(errormessage.responseText);
      }
  });
});
$(document).on('click', '#initial_exit_checkup', function(){
    $("#MedicalCheckupRecordId").val('');
    $('#check_up').select2('val','');
    $('#prisoner_id').select2('val','');
    $('#prisoner_name').val('');
    $('#gender').val('');
    $('#age').val('');
    $('#height').val('');
    $('#weight').val('');
    $('#bmi').val('');
    $('#other_disease').val('');
    $('#follow_up').val('<?php echo date('m-d-Y')?>');
    $("#MedicalCheckupRecordHivNagetive").attr('checked', 'checked');
    $("#regenment").hide();
    $("#regenment").val('');
    $("#MedicalCheckupRecordTbNagetive").attr('checked', 'checked');
    $("#MedicalCheckupRecordMentalCaseNo").attr('checked', 'checked');
    
});
$(document).on('click', '#sick_div', function(){
    $("#MedicalSickRecordId").val('');
    $('#prisoner_id_attendance').select2('val','');
    $('#MedicalSickRecordCheckUpDate').val('');
    $('#compliant').val('');
    $('#MedicalSickRecordExamination').val('');
    $('#digonosis').val('');
    $('#disease_id').select2('val','');
    $('#results').val('');
    $('#digonosis_dx').val('');
    $('#treatement_rx').val('');
    $('#radiology').val('');
    $("#MedicalSickRecordAttendanceNewAttendence").attr('checked', 'checked');
    $("#drug_description").val('');
});
$(document).on('click', '#seriouslyilldiv', function(){
    $("#MedicalSeriousIllRecordId").val('');
    $('#prisoner_id_recommend').select2('val','');
    $('#MedicalSeriousIllRecordCheckUpDate').val('');
    $('#MedicalSeriousIllRecordPriority').select2('val','');
    $('#medical_officer_id_other').select2('val','<?php echo $this->Session->read('Auth.User.id')?>');
    $('#hospital_id').select2('val','');
    $('#MedicalSeriousIllRecordRemark').val('');
    
});
$(document).on('click', '#deathdiv', function(){
    $("#MedicalDeathRecordId").val('');
    $('#prisoner_id_death').select2('val','');
    $('#cause').val('');
    $('#MedicalDeathRecordCheckUpDate').val('');
    $('#MedicalDeathRecordTimeOfDeath').val('');
    $("#MedicalDeathRecordDeathPlaceIn").attr('checked', 'checked');
    $("#place_name").hide();
    $("#place_name").val('');
    $('#medical_officer_id_death').select2('val','<?php echo $this->Session->read('Auth.User.id')?>');
    
});

$(document).on('change', '#check_up', function(){
  var check_up=$(this).val();
  $.ajax(
  {
      type: "POST",
      url: "<?php echo $this->Html->url(array('controller'=>'MedicalRecords','action'=>'getCheckupPrisnerInfo'));?>",
      data: {
          check_up: check_up,
      },
      cache: true,
      beforeSend: function()
      {  
        //$('tbody').html('');
      },
      success: function (data) {
        $('#priscontent').html(data);
        $('#prisoner_id').select2();
        $('#prisoner_id').select2('val','');

        $('#prisoner_name').val('');
        $('#gender').val('');
        $('#age').val('');
        
        $("#height_feet").select2('val','');
        $("#height_inch").select2('val','');
      },
      error: function (errormessage) {
        alert(errormessage.responseText);
      }
  });
});

$(function(){
        $("#MedicalCheckupRecordAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[MedicalCheckupRecord][check_up]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][prisoner_id]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][prisoner_name]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][gender]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][age]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][height_feet]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][height_inch]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][tb]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][hiv]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][mental_case]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][weight]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][bmi]': {
                    required: true,
                },
                'data[MedicalCheckupRecord][follow_up]': {
                    required: true,
                    datevalidateformatnew: true,
                },
                
            },
            messages: {
                'data[MedicalCheckupRecord][check_up]': {
                    required: "Please select check up type.",
                },
                'data[MedicalCheckupRecord][prisoner_id]': {
                    required: "Please select prisoner no.",
                },
                'data[MedicalCheckupRecord][prisoner_name]': {
                    required: "Please enter prisoner name.",
                },
                'data[MedicalCheckupRecord][gender]': {
                    required: "Please enter gender.",
                },
                'data[MedicalCheckupRecord][age]': {
                    required: "Please enter age.",
                },
                'data[MedicalCheckupRecord][height_feet]': {
                    required: "Please enter height in feet.",
                },
                'data[MedicalCheckupRecord][height_inch]': {
                    required: "Please enter height in inch.",
                },
                'data[MedicalCheckupRecord][tb]': {
                    required: "Please select T.B Test.",
                },
                'data[MedicalCheckupRecord][hiv]': {
                    required: "Please select HIV Test.",
                },
                'data[MedicalCheckupRecord][mental_case]': {
                    required: "Please select Mental Case.",
                },
                'data[MedicalCheckupRecord][weight]': {
                    required: "Please enter weight.",
                },
                'data[MedicalCheckupRecord][bmi]': {
                    required: "Please enter BMI.",
                },
                'data[MedicalCheckupRecord][follow_up]': {
                    required: "Please select folow up date.",
                    datevalidateformatnew: "Wrong Date Format"
                },
                
            }, 
    });

        $("#MedicalSickRecordAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[MedicalSickRecord][prisoner_id]': {
                    required: true,
                },
                'data[MedicalSickRecord][check_up_date]': {
                    required: true,
                },
                'data[MedicalSickRecord][compliant]': {
                    required: true,
                },
                'data[MedicalSickRecord][examination]': {
                    required: true,
                },
                'data[MedicalSickRecord][digonosis]': {
                    required: true,
                },
                'data[MedicalSickRecord][disease_id]': {
                    required: true,
                },
                'data[MedicalSickRecord][results]': {
                    required: true,
                },
                'data[MedicalSickRecord][digonosis_dx]': {
                    required: true,
                },
                'data[MedicalSickRecord][treatement_rx]': {
                    required: true,
                },
                'data[MedicalSickRecord][radiology]': {
                    required: true,
                },
                'data[MedicalSickRecord][drug_description]': {
                    required: true,
                },
                'data[MedicalSickRecord][attachment]': {
                    required: true,
                },

            },
            messages: {
                'data[MedicalSickRecord][prisoner_id]': {
                    required: "Please select prisoner no.",
                },
                'data[MedicalSickRecord][check_up_date]': {
                    required: "Please select check up date.",
                },
                'data[MedicalSickRecord][compliant]': {
                    required: "Please enter compliant.",
                },
                'data[MedicalSickRecord][examination]': {
                    required: "Please enter examination.",
                   
                },
                'data[MedicalSickRecord][digonosis]': {
                    required: "Please enter digonosis.",
                   
                },
                'data[MedicalSickRecord][disease_id]': {
                    required: "Please select lab test.",
                   
                },
                'data[MedicalSickRecord][results]': {
                    required: "Please enter results.",
                   
                },
                'data[MedicalSickRecord][digonosis_dx]': {
                    required: "Please enter digonosis(Dx).",
                   
                },
                'data[MedicalSickRecord][treatement_rx]': {
                    required: "Please enter treatement (Rx).",
                   
                },
                'data[MedicalSickRecord][radiology]': {
                    required: "Please enter radiology.",
                   
                },
                'data[MedicalSickRecord][drug_description]': {
                    required: "Please enter durg description prescribed.",
                   
                },
                'data[MedicalSickRecord][attachment]': {
                    required: "Please choose attachment.",
                   
                },
                
                
            }, 
    });
    $("#MedicalDeathRecordAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[MedicalDeathRecord][prisoner_id]': {
                    required: true,
                },
                'data[MedicalDeathRecord][death_cause]': {
                    required: true,
                },
                'data[MedicalDeathRecord][check_up_date]': {
                    required: true,
                },
                'data[MedicalDeathRecord][time_of_death]': {
                    required: true,
                },
                'data[MedicalDeathRecord][medical_officer_id]': {
                    required: true,
                },
                'data[MedicalDeathRecord][pathologist_attach]': {
                    required: true,
                },

                'data[MedicalDeathRecord][attachment]': {
                    required: true,
                },

            },
            messages: {
                'data[MedicalDeathRecord][prisoner_id]': {
                    required: "Please select prisoner no.",
                },
                'data[MedicalDeathRecord][check_up_date]': {
                    required: "Please select check up date.",
                },

                'data[MedicalDeathRecord][death_cause]': {
                    required: "Please enter cause of death.",
                },
                'data[MedicalDeathRecord][time_of_death]': {
                    required: "Please enter time of death.",
                   
                },
                'data[MedicalDeathRecord][pathologist_attach]': {
                    required: "Please enter choose medical form.",
                   
                },
                
                'data[MedicalDeathRecord][attachment]': {
                    required: "Please choose postmotorm report.",
                },
                
                
            }, 
    });    
  });
</script>
