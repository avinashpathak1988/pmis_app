<div class="" style="padding-bottom: 14px;">
    <div class="row-fluid secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Offence Details</h5>
        </div>
        <div class="widget-content">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Case File :<?php echo $req; ?> :</label>
                    <div class="controls">
                        <?php 
                        echo $this->Form->input('case_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','onChange'=>'getCaseOffence(this.value)','type'=>'select','options'=>$sentenceCaseFile, 'empty'=>'','required', 'title'=>'Case File is required.')); ?>

                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Offence<?php echo $req; ?> :</label>
                    <div class="controls">
                        <?php 
                        if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                        {
                            echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','onChange'=>'showIsSentence(this.value), getReturnFromCourt(this.value)','type'=>'select','options'=>$offenceIdList, 'empty'=>'','required'));
                        }
                        else 
                        {
                            echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','onChange'=>'showIsSentence(this.value)','type'=>'select','options'=>$offenceIdList, 'empty'=>'','required'));
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="span6 is_convicted <?php if(!isset($this->data['PrisonerSentenceCapture']['id'])){echo 'hidden';}?>">
               <div class="control-group">
                    <label class="control-label">Awaiting/Sentence Awarded<?php echo $req; ?></label>
                    <div class="controls uradioBtn">
                        <?php 
                        //debug($this->data['PrisonerSentenceCapture']); //exit;
                        $sentenceAwaitingVal = 'hidden';
                        $isDateOfConviction = 'hidden';
                        if(isset($this->data['PrisonerSentenceCapture']['is_convicted']) && ($this->data['PrisonerSentenceCapture']['is_convicted'] == 2))
                        {
                            $sentenceAwaitingVal = '';
                        }
                        if(isset($this->data['PrisonerSentenceCapture']['is_convicted']) && ($this->data['PrisonerSentenceCapture']['is_convicted'] !== 0))
                        {
                            $isDateOfConviction = '';
                        }
                        $sentenceAwaiting = array('1'=>'Awating Sentencing', '2'=>'Sentence Awarded');
                        echo $this->Form->input('is_convicted',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$sentenceAwaiting, 'empty'=>'','required','id'=>'awaiting_sentence', 'onchange'=>'getSentenceDetailField(this.value);'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="span6 doc-div <?php echo $isDateOfConviction;?>">
               <div class="control-group">
                <?php if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                {?>
                    <label class="control-label">Date of Conviction :</label>
                    <div class="controls">
                        <?php echo $this->Form->input('date_of_conviction',array('div'=>false,'label'=>false,'class'=>'form-control date_of_commital span11','type'=>'text', 'placeholder'=>'Enter Date of Conviction','required'=>false,'readonly'=>'readonly','id'=>'date_of_conviction2'));?>
                    </div>
                <?php }
                else 
                {?> 
                    <label class="control-label">Date of Conviction<?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('date_of_conviction',array('div'=>false,'label'=>false,'class'=>'form-control date_of_commital span11','type'=>'text', 'placeholder'=>'Enter Date of Conviction','required','readonly'=>'readonly','id'=>'date_of_conviction2'));?>
                    </div>
                <?php }?>
                </div>
            </div>
        </div>
    </div>
    <!-- Offence detail END --> 
</div>
<div class="awaiting <?php echo $sentenceAwaitingVal;?>" style="padding-bottom: 14px;">
    <div class="row-fluid secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Sentence Details</h5>
        </div>
        <div class="widget-content">
       
            <!-- <div class="span6 hidden">
               <div class="control-group">
                    <label class="control-label">Date of Committal <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php //echo $this->Form->input('date_of_committal',array('div'=>false,'label'=>false,'class'=>'form-control date_of_commital span11 date_of_committal','type'=>'text', 'placeholder'=>'Enter Date of Committal','readonly'=>true,'id'=>'date_of_committal2'));?>
                    </div>
                </div>
            </div> -->
            <div class="span6 awaiting <?php echo $sentenceAwaitingVal;?>">
               <div class="control-group">
                <?php if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                {?>
                    <label class="control-label">Date of Sentence:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('date_of_sentence',array('div'=>false,'label'=>false,'class'=>'form-control date_of_commital span11','type'=>'text', 'placeholder'=>'Enter Date of Sentence','required'=>false,'readonly'=>'readonly','id'=>'date_of_sentence2'));?>
                    </div>
                <?php 
                }
                else
                {
                    ?>
                    <label class="control-label">Date of Sentence <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('date_of_sentence',array('div'=>false,'label'=>false,'class'=>'form-control date_of_commital span11','type'=>'text', 'placeholder'=>'Enter Date of Sentence','required','readonly'=>'readonly','id'=>'date_of_sentence2'));?>
                    </div>
                    <?php 
                }?>
                </div>
            </div> 
            <div class="span6 awaiting <?php echo $sentenceAwaitingVal;?>">
                <div class="control-group">
                    <?php if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                    {?>
                        <label class="control-label">Sentence Of:</label>
                        <div class="controls">
                            <?php echo $this->Form->input('sentence_of',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$sentenceOfList, 'empty'=>'-- Select Sentence Of --','required'=>false,'id'=>'sentence_of_capture', 'onchange'=>"setSentence(this.value,'sentenceCapture')"));?>
                        </div>
                    <?php }
                    else {?>
                        <label class="control-label">Sentence Of<?php echo $req; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('sentence_of',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$sentenceOfList, 'empty'=>'-- Select Sentence Of --','required','id'=>'sentence_of_capture', 'onchange'=>"setSentence(this.value,'sentenceCapture')"));?>
                        </div>
                    <?php }?>
                    
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="span6 awaiting <?php echo $sentenceAwaitingVal;?>">
               <div class="control-group">
                    <label class="control-label">Wish To appeal:</label>
                    <div class="controls uradioBtn">
                        <?php 
                        $wish_to_appeal2 = 0;
                        if(isset($this->data['PrisonerSentenceCapture']['wish_to_appeal']))
                            $wish_to_appeal2 = $this->data['PrisonerSentenceCapture']['wish_to_appeal'];
                        $wish_to_appeal_options2= array('0'=>'No', '1'=>'Yes');
                        $wish_to_appeal_attributes2 = array(
                            'legend'    =>  false, 
                            'value'     =>  $wish_to_appeal2
                        );
                        echo $this->Form->radio('wish_to_appeal', $wish_to_appeal_options2, $wish_to_appeal_attributes2);
                        ?>
                    </div>
                </div>
            </div>
            <div class="span6 awaiting <?php echo $sentenceAwaitingVal;?>">
               <div class="control-group">
                    <label class="control-label">Waiting For Confirmation:</label>
                    <div class="controls uradioBtn">
                        <?php 
                        $waiting_for_confirmation = 0;
                        $sentenceConfirmationVal = 'style="display:none;"';
                        if(isset($this->data['PrisonerSentenceCapture']['waiting_for_confirmation']) && ($this->data['PrisonerSentenceCapture']['waiting_for_confirmation'] == 1))
                        {
                            $waiting_for_confirmation = $this->data['PrisonerSentenceCapture']['wish_to_appeal'];
                            $sentenceConfirmationVal = '';
                        }
                        $waiting_for_confirmation_options= array('0'=>'No', '1'=>'Yes');
                        $waiting_for_confirmation_attributes = array(
                            'legend'    =>  false, 
                            'value'     =>  $waiting_for_confirmation,
                            'onclick'   =>  'setConfirmation(this.value);'
                        );
                        echo $this->Form->radio('waiting_for_confirmation', $waiting_for_confirmation_options, $waiting_for_confirmation_attributes);
                        ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="span6" id="serving_as_div" <?php echo $sentenceConfirmationVal;?>>
               <div class="control-group">
                    <label class="control-label">Serving as:</label>
                    <div class="controls uradioBtn">
                        <?php 
                        $serving_as = 1;
                        if(isset($this->data['PrisonerSentenceCapture']['serving_as']))
                        {
                            $serving_as = $this->data['PrisonerSentenceCapture']['wish_to_appeal'];
                        }
                        $serving_as_options= array('0'=>'Not To Serve', '1'=>'Opt To Serve');
                        $serving_as_attributes = array(
                            'legend'    =>  false, 
                            'value'     =>  $serving_as,
                        );
                        echo $this->Form->radio('serving_as', $serving_as_options, $serving_as_attributes);
                        ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <?php //debug($this->request->data);
            $display_reciept_no2 = 'style="display: none;"';
            $display_fine_with_imprisonment2 = 'style="display: none;"';
            $display_fine_amount2 = 'style="display: none;"';
            $display_sentence_count2 = 'display: none;';
            $display_reciept_upload_div = 'style="display: none;"';
            if(isset($this->request->data['PrisonerSentenceCapture']['sentence_of']) && $this->request->data['PrisonerSentenceCapture']['sentence_of'] == 1)
            {
                $display_sentence_count2 = '';
            }
            if(isset($this->request->data['PrisonerSentenceCapture']['sentence_of']) && $this->request->data['PrisonerSentenceCapture']['sentence_of'] == 2)
            {
                $display_reciept_no2 = '';
                $display_fine_with_imprisonment2 = '';
                $display_sentence_count2 = '';
                $display_reciept_upload_div = '';
            }
            if(isset($this->request->data['PrisonerSentenceCapture']['sentence_of']) && $this->request->data['PrisonerSentenceCapture']['sentence_of'] == 3)
            {
                $display_reciept_no2 = '';
                $display_fine_amount2 = '';
                $display_reciept_upload_div = '';
            } 
            ?>
            <div class="span6" id="sentenceCapture_fine_with_imprisonment" <?php echo $display_fine_with_imprisonment2;?>>
                <div class="control-group">
                    <label class="control-label">Fine (Amount) In addition to imprisonment or default of further sentence:</label>
                    <div class="controls">
                        <?php echo $this->Form->text('fine_with_imprisonment',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'number', 'step'=>'0.01', 'placeholder'=>'Enter Fine (Amount)','required'=>false,'id'=>'fine_with_imprisonment', 'maxlength'=>'30'));?>
                    </div>
                </div>
                <div class="" id="sentenceCapture_payment_date" <?php echo $display_fine_with_imprisonment2;?>>
                    <div class="control-group">
                        <label class="control-label">Payment Date:</label>
                        <div class="controls">
                            <?php 
                            echo $this->Form->text('payment_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text', 'step'=>'0.01', 'placeholder'=>'Enter Payment Date','required'=>false,'readonly'));?>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="span6" id="sentenceCapture_fine_amount" <?php echo $display_fine_amount2;?>>
                <div class="control-group">
                    <label class="control-label">Fine (Amount)  Only or in default Imprisonment:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('fine_amount',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'number', 'step'=>'0.01', 'placeholder'=>'Enter Fine (Amount)','required'=>false,'id'=>'fine_amount', 'maxlength'=>'30'));?>
                    </div>
                </div>
            </div> 
            <div class="span6">
                <div class="control-group" id="sentenceCapture_receipt_number" <?php echo $display_reciept_no2;?>>
                    <label class="control-label">Receipt Number :</label>
                    <div class="controls">
                        <?php echo $this->Form->text('reciept_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Receipt Number ','required'=>false,'id'=>'receipt_number', 'maxlength'=>'30'));?>
                    </div>
                </div>
            </div>
            <!-- Fine receipt START -->
            <div class="span6" id="reciept_upload_div" <?php echo $display_reciept_upload_div;?>>
                <div class="control-group">
                    <label class="control-label">
                        Upload Reciept  
                        <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,gif) type photo!" id='example'></i>
                        :
                    </label>
                    <div class="controls">
                        <div id="" class="">
                            <?php if(isset($this->request->data["PrisonerSentence"]["reciept_file"]))
                            {?>
                                <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/sentence/<?php echo $this->request->data["PrisonerSentence"]["reciept_file"];?>" data-lightbox="example-set"><img src="<?php echo $this->webroot; ?>app/webroot/files/sentence/<?php echo $this->request->data["PrisonerSentence"]["reciept_file"];?>" alt="" width="100px" height="100px"></a>
                            <?php }?>
                        </div>
                        <span id="preview_reciept_file" class="preview_image">
                            <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="prev_reciept_file" src="#" class="img_prev" /></a>
                            <span id="remove_reciept_file" class="remove_img" onclick="removePreview('reciept_file');">[X]</span>
                        </span>
                        <div class="clear"></div>
                        <?php echo $this->Form->input('reciept_file',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'reciept_file', 'required'=>false, 'onchange'=>'readImage(this,"reciept_file");'));?>
                    </div>
                </div>
            </div>
            <!-- Fine receipt START -->
            <div class="clearfix"></div> 
            <div class="sentenceCaptureDiv" style="<?php echo $display_sentence_count2;?> margin-top: 10px;"> 
                <!-- <h4 class="text-center">Sentence</h4> -->
                <div class="clearfix"></div> 
                <?php echo $this->element('sentence-count');?>
            </div>
        </div>
    </div>
</div>
<script>
function getCaseOffence(id)
{
    var strURL = '<?php echo $this->Html->url(array('controller'=>'Prisoners','action'=>'getCaseOffence'));?>/'+id;
    $.post(strURL,{},function(data){
        if(data) { 
            $('#PrisonerSentenceCaptureOffenceId').html(data);
            $('#PrisonerSentenceCaptureOffenceId').val('');
            $('#PrisonerSentenceCaptureOffenceId').select2({
                placeholder: "-- Select --",
                allowClear: true,
                val:0
            });

              var county_id='';
              <?php if(isset($this->request->data['PrisonerSentenceCaptureOffenceId']['offence_no'])){?>
                  offence_no = '<?php echo $this->request->data['PrisonerSentenceCapture']['offence_no'];?>';
                  $('#PrisonerSentenceCaptureOffenceId').val(offence_no); 
              <?php }?>
        }
        else
        {
            alert("Error...");  
        }
        showIsSentence('');
    });
}
//get getReturnFromCourt
function getReturnFromCourt(offence_id)
{
    var url = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'getReturnFromCourt')); ?>';
    var prisoner_id = $('#PrisonerSentenceCapturePrisonerId').val();
    $.post(url, {'offence_id':offence_id, 'prisoner_id':prisoner_id}, function(res)
    {
        //console.log(res);
        var result = jQuery.parseJSON(res); 
        $('#date_of_sentence2').val(result.sentence_date);
        $('#date_of_conviction2').val(result.conviction_date);
        var sentence_type = 1;
        var sentence_type_html = '<option value="1">Awating Sentencing</option>';
        if(result.sentence_date == 16)
        {
            sentence_type_html = '<option value="2">Sentence Awarded</option>';
            sentence_type = 2;
        }

        $('#awaiting_sentence').html(sentence_type_html);
        $('#awaiting_sentence').select2('destroy');
        $('#awaiting_sentence').attr('readonly', 'readonly');
        // $('#awaiting_sentence').select2({
        //     placeholder: "-- Select --",
        //     allowClear: true,
        //     val:sentence_type
        // });
        getSentenceDetailField(sentence_type);
    });
}
function showIsSentence(val)
{
    if(val != '')
    {
        $('.is_convicted').removeClass('hidden');
    }
    else 
    {
        $('.is_convicted').addClass('hidden');
    }
    getSentenceDetailField('');
}
function getSentenceDetailField(val)
{
    if(val == 2)
    {
        $('.awaiting').removeClass('hidden');
        $('.doc-div').removeClass('hidden');
    }
    else 
    {
        $('.awaiting').addClass('hidden');
        $('.doc-div').removeClass('hidden');
        if(val == '')
            $('.doc-div').addClass('hidden');
    }
    $('#').val('hidden');
}
function setConfirmation(val)
{ 
    if(val == '1')
    {
        $('#serving_as_div').show();
    }
    else 
    {
        $('#serving_as_div').hide();
    }
}
</script>