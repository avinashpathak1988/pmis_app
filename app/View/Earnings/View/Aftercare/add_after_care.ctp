<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
                          

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add new After Care </h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('After care list'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="row-fluid">
                        <div class="span12 ">
                        <!-- form2 -->
                        <div class="aftercareform">
                            <?php echo $this->Form->create('Aftercare',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="span5">
                                 <div class="control-group">
                                    <label class="control-label">Prisoner Number :<?php echo $req; ?>:</label>
                                    <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$prisonersList,'onchange'=>'showFields(this.value)', 'empty'=>'-- Select Prisoner no --','required','id'=>'prisoner_no'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Activity Description  :<?php echo $req; ?></label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('description',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Description','id'=>'description','rows'=>3,'required','maxlength'=>1000));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Responsible Officer  :<?php echo $req; ?></label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('officer',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Responsible officer','id'=>'officer','required','maxlength'=>100));?>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="span5">

                                <div class="control-group">
                                            <label class="control-label">Prisoner name :<?php echo $req; ?></label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'class'=>'form-control prisonerName ','type'=>'text','readonly'=>'readonly','placeholder'=>'','id'=>'prisoner_name','rows'=>1,'required'));?>
                                            </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Attachment :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'photo', 'required'=>false));?>
                                    </div>
                                </div>
                            </div>
                            <div> 
                            <span class="span12">
                                <div class="form-actions" align="center">
                                    <button type="button" id="InformalCouncellingSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true", onclick="submitAfterCare()">Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn','onclick'=>"resetData('AftercareAddAfterCareForm');", 'formnovalidate'=>true))?>
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
$ajaxUrlSubmitAfterCare = $this->Html->url(array('controller'=>'Aftercare','action'=>'submitAfterCare'));
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

    function submitAfterCare(){
        var prisonerID = $('#prisoner_no').val();
        var url ='<?php echo $ajaxUrlSubmitAfterCare?>';


        if($("#AftercareAddAfterCareForm").valid())
        {
            $('#AftercareAddAfterCareForm').submit();
            $.post(url, $('#AftercareAddAfterCareForm').serialize(), function(res) {
                if (res.trim()=='Success') {
                    dynamicAlertBox('Message', 'Social Rehabilitation After Care Services saved successfully !');
                    //showListSearch();
                   // resetForm('AftercareAddAfterCareForm');
                    window.location = '<?php echo $this->webroot;?>Aftercare';

                }else{
                    dynamicAlertBox('Message', 'Social Rehabilitation After Care Services not saved !');
                }
            });
        }
    }


    $(function(){
        $("#AftercareAddAfterCareForm").validate({ 
            rules: {  
                'data[Aftercare][prisoner_id]': {
                    required: true,
                },
                'data[Aftercare][name]': {
                    required: true,
                },
                'data[Aftercare][officer]': {
                    required: true,
                },
                'data[Aftercare][description]':{
                    required: true,
                    maxlength:100
                }
            },
            messages: {
                'data[Aftercare][prisoner_id]':{
                    required: 'Please select prisoner number',
                },
                'data[Aftercare][name]': {
                    required: 'Please enter Name',
                },
                'data[Aftercare][officer]': {
                    required: 'Please enter Officer name',
                },
                'data[Aftercare][description]': {
                    required: 'Please Enter Description',
                    maxlength:"should be less than 100 characters"
                    }
            }
        });
    });
    function resetData(id){
        $('#'+id)[0].reset();

        //$('select').select2({minimumResultsForSearch: Infinity});
        $('select').select2().select2("val", null);
               

        
    }
</script>            	