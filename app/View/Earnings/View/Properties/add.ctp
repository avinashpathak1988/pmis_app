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
                    <h5>Add New property Records</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <ul class="nav nav-tabs">
                            <li><a href="#property">Property</a></li>
                           <!--- <li><a href="#seek">Seek</a></li>
                            <li><a href="#seriouly_ill">Seriously Ill</a></li>
                            <li><a href="#death">Death</a></li>-->
                            
                            <!-- <li class="pull-right controls"> -->
                            <li class="controls pull-right">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="tabscontent">
                            
                            <div id="property">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisner No<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prisoner No','id'=>'first_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prisoner Name','required','id'=>'last_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Property Description<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('country_id',array('div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'country_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Date<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_checkup',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date ','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="clearfix"></div>                           
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Source<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('country_id',array('div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'country_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                     
                                    <div class="clearfix"></div>                           
                                                        
                                </div>

                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                                    
                            </div>
                             <!--<div id="tab-3" class="lorem">
                             <?php //echo $this->Form->create('RelationshipDetail',array('class'=>'form-horizontal','url' => '/prisoners/prisnorsIdInfo'));?>
                              <?php //echo $this->Form->end();?>
                             </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

function removeDataId(id,model){
         if(id == 0){
            $("#row_delete_id"+model).closest("tr").remove();
         }else{
            var url = "<?php echo $this->Html->url(array('controller'=>'Prisoners','action'=>'removeDataId'))?>";
            if(confirm('Are you sure want to delete?')){
                $.post(url,{'id':id,'model':model},function(res){ 
                    if(res.trim()=="SUCC"){
                        $("#row_delete_id"+id).closest("tr").remove();
                    }else{
                        alert('Problem in delete');
                    }             
                });     
            }
         }
    }
var select_var='<?php echo $idselct;?>';

$(document).ready(function () {
  $("#addrow_for_id").on("click", function () {
            var counter = $('#myTable_id >tbody >tr').length;

            var newRow = $("<tr>");
            var cols = "";
            cols += '<td>'+select_var+'</td>';
            cols += '<td><input id="PrisonerIdNumber" type="text" required="required" name="data[PrisonerIdDetail][id_number][]"></td>';

            //cols += '<td><input type="file" name="data[Route]['+counter+'][route_info]"/></td>';

            cols += '<td><a id="row_delete_id'+counter+'" onclick="removeDataId(0,'+counter+')"><i class="icon icon-trash delete_row"></i></a></td>';
           
            //cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger " id="row_delete_route'+counter+'" onclick="removeDataRoute(0,'+counter+')"  value="Delete"></td>';
            newRow.append(cols);
            $("#myTable_id").append(newRow);
            counter++;
            $('#count').val(counter);
            $('.selectbox').select2();
    });
});
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

$(document).on('click',"#saveBtn_iddetail", function () { // button name
    tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
    var action = $(this).attr('tabcls');
    $("#PrisonerIdDetailAddForm").ajaxForm({ //form name
        beforeSend: function(){
            // $.blockUI({ message: '<h1> Just a moment...</h1>' });
            // $("#submit").html('');
            // $("#submit").html('loading...');
        },
        success: function(html){
            tabs[action]();
            e.preventDefault();
        },
    }).submit();
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
        $('#saveBtn').on('click', function(e){
            if(!$('#PrisonerAddForm').valid())
             {
                return false;
              }
            var photo = $('#photo').prop('files')[0];
            var first_name = $('#first_name').val();  
            var last_name= $('#last_name').val();
            var father_name= $('#father_name').val();
            var mother_name= $('#mother_name').val();
            var date_of_birth= $('#date_of_birth').val();
            var place_of_birth= $('#place_of_birth').val();
            var gender= $('#gender').val();
            var country_id= $('#country_id').val();
            var permanent_address=$('#permanent_address').val();

            var form_data = new FormData();                  
            form_data.append('photo', photo);
            form_data.append('first_name', first_name); 
            form_data.append('last_name', last_name);
            form_data.append('father_name', father_name);
            form_data.append('mother_name', mother_name);                          
            form_data.append('date_of_birth', date_of_birth);
            form_data.append('place_of_birth', place_of_birth);
            form_data.append('gender', gender);
            form_data.append('country_id', country_id);
            form_data.append('permanent_address', permanent_address);
            
            var action = $(this).attr('tabcls');
            var prisnor_id_set=$('.prisoner_id');
            $.ajax({
                        url: '".$userinfoajaxUrl."', // point to server-side PHP script 
                        dataType: 'json',  // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,                         
                        type: 'post',
                        success: function(data){
                            if(data.success=='success'){
                                prisnor_id_set.val(data.prisnor_id_set);
                                tabs[action]();
                                e.preventDefault();
                            }
                            else{
                                showJsonErrors(data.success);
                            }
                        }
             });
            
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