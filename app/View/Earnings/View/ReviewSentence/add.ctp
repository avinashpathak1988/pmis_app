<style type="text/css">
body input[type="text"],body input[type="textarea"],body select
{
border: 0;
border-bottom: 1px solid #ccc;
outline: 0;
margin-left:20px;
margin-right:20px;

}
.form-row{
margin-bottom: 20px;
}
.form-text-full{
width:100%;
}
textarea{
background-image: -webkit-linear-gradient(left, white 10px, transparent 10px), -webkit-linear-gradient(right, white 10px, transparent 10px), -webkit-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
background-image: -moz-linear-gradient(left, white 10px, transparent 10px), -moz-linear-gradient(right, white 10px, transparent 10px), -moz-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
background-image: -ms-linear-gradient(left, white 10px, transparent 10px), -ms-linear-gradient(right, white 10px, transparent 10px), -ms-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
background-image: -o-linear-gradient(left, white 10px, transparent 10px), -o-linear-gradient(right, white 10px, transparent 10px), -o-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
background-image: linear-gradient(left, white 10px, transparent 10px), linear-gradient(right, white 10px, transparent 10px), linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
background-size: 100% 100%, 100% 100%, 100% 31px;
border: 1px solid #ccc;
border-radius: 8px;
line-height: 31px;
font-family: Arial, Helvetica, Sans-serif;
padding: 8px;
text-decoration: underline;
text-decoration-style:dotted;
border:0;
}

</style>
<?php 

    /*if(isset($this->data['ReviewSentenceForm']['review_date1']) && $this->data['ReviewSentenceForm']['review_date1'] != '' ){
                    $this->data['ReviewSentenceForm']['review_date1'] = date('d-m-Y',strtotime($this->data['ReviewSentenceForm']['review_date1']));
                }
                if(isset($this->data['ReviewSentenceForm']['review_date2']) && $this->data['ReviewSentenceForm']['review_date2'] != '' ){
                    $this->data['ReviewSentenceForm']['review_date2'] = date('d-m-Y',strtotime($this->data['ReviewSentenceForm']['review_date2']));
                }
                if(isset($this->data['ReviewSentenceForm']['review_date3']) && $this->data['ReviewSentenceForm']['review_date3'] != '' ){
                    $this->data['ReviewSentenceForm']['review_date3'] = date('d-m-Y',strtotime($this->data['ReviewSentenceForm']['review_date3']));
                }
                if(isset($this->data['ReviewSentenceForm']['review_date4']) && $this->data['ReviewSentenceForm']['review_date4'] != '' ){
                    $this->data['ReviewSentenceForm']['review_date4'] = date('d-m-Y',strtotime($this->data['ReviewSentenceForm']['review_date4']));
                }*/

    $showForward = 'false';
    $showSave = 'false';
    $buttonName='Forward';
    $med ='false';
    $oic = 'false';
    $cgp = 'false';

    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
        $oic = 'true';

            if(isset($this->data['ReviewSentenceForm']['status'])) {
                if($this->data['ReviewSentenceForm']['status'] == 1){
                    $showSave = 'true';

                }
            }else{
                    $showSave = 'true';
            }
             
            if(isset($this->data['ReviewSentenceForm']['id']) && $this->data['ReviewSentenceForm']['id'] != ''){
                if(isset($this->data['ReviewSentenceForm']['status'])) {
                    if($this->data['ReviewSentenceForm']['status'] == 1){
                        $showForward = 'true';
                        $buttonName='Forward to Medical Officer';

                    }else if($this->data['ReviewSentenceForm']['status'] == 3){
                        $showForward = 'true';
                        $buttonName='Forward to CGP';

                    }
                }
            }
        }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
            $med = 'true';
            if(isset($this->data['ReviewSentenceForm']['status'])) {
                if($this->data['ReviewSentenceForm']['status'] == 2){
                    $showSave = 'true';
                    $showForward = 'true';
                    $buttonName='Forward to OC';

                }
            }
        }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
            $cgp = 'true';
            if(isset($this->data['ReviewSentenceForm']['status'])) {
                if($this->data['ReviewSentenceForm']['status'] == 4){
                    $showSave = 'true';
                    $showForward = 'true';
                    $buttonName='Forward to OC';

                }
            }
        }

        if($is_excel == 'Y'){
            $showForward = 'false';
            $showSave = 'false';
        }

 ?>
<div class="container-fluid">

<?php echo $this->Form->create('ReviewSentenceForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
    <div class="row-fluid">
        <div class="span12">

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>REVIEW OF SENTENCE FORM:</h5>
                    <?php if($is_excel == 'N' && isset($this->data["ReviewSentenceForm"]["id"])){ ?>

                    <?php
                        $exUrl = "add";
                        //debug($data);
                      $urlPrint = $exUrl.'/id:'.$this->data["ReviewSentenceForm"]["id"].'/reqType:PRINT';
                        
                      echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
                      }
                    ?>
                    <?php if($showForward == 'true'){ ?>
                        <div style="text-align: center;position: absolute;top: 2px;left: 50%;">
                            <?php echo $this->Form->button($buttonName, array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger forward-btn', 'formnovalidate'=>true))?>
                        </div>
                    <?php }?>
                    <div style="font-size: 1rem;font-weight: bold;color: red;position: absolute;top: 2px;left: 35%;">
                        <?php if($showForward != 'true' && $showSave != 'true'){ 

                        if($this->data['ReviewSentenceForm']['status'] == 2){
                            echo "Forwarded to  Medical Officer";
                        }
                        else if($this->data['ReviewSentenceForm']['status'] == 3){
                            echo "Forwarded to  Officer Incharge";
                        }else if($this->data['ReviewSentenceForm']['status'] == 4){
                            echo "Forwarded to  CGP";
                        }else if($this->data['ReviewSentenceForm']['status'] == 5){
                            echo "Approved";
                        }else{
                          echo "Draft";
                        }
                                
                        } ?>
                    </div>
                    
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Review Sentence list'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                 
                </div>
                <div class="widget-content nopadding">

                    <div class="row-fluid" style="margin-top:0px;">
                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                        <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden'))?>

                        
                        <div class="form-row">
                            <span class="form-text" style="width:10%">Prison: </span>
                                   <?php
                                echo $this->Form->input('prison_id',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'select',
                                  'empty'=>'--Select Prison Name--',
                                  'options'=> $prisonList,
                                  'required',
                                  'class'=>'dotted-input',
                                  'style'=>'width:35%;',
                                  'title'=>'Please select prison name',
                                  'readonly',
                                  'onChange'    => "getPrisoner(this.value),getWhomToMeetUsers(this.value)",
                                ));
                             ?>
                        </div>
                        <div class="form-row">
                            <span class="form-text" style="width:10%">Name : </span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:35%;','type'=>'text','readonly','required'=>false));?>
                            </span>
                            <span class="form-text" style="width:10%">Court : </span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('court',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:35%;','readonly','type'=>'text','required'=>false));?>
                            </span>
                        </div>
                        <div class="form-row">
                            <span class="form-text" style="width:10%">Offence : </span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('offence',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:34%;','readonly','type'=>'text','required'=>false));?>
                            </span>
                            <span class="form-text" style="width:35%">No. of previous convictions : </span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('previous_convictions',array('div'=>false,'label'=>false,'class'=>'dotted-input numeric','style'=>'width:20%;','readonly','type'=>'text','required'=>false));?>
                            </span>
                        </div>
                         <div class="form-row">
                            <span class="form-text" style="width:10%">Sentence : </span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('sentence',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:33%;','readonly','type'=>'text','required'=>false));?>
                            </span>
                            <span class="form-text" style="width:35%">Date of Sentence : </span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('sentence_date',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:28%;','readonly','readonly','type'=>'text','required'=>false));?>
                            </span>
                        </div>
                        <div class="form-row">
                            <span class="form-text" style="width:10%">E.P.D : </span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('epd',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:36%;','type'=>'text','readonly','required'=>false));?>
                            </span>
                            <span class="form-text" style="width:35%">L.P.D : </span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('lpd',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:36%;','type'=>'text','readonly','required'=>false));?>
                            </span>
                        </div>
                        <div class="form-row" style="width: 100%;">
                            <span class="form-text span5" style="min-width: 40%">No. of prison offences:- </span>
                            <span class="form-text span7" style="min-width: 40%" >Remission forfeited:- </span>
                        </div>
                        <div class="form-row">
                            <span class="form-text" style="width:10%">In previous 12 months: </span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('offence_12_months',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:25%;','readonly','type'=>'text','required'=>false));?>
                            </span>
                            <span class="form-text" style="width:35%">In previous 12 months:</span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('remission_12_months',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:25%;','readonly','type'=>'text','required'=>false));?>
                            </span>
                        </div>
                        <div class="form-row">
                            <span class="form-text" style="width:10%">Since admission: </span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('offence_since_adm',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:30%;','readonly','type'=>'text','required'=>false));?>
                            </span>
                            <span class="form-text" style="width:35%">Since admission:</span>
                            <span class="" style="">
                                    <?php echo $this->Form->input('remission_since_adm',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:29%;','readonly','type'=>'text','required'=>false));?>
                            </span>
                        </div>
                    </div>


                    <div class="row-fluid" style="margin-top:0px;">

                        <div class="form-row span12">
                            <span class="form-text" style="width:100%;font-weight: bold;font-size: 14px;">DATE FOR REVIEW: </span>
                            
                        </div>
                        <div class="form-row">
                            <span class="form-text" style="width:10%">1. </span>
                            <span class="" style="">
                                <?php echo $this->Form->input('review_date1',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:85%;','readonly','type'=>'text','required'=>false));?>
                            </span>
                        </div>
                        <div class="form-row">
                            <span class="form-text" style="width:10%">2. </span>
                            <span class="" style="">
                                <?php echo $this->Form->input('review_date2',array('div'=>false,'label'=>false,'class'=>'dotted-input ','readonly','style'=>'width:85%;','type'=>'text','required'=>false));?>
                            </span>
                        </div>
                        <div class="form-row">
                            <span class="form-text" style="width:10%">3. </span>
                            <span class="" style="">
                                <?php echo $this->Form->input('review_date3',array('div'=>false,'label'=>false,'class'=>'dotted-input ','readonly','style'=>'width:85%;','type'=>'text','required'=>false));?>
                            </span>
                        </div>
                        <div class="form-row">
                            <span class="form-text" style="width:10%">4. </span>
                            <span class="" style="">
                                <?php echo $this->Form->input('review_date4',array('div'=>false,'label'=>false,'class'=>'dotted-input','readonly','style'=>'width:85%;','type'=>'text','required'=>false));?>
                            </span>
                        </div>

                    </div>


                    <div class="row-fluid" style="margin-top:0px;">

                        <div class="form-row span12" style="text-align: center;">
                            <span class="form-text" style="width:100%;text-align: center;font-weight: bold;font-size: 14px;">PRECIS </span>
                            
                        </div>
                        <div class="form-row ">

                        <span class="" style="">
                            <?php echo $this->Form->input('precis',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%;','type'=>'textarea','rows'=>3,'required'=>false));?>
                        </span>
                        </div>           
                    </div>


                    <div class="row-fluid" style="margin-top:0px;">

                        <div class="form-row span12" style="text-align: center;">
                            <span class="form-text" style="width:100%;text-align: center;font-weight: bold;font-size: 14px;">HISTORY </span>
                            
                        </div>
                        <div class="form-row ">

                        <span class="" style="">
                            <?php echo $this->Form->input('history',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%;','type'=>'textarea','rows'=>3,'required'=>false));?>
                        </span>
                        </div>           
                    </div>

                    <div class="row-fluid" style="margin-top:0px;">

                        <div class="form-row span12" style="text-align: center;">
                            <span class="form-text" style="width:100%;text-align: center;font-weight: bold;font-size: 14px;">DEFAULTER'S SHEET </span>
                            
                        </div>
                        <div class="form-row ">

                        <span class="" style="">
                            <?php echo $this->Form->input('defaulters',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%;','type'=>'textarea','rows'=>3,'required'=>false));?>
                        </span>
                        </div>           
                    </div>

                    <div class="row-fluid" style="margin-top:0px;">

                        <div class="form-row span12" style="text-align: center;">
                            <span class="form-text" style="width:100%;text-align: center;font-weight: bold;font-size: 14px;">PRISONER'S PROGRESS </span>
                            
                        </div>
                        <div class="form-row ">

                        <span class="" style="">
                            <?php echo $this->Form->input('prisoners_progress',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%;','type'=>'textarea','rows'=>3,'required'=>false));?>
                        </span>
                        </div>           
                    </div>

                    <div class="row-fluid" style="margin-top:0px;">

                        <div class="form-row span12" style="text-align: center;">
                            <span class="form-text" style="width:100%;text-align: center;font-weight: bold;font-size: 14px;">SUPERINTENDENT OF PRISON'S REPORT </span>
                            
                        </div>
                        <div class="form-row ">

                        <span class="" style="">
                            <?php echo $this->Form->input('superintendent_report',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%;','type'=>'textarea','rows'=>3,'required'=>false));?>
                        </span>
                        </div>           
                    </div>

                    <div class="row-fluid" style="margin-top:0px;">

                        <div class="form-row span12" style="text-align: center;">
                            <span class="form-text" style="width:100%;text-align: center;font-weight: bold;font-size: 14px;">MEDICAL OFFICER'S REPORT </span>
                            
                        </div>
                        <div class="form-row ">

                        <span class="" style="">
                            <?php echo $this->Form->input('medical_officers_report',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%;','type'=>'textarea','rows'=>3,'required'=>false));?>
                        </span>
                        </div>           
                    </div>

                    <div class="row-fluid" style="margin-top:0px;">

                        <div class="form-row span12" style="text-align: center;">
                            <span class="form-text" style="width:100%;text-align: center;font-weight: bold;font-size: 14px;">COMMISSIONER OF PRISON'S RECOMMENDATION </span>
                            
                        </div>
                        <div class="form-row ">

                        <span class="" style="">
                            <?php echo $this->Form->input('commisioner_recommendation',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%;','type'=>'textarea','rows'=>3,'required'=>false));?>
                        </span>
                        </div>           
                    </div>


                    <div class="row-fluid" style="margin-top:0px;">

                        <div class="form-row span12" style="text-align: center;">
                            <span class="form-text" style="width:100%;text-align: center;font-weight: bold;font-size: 14px;">DECISION OF THE MINISTER OF JUSTICE </span>
                            
                        </div>
                        <div class="form-row ">

                        <span class="" style="">
                            <?php echo $this->Form->input('decision_minister_justice',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%;','type'=>'textarea','rows'=>3,'required'=>false));?>
                        </span>
                        </div>           
                    </div>
                    <div class="row-fluid" style="margin-top:0px;">

                       <span class="span12">
                                <div class="form-actions" align="center">
                                   
                                <?php if($showSave == 'true'){ ?>
                                     <button type="submit" id="DischargeBoardSummarySaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true">Save</button>
                               <?php } ?>
                                <?php if($showForward == 'true'){ ?>

                                    <?php echo $this->Form->button($buttonName, array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger forward-btn', 'formnovalidate'=>true))?>
                               <?php } ?>
                               <div style="font-size: 1rem;font-weight: bold;color: red;">
                                    <?php if($showForward != 'true' && $showSave != 'true'){ 

                                     if($this->data['ReviewSentenceForm']['status'] == 2){
                                            echo "Forwarded to  Medical Officer";
                                        }
                                
                                    } ?>
                                </div>
                                </div>
                            </span>

                    </div>


                </div>
            </div>
        </div>
    </div>
                    <?php echo $this->Form->end();?>

</div>

<?php
$ajaxUrlForward = $this->Html->url(array('controller'=>'ReviewSentence','action'=>'forwardForm'));
?>

<?php 
    
    
     if($oic == 'true'){ ?>
        <script type="text/javascript">
        $(document).ready(function(){
            $('#ReviewSentenceFormMedicalOfficersReport').attr('disabled','disabled');
            $('#ReviewSentenceFormCommisionerRecommendation').attr('disabled','disabled');
            $('#ReviewSentenceFormDecisionMinisterJustice').attr('disabled','disabled');
            $('#ReviewSentenceFormId').removeAttr('disabled');
        });

        </script>
   <?php  } 

    if($med == 'true'){ ?>
        <script type="text/javascript">
        $(document).ready(function(){

        $("#ReviewSentenceFormAddForm :input").prop("disabled", true);
        $('#ReviewSentenceFormMedicalOfficersReport').removeAttr('disabled');
        $('#ReviewSentenceFormId').removeAttr('disabled');
        $('#ReviewSentenceFormMedicalOfficersReport').attr('required','required');
        });

        </script>
   <?php  }


    if($cgp == 'true'){ ?>
        <script type="text/javascript">
        $(document).ready(function(){

        $("#ReviewSentenceFormAddForm :input").prop("disabled", true);
        $('#ReviewSentenceFormCommisionerRecommendation').removeAttr('disabled');
        $('#ReviewSentenceFormDecisionMinisterJustice').removeAttr('disabled');
        $('#ReviewSentenceFormCommisionerRecommendation').attr('required','required');

        
        });

        </script>
   <?php  }

        if($showSave == 'true'){ ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.formSaveBtn').removeAttr('disabled');
            });
        </script>
        <?php }else{ ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#ReviewSentenceFormAddForm :input").prop("disabled", true);

                });
            </script>
       <?php } ?>

        <?php if($showForward == 'true'){ ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.forward-btn').removeAttr('disabled');
                $('#ReviewSentenceFormId').removeAttr('disabled');

            }); 
        </script>
        <?php } ?>


<script type="text/javascript">
    
$(document).ready(function(){
  
    $('.forward-btn').click(function(){
        if($("#ReviewSentenceFormAddForm").valid()){
            if(confirm("Have you verified the form ? , Once forwarded can't be edited. Press ok to continue. ")){
            var url ='<?php echo $ajaxUrlForward?>';
            var id =  $('#ReviewSentenceFormId').val();

            $.post(url,$('#ReviewSentenceFormAddForm').serialize(), function(res) {
               if (res.trim()=='success') {
                    dynamicAlertBox('Message', 'Form forwarded successfully !');
                    //showListSearch();
                    //resetForm('AftercareIndexForm');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'Failed , Please verify again!');
                }
            });
          }
        }
        
    });
});



$(function(){
        $("#ReviewSentenceFormAddForm").validate({
     
            ignore: "",
                rules: {  
                    
                    'data[ReviewSentenceForm][prisoner_id]': {
                        required: true,
                    },
                    'data[ReviewSentenceForm][prison_id]': {
                        required: true,
                    },
                    
                    
                    
                },
                messages: {
                    'data[ReviewSentenceForm][prisoner_id]': {
                        required: "Please select prisoner.",
                    },
                    'data[ReviewSentenceForm][prison_id]': {
                        required: "Please select prisoner.",
                    },
                    
                },
            });
    }); 
</script>             
