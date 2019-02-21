 <div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> 
                    <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Prisoners</h5>
                    <div style="float:left;padding-top: 7px;">
                        <?php echo $this->Html->link('Prisoner List View',array('action'=>'listview'),array('escape'=>false,'class'=>'btn btn-success btn-mini pull-left')); ?>
                    </div>
                    <div style="float:right;padding-top: 7px;">
                       
                        <!-- <button type="button" class="btn btn-success btn-mini" data-toggle="modal" data-target="#myModal">Existing Prisoner</button> -->
                        <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || $this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                        {
                            echo $this->Html->link('Add New Prisoner',array('action'=>'add'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); 
                        }?>
                        &nbsp;&nbsp;
                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Existing Prisoner</h4>
                                    </div>
                                    <div class="modal-body">
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
                        </div>
                        <!-- Verify Modal START -->
                        <div id="verify" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                                        <h4 class="modal-title">Prisoner Verification</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $this->Form->create('VerifyPrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/verifyPrisoner','id'=>'verifyPrisoner'));?>
                                        <?php echo $this->Form->input('verify_id', array('type'=>'hidden','id'=>'verifyId','value'=>0))?>
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
                                            <?php echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','id'=>'verifyBtn',"onclick"=>"verifyPrisonerFinal()"))?>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    </div>
                                </div>
                            </div>
                        </div>                       
                        <!-- Verify Modal END -->
                        <!-- OIC Verification Modal START -->
                        <?php echo $this->element('oic-verify');?>
                        <!-- OIC Verification Modal END -->
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prisoner No. :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('sprisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 prisonerNo', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'sprisoner_no'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Prisoner Name :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Prisoner Name','id'=>'prisoner_name', 'style'=>''));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">EPD :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'epd_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                    To
                                    <?php echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'epd_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Age between:</label>
                                <div class="controls">
                               
                                
                                    <?php echo $this->Form->input('age_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Min Age','id'=>'age_from', 'maxlength'=>'3', 'required'=>false,'style'=>'width:43%;'));?>
                                    &nbsp;&&nbsp;
                                    <?php echo $this->Form->input('age_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Max Age','id'=>'age_to', 'maxlength'=>'3', 'required'=>false,'style'=>'width:43%;'));?>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="span12 advance_search hide" style="margin-left: 0;">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Personal Number :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_unique_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 prisonerNo','type'=>'text','placeholder'=>'Enter Personal Number','id'=>'prisoner_unique_no'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Prisoner Type:</label>
                                    <div class="controls">
                                        <?php $prisoner_type_id = '';
                                        if($prisoner_type == 'convicted')
                                        {
                                            $prisoner_type_id = Configure::read('CONVICTED');
                                        }
                                        else if($prisoner_type == 'remand')
                                        {
                                            $prisoner_type_id = Configure::read('REMAND');
                                        }
                                        else if($prisoner_type == 'debtor')
                                        {
                                            $prisoner_type_id = Configure::read('DEBTOR');
                                        }
                                        echo $this->Form->input('prisoner_type_id',array('div'=>false,'label'=>false,'onChange'=>'showPrisonerSubType(this.value)','class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonerTypeList, 'empty'=>'','required'=>false,'id'=>'prisoner_type_id', 'default' => $prisoner_type_id));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Present Status:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('present_status',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$presentStatusList, 'empty'=>'','required'=>false,'id'=>'present_status'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Ward:</label>
                                    <div class="controls">
                                        <?php 
                                        echo $this->Form->input('assigned_ward_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$wardList, 'empty'=>'','required'=>false,'id'=>'assigned_ward_id'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Offence:</label>
                                    <div class="controls">
                                        <?php 
                                        echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$offenceList, 'empty'=>'','required'=>false,'onChange'=>'showSOLaws(this.value)','id'=>'offence_id'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Type of Disability:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('special_condition_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select', 'onChange'=>'getTypeOfDisability()', 'options'=>$specialConditionList, 'empty'=>'','id'=>'special_condition_id', 'required'));?>
                                                </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Case File No.:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('case_file_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','id'=>'case_file_no','placeholder'=>'Enter Case File Number'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Presiding<br>Juducial Officer:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('judicial_officer',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','id'=>'judicial_officer','placeholder'=>'Enter Presiding Juducial'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">DOA:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('doa',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text','id'=>'judicial_officer','placeholder'=>'Enter DOA'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Gender:</label>
                                    <div class="controls">
                                        <?php $gender_id = '';
                                        if($gender_type == 'male')
                                        {
                                            $gender_id = Configure::read('GENDER_MALE');
                                        }
                                        else if($gender_type == 'female')
                                        {
                                            $gender_id = Configure::read('GENDER_FEMALE');
                                        }
                                        echo $this->Form->input('gender_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$genderList, 'empty'=>'','required'=>false,'id'=>'gender_id', 'default' => $gender_id));?>
                                    </div>
                                </div>
                                <div class="control-group hidden">
                                    <label class="control-label">Prisoner Sub Type:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'onChange'=>'showCountries(this.value)','class'=>'form-control span11 pmis_select','type'=>'select','options'=>array(), 'required'=>false,'id'=>'prisoner_sub_type_id'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Approval Status:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$approvalStatusList, 'empty'=>'','required'=>false,'id'=>'status'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Classification:</label>
                                    <div class="controls">
                                        <?php $classification_id = '';
                                        echo $this->Form->input('classification_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$classificationList, 'empty'=>'','required'=>false,'id'=>'classification_id', 'default'=>$classification_id));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Prisoner Term Type:</label>
                                    <div class="controls">
                                        <?php $is_long_term_prisoner = '';
                                        echo $this->Form->input('is_long_term_prisoner',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>array(0=>"Short Term",1=>"Long Term"), 'empty'=>'','required'=>false,'id'=>'is_long_term_prisoner', 'default'=>$is_long_term_prisoner));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Section Of Law:</label>
                                    <div class="controls">
                                        <?php 
                                                echo $this->Form->input('section_of_law',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select', 'empty'=>'','required'=>false,'id'=>'section_of_law', ));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Subcategory Disability :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('type_of_disability',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>'', 'options'=>'', 'empty'=>'','id'=>'type_of_disability', 'required'=>false));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Session No:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('session_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','id'=>'session_no','placeholder'=>'Enter Session No.'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Appeal No:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('appeal_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','id'=>'appeal_no','placeholder'=>'Enter Appeal No.'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">
                                        <?php $habitual_prisoner = 0;
                                        echo $this->Form->input('habitual_prisoner',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false,'id'=>'habitual_prisoner', 'default' => $habitual_prisoner));?>
                                    </label>
                                    <div class="controls" style="padding-top:19px;">
                                        Habitual prisoner?
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="span12 add-top" align="center" valign="center">
                            <?php echo $this->Form->button('Biometric Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning',"id"=>"start"))?>
                            <?php echo $this->Form->button('Advance Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-info', 'onclick'=>"showDiv('advance_search')"))?>
                            <?php echo $this->Form->input('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'onclick'=>"showData()"))?>
                            <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchIndexForm')"))?>
                        </div>                        
                    </div> 
                    <?php echo $this->Form->end();?> 
                     <div class="widget-content">
                        <!-- <div class="widget-box" style="padding:5px;">Latest admitted prisoners are listed below.</div> -->
                        <div id="listingDiv"></div>
                    </div>
                </div>                
                
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="biometricModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header" style="height: 40px;">
        <h5 class="modal-title" id="exampleModalLabel" style="float: left;">Biometric Search</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body" align="center">
        <?php echo $this->Html->image('finger.gif', array('alt' => $cakeDescription, 'border' => '0')); ?>
        <br />
        <p>Please press finger on biometric</p>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="stop()" class="btn btn-danger" data-dismiss="modal">Stop</button>
      </div>
    </div>
  </div>
</div>
<?php
$ajaxUrl            = $this->Html->url(array('controller'=>'prisoners','action'=>'indexAjax'));
$finalSaveAjaxUrl   = $this->Html->url(array('controller'=>'prisoners','action'=>'finalSavePrisoner'));
$trashAjaxUrl       = $this->Html->url(array('controller'=>'prisoners','action'=>'trashPrisoner'));
$verifyAjaxUrl      = $this->Html->url(array('controller'=>'prisoners','action'=>'verifyPrisoner'));
$biometricSearchAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'dataCheck'));
$biometricRedirectAjax = $this->Html->url(array('controller'=>'prisoners','action'=>'details'));
$approveAjaxUrl     = $this->Html->url(array('controller'=>'prisoners','action'=>'approvePrisoner'));
$getPrisonerSubajaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getPrisonerSubType'));
$approveUpdateAjaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'approveUpdate'));
$getSOLAjaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getSectionOfLaws'));
$getPrisonerDisabilityAjaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getTypeOfDisability'));
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
    function getTypeOfDisability()
         { 
        var url = '".$getPrisonerDisabilityAjaxUrl."';
        $.post(url, {'special_condition_id':$('#special_condition_id').val()}, function(res) {
            if (res) {
                $('#type_of_disability').html(res);
            }
        });
     }

    $(document).ready(function(){
        //setInterval('checkData()',10000);
        showData();
        // process the search form
        $('#SearchIndexForm').submit(function(event) { 
            showData();
            event.preventDefault();
        });

        

        //verify prisoner 
        $('#verifyBtn').click(function(){
            var verification_type = $('.verification_type:checked').val();
            if(verification_type == 'reject' && $('#verify_remark').val()==''){
                dynamicAlertBox('Error','Please enter remark for reject.');
                return false;
            }
            var matches = [];            
            matches.push($('#verifyId').val());
              
            
            if(verification_type != ''){
                //'+ids+'
                AsyncConfirmYesNo(
                    'Are you sure want to verify?',
                    'verify',
                    'Cancel',
                    function(){

                        var url = '".$approveUpdateAjaxUrl."';
                        $.post(url, {'type':verification_type,'ids':matches,'remarks':$('#verify_remark').val()}, function(res) { 
                            if (res.trim() == 'SUCC') {
                                $('#verify').modal('hide');
                                $('#verify_remark').val('');
                                showData();
                            }else{
                                dynamicAlertBox('Error','Invalid request, please try again!');
                            }
                        });

                    },
                    function(){}
                );
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
        var selectedPrisoner = '".$selectedPrisoner."';
        if($('#sprisoner_no').val() != ''){
            var prisoner_no = $('#sprisoner_no').val().replace('/', '-')
            url = url + '/prisoner_no:'+prisoner_no;
        }
        url = url + '/selectedPrisoner:'+selectedPrisoner;
        $.post(url, $('#SearchIndexForm').serialize(), function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }
    function finalSavePrisoner(uuid){
        if(uuid){
            AsyncConfirmYesNo(
                'Are you sure to save finally?',
                'Yes',
                'No',
                function(){
                    var url = '".$finalSaveAjaxUrl."';
                    $.post(url, {'uuid':uuid}, function(res) {
                        if (res.trim() == 'SUCC') {
                            showData();
                        }else if (res.trim() == 'PROB') {
                            dynamicAlertBox('Message', 'Please update mandatory personal & admission details and assign ward first.');
                        }else{
                            dynamicAlertBox('Message', 'Invalid request, please try again!');
                        }
                    });
                },
                function(){
                    
                }
            );
        }
    }
    function trashPrisoner(uuid){
        if(uuid){
            //if(confirm('Are you sure to delete?')){
                var url = '".$trashAjaxUrl."';
                $.post(url, {'uuid':uuid}, function(res) {
                    
                    if (res == 1) {
                        showData();
                    }else{
                        dynamicAlertBox('Error','Invalid request, please try again!');
                    }
                });
            //}
        }
    }
    function verifyPrisoner(uuid){
        if(uuid){
            if(confirm('Are you sure to verify?')){
                var url = '".$verifyAjaxUrl."';
                $.post(url, {'uuid':uuid}, function(res) { 
                    if (res == 1) {
                        showData();
                    }else{
                        dynamicAlertBox('Error','Invalid request, please try again!');
                    }
                });
            }
        }
    }
    function approvePrisoner(uuid){ 
        if(uuid){
            if(confirm('Are you sure to approve?')){
                var url = '".$approveAjaxUrl."';
                $.post(url, {'uuid':uuid}, function(res) { 
                    if (res == 'SUCC') {
                        showData();
                    }else{
                        dynamicAlertBox('Error','Invalid request, please try again!');
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
        $('#'+id)[0].reset();
        $('select').select2({minimumResultsForSearch: Infinity});
        showData();
    }

    function verifyPrisonerSetData(id){
        $('#verify').modal('show');
        $('#verifyId').val(id);
    }
    
    // function checkData(){
    //     var url = '".$biometricSearchAjax."';
    //     $.post(url, {}, function(res) { 
    //         if(res.trim()!='FAIL'){
    //             window.location='".$biometricRedirectAjax."/'+res;
    //         }else{
    //             alert('Please press figure on biometric or You are not registered');
    //         }            
    //     });
    // }

        function checkData(){
            var url = '".$biometricSearchAjax."';
            $.ajax({
                type: 'POST',
                url: url,
                success: function (res) {
                    if(res.trim()!='FAIL'){
                        window.location='".$biometricRedirectAjax."/'+res;
                    }else{
                        dynamicAlertBox('Error','Please press figure on biometric or You are not registered.');
                    }  
                },
                async:false
            });
        }
   
",array('inline'=>false));
?>

<script type="text/javascript">


function prisonerDeleteConfirm(formId, funcName) {
    AsyncConfirmYesNo(
            "Are you sure want to delete?",
            'Delete',
            'Cancel',
            MyYesPrisonerDelete,
            MyNoPrisonerDelete,
            formId,
            'Delete',
            funcName
        );
}
function MyYesPrisonerDelete(formId, funcName) 
{
  trashPrisoner(formId);
}
function MyNoPrisonerDelete() {
}



    $(function(){
 
        $.validator.addMethod("datevalidateformat", function(value, element) {
        //return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
        var dtRegex = new RegExp("^([0]?[1-9]|[1-2]\\d|3[0-1])-(01|02|03|04|05|06|07|08|09|10|11|12)-[1-2]\\d{3}$", 'i');
        return dtRegex.test(value);
        });

        
        $.validator.addMethod("valueNotEquals", function(value, element, arg){
                return arg !== value;
        }, "Please select valid data.");

       $("#SearchIndexForm").validate({
     
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
                     maxlength: 3
                },
                'data[Search][age_to]': {
                    min: 18,
                    maxlength: 3
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
                    min: "Minimum age: 18",
                    maxlength: "Minimum age should be max 3 digits."
                },
                'data[Search][age_to]': {
                    min: "Minimum age: 18",
                    maxlength: "Minimum age should be max 3 digits."
                },
            },
        }); 
    });

    var timer = null;


    function tick() {
        var url = '<?php echo $biometricSearchAjax; ?>';
        $.ajax({
            type: 'POST',
            url: url,
            success: function (res) {
                if(res.trim()!='FAIL'){
                    window.location='<?php echo $biometricRedirectAjax; ?>/'+res;
                }
            },
            async:false
        });
    };

    function start() {
        $('#biometricModal').modal('show');
        tick();
        timer = setTimeout(start, 1000);
        
    };

    function stop() {
        $('#biometricModal').modal('hide');
        clearTimeout(timer);
    };

    $('#start').click(start);

    function verifyNewAdmittedPrisoner(id){
        $('#oic-verify').modal('show');
        $('#oicVerifyId').val(id);
    }

    $(function(){

         //verify admitted prisoner by OIC
        $('#oicVerifyBtn').click(function(){
            var verification_type = $('.verification_type:checked').val();
            if(verification_type == 'reject' && $('#verify_remark').val()==''){
                dynamicAlertBox('Error','Please enter remark for reject.');
                return false;
            }
            var matches = [];            
            matches.push($('#oicVerifyId').val());
              
            
            if(verification_type != ''){
                //'+ids+'
                AsyncConfirmYesNo(
                    'Are you sure want to verify?',
                    'verify',
                    'Cancel',
                    function(){

                        var url = "<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'verifyAdmittedPrisoner'));?>";
                        $.post(url, {'type':verification_type,'ids':matches,'remarks':$('#verify_remark').val()}, function(res) { 
                            if (res.trim() == 'SUCC') {
                                $('#oic-verify').modal('hide');
                                $('#oic_verify_remark').val('');
                                showData();
                            }else{
                                dynamicAlertBox('Error','Invalid request, please try again!');
                            }
                        });

                    },
                    function(){}
                );
            }
            else 
            {
                $('#oic_verification_message_err').show();
            }
        });
    });
</script>