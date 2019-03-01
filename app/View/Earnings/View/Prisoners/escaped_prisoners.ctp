 <div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> 
                    <h5>Escaped Prisoners</h5>
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
                            <div class="control-group">
                                <label class="control-label">Prisoner Name :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Prisoner Name','id'=>'prisoner_name'));?>
                                </div>
                            </div>
                            <!--Search by Prison starts -->
                             <div class="control-group">
                                    <label class="control-label">Prisons:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prison',array('div'=>false,'label'=>false,'class'=>'width="10%"','type'=>'select','options'=>$prisonList, 'empty'=>'','required','id'=>'prison'));?>
                                    </div>
                                </div>
                            <!--Search by Prison ends -->
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">EPD :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'epd_from', 'readonly'=>true,'style'=>'width:42.5%;'));?>
                                    To
                                    <?php echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'epd_to', 'readonly'=>true,'style'=>'width:42.5%;'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Age between:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('age_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','id'=>'age_from', 'maxlength'=>'3', 'style'=>'width:42.5%;'));?>
                                    &
                                    <?php echo $this->Form->input('age_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','id'=>'age_to', 'maxlength'=>'3', 'style'=>'width:42.5%;'));?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="advance_search hide">
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
                                    <label class="control-label">Type of Disability:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('special_condition_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select', 'onChange'=>'getTypeOfDisability()', 'options'=>$specialConditionList, 'empty'=>'-- Select Type of Disability --','id'=>'special_condition_id', 'required'));?>
                                                </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Case File No.:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('case_file_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric','type'=>'text','id'=>'case_file_no'));?>
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
                                <div class="control-group">
                                    <label class="control-label">Subcategory Disability :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('type_of_disability',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>'', 'options'=>'', 'empty'=>'-- Select Subcategory Disability --','id'=>'type_of_disability', 'required'=>false));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">
                                        <?php $habitual_prisoner = 0;
                                        echo $this->Form->input('habitual_prisoner',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false,'id'=>'habitual_prisoner', 'default' => $habitual_prisoner));?>
                                    </label>
                                    <div class="controls">
                                        Habitual prisoner?
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="span12" align="center" valign="center">
                            <?php echo $this->Form->button('Advance Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-info', 'onclick'=>"showDiv('advance_search')"))?>
                            <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'onclick'=>"showData()"))?>
                            <?php echo $this->Form->input('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchEscapedPrisonersForm')"))?>
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
$ajaxUrl            = $this->Html->url(array('controller'=>'prisoners','action'=>'escapedPrisonersAjax'));

$getPrisonerSubajaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getPrisonerSubType'));

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
        showData();
    });
    
    function showData(){ 
        var url = '".$ajaxUrl."';
        if($('#sprisoner_no').val() != ''){
            var prisoner_no = $('#sprisoner_no').val().replace('/', '-')
            url = url + '/prisoner_no:'+prisoner_no;
        }
        $.post(url, $('#SearchEscapedPrisonersForm').serialize(), function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
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

       $("#SearchEscapedPrisonersForm").validate({
     
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