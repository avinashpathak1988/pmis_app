<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                
                    <h5>Formal Education</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Formal education list'), array('action' => 'formalEducation'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                	<div class="row-fluid">
                        <div class="span12 ">
                        <!-- form2 -->
                        <div class="aftercareform">
                            <?php echo $this->Form->create('FormalEducation',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'hidden'));?>
                                <div class="control-group">
                            <div class="span5">

                            	<div class="control-group">
                                <label class="control-label">Program Head:<?php echo $req; ?></label>
                                <div class="controls">
                                        <?php echo $this->Form->input('program_head_id',array('div'=>false,'label'=>false,'class'=>'form-control program_head_id ','type'=>'select','options'=>$councellorsList,'empty'=>'-- Select program head --','required','id'=>'program_head_id'));?>
                                </div>
                                </div>

                                
                                <div class="control-group">
                                                <label class="control-label"> Date Of Enrolment :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('date_of_enrolment',array('div'=>false,'label'=>false,'class'=>'form-control date_of_enrolment','readonly'=>'readonly','type'=>'text', 'placeholder'=>'Enter Date of Enrolment','required','id'=>'date_of_enrolment'));?>
                                                </div>
                                </div>
                                <div class="control-group">
                                                <label class="control-label">Start Date<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('start_date',array('div'=>false,'label'=>false,'class'=>'form-control from_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter End Date','required','id'=>'start_date'));?>
                                                </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Prisoners Input :<?php echo $req; ?></label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoners_input',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Prisoners Input','id'=>'prisoners_input','rows'=>3,'required','maxlength'=>1000));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Responsible Officer  :</label>
                                    
                                    <div class="controls">
                                        <?php echo $this->Form->input('responsible_officer',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Responsible officer','id'=>'responsible_officer','required'=>false,'maxlength'=>100));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                            <label class="control-label">Sponsor :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('sponsor',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'','id'=>'sponsor','rows'=>1,'required'=>false));?>
                                            </div>
                                </div>
                                
                            </div>
                            <div class="span5">
                            	<div class="control-group">
                                    <label class="control-label">Prisoner Number :<?php echo $req; ?>:</label>
                                    <div class="controls">
                                            <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$prisonersList,'onchange'=>'showFields(this.value)', 'empty'=>'-- Select Prisoner no --','required','id'=>'prisoner_no'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                            <label class="control-label">Prisoner name :<?php echo $req; ?></label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control prisonerName ','type'=>'text','readonly'=>'readonly','placeholder'=>'','id'=>'prisoner_name','rows'=>1,'required'));?>
                                            </div>
                                </div>
                                
                                <div class="control-group">
                                                <label class="control-label">End Date<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('end_date',array('div'=>false,'label'=>false,'class'=>'form-control to_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter End Date','required','id'=>'end_date'));?>
                                                </div>
                                </div>
<!-- 
                                <div class="control-group theme_search">
                                        <label class="control-label">Theme :</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('themes',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','multiple'=>'multiple','options'=>$themelist, 'empty'=>'-- Select theme --','required'=>false,'id'=>'themes'));?>
                                        </div>
                                </div> -->
                                <div class="control-group">
                                        <label class="control-label">School Program<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('school_program_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$schoolProgramList, 'empty'=>'-- Select school program --','required','id'=>'school_program_id','onchange'=>"getSubCategorySchoolprogram(this.value)"));?>
                                        </div>
                                </div>  
                                 <div class="control-group">
                                        <label class="control-label">Sub Category School Program<?php echo $req; ?>:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('sub_school_program_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$subSchoolProgramList, 'empty'=>'-- Select sub school program --','required','id'=>'sub_school_program_id','onchange'=>"getSubSubCategorySchoolprogram(this.value)"  ));?>
                                        </div>
                                </div>
                                 <div class="control-group">
                                        <label class="control-label">Sub sub Category School Program:</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('sub_category_school_program_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$subSubSchoolProgramList, 'empty'=>'-- Select sub subcategory school program --','required'=>false,'id'=>'sub_sub_school_program_id'));?>
                                        </div>
                                </div>
                                
                                    

                            </div>
                            
                            <div> 
                            <span class="span12">
                                <div class="form-actions" align="center">
                                    <button type="button" id="FormalEducationSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true", onclick="submitFormalEducation()">Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn','onclick'=>"resetData('FormalEducationAddFormalEducationForm')", 'formnovalidate'=>true))?>
                                </div>
                            </span>
                                </div>
                            

                                <?php echo $this->Form->end();?>

                        </div>
                    </div> 
                    </div> <!--  end row fluid -->
                
                
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
<?php

$ajaxUrlPrisonerDetails = $this->Html->url(array('controller'=>'Education','action'=>'getPrisonerDetail'));
$ajaxUrlSaveFormalEducation = $this->Html->url(array('controller'=>'CorrectionEducationProgrammes','action'=>'saveFormalEducation'));
$ajaxUrlgetSubCategorySchoolprogram =$this->Html->url(array('controller'=>'Education','action'=>'getSubCategorySchoolprogram'));
$ajaxUrlgetSubSubCategorySchoolprogram = $this->Html->url(array('controller'=>'Education','action'=>'getSubSubCategorySchoolprogram'));

?>
    $( document ).ready(function() {
		$('.date_of_enrolment').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true,
                startDate: new Date(),
                                                           
            }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('.from_date').datepicker('setStartDate', minDate);
                 $(this).datepicker('hide');
                 $(this).blur();
            });
            $('#prisoner_no').select2();
            $('#responsible_officer').select2();
            

            
    });  

    function showFields(prisonerID){     
        if(prisonerID != ' ' && prisonerID != 0 ){
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
    function submitFormalEducation(){
        if($("#FormalEducationAddFormalEducationForm").valid()){
            var prisonerID = $('#prisoner_no').val();
            var url ='<?php echo $ajaxUrlSaveFormalEducation?>';

        
            $.post(url, $('#FormalEducationAddFormalEducationForm').serialize(), function(res) {
                if (res.trim()=='Success') {
                    dynamicAlertBox('Message', 'Socialisation Program saved successfully !');
                    //showListSearch();
                    //resetForm('AftercareIndexForm');
                    window.location = '<?php echo $this->webroot;?>CorrectionEducationProgrammes/formalEducation';
                }else{
                    dynamicAlertBox('Message', 'Socialisation Program not saved !');
                }
            });
        }
        
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
    $(function(){
        $("#FormalEducationAddFormalEducationForm").validate({
     
            ignore: "",
                rules: {  
                    
                    'data[FormalEducation][program_head_id]': {
                        required: true,
                    },
                    'data[FormalEducation][prisoner_no]': {
                        required: true,
                    },
                    'data[FormalEducation][date_of_enrolment]': {
                        required: true,
                    },
                    'data[FormalEducation][start_date]': {
                        required: true,
                    },
                    'data[FormalEducation][prisoner_name]': {
                        required: true,
                    },
                    'data[FormalEducation][end_date]': {
                        required: true,
                    },
                    'data[FormalEducation][prisoners_input]':{
                        required: true,
                    },
                    'data[FormalEducation][school_program_id]':{
                        required: true,
                    },
                    'data[FormalEducation][sub_school_program_id]':{
                        required: true,
                    },
                    
                    
                    
                },
                messages: {
                    'data[SocialisationProgram][program_head_id]': {
                        required: "Please select program head.",
                    },
                    'data[SocialisationProgram][prisoner_no]': {
                        required: "Please select prisoner number",
                    },
                    'data[SocialisationProgram][date_of_enrolment]': {
                        required: "Please Enter date of enrolment",
                    },
                    'data[SocialisationProgram][start_date]': {
                        required: "Please Enter start date",
                    },
                    'data[SocialisationProgram][prisoner_name]': {
                        required: "Please Enter prisoner name",
                    },
                    'data[SocialisationProgram][end_date]': {
                        required: "Please Enter end Date",
                    },
                    'data[SocialisationProgram][prisoners_input]':{
                        required: "Please Enter Prisoner Input",
                    },
                    'data[FormalEducation][school_program_id]':{
                        required: "Please select school program",
                    },
                    'data[FormalEducation][sub_school_program_id]':{
                        required: "please select sub school program",
                    },
                    
                },
            });
    }); 

    function resetData(id){
        $('#'+id)[0].reset();

        //$('select').select2({minimumResultsForSearch: Infinity});
        $('select').select2().select2("val", null);
        
    }
</script>            
