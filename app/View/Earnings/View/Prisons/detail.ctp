<?php //echo '<pre>'; print_r($prisonData); exit;?>
<style>
.PrisonDetailPage .span6 {
    padding: 5px;
}
.PrisonDetailPage .span6 label {
    float: left;
    margin-right: 20px;
    text-align: right;
    width: 42%;
    font-weight: bold;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Prison Station Detail</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Prison Station List',array(
                                    
                                    'action'=>'index'
                                ),array(
                                    'escape'=>false,
                                    'class'=>'btn btn-success btn-mini'
                                )); ?>
              &nbsp;&nbsp;
          </div>
          </div>
          <div class="widget-content nopadding PrisonDetailPage">
               
               <div class="row-fluid">
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Number Of prisoners In Custody :</label>
                        <div class="controls">
                          <?php echo $prisonData['Prison']['pCount'];?>
                        </div>
                      </div>
                  </div>
                  <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Congestion Level :</label>
                        <div class="controls">
                          <?php echo stripslashes($prisonData['Prison']['congestion_level']);?>
                        </div>
                      </div>
                  </div>
                </div>  
                <div class="row-fluid">
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">List Of Courts :</label>
                        <div class="controls">
                          <?php echo $courtList;?>
                        </div>
                      </div>
                  </div>
                  <div class="span6">
                       <div class="control-group">
                        <label class="control-label">Capacity Of Station :</label>
                        <div class="controls">
                          <?php echo $prisonData['Prison']['capacity'];?>
                        </div>
                      </div>
                    </div>
                </div>  
               <div class="row-fluid">
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Station Name :</label>
                        <div class="controls">
                          <?php echo stripslashes($prisonData['Prison']['name']);?>
                        </div>
                      </div>
                  </div>
                  <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Station Code :</label>
                        <div class="controls">
                          <?php echo stripslashes($prisonData['Prison']['code']);?>
                        </div>
                      </div>
                  </div>
                </div>  
                
                <div class="row-fluid">
                    
                   <div class="span6">   
                      <div class="control-group">
                          <label class="control-label">Date Of Opening :</label>
                          <div class="controls">
                            <?php echo date('d-m-Y', strtotime($prisonData['Prison']['date_of_opening']));?>
                          </div>
                        </div>
                      </div>
                      <div class="span6">
                         <div class="control-group">
                          <label class="control-label">Security Level :</label>
                          <div class="controls">  
                            <?php echo $prisonData['Mastersecurity']['name'];?>
                          </div>
                        </div>
                    </div>
                 </div> 
                    
                  <div class="row-fluid">
                    
                    <div class="span6">
                        <div class="control-group">
                          <label class="control-label">Category :</label>
                          <div class="controls">
                            <?php echo $prisonData['Prison']['name'];?>
                          </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Physical Address :</label>
                            <div class="controls">
                              <?php if($prisonData['Prison']['physical_address'] != '')echo $prisonData['Prison']['physical_address'];else echo 'N/A';?>
                            </div>
                          </div>
                      </div>
                  </div>

                   <div class="row-fluid">
                      
                      <div class="span6">
                          <div class="control-group">
                            <label class="control-label">Postal Address :</label>
                            <div class="controls">
                              <?php if($prisonData['Prison']['postal_address'] != '')echo $prisonData['Prison']['postal_address'];else echo 'N/A';?>
                            </div>
                          </div>
                      </div>
                      <div class="span6">
                            <div class="control-group">
                            <label class="control-label">GPS location :</label>
                            <div class="controls">
                              <?php echo $prisonData['Prison']['gps_location'];?>
                            </div>
                          </div>
                      </div>
                   </div>

                   <div class="row-fluid">
                   
                          <div class="span6">
                              <div class="control-group">
                                <label class="control-label">Phone Number :</label>
                                <div class="controls">
                                  <?php if($prisonData['Prison']['phone'] != '')echo $prisonData['Prison']['phone'];else echo 'N/A';?>
                                </div>
                              </div>
                          </div>
                          <div class="span6">
                                <div class="control-group">
                                <label class="control-label">Email :</label>
                                <div class="controls">
                                  <?php echo $prisonData['Prison']['email'];?>
                                  <?php if($prisonData['Prison']['email'] == '' && $prisonData['Prison']['email2'] == '')echo 'N/A';echo $prisonData['Prison']['email'];?>
                                </div>
                              </div>
                          </div>
                </div>

                <div class="row-fluid">
                 
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Fax Number :</label>
                        <div class="controls">
                          <?php if($prisonData['Prison']['fax'] != '')echo $prisonData['Prison']['fax'];else echo 'N/A';?>
                        </div>
                      </div>
                  </div>
                  <div class="span6">
                      <div class="control-group">
                        <label class="control-label"></label>
                        <div class="controls">
                          <?php echo $prisonData['Prison']['email2'];?>
                        </div>
                      </div>
                  </div>
                  </div>
                  <div class="row-fluid">                
                
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Magisterial Area :</label>
                        <div class="controls">
                          <?php if(isset($prisonData['Magisterial']['name']) && $prisonData['Magisterial']['name'] != '')echo $prisonData['Magisterial']['name']; else echo 'N/A';?>
                        </div>
                      </div>
                  </div>
                    <!-- <div class="span6">
                      <div class="control-group">
                      <label class="control-label">Status</label>
                      <div class="controls">
                        <?php if(isset($prisonData['Prison']['is_enable']))
                        {
                          if($prisonData['Prison']['is_enable'] == 1)
                          {
                            echo 'Active';
                          }
                          else 
                          {
                            echo 'Inactive';
                          }
                        }?>
                      </div>
                    </div>
                  </div> -->
                  <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Region :</label>
                        <div class="controls">
                          <?php if(isset($prisonData['State']['name']) && $prisonData['State']['name'] != '')echo $prisonData['State']['name']; else echo 'N/A';?>
                        </div>
                      </div>
                  </div>
                  </div>
                   <div class="row-fluid" style="margin-bottom: 10px;">
                 
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Prison District :</label>
                        <div class="controls">
                          <?php if($prisonData['PrisonDistrict']['name'] != '')echo $prisonData['PrisonDistrict']['name'];else echo 'N/A';?>
                        </div>
                      </div>
                  </div>
                  <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Geographical District :</label>
                        <div class="controls">
                          <?php if($prisonData['GeographicalDistrict']['name'] != '') echo $prisonData['GeographicalDistrict']['name'];else echo 'N/A';?>
                        </div>
                      </div>
                  </div>
                  </div>
                  <!-- <div class="row-fluid" style="margin-bottom: 10px;">
                      

                    
                </div>    -->          
              
              
            <?php
echo $this->Form->end();
             ?>
          </div>
        </div>
      </div>
    </div>
  </div>
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

