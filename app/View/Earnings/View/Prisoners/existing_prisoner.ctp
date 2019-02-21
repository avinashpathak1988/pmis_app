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
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Existing Prisoner</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('New Prisoner',array('action'=>'add'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
              
                           
                                <?php echo $this->Form->create('existingPrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter First Name','id'=>'first_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <div class="controls">
                                                <?php echo $this->Form->button('Continue', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true))?>
                                            </div>
                                        </div>
                                    </div>                  
                                </div>
                                <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function showRegions(id) 
{ 
    var strURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'stateList'));?>';
    
    $.post(strURL,{"country_id":id},function(data){  
        
        if(data) { 
            $('#state_id').html(data); 
            
        }
        else
        {
            alert("Error...");  
        }
    });
}
function showDistricts(id) 
{ 
    var strURL = '<?php echo $this->Html->url(array('controller'=>'users','action'=>'getDistrict'));?>';
    
    $.post(strURL,{"state_id":id},function(data){  
        
        if(data) { 
            $('#district_id').html(data); 
            
        }
        else
        {
            alert("Error...");  
        }
    });
}
$(document).ready(function () {
  
    $(document).on('change', '#country_id', function() {
      var country_id=$(this).val();
       $.ajax(
        {
            type: "POST",
            dataType: "json",
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
              $("#nationality_name").val(data.nationality_name);
            },
            
        });
        //showRegions(country_id); 
    }); 
}); 
$(function(){
    $("#PrisonerAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Prisoner][first_name]': {
                    required: true,
                },
                'data[Prisoner][last_name]': {
                    required: true,
                },
                'data[Prisoner][father_name]': {
                    required: true,
                },
                'data[Prisoner][mother_name]': {
                    required: true,
                },
                'data[Prisoner][date_of_birth]': {
                    required: true,
                },
                'data[Prisoner][place_of_birth]': {
                    required: true,
                },
                'data[Prisoner][gender]': {
                    required: true,
                },
                'data[Prisoner][country_id]': {
                    required: true,
                },
                'data[Prisoner][tribe_id]': {
                    required: true,
                },
                'data[Prisoner][photo]': {
                    required: true,
                },
                
            },
            messages: {
                'data[Prisoner][first_name]': {
                    required: "Please enter first name.",
                },
                'data[Prisoner][last_name]': {
                    required: "Please enter last name.",
                },
                'data[Prisoner][father_name]': {
                    required: "Please enter father name.",
                },
                'data[Prisoner][mother_name]': {
                    required: "Please enter mother name.",
                },
                'data[Prisoner][date_of_birth]': {
                    required: "Please choose date of birth.",
                },
                'data[Prisoner][place_of_birth]': {
                    required: "Please enter place of birth.",
                },
                'data[Prisoner][gender]': {
                    required: "Please select gender.",
                },
                'data[Prisoner][country_id]': {
                    required: "Please select country.",
                },
                'data[Prisoner][tribe_id]': {
                    required: "Please select tribe.",
                },
                'data[Prisoner][photo]': {
                    required: "Please choose photo.",
                },
            },
               
    });
  });

</script>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'users','action'=>'getDistrict'));
$userinfoajaxUrl=$this->Html->url(array('controller'=>'prisoners','action'=>'personalInfo'));
$idinfoajaxUrl=$this->Html->url(array('controller'=>'prisoners','action'=>'prisnorsIdInfo'));
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
",array('inline'=>false));
?> 