<style type="text/css">
	body input[type="text"],body input[type="textarea"]
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
	    border:0;
    }
    .italica{
        font-style: italic;
    }
    .form-sec-head{
        text-align: center;
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .form-table{
        width:100%;
        
    }
    .form-table td{
        border:1px solid;
    }
    .form-table th{
        border:1px solid;
    }
    .form-table2{
        width:100%;
        height: 100%;
    }
    .form-table2 td{
        border:1px solid;
    }
    .form-table2 th{
        border:1px solid;
    }

</style>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>PRISONS DEPARTMENT</h5>
                    
                   <div style="float:right;padding-top: 7px;">
                        
                         <?php echo $this->Html->link('Extract prisoners record list',array('controller'=>'ExtractPrisonersRecord','action'=>'index'),array('class' => 'btn btn-primary'));?> 
                        &nbsp;&nbsp;
                    </div>
               </div>

                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('ExtractPrisonerRecord',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id', array('type'=>'hidden' ))?>
                            <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden','value'=>$prisoner['Prisoner']['id']))?>
                    <div class="form-row italica">
                        Reference Number in regard to any previous Correspondence with Attorney General's Office
                         <?php echo $this->Form->input('ref_no',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:10%;','type'=>'text','required'));?>
                    </div>

                    <div class="form-row form-sec-head">
                        Extract From Prisoner's Record
                    </div>

                    <div class="form-row table-content">
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <td class="t1" style="width:20%;height: 90px;">Serial No.:
                                     <?php echo $this->Form->input('sr_no',array('div'=>false,'label'=>false,'class'=>'dotted-input','type'=>'text','style'=>'width:90%','required'));?>
                                         
                                     </td>
                                    <td class="t1" style="width:60%;height: 90px;">Name in Full:
                                        <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'dotted-input','type'=>'text','readonly','required'));?>
                                        
                                    </td>
                                    <td class="t1" style="width:20%;height: 90px;">Age on conviction:
                                        <?php echo $this->Form->input('age_on_conviction',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%','readonly','type'=>'text','required'=>false));?></td>
                                </tr>
                                <tr>
                                    <td class="t1" style="width:20%;height: 200px;" valign="top">Conduct in Prison ( Good, Fair ,Indifferent or Bad )
                                        <?php echo $this->Form->input('conduct_in_prison',array('div'=>false,'label'=>false,'class'=>'dotted-input','type'=>'textarea','rows'=>3,'required'=>false));?>
                                            
                                        </td>
                                    <td class="t1" style="width:60%;height: 200px;">
                                        <table class="form-table2">
                                            <thead>
                                                <tr>
                                                    <th colspan="2" class="form-sec-head">Remission Forfeited</th>
                                                    <th colspan="2" class="form-sec-head">Appeal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="width:25%;">Last 12 Months</td>
                                                    <td style="width:25%;"><?php echo $this->Form->input('remission_last_year',array('div'=>false,'label'=>false,'class'=>'dotted-input','type'=>'text','readonly','style'=>'width:80%','required'=>false));?></td>

                                                    <td style="width:25%;">Has Prisoner appealed?</td>
                                                    <td style="width:25%;"><?php echo $this->Form->input('has_appealed',array('div'=>false,'label'=>false,'class'=>'dotted-input','type'=>'text','readonly','style'=>'width:80%','required'=>false));?></td>
                                                </tr>
                                                <tr>
                                                    <td>Previously</td>
                                                    <td><?php echo $this->Form->input('remission_previous',array('div'=>false,'label'=>false,'class'=>'dotted-input','type'=>'text','style'=>'width:80%','readonly','required'=>false));?></td>
                                                    
                                                    <td>Number of days for which sentence was suspended by appeal</td>
                                                    <td><?php echo $this->Form->input('days_suspended_by_appeal',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','readonly','type'=>'text','required'=>false));?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="t1" style="width:20%;height: 200px;" valign="top">Earliest possible date of release on normal remission
                                        <?php echo $this->Form->input('earliest_possible_dor',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','type'=>'text','readonly','disabled','required'=>false));?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-row form-sec-head">
                        Present Convictions
                    </div>

                    <div class="form-row ">
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:10%;" valign="top">N.B. </td>
                                    <td style="width:90%;" valign="top">
                                        <ul style="list-style-type:decimal;">
                                            <li>
                                                Where a prisoner is sentenced on being  brought up for conviction or judgement after breach of recognizance the date on which he was bound over, as well as the date of sentence, should be shown and the nature of the original offence should be stated.
                                            </li>
                                            <li>
                                                Where outstanding charges are known to have been taken into consideration by the court in passing sentence the number of such charges, and if possible, their general character should be stated.
                                            </li>
                                        </ul>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-row ">
                        <table class="form-table">
                            <thead>
                                <tr>
                                    <th>Court</th>
                                    <th>Place</th>
                                    <th>Date</th>
                                    <th>Offence</th>
                                    <th>Sentence</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $count = 0;

                                foreach($prisonerSentence as $sentence){
                                    //debug($sentence);
                                    /*
                                    echo $sentence['Court']['name'];
                                    echo $sentence['Court']['physical_address'];
                                    echo $sentence['Offence']['name'];
                                    echo $sentence['PrisonerSentence']['date_of_conviction'];
                                    echo $sentence['SentenceOf']['name'];*/
                                    ?>
                                    <tr>
                                    <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Presentconviction.'.$count.'.court',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly','value'=>isset($sentence['Court']['name'])?$sentence['Court']['name']:''));?></td>
                                    <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Presentconviction.'.$count.'.place',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly','value'=>isset($sentence['Court']['physical_address'])?$sentence['Court']['physical_address']:''));?></td>
                                    <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Presentconviction.0.date',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','style'=>'width:80%','type'=>'text','readonly','required'=>false,'readonly','disabled','value'=>isset($sentence['PrisonerSentence']['date_of_conviction'])?date('d-m-Y',strtotime($sentence['PrisonerSentence']['date_of_conviction'])):''));?></td>
                                    <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Presentconviction.'.$count.'.offence',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly','value'=>isset($sentence['Offence']['name'])?$sentence['Offence']['name']:''));?></td>
                                    <td  style="width:20%;height: 50px;"><?php echo $this->Form->input('Presentconviction.'.$count.'.sentence',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly','value'=>isset($sentence['SentenceOf']['name'])?$sentence['SentenceOf']['name']:''));?></td>
                                </tr>

                                <?php
                                $count++;
                                }?>
                                
                                <?php if(count($prisonerSentence) <=0){?>
                                    <tr>
                                    <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Presentconviction.0.court',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly'));?></td>
                                    <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Presentconviction.0.place',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly'));?></td>
                                    <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Presentconviction.0.date',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','disabled','style'=>'width:80%','type'=>'text','readonly','required'=>false,'readonly',));?></td>
                                    <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Presentconviction.0.offence',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly'));?></td>
                                    <td  style="width:20%;height: 50px;"><?php echo $this->Form->input('Presentconviction.0.sentence',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly'));?></td>
                                </tr>
                               <?php } ?>
                            </tbody>
                        </table>
                    </div>

                     <div class="form-row form-sec-head">
                        Previous Convictions
                    </div>
                     <div class="form-row ">
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:10%;" valign="top">N.B. </td>
                                    <td style="width:90%;" valign="top">
                                        <ul style="list-style-type:decimal;">
                                            <li>
                                                A complete list of previous convictions will be attached to this form. Where a pisoner has no previous convictions the words  "No previous convictions will be written in the summary below."
                                            </li>
                                           
                                        </ul>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-row ">
                       
                        <table class="form-table">
                            <tr>

                                
                                <td colspan="4" style="height: 50px;text-align: left;padding-left:10px; " class="form-sec-head" valign="left">
                                    Number of previous convictions   
                                   
                                    <?php echo $this->Form->input('no_of_conviction',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:20%;','readonly','type'=>'text','required'=>false ));?>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 50px;"></td>
                                <td style="height: 50px;">Court</td>
                                <td style="height: 50px;">Offence</td>
                                <td style="height: 50px;">Sentence</td>
                                

                            </tr>
                            
                            <tr>
                                <td rowspan="1" style="width:20%" valign="top"  >
                                    Date of first previous conviction
                                    <?php if(isset($prisonerSentencesOld[0]['PrisonerSentence']['date_of_conviction'])){
                                        echo $this->Form->input('date_first_conviction',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','type'=>'text','readonly','style'=>'width:90%;','required'=>false,'disabled','value'=>date('d-m-Y',strtotime($prisonerSentencesOld[0]['PrisonerSentence']['date_of_conviction']))));
                                    }else{
                                        echo $this->Form->input('date_first_conviction',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','disabled','type'=>'text','style'=>'width:90%;','readonly','required'=>false));
                                    } ?>
                                </td>
                               <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Previousconviction.'.$count.'.court',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly','value'=>isset($prisonerSentencesOld[0]['Court']['name'])?$prisonerSentencesOld[0]['Court']['name']:''));?></td>
                               <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Previousconviction.'.$count.'.court',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly','value'=>isset($prisonerSentencesOld[0]['Offence']['name'])?$prisonerSentencesOld[0]['Offence']['name']:''));?></td>
                               <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Previousconviction.'.$count.'.court',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly','value'=>isset($prisonerSentencesOld[0]['SentenceOf']['name'])?$prisonerSentencesOld[0]['SentenceOf']['name']:''));?></td>
                                
                            </tr>
                            <tr>
                                 <td style="height: 50px;" rowspan="1" style="width:20%" valign="top">Date of last previous conviction 
                                     <?php 
                                        $indexCount = 0 ;
                                        if(count($prisonerSentencesOld) > 1){
                                            $indexCount = count($prisonerSentencesOld) -1;
                                        }
                                    ?>

                                    <?php if(isset($prisonerSentencesOld[$indexCount]['PrisonerSentence']['date_of_conviction'])){
                                        echo $this->Form->input('date_last_conviction',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','type'=>'text','readonly','style'=>'width:90%;','required'=>false,'disabled','value'=>date('d-m-Y',strtotime($prisonerSentencesOld[$indexCount]['PrisonerSentence']['date_of_conviction']))));
                                    }else{
                                        echo $this->Form->input('date_last_conviction',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','disabled','type'=>'text','readonly','style'=>'width:90%;','required'=>false));
                                    } ?>

                                   </td>
                                <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Previousconviction.'.$count.'.court',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly','value'=>isset($prisonerSentencesOld[$indexCount]['Court']['name'])?$prisonerSentencesOld[$indexCount]['Court']['name']:''));?></td>
                               <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Previousconviction.'.$count.'.court',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly','value'=>isset($prisonerSentencesOld[$indexCount]['Offence']['name'])?$prisonerSentencesOld[$indexCount]['Offence']['name']:''));?></td>
                               <td style="width:20%;height: 50px;"><?php echo $this->Form->input('Previousconviction.'.$count.'.court',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%','type'=>'text','required'=>false,'readonly','value'=>isset($prisonerSentencesOld[$indexCount]['SentenceOf']['name'])?$prisonerSentencesOld[$indexCount]['SentenceOf']['name']:''));?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="form-row form-sec-head">
                        General Assessment of prisoner by superintendent of prisons
                    </div>
                    <div class="form-row">
                         <?php echo $this->Form->input('general_assessment',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:100%;','type'=>'text','required'=>false,'id'=>'general_assessment' ));?>
                    </div>
                    <div class="form-row" style="height: 5px;">
                        <?php echo $this->Form->input('officer_incharge',array('div'=>false,'label'=>false,'class'=>'dotted-input','readonly','style'=>'width:20%;float: right;margin-right:20%;','type'=>'text','required'=>false));?>
                    </div>
                    <br/>
                    <div class="form-row" style="height: 25px;">
                        <span style="width: 20%;float: right;margin-right: 17%;">
                            officer-in-charge of prison
                        </span>
                    </div>
                    <div class="form-row form-sec-head">
                        offences against prison discipline
                    </div>
                    <div class="form-row ">
                        <table class="form-table">
                            <thead>
                                <tr>
                                    <th style="height: 25px;width: 30%;">Date</th>
                                    <th style="height: 25px;width: 30%;">Offence</th>
                                    <th style="height: 25px;width: 30%;">Punishment</th>
                                </tr>
                            </thead>
                           <tbody>

                                <?php 
                                $count = 0;
                                foreach($inPrisonPunishments as $punishment){
                                    if(isset($punishment['Offence']['name'])){ ?>
                                       
                                        <tr>
                                         <td style="height: 120px;"><?php echo $this->Form->input('OffencePrisonDiscipline.'.$count.'.date',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','style'=>'width:80%','type'=>'text','readonly','value'=>isset($punishment['DisciplinaryProceeding']['offence_date'])?date('d-m-Y',strtotime($punishment['DisciplinaryProceeding']['offence_date'])):'','required'=>false));?></td>
                                        <td style="height: 120px;"><?php echo $this->Form->input('OffencePrisonDiscipline.'.$count.'.offence',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%','type'=>'textarea','rows'=>'3','value'=>isset($punishment['Offence']['name'])?$punishment['Offence']['name']:'','required'=>false,'readonly'));?></td>
                                        <td style="height: 120px;"><?php echo $this->Form->input('OffencePrisonDiscipline.'.$count.'.punishment',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%','type'=>'textarea','rows'=>'3','value'=>isset($punishment['Punishment']['name'])?$punishment['Punishment']['name']:'','required'=>false,'readonly'));?></td>
                                    </tr>

                                  <?php 
                                    $count++;
                                   }
                                }
                                 if(count($inPrisonPunishments) <=0 ){ ?>
                                       <td style="height: 120px;"><?php echo $this->Form->input('OffencePrisonDiscipline.0.date',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','style'=>'width:80%','type'=>'text','readonly','value'=>isset($punishment['DisciplinaryProceeding']['offence_date'])?date('d-m-Y',strtotime($punishment['DisciplinaryProceeding']['offence_date'])):'','required'=>false));?></td>
                                        <td style="height: 120px;"><?php echo $this->Form->input('OffencePrisonDiscipline.0.offence',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%','type'=>'textarea','rows'=>'3','value'=>isset($punishment['Offence']['name'])?$punishment['Offence']['name']:'','required'=>false,'readonly'));?></td>
                                        <td style="height: 120px;"><?php echo $this->Form->input('OffencePrisonDiscipline.0.punishment',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:90%','type'=>'textarea','rows'=>'3','value'=>isset($punishment['Punishment']['name'])?$punishment['Punishment']['name']:'','required'=>false,'readonly'));?></td>
                                    </tr>
                                <?php }
                                ?>
                               
                                                                
                           </tbody>
                           
                        </table>
                    </div>
                    <span class="span12">
                                <div class="form-actions" align="center">
                                    <button type="submit" id="extractSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true">Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn', 'formnovalidate'=>true))?>
                                </div>
                            </span>


                                <?php echo $this->Form->end();?>

                </div>


            </div>
        </div>
    </div>
</div>
<?php 
$allowedEdit = false;
if(isset($this->data['ExtractPrisonerRecord']['status']) && $this->data['ExtractPrisonerRecord']['status'] == 'Draft' ){
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
            $allowedEdit =true;
        }
}else if(isset($this->data['ExtractPrisonerRecord']['status']) && $this->data['ExtractPrisonerRecord']['status'] == 'Saved'){
    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
        $allowedEdit =true;
        }
}else if(isset($this->data['ExtractPrisonerRecord']['status']) && $this->data['ExtractPrisonerRecord']['status'] == 'Reviewed'){
    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
        $allowedEdit =true;
        }
}

if(!isset($this->data['ExtractPrisonerRecord']['status'])){
    $allowedEdit = true;
}

if(!$allowedEdit){ ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#ExtractPrisonerRecordAddForm :input").prop("disabled", true);
        });
    </script>
<?php
    }
?>
<script type="text/javascript">
    $(function(){
        $("#ExtractPrisonerRecordAddForm").validate({ 
            rules: {  
                'data[ExtractPrisonerRecord][ref_no]': {
                    required: true,
                },
                 'data[ExtractPrisonerRecord][sr_no]': {
                    required: true,
                },
                
            },
            messages: {
                'data[ExtractPrisonerRecord][ref_no]':{
                    required: 'Please Enter reference number',
                },
                'data[ExtractPrisonerRecord][sr_no]': {
                    required: 'Please Enter serial number',
                },
                
               
            }
        });
    });
</script>