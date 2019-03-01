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
 <?php //debug($this->request->data) ?>


<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Welfare Details at Reception Board</h5>
                    <?php if($is_excel == 'N'){ ?>

                    
                    <?php
                        $exUrl = "viewDischargeSummary";
                        //debug($data);
                      $urlPrint = $exUrl.'/id:'.$data["DischargeBoardSummaryView"]["id"].'/reqType:PRINT';
                        
                      echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
                      }
                    ?>
                    
                   <div style="float:right;padding-top: 7px;">
                        
                        <?php echo $this->Html->link('Discharge Board summary list',array('controller'=>'DischargeBoardSummary','action'=>'index'),array('class' => 'btn btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
               </div>
               <div class="widget-content nopadding">
                	<!-- <?php debug($this->request->data); ?> -->
                    <div class="row-fluid" style="margin-top:0px;">
                            <?php echo $this->Form->create('DischargeBoardSummary',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
							<?php echo $this->Form->input('id', array('type'=>'hidden'))?>

                            <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden','value'=>$prisoner_id))?>

                        <div class="span12">
                            <div class="form-row">
                                    <!-- form starts -->
                                
                                <div class="form-row">
                                    <span class="form-text" style="width:10%">Prison :</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prison',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:80%;','type'=>'text','required'=>false,'id'=>'prison',));?>
                                    </span>
                                </div>
                                <div class="form-row">
                                     <span class="form-text" style="width:10%">Name (In full) : </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:76%;','type'=>'text','required'=>false,'id'=>'name' ));?>
                                    </span>
                                </div>
                                <div class="form-row">
                                    <span class="form-text" style="width:10%">Superintendent: </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('superintendent',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:75%;','type'=>'text','required'=>false,'id'=>'superintendent'));?>
                                    </span>
                                </div>
                                <div class="form-row">
                                    <span class="form-text" style="width:10%">Former Employment: </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('former_employment',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:72%;','type'=>'text','required'=>false,'id'=>'former_employment'));?>
                                    </span>
                                </div>
                                <div class="form-row">
                                    <span class="form-text" style="width:100%">Address on discharge if none fixed, state town to which proceeding: </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('address_on_discharge',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:85%;','type'=>'text','required'=>false,'id'=>'address_on_discharge'));?>
                                    </span>
                                </div>

                                  </div>  
                                <div class="form-row">
                                    <span class="form-text-full">What he wishes to to:</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('wishes',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'wishes'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text-full">Any offer of help or employment :</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('offer_of_help',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'offer_of_help'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text-full">Vocational and spare time training :</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('vocational_training',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'vocational_training'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text-full">General remarks and suggestions for after care :</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('general_remarks',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:88%;','type'=>'textarea','rows'=>3,'required'=>false,'id'=>'general_remarks'));?>
                                    </span>
                                    
                                </div>
                                <div class="form-row">
                                    <span class="form-text" style="width:10%">Amount of previous cash: </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('cash_amount',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:70%;','type'=>'text','required'=>false,'id'=>'cash_amount'));?>
                                    </span>
                                </div>
                                <div class="form-row">
                                    <span class="form-text" style="width:10%">Earliest Date of Discharge: </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('earliest_date_of_discharge',array('div'=>false,'label'=>false,'class'=>'dotted-input mydate','style'=>'width:70%;','type'=>'text','required'=>false,'id'=>'earliest_date_of_discharge'));?>
                                    </span>
                                </div>
                                <div class="form-row">
                                    <span class="form-text" style="width:10%">Licence Expires(If has any): </span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('licence_expires',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:70%;','type'=>'text','required'=>false,'id'=>'licence_expires'));?>
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
                                            <?php echo $this->Form->input('superintendent_sig',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:20%;float: right;margin-right:20%;','type'=>'text','required'=>false,'id'=>'superintendent_sig'));?>
                                        </span>
                                    </div>
                                    <div style="width:100%;">
                                        <span class="form-text" style="float: right;margin-right:25%;">Superintendent</span>
                                    </div>
                                    
                                </div>

                                    <!-- form field ends -->


                            </div>
                            <!-- <span class="span12">
                                <div class="form-actions" align="center">
                                    <button type="submit" id="DischargeBoardSummarySaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true">Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn', 'formnovalidate'=>true))?>
                                </div>
                            </span> -->





                                <?php echo $this->Form->end();?>

                    </div>
                </div>    	
           </div>
       </div>
   </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#DischargeBoardSummaryViewDischargeSummaryForm :input").prop("disabled", true);

    });
</script>