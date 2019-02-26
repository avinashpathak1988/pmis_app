<?php $debtorJudgements = array(); //echo $debtor_case_file_id; exit;
if(isset($debtor_case_file_id) && ($debtor_case_file_id > 0))
{
    $debtorJudgements = $funcall->getDebtorJudgements($debtor_case_file_id);
}
//debug($debtorJudgements); exit;
?>

    <div class="row-fluid secondDiv widget-box" style="padding-bottom: 14px; background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Judgement Debtor Details</h5>
        </div>
        <div class="widget-content">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Date of Admission <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('created',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Date of Admission','required','readonly'=>'readonly', 'value'=> date('d-m-Y')));?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Creditor name <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('creditor_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Enter Creditors name','required','id'=>'creditor_name','title'=>'Please enter Creditor name'));?>
                    </div>
                </div>
            </div> 
            <div class="clearfix"></div> 
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Value of Debt* <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('value_of_debt',array('div'=>false,'label'=>false,'class'=>'form-control span11 nospace numeric','type'=>'text', 'placeholder'=>'Enter Value of debt','required','id'=>'value_of_debt', 'maxlength'=>'15', 'title'=>'Please enter Value of Debt*'));?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Creditor Contact No. :</label>
                    <div class="controls">
                        <?php echo $this->Form->input('creditor_contact_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 phone','type'=>'text', 'placeholder'=>'Enter Creditors Contact No.','required'=>false,'id'=>'creditor_name','title'=>'Please enter Creditor Contact No.', 'maxlength'=>'15'));?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div> 
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">No Pay:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('no_pay',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox', 'id'=>'debtor_no_pay', 'onClick'=>'debtorNoPay()'));?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div> 
            <div class="row-fluid secondDiv" id="payment_list">
                <?php 
                if(isset($debtorJudgements) && count($debtorJudgements) > 0)
                {
                    $i = 0; 
                    $total_paid_days = 0;
                    foreach($debtorJudgements as $debtorJudgement)
                    {
                        $debtorJudgementData = $debtorJudgement['DebtorJudgement'];
                        $total_paid_days = $total_paid_days+$debtorJudgementData['no_of_days_for_amount'];
                        ?>
                        <div class="span12 payment_list" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;">
                            <?php 
                            echo $this->Form->input('DebtorJudgement.'.$i.'.id',array('div'=>false,'label'=>false,'type'=>'hidden','required'=>false, 'value'=> $debtorJudgementData['id']));
                            ?>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">No. Of Days Paid For<?php echo $req; ?>:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('DebtorJudgement.'.$i.'.no_of_days_for_amount',array('div'=>false,'label'=>false,'class'=>'form-control span11 nospace numeric','type'=>'text', 'placeholder'=>'Enter No. Of Days Paid For','required','id'=>$i.'_no_of_days_for_amount','onkeyup'=>'getPaymentDetails(this.value,'.$i.');','title'=>'Please enter No. Of Days Paid For', 'value'=> $debtorJudgementData['no_of_days_for_amount']));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Rate/Day(USh):</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('DebtorJudgement.0.rate_per_day',array('div'=>false,'label'=>false,'class'=>'form-control span11 nospace numeric','type'=>'text', 'readonly'=>'readonly','default'=>$rate_per_day, 'id'=>$i.'_rate_per_day', 'value'=> $debtorJudgementData['rate_per_day']));?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Subsistence allowance :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('DebtorJudgement.'.$i.'.subsistence_allowance',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text', 'placeholder'=>'Enter subsistence allowance','required'=>false, 'id'=>$i.'_subsistence_allowance', 'readonly', 'value'=> $debtorJudgementData['subsistence_allowance']));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label next_payment_date_div" id="<?php echo $i;?>_next_payment_date_div">
                                        <?php if($total_paid_days == 186)
                                        echo 'Final Date Of Release';
                                        else 
                                        {
                                            echo 'Next Payment Date';
                                        }?>
                                    :</label>
                                    <div class="controls">
                                        <?php 
                                        $next_payment_date = date('d-m-Y', strtotime($debtorJudgementData['next_payment_date']));
                                        echo $this->Form->input('DebtorJudgement.'.$i.'.next_payment_date',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'=>false,'readonly'=>'readonly','id'=>$i.'_next_payment_date', 'value'=>$next_payment_date));?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php $i++;
                    }
                }
                else 
                {?>
                    <div class="span12 payment_list" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">No. Of Days Paid For<?php echo $req; ?>:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('DebtorJudgement.0.no_of_days_for_amount',array('div'=>false,'label'=>false,'class'=>'form-control span11 nospace numeric','type'=>'text', 'placeholder'=>'Enter No. Of Days Paid For','required','id'=>'0_no_of_days_for_amount','onkeyup'=>'getPaymentDetails(this.value,0);','title'=>'Please enter No. Of Days Paid For'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Rate/Day(USh):</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('DebtorJudgement.0.rate_per_day',array('div'=>false,'label'=>false,'class'=>'form-control span11 nospace numeric','type'=>'text', 'readonly'=>'readonly','default'=>$rate_per_day, 'id'=>'0_rate_per_day'));?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Subsistence allowance :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('DebtorJudgement.0.subsistence_allowance',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text', 'placeholder'=>'Enter subsistence allowance','required'=>false, 'id'=>'0_subsistence_allowance', 'readonly'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label next_payment_date_div" id="0_next_payment_date_div">Next Payment Date:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('DebtorJudgement.0.next_payment_date',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'=>false,'readonly'=>'readonly','id'=>'0_next_payment_date'));?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <div class="clearfix"></div>
            </div>
            <div class="span12">
                <span class="input-group-btn" id="payment-btn-add">
                    <button class="btn btn-success btn-add payment-btn-add" type="button" style="padding: 8px 8px;">
                        <span class="icon icon-plus"></span>
                        <!-- Add Payment -->
                    </button>
                </span>
                <?php 
                if(isset($debtorJudgements) && count($debtorJudgements) > 0)
                {?>
                    <span class="input-group-btn">
                        <button class="btn btn-add btn-remove btn-danger payment-remove <?php if(count($debtorJudgements) == 1){echo 'hidden';}?>" type="button" style="padding: 8px 8px;float: right;position: absolute;right: 15px;">
                            <span class="icon icon-minus"></span>
                        </button>
                    </span>
            <?php }?>
            </div>
        </div>
    </div>
    
<?php $deleteOffenceUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'deleteDebtorJudgements'));?>
<script type="text/javascript">
function getPaymentDetails(val, cnt)
{
    if(val != '')
    {
        var rate = $('#'+cnt+'_rate_per_day').val();

        if(cnt > 0)
        {
            var prev_cnt = parseInt(cnt)-1;
            var doa = $('#'+prev_cnt+'_next_payment_date').val();
        }
        else 
        {
            var doa = $('#PrisonerAdmissionCreated').val();
        }

        var scount = parseInt($('#payment_list .payment_list').length);

        //get total paid days 
        var total_paid_days = 0;
        for(var i=0; i<scount; i++)
        {
            total_paid_days = total_paid_days+parseInt($('#'+i+'_no_of_days_for_amount').val());
        }

        var max_pay_days = parseInt(186); 
        if(total_paid_days > max_pay_days)
        {
            dynamicAlertBox('Total No. Of Days Paid For should not greater than '+max_pay_days+' days');
            $('#'+cnt+'_subsistence_allowance').val('');
            $('#'+cnt+'_no_of_days_for_amount').val('');
            $('#payment-btn-add').hide();
        }
        else
        {
            var subsistence_allowance = val*parseInt(rate);
            $('#'+cnt+'_subsistence_allowance').val(subsistence_allowance);
            $('#payment-btn-add').show();
            if(total_paid_days == 186)
            {
                $("#"+cnt+"_next_payment_date_div").html('Final Date Of Release');
            }
            else 
            {
                $("#"+cnt+"_next_payment_date_div").html('Next Payment Date');
            }
        }
        paid_days = val;
    }

    var doa_data = doa.split('-');
    var doaDay = doa_data[0];
    var doaMonth = doa_data[1];
    var doaYear = doa_data[2];

    var tt = doaMonth+'/'+doaDay+'/'+doaYear;

    var date = new Date(tt);
    var newdate = new Date(date);

    newdate.setDate(newdate.getDate() + parseInt(val));
    
    var dd = newdate.getDate();
    var mm = newdate.getMonth() + 1;
    var y = newdate.getFullYear();

    var next_payment_date = dd + '-' + mm + '-' + y;

    // var date = new Date(tt);
    // var newdate = new Date(date);

    // newdate.setDate(newdate.getDate() + paid_days);
    
    // var dd = newdate.getDate();
    // var mm = newdate.getMonth() + 1;
    // var y = newdate.getFullYear();

    // var next_payment_date = dd + '-' + mm + '-' + y;

    // var current_date = new Date(doaYear, doaMonth-1, doaDay);
    // alert(current_date);
    // alert(paid_days);
    // current_date.setDate(current_date.getDate() + paid_days);
    // alert(current_date);
    // var dd = current_date.getDate();
    // var mm = current_date.getMonth() + 1;
    // var y = current_date.getFullYear();
    // var next_payment_date = dd + '-' + mm + '-' + y;
    
    $('#'+cnt+'_next_payment_date').val(next_payment_date);
    //update other payments 
    updatePaymentDetails(val, cnt);
}
//update payment details 
function updatePaymentDetails(val, cnt)
{
    //update every payment details 
    var total_count = parseInt($('#payment_list .payment_list').length);
    //alert(total_count);
    if(cnt < total_count)
    {
        for(var i= cnt; i<total_count; i++)
        {
            //alert(i);
        }
    }
}
var prev_sentence_capture_count = '<?php echo $total_sentence_capture_count;?>';

$(function()
{

    $('.pmis_select2').select2({
        placeholder: "-- Select --",
        allowClear: false
    });

    var scount = 0;
    $(document).on('click', '.payment-btn-add', function(e)
    {
        e.preventDefault();

        var paymentForm = $('#payment_list');
        var currentEntry   =  $('#payment_list .payment_list:last');
        var newEntry = $(currentEntry.clone()).appendTo(paymentForm);

        //newEntry.find('input').val('');

        paymentForm.find('.payment_list:not(:first) .payment-remove')
            .removeClass('hidden');
        //change name of inputs 
        scount = parseInt($('#payment_list .payment_list').length);
        scount = scount-1; 
        
        var no_of_days_for_amount_name = "data[DebtorJudgement]["+scount+"][no_of_days_for_amount]";
        $('#payment_list input[name*="no_of_days_for_amount"]:last').attr('name',no_of_days_for_amount_name);
        $('#payment_list input[name*="no_of_days_for_amount"]:last').attr("id",scount+"_no_of_days_for_amount");

        $('#payment_list input[name*="id"]:last').remove();

        $("#"+scount+"_no_of_days_for_amount").removeAttr('onkeyup');
        $("#"+scount+"_no_of_days_for_amount").val('');

        var onchangeFunction = 'getPaymentDetails(this.value,'+scount+');';
        $('#payment_list input[name*="no_of_days_for_amount"]:last').attr("onkeyup",onchangeFunction);

        var rate_per_day_name = "data[DebtorJudgement]["+scount+"][rate_per_day]";
        $('#payment_list input[name*="rate_per_day"]:last').attr('name',rate_per_day_name);

        $('#payment_list input[name*="rate_per_day"]:last').attr("id",scount+"_rate_per_day");

        var subsistence_allowance_name = "data[DebtorJudgement]["+scount+"][subsistence_allowance]";
        $('#payment_list input[name*="subsistence_allowance"]:last').attr('name',subsistence_allowance_name);
        $('#payment_list input[name*="subsistence_allowance"]:last').attr("id",scount+"_subsistence_allowance");
        $("#"+scount+"_subsistence_allowance").val('');

        var next_payment_date_name = "data[DebtorJudgement]["+scount+"][next_payment_date]";
        $('#payment_list input[name*="next_payment_date"]:last').attr('name',next_payment_date_name);
        $('#payment_list input[name*="next_payment_date"]:last').attr("id",scount+"_next_payment_date");
        
        $("#payment_list .next_payment_date_div:last").attr("id",scount+"_next_payment_date_div");
        $("#"+scount+"_next_payment_date").val('');

        //show remove payment button 
        $('.payment-remove').removeClass('hidden');
               
    }).on('click', '.payment-remove', function(e)
    {
        AsyncConfirmYesNo(
            "Are you sure want to delete the last record?",
            'Delete',
            'Cancel',
            function(){
                
                var id = $('#payment_list .payment_list:last input[name*="id"]').val();
                
                if(id != undefined)
                {
                    var url = '<?php echo $deleteOffenceUrl;?>';
                    $.post(url, {'paramId':id}, function(res) { 
                        if(res == 'SUCC'){
                            $('#payment_list .payment_list:last').remove();
                        }else{
                            alert('Invalid request, please try again!');
                        }
                    });
                }
                else 
                {
                    $('#payment_list .payment_list:last').remove();
                }
                var scount = parseInt($('#payment_list .payment_list').length);
                if(scount == 1)
                {
                    $('.payment-remove').addClass('hidden');
                }
                e.preventDefault();
                return false;
            },
            function(){}
        );
        
  });
    //Debtor no pay -- START -- 
    $('#debtor_no_pay').click(function(){
        if($(this).prop("checked") == true){
            //hide payment div
            $('#payment_list').hide();
            $('#payment-btn-add').hide();
        }
        else if($(this).prop("checked") == false){
            //show payment div
            $('#payment_list').show();
            $('#payment-btn-add').show();
        }
    });
    //Debtor no pay -- END -- 
});
</script>
