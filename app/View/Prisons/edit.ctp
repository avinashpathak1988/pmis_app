<style>
.leftSelect{margin-left:0;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Edit Prison Station</h5>
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
                        <label class="control-label">Name Of Station<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('name',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alpha',
                                'placeholder'=>'Enter Name Of Station',
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
                                'placeholder'=>'Enter Name Of Station Code',
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
                                'placeholder'=>'Enter Name Of Capacity Of Station',
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
                                   'empty'=>'',
                                   'required',
                                   'class'=>'span11 pmis_select',
                                 ));
                             ?>
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label">Congestion Level<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php
                                 echo $this->Form->input('congestion_id',array(
                                   'div'=>false,
                                   'label'=>false,
                                   'type'=>'number',
                                   'required',
                                   'placeholder'=>'Enter Congestion Level',
                                   'class'=>'span11 mobile',
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
                                   'empty'=>'',
                                   'required',
                                   'class'=>'span11 pmis_select',
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
                                    'placeholder'=>'Enter Physical Address ',
                                    'required',
                                    'rows'=>3
                                  ));
                               ?>
                            </div>
                          </div>
                      </div>
                      <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Prisons
                              Administrative Region
                            <?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('prisons_adm_region',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alpha',
                                'placeholder'=>'Enter Prisons Administrative Region',
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
                            <label class="control-label">Postal Address :</label>
                            <div class="controls">
                              <?php
                                  echo $this->Form->input('postal_address',array(
                                    'type'=>'textarea',
                                    'div'=>false,
                                    'label'=>false,
                                    'class'=>'span11',
                                     'placeholder'=>'Enter Postal Address',
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
                                     'placeholder'=>'Enter GPS location',
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
                                'placeholder'=>'Enter Fax Number',
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
                        <label class="control-label">Jurisdiction Area :</label>
                        <div class="controls">
                          <?php
                          $userrole_id_arr=array();
                          if (isset($this->data["Prison"]["magisterial_id"]) && strpos($this->data["Prison"]["magisterial_id"], ',') !== false) {
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
                          <label class="control-label">Phone Number :</label>
                          
                            <?php
                          $phone_arr=array();
                          if (isset($this->data["Prison"]["phone"]) && strpos($this->data["Prison"]["phone"], ',') !== false) {
                             $phone_arr=explode(",",$this->data["Prison"]["phone"]);
                             
                             $phone_arr=array_filter($phone_arr);
                           }
                           echo $this->Form->input('phone_count',array(
                                  'type'=>'hidden',
                                  'value'=>count($phone_arr),
                                  'id'=>'phone_count'
                                ));
                           if(count($phone_arr)>0)
                           {
                            $i = 0;
                             foreach($phone_arr as $phone_key=>$phone_field)
                             {
                              $i++;?>
                              <div class="controls add-more-phoneno">
                              <?php 
                               echo $this->Form->input('phone.',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11 numeric',
                                  'type'=>'text',
                                  'maxlength'=>'25',
                                  'placeholder'=>'Enter Phone Number',
                                  'value' => $phone_field,
                                  'required'=>false
                                ));?>
                                <div class="change">
                                  <?php 
                                  if($i>1)
                                  {
                                    //echo $this->Html->link('- Remove',array(),array('escape'=>false,'class'=>'btn btn-danger btn-mini remove'));
                                    ?>
                                    <a class='btn btn-danger btn-mini remove'>- Remove</a>
                                    <?php 
                                  }?>
                                </div>
                          </div>
                                <?php 
                             }
                           }
                           else 
                           {?>
                              <div class="controls add-more-phoneno">
                                <?php 
                                 echo $this->Form->input('phone.',array(
                                    'div'=>false,
                                    'label'=>false,
                                    'class'=>'span11 numeric',
                                    'type'=>'text',
                                    'maxlength'=>'25',
                                    'placeholder'=>'Enter Phone Number',
                                    'required'=>false
                                  ));?>
                                  <div class="change">
                                  </div>
                            </div>
                           <?php }
                             ?>
                             
                        </div>
                        <?php 
                        if(count($phone_arr) < 4)
                        {?>
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
                        <?php }
                        ?>
                        <div class="control-group">
                        <label class="control-label">Region Area :<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('state_id',array(
                                   'type'=>'select',
                                   'div'=>false,
                                   'label'=>false,
                                   'id'=>'state_id',
                                   'options'=>$state,
                                   'empty'=>'',
                                   'class'=>'span11 pmis_select',
                                   'onchange'=>'javascript:showDistrict(this.value);',
                                   'required'
                                 ));

                             ?>
                        </div>
                      </div>

                      <div class="control-group">
                                <label class="control-label">UPS PrisonDistrict :<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php 
                                      if(isset($this->request->data['Prison']['district_id']))
                                      {
                                        echo $this->Form->input('district_id', array('type'=>'select','class'=>'span11 pmis_select','id'=>'district_id','options'=>$districtList,'div'=>false,'label'=>false,'onchange'=>'javascript:showGeoDistrict(this.value);','required'));
                                     
                                      }
                                      else
                                      {
                                        echo $this->Form->input('district_id', array('type'=>'select','class'=>'span11 pmis_select','id'=>'district_id','options'=>'','empty'=>'','div'=>false,'label'=>false,'onchange'=>'javascript:showGeoDistrict(this.value);','required'));
                                      }
                                    ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Geographical District :<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php 
                                    
                                      if(isset($this->request->data['Prison']['geographical_id']))
                                      {
                                         echo $this->Form->input('geographical_id', array('type'=>'select','class'=>'span11 pmis_select','id'=>'geographical_id','options'=>$geodistrictList,'div'=>false,'label'=>false,'required'));
                                      }
                                      else
                                      {
                                         echo $this->Form->input('geographical_id', array('type'=>'select','class'=>'span11 pmis_select','id'=>'geographical_id','options'=>'','empty'=>'','div'=>false,'label'=>false,'required'));
                                      }
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
<script type="text/javascript">

$(document).ready(function() {
    var phone_count = '<?php echo count($phone_arr);?>';
    $('#phone_count').val(phone_count);
    $("body").on("click","#add-more",function(){ 
        var html = $(".add-more-phoneno").first().clone();
      
        $(html).find(".change").html("<a class='btn btn-danger btn-mini remove'>- Remove</a>");

        $(html).find("input").val('');
        var phone_count = $('#phone_count').val();
        phone_count = parseInt(phone_count)+1;
        $('#phone_count').val(phone_count);
        $(".add-more-phoneno").last().after(html);
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
	
	$('#PrisonMagisterialId').select2({placeholder: "Select Jurisdiction area",allowClear: true});
});

 /*$(document).on('change', '#PrisonTier', function() {
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
});*/
$(document).ready(function(){
    //$('.datepicker').datepicker();
});


$(function(){
    $("#PrisonEditForm").validate({
     
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
                'data[Prison][prisons_adm_region]':{
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
                'data[Prison][prisons_adm_region]':{
                  required: "Please choose Prisons Administrative Region.",
                },
                'data[Prison][state_id]':{
                  required: "Please choose Region.",
                },
                'data[Prison][district_id]':{
                  required: "Please choose District.",
                },
                'data[Prison][geographical_id]':{
                  required: "Please choose Geographical District.",
                },
                
        
            },
               
    });
  });
</script>
<?php 
$districtajaxUrl = $this->Html->url(array('controller'=>'Prisons','action'=>'getdistrictAjax'));
$geodistrictajaxUrl = $this->Html->url(array('controller'=>'Prisons','action'=>'getgeodistrictAjax'));
?>
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
function showDistrict(id)
    {
        var url = '<?php echo $districtajaxUrl ?>';
        url = url + '/state_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#district_id').html(res);
            }
        });
    }
    function showGeoDistrict(id)
    {
        var url = '<?php echo $geodistrictajaxUrl ?>';
        url = url + '/district_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#geographical_id').html(res);
            }
        });
    }
</script>

