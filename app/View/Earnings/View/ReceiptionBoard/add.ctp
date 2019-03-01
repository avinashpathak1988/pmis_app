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
</style>
<!-- <?php debug($this->request->data) ?> -->


<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Welfare Details at Reception Board</h5>
                    <?php
                    if(isset($this->request->data["WelfareDetail"]["id"])){
                        $exUrl = "add";
                        
                      $urlPrint = $exUrl.'/id:'.$this->request->data["WelfareDetail"]["id"].'/reqType:PRINT';
                        
                      echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
                    }
                        
                      
                    ?>
                    
                    <?php if(isset($this->request->data['WelfareDetail']['status']) && $this->request->data['WelfareDetail']['status']=='Approved'){ ?>

                        <span style="text-align: center;margin-top: 10px;margin-left: 20%;" class="badge badge-success"><?php echo $this->request->data['WelfareDetail']['status']; ?></span>

                    <?php }else{ ?>
                        <span style="text-align: center;margin-top: 10px;margin-left: 20%;" class="badge badge-warning"><?php echo isset($this->request->data['WelfareDetail']['status'])?$this->request->data['WelfareDetail']['status']:'Not Yet Filled'; ?></span>
                    <?php } ?>
                   <div style="float:right;padding-top: 7px;">
                        
                        <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Reception Board list'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                    </div>
               </div>
               <div class="widget-content nopadding">
                	<!-- <?php debug($this->request->data); ?> -->
                    <div class="row-fluid" style="margin-top:0px;">
                            <?php echo $this->Form->create('WelfareDetail',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
							<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
							<?php echo $this->Form->input('prisoner_id', array('type'=>'hidden','value'=>$prisoner['Prisoner']['id']))?>


                            <div class="span12">
                                 <div class="form-row">
                                    <span class="form-text" style="width:10%">Name </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:40%;','type'=>'text','required'=>false,'value'=>$prisoner['Prisoner']['first_name'] .' ' . $prisoner['Prisoner']['last_name'] ,'id'=>'prisoner_name'));?>
                                    </span>
                                    <span class="form-text" style="width:10%">Number</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner_number',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:32%;','type'=>'text','required'=>false,'value'=>$prisoner['Prisoner']['prisoner_no'],'id'=>'prisoner_number'));?>
                                    </span>
                                </div>
                                <div class="form-row">
                                    <span class="form-text" >Seen by Receiption Board on </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('receiption_seen_date',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','style'=>'width:30%;','type'=>'text','readonly','required'=>false,'id'=>'receiption_seen_date'));?>
                                    </span>
                                    <span class="form-text" >at</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('receiption_seen_place',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:32%;','type'=>'text','required'=>false,'id'=>'receiption_seen_place'));?>
                                    </span>
                                </div>
                                <div class="form-row">
                                    <span class="form-text" >Sex </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner_sex',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:12%;','type'=>'text','required'=>false,'value'=>$prisoner['Gender']['name'],'id'=>'prisoner_sex'));?>
                                    </span>
                                    <span class="form-text" >Age</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner_age',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:12%;','type'=>'text','required'=>false,'value'=>$prisoner['Prisoner']['age'],'id'=>'prisoner_age'));?>
                                    </span>

                                    <span class="form-text" >Married or Single</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner_marital_status',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:12%;','type'=>'text','required'=>false,'value'=>$prisoner['MaritalStatus']['name'],'id'=>'prisoner_marital_status'));?>
                                    </span>
                                    <span class="form-text">Literate</span>

                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner_literacy',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:16%;','type'=>'text','required'=>false,'value'=>$prisoner['LevelOfEducation']['name'],'id'=>'prisoner_literacy'));?>
                                    </span>
                                </div>

                                <div class="form-row">
                                    <span class="form-text-full">Degree of education (School attended - standard reached ) </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner_education',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'value'=>$prisoner['LevelOfEducation']['name'],'id'=>'prisoner_education'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text" >Religion </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner_religion',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%;','type'=>'text','required'=>false,'value'=>$prisoner['Religion']['name'],'id'=>'prisoner_religion'));?>
                                    </span>
                                    
                                </div>
                                 <div class="form-row">
                                    <span class="form-text" >Tread Qualification </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('tread_qualification',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:74%;','type'=>'text','required'=>false,'id'=>'tread_qualification'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text-full">Physical and Mental state (to be filled by Prisons Medical Officer before the prisoner is seen by reception board)</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('physical_mental_state',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'physical_mental_state'));?>
                                    </span>
                                    
                                </div>

                                <div class="form-row">
                                    <span class="form-text-full">History since last imprisonment (if any) previous action taken by prison authorities i.e After Care</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner_history',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'prisoner_history'));?>
                                    </span>
                                    
                                </div>

                                <div class="form-row">
                                    <span class="form-text-full">Note from previous record (if any re disciplinary offences, medical history special occurences</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('note_from_previous_record',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'note_from_previous_record'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text-full">Recomendation regarding classification</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('recommendation_classification',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'recommendation_classification'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text-full">Instructions reposition with any special recommendations from the Board</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('instruction_reposition',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'instruction_reposition'));?>
                                    </span>
                                    
                                </div>

                                <div class="form-row">
                                    <span class="form-text-full">Number of children , sex, ages</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('children_details',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'children_details'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text-full">Who are dependent members of prisoner's family and where are they living</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('dependent_members',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'dependent_members'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text-full">What income is there</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('income_details',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'income_details'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text" >Do dependents of prisoner own land or property </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('dependent_property',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:56%;','type'=>'text','required'=>false,'id'=>'dependent_property'));?>
                                    </span>
                                    
                                </div>

                                <div class="form-row">
                                    <span class="form-text-full">Does the Board consider an investigation by Welfare Officer necessary</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('consider_investigation',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'consider_investigation'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text-full">Has prisoner any salary or debts owing to him or property with police</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner_sal_debt',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'prisoner_sal_debt'));?>
                                    </span>
                                    
                                </div>

                                <div class="form-row">
                                    <span class="form-text-full">Does prisoner, or his family own money as the result of his imprisonment</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('family_own_money',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'family_own_money'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text-full">Any further details &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('further_details',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'further_details'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">

                                	<span class="form-text">Date</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('filled_date',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:20%;','type'=>'text','required'=>false,'readonly','value'=>date('d-m-Y'),'id'=>'filled_date'));?>
                                    </span>
                                </div>


                                <div class="form-row">
                                	
                                	<div style="width:100%;float:right">
                                		<span class="" style="">
                                            <?php echo $this->Form->input('officer_incharge',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:20%;float: right;margin-right:16%;','type'=>'text','required'=>false,'value'=>$offcerInCharge['User']['name'],'id'=>'officer_incharge'));?>
                                    	</span>
                                	</div>
                                	<div style="width:100%;">
                                		<span class="form-text" style="float: right;margin-right:25%;">Officer in Charge</span>

                                	</div>
                                    
                                </div>
                            </div>
                            <span class="span12">
                                <div class="form-actions" align="center">
                                    <button type="submit" id="WelfareDetailsSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true">Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn', 'formnovalidate'=>true))?>
                                </div>
                            </span>





                                <?php echo $this->Form->end();?>

                    </div>
                </div>    	
           </div>
       </div>
   </div>
</div>
<?php if(isset($this->request->data['WelfareDetail']['status'])){
        if( $this->request->data['WelfareDetail']['status'] != 'Draft'){ 
            ?>
            <script type="text/javascript">
                $("#WelfareDetailAddForm :input").prop("disabled", true);
            </script>
<?php } 
}?>