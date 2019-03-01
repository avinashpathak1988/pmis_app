
<div class="span12" style="margin-top: 10px;">
    <div style="width: 90%;margin: 0 auto;">
        <div class="scountDiv" id="admission_sentence_count"> 
            <div class="input-group col-xs-3">
                <div class="scount_sl span2"></div>
                <div class="scount_sl span2" style="padding-left: 10px;">Years</div>
                <div class="scount_sl span2" style="padding-left: 12px;">Months</div>
                <div class="scount_sl span2" style="padding-left: 15px;">Days</div>
                <div class="scount_sl span4" style="padding-left: 5px;">Sentence Type</div>
				<div class="scount_sl span1"></div>
            </div>
            <div class="clearfix"></div>
            <?php 
            $total_scount = 0;
            //debug($sentenceCountData); //exit;
            if($sentenceCountData != '' && count($sentenceCountData) > 0)
            {
                $i = 0;
                $total_scount = count($sentenceCountData);
                //for($i=$total_scount; $i>0; $i--)
                foreach($sentenceCountData as $SentenceCount)
                {
                    $SentenceCount = $SentenceCount['PrisonerSentenceCount'];
                    ?>
                    <div class="entry input-group col-xs-3">
                        <div class="scount_sl span2">Count</div>
                                                
                        <?php echo $this->Form->input('PrisonerSentenceCount.'.$i.'.years',array('div'=>false,'label'=>false,'class'=>'form-control numeric span2','type'=>'text', 'placeholder'=>'Years','required'=>false, 'value'=>$SentenceCount['years']));?>
                        <?php echo $this->Form->input('PrisonerSentenceCount.'.$i.'.months',array('div'=>false,'label'=>false,'class'=>'form-control numeric span2','type'=>'text', 'placeholder'=>'Months','required'=>false, 'value'=>$SentenceCount['months']));?>
                        <?php echo $this->Form->input('PrisonerSentenceCount.'.$i.'.days',array('div'=>false,'label'=>false,'class'=>'form-control numeric span2','type'=>'text', 'placeholder'=>'Days','required'=>false, 'value'=>$SentenceCount['days'] ));?>
                        <?php echo $this->Form->input('PrisonerSentenceCount.'.$i.'.sentence_type',array('div'=>false,'label'=>false,'class'=>'form-control span4','type'=>'select','options'=>$sentenceTypeList, 'required'=>false, 'style'=>'margin-top:-10px;margin-right:5px;', 'selected'=>$SentenceCount['sentence_type']));?>
                        <?php if($i == 0)
                        {?>
                            <div class="input-group-btn span1 pull-right">
                                <button class="btn btn-success btn-add admission-sentence-btn-add" type="button" style="padding:10px 10px;position:relative;">
                                    <span class="icon icon-plus"></span>
                                </button>
                            </div>
                        <?php }
                        else 
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-danger btn-remove" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
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
                <div class="entry input-group col-xs-3">
                    <div style="float: left; margin: 5px;" class="scount_sl span2">Count</div>
                    <?php echo $this->Form->input('PrisonerSentenceCount.0.years',array('div'=>false,'label'=>false,'class'=>'form-control span2 numeric','type'=>'text', 'placeholder'=>'Years','required'=>false));?>
                    <?php echo $this->Form->input('PrisonerSentenceCount.0.months',array('div'=>false,'label'=>false,'class'=>'form-control span2 numeric','type'=>'text', 'placeholder'=>'Months','required'=>false));?>
                    <?php echo $this->Form->input('PrisonerSentenceCount.0.days',array('div'=>false,'label'=>false,'class'=>'form-control span2 numeric','type'=>'text', 'placeholder'=>'Days','required'=>false));?>
                    <?php echo $this->Form->input('PrisonerSentenceCount.0.sentence_type',array('div'=>false,'label'=>false,'class'=>'form-control span4','type'=>'select','options'=>$sentenceTypeList, 'required'=>false, 'style'=>'float:left','default'=>Configure::read('PRISONER-TYPE-CONSECUTIVE')));?>
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-add admission-sentence-btn-add" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                            <span class="icon icon-plus"></span>
                        </button>
                    </span>
                </div> 
            <?php }?>
        </div>
    </div>
</div>
          
<style type="text/css">
    .scountDiv input {
    margin-bottom: 10px;
}
</style>
<script type="text/javascript">
var prevScount = '<?php echo $total_scount;?>';
// function addSentenceCount2()
// {
//     $('#admission_sentence_count .entry.new').remove();

//     if(prevScount == 0)
//     {
//         $("#admission_sentence_count select").select2("destroy");

//         var admissionControlForm = $('#admission_sentence_count');
//         //var currentEntry   =   $('#dataFormat .entry.new');
//         var currentEntry   =   $('.entry:last');
//         var newEntry = $(currentEntry.clone()).appendTo(admissionControlForm);

//         newEntry.find('input').val('');

//         //get total sentence count length 
//         var scount = parseInt($('.scountDiv input[name*="years"]').length);
//         if(scount > 1)
//         {
//             admissionControlForm.find('.entry:not(:first) .admission-sentence-btn-add')
//                 .removeClass('btn-add').addClass('btn-remove')
//                 .removeClass('btn-success').addClass('btn-danger')
//                 .html('<span class="icon icon-minus"></span>');
//         }

//         //change name of inputs 
//         scount = scount-1;
//         var years_name = "data[PrisonerSentenceCount]["+scount+"][years]";
//         $('.scountDiv input[name*="years"]:last').attr('name',years_name);

//         var months_name = "data[PrisonerSentenceCount]["+scount+"][months]";
//         $('.scountDiv input[name*="months"]:last').attr('name',months_name);

//         var days_name = "data[PrisonerSentenceCount]["+scount+"][days]";
//         $('.scountDiv input[name*="days"]:last').attr('name',days_name);

//         var sentence_type_name = "data[PrisonerSentenceCount]["+scount+"][sentence_type]";
//         $('.scountDiv select[name*="sentence_type"]:last').attr('name',sentence_type_name);

//         $('.scountDiv select[name*="sentence_type"]:last option:selected').removeAttr("selected");

//         $('select').select2({minimumResultsForSearch: Infinity});
//     }
// }

$(function()
{
    var stype = "<?php echo Configure::read('PRISONER-TYPE-CONSECUTIVE');?>";
    var scount = 0;
    $(document).on('click', '.admission-sentence-btn-add', function(e)
    {
        e.preventDefault();
        $("#admission_sentence_count select").select2("destroy");

        var admissionControlForm = $('#admission_sentence_count'); 
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('#admission_sentence_count .entry:last');
        newEntry = $(currentEntry.clone()).appendTo(admissionControlForm);

        newEntry.find('input').val('');
        admissionControlForm.find('.entry:not(:first) .admission-sentence-btn-add')
            .removeClass('admission-sentence-btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');

        //change name of inputs 
        scount = parseInt($('#admission_sentence_count input[name*="years"]').length);
        scount = scount-1;
        var years_name = "data[PrisonerSentenceCount]["+scount+"][years]";
        $('#admission_sentence_count input[name*="years"]:last').attr('name',years_name);

        var months_name = "data[PrisonerSentenceCount]["+scount+"][months]";
        $('#admission_sentence_count input[name*="months"]:last').attr('name',months_name);

        var days_name = "data[PrisonerSentenceCount]["+scount+"][days]";
        $('#admission_sentence_count input[name*="days"]:last').attr('name',days_name);

        var sentence_type_name = "data[PrisonerSentenceCount]["+scount+"][sentence_type]";
        $('#admission_sentence_count select[name*="sentence_type"]:last').attr('name',sentence_type_name);

        //$('#admission_sentence_count select[name*="sentence_type"]:last option:selected').remove();
        

        $('#admission_sentence_count select').select2({minimumResultsForSearch: Infinity});

        $('#admission_sentence_count select[name*="sentence_type"]:last').select2('val',stype);
               
    }).on('click', '#admission_sentence_count .btn-remove', function(e)
    {
        $(this).parents('.entry:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>