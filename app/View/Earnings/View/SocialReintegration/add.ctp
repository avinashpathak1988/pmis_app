<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
                          

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add new Social Reintegration Assessment </h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('social reintegration assessment list'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="row-fluid">
                        <div class="span12 ">
                        <!-- form2 -->
                        <div class="aftercareform">
                            <?php echo $this->Form->create('SocialReintegrationAssessment',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'hidden'));?>
                            <div class="span5">
                                 <div class="control-group">
                                    <label class="control-label">Prisoner Number :<?php echo $req; ?>:</label>
                                    <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$prisonersList,'onchange'=>'showFields(this.value)', 'empty'=>'-- Select Prisoner no --','required'=>false,'id'=>'prisoner_no'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                                <label class="control-label">Start Date<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('start_date',array('div'=>false,'label'=>false,'class'=>'form-control from_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter End Date','required','id'=>'start_date'));?>
                                                </div>
                                </div>
                                <div class="control-group ">
                                        <label class="control-label">Reintegration Activity :<?php echo $req; ?></label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('activity',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$reintegrationActivityList, 'empty'=>'-- Select Activity --','required'=>false,'id'=>'activity'));?>
                                        </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"> Board members:<?php echo $req; ?></label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('board_members',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','id'=>'board_members','multiple'=>'multiple','options'=>$userList,'required'=>false));?>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="span5">

                                <div class="control-group">
                                            <label class="control-label">Prisoner name :<?php echo $req; ?></label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'class'=>'form-control prisonerName ','type'=>'text','readonly'=>'readonly','placeholder'=>'','id'=>'prisoner_name','rows'=>1,'required'=>false));?>
                                            </div>
                                </div>
                                <div class="control-group">
                                                <label class="control-label">End Date<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('end_date',array('div'=>false,'label'=>false,'class'=>'form-control to_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter End Date','required','id'=>'end_date'));?>
                                                </div>
                                </div>
                                <div class="control-group ">
                                        <label class="control-label"> Activity status:<?php echo $req; ?></label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('activity_status',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$activityStatus, 'empty'=>'-- Select activity status --','required'=>false,'id'=>'activity_status'));?>
                                        </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Remark :<?php echo $req; ?></label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('remark',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Prisoners Input','id'=>'remark','rows'=>3,'required'=>false,'maxlength'=>1000));?>
                                    </div>
                                </div>

                            </div>
                            
                            <div> 
                            <span class="span12">
                                <div class="form-actions" align="center">
                                    <button type="button" id="InformalCouncellingSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true", onclick="submitReintegration()">Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn','onclick'=>"resetData('SocialReintegrationAssessmentAddForm');", 'formnovalidate'=>true))?>
                                </div>
                            </span>
                                </div>
                            

                                <?php echo $this->Form->end();?>

                        </div>
                    </div> 
                    </div>
						
                     <!-- <div class="row-fluid">
                         <div class="span12">
                        <div class="aftercareList" id="aftercareList">
                        
                        </div>
                    </div>
                     </div> -->
                    
                </div>
             </div>
         </div>

         
     </div>
</div>   

<?php
$ajaxUrlSubmitReintegration = $this->Html->url(array('controller'=>'SocialReintegration','action'=>'submitReintegration'));
/*$ajaxUrlAfterCareList = $this->Html->url(array('controller'=>'Aftercare','action'=>'aftercareAjax'));*/
$ajaxUrlPrisonerDetails = $this->Html->url(array('controller'=>'Education','action'=>'getPrisonerDetail'));
?>
<script type="text/javascript">
    
    $( document ).ready(function() {/*
        $('#collapsedSearch').addClass('collapsed');
        $('#searchPrisonerOne').removeClass('in');
        $('#searchPrisonerOne').css('height','0px');*/
       // showListSearch();
            $('#prisoner_no').select2();
            $('#officer').select2();
            $('#board_members').select2();

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

    function submitReintegration(){
        if($("#SocialReintegrationAssessmentAddForm").valid()){
            var prisonerID = $('#prisoner_no').val();
            var url ='<?php echo $ajaxUrlSubmitReintegration?>';

            $.post(url, $('#SocialReintegrationAssessmentAddForm').serialize(), function(res) {
                if (res.trim()=='Success') {
                    dynamicAlertBox('Message', 'Social Reintegration Assessment saved successfully !');
                    //showListSearch();
                    //resetForm('AftercareIndexForm');
                    window.location = '<?php echo $this->webroot;?>SocialReintegration';
                }else{
                    dynamicAlertBox('Message', 'Social Reintegration Assessment not saved !');
                }
            });
        }
        
    }

$(function(){
        $("#SocialReintegrationAssessmentAddForm").validate({
     
            ignore: "",
                rules: {  
                    
                    'data[SocialReintegrationAssessment][prisoner_id]': {
                        required: true,
                    },
                    'data[SocialReintegrationAssessment][name]': {
                        required: true,
                    },
                    'data[SocialReintegrationAssessment][start_date]': {
                        required: true,
                    },
                    'data[SocialReintegrationAssessment][end_date]': {
                        required: true,
                    },
                    'data[SocialReintegrationAssessment][activity]': {
                        required: true,
                    },
                    'data[SocialReintegrationAssessment][activity_status]': {
                        required: true,
                    },
                    'data[SocialReintegrationAssessment][board_members][]': {
                        required: true,
                    },
                    'data[SocialReintegrationAssessment][remark]': {
                        required: true,
                    },
                    
                    
                    
                },
                messages: {
                    'data[SocialReintegrationAssessment][prisoner_id]': {
                        required: "Please select prisoner number",
                    },
                    'data[SocialReintegrationAssessment][name]': {
                        required: "Please Enter prisoner name",
                    },
                    'data[SocialReintegrationAssessment][start_date]': {
                        required: "Please select start date",
                    },
                    'data[SocialReintegrationAssessment][end_date]': {
                        required: "Please select end date",
                    },
                    'data[SocialReintegrationAssessment][activity]': {
                        required: "Please Select Activity",
                    },
                    'data[SocialReintegrationAssessment][activity_status]': {
                        required: "Please Select activity status",
                    },
                    'data[SocialReintegrationAssessment][board_members][]': {
                        required: "Please Select Board members",
                    },
                    'data[SocialReintegrationAssessment][remark]': {
                        required: "Please Enter Remark",
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