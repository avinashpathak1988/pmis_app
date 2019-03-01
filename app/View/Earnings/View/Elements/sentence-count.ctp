<?php 
$cuncurrent_with_sentences = $funcall->getCuncurrentWithSentences($this->data['Prisoner']['id']);
//debug($cuncurrent_with_sentences);
?>
<style type="text/css">
    #cuncurrent_with div.checker
    {
        float: left;
        margin-top:-4px;
    }
</style>
<div class="span12" style="margin-top: 10px;">
    <div style="width: 90%;margin: 0 auto;">
        <div id="sentence_capture_count"> 
            <div class="input-group col-xs-3">
                <div style="width:10%; float: left; margin: 5px;" class="scount_sl"></div>
                <div style="width:20%; float: left; margin: 5px;" class="scount_sl">Years</div>
                <div style="width:20%; float: left; margin: 5px;" class="scount_sl">Months</div>
                <div style="width:20%; float: left; margin: 5px;" class="scount_sl">Days</div>
                <div style="width:20%; float: left; margin: 5px;" class="scount_sl">Sentence Type</div>
            </div>
            <div class="clearfix"></div>
            <?php 
            $total_sentence_capture_count = 0;
            if(isset($this->request->data['PrisonerSentenceCapture']['sentenceCountData']) && count($this->request->data['PrisonerSentenceCapture']['sentenceCountData']) > 0)
            {
                $editSentenceCountData  =   $this->request->data['PrisonerSentenceCapture']['sentenceCountData'];
            }
            //unset($this->data['PrisonerSentence']['PrisonerSentenceCount'][0]);
            //echo '<pre>'; print_r($this->data); echo '</pre>';
            ?>
                <div class="sentence_capture_entry input-group col-xs-3" style="margin-bottom:5px;">
                    <div style="float: left; margin: 5px;" class="scount_sl"></div>
                    <?php echo $this->Form->input('years',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Years','required'=>false));?>
                    <?php echo $this->Form->input('months',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Months','required'=>false));?>
                    <?php echo $this->Form->input('days',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Days','required'=>false));?>
                    <?php echo $this->Form->input('sentence_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$sentenceTypeList, 'required'=>false, 'style'=>'width:20%','default'=>Configure::read('PRISONER-TYPE-CONSECUTIVE'), 'onchange'=>'cuncurrentWith(this.value);'));?>
                    <div id="cuncurrent_with" style="width:28%; float:right; display:none;">
                        <?php 
                        if(!empty($cuncurrent_with_sentences) && count($cuncurrent_with_sentences) > 0)
                        {
                            foreach($cuncurrent_with_sentences as $csentence)
                            {
                                echo $this->Form->input('cuncurrent_with',array('div'=>false,'label'=>$csentence[0]['file_count'],'class'=>'form-control','type'=>'checkbox', 'required'=>false, 'value'=>$csentence['PrisonerSentence']['id']));
                            }
                        }
                        ?>
                    </div>
                    <!-- <span class="input-group-btn">
                        <button class="btn btn-success btn-add sentence-capture-btn-add" type="button" style="padding: 8px 8px;">
                            <span class="icon icon-plus"></span>
                        </button>
                    </span> -->
                </div> 
            <?php //}?>
        </div>
    </div>
</div>         
<style type="text/css">
    .scountDiv2 input {
    margin-bottom: 10px;
}
</style>
<script type="text/javascript">
function cuncurrentWith(val)
{
    $('#cuncurrent_with').hide();
    $('#cuncurrent_with input:checkbox').removeAttr('checked');
    if(val == 2)
    {
        $('#cuncurrent_with').show();
    }
}
var prev_sentence_capture_count = '<?php echo $total_sentence_capture_count;?>';
// function addSentenceCount()
// {
//     $('.scountDiv2 .sentence_capture_entry.new').remove();

//     if(prev_sentence_capture_count == 0)
//     {
//         $("select").select2("destroy");

//         var controlForm = $('.scountDiv2');
//         //var currentEntry   =   $('#dataFormat .entry.new');
//         var currentEntry   =   $('.sentence_capture_entry:last');
//         var newEntry = $(currentEntry.clone()).appendTo(controlForm);

//         newEntry.find('input').val('');

//         //get total sentence count length 
//         var scount = parseInt($('.scountDiv2 input[name*="years"]').length);
//         if(scount > 1)
//         {
//             controlForm.find('.sentence_capture_entry:not(:first) .sentence-capture-btn-add')
//                 .removeClass('btn-add').addClass('btn-remove')
//                 .removeClass('btn-success').addClass('btn-danger')
//                 .html('<span class="icon icon-minus"></span>');
//         }

//         //change name of inputs 
//         scount = scount-1; 
//         var years_name = "data[PrisonerSentenceCount]["+scount+"][years]";
//         $('.scountDiv2 input[name*="years"]:last').attr('name',years_name);

//         var months_name = "data[PrisonerSentenceCount]["+scount+"][months]";
//         $('.scountDiv2 input[name*="months"]:last').attr('name',months_name);

//         var days_name = "data[PrisonerSentenceCount]["+scount+"][days]";
//         $('.scountDiv2 input[name*="days"]:last').attr('name',days_name);

//         var sentence_type_name = "data[PrisonerSentenceCount]["+scount+"][sentence_type]";
//         $('.scountDiv2 input[name*="sentence_type"]:last').attr('name',sentence_type_name);

//         $('.scountDiv2 input[name*="sentence_type"]:last option:selected').removeAttr("selected");

//         $('select').select2({minimumResultsForSearch: Infinity});
//     }
// }

$(function()
{
    <?php 
    if($editSentenceCountData != '' && count($editSentenceCountData) > 0)
    {}
    else
    {?>
        $('#PrisonerSentenceCaptureEditForm #PrisonerSentenceCount0Years').val('');
        $('#PrisonerSentenceCaptureEditForm #PrisonerSentenceCount0Months').val('');
        $('#PrisonerSentenceCaptureEditForm #PrisonerSentenceCount0Days').val('');
    <?php }?> 
    var stype = "<?php echo Configure::read('PRISONER-TYPE-CONSECUTIVE');?>";
    var scount = 0;
    $(document).on('click', '.sentence-capture-btn-add', function(e)
    {
        e.preventDefault();
        $("#sentence_capture_count select").select2("destroy");

        var sentenceCaptureControlForm = $('#sentence_capture_count');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('#sentence_capture_count .sentence_capture_entry:last');
        newEntry = $(currentEntry.clone()).appendTo(sentenceCaptureControlForm);

        newEntry.find('input').val('');
        sentenceCaptureControlForm.find('.sentence_capture_entry:not(:first) .sentence-capture-btn-add')
            .removeClass('sentence-capture-btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');

        //change name of inputs 
        scount = parseInt($('#sentence_capture_count input[name*="years"]').length);
        scount = scount-1; 
        var years_name = "data[PrisonerSentenceCount]["+scount+"][years]";
        $('#sentence_capture_count input[name*="years"]:last').attr('name',years_name);

        var months_name = "data[PrisonerSentenceCount]["+scount+"][months]";
        $('#sentence_capture_count input[name*="months"]:last').attr('name',months_name);

        var days_name = "data[PrisonerSentenceCount]["+scount+"][days]";
        $('#sentence_capture_count input[name*="days"]:last').attr('name',days_name);

        var sentence_type_name = "data[PrisonerSentenceCount]["+scount+"][sentence_type]";
        $('#sentence_capture_count select[name*="sentence_type"]:last').attr('name',sentence_type_name);

        $('#sentence_capture_count select[name*="sentence_type"]:last option:selected').removeAttr("selected");

        $('#sentence_capture_count select').select2({minimumResultsForSearch: Infinity});
        $('#sentence_capture_count select:last').select2('val',stype);
               
    }).on('click', '#sentence_capture_count .btn-remove', function(e)
    {
        $('#sentence_capture_count .sentence_capture_entry:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>