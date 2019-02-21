 <div class="container-fluid"><hr>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> 
                    <h5>Prisoners</h5>
                    <!-- <div style="float:left;padding-top: 7px;">
                        <?php //echo $this->Html->link('Prisoner Grid View',array('action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini pull-left')); ?>
                    </div> -->
                    <!-- <div style="float:right;padding-top: 7px;">
                        <!-- <button type="button" class="btn btn-success btn-mini" data-toggle="modal" data-target="#myModal">Existing Prisoner</button> -->
                        <!-- <?php //echo $this->Html->link('Add New Prisoner',array('action'=>'add'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp; -->
                        <!-- <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <!-- <div class="modal-content"> -->
                                    <!-- <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Existing Prisoner</h4>
                                    </div> -->
                                    <!-- <div class="modal-body">
                                        <?php echo $this->Form->create('existingPrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/existingPrisoner','onsubmit'=>"return isExistingPrisoner();"));?>
                                        <div class="row" style="padding-bottom: 14px;">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prisoner Number','required','id'=>'prisoner_no'));?>
                                                    </div>
                                                </div>
                                            </div>                  
                                        </div>
                                        <div class="form-actions" align="center" style="background:#fff;">
                                            <?php echo $this->Form->button('Continue', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success'))?>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- Verify Modal START -->
                        <!-- <div id="verify" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <!-- <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                                        <h4 class="modal-title">Prisoner Verification</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $this->Form->create('VerifyPrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/verifyPrisoner','id'=>'verifyPrisoner'));?>
                                        <?php echo $this->Form->input('uuid',array('type'=>'hidden','id'=>'prisoner_uuid'));?>
                                        <div class="row" style="padding-bottom: 14px;">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Verification Type<?php echo $req; ?> :</label>
                                                    <div class="controls uradioBtn">
                                                        <?php 
                                                        $verification_type = array();
                                                        $default = array();
                                                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
                                                            $verification_type = array('verify'=>'Verified','reject'=>'Reject');
                                                            $default = array("default"=>"verify");
                                                        }
                                                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                                                            $verification_type = array('approve'=>'Approve','reject'=>'Reject');    
                                                            $default = array("default"=>"approve");
                                                        }
                                                        
                                                         echo $this->Form->radio('type', $verification_type,array("legend"=>false,'class'=>'verification_type')+$default); 
                                                        ?>
                                                        <div style="clear:both;"></div>
                                                        <div class="error-message" id="verification_type_err" style="display:none;">Verification type is required !</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Remark :</label>
                                                    <div class="controls uradioBtn">
                                                       <?php echo $this->Form->input('verify_remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Remark','id'=>'verify_remark','rows'=>3,'required'=>false));?>
                                                       <div style="clear:both;"></div>
                                                        <div class="error-message" id="verification_message_err" style="display:none;">Verification type is required !</div>
                                                    </div>
                                                </div>
                                            </div>                
                                        </div>
                                        <div class="form-actions" align="center" style="background:#fff;">
                                            <?php echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','id'=>'verifyBtn'))?>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    </div>
                                </div>
                            </div> -->
                        <!-- </div>    -->              
                        <!-- Verify Modal END -->
                    <!-- </div> --> 
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prisoner No. :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('sprisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric','type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'sprisoner_no'));?>
                                </div>
                            </div>
                            <!-- <div class="control-group">
                                <label class="control-label">Prisoner Name :</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Prisoner Name','id'=>'prisoner_name'));?>
                                </div>
                            </div> -->
                        </div>
                        <!-- <div class="span6"> -->
                            <!-- <div class="control-group">
                                <label class="control-label">EPD :</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'epd_from', 'readonly'=>true,'style'=>'width:150px;'));?>
                                    To
                                    <?php //echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'epd_to', 'readonly'=>true,'style'=>'width:150px;'));?>
                                </div>
                            </div> -->
                            <!-- <div class="control-group">
                                <label class="control-label">Age between:</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('age_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','id'=>'age_from', 'maxlength'=>'3', 'style'=>'width:100px;'));?>
                                    &
                                    <?php //echo $this->Form->input('age_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','id'=>'age_to', 'maxlength'=>'3', 'style'=>'width:100px;'));?>
                                </div>
                            </div> -->
                        <!-- </div> --><!-- 
                        <div class="span12 advance_search hide">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Personal Number :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_unique_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Personal Number','id'=>'prisoner_unique_no'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Prisoner Type:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_type_id',array('div'=>false,'label'=>false,'onChange'=>'showPrisonerSubType(this.value)','class'=>'form-control span11','type'=>'select','options'=>$prisonerTypeList, 'empty'=>'-- Select Prisoner Type --','required','id'=>'prisoner_type_id'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Present Status:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('present_status',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$presentStatusList, 'empty'=>'-- Select Present Status --','required'=>false,'id'=>'present_status'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Ward:</label>
                                    <div class="controls">
                                        <?php 
                                        echo $this->Form->input('assigned_ward_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$wardList, 'empty'=>'-- Select Ward --','required'=>false,'id'=>'assigned_ward_id'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Offence:</label>
                                    <div class="controls">
                                        <?php 
                                        echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offenceList, 'empty'=>'-- Select Offence --','required'=>false,'onChange'=>'showSOLaws(this.value)','id'=>'offence_id'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">
                                        <?php $habitual_prisoner = 0;
                                        // if($prisoner_type == 'habitual')
                                        // {
                                        //     $habitual_prisoner = 1;
                                        // }
                                        echo $this->Form->input('habitual_prisoner',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false,'id'=>'habitual_prisoner', 'default' => $habitual_prisoner));?>
                                    </label>
                                    <div class="controls">
                                        Habitual prisoner?
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Gender:</label>
                                    <div class="controls">
                                        <?php $gender_id = '';
                                        echo $this->Form->input('gender_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$genderList, 'empty'=>'-- Select Gender --','required'=>false,'id'=>'gender_id', 'default' => $gender_id));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Prisoner Sub Type:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Prisoner Sub Type --','required','id'=>'prisoner_sub_type_id'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Approval Status:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$approvalStatusList, 'empty'=>'-- Select Approval Status --','required'=>false,'id'=>'status'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Classification:</label>
                                    <div class="controls">
                                        <?php $classification_id = '';
                                        if($prisoner_type == 'young')
                                        {
                                            $classification_id = Configure::read('YOUNG');
                                        }
                                        else if($prisoner_type == 'star')
                                        {
                                            $classification_id = Configure::read('STAR');
                                        }
                                        else if($prisoner_type == 'ordinary')
                                        {
                                            $classification_id = Configure::read('ORDINARY');
                                        }
                                        echo $this->Form->input('classification_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$classificationList, 'empty'=>'-- Select Classification --','required'=>false,'id'=>'classification_id', 'default'=>$classification_id));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Section Of Law:</label>
                                    <div class="controls">
                                        <?php 
                                                echo $this->Form->input('section_of_law',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select', 'empty'=>'-- Select Section Of Law --','required'=>false,'id'=>'section_of_law', ));?>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="span12" align="center" valign="center">
                            <!-- <?php //echo $this->Form->button('Advance Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn', 'onclick'=>"showDiv('advance_search')"))?> -->
                            <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'onclick'=>"showData()"))?>
                            <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchPrisonerListForm')"))?>
                            <?php //echo $this->Form->input('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchPrisonerListForm')"))?>
                        </div>                        
                    </div> 
                    <?php echo $this->Form->end();?> 
                     <div class="widget-content">
                        <div class="table-responsive" id="listingDiv">

                        </div>
                    </div>
                </div>                
                <div class="widget-content" id="listingDiv">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl            = $this->Html->url(array('controller'=>'prisoners','action'=>'PrisonerListAjax'));
$finalSaveAjaxUrl   = $this->Html->url(array('controller'=>'prisoners','action'=>'finalSavePrisoner'));
$trashAjaxUrl       = $this->Html->url(array('controller'=>'prisoners','action'=>'trashPrisoner'));
$verifyAjaxUrl      = $this->Html->url(array('controller'=>'prisoners','action'=>'verifyPrisoner'));
$approveAjaxUrl     = $this->Html->url(array('controller'=>'prisoners','action'=>'approvePrisoner'));
$getPrisonerSubajaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getPrisonerSubType'));
$approveUpdateAjaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'approveUpdate'));
$getSOLAjaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getSectionOfLaws'));
echo $this->Html->scriptBlock("

    //get section of laws
    function showSOLaws(offence_id)
    { 
        var solURL = '".$getSOLAjaxUrl."';
        $.post(solURL,{'offence_id':offence_id},function(data){  
            
            if(data) { 
                $('#section_of_law').html(data); 
            }
        });
    }

    $(document).ready(function(){
        showData();
        // process the search form
        // $('#SearchIndexForm').submit(function(event) { 
        //     showData();
        //     event.preventDefault();
        // });

        //verify prisoner 
        $('#verifyBtn').click(function(){
            var verification_type = $('.verification_type:checked').val();
            if(verification_type == 'reject' && $('#verify_remark').val()==''){
                alert('Please enter remark for reject.');
                return false;
            }
            var matches = [];
            var cnt = 0;
            $('input:checkbox.checkbox').each(function () {
                if(this.checked){
                     matches.push(this.value);
                }
                cnt = 1;
            });

            if(cnt == 0){
                alert('Please select atleast 1 check box.');
                return false;
            }
            
            if(verification_type != ''){
                if(confirm('Are you sure to verify?')){
                    var url = '".$approveUpdateAjaxUrl."';
                    $.post(url, {'type':verification_type,'ids':matches,'remarks':$('#verify_remark').val()}, function(res) { 
                        if (res == 'SUCC') {
                            $('#verify').modal('hide');
                            $('#verify_remark').val('');
                            showData();
                        }else{
                            alert('Invalid request, please try again!');
                        }
                    });
                }
            }
            else 
            {
                $('#verification_type_err').show();
            }
        });
    });
    function setVerifyPUuid(uuid)
    {
        $('#prisoner_uuid').val(uuid);
        $('#verification_type').val('');
        $('#s2id_verification_type .select2-choice span').html('');
        $('#verify_remark').val('');
        $('#verification_type_err').hide();
    }
    function setPUuid()
    {
        $('#prisoner_uuid').val('');
    }
    function showData(){ 
        var url = '".$ajaxUrl."';
        if($('#sprisoner_no').val() != ''){
            var prisoner_no = $('#sprisoner_no').val().replace('/', '-')
            url = url + '/prisoner_no:'+prisoner_no;
        }
        $.post(url, $('#SearchListviewForm').serialize(), function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }
    function finalSavePrisoner(uuid){
        if(uuid){
            if(confirm('Are you sure to save finally?')){
                var url = '".$finalSaveAjaxUrl."';
                $.post(url, {'uuid':uuid}, function(res) {
                    if (res == 'SUCC') {
                        showData();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    function trashPrisoner(uuid){
        if(uuid){
            if(confirm('Are you sure to delete?')){
                var url = '".$trashAjaxUrl."';
                $.post(url, {'uuid':uuid}, function(res) { 
                   
                    if (res == 'SUCC') {
                        showData();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    function verifyPrisoner(uuid){
        if(uuid){
            if(confirm('Are you sure to verify?')){
                var url = '".$verifyAjaxUrl."';
                $.post(url, {'uuid':uuid}, function(res) { alert(res)
                    if(res == 'SUCC') {
                        showData();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    function approvePrisoner(uuid){ 
        if(uuid){
            if(confirm('Are you sure to approve?')){
                var url = '".$approveAjaxUrl."';
                $.post(url, {'uuid':uuid}, function(res) { alert(res)
                    if(res == 'SUCC') {
                        showData();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    } 

    function showPrisonerSubType(){
        var url = '".$getPrisonerSubajaxUrl."';
        $.post(url, {'prisoner_type_id':$('#prisoner_type_id').val()}, function(res) {
            if (res) {
                $('#prisoner_sub_type_id').html(res);
            }
        });
    }   

   function resetData(id){
    //alert(id);
        $('#'+id)[0].reset();
        showData();
    }



    function approvePrisoner(uuid){ 
        if(uuid){
            if(confirm('Are you sure to approve?')){
                var url = '".$approveAjaxUrl."';
                $.post(url, {'uuid':uuid}, function(res) { alert(res)
                    if (res == 'SUCC') {
                        showData();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

    function updatePrisonerDeatils(type){
       
        var matches = [];
        $('input:checkbox.checkbox').each(function () {
            if(this.checked){
                 matches.push(this.value);
            }
        });
        var message = '';
        var conf_message = '';
        if(type=='finalsave'){
            var message = 'Are you sure to final save?';
            var conf_message = 'Final saved successfully.';
        }else if (type=='verify') {
            var message = 'Are you sure to verify?';
            var conf_message = 'verify successfully.';
        }else if (type=='approve') {
            var message = 'Are you sure to approve?';
            var conf_message = 'approved successfully.';
        }else if (type=='reject') {
            var message = 'Are you sure to reject?';
            var conf_message = 'rejected successfully.';
            var remark = $('#verify_remark').val();
        }else{

        }

        if(confirm(message)){
            var url = '".$approveUpdateAjaxUrl."';
            $.post(url, {'type':type,'ids':matches,'remarks':remark}, function(res) {
                alert(conf_message);
                if (res == 'SUCC') {
                    showData();
                }else{
                    alert('Invalid request, please try again!');
                }
            });
        }
    } 
",array('inline'=>false));
?>
<script type="text/javascript">

    $(function(){
 
        $.validator.addMethod("datevalidateformat", function(value, element) {
        //return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
        var dtRegex = new RegExp("^([0]?[1-9]|[1-2]\\d|3[0-1])-(01|02|03|04|05|06|07|08|09|10|11|12)-[1-2]\\d{3}$", 'i');
        return dtRegex.test(value);
    });

        $.validator.addMethod("valueNotEquals", function(value, element, arg){
                return arg !== value;
        }, "Please select valid data.");

       $("#SearchListviewForm").validate({
     
      ignore: "",
            rules: { 
                
                'data[Search][epd_from]': {
                    'notEmpty': {
                        'datevalidateformat': true,
                    }
                },
                'data[Search][epd_to]': {
                    'notEmpty': {
                        'datevalidateformat': true,
                    }
                },
                'data[Search][age_from]': {
                     min: 18,
                },
                'data[Search][age_to]': {
                    min: 18,
                },
            },
            messages: {
                
                'data[Search][epd_from]': {
                    'notEmpty': {
                        'datevalidateformat': "Invalid date"
                    }
                },
                'data[Search][epd_to]': {
                    'datevalidateformat': "Invalid date"
                },
                'data[Search][age_from]': {
                    required: "Minimum age should be 18"
                },
                'data[Search][age_to]': {
                    required: "Minimum age should be 18"
                },
            },
        }); 
    });
</script>