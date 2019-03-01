<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<?php
if(isset($this->data['PhysicalLockup']['lock_date']) && $this->data['PhysicalLockup']['lock_date'] != ''){
    $this->request->data['PhysicalLockup']['lock_date'] = date('d-m-Y', strtotime($this->data['PhysicalLockup']['lock_date']));
}

?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Physical Lockup Records</h5>
                    <a class="toggleBtn" href="#searchPhysicalLucup" data-toggle="collapse"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){?>
                        <div style="float:right;padding-top: 7px;">
                            <?php echo $this->Html->link('Add Physical Lockup','#addPhysicalLuckup',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse",'id'=>"physical_luckup")); ?>
                        </div>
                    <?php }?>



                      <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('View Report'), array('action' => 'lockupReport'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div id="addPhysicalLuckup" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;"<?php }?>>
                                <?php echo $this->Form->create('PhysicalLockup',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <?php echo $this->Form->input('id',array("type"=>"hidden"))?>
                                <?php echo $this->Form->input('uuid',array('type'=>'hidden'))?>
                                <div class="row phy-lockup">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Type<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_type_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonerTypeList,'empty'=>'',));?>
                                            </div>
                                        </div>
                                    </div>
                                      
                                    <div class="span6">
                                        <div class="control-group">
                                           <label class="control-label">Lockup Type<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('lockup_type_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$lockupTypeList,'empty'=>'' ,'onchange'=>'getLockupList(this.value)'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">


                                            <label class="control-label">Date<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('lock_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter lock date', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control','type'=>'text','required','value'=>date('d-m-Y')));//maxCurrentDate?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                    
                                        <div class="control-group">
                                            <label class="control-label">Male <?php echo $req; ?>:</label>
                                            <div class="controls">
                                                 <?php echo $this->Form->input('no_of_male',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter no.of males','class'=>'form-control numeric','type'=>'text','required'));?>
                                            </div>
                                        </div>
                                   
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                     
                                        <div class="control-group">
                                            <label class="control-label"> Female <?php echo $req; ?> :</label>
                                            <div class="controls">
                                                 <?php echo $this->Form->input('no_of_female',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter no.of females','class'=>'form-control numeric','type'=>'text','required'));?>
                                            </div>
                                        </div>
                                   
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                           <label class="control-label">Lockup<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php 
                                                /*if(strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 08:00:00")) && strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 11:00:00"))){
                                                $lockuplist=array('Expected'=>'Expected','Unlock'=>'Unlock');
                                                }else{
                                                    $lockuplist=array('Expected'=>'Expected','Lockup'=>'Lockup');
                                                }*/
                                                echo $this->Form->input('lockup',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','empty'=>'---Select Lockup---'));?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if(isset($this->data['PhysicalLockup']['id'])) { ?>
                                <div class="form-actions" align="center">
                                <button type="submit" class="btn btn-success" id="updateIdJournal">Update</button>
                                <?php echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-danger ')); ?>
                               </div>
                                <?php } else{?>
                               <div class="form-actions" align="center">
                                    <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
                              </div>
                              <?php } ?>
                                <?php echo $this->Form->end();?>     
                        </div>

                        <div class="collapse <?php if($isEdit == 0){ echo "in";}?>" id="searchPhysicalLucup"  <?php if($isEdit == 1){?> style="height: 0px;"<?php }?>>
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row phy-lock-ty">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Type</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_type_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$prisonerTypeList,'empty'=>'---Select Prisoner Type---','id'=>'prioner_type_d_search'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('folow_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'folow_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('folow_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'folow_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Lockup Type</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('lockup_type_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$lockupTypeList,'empty'=>'---Select Lockup Type---','id'=>'lock_type_searchs'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Status</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'status','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                                
                            </div>
                    <?php echo $this->Form->end();?>
                     
                                <div class="table-responsive" id="listingDiv">

                        </div>
                                </div>
                          
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">

 $(function(){
    $("#PhysicalLockupIndexForm").validate({
     
      ignore: "",
            rules: {  
               'data[PhysicalLockup][prisoner_type_id]': {
                    required: true,
                },
                'data[PhysicalLockup][lockup_type_id]': {
                    required: true,
                },
                'data[PhysicalLockup][lock_date]' : {
                    required: true,
                },
                'data[PhysicalLockup][no_of_male]' : {
                    required: true,
                },
                'data[PhysicalLockup][no_of_female]' : {
                     required: true,
                 },
           },

            messages: {
                'data[PhysicalLockup][prisoner_type_id]': {
                    required: "Please enter prisoner type.",
                },
                'data[PhysicalLockup][lockup_type_id]': {
                   required: "Please enter lockup type.", 
                },
                'data[PhysicalLockup][lock_date]':{
                   required: "Please enter lock date.",
                },
               'data[PhysicalLockup][no_of_male]' :{
                 required: "Please enter no of male.",
               },
               'data[PhysicalLockup][no_of_female]' : {
                required: "Please enter no of female.",
                },
            },
               
    });
    
    $('#submit').click(function(){
        if($("#PhysicalLockupIndexForm").valid()){
            if( !confirm('Are you sure to save?')) {
                return false;
            }
        }
    });

    function validateForm(){
    var errcount = 0;
    $('.validate').each(function(){
        if($(this).val() == ''){
            errcount++;
            $(this).addClass('error-text');
            $(this).removeClass('success-text'); 
        }else{
            $(this).removeClass('error-text');
            $(this).addClass('success-text'); 
        }        
    });        
    if(errcount == 0){            
        if(confirm('Are you sure want to save?')){  
            return true;            
        }else{               
            return false;           
        }        
    }else{   
        return false;
    }  
 }
 $(document).on('click',"#btnsearchcash", function () { // button name
        showData();
  });
 }); 
 function getLockupList(id){
    lockupOption='';
    if(id==1){
        lockupOption = '<option value="">-- Select Lockup--</option><option value="Expected">Expected</option><option value="Unlock">Unlock</option>';
    }else{
       lockupOption = '<option value="">-- Select Lockup--</option><option value="Expected">Expected</option><option value="Unlock">Lockup</option>';
    }
    $('#PhysicalLockupLockup').html(lockupOption);
 }
</script>

<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'physicallockups','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        $('#prioner_type_d_search').select2('val','');
        $('#lock_type_searchs').select2('val','');
        $('#status').select2('val','".$default_status."');
        showData();
         $('.toggleBtn').click(function(){
            $('.collapse.in').css('height','0');
            $('.collapse.in').removeClass('in');
         });
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/folow_from:'+$('#folow_from').val();
        url = url + '/folow_to:'+$('#folow_to').val();
        url = url + '/prioner_type_d_search:'+$('#prioner_type_d_search').val();
        url = url + '/lock_type_searchs:'+$('#lock_type_searchs').val();
        url = url + '/status:'+$('#status').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
            var usertype_id='".$this->Session->read('Auth.User.usertype_id')."';
                var user_typercpt='".Configure::read('RECEPTIONIST_USERTYPE')."';
                var user_typepoi='".Configure::read('PRINCIPALOFFICER_USERTYPE')."';
                var user_typeoiu='".Configure::read('OFFICERINCHARGE_USERTYPE')."';
             
                 if(usertype_id==user_typercpt)
                 {
                    if($('#status').val()=='Saved' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typepoi)
                 {
                    if($('#status').val()=='Reviewed' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typeoiu)
                 {
                    if($('#status').val()=='Approved'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
        });           
    }
",array('inline'=>false));
?>

