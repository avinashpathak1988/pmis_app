<div class="container-fluid"><hr>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> 
                  <h5>Prisoner's Deatils </h5>
                </div>
                <div class="widget-content">
                    <div class="container">
                        <div class="control-group" id="fields">
                            <label class="control-label" for="field1">Sentence Counts</label>
                            <div class="controls"> 
                                <form role="form" autocomplete="off">
                                    <div class="entry input-group col-xs-3">
                                        <div style="float: left; margin: 5px;" class="scount_sl">C (1)</div>
                                        <?php echo $this->Form->input('years[]',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Years','required'));?>
                                        <?php echo $this->Form->input('months[]',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Months','required'));?>
                                        <?php echo $this->Form->input('days[]',array('div'=>false,'label'=>false,'class'=>'form-control numeric span3','type'=>'text', 'placeholder'=>'Days','required'));?>
                                        <?php echo $this->Form->input('sentence_type[]',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$sentenceTypeList, 'empty'=>array('0'=>'-- Sentence Type --'),'required', 'style'=>'width:20%'));?>
                                        <span class="input-group-btn">
                                            <button class="btn btn-success btn-add" type="button" style="padding: 8px 8px;">
                                                <span class="icon icon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(function()
{
    var scount = 0;
    $(document).on('click', '.btn-add', function(e)
    {
        e.preventDefault();
        $("select").select2("destroy");

        var controlForm = $('.controls form:first'),
        currentEntry = $(this).parents('.entry:first'),
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        scount = parseInt($('input[name*="years"]').length);
        var scount_data = "("+scount+")";
        $('.scount_sl:last').html('C '+scount_data);

        newEntry.find('input').val('');
        controlForm.find('.entry:not(:last) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');

        $('#s2id_autogen1:last')
            .attr('id','s2id_autogen'+scount);

        $('select').select2({minimumResultsForSearch: Infinity});
               
    }).on('click', '.btn-remove', function(e)
    {
    $(this).parents('.entry:first').remove();

    e.preventDefault();
    return false;
  });
});
</script>