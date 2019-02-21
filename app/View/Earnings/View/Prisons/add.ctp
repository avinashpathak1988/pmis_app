<style>
.leftSelect{margin-left:0;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add New Prison Station</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Prison Station List',array(
                                    
                                    'action'=>'index'
                                ),array(
                                    'escape'=>false,
                                    'class'=>'btn btn-success btn-mini'
                                )); ?>
              <?php //echo $this->Html->link('Users List',array(
                  //'action'=>'index',
                 // array('escape'=>false,'class'=>'btn btn-success'),
              //));
              ?>
              &nbsp;&nbsp;
          </div>
          </div>
          <div class="widget-content nopadding">
              <?php
echo $this->Form->create('Prison',array(
  'class'=>'form-horizontal'
));
               ?>

               <input type="hidden" name="minimum" id="minimum">
               <input type="hidden" name="maximum" id="maximum">
               <div class="row-fluid">
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Name Of Station<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('name',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alpha',
                                'type'=>'text',
                                'required',
                              ));
                           ?>
                        </div>
                      </div>
                  </div>
                  <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Station Code<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('code',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alphanumeric',
                                'type'=>'text',
                                'required',
                              ));
                           ?>
                        </div>
                      </div>
                  </div>
                </div>  
                <div class="row-fluid">
                    
                    <div class="span6">
                       <div class="control-group">
                        <label class="control-label">Capacity Of Station<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('capacity',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 numeric',
                                'type'=>'text',
                                'required',
                              ));
                           ?>
                        </div>
                      </div>
                    </div>
                    <div class="span6">
                          
                        <div class="control-group">
                          <label class="control-label">Date Of Opening<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php
                                echo $this->Form->input('date_of_opening',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'text',
                                  'class'=>'span11 mydate',
                                  'data-date-format'=>"dd-mm-yyyy",
                                  'readonly'=>'readonly',
                                  'required',
                                ));
                             ?>
                          </div>
                        </div>

                    </div>
                </div>
                
                    
                  <div class="row-fluid">
                    
                    <div class="span6">
                        
                        <div class="control-group">
                          <label class="control-label">Security Level<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php

                                 echo $this->Form->input('security_id',array(
                                   'div'=>false,
                                   'label'=>false,
                                   'options'=>$security_id,
                                   'empty'=>'-- Select Security Level--',
                                   'required',
                                   'class'=>'span11',
                                 ));
                             ?>
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label">Category<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php
                                 echo $this->Form->input('stationcategory_id',array(
                                   'div'=>false,
                                   'label'=>false,
                                   'options'=>$stationcategory_id,
                                   'empty'=>'-- Select Category--',
                                   'required',
                                   'class'=>'span11',
                                 ));
                             ?>
                          </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Physical Address<?php echo MANDATORY; ?> :</label>
                            <div class="controls">
                              <?php
                                  echo $this->Form->input('physical_address',array(
                                    'type'=>'textarea',
                                    'div'=>false,
                                    'label'=>false,
                                    'class'=>'span11',
                                    'required',
                                    'rows'=>3
                                  ));
                               ?>
                            </div>
                          </div>
                      </div>
                  </div>

                   <div class="row-fluid">
                      
                      <div class="span6">
                          <div class="control-group">
                            <label class="control-label">Postal Address :</label>
                            <div class="controls">
                              <?php
                                  echo $this->Form->input('postal_address',array(
                                    'type'=>'textarea',
                                    'div'=>false,
                                    'label'=>false,
                                    'class'=>'span11',
                                    'rows'=>3
                                  ));
                               ?>
                            </div>
                          </div>
                      </div>
                      <div class="span6">
                            <div class="control-group">
                            <label class="control-label">GPS location :</label>
                            <div class="controls">
                              <?php
                                  echo $this->Form->input('gps_location',array(
                                    'div'=>false,
                                    'label'=>false,
                                    'class'=>'span11',
                                    'type'=>'text',
                                    'readonly'=>'readonly',
                                  ));
                               ?>
                            </div>
                          </div>
                          <div class="control-group">
                        <label class="control-label">Fax Number :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('fax',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'text',
                              ));
                           ?>
                        </div>
                      </div>
                      </div>
                   </div>

                   <div class="row-fluid">
                    <div class="span6">
                          <div class="control-group">
                          <label class="control-label">Email :</label>
                          <div class="controls">
                            <?php
                              echo $this->Form->input('email',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'email',
                                'placeholder'=>'Primary Email'
                              ));
                           ?>
                          </div>
                        </div>
                        <div class="control-group">
                        <label class="control-label"></label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('email2',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'email',
                                'placeholder'=>'Secondary Email'
                              ));
                           ?>
                        </div>
                      </div>
                        <div class="control-group">
                        <label class="control-label">Magisterial Area :</label>
                        <div class="controls">
                          <?php
                                 echo $this->Form->input('magisterial_id',array(
                                   'div'=>false,
                                   'label'=>false,
                                   'options'=>$magisterial_id,
                                   'empty'=>'-- Select Magisterial Area--',
                                   'class'=>'span11 leftSelect',
                                   'multiple'=>'multiple',
                                 ));
                             ?>
                          
                        </div>
                      </div>

                    </div>
                    <div class="span6">
                        <div class="control-group">
                          <label class="control-label">Phone Number :</label>
                          <div class="controls add-more-phoneno">
                            <?php
                              echo $this->Form->input('phone_count',array(
                                  'type'=>'hidden',
                                  'value'=>1,
                                  'id'=>'phone_count'
                                ));
                                echo $this->Form->input('phone.',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11 numeric',
                                  'type'=>'text',
                                  'maxlength'=>'25',
                                  'placeholder'=>'Enter Phone Number'
                                ));
                             ?>
                             <div class="change"></div>
                          </div>
                        </div>
                        <div class="control-group">
                        <label class="control-label"></label>
                        <?php
                            echo $this->Form->button('+',array(
                              'div'=>false,
                              'label'=>false,
                              'class'=>'Addmore',
                              'type'=>'button',
                              'id'=>'add-more'
                            ));
                         ?>
                         </div>
                    </div>
                          
                </div>
                  <div class="row-fluid" style="margin-bottom: 10px;"> 
                  <?php 
                  echo $this->Form->input('is_enable',array(
                                        'type'=>'hidden',
                                        'class'=>'is_enable',
                                        'value'=>1
                                      ));
                  ?>
                    
                  </div>
              <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            <?php
echo $this->Form->end();
             ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">

$(document).ready(function() {
    $("body").on("click","#add-more",function(){ 
        var html = $(".add-more-phoneno").first().clone();
      
        $(html).find(".change").html("<a class='btn btn-danger btn-mini remove'>- Remove</a>");

        $(html).find("input").val('');
      
        $(".add-more-phoneno").last().after(html);

        var phone_count = $('#phone_count').val();
        phone_count = parseInt(phone_count)+1;
        $('#phone_count').val(phone_count);
        if(phone_count==4)
        {
          $('#add-more').hide();
        }
       
    });

    $("body").on("click",".remove",function(){ 
        $(this).parents(".add-more-phoneno").remove();
        var phone_count = $('#phone_count').val();
        phone_count = parseInt(phone_count)-1;
        $('#phone_count').val(phone_count);
        if(phone_count<4)
        {
          $('#add-more').show();
        }
    });
});

 $(document).on('change', '#PrisonTier', function() {
    var tierid=$(this).val();
     $.ajax(
    {
        type: "POST",
        dataType: "json",
        url: "<?php echo $this->Html->url(array('controller'=>'prisons','action'=>'getMiniMaxVal'));?>",
        data: {
            tierid: tierid,
           
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
          $('#minimum').val(data.minimum);
          $('#maximum').val(data.maximum);
        },
        
    });
 }); 
$(document).on('focusout', '#PrisonCapacity', function() {
  var capacity=$(this).val();
   $.ajax(
    {
        type: "POST",
        dataType: "json",
        url: "<?php echo $this->Html->url(array('controller'=>'prisons','action'=>'getTierVal'));?>",
        data: {
            capacity: capacity,
           
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
          $("#PrisonTierNm").val(data.name);
        },
        
    });
});
$(document).ready(function(){
    //$('.datepicker').datepicker();
});


$(function(){
    $("#PrisonAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Prison][security_id]': {
                    required: true,
                },
                'data[Prison][stationcategory_id]': {
                    required: true,
                },
                'data[Prison][name]': {
                    required: true,
                },
                'data[Prison][code]': {
                    required: true,
                },
                'data[Prison][capacity]': {
                    required: true,
                },
                'data[Prison][date_of_opening]': {
                    required: true,
                },
                
            },
            messages: {
                'data[Prison][security_id]': {
                    required: "Please select security level.",
                },
                'data[Prison][stationcategory_id]': {
                    required: "Please select category.",
                },
                'data[Prison][name]': {
                    required: "Please enter station name.",
                },
                'data[Prison][code]': {
                    required: "Please enter station code.",
                },
                'data[Prison][capacity]': {
                    required: "Please enter capacity.",
                },
                'data[Prison][date_of_opening]': {
                    required: "Please choose opening date.",
                },
                
        
            },
               
    });
  });
</script>
<script src='https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyBodFycVkrwRnET3bCpvNZe2LdZkVRPdD0'></script>

<script>
// $("#gps_loc").click(function(){
//             var geocoder =  new google.maps.Geocoder();
//     geocoder.geocode( { 'address': '#StationPhysicalAddress'}, function(results, status) {
//           if (status == google.maps.GeocoderStatus.OK) {
//             alert("location : " + results[0].geometry.location.lat() + " " +results[0].geometry.location.lng()); 
//           } else {
//             alert("Something got wrong " + status);
//           }
//         });
// });
$("#gps_loc").click(function(){

          
});
$(document).on('focusout', '#PrisonPhysicalAddress', function(){
    var geocoder =  new google.maps.Geocoder();
    geocoder.geocode( { 'address': $('#PrisonPhysicalAddress').val()}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            $('#PrisonGpsLocation').val(results[0].geometry.location.lat() + "," +results[0].geometry.location.lng());
            //$('.push-down').text(); 
          } 
        });
});
</script>

