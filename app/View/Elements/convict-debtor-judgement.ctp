<?php 
$judicialOfficerLevel = 'Presiding Judicial Officer';
$debtorJudgements = array(); 
$debtorCaseFile = array(); 
$debtor_casefile_id = '';
$debtor_court_file_no = '';
$debtor_highcourt_file_no = '';
$debtor_case_file_no = '';
$debtor_crb_no = '';
$debtor_courtlevel_id = '';
$debtor_court_id = '';
$debtor_magisterial_id = '';
$judicial_officers = array();
$debtor_case_status = 'Draft';

if(isset($debtor_case_file_id) && ($debtor_case_file_id > 0))
{
    $debtorJudgements = $funcall->getDebtorJudgements($debtor_case_file_id);
    //debug($debtorJudgements); exit;
    if(isset($this->data['PrisonerAdmission']['id']) && !empty($this->data['PrisonerAdmission']['id']))
    {
        $debtorCaseFile = $funcall->getPrisonerCaseFiles($this->data['PrisonerAdmission']['id'],'Debtor');
    }
    
    if(isset($debtorCaseFile[0]))
    {
        $debtorCaseFile = $debtorCaseFile[0];

        $debtor_case_status = $debtorCaseFile['PrisonerCaseFile']['status'];

        $judicialOfficerLevel = $funcall->getJudicialOfficerLevel($debtorCaseFile['PrisonerCaseFile']['courtlevel_id']);

        $debtor_casefile_id = $debtorCaseFile['PrisonerCaseFile']['id'];
        $debtor_court_file_no = $debtorCaseFile['PrisonerCaseFile']['court_file_no'];
        $debtor_highcourt_file_no = $debtorCaseFile['PrisonerCaseFile']['highcourt_file_no'];
        $debtor_case_file_no = $debtorCaseFile['PrisonerCaseFile']['case_file_no'];
        $debtor_crb_no = $debtorCaseFile['PrisonerCaseFile']['crb_no'];
        $debtor_courtlevel_id = $debtorCaseFile['PrisonerCaseFile']['courtlevel_id'];
        $debtor_court_id = $debtorCaseFile['PrisonerCaseFile']['court_id'];
        $debtor_magisterial_id = $debtorCaseFile['PrisonerCaseFile']['magisterial_id'];
        $judicial_officers = explode(',',$debtorCaseFile['PrisonerCaseFile']['judicial_officer']);
    }
}
$disableFieldClass = '';
if($debtor_case_status != 'Draft')
{
    $disableFieldClass = 'field_disable';
}
?>

    <div class="row-fluid secondDiv widget-box" style="padding-bottom: 14px; background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Judgement Debtor Details</h5>
        </div>
        <div class="widget-content <?php echo $disableFieldClass;?>">

            <!-- Court details START-->
            <div class="row-fluid secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
                <div class="widget-title">
                    <h5>Court Details</h5>
                </div>
                <?php echo $this->Form->input('Debtor.PrisonerCaseFile.0.id',array('div'=>false,'label'=>false,'type'=>'hidden', 'value'=>$debtor_casefile_id)); 
                echo $this->Form->input('Debtor.PrisonerCaseFile.0.file_type',array('div'=>false,'label'=>false,'type'=>'hidden','value'=>'Debtor'));?>
                <div class="widget-content">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Court File No<span id="court_file_no_reqd"><?php echo $req;?></span> :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('Debtor.PrisonerCaseFile.0.court_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace convict','type'=>'text','placeholder'=>"Enter Court File No",'required'=>false, 'id'=>'0_court_file_no', 'maxlength'=>'30', 'title'=>'Court File No is required.','value'=>$debtor_court_file_no));?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Civil Suit No. :</label>
                            <div class="controls">
                                <?php echo $this->Form->text('Debtor.PrisonerCaseFile.0.case_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Civil Suit No.','required'=>false,'id'=>'0_case_file_no', 'maxlength'=>'30', 'title'=>'Civil Suit No. is required.','value'=>$debtor_case_file_no));?>
                            </div>
                        </div>
                    </div> 
                    <div class="clearfix"></div>  
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">C.R.B No<span id="crb_no_reqd" class="hidden"><?php echo $req;?></span>:</label>
                            <div class="controls">
                                <?php echo $this->Form->input('Debtor.PrisonerCaseFile.0.crb_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Enter C.R.B No.','required'=>false,'id'=>'0_crb_no', 'maxlength'=>'30', 'title'=>'C.R.B No is required.','value'=>$debtor_crb_no));?> 
                            </div>
                        </div>
                    </div> 
                     
                    <div class="clearfix"></div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Court Category<?php echo $req; ?>:</label>
                            <div class="controls">
                                <?php 
                                $debtor_id = "'debtor'";
                                echo $this->Form->input('Debtor.PrisonerCaseFile.0.courtlevel_id',array('div'=>false,'label'=>false,'onChange'=>'getCourtList(this.value, '.$debtor_id.')','class'=>'form-control span11 court pmis_select convict','type'=>'select','options'=>$courtLevelList, 'empty'=>'','required'=>false,'id'=>'debtor_courtlevel_id', 'title'=>'Select Court Category','value'=>$debtor_courtlevel_id));?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Court Name<?php echo $req; ?>:</label>
                            <div class="controls">
                                <?php 
                                if($debtor_courtlevel_id != '')
                                    $courtList = $funcall->getCourtList($debtor_courtlevel_id);
                                echo $this->Form->input('Debtor.PrisonerCaseFile.0.court_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select convict','type'=>'select','options'=>$courtList, 'onChange'=>'getCourtDetails(this.value)','empty'=>'','required'=>false,'id'=>'debtor_court_id', 'title'=>'Select court name','value'=>$debtor_court_id));?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Jurisdiction area.:</label>
                            <div class="controls">
                                <?php
                                    echo $this->Form->input('Debtor.PrisonerCaseFile.0.magisterial_id',array(
                                      'div'=>false,
                                      'label'=>false,
                                      'type'=>'select',
                                      'class'=>'pmis_select',
                                      'options'=>$magisterialList, 'empty'=>'',
                                      'required'=>false,'title'=>"Please select Jurisdiction area","id"=>"debtor_magisterial_id",'value'=>$debtor_magisterial_id
                                    ));
                                 ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" id="debtor_magistrate_level"><?php echo $judicialOfficerLevel;?>:</label>
                            <div class="controls" id="debtor_judges">
                                <?php 
                                $isRemoveJOfficer = 'hidden';
                                if(count($judicial_officers) > 1)
                                {
                                    $isRemoveJOfficer = '';
                                }
                                $isAddJOfficer = 'hidden';
                                if(in_array($debtor_courtlevel_id,array(9,10)))
                                {
                                    $isAddJOfficer = '';
                                }
                                //debug($judicial_officers); exit;
                                if(count($judicial_officers) > 0)
                                {
                                    $j = 0; $judicial_officer_style = 'margin-top:0px';
                                    foreach($judicial_officers as $judicial_officer)
                                    {
                                        if($j > 0)
                                        {
                                            $judicial_officer_style = 'margin-top:5px';
                                        }
                                        
                                        echo $this->Form->text('Debtor.PrisonerCaseFile.'.$j.'.judicial_officer.',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp judicial_officer','type'=>'text', 'placeholder'=>$judicialOfficerLevel,'id'=>$c.'_judicial_officer', 'value'=> $judicial_officer,'title'=>$judicialOfficerLevel.' is required.','style'=>$judicial_officer_style));
                                        $j++;
                                    }
                                }
                                else 
                                {
                                    echo $this->Form->text('Debtor.PrisonerCaseFile.0.judicial_officer.',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp','type'=>'text', 'placeholder'=>'Judicial Officer','required'=>false,'id'=>'debtor_judicial_officer'));
                                }
                                ?>
                            </div>
                            <button class="btn btn-success <?php echo $isAddJOfficer;?> btn-add judges_btn" type="button" style="padding: 8px 8px; float:right;" id="debtor_judges_btn" onclick="addJudge('debtor');">
                                <span class="icon icon-plus"></span>
                            </button>
                            <button class="btn btn-danger <?php echo $isRemoveJOfficer;?> btn-add judges_btn" type="button" style="padding: 8px 8px; float:right;" id="debtor_judges_remove_btn" onclick="removeJudge('debtor');">
                                <span class="icon icon-minus"></span>
                            </button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="span6 hidden" id="debtor_highcourt_file_no_reqd">
                        <div class="control-group">
                            <label class="control-label">High Court File No<?php echo $req;?>:</label>
                            <div class="controls">
                                <?php echo $this->Form->input('Debtor.PrisonerCaseFile.0.highcourt_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Enter High Court File No.','required'=>false,'id'=>'debtor_highcourt_file_no', 'maxlength'=>'30'));?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Court details END-->
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Date of Admission <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('created',array('div'=>false,'label'=>false,'class'=>'form-control span11 convict','type'=>'text', 'placeholder'=>'Date of Admission','required','readonly'=>'readonly', 'value'=> date('d-m-Y')));?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Creditor name <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('creditor_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha convict','type'=>'text', 'placeholder'=>'Enter Creditors name','required'=>false,'id'=>'creditor_name','title'=>'Please enter Creditor name'));?>
                    </div>
                </div>
            </div> 
            <div class="clearfix"></div> 
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Value of Debt <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('value_of_debt',array('div'=>false,'label'=>false,'class'=>'form-control span11 nospace numeric convict','type'=>'text', 'placeholder'=>'Enter Value of debt','required'=>false,'id'=>'value_of_debt', 'maxlength'=>'15', 'title'=>'Please enter Value of Debt*'));?>
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
                                        <?php echo $this->Form->input('DebtorJudgement.'.$i.'.no_of_days_for_amount',array('div'=>false,'label'=>false,'class'=>'form-control span11 nospace numeric convict','type'=>'text', 'placeholder'=>'Enter No. Of Days Paid For','required','id'=>$i.'_no_of_days_for_amount','onkeyup'=>'getPaymentDetails(this.value,'.$i.');','title'=>'Please enter No. Of Days Paid For', 'value'=> $debtorJudgementData['no_of_days_for_amount']));?>
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
                                    <?php echo $this->Form->input('DebtorJudgement.0.no_of_days_for_amount',array('div'=>false,'label'=>false,'class'=>'form-control span11 nospace numeric convict','type'=>'text', 'placeholder'=>'Enter No. Of Days Paid For','required'=>false,'id'=>'0_no_of_days_for_amount','onkeyup'=>'getPaymentDetails(this.value,0);','title'=>'Please enter No. Of Days Paid For'));?>
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
                <span class="input-group-btn">
                    <?php 
                    if(isset($debtorJudgements) && count($debtorJudgements) > 1)
                    {?>
                        <button class="btn btn-add btn-remove btn-danger payment-remove" type="button" style="padding: 8px 8px;float: right;position: absolute;right: 15px;">
                            <span class="icon icon-minus"></span>
                        </button>
                    <?php }
                    else {?>
                        <button class="btn btn-add btn-remove btn-danger payment-remove hidden" type="button" style="padding: 8px 8px;float: right;position: absolute;right: 15px;">
                            <span class="icon icon-minus"></span>
                        </button>
                    <?php }?>
                </span>
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
    //disable case modification-- START--
    <?php 
    if($debtor_case_status != 'Draft')
    {?>
        $('#PrisonerAdmissionDebtorFiles').attr("disabled", true);;
    <?php }
    ?>
    // $('.field_disable input').attr("disabled", "disabled");
    // $('.field_disable select').select2('destroy');
    // $('.field_disable select').prop("disabled", true);
    // $('.field_disable select').select2({
    //     placeholder: "-- Select --",
    //     allowClear: true
    //   });
    // $('.field_disable .judges_btn').remove();
    // //disable case modification-- END--

    // $('.pmis_select2').select2({
    //     placeholder: "-- Select --",
    //     allowClear: false
    // });

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

        $('.field_disable input').removeAttr("disabled");

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
});
</script>
