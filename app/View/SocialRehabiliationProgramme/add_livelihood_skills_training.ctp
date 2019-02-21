<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Livelihood Skills Training</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Livelihood skills training list'), array('action' => 'livelihoodSkillsTraining'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                	<div class="row-fluid">
                        <div class="span12 ">
                        <!-- form2 -->
                        <div class="aftercareform">
                            <?php echo $this->Form->create('LivelihoodSkillsTraining',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'hidden'));?>
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
                                                    <?php echo $this->Form->input('date_of_enrolment',array('div'=>false,'label'=>false,'class'=>'form-control date_of_enrolment','readonly'=>'readonly','type'=>'text', 'placeholder'=>'Enter Date of   Counseling By','required','id'=>'date_of_enrolment'));?>
                                                </div>
                                </div>
                                <div class="control-group">
                                                <label class="control-label">Start Date<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('start_date',array('div'=>false,'label'=>false,'class'=>'form-control from_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter End Date','required','id'=>'start_date'));?>
                                                </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Prisoners Input  :<?php echo $req; ?></label>
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

                                <div class="control-group ">
                                        <label class="control-label">Themes :<?php echo $req; ?></label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('themes',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','multiple'=>'multiple','options'=>$themelist, 'empty'=>'-- Select theme --','required','id'=>'themes'));?>
                                        </div>
                                </div>
                                

                            </div>
                            
                            <div> 
                            <span class="span12">
                                <div class="form-actions" align="center">
                                    <button type="button" id="LivelihoodSkillsTrainingSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true", onclick="submitLivelihoodSkillsTraining()">Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn','onclick'=>"resetData('LivelihoodSkillsTrainingAddLivelihoodSkillsTrainingForm')",'formnovalidate'=>true))?>
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
<?php

$ajaxUrlPrisonerDetails = $this->Html->url(array('controller'=>'Education','action'=>'getPrisonerDetail'));
$ajaxUrlSubmitLivelihoodSkillsTraining = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'saveLivelihoodSkillsTraining'));


?>
<script type="text/javascript">

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
             $('#themes').select2();
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
    function submitLivelihoodSkillsTraining(){
        if($("#LivelihoodSkillsTrainingAddLivelihoodSkillsTrainingForm").valid()){
            var prisonerID = $('#prisoner_no').val();
            var url ='<?php echo $ajaxUrlSubmitLivelihoodSkillsTraining?>';

        
            $.post(url, $('#LivelihoodSkillsTrainingAddLivelihoodSkillsTrainingForm').serialize(), function(res) {
                if (res.trim()=='Success') {
                    dynamicAlertBox('Message', 'Livelihood Skills Training saved successfully !');
                    window.location = '<?php echo $this->webroot;?>SocialRehabiliationProgramme/livelihoodSkillsTraining';
                }else{
                    dynamicAlertBox('Message', 'Livelihood Skills Training Program not saved !');
                }
            });
        }
        
    }

     $(function(){
        $("#LivelihoodSkillsTrainingAddLivelihoodSkillsTrainingForm").validate({
     
            ignore: "",
                rules: {  
                    
                    'data[LivelihoodSkillsTraining][program_head_id]': {
                        required: true,
                    },
                    'data[LivelihoodSkillsTraining][prisoner_no]': {
                        required: true,
                    },
                    'data[LivelihoodSkillsTraining][date_of_enrolment]': {
                        required: true,
                    },
                    'data[LivelihoodSkillsTraining][start_date]': {
                        required: true,
                    },
                    'data[LivelihoodSkillsTraining][prisoner_name]': {
                        required: true,
                    },
                    'data[LivelihoodSkillsTraining][end_date]': {
                        required: true,
                    },
                    'data[LivelihoodSkillsTraining][prisoners_input]':{
                        required: true,
                    },
                    'data[LivelihoodSkillsTraining][themes][]':{
                        required: true,
                    }
                    
                    
                    
                },
                messages: {
                    'data[LivelihoodSkillsTraining][program_head_id]': {
                        required: "Please select program head.",
                    },
                    'data[LivelihoodSkillsTraining][prisoner_no]': {
                        required: "Please select prisoner number",
                    },
                    'data[LivelihoodSkillsTraining][date_of_enrolment]': {
                        required: "Please Enter date of enrolment",
                    },
                    'data[LivelihoodSkillsTraining][start_date]': {
                        required: "Please Enter start date",
                    },
                    'data[LivelihoodSkillsTraining][prisoner_name]': {
                        required: "Please Enter prisoner name",
                    },
                    'data[LivelihoodSkillsTraining][end_date]': {
                        required: "Please Enter end Date",
                    },
                    'data[LivelihoodSkillsTraining][prisoners_input]':{
                        required: "Please Enter Prisoner Input",
                    },
                    'data[LivelihoodSkillsTraining][themes][]':{
                        required: "Please Select themes",
                    }
                    
                },
            });
    }); 
     function resetData(id){
        $('#'+id)[0].reset();

        //$('select').select2({minimumResultsForSearch: Infinity});
        $('select').select2().select2("val", null);
        
    }
</script>            