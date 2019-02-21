<?php
if(isset($this->data['Prisoner']['date_of_birth']) && $this->data['Prisoner']['date_of_birth'] != ''){
    $this->request->data['Prisoner']['date_of_birth']=date('d-m-Y',strtotime($this->data['Prisoner']['date_of_birth']));
}
?>
<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
#nationality_name2, #nationality_name2_note
{
    margin-top: 15px;
}
.topMargin div{margin-top: 10px;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Prisoner</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
              
                           
                                <?php 
                                echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/addPrisoner/'));
                                // echo $this->Form->input('status',array(
                                //         'type'=>'hidden',
                                //         'value'=>'Admitted'
                                //     ));
                                echo $this->Form->input('status',array(
                                    'type'=>'hidden',
                                    'value'=>'G-Draft'
                                ));
                                echo $this->Form->input('is_added_by_gatekeeper',array(
                                    'type'=>'hidden',
                                    'value'=>'1'
                                ));
                                if(isset($this->request->data['Prisoner']['id']))
                                {
                                    $prisoner_unique_no = $this->request->data['Prisoner']['prisoner_unique_no'];
                                    
                                    echo $this->Form->input('prisoner_unique_no',array(
                                        'type'=>'hidden',
                                        'class'=>'prisoner_unique_no',
                                        'value'=>$prisoner_unique_no
                                    ));
                                    echo $this->Form->input('personal_no',array(
                                        'type'=>'hidden',
                                        'value'=>$this->request->data['Prisoner']['personal_no']
                                    ));
                                    $this->Form->control('id', array('value' => ''));
                                    echo $this->Form->input('exp_photo_name',array(
                                        'type'=>'hidden',
                                        'class'=>'exp_photo_name',
                                        'value'=>$this->request->data['Prisoner']['photo']
                                    ));
                                    echo $this->Form->input('is_ext',array(
                                        'type'=>'hidden',
                                        'class'=>'is_ext',
                                        'value'=>1
                                    ));
                                    echo $this->Form->input('present_status',array(
                                        'type'=>'hidden',
                                        'class'=>'present_status',
                                        'value'=>1
                                    ));
                                }
                                ?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">DOA<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('doa',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text','placeholder'=>'Enter Date Of Admission','id'=>'doa', 'default'=>date('d-m-Y'), 'readonly'));?>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">First Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Enter First Name','id'=>'first_name','maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Middle name :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('middle_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Middle name','required'=>false,'id'=>'middle_name','maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Surname :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Enter Surname','required'=>false,'id'=>'last_name','maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Type<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonerTypeList, 'empty'=>'','required','id'=>'prisoner_type_id','onchange'=>'showPrisonerSubType();'));?>
                                            </div>
                                        </div>
                                    </div>                                                           
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Sex<?php echo $req; ?> :</label>
                                            <div class="controls uradioBtn">
                                                <?php 
                                                $gender = 1;
                                                if(isset($this->data['Prisoner']['gender_id']))
                                                    $gender = $this->data['Prisoner']['gender_id'];
                                                $options2= $genderList;
                                                $attributes2 = array(
                                                    'legend' => false, 
                                                    'value'  => $gender,
                                                    'onclick'=> 'dissplayStatusOfWomen(this.value);'
                                                );
                                                echo $this->Form->radio('gender_id', $options2, $attributes2);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Continent<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php 
                                                echo $this->Form->input('continent_id',array('div'=>false,'label'=>false,'onChange'=>'showCountries(this.value)','class'=>'form-control span11 pmis_select','type'=>'select','options'=>$continentList, 'empty'=>'','required','id'=>'continent_id', 'selected'=>'1'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Country of origin<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('country_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$countryList, 'empty'=>'','required'=>false,'id'=>'country_id','default'=>1));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Link with Biometric:</label>
                                            <div class="controls">
                                                <?php 
                                                echo $this->Form->input('link_biometric',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$this->requestAction('/Biometrics/getUnlinkedBioUser'), 'empty'=>'','required'=>false,'id'=>'link_biometric'));
                                                  ?>
                                                  <span id="link_biometric_span"></span>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Date of Birth<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control span11 prisoner_dob','type'=>'text', 'placeholder'=>'Enter Date of Birth','required','id'=>'date_of_birth','readonly'=>'readonly', 'onChange'=>'getPrisonerClass();'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div>
                                    <div class="span6 hidden" id="classification_div">
                                        <div class="control-group">
                                            <label class="control-label">Classification<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('classification_id',array('div'=>false,'label'=>false, 'type'=> 'hidden', 'id'=>'classification_id'));

                                                echo $this->Form->input('classification_id_display',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$classificationList, 'empty'=>'','required'=>false,'id'=>'classification_id_display', 'readonly', 'disabled'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="span6 hidden">
                                        <div class="control-group">
                                            <label class="control-label">Nationality:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Nationality','required','id'=>'nationality_name', 'readonly', 'maxlength'=>'30','value'=>'Uganda'));?>
                                            </div>
                                        </div>
                                    </div>            
                                </div>
                                <div class="form-actions" align="center">
                                <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true))?>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"resetForm('PrisonerAddPrisonerForm');"))?>
                                    
                                </div>
                                <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$uganda_country_id = Configure::read('COUNTRY-UGANDA');
$remand_type = Configure::read('REMAND');
?>
<script type="text/javascript">
//open other field 
function openOtherField(fname)
{
    //alert(fname);
    var field_id = fname+'_id';
    var selected_val = $('#'+field_id).val();
    var other_field_id = 'other_'+fname;
    var placeholder_val = fname.charAt(0).toUpperCase() + fname.substr(1);
    placeholder_val = placeholder_val.replace(/_/g, " ");
    if(selected_val == 'other')
    {
        var other_field = '<div><input name="data[Prisoner][other_'+fname+']" class="form-control span11" placeholder="'+placeholder_val+'" id="other_'+fname+'" style="margin-top:10px;" maxlength="30" type="text" required></div>';
        $( other_field ).insertAfter('#'+field_id);
    }
    else 
    {
        if ($('#'+other_field_id).length)
        {
            //remove validation error message
            //var $validator = $("#PrisonerAddForm").validate();
            //errors = { 'data[Prisoner][other_tribe]': "" };
            //$validator.showErrors(errors);  
            $('#'+other_field_id).parent().find('label').remove();
            //remove other field  
            $('#'+other_field_id).remove();
        }
    }
}
$(document).ready(function() {
    //auto focus on prisoner's first name 
    
    $('#first_name').focus(); 
    //if other country selected 
    if($('#country_id').val() == 'other')
    {
        $('#other_country').show();   
    }
    else 
    {
        $('#other_country').hide();  
    }
});
$(document).ready(function(){
    //$('#country_id').select2('val', '1');
    //$('#continent_id').select2('val', '1');
    //$("#country_id option[value='1']").attr("selected","selected");
    //$("#continent_id option[value='1']").attr("selected","selected");
        
});
function showCountries(id)
{
    var strURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'countryList'));?>';
    $.post(strURL,{"continent_id":id},function(data){  
        
        if(data) { 
            $('#country_id').html(data); 
            if(id == 1)
            {
                $('#country_id').val(1);
            }
            else 
            {
                $('#country_id').val(0);
            }
            $('#country_id').select2({
                placeholder: "-- Select --",
                allowClear: true
            });
        }
        else
        {
            alert("Error...");  
        }
    });
}
function getNationality(country_id)
{
    $("#nationality_name").attr('readonly','readonly');
    if(country_id != '')
    { 
        if(country_id != 'other')
        {
            $.ajax(
            {
                type: "POST",
                dataType: "html",
                url: "<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'getNationName'));?>",
                data: {
                    country_id: country_id,
                },
                cache: true,
                beforeSend: function()
                {  
                  //$('tbody').html('');
                },
                error: function ( jqXHR, textStatus, errorThrown) {
                     alert("errorThrown: " + errorThrown + " textStatus:" + textStatus);
                },
                success: function (data) {
                  $("#nationality_name").val(data);
                },
                
            });
        }
        else 
        {
            openOtherField("country");
            $("#nationality_name").val('');
            $("#nationality_name").removeAttr('readonly');
        }
    }
}
$(document).ready(function () {

    $("select").trigger("change");

    var year_range = '<?php echo date("Y")-120;?>:<?php echo date("Y");?>-18';

    $('#date_of_birth').datepicker({
        onSelect: function(value, ui) {
            var prisoner_type_id = $('#prisoner_type_id').val();
            getPrisonerClass(prisoner_type_id, value);
        },
        maxDate: '+0d',
        yearRange: year_range,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
    });
    var country_id=$('#country_id').val();
    getNationality(country_id);

    $(document).on('change', '#country_id', function() {
        var country_id=$(this).val();
        getNationality(country_id);
    });  
    
}); 
$(function(){

     $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\,\.\s]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");
     
        $("#PrisonerAddPrisonerForm").validate({
     
      ignore: "",
            rules: {  
                'data[Prisoner][first_name]': {
                    required: true,
                },
                'data[Prisoner][gender]': {
                    required: true,
                },
                'data[Prisoner][prisoner_type_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                'data[Prisoner][continent_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                'data[Prisoner][country_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                'data[Prisoner][date_of_birth]': {
                    required: true,
                }
            },
            messages: {
                'data[Prisoner][first_name]': {
                    required: "Please enter first name.",
                },
                'data[Prisoner][gender]': {
                    required: "Please select gender.",
                },
                'data[Prisoner][prisoner_type_id]': {
                    required: "Please select prisoner type.",
                    valueNotEquals: "Please select prisoner type.",
                },
                'data[Prisoner][continent_id]': {
                    required: "Please select continent.",
                },
                'data[Prisoner][country_id]': {
                    required: "Please select country.",
                    valueNotEquals: "Please select country.",
                },
                'data[Prisoner][date_of_birth]': {
                    required: "Please select date of birth."
                }
            }, 
    });
  });

</script>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'users','action'=>'getDistrict'));
$userinfoajaxUrl=$this->Html->url(array('controller'=>'prisoners','action'=>'personalInfo'));
$idinfoajaxUrl=$this->Html->url(array('controller'=>'prisoners','action'=>'prisnorsIdInfo'));
$biometricSearchAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'getLastUser'));
$getPrisonerSubajaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getPrisonerSubType'));
$getClassificationUrl  = $this->Html->url(array('controller'=>'app','action'=>'getPrisonerClass'));
echo $this->Html->scriptBlock("
   var tabs;
    jQuery(function($) {
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });  
       
    }); 
    function getDistrict(){
        var url = '".$ajaxUrl."';
        $.post(url, {'state_id':$('#state_id').val()}, function(res) {
            if (res) {
                $('#district_id').html(res);
            }
        });
    }
    //get prisoner sub type list
    function showPrisonerSubType(){

        var url = '".$getPrisonerSubajaxUrl."';
        var prisoner_type_id = $('#prisoner_type_id').val();
        //alert(prisoner_type_id)
        var remand_type = '".$remand_type."';
        // if(prisoner_type_id == remand_type)
        // {
        //     $('#prisonerSubTypeValid').show();
        // }
        // else 
        // {
        //     $('#prisonerSubTypeValid').hide();
        //     $('#prisoner_sub_type_id').next('label.error').remove();
        // }
        // $.post(url, {'prisoner_type_id':prisoner_type_id}, function(res) {
        //     if (res) {
        //         $('#prisoner_sub_type_id').html(res);
        //         //$('#prisoner_sub_type_id').removeAttr('readonly');
        //     }
        //     else 
        //     {
        //         //$('#prisoner_sub_type_id').attr('readonly','readonly');
        //     }
        // });
        // $('#prisoner_sub_type_id').select2('val', '');
        var dob = $('#date_of_birth').val();
        getPrisonerClass(prisoner_type_id,dob);
    }
    
    function getPrisonerClass(prisoner_type_id='',dob='')
    {
        var prisoner_type_id = $('#prisoner_type_id').val();
        var dob = $('#date_of_birth').val();
        if(prisoner_type_id != '' && dob != '')
        {
            if(prisoner_type_id == 2)
            {
                $('#classification_id').attr('required','required');
                var classification_url = '".$getClassificationUrl."';
                $.post(classification_url, {'dob':dob, 'prisoner_type_id':prisoner_type_id}, function(class_res) {
                    if (class_res) {
                        $('#classification_id_display').val(class_res).trigger('change');
                        $('#classification_id').val(class_res);
                    }
                });
                $('#classification_div').show();
            }
            else 
            {
                $('#classification_id_display').val('').trigger('change');
                $('#classification_id').val('');
                $('#classification_id').removeAttr('required','');
                $('#classification_div').hide();
            }
        }
    }

    function checkData(){
        var url = '".$biometricSearchAjax."';
        $.ajax({
            type: 'POST',
            url: url,
            success: function (res) {
                if(res.trim()!='FAIL'){
                    $('#link_biometric').val(res.trim());
                    $('#link_biometric_span').html(res.trim());
                    $('#link_biometric_button').hide();
                }else{
                    alert('Please register in biometric first');
                }  
            },
            async:false
        });
    }

",array('inline'=>false));
?> 
