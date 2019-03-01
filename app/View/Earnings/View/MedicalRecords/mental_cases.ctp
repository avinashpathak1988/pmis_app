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
                    <h5>Mental Cases</h5>
                     <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('view Mental Cases'), array('action' => '/mentalCaseList'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                    <div style="float:right;padding-top: 7px;">
                        
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('MentalCase',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <?php echo $this->Form->input('id',array("type"=>"hidden"))?>
                                <?php echo $this->Form->input('prison_id',array("type"=>"hidden",'value'=>$this->Session->read('Auth.User.prison_id')))?>
                                <?php echo $this->Form->input('uuid',array('type'=>'hidden'))?>
                                <?php echo $this->Form->input('created_by',array('type'=>'hidden','value'=>$this->Session->read('Auth.User.id')))?>
                                <div class="row" style="padding-bottom: 14px;">
                                   
                                     <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner No. :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                   <?php 
                                                    if(isset($this->data['MedicalSickRecord']['id']) && $this->data['MedicalSickRecord']['id']!=''){
                                                        echo $funcall->getName($this->data['MedicalSickRecord']['prisoner_id'],"Prisoner","prisoner_no");
                                                        echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'hidden'));
                                                    }else{
                                                        echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoner --','options'=>$prisonerListData, 'class'=>'form-control','required', 'id'=>'prisoner_id_attendance'));
                                                    }
                                                   ?>
                                                </div>
                                            </div>
                                        </div> 
                                   
                                   <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner Name :</label>
                                                <div class="controls">
                                                   <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control','required', 'id'=>'prisoner_name_id','placeholder'=>'Prisoner name', 'readonly'));?>
                                                   
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                          <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Remarks :<?php echo $req; ?> </label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('remarks',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11 alphanumericone','required', 'cols'=>30, 'rows'=>3,'placeholder'=>'Enter Remarks'));?>
                                                    </div>
                                            </div>
                                        </div> 

                                    <div class="span6" id="mental_cases">
                                            <div class="control-group">
                                                <label class="control-label">Mental Cases:</label>
                                                <div class="controls uradioBtn">
                                                      <?php 
                                                    $mental_case = "No";
                                                    $options2= $mentalcasesList;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $mental_case,
                                                        'onChange'=>'showHivtesting(this.value)',
                                                    );
                                                    echo $this->Form->radio('mental_case', $options2, $attributes2);
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                 
                                 
                                         <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">
                                             <label class="control-label">Date:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Date Of Creation', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control','type'=>'text','required','default'=>date('d-m-Y')));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                     <div class="span6" id="certified_test" style="display: none;">
                                            <div class="control-group">
                                                <label class="control-label">Certified:</label>
                                                <div class="controls uradioBtn">
                                                     <?php 
                                                    $certified_case = "Certified";
                                                    $options2= $mentalcheckList;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $certified_case,
                                                    );
                                                    echo $this->Form->radio('certified_case', $options2, $attributes2);
                                                    ?>
                                                
                                                   
                                                </div>
                                            </div>
                                        </div>
                                <div class="clearfix"></div> 
                                <div class="form-actions" align="center">
                                    <?php echo $this->Form->input('save', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit'))?>
                                </div>
                                <?php echo $this->Form->end();?>     
                        </div>
                         <div class="table-responsive" id="listingDiv">

                    </div> 
                    
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $( document ).ready(function() {
        <?php
        if(isset($this->data['Occurance']['id']) && $this->data['Occurance']['id']!=''){
            ?>
            getShiftId(<?php echo $this->data['Occurance']['shift_id']; ?>);
            getLockupAjax(<?php echo $this->data['Occurance']['shift_id']; ?>);
            <?php
        }
        ?>
    });
    $("#OccuranceIndexForm").validate({
        ignore: "",
    });
function getShiftId(id){
  if(id!=''){
    var shift_date=$('#OccuranceDate').val();
    var strURL = '<?php echo $this->Html->url(array('controller'=>'Occurances','action'=>'getShiftId'));?>/'+id+'/'+shift_date;
    var strAbsentURL = '<?php echo $this->Html->url(array('controller'=>'Occurances','action'=>'absentStaff'));?>/'+id+'/'+shift_date;
    $.post(strURL,{},function(data){
      if(data) { 
       //alert(data);
          $('.areaof').show();
          $('#areaof').html(data);

      }
      else
      {
          $('.areaof').hide();
          alert("Error...");  
      }
    });

    $.post(strAbsentURL,{},function(data){
      if(data) { 
        $('#OccuranceNumberOfAbsentStafs').val(data);
      }
    });
  }
  }

  function getLockupAjax(id){
      var shift_date=$('#OccuranceDate').val();
      var strURL = '<?php echo $this->Html->url(array('controller'=>'Occurances','action'=>'lockupReportAjax'));?>/'+id+'/'+shift_date;
      $.post(strURL,{},function(data){
          if(data) { 
           //alert(data);
              $('#lockup').html(data);

          }
          else
          {
              $('#lockup').html('');
              alert("Error...");  
          }
      });
  }
  function showHivtesting(ishiv)
{
    // alert(1);
    if(ishiv == "Yes")
    {
        $('#certified_test').show();
        
    }
    else 
    {
        $('#certified_test').hide();
       
    }
}
$(document).on('change', '#prisoner_id_attendance', function(){
  var prisoner_id_attendance=$(this).val();
  $.ajax(
  {
      type: "POST",
      dataType: "json",
      url: "<?php echo $this->Html->url(array('controller'=>'MedicalRecords','action'=>'getPrisnerInfo'));?>",
      data: {
          prisoner_id: prisoner_id_attendance,
      },
      cache: true,
      beforeSend: function()
      {  
        //$('tbody').html('');
      },
      success: function (data) {
        $('#prisoner_name_id').val(data.prisoner_name);
        $('#MedicalSickRecordHeightFeet').val(data.height_feet);
        $('#MedicalSickRecordHeightFeet').select2('val',data.height_feet);
        if(data.is_restricted == 1){
            $('#MedicalSickRecordRestrictedPrisoner').attr('checked', true); // Checks it
            $("#uniform-MedicalSickRecordRestrictedPrisoner span").addClass("checked");
            // $("#malnutrition_type_id").val(1);
            // $("#restricted_prisoner").show();
            $("#restricted").show();

        }else{
            $("#uniform-MedicalSickRecordRestrictedPrisoner span").removeClass("checked");
            $('#MedicalSickRecordRestrictedPrisoner').attr('checked', false); // Unchecks it
            // $("#malnutrition_type_id").val('');
            // $("#restricted_prisoner").hide();
            $("#restricted").hide();
        }
        
      },
      error: function (errormessage) {
        alert(errormessage.responseText);
      }
  });
});
</script>
