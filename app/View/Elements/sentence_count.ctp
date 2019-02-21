
<div class="span12" style="margin-top: 10px;">
    <div style="width: 90%;margin: 0 auto;">
        <div class="scountDiv"> 
            
        </div>
    </div>
</div>
<div id="dataFormat" style="display:none">
    <div class="entry input-group col-xs-3 new">
        <div style="float: left; margin: 5px;" class="scount_sl">Count</div>
        <?php echo $this->Form->input('PrisonerSentenceCount.0.years',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Years','required'=>false));?>
        <?php echo $this->Form->input('PrisonerSentenceCount.0.months',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Months','required'=>false));?>
        <?php echo $this->Form->input('PrisonerSentenceCount.0.days',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Days','required'=>false));?>
        <?php echo $this->Form->input('PrisonerSentenceCount.0.sentence_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$sentenceTypeList, 'required'=>false, 'style'=>'width:20%', 'selected'=>''));?>
        <span class="input-group-btn">
            <button class="btn btn-success btn-add" type="button" style="padding: 8px 8px;">
                <span class="icon icon-plus"></span>
            </button>
        </span>
    </div>     
</div>          
<style type="text/css">
    .scountDiv input {
    margin-bottom: 10px;
}
</style>
<script type="text/javascript">
var prevScount = 0;
function addSentenceCount()
{
    $('.scountDiv .entry.new').remove();

    if(prevScount == 0)
    {
        $("select").select2("destroy");

        var controlForm = $('.scountDiv');
        var currentEntry   =   $('#dataFormat .entry.new');
        var newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');

        //get total sentence count length 
        var scount = parseInt($('.scountDiv input[name*="years"]').length);
        if(scount > 1)
        {
            controlForm.find('.entry:not(:first) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="icon icon-minus"></span>');
        }

        //change name of inputs 
        scount = scount-1;
        var years_name = "data[PrisonerSentenceCount]["+scount+"][years]";
        $('.scountDiv input[name*="years"]:last').attr('name',years_name);

        var months_name = "data[PrisonerSentenceCount]["+scount+"][months]";
        $('.scountDiv input[name*="months"]:last').attr('name',months_name);

        var days_name = "data[PrisonerSentenceCount]["+scount+"][days]";
        $('.scountDiv input[name*="days"]:last').attr('name',days_name);

        var sentence_type_name = "data[PrisonerSentenceCount]["+scount+"][sentence_type]";
        $('.scountDiv input[name*="sentence_type"]:last').attr('name',sentence_type_name);

        $('.scountDiv input[name*="sentence_type"]:last option:selected').remove();

        $('select').select2({minimumResultsForSearch: Infinity});
    }
}

$(function()
{
    var scount = 0;
    $(document).on('click', '.btn-add', function(e)
    {
        e.preventDefault();
        $("select").select2("destroy");

        var controlForm = $('.scountDiv');
        currentEntry   =   $('#dataFormat .entry');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');
        controlForm.find('.entry:not(:first) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');

        //change name of inputs 
        scount = parseInt($('.scountDiv input[name*="years"]').length);
        scount = scount-1;
        var years_name = "data[PrisonerSentenceCount]["+scount+"][years]";
        $('.scountDiv input[name*="years"]:last').attr('name',years_name);

        var months_name = "data[PrisonerSentenceCount]["+scount+"][months]";
        $('.scountDiv input[name*="months"]:last').attr('name',months_name);

        var days_name = "data[PrisonerSentenceCount]["+scount+"][days]";
        $('.scountDiv input[name*="days"]:last').attr('name',days_name);

        var sentence_type_name = "data[PrisonerSentenceCount]["+scount+"][sentence_type]";
        $('.scountDiv input[name*="sentence_type"]:last').attr('name',sentence_type_name);

        $('.scountDiv input[name*="sentence_type"]:last option:selected').remove();

        $('select').select2({minimumResultsForSearch: Infinity});
               
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.entry:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>