<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Modify Prison Station</h5>
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

                              echo $this->Form->input('id',array(
                                'type'=>'hidden',
                              ));
                          
               ?>
              <input type="hidden" name="minimum" id="minimum">
               <input type="hidden" name="maximum" id="maximum">
               <div class="row-fluid">
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Station Name<?php echo MANDATORY; ?> :</label>
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
                          <label class="control-label">Tiers<?php echo MANDATORY; ?></label>
                          <div class="controls">
                            <?php
                                echo $this->Form->input('tier_nm',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11',
                                  'type'=>'text',
                                  'required',
                                  'readonly'=>'readonly'
                                ));
                             ?>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    
                   <div class="span6">   
                      <div class="control-group">
                          <label class="control-label">Date Of Opening<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php
                            $date_of_opening=date('d-m-Y',strtotime($this->request->data["Prison"]["date_of_opening"]));
                                echo $this->Form->input('date_of_opening',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'text',
                                  'class'=>'span11 mydate',
                                  'data-date-format'=>"dd-mm-yyyy",
                                  'readonly'=>'readonly',
                                  'required',
                                  'value'=>$date_of_opening,
                                ));
                             ?>
                          </div>
                        </div>
                      </div>
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
                    </div>
                 </div> 
                    
                  <div class="row-fluid">
                    
                    <div class="span6">
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
                               <!-- <input type="button" name="" id="gps_loc" value="Get GPS Location"> -->
                            </div>
                          </div>
                      </div>
                   </div>

                   <div class="row-fluid">
                   
                          <div class="span6">
                              <div class="control-group">
                                <label class="control-label">Phone Number :</label>
                                <div class="controls">
                                  <?php
                                      echo $this->Form->input('phone',array(
                                        'div'=>false,
                                        'label'=>false,
                                        'class'=>'span11 numeric',
                                        'type'=>'text',
                                      ));
                                   ?>
                                </div>
                              </div>
                          </div>
                          <div class="span6">
                                <div class="control-group">
                                <label class="control-label">Mobile Number :</label>
                                <div class="controls">
                                  <?php
                                      echo $this->Form->input('mobile',array(
                                        'div'=>false,
                                        'label'=>false,
                                        'class'=>'span11 numeric',
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
                  <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Email address :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('email',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'email',
                              ));
                           ?>
                        </div>
                      </div>
                  </div>
                  </div>
                  <div class="row-fluid" style="margin-bottom: 10px;">                
                
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Magisterial Area :</label>
                        <div class="controls">
                          <?php
                          $userrole_id_arr=array();
                          if (strpos($this->data["Prison"]["magisterial_id"], ',') !== false) {
                             $userrole_id_arr=explode(",",$this->data["Prison"]["magisterial_id"]);
                             
                             $userrole_id_arr=array_filter($userrole_id_arr);
                           }
                                 echo $this->Form->input('magisterial_id',array(
                                   'div'=>false,
                                   'label'=>false,
                                   'options'=>$magisterial_id,
                                   'empty'=>'-- Select Magisterial Area--',
                                   'selected' => $userrole_id_arr,
                                   'class'=>'span11',
                                   'multiple'=>'multiple'
                                 ));

                             ?>
                          
                        </div>
                      </div>
                  </div>
                  
                    <div class="span6">
                      <div class="control-group">
                      <label class="control-label">Is Enabled ?</label>
                      <div class="controls">
                        <?php
                            echo $this->Form->input('is_enable',array(
                              'div'=>false,
                              'label'=>false,
                              'class'=>'span11',
                              'options'=>$is_enable,
                              'default'=>1,
                              'style'=>'width:120px',
                            ));
                         ?>
                      </div>
                    </div>
                  </div>
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
<script src='https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyBodFycVkrwRnET3bCpvNZe2LdZkVRPdD0'></script>

<script type="text/javascript">
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

