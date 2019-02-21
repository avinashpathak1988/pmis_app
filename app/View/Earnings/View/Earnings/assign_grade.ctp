<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add station journals</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Station journals',array(
                                    
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
                echo $this->Form->create('Stationjournal',array(
                  'class'=>'form-horizontal','enctype'=>'multipart/form-data'
                ));
                $prison_id = $this->Session->read('Auth.User.prison_id');
                $user_id = $this->Session->read('Auth.User.id');
               ?>
                    <div class="row-fluid">
                      <div class="span6"> 
                       <div class="control-group">
                          <label class="control-label">Date<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php
                                echo $this->Form->input('journal_date',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11',
                                  'type'=>'text',
                                  'required',
                                  'data-date-format'=>"dd-mm-yyyy",
                                  'readonly',
                                  'placeholder'=>'Please Enter Date',
                                  'default'=>date('d-m-Y')
                                ));
                             ?>
                          </div>
                        </div>
                      </div>
                       <div class="span6">     
                        <div class="control-group">

                           <label class="control-label">Prison Stations Id<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php $selected='';
                          	if($prison_id!=''){
                          		$selected=$prison_id;
                          		echo $this->Form->input('prison_id',array('type'=>'hidden','value'=>$prison_id));
                          	}
                               echo $this->Form->input('prison_id1',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11 pmis_select',
                                  'style'=>'margin-left:0px',
                                  'options'=>$psid,
                                  'empty'=>'',
                                  'required',
                                  'default'=>$selected,
                                  'onChange'=>'getStationsName(this.value)',
                                  'readonly',
                                  'disabled'
                                ));
                           ?>
                        </div>
                        </div>
                      </div>
                      </div>
                       <div class="row-fluid">
                      <div class="span6">  
                    <div class="control-group">
                      <label class="control-label">Name of Station<?php echo MANDATORY; ?></label>
                          <div class="controls">
                            <?php
                                echo $this->Form->input('station_name',array(
                                  'div'=>false,
                                   'type'=>'text',
                                  'label'=>false,
                                  'class'=>'span11',
                                  'placeholder'=>'Name of Station',
                                  'required',
                                  'readonly'
                                ));
                             ?>
                          </div> 
                      </div>
                      </div>

                      <div class="span6">  
                    <div class="control-group">
                        <label class="control-label">State Of Prisoners<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('prisnors_state',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alphanumericone',
                                'type'=>'textarea',
                                'required'=>'required',
                                'rows'=>3,
                                'placeholder'=>'Please Enter State Of Prisoners'
                              ));
                           ?>
                        </div>
                      </div>
                      </div>
                      </div>

                      <div class="row-fluid">
                      <div class="span6">  
                      <div class="control-group">
                        <label class="control-label">State Of Prison<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('prisons_state',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alphanumericone',
                                'type'=>'textarea',
                                'required'=>'required',
                                'rows'=>3,
                                'placeholder'=>'Please Enter State Of Prison'
                              ));
                           ?>
                        </div>
                      </div>
                      </div>

                      <div class="span6">  
                    <div class="control-group">
                        <label class="control-label">Remark :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('remark',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alphanumericone',
                                'type'=>'textarea',
                                'required'=>'false',
                                'rows'=>3,
                                'placeholder'=>'Please Enter Remark'
                              ));
                           ?>
                        </div>
                      </div>
                      </div>
                      </div>

                      <div class="row-fluid">
                      <div class="span6">  
                      <div class="control-group">
                        <label class="control-label">Duty Officer<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                          	$selected1='';
                          	if($user_id!=''){
                          		$selected1=$user_id;
                          		echo $this->Form->input('dutyofficer_id',array('type'=>'hidden','value'=>$user_id));
                          	}
                                echo $this->Form->input('dutyofficer_id1',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11',
                                  'type'=>'text',
                                  'value'=>$this->Session->read('Auth.User.name'),
                                  //'options'=>$duty_officer,
                                  //'empty'=>'-- Select Prison--',
                                  'required',
                                  //'default'=>$selected1,
                                  'readonly',
                                ));
                             ?>
                        </div>
                      </div>
                      </div>
                      <div class="span6">
	                        <div class="control-group">
	                            <label class="control-label">
	                                Upload 
	                                <!-- <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,gif) type photo!" id='example'></i> -->
	                                :
	                            </label>
	                            <div class="controls">
	                                <div>
	                                    <?php if(isset($this->request->data["Stationjournal"]["upload"]))
	                                    {?>
	                                        <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/stationjournal/<?php echo $this->request->data["Stationjournal"]["photo"];?>" data-lightbox="example-set"><img src="<?php echo $this->webroot; ?>app/webroot/files/stationjournal/<?php echo $this->request->data["Stationjournal"]["upload"];?>" alt="" width="100px" height="100px"></a>
	                                    <?php }?>
	                                </div>
	                                <?php echo $this->Form->input('upload',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'upload', 'required'=>false));?>
	                            </div>
	                        </div>
	                    </div>
                      </div>
                      
                            
              
              <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success" id="submit">Save</button>
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
  
$(document).ready(function(){
	if($('#StationjournalPrisonId').val()!=''){
		getStationsName($('#StationjournalPrisonId').val());
	}
});

  // $('#StationjournalStationName').keyup(function()
  // {
  //   var your = $(this).val();
  //   re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
  //   var isSpl = re.test(your);
  //   if(isSpl)
  //   {
  //     var no_spl = your.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
  //     $(this).val(no_spl);
  //   }
  // });
$(function(){
    $("#StationjournalAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Stationjournal][journal_date]': {
                    required: true,
                },
                'data[Stationjournal][prison_id]': {
                    required: true,
                },
                'data[Stationjournal][prisnors_state]': {
                    required: true,
                },
                'data[Stationjournal][prisons_state]': {
                    required: true,
                },
                'data[Stationjournal][station_name]': {
                    required: true,
                },
                'data[Stationjournal][dutyofficer_id]': {
                    required: true,
                },
            },
            messages: {
                'data[Stationjournal][journal_date]': {
                    required: "Please choose date.",
                },
                'data[Stationjournal][prison_id]': {
                    required: "Please select prison station id.",
                },
                'data[Stationjournal][prisnors_state]': {
                    required: "Please enter state of prisoners.",
                },
                'data[Stationjournal][prisons_state]': {
                    required: "Please enter state of prison.",
                },
                'data[Stationjournal][station_name]': {
                    required: "Please enter name of station ",
                },
                'data[Stationjournal][dutyofficer_id]': {
                    required: "Please select duty officer name.",
                },
               
        
            },
               
    });
  });

function getStationsName(id) 
{ 
    if(id != '')
    {
        var strURL = '<?php echo $this->Html->url(array("controller"=>"stationjournals","action"=>"getStationsName"));?>';
        $.post(strURL,{"id":id},function(data){  
            
            if(data) { 
                //var obj = jQuery.parseJSON(data);
                $('#StationjournalStationName').val(data);
            }
        });
    }
}

$('#submit').click(function(){
        if($("#StationjournalAddForm").valid()){
            if( !confirm('Are you sure to save?')) {
                return false;
            }
        }
    });
</script>