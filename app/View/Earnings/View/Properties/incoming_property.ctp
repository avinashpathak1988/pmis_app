
<div class="container-fluid"><hr>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> 
                  <h5>Property page </h5>
                </div>
                <div class="widget-content">
                	<div class="">
                        <ul class="nav nav-tabs">
                            <li><a href="#Property" id="medicalChekupDiv">Incoming Property</a></li>
                            <li><a href="#Transaction" id="medicalSickDiv">Transaction</a></li>
                            <li class="controls pull-right">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="tabscontent">
                        	<div id="Property" class="uradioBtn" >
                        		<h4>Property Type : </h4>
                        		  <div>
                                   <input id="radio1" type="radio" name="property_type" value="cash" onclick="show();"> Cash   
                                  </div>
                                  <div>
                                   <input id="radio2" type="radio" name="property_type" value="physical" onclick="show();"> Physical Property   
                                  </div>
                        	</div>
                                <div id="div1" align="center">
                                    <div class="row" style="padding-bottom: 14px;">
                                        <div class="clearfix"></div> 
                                        <div class="row">
                                            <div class="span6">
                                                Date & Time : <input type="text" name="" class="datetimepicker" placeholder="Enter date & time" >
                                            </div>
                                            <div class="span6">
                                                Description : <textarea rows="2" cols="50"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="span6">
                                                Source : <textarea rows="2" cols="50">Enter Source</textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="scountDiv"> 
                                            <div class="entry2 input-group col-xs-3">
                                            Amount : <input type="text" id="PrisonerSentenceCount0Years" maxlength="11" placeholder="Amount" class="form-control numeric" name="data[PrisonerSentenceCount][0][years]">                    
                                            Currency : <input type="text" id="PrisonerSentenceCount0Months" maxlength="11" placeholder="Currency" class="form-control numeric" name="data[PrisonerSentenceCount][0][months]">                    
                                                                    
                                        <span class="input-group-btn">
                                            <button style="padding: 8px 8px;margin-bottom: 13px;" type="button" class="btn btn-success btn-add">
                                                    <span class="icon icon-plus"></span>
                                                </button>
                                            </span>
                                            </div> 
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="row"> 
                                            <div class="span12">
                                                <button type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="div2" align="center">
                                   <div class="row" style="padding-bottom: 14px;">
                                        <div class="clearfix"></div> 
                                        <div class="row">
                                            <div class="span6">
                                                Date & Time : <input type="text" name="" class="datetimepicker" placeholder="Enter date & time" >
                                            </div>
                                            <div class="span6">
                                                Description : <textarea rows="2" cols="50"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="span6">
                                                Source : <textarea rows="2" cols="50"></textarea>
                                            </div>
                                        </div>
                                        <!--
                                       <div class="clearfix"></div>
                                        <div class="scountDiv"> 
                                            <div class="entry2 input-group col-xs-3">
                                            Item : <input type="text" id="PrisonerSentenceCount0Years" maxlength="11" placeholder="Item" class="form-control numeric" name="data[PrisonerSentenceCount][0][years]">                    
                                            Bag No. : <input type="text" id="PrisonerSentenceCount0Months" maxlength="11" placeholder="Bag" class="form-control numeric" name="data[PrisonerSentenceCount][0][months]">                    
                                            Quantity : <input type="text" id="PrisonerSentenceCount0Years" maxlength="11" placeholder="Quantity" class="form-control numeric" name="data[PrisonerSentenceCount][0][years]">                    
                                            Type : <input type="text" id="PrisonerSentenceCount0Months" maxlength="11" placeholder="Type" class="form-control numeric" name="data[PrisonerSentenceCount][0][months]">                       
                                        <span class="input-group-btn">
                                            <button style="padding: 8px 8px;margin-bottom: 13px;" type="button" class="btn btn-success btn-add">
                                                    <span class="icon icon-plus"></span>
                                                </button>
                                            </span>
                                            </div> 
                                        </div> -->
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="span12">
                                                <button type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                        	<div id="Transaction" align="center">
                        		<div class="row" style="padding-bottom: 14px;">
                                    <div class="clearfix"></div> 
                                    <div class="row">
                                        <div class="span6">
                                            Start Date : <input type="text" name="" class="datetimepicker" placeholder="Enter start date" >
                                        </div>
                                        <div class="span6">
                                            End Date : <input type="text" name="" class="datetimepicker" placeholder="Enter end date" >
                                        </div>
                                    </div>
                                </div>
                        	</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var tab_param = '';
var tabs;

jQuery(function($) {

    tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
    // Next and prev actions
    $('.controls a').on('click', function(e) {
        var action = $(this).attr('href').replace('#', ''); 
        tabs[action]();
        e.preventDefault();
    });
$(document).ready(function(){
	//$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	$('.datetimepicker').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
	if($('#prisoner_no').val() != '')
	{
		getPrisonerStationInfo($('#prisoner_no').val());
	}
    });
        $('#radio1').click(function () {
           $('#div2').hide('fast');
           $('#div1').show('fast');
    });
        $('#radio2').click(function () {
          $('#div1').hide('fast');
          $('#div2').show('fast');
    });
function addSentenceCount()
{
    $('.scountDiv .entry2.new').remove();

    if(prevScount == 0)
    {
        $("select").select2("destroy");

        var controlForm = $('.scountDiv');
        //var currentEntry   =   $('#dataFormat .entry.new');
        var currentEntry   =   $('.entry2:last');
        var newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');

        //get total sentence count length 
        var scount = parseInt($('.scountDiv input[name*="years"]').length);
        if(scount > 1)
        {
            controlForm.find('.entry2:not(:first) .btn-add')
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

        $('.scountDiv input[name*="sentence_type"]:last option:selected').removeAttr("selected");

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
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('.entry2:last');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');
        controlForm.find('.entry2:not(:first) .btn-add')
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

        $('select').select2({minimumResultsForSearch: Infinity});
               
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.entry2:last').remove();
        e.preventDefault();
        return false;
  });
});
});

</script>

 