<?php //debug($returnFromCourtStatus); //exit;
$appeal_file_no = $funcall->getAppealCaseFile($this->data['Prisoner']['id']);
if(isset($returnFromCourtStatus['ReturnFromCourt']['id']) && !empty($returnFromCourtStatus['ReturnFromCourt']['id']))
{
    $appeal_file_no = $funcall->getAppealCaseFile($this->data['Prisoner']['id'], $returnFromCourtStatus['ReturnFromCourt']['case_file_number']);
}
$displaySentenceData = 'hidden';
$appeal_case_file_no = '';
$appeal_offence = '';
$appeal_sentence = '';
$type_of_appeallant = '';
$appeal_result_date = date('d-m-Y');
$appeal_result_list = array('Enhanced'=>'Enhanced',
    'Reduced'=>'Reduced',
    'Maintained'=>'Maintained',
    'Quashed'=>'Quashed', 
    'Dismissed'=>'Dismissed',
    'Pending minister Order'=>'Pending minister Order',
    'Order  for retrial' => 'Order  for retrial');
if(isset($this->data['PrisonerSentenceAppeal']['id']) && !empty($this->data['PrisonerSentenceAppeal']['id']))
{
    $displaySentenceData = '';
    $type_of_appeallant = 'readonly';

    $sentenceData = $funcall->getSentenceDetail($this->data['PrisonerSentenceAppeal']['offence_id']);
    $sentenceData = (array)json_decode($sentenceData);
    $appeal_case_file_no = $sentenceData['data']->PrisonerCaseFile->case_file_no;
    $appeal_offence = $sentenceData['data']->Offence->name;
    $appeal_sentence = $sentenceData['data']->PrisonerSentence->sentenceData;
    if($this->data['PrisonerSentenceAppeal']['appeal_result_date'] != '0000-00-00')
    {
        $appeal_result_date = date('d-m-Y', strtotime($this->data['PrisonerSentenceAppeal']['appeal_result_date'] ));
    }
}
?>
<div class="" style="padding-bottom: 14px;">
    <div class="row-fluid secondDiv widget-box" style="padding-bottom: 14px; background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Appeal</h5>
        </div>
        <div class="widget-content">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">File no <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php 
                        echo $this->Form->input('case_file_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','multiple'=>true,'onChange'=>'getAppealFileCount()','type'=>'select','multiple'=>true,'options'=>$appeal_file_no, 'empty'=>'','required', 'title'=>'Case File is required.', 'id'=>'appeal_file_no')); ?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Count <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','multiple'=>true,'options'=>$appealCountList, 'empty'=>'','onChange'=>'getSentenceDetail(this.value),getAppealSentenceDetail()', 'required', 'title'=>'Count is required.'));?>
                    </div>
                </div>
            </div> 
            <div class="clearfix"></div>
            <div class="sentenceAppeal"></div>
             
           
            <div class="span6">
                <div class="control-group ">
                    <label class="control-label">Type of appellant <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php 
                        echo $this->Form->input('type_of_appeallant',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$typeOfAppeallant, 'empty'=>'','required','id'=>'type_of_appeallant', $type_of_appeallant, 'title'=>'Type of appellant is required.'));?>
                    </div>
                </div>
            </div>
            <!-- <div class="clearfix"></div>  -->
            <div class="span6" id="appeal_status_div">
                <div class="control-group">
                    <label class="control-label">Appeal Status :</label>
                    <div class="controls">
                        <?php 
                        $aplstatus = array(
                            'Notes of appeal' => 'Notes of appeal', 
                            'Memorandum of Appeal' => 'Memorandum of Appeal',
                            'Pending Hearing of Appeal' => 'Pending Hearing of Appeal', 
                            'Cause List' => 'Cause List'
                        );
                        echo $this->Form->input('appeal_status',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$aplstatus, 'empty'=>'','required'=>false,'id'=>'appeal_status', 'onChange'=>'getAppealStatusFields(this.value);','readonly'));?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- Appeal status fields START  -->
            <div class="row-fluid appealStatusDiv hidden widget-box" style="padding-bottom: 14px; background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
                <div class="widget-content">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Court Level <?php echo $req; ?>:</label>
                            <div class="controls">
                                <?php $cid = "'appeal'";
                                echo $this->Form->input('courtlevel_id',array('div'=>false,'label'=>false,'onChange'=>'getCourtList(this.value,'.$cid.')','class'=>'form-control span11 pmis_select courtlevel_id','type'=>'select','options'=>$courtLevelList, 'empty'=>'','required'=>false,'id'=>'appeal_courtlevel_id', 'title'=>'Select Court Level'));?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Court Name<?php echo $req; ?>:</label>
                            <div class="controls">
                                <?php 
                                $courtList = array();
                                //$courtList = $funcall->getCourtList($caseData['PrisonerCaseFile']['courtlevel_id']);
                                echo $this->Form->input('court_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select court_id','type'=>'select','options'=>$appealCourtList, 'empty'=>'','required'=>false,'id'=>'appeal_court_id',  'title'=>'Select court name'));?>
                            </div>
                        </div>
                    </div> 
                    <div class="clearfix"></div> 
                    <div class="span6" id="submission_date_div">
                        <div class="control-group">
                            <label class="control-label">Date of Submission <?php echo $req; ?>:</label>
                            <div class="controls">
                                <?php echo $this->Form->input('submission_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text', 'placeholder'=>'Date of Submission','required'=>false,'id'=>'submission_date', 'maxlength'=>'15', 'title'=>'Please select Date of Submission'));?>
                            </div>
                        </div>
                    </div>
                    <div class="span6" id="appeal_no_div">
                        <div class="control-group">
                            <label class="control-label">Appeal No:</label>
                            <div class="controls">
                                <?php echo $this->Form->input('appeal_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'=>false,'id'=>'appeal_no'));?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Appeal status fields END  -->
            <?php //debug($this->data['PrisonerSentenceAppeal']['appeal_result']); exit;
            $displayResult = '';
            $displayResultDiv = 'display:none;';
            $appealResultRequired = "";
            if((isset($this->data['PrisonerSentenceAppeal']['appeal_result']) && ($this->data['PrisonerSentenceAppeal']['appeal_result'] != '')) || $displayResult == 1)
            {
                $displayResultDiv = '';
                $appealResultRequired = "required";
            }?>
            <?php 
            echo $this->Form->input('fromcourt_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden','value'=>$returnFromCourtStatus['ReturnFromCourt']['id'], 'id'=>'fromcourt_id'));
            echo $this->Form->input('appeal_sentence_length',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden','id'=>'appeal_sentence_length'));
            echo $this->Form->input('appeal_sentence_date_of_conviction',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden','id'=>'appeal_sentence_date_of_conviction'));
            echo $this->Form->input('ndoc',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden','id'=>'ndoc'));?>

            <div class="span6" id="sentence_appeal_result_div" style=" <?php echo $displayResultDiv;?>">
                <div class="control-group">
                    <label class="control-label">Appeal Result<?php echo $req;?>:</label>
                    <div class="controls">
                        <?php 
                        echo $this->Form->input('appeal_result',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select court_id','type'=>'select','options'=>$appeal_result_list, 'empty'=>'','id'=>'appeal_result_id',  'title'=>'Select appeal result', 'onChange'=>'getAppeledResultInfo(this.value);', 'title'=> 'Please select appeal result', $appealResultRequired));?>
                    </div>
                </div>
            </div>
            <div class="span6" id="appeal_result_date_div" style=" <?php echo $displayResultDiv;?>">
                <div class="control-group">
                    <label class="control-label">Appeal Result Date <?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('appeal_result_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text', 'placeholder'=>'Appeal Result Date','required','id'=>'appeal_result_date', 'maxlength'=>'15', 'title'=>'Please select appeal result date', 'readonly', 'value'=>$appeal_result_date));?>
                    </div>
                </div>
            </div> 
            <div class="span6" id="date_of_dismissal_appeal" style="display: none;">
                 <div class="control-group">
                    <label class="control-label">Date of Dismissal of Appeal:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('date_of_dismissal_appeal',array('div'=>false,'label'=>false,'class'=>'form-control dismissalDate span11','type'=>'text', 'placeholder'=>'Enter Date of Dismissal of Appeal','required'=>false,'readonly'=>'readonly','id'=>'date_of_dismissal_appeal'));?>
                    </div>
                </div>
            </div> 
            <!-- Appeal sentence count details START -->
            <div id="new_sentence_count_details" style="display:none">
                <?php 
                if(isset($this->data['PrisonerSentenceAppeal']['sentence_count_id']) && (int)$this->data['PrisonerSentenceAppeal']['sentence_count_id'] > 0)
                {
                    $sentenceCountDetail = $funcall->getDataRow('PrisonerSentence',$this->data['PrisonerSentenceAppeal']['sentence_count_id']);
                    if(isset($sentenceCountDetail) && count($sentenceCountDetail) > 0)
                    {
                        //echo '<pre>'; 
                        //print_r($sentenceCountDetail); 
                    }
                }
                $appeal_scount_days = $sentenceCountDetail['PrisonerSentenceCount']['days'];
                $appeal_scount_months = $sentenceCountDetail['PrisonerSentenceCount']['months'];
                $appeal_scount_years = $sentenceCountDetail['PrisonerSentenceCount']['years'];
                ?>
                <div class="input-group col-xs-3">
                    <div style="width:5%; float: left; margin: 3px;" class="scount_sl"></div>
                    <div style="width:25%; float: left; margin: 5px;" class="scount_sl">Years</div>
                    <div style="width:25%; float: left; margin: 5px;" class="scount_sl">Months</div>
                    <div style="width:25%; float: left; margin: 5px;" class="scount_sl">Days</div>
                </div>
                <div class="clearfix"></div>
                <div class="sentence_capture_entry input-group col-xs-3" style="margin-bottom:5px;">
                    <div style="float: left; margin: 5px;" class="scount_sl">Count</div>
                    <?php 
                    echo $this->Form->input('years',array('div'=>false,'label'=>false,'class'=>'form-control span3 numeric','type'=>'text','id'=>'appeal_scount_years')); 
                    echo $this->Form->input('months',array('div'=>false,'label'=>false,'class'=>'form-control span3 numeric','type'=>'text','id'=>'appeal_scount_months')); 
                    echo $this->Form->input('days',array('div'=>false,'label'=>false,'class'=>'form-control span3 numeric','type'=>'text','id'=>'appeal_scount_days')); 
                    ?>
                </div>
            </div>
            <!-- Appeal sentence count details END -->
        </div>
    </div>
</div>
<script>
$(function(){

    $('.dismissalDate').datepicker({
        format: 'dd-mm-yyyy',
        autoclose:true,
        endDate: new Date(),
    }).on('changeDate', function (ev) {
         $(this).datepicker('hide');
         $(this).blur();
         //console.log(this.value);
         getNDOC(this.value, 2);
    });

    //open appeal result fields -- START -- 
    <?php 
    if(isset($returnFromCourtStatus['ReturnFromCourt']['id']) && !empty($returnFromCourtStatus['ReturnFromCourt']['id']))
    {?>
        var appeal_file_no = "<?php echo $returnFromCourtStatus['ReturnFromCourt']['case_file_number'];?>"; 
        $('#appeal_file_no').select2('destroy');
        $('#appeal_file_no').removeAttr('multiple');
        $('#appeal_file_no').select2('val',appeal_file_no);
        $('#appeal_file_no').attr('readonly','readonly');
        getAppealFileCount("<?php echo $returnFromCourtStatus['ReturnFromCourt']['offence_id'];?>");
        setTimeout(function(){
            $('#PrisonerSentenceAppealOffenceId').select2('destroy');
            $('#PrisonerSentenceAppealOffenceId').removeAttr('multiple');
            $('#PrisonerSentenceAppealOffenceId').select2('val',"<?php echo $returnFromCourtStatus['ReturnFromCourt']['offence_id'];?>");
            $('#PrisonerSentenceAppealOffenceId').attr('readonly','readonly');
            setTimeout(function(){
                getSentenceDetail("<?php echo $returnFromCourtStatus['ReturnFromCourt']['offence_id'];?>");
                getAppealSentenceDetail();
                $('#appeal_status_div').hide();
                $('#sentence_appeal_result_div').show();
                $('#appeal_result_id').select2('val','');
                $('#appeal_result_id').attr('required','required');
                $('#appeal_result_date_div').show();
                var strSURL = '<?php echo $this->Html->url(array('controller'=>'App','action'=>'getSentenceCountInDays'));?>/';
                var offence_id = "<?php echo $returnFromCourtStatus['ReturnFromCourt']['offence_id'];?>";
                $.post(strSURL,{"offence_id":offence_id},function(data){
                    var result = jQuery.parseJSON(data); 
                    
                    $('#appeal_sentence_date_of_conviction').val(result.doc);
                    $('#appeal_sentence_length').val(result.slength);
                });
            }, 1000);
        }, 1000);
        
        
    <?php }?>
    //open appeal result fields -- END --

    getAppealStatusFields($('#appeal_status').val());
    getAppealSentenceDetail();
});
function getAppealFileCount(count='')
{
    var file_nos = $('#appeal_file_no').val();
    if(file_nos != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'App','action'=>'getAppealCount'));?>/';
        $.post(strURL,{"file_nos":file_nos, "count":count},function(data){
            if(data) { 
                $('#PrisonerSentenceAppealOffenceId').html(data);
            }
            else
            {
                //alert("Error..."); 
                //$('#PrisonerSentenceAppealOffenceId').html(data); 
            }
        });
    }
    $('#appeal_count_details').addClass('hidden');
    $('#appeal_count_case_file_no').val('');
    $('#appeal_count_offence').val('');
    $('#appeal_count_sentence').val('');
    $('#PrisonerSentenceAppealOffenceId').html('');
    $('#PrisonerSentenceAppealOffenceId').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
}

//get appeal sentence details --START--
function getAppealSentenceDetail()
{   
    var ret = [];
    var select=document.getElementById('PrisonerSentenceAppealOffenceId');
    // var x=document.getElementById('PrisonerSentenceAppealOffenceId');
    //   for (var i = 0; i < x.options.length; i++) {
    //      if(x.options[i].selected ==true){
    //           alert(x.options[i].value);
    //       }
    // }
     for (var i=0; i < select.options.length; i++) {
            if (select.options[i].selected) {
                ret.push(select.options[i].value);
            }
        }
        
    if(ret != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'Prisoners','action'=>'getAppealSentenceDetail'));?>/'+ret;
        $.post(strURL,{},function(data){
            if(data) 
            { 
                $('.sentenceAppeal').html(data);
                // var result = jQuery.parseJSON(data); 
                // if(result.status == 'success')
                // {
                //     console.log(result.data);
                //     $('#appeal_count_case_file_no').val(result.data.PrisonerCaseFile.case_file_no);
                //     $('#appeal_count_offence').val(result.data.Offence.name);
                //     $('#appeal_count_sentence').val(result.data.PrisonerSentence.sentenceData);
                //     $('#appeal_count_details').removeClass('hidden');
                //     //If any previous appeal result 
                    
                // }
            }
            else
            {
                alert("Error...");  
            }
        });
    }
    else 
    {
        $('#type_of_appeallant').removeAttr('readonly');
        $('#type_of_appeallant').select2('val','');
        $('#appeal_count_details').addClass('hidden');
        $('#appeal_count_case_file_no').val('');
        $('#appeal_count_offence').val('');
        $('#appeal_count_sentence').val('');
    }
}
//get appeal sentence details --END--
//get sentence details --START--
function getSentenceDetail(count_id)
{   
    
    if(count_id != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'App','action'=>'getSentenceDetail'));?>/'+count_id;
        $.post(strURL,{},function(data){//alert(data);
            if(data) 
            { 
                var result = jQuery.parseJSON(data); 
                if(result.status == 'success')
                {
                    console.log(result.data);
                    // $('#appeal_count_case_file_no').val(result.data.PrisonerCaseFile.case_file_no);
                    // $('#appeal_count_offence').val(result.data.Offence.name);
                    // $('#appeal_count_sentence').val(result.data.PrisonerSentence.sentenceData);
                    // $('#appeal_count_details').removeClass('hidden');
                    //If any previous appeal result 
                    if(result.data.PrisonerSentenceAppeal == '')
                    {
                        $('#appeal_status').html('<option value="Notes of appeal">Notes of appeal</option>');
                        $('#appeal_status').select2('val','Notes of appeal');
                        getAppealStatusFields('Notes of appeal');
                    }
                    else 
                    {
                        var next_status = '';
                        if(result.data.PrisonerSentenceAppeal.appeal_status == 'Notes of appeal')
                        {
                            next_status = 'Memorandum of Appeal';
                        }
                        if(result.data.PrisonerSentenceAppeal.appeal_status == 'Memorandum of Appeal')
                        {
                            next_status = 'Pending Hearing of Appeal';
                        }
                        if(result.data.PrisonerSentenceAppeal.appeal_status == 'Pending Hearing of Appeal')
                        {
                            next_status = 'Cause List';
                        }
                        $('#appeal_status').html('<option value="'+next_status+'">'+next_status+'</option>');
                        $('#appeal_status').select2('val',next_status);
                        getAppealStatusFields(next_status);
                        //set previous appeal values --START--
                        
                        
                        //$('#type_of_appeallant').select2('val',result.data.PrisonerSentenceAppeal.type_of_appeallant);
                        //$('#type_of_appeallant').select2("readonly", true);
                        var type_of_appeallant = result.data.PrisonerSentenceAppeal.type_of_appeallant;
                        $('#type_of_appeallant').select2('destroy');
                        $('#type_of_appeallant').html('<option value="'+type_of_appeallant+'">'+type_of_appeallant+'</option>');
                        $('#type_of_appeallant').attr('readonly','readonly');
                        $('#type_of_appeallant').select2('val',type_of_appeallant);

                        $('#appeal_courtlevel_id').select2('destroy');
                        $('#appeal_courtlevel_id').html('<option value="'+result.data.PrisonerSentenceAppeal.courtlevel_id+'">'+result.data.PrisonerSentenceAppeal.courtlevel_name+'</option>');
                        $('#appeal_courtlevel_id').attr('readonly','readonly');

                        //$('#appeal_courtlevel_id').val(result.data.PrisonerSentenceAppeal.courtlevel_id);
                        //$('#appeal_courtlevel_id').attr('disabled','disabled');
                        
                        
                        //getCourtList(result.data.PrisonerSentenceAppeal.courtlevel_id, 'appeal');
                        $('#appeal_court_id').select2('destroy');

                        $('#appeal_court_id').html('<option value="'+result.data.PrisonerSentenceAppeal.court_id+'">'+result.data.PrisonerSentenceAppeal.court_name+'</option>');
                        $('#appeal_court_id').attr('readonly','readonly');
                        
                        // setTimeout(function(){
                        //     $('#appeal_court_id').val(result.data.PrisonerSentenceAppeal.court_id);
                        //     //$('#appeal_court_id').attr('disabled','disabled');
                        // }, 1000);
                        
                        $('#submission_date').attr('readonly','readonly');
                        $('#submission_date').val(result.data.PrisonerSentenceAppeal.submission_date);
                        if(result.data.PrisonerSentenceAppeal.appeal_status != 'Notes of appeal')
                        {
                            $('#appeal_no').attr('readonly','readonly');
                            $('#appeal_no').val(result.data.PrisonerSentenceAppeal.appeal_no);
                        }
                        
                        //set previous appeal values --END--
                    }
                }
            }
            else
            {
                alert("Error...");  
            }
        });
    }
    else 
    {
        $('#type_of_appeallant').removeAttr('readonly');
        $('#type_of_appeallant').select2('val','');
        $('#appeal_count_details').addClass('hidden');
        $('#appeal_count_case_file_no').val('');
        $('#appeal_count_offence').val('');
        $('#appeal_count_sentence').val('');
    }
}
//get sentence details --END--
function getAppealStatusFields(appeal_status)
{
    if(appeal_status != '' && appeal_status != 'Cause List')
    {
        $('#appeal_courtlevel_id').prop('required','required');
        $('#appeal_court_id').prop('required','required');
        $('#submission_date').prop('required','required');
        
        $('#appeal_no_div').show();
        $('#submission_date_div').show();
        if(appeal_status == 'Notes of appeal')
        {
            $('#appeal_no_div').val('');
            $('#appeal_no_div').hide();
            $('#submission_date').prop('required','required');
        }
        if(appeal_status == 'Pending Hearing of Appeal')
        {
            $('#submission_date_div').val('');
            $('#submission_date_div').hide();
            $('#submission_date').prop('required','');
        }
        $('.appealStatusDiv').removeClass('hidden');
    }
    else 
    {
        $('#appeal_courtlevel_id').prop('required','');
        $('#appeal_court_id').prop('required','');
        $('#submission_date').prop('required','');
        $('.appealStatusDiv').addClass('hidden');
    }
}
function validateAppealForm()
{
    var appeal_result_id = $('#appeal_result_id').val();
    var fromcourt_id = $('#fromcourt_id').val();
    if(fromcourt_id != '')
    {
        if(appeal_result_id == '')
        {
            return false;
        }
        if(appeal_result_id == 'Enhanced' || appeal_result_id == 'Reduced')
        {
            var scount_years = $('#appeal_scount_years').val();
            var scount_months = $('#appeal_scount_months').val();
            var scount_days = $('#appeal_scount_days').val();
            var sLength = 0;
            if(scount_years == '' && scount_months == '' && scount_days == '')
            {
                dynamicAlertBox('error','Please enter '+appeal_result_id+' sentence');
                return false;
            }
            else 
            {
                var strSURL = '<?php echo $this->Html->url(array('controller'=>'App','action'=>'getSentenceCountInDays'));?>/';
                var offence_id = $('#PrisonerSentenceAppealOffenceId').val();
                
                var sLength2 = 0;
                $.post(strSURL,{"offence_id":offence_id},function(data){
                    sLength2 = data;
                });
                if(scount_years != '')
                    sLength += (parseInt(scount_years)*365);
                if(scount_months != '')
                    sLength += (parseInt(scount_months)*30);
                if(scount_days != '')
                    sLength += parseInt(scount_days);

                //comapre current appeal sentence with previous sentence -- START -- 
                    
                var sLength2 = $('#appeal_sentence_length').val();
                if(appeal_result_id == 'Enhanced')
                {
                    if(sLength <= sLength2)
                    {
                        dynamicAlertBox('error','Enhanced sentence should be greater than previous sentence.');
                        return false;
                    }
                }
                else if(appeal_result_id == 'Reduced')
                {
                    if(sLength >= sLength2)
                    {
                        dynamicAlertBox('error','Reduced sentence should be less than previous sentence.');
                        return false;
                    }
                }
                //comapre current appeal sentence with previous sentence -- END -- 
            }
        }
    }
    
}
//get ndoc 
function getNDOC(val, type='')
{
    var ndoc = '';
    if(type == 2)
    {
        if($('#type_of_appeallant').val() == 'Un-Convicted')
        {
            var sentence_doc = $('#appeal_sentence_date_of_conviction').val();
            var date1 = sentence_doc.split('-');
            var sentence_doc = new Date(date1[2], date1[1] - 1, date1[0]);
            
            var doc = new Date(sentence_doc);
            doc.setDate(doc.getDate() + 42);
            var d1 = doc;
            var date2 = val.split('-');
            var d2 = new Date(date2[2], date2[1] - 1, date2[0]);
            
            var updated_doc = '';
            if(d2.getTime() > d1.getTime())
            { 
                updated_doc = d1.getDate()+'-'+d1.getMonth()+'-'+d1.getFullYear();
            }
            else 
            { 
                updated_doc = d2.getDate()+'-'+d2.getMonth()+'-'+d2.getFullYear();
            }
            $('#ndoc').val(updated_doc);
        }
    } 
    else 
    {
        $('#ndoc').val(val);
    }
    //$('#new_date_of_confirmation_div').show();
}
</script>