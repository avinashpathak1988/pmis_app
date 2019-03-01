<style type="text/css">
    .rehabilitation-link{
        margin-top: 20px;
        text-align: center;
    }
    .rehabilitation-form1{
        /*display: none;
         margin-top: 20px;*/
    }
    .rehabilitation-form2{
        /*display: none;*/
    }
    .rehabilitation-form3{
        display: none;
    }
    .rehabilitation-form4{
        display: none;

    }
    .heading-rehabit-form{
        color: #462525;
    font-size: 14px;
    font-weight: bold;
    padding: 12px;
    line-height: 12px;
    margin: 0;
    background-color: #fff;
    border: 1px solid #ddd;
    margin-top: -4px;
    margin-bottom: 15px;
    }
    .informalEducationList{
        margin-top: 20px;
    }
    .span9{
        background-color: #fff;
        margin-top:16px;
    }
    .widget-box{
        margin-top:0px;
    }
</style>
<?php
// debug($this->data);
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Social Rehabilitation Education</h5>
                   <div style="display: none;">
                       <input type="hidden" name="" id="selectedForm" value="">
                   </div>
                </div>
                <div class="widget-content nopadding">
                
                    <div class="row-fluid" style="margin-top:0px;">

                    <div class="span3 rehabilitation-link" >
                       
                      <div class=" rehabilitation-form1">
                            <div class="heading-rehabit-form">Select Education Type:</div>
                            <form action="#">
                            <div class="">
                                
                                 <div class="form-group">
                                        
                                    <!-- <input id="informalCheck" type="radio" name="education_type" value="other"> Informal 
                                    <input id="formalCheck" type="radio" name="education_type" value="other"> Formal  -->
                                    <button id="informalCheck" class="btn btn-info btn-tab-full">Informal</button>
                                    <button  id="formalCheck" class="btn btn-success btn-tab-full">Formal Education</button>
                                    <button  id="NonFormalCheck" class="btn btn-danger btn-tab-full">Non Formal Education</button>
                                 </div>
                            </div>
                            
                            </form>
                        </div>
                    </div>
                    <div class="span9 ">
                        

                        <!-- form2 -->
                        <div class="rehabilitation-form2">
                            <div class="widget-box">
                                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                                     <h5>Informal Counseling</h5>
                                          <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                         </div>
                                </div>
                            </div>
                         
                            <?php echo $this->Form->create('InformalCouncelling',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="span6">
                                <div class="control-group">
                                <label class="control-label">Head Of Program:<?php echo $req; ?></label>
                                <div class="controls">
                                        <?php echo $this->Form->input('councellor_id',array('div'=>false,'label'=>false,'class'=>'form-control councellors_id ','type'=>'select','options'=>$councellorsList,'value'=>$user_id, 'empty'=>'-- Select  Counselor --','required'=>false,'id'=>'councellors_id'));?>
                                </div>
                                </div>


                                <div class="control-group">
                                            <label class="control-label">Prisoner Name :<?php echo $req; ?></label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control prisonerName','readonly'=>'readonly','type'=>'text','placeholder'=>'Enter Prisoner Name','id'=>'prisonerName','rows'=>1,'required'=>false));?>
                                            </div>
                                </div>
                                 <div class="control-group">
                                            <label class="control-label">Prisoners Input :<?php echo $req; ?></label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('opinion_by_prisoner',array('div'=>false,'label'=>false,'class'=>'form-control prisonerOpinion ','type'=>'textarea','placeholder'=>'Opinion given by Prisoner','id'=>'prisonerOpinion','rows'=>3,'required'=>false));?>
                                            </div>
                                </div>
                                <div class="control-group">
                                                <label class="control-label">End Date<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('end_date',array('div'=>false,'label'=>false,'class'=>'form-control to_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter End Date','required','id'=>'to_date_education'));?>
                                                </div>
                                </div>
                                <div class="control-group">
                                            <label class="control-label">Sponsor :<?php echo $req; ?></label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('sponser',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Sponsor','id'=>'sponser','rows'=>1,'required'=>false));?>
                                            </div>
                                </div>


                            </div>
                            <div class="span5">
                                
                                 <div class="control-group">
                                        <label class="control-label">Prisoner Number :<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$prisonersList, 'empty'=>'-- Select Prisoner no --','required'=>false,'id'=>'prisoner_no','onchange'=>"showFields(this.value)"));?>
                                        </div>
                                </div>

                                <div class="control-group">
                                                <label class="control-label"> Date Of Enrolment :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('date_of_councelling',array('div'=>false,'label'=>false,'class'=>'form-control date_of_councelling','readonly'=>'readonly','type'=>'text', 'placeholder'=>'Enter Date of   Counseling By','required','id'=>'doc'));?>
                                                </div>
                                </div>

                                <div class="control-group">
                                        <label class="control-label">Theme :<?php echo $req; ?></label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('theme_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$themelist, 'empty'=>'-- Select theme --','required'=>false,'id'=>'theme_id'));?>
                                        </div>
                                </div>  
                                <div class="control-group">
                                    <label class="control-label">Start Date<?php echo $req; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('start_date',array('div'=>false,'label'=>false,'class'=>'form-control from_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter Start Date','required','id'=>'from_date_education'));?>
                                    </div>
                                </div> 
                                 
                                
                                <div class="control-group">
                                <label class="control-label">Responsible Officer <?php echo $req; ?>:</label>
                                <div class="controls">
                                        <?php echo $this->Form->input('responsible_officer',array('div'=>false,'label'=>false,'class'=>'form-control responsible_officer ','type'=>'select','options'=>$responsibleOfficerList, 'empty'=>'-- Select officer --','required'=>false,'id'=>'responsible_officer'));?>
                                </div>
                                </div>      

                            </div>
                            
                            <div>
                             <div class="span12">
                                
                                <div class="form-actions" align="center">
                                    <button type="button" id="InformalCouncellingSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true", onclick="submitInformalCouncelling()">Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn', 'formnovalidate'=>true, 'onclick'=>"resetForm('InformalCouncellingIndexForm');"))?>
                                </div>
                            </div>
                            </div>
                            
                                
                                <?php echo $this->Form->end();?>
                        </div>

                         <!-- Form 2 End -->

                         <!-- form 3 -->

                         <div class="rehabilitation-form3">
                            <div class="widget-box">
                                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                                     <h5>Formal Education</h5>
                                          <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                         </div>
                                </div>
                            </div>
                         
                            <?php echo $this->Form->create('FormalEducation',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="span6">
                                <div class="control-group">
                                <label class="control-label"> Head Of Program :<?php echo $req; ?></label>
                                <div class="controls">
                                        <?php echo $this->Form->input('councellor_id',array('div'=>false,'label'=>false,'class'=>'form-control councellors_id ','type'=>'select','readonly'=>'readonly','options'=>$councellorsList, 'value'=>$user_id,'empty'=>'-- Select  Counselor --','required'=>false,'id'=>'councellors_id'));?>
                                </div>
                                </div>


                                <div class="control-group">
                                            <label class="control-label">Prisoner Name :<?php echo $req; ?></label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control prisonerName','readonly'=>'readonly','type'=>'text','placeholder'=>'Enter Prisoner Name','id'=>'prisonerName','rows'=>1,'required'=>false));?>
                                            </div>
                                </div>
                                 <div class="control-group">
                                            <label class="control-label"> Prisoners Input :<?php echo $req; ?></label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_opinion',array('div'=>false,'label'=>false,'class'=>'form-control  ','type'=>'textarea','placeholder'=>'Opinion given by Prisoner','id'=>'prisonerOpinion','rows'=>3,'required'=>false));?>
                                            </div>
                                </div>
                                
                               
                                


                            </div>
                            <div class="span5">
                                <div class="control-group">
                                        <label class="control-label">Prisoner Number :<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$prisonersList, 'empty'=>'-- Select Prisoner no --','required'=>false,'onchange'=>"showFields(this.value)",'id'=>'prisoner_no'));?>
                                        </div>
                                </div>

                                <div class="control-group">
                                                <label class="control-label">Date Of Enrolment<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('doc',array('div'=>false,'label'=>false,'class'=>'form-control maxCurrentDate','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter Date of  Counseling ','required','id'=>'doc'));?>
                                                </div>
                                </div>

                                <div class="control-group">
                                        <label class="control-label">School Program<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('school_program_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$schoolProgramList, 'empty'=>'-- Select school program --','required'=>false,'id'=>'school_program_id','onchange'=>"getSubCategorySchoolprogram(this.value)"));?>
                                        </div>
                                </div>  
                                 <div class="control-group">
                                        <label class="control-label">Sub Category School Program<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('sub_school_program_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$subSchoolProgramList, 'empty'=>'-- Select sub school program --','required'=>false,'id'=>'sub_school_program_id','onchange'=>"getSubSubCategorySchoolprogram(this.value)"  ));?>
                                        </div>
                                </div>
                                 <div class="control-group">
                                        <label class="control-label">Sub sub Category School Program<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('sub_sub_school_program_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$subSubSchoolProgramList, 'empty'=>'-- Select sub subcategory school program --','required'=>false,'id'=>'sub_sub_school_program_id'));?>
                                        </div>
                                </div>
                                         

                            </div>
                            
                            <div>
                             <div class="span12">
                                
                                <div class="form-actions" align="center">
                                    <button type="button" id="FormalEducationSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true" onclick="submitFormalEducation()">Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn', 'formnovalidate'=>true, 'onclick'=>"resetForm('FormalEducationIndexForm');"))?>
                                </div>
                            </div>
                            </div>
                            
                                
                                <?php echo $this->Form->end();?>
                        </div>

                        <!-- form3 end -->



                         <!-- form 4 -->

                         <div class="rehabilitation-form4">
                            <div class="widget-box">
                                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                                     <h5>Non Formal Education</h5>
                                          <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                         </div>
                                </div>
                            </div>
                         
                            <?php echo $this->Form->create('NonFormalEducation',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="span6">
                                <div class="control-group">
                                <label class="control-label"> Head Of Program<?php echo $req; ?>:</label>
                                <div class="controls">
                                        <?php echo $this->Form->input('councellor_id',array('div'=>false,'label'=>false,'class'=>'form-control councellors_id ','type'=>'select','readonly'=>'readonly','options'=>$councellorsList,'value'=>$user_id, 'empty'=>'-- Select   Counselor --','required'=>false,'id'=>'councellors_id'));?>
                                </div>
                                </div>


                                <div class="control-group">
                                            <label class="control-label">Prisoner Name :<?php echo $req; ?></label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control prisonerName ','readonly'=>'readonly','type'=>'text','placeholder'=>'Enter Prisoner Name','id'=>'prisonerName','rows'=>1,'required'=>false));?>
                                            </div>
                                </div>
                                 <div class="control-group">
                                            <label class="control-label">Prisoners Input :<?php echo $req; ?></label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_opinion',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'textarea','placeholder'=>'Opinion given by Prisoner','id'=>'prisonerOpinion','rows'=>3,'required'=>false));?>
                                            </div>
                                </div>
                                <div class="control-group">
                                            <label class="control-label">Remarks :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('remarks',array('div'=>false,'label'=>false,'class'=>'form-control remarks','type'=>'textarea','placeholder'=>'Enter Remarks(optional)','id'=>'remarks','rows'=>3,'required'=>false));?>
                                            </div>
                                </div>
                                <div class="control-group">
                                            <label class="control-label">Awarded :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('awarded',array('div'=>false,'label'=>false,'class'=>'form-control awarded','type'=>'textarea','placeholder'=>'Awarded','id'=>'awarded','rows'=>1,'required'=>false));?>
                                            </div>
                                </div>
                               
                                


                            </div>
                            <div class="span5">
                                <div class="control-group">
                                        <label class="control-label">Prisoner Number :<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$prisonersList, 'empty'=>'-- Select Prisoner no --','required'=>false,'onchange'=>"showFields(this.value)",'id'=>'prisoner_no'));?>
                                        </div>
                                </div>

                                <div class="control-group">
                                                <label class="control-label">Date Of Enrolment  <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('doc',array('div'=>false,'label'=>false,'class'=>'form-control maxCurrentDate','type'=>'text', 'readonly'=>'readonly','placeholder'=>'Enter Date of  Counseling','required','id'=>'doc'));?>
                                                </div>
                                </div>

                                <div class="control-group">
                                        <label class="control-label">Non Formal Program<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('non_formal_program_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$nonFormalProgramList, 'empty'=>'-- Select  program --','required'=>false,'id'=>'non_formal_program_id' , 'onchange'=>"getProgramModuleList(this.value)"));?>
                                        </div>
                                </div>  
                                 <div class="control-group">
                                        <label class="control-label">Module<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('module_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$moduleList, 'empty'=>'-- Select module --','required'=>false,'id'=>'module_id','onchange'=>"getModuleStageList(this.value)" ));?>
                                        </div>
                                </div>
                                 <div class="control-group">
                                        <label class="control-label">Module stage<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('module_stage_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$moduleStageList, 'empty'=>'-- Select module stage --','required'=>false,'id'=>'module_stage_id'));?>
                                        </div>
                                </div>
                                         

                            </div>
                            
                            <div>
                             <div class="span12">
                                
                                <div class="form-actions" align="center">
                                    <button type="button" id="NonFormalEducationSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true" onclick="submitNonFormalEducation();" >Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn', 'formnovalidate'=>true, 'onclick'=>"resetForm('NonFormalEducationIndexForm');"))?>
                                </div>
                            </div>
                            </div>
                            
                                
                                <?php echo $this->Form->end();?>
                        </div>

                        <!-- form4 end -->
                    </div> 
                    
        
                    <div class="span12" style="margin-left:0px;">
                        <div class="respo" id="respo">
                        
                    </div>
                    </div>
                    <div class="span12" style="margin-left: 0px;">
                            <div class="widget-content nopadding">
               <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                                     <h5> Search Prisoner</h5>
									 <a class="" id="collapsedSearch" href="#searchPrisonerTwo" data-toggle="collapse">
										<span class="icon"><i class="icon-search" style="color:#000;"></i></span>
									 </a>
                                     <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                    </div>
               </div>
                        <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
						<div id="searchPrisonerTwo" class="row collapse" style="height:auto;">
                          <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner No. :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('sprisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric', 'type'=>'text','placeholder'=>'Search Prisoner No.','id'=>'sprisoner_no', 'style'=>'width:200px;'));?>
                                    </div>
                                </div>
                                <div class="control-group start_date_search">
                                                <label class="control-label">Start Date<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('sprisoner_start_date',array('div'=>false,'label'=>false,'class'=>'form-control from_date','id'=>'from_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter Start Date'));?>
                                                </div>
                                </div> 
                                <div class="control-group theme_search">
                                        <label class="control-label">Theme :</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('stheme',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$themelist, 'empty'=>'-- Select theme --','required'=>false,'id'=>'theme_id'));?>
                                        </div>
                                </div>
                                
                          </div>
                          <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner Name. :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('sprisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 ', 'type'=>'text','placeholder'=>'Search Prisoner Name.','id'=>'sprisoner_name', 'style'=>'width:200px;'));?>
                                    </div>
                                </div>
                                <div class="control-group end_date_search">
                                                <label class="control-label">End Date<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('sprisoner_end_date',array('div'=>false,'label'=>false,'class'=>'form-control to_date','id'=>'to_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter End Date'));?>
                                                </div>
                                </div>
                                
                          </div>
                          <div class="span12 add-top" align="center" valign="center">
                                <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'btnsearchcash', 'onclick'=>"showListSearch();"))?>
                                <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchDataAjaxForm')"))?>
                            </div> 
                    </div>    
          
            </div>  
                    </div>
                    <div class="span12" style="margin-left:0px;">
                        <div class="informalEducationList" id="informalEducationList">
                        
                    </div>
                    </div>
                     <div class="span12" style="margin-left:0px;">
                        <div class="FormalEducationList" id="FormalEducationList">
                        
                    </div>
                    </div>
                    <div class="span12" style="margin-left:0px;">
                        <div class="NonFormalEducationList" id="NonFormalEducationList">
                        
                    </div>
        
                     
                    </div>                

                </div>
            </div>  
        </div> 
        </div>
        
        

    </div>  
</div>
<?php
$ajaxUrlInformalList = $this->Html->url(array('controller'=>'Education','action'=>'dataAjax'));
$ajaxUrlFormalList = $this->Html->url(array('controller'=>'Education','action'=>'formalDataAjax'));
$ajaxUrlNonFormalList = $this->Html->url(array('controller'=>'Education','action'=>'NonFormalDataAjax'));

$ajaxUrlPrisonerDetails = $this->Html->url(array('controller'=>'Education','action'=>'getPrisonerDetail'));
$ajaxUrlInformalDetails = $this->Html->url(array('controller'=>'Education','action'=>'getInformalDetails'));
$ajaxUrlInformalFormSubmit = $this->Html->url(array('controller'=>'Education','action'=>'submitInformalForm'));
$ajaxUrlFormalFormSubmit = $this->Html->url(array('controller'=>'Education','action'=>'submitFormalForm'));
$ajaxUrlNonFormalFormSubmit =$this->Html->url(array('controller'=>'Education','action'=>'submitNonFormalForm'));
$ajaxUrlgetSubCategorySchoolprogram =$this->Html->url(array('controller'=>'Education','action'=>'getSubCategorySchoolprogram'));
$ajaxUrlgetSubSubCategorySchoolprogram = $this->Html->url(array('controller'=>'Education','action'=>'getSubSubCategorySchoolprogram'));
$ajaxUrlgetProgramModuleList = $this->Html->url(array('controller'=>'Education','action'=>'getModuleList'));
$ajaxUrlgetModuleStageList = $this->Html->url(array('controller'=>'Education','action'=>'getModuleStageList'));


?>

<script type="text/javascript">

    $( document ).ready(function() {
    //console.log( "ready!" );

$("#from_date_search").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                maxDate:'0',
                onSelect: function( selectedDate ) {
                    $( "#to_date_search" ).datepicker( "option", "minDate", selectedDate );
                },
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true
            });
            $("#to_date_search").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                onSelect: function( selectedDate ) {
                    $( "#from_date_search" ).datepicker( "option", "maxDate", selectedDate );
                },
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true
            }); 
// $("#from_date_search").datepicker({
//                 defaultDate: new Date(),
//                 changeMonth: true,
//                 numberOfMonths: 1,
//                 maxDate:'0',
//                 onSelect: function( selectedDate ) {
//                     alert(selectedDate);
//                     $( "#to_date_search" ).datepicker( "option", "minDate", selectedDate );
//                    //$("#StartDate").datepicker("option","maxDate", selected)
//                 },
//                 dateFormat: 'dd-mm-yy',
//                 changeMonth: true,
//                 changeYear: true
//             });
// $("#to_date_search").datepicker({
//                 defaultDate: new Date(),
//                 changeMonth: true,
//                 numberOfMonths: 1,
//                 minDate:'0',
//                 onSelect: function( selectedDate ) {
//                     $( "#from_date_search" ).datepicker( "option", "maxDate", selectedDate );
//                 },
//                 dateFormat: 'dd-mm-yy',
//                 changeMonth: true,
//                 changeYear: true
//             });

$('.date_of_councelling').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true,
                startDate: new Date(),
                                                           
            }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('.from_date').datepicker('setStartDate', minDate);
                 $(this).datepicker('hide');
                 $(this).blur();
            });
// $(".date_of_councelling").datepicker({
//                 defaultDate: new Date(),
//                 changeMonth: true,
//                 numberOfMonths: 1,
//                 maxDate:'0',
//                 onSelect: function( selectedDate ) {
//                     var minDate = new Date(selected.date.valueOf());
//                     $('.from_date_education').datepicker('setStartDate', minDate);
//                      $(this).datepicker('hide');
//                      $(this).blur();
                    
//                     alert(11);
//                 },
//                 dateFormat: 'dd-mm-yy',
//                 changeMonth: true,
//                 changeYear: true
//             });
// $(".from_date_education").datepicker({
//                 defaultDate: new Date(),
//                 changeMonth: true,
//                 numberOfMonths: 1,
//                 onSelect: function( selectedDate ) {
//                     $( ".date_of_councelling" ).datepicker( "option", "maxDate", selectedDate );
//                     $( ".to_date_education" ).datepicker( "option", "minDate", selectedDate );

//                 },
//                 dateFormat: 'dd-mm-yy',
//                 changeMonth: true,
//                 changeYear: true
//             });
$(".to_date_education").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                onSelect: function( selectedDate ) {
                    $( ".from_date_education" ).datepicker( "option", "maxDate", selectedDate );
                },
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true
            });

            $('.from_date').datepicker({
                format: 'dd-mm-yyyy',
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
       
          
        $('#collapsedSearch').addClass('collapsed');
        $('#searchPrisonerTwo').removeClass('in');
        $('#searchPrisonerTwo').css('height','0px');

         resetForm('InformalCouncellingIndexForm');
     showDataInformal();
         
         
        $('#educationLink').click(function(event){
            event.preventDefault();
            $('.rehabilitation-form1').css('display','block');
        });

        $("#informalCheck").click(function(){
            $('.rehabilitation-form4').css('display','none');
            $('.rehabilitation-form3').css('display','none');
            $('.rehabilitation-form2').css('display','block');
            $('.start_date_search').css('display','block');
            $('.end_date_search').css('display','block');
            $('.theme_search').css('display','block');
         showDataInformal();
         resetForm('InformalCouncellingIndexForm');
         return false;
        });
        $("#formalCheck").click(function(){
            $('.rehabilitation-form4').css('display','none');
            $('.rehabilitation-form3').css('display','block');
            $('.rehabilitation-form2').css('display','none');
            $('.start_date_search').css('display','none');
            $('.end_date_search').css('display','none');
            $('.theme_search').css('display','none');

            showDataFormal();
            resetForm('FormalEducationIndexForm');
            return false;
        });
        $("#NonFormalCheck").click(function(){
            
            $('.rehabilitation-form4').css('display','block');
            $('.rehabilitation-form3').css('display','none');
            $('.rehabilitation-form2').css('display','none');
            $('.start_date_search').css('display','none');
            $('.end_date_search').css('display','none');
            $('.theme_search').css('display','none');
            showDataNonFormal();
            resetForm('NonFormalEducationIndexForm');
            return false;
        });
        
         

});

function showListSearch(){
    var informalVisible =$('#informalEducationList').is(":visible");
    var formalVisible =$('#FormalEducationList').is(":visible");
    var nonformalVisible =$('#NonFormalEducationList').is(":visible");
    if(informalVisible){
        var url ='<?php echo $ajaxUrlInformalList?>';
    }else if(formalVisible){
        var url ='<?php echo $ajaxUrlFormalList?>';
    }else if(nonformalVisible){
        var url ='<?php echo $ajaxUrlNonFormalList?>';
    }

    $.post(url, $('#SearchIndexForm').serialize(), function(res) {
        if (res) {
                if(informalVisible){
                    $('#informalEducationList').html(res);
                }else if(formalVisible){
                    $('#FormalEducationList').html(res);
                }else if(nonformalVisible){
                    $('#NonFormalEducationList').html(res);
                }
            
             }
    });
        }
function getProgramModuleList(id){
        var url ='<?php echo $ajaxUrlgetProgramModuleList?>';
    $.post(url, {'id':id}, function(res) {
        if (res) {

            //$('#respo').html(res);
            //alert('hi');
            $('#module_id').html(res);
            //console.log(res);
             }
    });
        }
      function  getModuleStageList(id){
             var url ='<?php echo $ajaxUrlgetModuleStageList?>';
    $.post(url, {'id':id}, function(res) {
        if (res) {

            //$('#respo').html(res);
            //alert('hi');
            $('#module_stage_id').html(res);
            //console.log(res);
             }
    });
        }
    function getSubCategorySchoolprogram(school_program_id){
        var url ='<?php echo $ajaxUrlgetSubCategorySchoolprogram?>';
    $.post(url, {'school_program_id':school_program_id}, function(res) {
        if (res) {

            //$('#respo').html(res);
            //alert('hi');
            $('#sub_school_program_id').html(res);
            //console.log(res);
             }
    });
        }

function getSubSubCategorySchoolprogram(school_program_id){
        var url ='<?php echo $ajaxUrlgetSubSubCategorySchoolprogram?>';
    $.post(url, {'school_program_id':school_program_id}, function(res) {
        if (res) {

            //$('#respo').html(res);
            //alert('hi');
            $('#sub_sub_school_program_id').html(res);
            //console.log(res);
             }
    });
        }

       function submitInformalCouncelling(){

        // $("#InformalCouncellingIndexForm").submit();

        if($("#InformalCouncellingIndexForm").valid()){
                
                    $('#selectedForm').val('informal');
                    var url ='<?php echo $ajaxUrlInformalFormSubmit?>';
                    $.post(url, $('#InformalCouncellingIndexForm').serialize(), function(res) {
                    if(res.trim()=='SUCC'){
                        resetForm('InformalCouncellingIndexForm');
                        dynamicAlertBox('Message', 'Informal Counseling saved successfully !');
                        showListSearch();
                    }else if (res.trim()=='PROB') {

                        dynamicAlertBox('Message', 'Informal Councelling Already exits in application!');
                    }else{
                        dynamicAlertBox('Message', 'Informal Counseling is not saved !');
                    }

                    

                     //showDataInformal();
                    
                        // document.location = "gfdgfdg";
                       // $('#respo').html(res);
                        // window.location.reload();

                        //alert('hi');
                        
                });
        }
        
    //return false;
        }
        function submitFormalEducation(){
        $('#selectedForm').val('formal');
        // $("#FormalEducationIndexForm").submit();
        if($("#FormalEducationIndexForm").valid()){
            var url ='<?php echo $ajaxUrlFormalFormSubmit?>';
            $.post(url, $('#FormalEducationIndexForm').serialize(), function(res) {
                if(res.trim()=='SUCC'){
                    showDataFormal();
                    resetForm('FormalEducationIndexForm');
                    dynamicAlertBox('Message', 'Formal Education saved successfully !');
                    // $('#respo').html(res);
                }else if (res.trim()=='PROB') {
                    dynamicAlertBox('Message', 'Formal Education Already exits in application!');
                }else{
                    dynamicAlertBox('Message', 'Formal Education is not saved !');
                }
                
            });
        }
       
    //return false;
        }

        function submitNonFormalEducation(){
              

        $('#selectedForm').val('nonformal');
        // $("#NonFormalEducationIndexForm").submit();
        if($("#NonFormalEducationIndexForm").valid()){
                var url ='<?php echo $ajaxUrlNonFormalFormSubmit?>';
                $.post(url, $('#NonFormalEducationIndexForm').serialize(), function(res) {
                    if(res.trim()=='SUCC'){
                        showDataNonFormal();
                        resetForm('NonFormalEducationIndexForm');
                        dynamicAlertBox('Message', 'Non Formal Education saved successfully !');
                        // $('#respo').html(res);
                    }else if (res.trim()=='PROB') {
                        dynamicAlertBox('Message', 'Non Formal Education Already exits in application!');
                    }else{
                        dynamicAlertBox('Message', 'Non Formal Education is not saved !');
                    }
                    
                       
                        //alert('hi');
                         
                });
        }
        
     //return false;
        }

     function showFields(prisonerID){     
        if(prisonerID != ' ' && prisonerID != 0 ){
                 
         getInformalDetails(prisonerID);

            var url ='<?php echo $ajaxUrlPrisonerDetails ?>';
            $.post(url,{'prisoner_id':prisonerID}, function(res) {
                if (res) {
                    //console.log(res);
                    $('.prisonerName').val(res);
                 }
            });
        }else{

            $('.prisonerName').val('');

               
        }
    

    
}

   function getInformalDetails(prisonerID){     
    var url ='<?php echo $ajaxUrlInformalDetails?>';
    $.post(url,{'prisoner_id':prisonerID}, function(res) {
        if (res) {
            
                var myObj = JSON.parse(res);
            if(myObj != ''){
               var councellorId = myObj.InformalCouncelling.councellor_id;
               var prisoneropinion = myObj.InformalCouncelling.opinion_by_prisoner;

               $('.prisonerOpinion').val(prisoneropinion);
               //$('.councellors_id').val(councellorId);


            }else{
                $('.prisonerOpinion').val('');
              // $('.councellors_id').val('');
            }
            
            
         }
    });
}
    function showDataInformal(){  
        
    var url ='<?php echo $ajaxUrlInformalList?>';
    $.post(url,{'prisoner_id':1}, function(res) {
        if (res) {

            $('#informalEducationList').html(res);
            $('#NonFormalEducationList').css('display','none');
        $('#FormalEducationList').css('display','none');
        $('#informalEducationList').css('display','block');
            //show Check Box
            //showCheckBox();
         }else{
            $('#informalEducationList').html('');



         }
    });
}

  



function showDataFormal(){     
    

    var url ='<?php echo $ajaxUrlFormalList?>';
    $.post(url,  {'prisoner_id':1},function(res) {
        if (res) {
            $('#FormalEducationList').html(res);
            $('#NonFormalEducationList').css('display','none');
            $('#FormalEducationList').css('display','block');
            $('#informalEducationList').css('display','none');
            //show Check Box
            //showCheckBox();
         }else{
            $('#FormalEducationList').html('');

         }
    });
}

function showDataNonFormal(){    
    

    var url ='<?php echo $ajaxUrlNonFormalList?>';
    $.post(url,{'prisoner_id':1}, function(res) {
        if (res) {
            $('#NonFormalEducationList').html(res);
            $('#NonFormalEducationList').css('display','block');
            $('#FormalEducationList').css('display','none');
            $('#informalEducationList').css('display','none');
            //show Check Box
            //showCheckBox();
         }else{
            $('#NonFormalEducationList').html('');

         }
    });
}
$(function(){

    

$("#InformalCouncellingIndexForm").validate({ 
        rules: {  
                'data[InformalCouncelling][councellor_id]': {
                    required: true,
                },
                'data[InformalCouncelling][prisoner_no]':{
                    required: true,

                },
                'data[InformalCouncelling][prisoner_name]': {
                    required: true,
                },
                'data[InformalCouncelling][date_of_councelling]': {
                    required: true,

                },
                'data[InformalCouncelling][opinion_by_prisoner]': {
                    required: true,
                },
                'data[InformalCouncelling][theme_id]':{
                    required: true,
                },
                'data[InformalCouncelling][start_date]': {
                    required: true,

                },
                'data[InformalCouncelling][end_date]':{
                    required: true,

                },
                'data[InformalCouncelling][responsible_officer]':{
                    required: true,

                },
                'data[InformalCouncelling][sponser]':{
                    required: true,

                }
                
            },
            messages: {
                'data[InformalCouncelling][councellor_id]': {
                    required: "Please select  Counselor.",
                },
                'data[InformalCouncelling][prisoner_no]':{
                    required: "Please select Prisoner Number.",
                },
                'data[InformalCouncelling][prisoner_name]':{
                    required: "Please fill Name",
                },
                'data[InformalCouncelling][date_of_councelling]':{
                    required: 'Please select date of Counseling ',
                },
                'data[InformalCouncelling][opinion_by_prisoner]':{
                    required: 'Please enter prisoners opinion',
                },
                'data[InformalCouncelling][theme_id]':{
                    required: 'Please select Theme',
                },
                'data[InformalCouncelling][start_date]':{
                    required: 'Please select start date ',
                },
                'data[InformalCouncelling][end_date]':{
                    required: 'Please select end date ',

                },
                'data[InformalCouncelling][responsible_officer]':{
                    required: 'Please select responsible officer',     
                    
                },
                'data[InformalCouncelling][sponser]':{
                    required: "Please Fill Sponsor",
                    
                }
            }
    });

    $("#FormalEducationIndexForm").validate({ 
            rules: {  
                'data[FormalEducation][councellor_id]': {
                    required: true,
                },
                'data[FormalEducation][prisoner_no]':{
                    required: true,

                },
                'data[FormalEducation][prisoner_name]': {
                    required: true,
                },
                'data[FormalEducation][date_of_councelling]': {
                    required: true,

                },
                'data[FormalEducation][opinion_by_prisoner]':{
                    required: true
                },
                'data[FormalEducation][school_program_id]':{
                    required: true
                },
                'data[FormalEducation][sub_school_program_id]':{
                    required: true
                },
                'data[FormalEducation][sub_sub_school_program_id]':{
                    required: true
                }
                
                
            },
            messages: {
                'data[FormalEducation][councellor_id]': {
                    required: "Please select  Counselor.",
                },
                'data[FormalEducation][prisoner_no]':{
                    required: "Please select Prisoner Number.",
                },
                'data[FormalEducation][prisoner_name]':{
                    required: "Please fill Name",
                },
                'data[FormalEducation][date_of_councelling]':{
                    required: 'Please select date of Counselling',
                },
                'data[FormalEducation][opinion_by_prisoner]':{
                    required: 'Please enter prisoners opinion',
                },
                'data[FormalEducation][school_program_id]':{
                    required:  "Please select Program.",
                },
                'data[FormalEducation][sub_school_program_id]':{
                    required:  "Please select sub program.",
                },
                'data[FormalEducation][sub_sub_school_program_id]':{
                    required:  "Please select sub program.",
                }
            }

    });

    $("#NonFormalEducationIndexForm").validate({ 
            rules: {  
                'data[NonFormalEducation][councellor_id]': {
                    required: true,
                },
                'data[NonFormalEducation][prisoner_no]':{
                    required: true,

                },
                'data[NonFormalEducation][prisoner_name]': {
                    required: true,
                },
                'data[NonFormalEducation][date_of_councelling]': {
                    required: true,

                },
                'data[NonFormalEducation][prisoner_opinion]':{
                    required: true
                },
                'data[NonFormalEducation][non_formal_program_id]':{
                    required: true
                },
                'data[NonFormalEducation][module_id]':{
                    required: true
                },
                'data[NonFormalEducation][module_stage_id]':{
                    required: true
                },
                'data[NonFormalEducation][awarded]':{
                    required: false
                }
                
                
            },
            messages: {
                'data[NonFormalEducation][councellor_id]': {
                    required: "Please select  Counselor.",
                },
                'data[NonFormalEducation][prisoner_no]':{
                    required: "Please select Prisoner Number.",
                },
                'data[NonFormalEducation][prisoner_name]':{
                    required: "Please fill Name",
                },
                'data[NonFormalEducation][date_of_councelling]':{
                    required: 'Please select date of  Counseling',
                },
                'data[NonFormalEducation][prisoner_opinion]':{
                    required: 'Please enter prisoners opinion',
                },
                'data[NonFormalEducation][non_formal_program_id]':{
                    required:  "Please select Program.",
                },
                'data[NonFormalEducation][module_id]':{
                    required:  "Please select program module.",
                },
                'data[NonFormalEducation][module_stage_id]':{
                    required:  "Please select Module stage.",
                },
                'data[NonFormalEducation][awarded]':{
                    required:  "Please Enter Awarded .",
                }
            }

    });

    


});
function resetData(id){
    $('#'+id)[0].reset();

    //$('select').select2({minimumResultsForSearch: Infinity});
    $('select').select2().select2("val", null);
            $('#NonFormalEducationList').html('');
            $('#FormalEducationList').html('');
            $('#NonFormalEducationList').html('');

            $('.prisonerName').val('');

    
}


</script>