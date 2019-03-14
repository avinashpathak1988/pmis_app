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
.radio input[type="radio"], .checkbox input[type="checkbox"]{
    margin-left: 0px
}
.checkbox{
    display: inline-block;
}
.checkbox label{
    display: inline-block;
    position: relative;
    top: 7px;
}
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
select{width: 92%;}
<?php 
if($login_user_type_id == Configure::read('RECEPTIONIST_USERTYPE') && !empty($prisoner_uuid))
{?>
    #searchInitialExitCheckup, #searchClinicalAttendance,
    #searchRecommendForRelease, #searchDeath,
    #searchRecommendForReleaseRecom,#searchRecommendForReleaseRecomIcon,
    #searchInitialExitCheckupIcon, #searchClinicalAttendanceIcon,
    #searchRecommendForReleaseIcon, #searchDeathIcon{
        display:none;
    }
<?php }?>
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
                            <li><a href="#sick" id="medicalSickDiv">Clinical Attendance</a></li>
                            <li><a href="#seriouslyill" id="medicalSeriousIllDiv">Recommendation on Referral</a></li>
                            <li><a href="#death" id="medicalDeathDiv">Death</a></li>
                            <li><a href="#release_recom" id="medicalReleaseDiv">Recommendation on Release</a></li>
                            <!-- <li class="pull-right controls"> -->
                        <!--</ul>
                        <ul class="nav nav-tabs">-->
                            <li class="controls pull-right" style="margin-top: -40px;">
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
                                    <a class="toggleBtn" href="#searchInitialExitCheckup" data-toggle="collapse" htmlshwattr="health_checkup" title="Search" id="searchInitialExitCheckupIcon"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
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
                                
                                    <?php //debug($this->data); ?>
                                    <div class="row" style="padding: 0px 0px 0px 60px;">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Check up :<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                   <?php 
                                                   if(isset($this->data['MedicalCheckupRecord']['id']) && $this->data['MedicalCheckupRecord']['id']!=''){
                                                        echo $this->data['MedicalCheckupRecord']['check_up'];
                                                   }else{
                                                        echo $this->Form->input('check_up',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Check up --','options'=>$checkupData, 'class'=>'form-control','required', 'id'=>'check_up'));
                                                   }
                                                   ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner No. :<?php echo $req; ?> :</label>
                                                <div class="controls" id="priscontent">
                                                   <?php 
                                                   if(isset($this->data['MedicalCheckupRecord']['id']) && $this->data['MedicalCheckupRecord']['id']!=''){
                                                        echo $funcall->getName($this->data['MedicalCheckupRecord']['prisoner_id'],"Prisoner","prisoner_no");
                                                   }else{
                                                        echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoner --','options'=>$prisonerListData1, 'class'=>'form-control','required', 'id'=>'prisoner_id'));
                                                    }
                                                    ?>   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                                                   
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner Name :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Prisoner Name','id'=>'prisoner_name','readonly'=>'readonly'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Gender :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('gender',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Gender','id'=>'gender','readonly'=>'readonly'));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div> 
                                         
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Age :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('age',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Age','id'=>'age','readonly'=>'readonly'));?>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Height :</label>
                                                <div class="controls">
                                                    <?php //echo $this->Form->input('height',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Height','id'=>'height','readonly'=>'readonly'));?>
                                                    <div class="span12">
                                                        <?php echo $this->Form->input('height_feet',array('div'=>false,'label'=>false,'class'=>'form-control span11', 'empty'=>'-- Height in cm --','type'=>'select','options'=>$heightInFeetList,"onChange"=>"checkBmiVal()"));?>
                                                    </div>
                                                    <!-- <div class="span6">
                                                        <?php //echo $this->Form->input('height_inch',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$heightInInchList, 'empty'=>array('0'=>'-- Height in Inch --'),'required'=>false,'id'=>'height_inch'));?>
                                                    </div> -->
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                        
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Weight   (in Kg) :<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('weight',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Weight','minlength'=>'2', 'maxlength'=>'3','onblur'=>"checkBmiVal()"));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">BMI :<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('bmi',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'BMI','readonly'=>true));?>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">BMI Classification :<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('grade',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'BMI Classification','readonly'=>true));?>
                                                    <?php
                                                    // $grade = array('A'=>'A','B'=>'B','C'=>'C');
                                                    //  echo $this->Form->input('grade',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','id'=>'grade','empty'=>'--Select Grade--','options'=>$grade,'required'));?>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Blood Groups:<?php //echo $req;?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('blood_group',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$bloodGroupList, 'empty'=>'-- Select Blood Groups --','required'=>false,'id'=>'blood_group'));?>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">BMI Treatment :<?php //echo $req;?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('bmi_treatment_initial',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$bmiTreatmentList, 'empty'=>'-- Select BMI Treatment --','required'=>false,'id'=>'bmi_treatment_initial'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">T.B Test :</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $tb = "Nagetive";
                                                    if(isset($this->data['MedicalCheckupRecord']['tb']))
                                                        $tb = $this->data['MedicalCheckupRecord']['tb'];

                                                    if($tb=="Nagetive"){$display_style="style='display:none'";}
                                                    if($tb=="Positve"){$display_style="style='display:block'";}
                                                    $options2= $tbList;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $tb,
                                                        'onChange'=>'showregimen(this.value)',
                                                    );
                                                    echo $this->Form->radio('tb', $options2, $attributes2);
                                                    ?>
                                                    <?php echo $this->Form->input('regimen',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'regimen',$display_style,'title'=>'Plese enter regimen'));?>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <!-- partha code starts  -->
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">HIV Testing:</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $hiv_testing = "No";
                                                    if(isset($this->data['MedicalCheckupRecord']['hiv_testing']))
                                                        $hiv_testing = $this->data['MedicalCheckupRecord']['hiv_testing'];
                                                    if($hiv_testing=="No"){$display_style="style='display:none'";}
                                                    if($hiv_testing=="Yes"){$display_style="style='display:block'";}
                                                    $options2= $hivtesting;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $hiv_testing,
                                                        'onChange'=>'showHivtesting(this.value)',
                                                    );
                                                    echo $this->Form->radio('hiv_testing', $options2, $attributes2);
                                                    ?>
                                                    <?php echo $this->Form->input('hiv_testing',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'regiment','id'=>'hiv_testing',$display_style,'title'=>'Plese enter regiment'));?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <!-- partha code end -->
                                        <div class="span6" id="hiv_test" style="display: none;">
                                            <div class="control-group">
                                                <label class="control-label">HIV Result :</label>
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
                                                    <?php echo $this->Form->input('regenment',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'regiment','id'=>'regenment',$display_style,'title'=>'Plese enter regiment'));?>
                                                </div>
                                            </div>
                                        </div>
                                         
                                        <div class="clearfix"></div>
                                        

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Mental Illness :</label>
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
                                                <label class="control-label">Other Diseases :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('other_disease',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Other Diseases','id'=>'other_disease'));?>
                                                   
                                                </div>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>

                                        
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Follow up date :<?php echo $req; ?></label>
                                                <div class="controls">
                                                <?php
                                                $follow_up=date('m-d-Y');
                                                    if(isset($this->data["MedicalCheckupRecord"]["follow_up"])){
                                                        $follow_up=$this->data["MedicalCheckupRecord"]["follow_up"];
                                                        $parts = explode('-',$follow_up);
                                                        
                                                        $follow_up =$parts[1].'-'.$parts[2].'-'.$parts[0];
                                                    }
                                                ?>
                                                    <?php echo $this->Form->input('follow_up',array('div'=>false,'label'=>false,'class'=>'form-control minCurrentDate','type'=>'text','placeholder'=>'Follow Up Date','id'=>'follow_up','value'=>$follow_up));?>
                                                </div>
                                            </div>
                                            </div>
                                            <!-- <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Grade :<?php //echo $req; ?></label>
                                                    <div class="controls">
                                                        <?php
                                                        // $grade = array('A'=>'A','B'=>'B','C'=>'C');
                                                         // echo $this->Form->input('grade',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','id'=>'grade','empty'=>'--Select Grade--','options'=>$grade,'required'));?>
                                                    </div>
                                                </div>
                                             </div> -->
                                        <div class="clearfix"></div>
                                    </div>

                                    <?php if(isset($this->data['MedicalCheckupRecord']['id'])) { ?>
                                     <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Update', array('type'=>'submit', 'div'=>false,'label'=>false, 'onclick'=>"checkUpdate()", 'class'=>'btn btn-success', 'formnovalidate'=>true,'id'=>'update_initial_exit'))?>
                                        
                                        <?php echo $this->Form->button('Cancel', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true,'id'=>'save_initial_exit'))?>
                                      
                                    </div>

                                    <?php } else{ ?>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'save_initial_exit'))?>
                                    </div>
                                    <?php } ?>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                </div>
                                <div class="collapse <?php if($isEdit == 0){ echo "in";}?>" id="searchInitialExitCheckup"  <?php if($isEdit == 1){?> style="height: 0px;"<?php }?>>
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','id'=>'searchFordata'));?>
                            <div class="row" style="padding: 0px 0px 0px 60px;">
                                <?php
                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
                                ?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prison Name. :</label>
                                        <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $prisonListData, 'empty'=>'-- Select Prison --','id'=>'prison_id_serch'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner No. :</label>
                                        <div class="controls">
                                            <?php //echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select prisoner --','options'=>$prisonerListData, 'class'=>'form-control','required', 'id'=>'prisoner_id_serch'));?>
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'prisoner_id_serch', 'style'=>'','maxlength'=>'500'));?>

                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Follow Up Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('folow_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'folow_from',"readonly"=>true, 'required'=>false,  'onblur'=>'checkDate()','style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('folow_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'folow_to',"readonly"=>true,'required'=>false, 'onblur'=>'checkDate()','style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Age between :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('age_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide Age','id'=>'age_from', 'required'=>false,'onblur'=>'validateAge2()','style'=>'width:43%;'));?>
                                            &
                                            <?php echo $this->Form->input('age_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide Age','id'=>'age_to', 'required'=>false,'onblur'=>'validateAge2()','style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Height :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('hgt_ft',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide height in feet','id'=>'hgt_ft', 'required'=>false,'style'=>''));?>
                                            
                                            <?php echo $this->Form->input('hgt_inch',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide height in inch','id'=>'hgt_inch', 'required'=>false,'style'=>'width:130px;', 'style'=>'display:none'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Weight :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('weight_search',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide weight in KG','id'=>'weight_search', 'required'=>false,'style'=>''));?>
                                        </div>
                                    </div>
                                </div>


                                <div class="span6" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Status :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'status','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                                  <div class="clearfix"></div>
                              
                                  <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Blood Groups :</label>
                                        <div class="controls">
                                           <?php echo $this->Form->input('blood_group_search',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$bloodGroupList, 'empty'=>'-- Select Blood Groups --','required'=>false,'id'=>'blood_group_search'));?>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                             <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('searchFordata')"))?>   
                            </div>
                    <?php echo $this->Form->end();?>
                     
                                <div class="table-responsive" id="checkupListingDiv">

                                </div>
                                </div>
                            </div> 
                            <div id="sick">

                                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                    <h5>Clinical Attendance</h5>
                                    <a class="toggleBtn" href="#searchClinicalAttendance" data-toggle="collapse" htmlshwattr="sick" title="Search" id="searchClinicalAttendanceIcon"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
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

                                   <!--  style="padding: 0px 0px 0px 60px;" -->
                                    <div class="row">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner No. :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                   <?php 
                                                    if(isset($this->data['MedicalSickRecord']['id']) && $this->data['MedicalSickRecord']['id']!=''){
                                                        echo $funcall->getName($this->data['MedicalSickRecord']['prisoner_id'],"Prisoner","prisoner_no");
                                                        echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'hidden'));
                                                    }else{
                                                        echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoner --','options'=>$prisonerListData, 'class'=>'form-control','required', 'id'=>'prisoner_id_attendance'));
                                                    }
                                                   ?>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner Name :</label>
                                                <div class="controls">
                                                   <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control','required', 'id'=>'prisoner_name_id','placeholder'=>'Prisoner name', 'readonly'));?>
                                                   <br>
                                                   <span id="restricted" style="color:red;font-weight: bold;display:none;">This prisoner has restricted</span>
                                                   <span id="unfit_labour" style="color:red;font-weight: bold;display:none;">This prisoner Is Unfit</span>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Height (In CM):</label>
                                                <div class="controls">
                                                    <div class="span12">
                                                            <?php echo $this->Form->input('height_feet',array('div'=>false,'label'=>false,'class'=>'form-control span11', 'empty'=>'-- Height in cm --','type'=>'select','options'=>$heightInFeetList,'id'=>'height_feet',"onChange"=>"checkBmiVal()"));?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Weight (in Kg) :<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('weight',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Weight','minlength'=>'2', 'maxlength'=>'3','onblur'=>"checkClinicalBmiVal()"));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">BMI :<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('bmi',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'BMI','readonly'=>true));?>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">BMI Treatment :<?php //echo $req;?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('bmi_treatment_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$bmiTreatmentList, 'empty'=>'-- Select BMI Treatment --','required'=>false,'id'=>'bmi_treatment_id'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Nutrition Status :<?php echo $req; ?></label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('nutrition_status',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Nutrition Status','readonly'=>true));?>
                                                    <?php
                                                    // $grade = array('A'=>'A','B'=>'B','C'=>'C');
                                                    //  echo $this->Form->input('grade',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','id'=>'grade','empty'=>'--Select Grade--','options'=>$grade,'required'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of checkup :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                <?php
                                                $check_up_date=date("d-m-Y");
                                                    if(isset($this->data["MedicalSickRecord"]["check_up_date"])){
                                                        $check_up_date=date("d-m-Y", strtotime($this->data["MedicalSickRecord"]["check_up_date"]));
                                                        
                                                    }
                                                ?>
                                                    <?php echo $this->Form->input('check_up_date1',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Date of Checkup','required','readonly'=>'readonly','value'=>$check_up_date));?>
                                                </div>
                                            </div>
                                        </div>                             
                                         
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Attendance Description :</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $attendance = "New Attendance";
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
                                                <label class="control-label">Patient Type :</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $checkup_type = "Out Patient";
                                                    if(isset($this->data['MedicalSickRecord']['checkup_type']))
                                                        $checkup_type = $this->data['MedicalSickRecord']['checkup_type'];
                                                    $options2= array("Out Patient"=>"Out Patient","In Patient"=>"In Patient");
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $checkup_type,
                                                        'onclick'=>'showCell(this.value)',
                                                    );
                                                    echo $this->Form->radio('checkup_type', $options2, $attributes2);
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="span6 cell_div" style="display: none;">
                                            <div class="control-group">
                                                <label class="control-label">Ward <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('ward_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$wardMaster, 'empty'=>'-- Select Ward --','required'=>false,'title'=>'Please select ward'));?>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="span6 cell_div" style="display: none;">
                                            <div class="control-group">
                                                <label class="control-label">Cell <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('ward_cell_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Cell --','required'=>false,'title'=>'Please select cell'));?>
                                                    
                                                
                                                </div>
                                            </div>
                                        </div>
                                                                     
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Presenting Compliant :<?php echo $req; ?> </label>
                                                <div class="controls" id="division">
                                                     <?php echo $this->Form->input('compliant',array('type'=>'textarea', 'div'=>false, 'onkeyup'=>'checkMax(this.form)','onkeydown'=>'checkMax(this.form)','label'=>false,'class'=>'form-control span11 alphanumericone','id'=>'compliant', 'rows'=>3, 'placeholder'=>'Enter Presenting compliant'));?>
                                                </div>
                                            </div>
                                        </div>
                                                                    
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Deferential Diagnosis :<?php //echo $req; ?> </label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('digonosis',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','required'=>false,'id'=>'digonosis', 'cols'=>30, 'rows'=>3, 'placeholder'=>'Enter Deferential Diagnosis'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Examination :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('examination',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','type'=>'text', 'placeholder'=>'Enter examination','required')); ?>
                                                    </div>
                                            </div>
                                        </div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Lab Test :<?php //echo $req;?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('disease_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','multiple'=>true,'options'=>$diseaseList, 'empty'=>'-- Select Lab Test --','required'=>false,'id'=>'disease_id'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Results :<?php //echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('results',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','required'=>false,'id'=>'results', 'cols'=>30, 'rows'=>3, 'placeholder'=>'Enter results'));?>
                                                    </div>
                                            </div>
                                        </div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Diagnosis (Dx) :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('digonosis_dx',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','required','id'=>'digonosis_dx', 'cols'=>30, 'rows'=>3, 'placeholder'=>'Enter Diagnosis (Dx)'));?>
                                                </div>
                                            </div>
                                        </div>
                                                                
                                        

                                        <!-- <div class="clearfix"></div> -->
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Radiology :<?php //echo $req; ?> </label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('radiology',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','required'=>false,'id'=>'radiology', 'cols'=>30, 'rows'=>3, 'placeholder'=>'Enter Radiology'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prescription :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('drug_description',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','required','id'=>'drug_description', 'cols'=>30, 'rows'=>3,'placeholder'=>'Enter durg description prescribed'));?>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Treatement (Rx)</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('treatement_rx',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','required','id'=>'treatement_rx', 'cols'=>30, 'rows'=>3, 'placeholder'=>'Enter Treatement (Rx)'));?>
                                                    </div>
                                            </div>
                                        </div> 
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Attachment : </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('attachment',array('type'=>'file','div'=>false,'label'=>false,'class'=>'form-control',
                                                    'id'=>'attachment','required'=>false));?>
                                                    <br /><span>(upload jpg,jpeg,png,gif,pdf,doc,docx type file)</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Restricted Prisoner :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('restricted_prisoner',array('div'=>false,'label'=>false,'class'=>'form-control','required','type'=>'checkbox', 'onChange'=>'showRestrictedPrisonerReamrks(this.value)'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                         <div class="span6" style="display: none;" id="restricted_prisoner">
                                            <div class="control-group">
                                                <label class="control-label">Restricted Prisoner Remarks:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('remarks_restricted_text',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>''));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Presentation of Patient :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                     <?php
                                                     $status = array('Walking'=>'Walking','Lying'=>'Lying','Sitting'=>'Sitting');
                                                      echo $this->Form->input('presentation',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'form-control span11','required'=>true,'id'=>'presentation', 'empty'=>'--Select--','options'=>$status,'title'=>'Please provide patient presentation'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Special Diet :<?php //echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('diet',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','required'=>false,'id'=>'diet', 'cols'=>30, 'rows'=>3,'placeholder'=>'Enter Special Diet'));?>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">State Of Prisoner :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                     <?php
                                                    echo $this->Form->input('prisoner_state_id',array(
                                                      'div'=>false,
                                                      'label'=>false,
                                                      'type'=>'select',
                                                      'required',
                                                      'empty'=>'--Select--',
                                                      'options' => $prisonerStateList,
                                                    ));
                                                 ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Remarks :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('prisoner_state_remarks',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','required', 'cols'=>30, 'rows'=>3,'placeholder'=>'Enter State Of Prisoner Remarks'));?>
                                                    </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'saveClinicalAttendance'))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                            </div>
                                <div class="collapse <?php if($isEdit == 0){ echo "in";}?>" id="searchClinicalAttendance" <?php if($isEdit == 1){?> style="height: 0px;"<?php }?>>
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id'=>'ClinicalAttendanceData'));?>
                            <div class="row" style="padding: 0px 0px 0px 60px;">
                                <?php
                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
                                ?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prison Name. :</label>
                                        <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $prisonListData, 'empty'=>'-- Select Prison --','id'=>'prison_id_serch_attendance'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner No. :</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'prisoner_id_serch_attendance', 'style'=>''));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date of checkup :</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('sick_checkup_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'sick_checkup_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('sick_checkup_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'sick_checkup_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Attendance Description :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('attendence_search',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Attendance Description --','options'=>$attendence_description_search, 'class'=>'form-control','required', 'id'=>'attendence_search'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Patient Type:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('patient_type',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- All --','options'=>array("In Patient"=>"In Patient","Out Patient"=>"Out Patient"), 'class'=>'form-control','required', 'id'=>'patient_type'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Lab Test :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('lab_test_search',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Lab Test --','options'=>$diseaseList, 'class'=>'form-control','required','multiple','id'=>'lab_test_search'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Status :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'status_sick','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchsick" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                             <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetClinicalData('ClinicalAttendanceData')"))?>    
                            </div>
                    <?php echo $this->Form->end();?>
                     
                                <div class="table-responsive" id="sickListingDiv">

                                </div>
                                </div>
                            </div>
                            <div id="seriouslyill">

                            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                    <h5>Recommend For Referral</h5>
                                    <a class="toggleBtn" href="#searchRecommendForRelease" data-toggle="collapse" htmlshwattr="seriouslyill" title="Search" id="searchRecommendForReleaseIcon"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){?>
                                        <div style="float:right;padding-top: 3px;">
                                            <?php echo $this->Html->link('Add Recommend For Referral','#addRecommendForReferral',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse", 'htmlshwattr'=>"seriouslyill",'id'=>"seriouslyilldiv")); ?>
                                        </div>
                                    <?php }?>
                                </div>
                                <div id="addRecommendForReferral" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;"<?php }?>>
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('MedicalSeriousIllRecord',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    
                                    
                                    <div class="row" style="padding: 0px 0px 0px 60px;">
                                    <?php //debug($this->data['MedicalSeriousIllRecord']);
                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
                                    ?>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prison Name111. :</label>
                                            <div class="controls">
                                            <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $prisonListData, 'empty'=>'-- Select Prison --','required','id'=>'prison_id_se11111archrecommend'));?>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Prisoner No. :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 220px;">
                                                   <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoner --','options'=>$prisonerListData, 'class'=>'form-control','required', 'id'=>'prisoner_id_recommend'));?>
                                                </div>
                                            </div>
                                    </div>
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Date of recommendation :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 220px;">
                                                
                                                
                                                <?php
                                                $check_up_date=date("d-m-Y");
                                                    if(isset($this->data["MedicalSeriousIllRecord"]["check_up_date"])){
                                                        $check_up_date=date("d-m-Y", strtotime($this->data["MedicalSeriousIllRecord"]["check_up_date"]));
                                                        
                                                    }
                                                ?>
                                                    <?php echo $this->Form->input('check_up_date',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text', 'placeholder'=>'Enter Date of Checkup','required','id'=>'check_up_date', 'readonly'=>'readonly','value'=>$check_up_date));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Priority :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 220px;">
                                                    <?php echo $this->Form->input('priority',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$priorityList, 'empty'=>'-- Select Priority --','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Medical Personnel :</label>
                                                <div class="controls" style="margin-left: 220px;">
                                                   <?php echo $this->Form->input('medical_officer_id_other',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Medical Officer --','options'=>$medicalOfficerListData, 'default'=>$this->Session->read('Auth.User.id'), 'class'=>'form-control','required', 'id'=>'medical_officer_id_other'));?>
                                                </div>
                                            </div>
                                        </div>
                                                                            
                                        <div class="clearfix"></div>
                                        
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Cause of Recommendation :<?php echo $req; ?></label>
                                                <div class="controls" style="margin-left: 220px;">
                                                    <?php //echo $this->Form->input('remark',array('type'=>'select', 'options'=>$recommendationList, 'div'=>false,'label'=>false,'empty'=>'-- Select Recommendation --','class'=>'form-control span11','required'));?>
                                                    <?php echo $this->Form->input('remark',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','rows'=>'3','cols'=>'6'));?>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        
                                       
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Recommendation Category :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 220px;">
                                                    <?php echo $this->Form->input('category_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $categoryList, 'onChange' => 'showHospital(this.value)','empty'=>'-- Select Recomendation Category --','required','id'=>'malnutrition_type_id'));?>
                                                    </div>
                                            </div>
                                        </div>
                                         <div class="clearfix"></div>
                                        <div class="span6" id="hospital_div" style="display: none;">
                                            <div class="control-group" >
                                                <label class="control-label" style="width: 200px;">Hospital Name :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 220px;">
                                                    <?php echo $this->Form->input('hospital_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$hospitalList,'empty'=>'-- Select Hospital --','required','id'=>'hospital_id'));?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <hr>
                                    
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'recommendationForRelese'))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                </div>
                                 <div class="collapse <?php if($isEdit == 0){ echo "in";}?>" id="searchRecommendForRelease" <?php if($isEdit == 1){?> style="height: 0px;"<?php }?>>
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id'=>'searchReleseData'));?>
                            <div class="row" style="padding: 0px 0px 0px 60px;">
                                <?php
                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
                                ?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prison Name. :</label>
                                        <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $prisonListData, 'empty'=>'-- Select Prison --','required','id'=>'prison_id_searchrecommend'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner No. :</label>
                                        <div class="controls">
                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'prisoner_id_searchrecommend', 'style'=>''));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date of recommendation :</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('recommendation_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate
                                                ','type'=>'text','placeholder'=>'Start Date','id'=>'recommendation_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('recommendation_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text','placeholder'=>'End Date','id'=>'recommendation_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Priority :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('priority_searched',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$priorityList, 'empty'=>'-- Select Priority --','required','id'=>'priority_searched'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Medical Personnel :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('medical_off_ser',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Medical Officer --','options'=>$medicalOfficerListData, 'default'=>$this->Session->read('Auth.User.id'), 'class'=>'form-control','required', 'id'=>'medical_off_ser'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Recomendation Category :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('hos_id_search',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $categoryList, 'empty'=>'-- Select Recomendation Category --','required','id'=>'hos_id_search'));?>
                                            
                                        </div>
                                    </div>
                                </div>


                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Status :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'statusseriousIll','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchrecommend" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                             <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetRecommendData('searchReleseData')"))?>     
                            </div>
                    <?php echo $this->Form->end();?>
                     
                                <div class="table-responsive" id="seriousIllListingDiv">

                                </div>  
                                </div>                              
                            </div>
                            <div id="death">
                            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                    <h5>Death</h5>
                                    <a class="toggleBtn" href="#searchDeath" data-toggle="collapse" htmlshwattr="death" title="Search" id="searchDeathIcon"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
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
                                    <div class="row" style="padding: 0px 0px 0px 60px;">
                                    <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner No. :<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                   <?php 
                                                   if(isset($this->data['MedicalDeathRecord']['id']) && $this->data['MedicalDeathRecord']['id']!=''){
                                                        echo $funcall->getName($this->data['MedicalDeathRecord']['prisoner_id'],"Prisoner","prisoner_no");
                                                        echo $this->Form->input('prisoner_id',array('type'=>'hidden','div'=>false,'label'=>false));
                                                    }else{
                                                        echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoners --','options'=>$prisonerDeathListData, 'class'=>'form-control','required', 'id'=>'prisoner_id_death'));
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                    </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Cause Of Death :<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('death_cause',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'cause', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Presumed Cause of death :<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('presumed_cause',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control span11','required', 'cols'=>30, 'rows'=>3,'title'=>'Please provide presumed cause of death'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Actual Cause of Death :<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('actual_cause',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control span11','required', 'cols'=>30, 'rows'=>3,'title'=>'Please provide actual cause of death'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date and Time of Death :<?php echo $req; ?> :</label>
                                                <div class="controls">

                                                <?php
                                                $check_up_date="";
                                                    if(isset($this->data["MedicalDeathRecord"]["check_up_date"])){
                                                        $check_up_date1=$this->data["MedicalDeathRecord"]["check_up_date"];
                                                       
                                                        $check_up_date = date("d-m-Y H:i");
                                                    }
                                                ?>
                                                    <?php echo $this->Form->input('check_up_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 mydatetimepicker1','type'=>'text', 'placeholder'=>'Enter Date and Time of Death','required','readonly'=>'readonly','value'=>$check_up_date));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <!-- <label class="control-label">Time of Death<?php //echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php //echo $this->Form->input('time_of_death',array('div'=>false,'label'=>false,'class'=>'form-control timepicker1 span11','type'=>'text', 'placeholder'=>'Enter Time of Death','readonly'=>'readonly'));?>
                                                </div> -->
                                            </div>
                                        </div>   
                                        <div class="clearfix"></div>  
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Place of Death:</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $death_place = "Inside the Prison";
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
                                                <label class="control-label">Medical Personnel :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('medical_officer_id_death',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisnors --','options'=>$medicalOfficerListData, 'default'=>$this->Session->read('Auth.User.id'), 'class'=>'form-control','id'=>'medical_officer_id_death'));?>
                                                </div>
                                            </div>
                                        </div>
                                         
                                        <div class="clearfix"></div>                           
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Upload Medical Form :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php 
                                                    $required = array('required'=>true); 
                                                    if(isset($this->data['MedicalDeathRecord']['medical_from_attach']) && $this->data['MedicalDeathRecord']['medical_from_attach']!=''){
                                                        $required = array('required'=>false); 
                                                    }
                                                    echo $this->Form->input('medical_from_attach',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','title'=>"Please upload medical form.")+$required);?>
                                                    <br /><span>(upload jpg,jpeg,png,gif,pdf,doc,docx type file)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Upload Postmotorm Report :</label>
                                                <div class="controls">
                                                    <?php 
                                                    $required = array('required'=>false); 
                                                    if(isset($this->data['MedicalDeathRecord']['attachment']) && $this->data['MedicalDeathRecord']['attachment']!=''){
                                                        $required = array('required'=>false); 
                                                    }
                                                    echo $this->Form->input('attachment',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','title'=>"Please upload postmotorm report.")+$required);?>
                                                    <br /><span>(upload jpg,jpeg,png,gif,pdf,doc,docx type file)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Upload Pathologist Report :<?php //echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php
                                                    $required = array('required'=>false); 
                                                    if(isset($this->data['MedicalDeathRecord']['pathologist_attach']) && $this->data['MedicalDeathRecord']['pathologist_attach']!=''){
                                                        $required = array('required'=>false); 
                                                    }
                                                    echo $this->Form->input('pathologist_attach',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','title'=>"Please upload pathological report.")+$required);?>
                                                    <br /><span>(upload jpg,jpeg,png,gif,pdf,doc,docx type file)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false,'onclick'=>'saveFunction()','class'=>'btn btn-success','id'=>'deathValidation'))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                </div>
                                <div class="collapse <?php if($isEdit == 0){ echo "in";}?>" id="searchDeath" <?php if($isEdit == 1){?> style="height: 0px;"<?php }?>>
                                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id'=>'showDeathdata'));?>
                                            <div class="row" style="padding: 0px 60px;">
                                                <?php
                                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
                                                ?>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Prison Name. :</label>
                                                        <div class="controls">
                                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $prisonListData, 'empty'=>'-- Select Prison --','id'=>'prison_id_searchdeath'));?>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                }
                                                ?>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Prisoner No. :</label>
                                                        <div class="controls">
                                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'prisoner_id_searchdeath', 'style'=>''));?>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Date of Death :</label>
                                                        <div class="controls">
                                                            
                                                            <?php echo $this->Form->input('death_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'death_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                                            To
                                                            <?php echo $this->Form->input('death_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'death_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Medical Personnel:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('medi_off_death',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Medical Officer --','options'=>$medicalOfficerListData, 'default'=>$this->Session->read('Auth.User.id'), 'class'=>'form-control','required', 'id'=>'medi_off_death'));?>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6" style="display:none;">
                                                    <div class="control-group">
                                                        <label class="control-label">Status :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'statusdeath','default'=>$default_status));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div> 
                                            </div>
                                            <div class="form-actions" align="center">
                                            <button id="btnsearchdeath" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                                             <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetDeathData('showDeathdata')"))?>    
                                            </div>
                                    <?php echo $this->Form->end();?>
                                     
                                <div class="table-responsive" id="deathListingDiv"></div>
                                </div>
                            </div>

                            <div id="release_recom">

                            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                    <h5>Recommend For Release</h5>
                                    <a class="toggleBtn" href="#searchRecommendForReleaseRecom" data-toggle="collapse" htmlshwattr="release_recom" title="Search" id="searchRecommendForReleaseRecomIcon"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){?>
                                        <div style="float:right;padding-top: 3px;">
                                            <?php echo $this->Html->link('Add Recommend For Release','#addRecommendForRelease',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse", 'htmlshwattr'=>"release_recom",'id'=>"release_recomdiv")); ?>
                                        </div>
                                    <?php }?>
                                </div>
                                <div id="addRecommendForRelease" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;"<?php }?>>
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('MedicalRelease',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    
                                    
                                    <div class="row" style="padding: 0px 0px 0px 60px;">
                                    <?php
                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
                                    ?>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prison Name111. :</label>
                                            <div class="controls">
                                            <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $prisonListData, 'empty'=>'-- Select Prison --','required','id'=>'prison_id_se11111archrecommend'));?>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Prisoner No. :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 220px;">
                                                   <?php 
                                                   if(isset($this->data["MedicalRelease"]["id"]) && $this->data["MedicalRelease"]["id"]!='' ){
                                                        echo $this->data["Prisoner"]["prisoner_no"];
                                                   }else{
                                                        echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoner --','options'=>$prisonerReleaseListData, 'class'=>'form-control','required'));
                                                   }
                                                   ?>
                                                </div>
                                            </div>
                                    </div>
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Date of recommendation :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 220px;">
                                                
                                                <?php
                                                $check_up_date= date('d-m-Y');

                                                     if(isset($this->data["MedicalRelease"]["check_up_date"]) ){
                                                        $check_up_date=date('d-m-Y',strtotime($this->data["MedicalRelease"]["check_up_date"]));
                                                        
                                                    }
                                                ?>
                                                    <?php echo $this->Form->input('check_up_date',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text', 'placeholder'=>'Enter Date of Checkup','required','id'=>'check_up_date', 'readonly'=>'readonly','value'=>$check_up_date));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        
                                        <div class="clearfix"></div> 
                                        <!-- <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Priority :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 220px;">
                                                    <?php echo $this->Form->input('priority',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$priorityList, 'empty'=>'-- Select Priority --','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Medical Personnel :</label>
                                                <div class="controls" style="margin-left: 220px;">
                                                   <?php echo $this->Form->input('medical_officer_id_other',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Medical Officer --','options'=>$medicalOfficerListData, 'default'=>$this->Session->read('Auth.User.id'), 'class'=>'form-control','required', 'id'=>'medical_officer_id_other'));?>
                                                </div>
                                            </div>
                                        </div>
                                                                            
                                        <div class="clearfix"></div>
                                        
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Cause of Recommendation :<?php echo $req; ?></label>
                                                <div class="controls" style="margin-left: 220px;">
                                                    <?php //echo $this->Form->input('remark',array('type'=>'select', 'options'=>$recommendationList, 'div'=>false,'label'=>false,'empty'=>'-- Select Recommendation --','class'=>'form-control span11','required'));?>
                                                    <?php echo $this->Form->input('remark',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','rows'=>'3','cols'=>'6'));?>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        
                                       
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Recommendation Category :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 220px;">
                                                    <?php echo $this->Form->input('category_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $categoryList, 'empty'=>'-- Select Recomendation Category --','required','id'=>'malnutrition_type_id'));?>
                                                    </div>
                                            </div>
                                        </div>
                                         <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 200px;">Hospital Name :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 220px;">
                                                    <?php echo $this->Form->input('hospital_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$hospitalList, 'empty'=>'-- Select Hospital --','required','id'=>'hospital_id'));?>
                                                </div>
                                            </div>
                                        </div> -->

                                    </div>
                                    <hr>
                                    <div class="row" style="padding: 0px 0px 0px 60px;">
                                     <h5>Prisons Form 19</h5> 
                                    <h6>(a) (Seriously ill, and that his/her condition is as follows, namely</h6>
                                 
                                    <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;">State abnormal condition present :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                   <?php echo $this->Form->input('prisoner_condition_present',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control','required'));?>
                                                </div>
                                            </div>
                                    </div>
                                      <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;">State what is known of duration and cause of condition :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_cause_condition',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text', 'placeholder'=>'State what is known of duration and cause of condition','required'));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        
                                        <div class="clearfix"></div> 
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;">Is life of prisoner likely to be endangered or shortened by further imprisonment ? :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_endanger',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;">Is Illness likely to terminate fatally within a brief period and before expiration of sentence?  :</label>
                                                <div class="controls" style="margin-left: 520px;">
                                                   <?php echo $this->Form->input('prisoner_illness_expiry',array('div'=>false,'label'=>false,'type'=>'text','class'=>'form-control','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                                                            
                                        <div class="clearfix"></div>
                                        
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;"> Is Illness of peculiarly aggravated or painful character ? :<?php echo $req; ?></label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_illness_pain',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','rows'=>'3','cols'=>'6'));?>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        
                                       
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;">Was Illness contracted in prison? :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_illness',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'));?>
                                                    </div>
                                            </div>
                                        </div>
                                         <div class="clearfix"></div>
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;">Is illness of such type that prisoner will be permanently unfit for any form of prison labour? :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_fitness',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;">Can the case not be met by temporary removal to hospital? :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_temp_release',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;">Extremely old, crippled, or feeble :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                                                      <?php
                                            $apps = array(
                                                'Extremely old' => 'Extremely old',
                                                'crippled' => 'crippled',
                                                'feeble' => 'feeble',
                                            );
                                            if (isset($this->request->data['MedicalRelease']['presentation_id']) && $this->request->data['MedicalRelease']['presentation_id']!='') {
                                                $this->request->data['MedicalRelease']['presentation_id'] = explode(",", $this->request->data['MedicalRelease']['presentation_id']);
                                            }
                                            echo $this->Form->select('MedicalRelease.presentation_id', $apps, array(
                                                'multiple' => 'checkbox','style'=>'margin-left:0px !important'

                                            ));
                                            ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;"><strong>(b) In a mental condition that is liable to be affected or endangered by further imprisonment  :</strong><?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_liability',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;"><strong>(c) Any other observations :</strong><?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_obsv',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;"><strong>Ref No :</strong><?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_ref_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){?>
                                    <hr>
                                    <div class="row" style="padding: 0px 0px 0px 60px;">
                                    <h5>Prisons Form 20</h5>
                                    
                                    <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;">  Whether friends are able and willing to receive and support the prisoner if discharged :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                   <?php echo $this->Form->input('prisoner_supporter',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control','required'));?>
                                                </div>
                                            </div>
                                    </div>
                                      <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;">The prisoner’s own wishes :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_wishes',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text', 'placeholder'=>'Prisoner’s own wishes','required',));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        
                                        <div class="clearfix"></div> 
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;"> Whether or not it is possible that the prisoner will again engage in crime :<?php echo $req; ?> </label>
                                                <div class="controls" style="margin-left: 520px;">
                                                    <?php echo $this->Form->input('prisoner_crime',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label" style="width: 500px;"> Whether in case of prisoner being without home or friends, there is any hospital or other suitable institution to which prisoner could be removed :</label>
                                                <div class="controls" style="margin-left: 520px;">
                                                   <?php echo $this->Form->input('prisoner_relocation',array('div'=>false,'label'=>false,'type'=>'text','class'=>'form-control','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                                                            
                                        <div class="clearfix"></div>
                                        
                                    </div>
                                    <?php }?>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'recommendationForRelease'))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                </div>
                                 <div class="collapse <?php if($isEdit == 0){ echo "in";}?>" id="searchRecommendForReleaseRecom" <?php if($isEdit == 1){?> style="height: 0px;"<?php }?>>
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id'=>'searchReleaseData'));?>
                            <div class="row" style="padding: 0px 0px 0px 60px;">
                                <?php
                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
                                ?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prison Name. :</label>
                                        <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $prisonListData, 'empty'=>'-- Select Prison --','required','id'=>'prison_id_searchrecommend'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner No. :</label>
                                        <div class="controls">
                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'prisoner_id_searchrecommend', 'style'=>''));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date of recommendation :</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('recommendation_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate
                                                ','type'=>'text','placeholder'=>'Start Date','id'=>'recommendation_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('recommendation_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text','placeholder'=>'End Date','id'=>'recommendation_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Priority :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('priority_searched',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$priorityList, 'empty'=>'-- Select Priority --','required','id'=>'priority_searched'));?>
                                            
                                        </div>
                                    </div>
                                </div> -->
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Medical Personnel :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('medical_off_ser',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Medical Officer --','options'=>$medicalOfficerListData, 'default'=>$this->Session->read('Auth.User.id'), 'class'=>'form-control','required', 'id'=>'medical_off_ser'));?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Recomendation Category :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('hos_id_search',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=> $categoryList, 'empty'=>'-- Select Recomendation Category --','required','id'=>'hos_id_search'));?>
                                            
                                        </div>
                                    </div>
                                </div> -->


                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Status :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'statusRelease','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchrelease" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                             <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetReleaseData('searchReleaseData')"))?>     
                            </div>
                    <?php echo $this->Form->end();?>
                     
                                <div class="table-responsive" id="medicalReleaseListingDiv">

                                </div>  
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
$medicalReleaseUrl   = $this->Html->url(array('controller'=>'medicalRecords','action'=>'showMedicalRelease'));
$deleteMedicalReleaseUrl   = $this->Html->url(array('controller'=>'medicalRecords','action'=>'deleteMedicalRelease'));
$deleteMedicalSeriUrl   = $this->Html->url(array('controller'=>'medicalRecords','action'=>'deleteMedicalSeriousillRecords'));
$medicalDeathUrl        = $this->Html->url(array('controller'=>'medicalRecords','action'=>'showMedicalDeathRecords'));
$deleteMedicalDeathUrl  = $this->Html->url(array('controller'=>'medicalRecords','action'=>'deleteMedicalDeathRecords'));
echo $this->Html->scriptBlock("
    var tab_param = '';
    var tabs;
    jQuery(function($) {
        $('select').select2({});
         $('select').each(function(i){
            if($(this).val()==''){
                $(this).select2('val','');
            }
        });
        $('.mydatetimepicker1').datetimepicker({
                showMeridian: false,
                defaultTime:false,
                format: 'dd-mm-yyyy hh:ii',
                autoclose:true
            }).on('changeDate', function (ev) {
                 $(this).datetimepicker('hide');
                 $(this).blur();
            });
        $('.toggleBtn').click(function(){
            var htmlshwattr=$(this).attr('htmlshwattr');
            $(this).closest('#'+htmlshwattr).find('.collapse.in').css('height','0');
            $(this).closest('#'+htmlshwattr).find('.collapse.in').removeClass('in');
         });
        if($('#MedicalCheckupRecordId').val()==''){
           // $('#prisoner_id_serch').select2('val','');
            //$('#prisoner_id').select2('val', '');
            //$('#check_up').select2('val','');
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
            $('#prison_id_serch_attendance').select2('val','');

            $('#disease_id').select2('val','');
        }   
        if($('#MedicalSeriousIllRecordId').val()==''){
            $('#prison_id_searchrecommend').select2('val', '');
            $('#prisoner_id_recommend').select2('val', '');
            $('#prisoner_id_searchrecommend').select2('val','');
            //$('#MedicalSeriousIllRecordDiseaseId').select2('val','');
            $('#recomendation_category').select2('val','');
            $('#MedicalSeriousIllRecordPriority').select2('val','');
            $('#hospital_id').select2('val','');
            $('#priority_searched').select2('val','');
            $('#hos_id_search').select2('val','');
        }
        if($('#MedicalReleaseId').val()==''){
            $('#prison_id_searchrecommend').select2('val', '');
            $('#MedicalReleasePrisonerId').select2('val', '');
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
        $('#medicalReleaseDiv').on('click', function(e){
            showMedicalRelease();
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
        }else if(tab_param == 'release_recom'){
            showMedicalRelease();
        }else if(tab_param == 'death'){
            showMedicalDeathRecords();
        }
        else{
            showMedicalChekupRecords();
        }
          
    });
    function showMedicalChekupRecords()
    {
        // alert($('#blood_group_search').val());
       
        var url         = '".$medicalChekupUrl."';
        if($('#prisoner_id_serch').val() != ''){
            var prisoner_id = $('#prisoner_id_serch').val().replace('/', '-');
            prisoner_id = prisoner_id.replace('/', '-');
            url = url + '/prisoner_id:'+prisoner_id;
        }
        test = encodeURIComponent($('#blood_group_search').val());
        url = url + '/prison_id:'+$('#prison_id_serch').val();   
        url = url + '/age_from:'+$('#age_from').val();
        url = url + '/age_to:'+$('#age_to').val();
        url = url + '/folow_from:'+$('#folow_from').val();
        url = url + '/folow_to:'+$('#folow_to').val();
        url = url + '/hgt_ft:'+$('#hgt_ft').val();
        url = url + '/hgt_inch:'+$('#hgt_inch').val();
        url = url + '/weight_search:'+$('#weight_search').val();
        url = url + '/status:'+$('#status').val();
        url = url + '/blood_group_search:'+test;
        url = url + '/uuid:".$uuid."';
        
        // alert(url);
         
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
        //url = url + '/prisoner_id:'+$('#prisoner_id_serch').val();
        url = url + '/prison_id:'+$('#prison_id_serch_attendance').val();
        url = url + '/sick_checkup_from:'+$('#sick_checkup_from').val();
        url = url + '/sick_checkup_to:'+$('#sick_checkup_to').val();
        url = url + '/attendence_search:'+$('#attendence_search').val();
        url = url + '/patient_type:'+$('#patient_type').val();
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
            url = url + '/prison_id:'+$('#prison_id_searchrecommend').val();
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


    function showMedicalRelease(){
        var prisoner_id = '';
        var uuid        = '';
        var url         = '".$medicalReleaseUrl."';
        if($('#prisoner_id_searchrecommend').val() != ''){
            var prisoner_id = $('#prisoner_id_searchrecommend').val().replace('/', '-');
            prisoner_id = prisoner_id.replace('/', '-');
            url = url + '/prisoner_id:'+prisoner_id;            
        }
       // url = url + '/prisoner_id:'+$('#prisoner_id_serch').val();
            url = url + '/prison_id:'+$('#prison_id_searchrecommend').val();
            url = url + '/recommendation_from:'+$('#recommendation_from').val();
            url = url + '/recommendation_to:'+$('#recommendation_to').val();
            url = url + '/priority_searched:'+$('#priority_searched').val();
            url = url + '/medical_off_ser:'+$('#medical_off_ser').val();
            url = url + '/hos_id_search:'+$('#hos_id_search').val();
            url = url + '/status:'+$('#statusRelease').val();
            url = url + '/uuid:".$uuid."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#medicalReleaseListingDiv').html(res);
                var usertype_id='".$this->Session->read('Auth.User.usertype_id')."';
                var user_typercpt='".Configure::read('MEDICALOFFICE_USERTYPE')."';
                var user_typepoi='".Configure::read('PRINCIPALOFFICER_USERTYPE')."';
                var user_typeoiu='".Configure::read('OFFICERINCHARGE_USERTYPE')."';
             
                 if(usertype_id==user_typercpt)
                 {
                    if($('#statusRelease').val()=='Saved' || $('#statusRelease').val()=='Approved' || $('#statusRelease').val()=='Approve-Rejected'){
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
                    if($('#statusRelease').val()=='Reviewed' || $('#statusRelease').val()=='Approved' || $('#statusRelease').val()=='Approve-Rejected'){
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
                    if($('#statusRelease').val()=='Approved'){
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
    function deleteMedicalRelease(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteMedicalReleaseUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    if(res == 'SUCC'){
                        showMedicalRelease();
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
        url = url + '/prison_id:'+$('#prison_id_searchdeath').val();
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

$('#update_initial_exit').click(function(){
        //if($("#MedicalCheckupRecordEditForm").valid()){
            if( !confirm('Are you sure to update?')) {
                return false;
            }
        //}
    });
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
                // 'data[MedicalSeriousIllRecord][hospital_id]': {
                //     required: true,
                // },
                'data[MedicalSeriousIllRecord][remark]': {
                    required: true,
                },
                'data[MedicalSeriousIllRecord][category_id]':{
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
                // 'data[MedicalSeriousIllRecord][hospital_id]': {
                //     required: "Please select hospital.",
                // },
                'data[MedicalSeriousIllRecord][remark]': {
                    required: "Please enter cause of recommendation .",
                },
                'data[MedicalSeriousIllRecord][category_id]':{
                   required: "Please enter recommendation category .",  
                }
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
 $(document).on('click',"#btnsearchrelease", function () { // button name
        showMedicalRelease();
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
//partha code starts
function showHivtesting(ishiv)
{
    if(ishiv == "Yes")
    {
        $('#hiv_test').show();
        $('#hiv_test').attr('required','required');
    }
    else 
    {
        $('#hiv_test').hide();
        $('#hiv_test').val('');
        $('#hiv_test').removeAttr('required');
    }
}
// partha code ends

function showregimen(ishiv)
{
    if(ishiv == "Positve")
    {
        $('#MedicalCheckupRecordRegimen').show();
        $('#MedicalCheckupRecordRegimen').attr('required','required');
    }
    else 
    {
        $('#MedicalCheckupRecordRegimen').hide();
        $('#MedicalCheckupRecordRegimen').val('');
        $('#MedicalCheckupRecordRegimen').removeAttr('required');
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

function showHospital(isplace) 
{
    if(isplace == 1) {//alert(isplace);
        $('#hospital_div').show();
        $('#hospital_id').attr('required', 'required');
       
    }else{//alert(isplace);
        $('#hospital_div').hide();
        $('#hospital_id').removeAttr("required");
    }
}

//check if is dual citizen is clicked
$(document).ready(function () {
    // alert(2);
    showRestrictedPrisonerReamrks();
    <?php
    if(isset($this->data['MedicalSickRecord']['ward_id']) && $this->data['MedicalSickRecord']['ward_id']!=''){
        ?>
        
        $('#MedicalSickRecordWardId').trigger('change');
        $(".cell_div").show();
        $(".cell_div select").attr("required",true);
        <?php
    }
    ?>
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
    <?php if(isset($this->data['MedicalSeriousIllRecord']['prisoner_id']) && $this->data['MedicalSeriousIllRecord']['prisoner_id']!=''){?>
    $('#prisoner_id_recommend').select2('val',<?php echo $this->data['MedicalSeriousIllRecord']['prisoner_id']?>);
    <?php }?>
    <?php if(isset($this->data['MedicalSeriousIllRecord']['priority']) && $this->data['MedicalSeriousIllRecord']['priority']!=''){?>
    $('#MedicalSeriousIllRecordPriority').select2('val','<?php echo $this->data['MedicalSeriousIllRecord']['priority']?>');
    <?php }?>
    <?php if(isset($this->data['MedicalSeriousIllRecord']['hospital_id']) && $this->data['MedicalSeriousIllRecord']['hospital_id']!=''){?>
    $('#hospital_id').select2('val','<?php echo $this->data['MedicalSeriousIllRecord']['hospital_id']?>');
    <?php }?>
    <?php if(isset($this->data['MedicalSeriousIllRecord']['category_id']) && $this->data['MedicalSeriousIllRecord']['category_id']!=''){?>
    showHospital(<?php echo $this->data['MedicalSeriousIllRecord']['category_id']?>);
    <?php }?>
});

function getPrisonerDetails(prisoner_id)
{
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
        //alert(data.height_feet);
        $('#prisoner_name').val(data.prisoner_name);
        $('#gender').val(data.gender);
        $('#age').val(data.age);
        //var height=data.height_feet+" foot "+data.height_inch+" inch";
        // alert(data.height_feet);
        // alert(data.height_inch);
        $("#height_feet").val(data.height_feet);
        $("#height_inch").val(data.height_inch);
        
        // $("#height_feet").select2('val',data.height_feet);
        // $("#height_inch").select2('val',data.height_inch);
      },
      error: function (errormessage) {
        alert(errormessage.responseText);
      }
    });
}

$(document).on('change', '#prisoner_id', function(){
  var prisoner_id=$(this).val();
  getPrisonerDetails(prisoner_id);
});

$(document).on('change', '#prisoner_id_attendance', function(){
  var prisoner_id_attendance=$(this).val();
  $.ajax(
  {
      type: "POST",
      dataType: "json",
      url: "<?php echo $this->Html->url(array('controller'=>'MedicalRecords','action'=>'getPrisnerInfo'));?>",
      data: {
          prisoner_id: prisoner_id_attendance,
      },
      cache: true,
      beforeSend: function()
      {  
        //$('tbody').html('');
      },
      success: function (data) {
        $('#prisoner_name_id').val(data.prisoner_name);
        $('#height_feet').val(data.height_feet);
        $('#height_feet').select2('val',data.height_feet);
        $('#MedicalSickRecordRestrictedPrisoner').attr('checked', false);
        $("#uniform-MedicalSickRecordRestrictedPrisoner span").removeClass("checked");
        $('#MedicalSickRecordPrisonerStateId').val('');
        $('#MedicalSickRecordPrisonerStateId').select2('val','');
        $("#restricted").hide();
        $("#unfit_labour").hide();
        $('#restricted_prisoner').hide();
        if(data.is_restricted == 1 || data.is_unfit_labour == 1){
           
            // $("#malnutrition_type_id").val(1);
            // $("#restricted_prisoner").show();
           
            if (data.is_restricted == 1) {
                $('#MedicalSickRecordRestrictedPrisoner').attr('checked', true); // Checks it
                $("#uniform-MedicalSickRecordRestrictedPrisoner span").addClass("checked");
                $("#restricted").show();
                $('#restricted_prisoner').show();
            }
            if (data.is_unfit_labour == 1) {
                // alert('1');
                $('#MedicalSickRecordPrisonerStateId').val(6);
                $('#MedicalSickRecordPrisonerStateId').select2('val', 6);
                $('#restricted_prisoner').show();
              
                $("#unfit_labour").show();

            }


        }else{
            $("#uniform-MedicalSickRecordRestrictedPrisoner span").removeClass("checked");
            $('#MedicalSickRecordRestrictedPrisoner').attr('checked', false); // Unchecks it
            // $("#malnutrition_type_id").val('');
            // $("#restricted_prisoner").hide();
            $("#restricted").hide();
        }
        
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
$(document).on('click', '#medicalSeriousIllDiv', function(){
    $('#check_up_date').val('<?php echo date('d-m-Y')?>');
    
});
$(document).on('click', '#medicalReleaseDiv', function(){
    $('#check_up_date').val('<?php echo date('d-m-Y')?>');
    
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
$(document).on('click', '#release_recomdiv', function(){
    $("#MedicalSeriousIllRecordId").val('');
    $('#MedicalReleasePrisonerId').select2('val','');
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

<?php 
if(!empty($prisoner_id) && (empty($isPrisonerInitialCheckup) || $isPrisonerInitialCheckup==0))
{?>
    var prisoner_id = '<?php echo $prisoner_id;?>'; 
    // $('#initial_exit_checkup').click();
    $('#check_up').val('Intial');
    $('#check_up').attr('readonly','readonly');
    $('#prisoner_id').val(prisoner_id);
    getPrisonerDetails(prisoner_id);
<?php }?>

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
        checkBmiVal();
      },
      error: function (errormessage) {
        alert(errormessage.responseText);
      }
  });
});

function checkBmiVal(){
    $.ajax({
        type: "POST",
        url: "<?php echo $this->Html->url(array('controller'=>'MedicalRecords','action'=>'getBmiInfo'));?>",
        data: {
            height: $("#MedicalCheckupRecordHeightFeet").val(),
            weight: $("#MedicalCheckupRecordWeight").val(),
        },
        cache: true,
        beforeSend: function()
        {  
            //$('tbody').html('');
        },
        success: function (data) {
            if(data.trim() != 'FAIL'){
                var height = data.split("*****")
                $('#MedicalCheckupRecordGrade').val(height[1]);
                $('#MedicalCheckupRecordBmi').val(height[0]);
            } 
        },
        error: function (errormessage) {
            alert(errormessage.responseText);
        }
    });
}

function checkClinicalBmiVal(){
    
    $.ajax({

        type: "POST",
        url: "<?php echo $this->Html->url(array('controller'=>'MedicalRecords','action'=>'getBmiInfo'));?>",
        data: {

            height: $("#height_feet").val(),
            weight: $("#MedicalSickRecordWeight").val(),
        },
        cache: true,
        beforeSend: function()
        {  
            //$('tbody').html('');
        },
        success: function (data) {
            if(data.trim() != 'FAIL'){
                var height = data.split("*****")
                $('#MedicalSickRecordNutritionStatus').val(height[1]);
                $('#MedicalSickRecordBmi').val(height[0]);
            } 
        },
        error: function (errormessage) {
            alert(errormessage.responseText);
        }
    });
}

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
                // 'data[MedicalCheckupRecord][prisoner_name]': {
                //     required: true,
                // },
                // 'data[MedicalCheckupRecord][gender]': {
                //     required: true,
                // },
                // 'data[MedicalCheckupRecord][age]': {
                //     required: true,
                // },
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
                'data[MedicalCheckupRecord][grade]': {
                    required: true,
                },
                
            },
            messages: {
                'data[MedicalCheckupRecord][check_up]': {
                    required: "Please select check up type.",
                },
                'data[MedicalCheckupRecord][prisoner_id]': {
                    required: "Please select prisoner no.",
                },
                // 'data[MedicalCheckupRecord][prisoner_name]': {
                //     required: "Please enter prisoner name.",
                // },
                // 'data[MedicalCheckupRecord][gender]': {
                //     required: "Please enter gender.",
                // },
                // 'data[MedicalCheckupRecord][age]': {
                //     required: "Please enter age.",
                // },
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
                'data[MedicalCheckupRecord][grade]': {
                    required: "Please select grade",
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
                     maxlength:150
                },
                'data[MedicalSickRecord][checkup_type]':{
                    required: true,
                },
                'data[MedicalSickRecord][examination]': {
                    required: true,
                },
                'data[MedicalSickRecord][digonosis]': {
                    required: false,
                },
                'data[MedicalSickRecord][disease_id]': {
                    required: false,
                },
                'data[MedicalSickRecord][results]': {
                    required: false,
                },
                'data[MedicalSickRecord][digonosis_dx]': {
                    required: true,
                },
                'data[MedicalSickRecord][treatement_rx]': {
                    required: false,
                },
                'data[MedicalSickRecord][radiology]': {
                    required: false,
                },
                'data[MedicalSickRecord][drug_description]': {
                    required: true,
                },
                'data[MedicalSickRecord][attachment]': {
                    //required: true,
                    <?php 
                        if(!isset($this->request->data["MedicalSickRecord"]["attachment"]))
                        {
                     ?>
                        required: false,
                    <?php
                        }
                     ?>
                },
                'data[MedicalSickRecord][malnutrition_type_id]': {
                    required:true,
                },
                'data[MedicalSickRecord][diet]':{
                    required:false,
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
                    maxlength:"should be less than 150 characters"
                },
                'data[MedicalSickRecord][checkup_type]':{
                   required: "Please enter check-up type.", 
                },
                'data[MedicalSickRecord][examination]': {
                    required: "Please enter examination.",
                   
                },
                'data[MedicalSickRecord][digonosis]': {
                    required: "Please enter deferential diagonosis.",
                   
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
                    required: "Please enter Radiology.",
                   
                },
                'data[MedicalSickRecord][drug_description]': {
                    required: "Please enter durg description prescribed.",
                   
                },
                'data[MedicalSickRecord][attachment]': {
                    required: "Please choose attachment.",
                   
                },
                'data[MedicalSickRecord][malnutrition_type_id]':{
                    required:"Please select malnutrition_type.",
                },
                'data[MedicalSickRecord][diet]':{
                    required:"Please enter special diet.",
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
                // 'data[MedicalDeathRecord][pathologist_attach]': {
                //     required: true,
                // },

                // 'data[MedicalDeathRecord][attachment]': {
                //     required: true,
                // },

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
                // 'data[MedicalDeathRecord][pathologist_attach]': {
                //     required: "Please enter choose medical form.",
                   
                // },
                
                // 'data[MedicalDeathRecord][attachment]': {
                //     required: "Please choose postmotorm report.",
                // },
                
                
            }, 
    });    
});

// $('#malnutrition_id').on('change', function() {
//    if (this.value == 'State of Prisoner');
//   {
//     $("#state").show();
//   }
//   else
//   {
//     $("#state").hide();
//   }
// });
// function show(select_item) {
//    //var select_item = "State of Prisoner";
//         if (select_item == 2) {
//             state.style.visibility='visible';
//             state.style.display='block';
//             Form.fileURL.focus();
//         } 
//         if (select_item == 1) {
//             restricted_prisoner.style.visibility='visible';
//             restricted_prisoner.style.display='block';
//             Form.fileURL.focus();
//         } 
//         else
//          {
//             state.style.visibility='hidden';
//             state.style.display='none';
//             restricted_prisoner.visibility='hidden';
//             restricted_prisoner.display='none';
//         }
//     } 

// $('#medicalSeriousIllDiv').on('click', function() {
//     var urlDetails = "<?php //echo $this->Html->url(array('controller'=>'medicalRecords','action'=>'add')); ?>"
//     window.location.href = urlDetails+"####";
// });

$('#malnutrition_type_id').on('change', function() {
  //  alert( this.value ); // or $(this).val()
  if(this.value == "2") {
    $('#state').show();
    $('#restricted_prisoner').hide();
   
  }
  if(this.value == "1") {
    $('#restricted_prisoner').show();
    $('#state').hide();
  }
  if(this.value == "3") {
    $('#restricted_prisoner').hide();
    $('#state').hide();
  }
  if(this.value == "") {
    $('#restricted_prisoner').hide();
    $('#state').hide();
  }
});

     function resetData(id){
    //alert(id);
        $('#'+id)[0].reset();
        showMedicalChekupRecords();
    }
    function resetClinicalData(id){
    //alert(id);
        $('#'+id)[0].reset();
        showMedicalSickRecords();
    }
    function resetRecommendData(id){
    //alert(id);
        $('#'+id)[0].reset();
        showMedicalSeriousIllRecords();
    }
    function resetReleaseData(id){
    //alert(id);
        $('#'+id)[0].reset();
        showMedicalRelease();
    }
    function resetDeathData(id){
    //alert(id);
        $('#'+id)[0].reset();
        showMedicalDeathRecords();
    }
   function validateAge2(){
    
    var age1 = parseInt($("#age_from").val());
    var age2 = parseInt($("#age_to").val());
    if(age1>age2){
        alert("Please provide age greater than first age");
        //reset(age2);
        $("#age_to").val('');
        }
    }

    function showOther(value){
        if(value=='Others'){
            $("#MedicalSickRecordRestrictedWorkDesc").show();
            $("#MedicalSickRecordRestrictedWorkDesc").attr("required", true);
        }else{
            $("#MedicalSickRecordRestrictedWorkDesc").hide();
            $("#MedicalSickRecordRestrictedWorkDesc").val('');
            $("#MedicalSickRecordRestrictedWorkDesc").removeAttr("required");
        }
    }
    $('#MedicalSickRecordWardId').on('change', function(event) {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'MedicalRecords','action'=>'showWardCell'));?>';
        $.post(strURL,{assigned_ward_id:$(this).val()},function(data){
            if(data) { 
                $('#MedicalSickRecordWardCellId').html(data);
                <?php
                if(isset($this->data['MedicalSickRecord']['ward_cell_id']) && $this->data['MedicalSickRecord']['ward_cell_id']!=''){
                    ?>
                    $('#MedicalSickRecordWardCellId').val(<?php echo $this->data['MedicalSickRecord']['ward_cell_id']; ?>);
                    $('#MedicalSickRecordWardCellId').select2('val',<?php echo $this->data['MedicalSickRecord']['ward_cell_id']; ?>);
                    <?php
                }
                ?>
            }
        });
    });
    function showRestrictedPrisonerReamrks() {
        // alert(1);
        
        if ($('#MedicalSickRecordRestrictedPrisoner').is(":checked")) {
            $('#restricted_prisoner').show();
        }else{
             $('#restricted_prisoner').hide();

        }
    }
    function showCell(val){
        if(val=='In Patient'){
            $(".cell_div").show();
            $(".cell_div select").attr("required",true);
            $(".cell_div select").val('');
        }else{
            $('.cell_div').hide();
            $(".cell_div select").attr("required",false);
            $(".cell_div select").val('');
        }
    }
  // function checkDate(){
  //   //alert(1);
  //   var date1 = ("#folow_from").val();
  //   var date2 = ("#folow_to").val();
  //   alert(date1);
  //   if(date1>date1){
  //       alert("Please provide date less than first date");
        
  //       $("#folow_to").val('');
  //   }
  // }

//   function checkMax(division){
//    maxLen = 150; 
//    if (division.presenting_comp.value.length >= maxLen)
//     {
//         var msg = "You have reached your maximum limit of characters allowed";
//         alert(msg);

//     }

// }
   //  function validateAge1(){
   //  var age1 = $("#age_from").val();
   //  var age2 = $("#age_to").val();
   //  if(age1>age2){
   //      alert("Please provide age greater than first age");
   //      //reset(age2);
   //      $("#age_to").val('');
   //  }
   // }
//    $(document).ready(function() {
//   $('#attachment').on('change', function(evt) {
//     var length = this.files[0].size;
//     var fileInput = document.getElementById('attachment');
//     var filePath = fileInput.value;
//     //alert(length);
//     if(length>2000000){
//         alert("Please upload file within 2MB.");
//          fileInput.value = '';
//         return false;
//     }
//     else{
//         return true;
//     }
//   });
// });
function saveFunction(){
     $('#deathValidation').hide();
}


</script>
