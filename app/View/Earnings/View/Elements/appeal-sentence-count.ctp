
<div class="span12" style="margin-top: 10px;">
    <div style="width: 90%;margin: 0 auto;">
        <div id="sentence_appeal_count"> 
            <div class="input-group col-xs-3">
                <div style="width:10%; float: left; margin: 5px;" class="scount_sl"></div>
                <div style="width:20%; float: left; margin: 5px;" class="scount_sl">Years</div>
                <div style="width:20%; float: left; margin: 5px;" class="scount_sl">Months</div>
                <div style="width:20%; float: left; margin: 5px;" class="scount_sl">Days</div>
                <div style="width:20%; float: left; margin: 5px;" class="scount_sl">Sentence Type</div>
            </div>
            <div class="clearfix"></div>
            <?php 
            $total_scount = 0;
            if(isset($this->request->data['PrisonerSentenceCapture']['sentenceCountData']) && count($this->request->data['PrisonerSentenceCapture']['sentenceCountData']) > 0)
            {
                $editSentenceCountData  =   $this->request->data['PrisonerSentenceCapture']['sentenceCountData'];
            }
            if($editSentenceCountData != '' && count($editSentenceCountData) > 0)
            {
                $i = 0;
                $total_scount = count($editSentenceCountData);
                //echo '<pre>'; print_r($editSentenceCountData); exit;
                foreach($editSentenceCountData as $SentenceCount)
                {
                    $SentenceCount = $SentenceCount['PrisonerSentenceCount'];
                    ?>
                    <div class="sentence_appeal_entry input-group col-xs-3" style="margin-bottom:5px;">
                        <div style="float: left; margin: 5px;" class="scount_sl">Count</div>
                        <?php echo $this->Form->input('PrisonerSentenceCount.'.$i.'.years',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Years','required'=>false, 'value'=>$SentenceCount['years']));?>
                        <?php echo $this->Form->input('PrisonerSentenceCount.'.$i.'.months',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Months','required'=>false, 'value'=>$SentenceCount['months']));?>
                        <?php echo $this->Form->input('PrisonerSentenceCount.'.$i.'.days',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Days','required'=>false, 'value'=>$SentenceCount['days'] ));?>
                        <?php echo $this->Form->input('PrisonerSentenceCount.'.$i.'.sentence_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$sentenceTypeList, 'required'=>false, 'style'=>'width:20%', 'selected'=>$SentenceCount['sentence_type']));?>
                        <?php if($i == 0)
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-add sentence-appeal-btn-add" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                    <span class="icon icon-plus"></span>
                                </button>
                            </span>
                        <?php }
                        else 
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-danger btn-remove" type="button" style="padding: 8px 8px;">
                                    <span class="icon icon-minus"></span>
                                </button>
                            </span>
                        <?php }?>
                        
                    </div>
                <?php $i++;
                }
            }
            else 
            {?>
                <div class="sentence_appeal_entry input-group col-xs-3" style="margin-bottom:5px;">
                    <div style="float: left; margin: 5px;" class="scount_sl">Count</div>
                    <?php echo $this->Form->input('PrisonerSentenceCount.0.years',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Years','required'=>false));?>
                    <?php echo $this->Form->input('PrisonerSentenceCount.0.months',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Months','required'=>false));?>
                    <?php echo $this->Form->input('PrisonerSentenceCount.0.days',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Days','required'=>false));?>
                    <?php echo $this->Form->input('PrisonerSentenceCount.0.sentence_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$sentenceTypeList,'required'=>false, 'style'=>'width:20%','default'=>Configure::read('PRISONER-TYPE-CONSECUTIVE')));?>
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-add sentence-appeal-btn-add" type="button" style="padding: 8px 8px;">
                            <span class="icon icon-plus"></span>
                        </button>
                    </span>
                </div> 
            <?php }?>
        </div>
    </div>
</div>        
<style type="text/css">
    .scountDiv2 input {
    margin-bottom: 10px;
}
</style>
<script type="text/javascript">
var prevScount = '<?php echo $total_scount;?>';
// function addSentenceCount()
// {
//     $('.scountDiv2 .entry3.new').remove();

//     if(prevScount == 0)
//     {
//         $("select").select2("destroy");

//         var controlForm = $('.scountDiv2');
//         //var currentEntry   =   $('#dataFormat .entry.new');
//         var currentEntry   =   $('.entry3:last');
//         var newEntry = $(currentEntry.clone()).appendTo(controlForm);

//         newEntry.find('input').val('');

//         //get total sentence count length 
//         var scount = parseInt($('.scountDiv2 input[name*="years"]').length);
//         if(scount > 1)
//         {
//             controlForm.find('.entry3:not(:first) .btn-add')
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
    var stype = "<?php echo Configure::read('PRISONER-TYPE-CONSECUTIVE');?>";
    var scount = 0;
    $(document).on('click', '.sentence-appeal-btn-add', function(e)
    {
        e.preventDefault();
        $("select").select2("destroy");

        var sentenceAppealControlForm = $('#sentence_appeal_count');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('.sentence_appeal_entry:last');
        newEntry = $(currentEntry.clone()).appendTo(sentenceAppealControlForm);

        newEntry.find('input').val('');
        sentenceAppealControlForm.find('.sentence_appeal_entry:not(:first) .sentence-appeal-btn-add')
            .removeClass('sentence-appeal-btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');

        //set blank value for input fields 
        $('#sentence_appeal_count input[name*="years"]:last').val('');
        $('#sentence_appeal_count input[name*="months"]:last').val('');
        $('#sentence_appeal_count input[name*="days"]:last').val('');
        //change name of inputs 
        scount = parseInt($('#sentence_appeal_count input[name*="years"]').length);
        scount = scount-1; 
        var years_name = "data[PrisonerSentenceCount]["+scount+"][years]";
        $('#sentence_appeal_count input[name*="years"]:last').attr('name',years_name);

        var months_name = "data[PrisonerSentenceCount]["+scount+"][months]";
        $('#sentence_appeal_count input[name*="months"]:last').attr('name',months_name);

        var days_name = "data[PrisonerSentenceCount]["+scount+"][days]";
        $('#sentence_appeal_count input[name*="days"]:last').attr('name',days_name);

        var sentence_type_name = "data[PrisonerSentenceCount]["+scount+"][sentence_type]";
        $('#sentence_appeal_count select[name*="sentence_type"]:last').attr('name',sentence_type_name);

        //$('#sentence_appeal_count select[name*="sentence_type"]:last option:selected').removeAttr("selected");
        

        $('select').select2({minimumResultsForSearch: Infinity});
        $('#sentence_appeal_count select[name*="sentence_type"]:last').select2('val',stype);
               
    }).on('click', '#sentence_appeal_count .btn-remove', function(e)
    {
        $('#sentence_appeal_count .sentence_appeal_entry:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>