<?php
if(isset($this->data['Discharge']['escape_date']) && $this->data['Discharge']['escape_date'] != ''){
    $this->request->data['Discharge']['escape_date'] = date('d-m-Y H:i', strtotime($this->data['Discharge']['escape_date']));
}
if(isset($this->data['Discharge']['bail_date']) && $this->data['Discharge']['bail_date'] != ''){
    $this->request->data['Discharge']['bail_date'] = date('d-m-Y', strtotime($this->data['Discharge']['bail_date']));
}
if(isset($this->data['Discharge']['end_bail_date']) && $this->data['Discharge']['end_bail_date'] != ''){
    $this->request->data['Discharge']['end_bail_date'] = date('d-m-Y', strtotime($this->data['Discharge']['end_bail_date']));
}
if(isset($this->data['Discharge']['execution_date']) && $this->data['Discharge']['execution_date'] != ''){
    $this->request->data['Discharge']['execution_date'] = date('d-m-Y H:i', strtotime($this->data['Discharge']['execution_date']));
}

if(isset($this->data['Discharge']['death_warrant']) && $this->data['Discharge']['death_warrant'] != ''){
    $this->request->data['Discharge']['death_warrant'] = date('d-m-Y H:i', strtotime($this->data['Discharge']['death_warrant']));
}

if(isset($this->data['Discharge']['clearance']) && $this->data['Discharge']['clearance'] != ''){
    $this->request->data['Discharge']['clearance'] = explode(",", $this->data['Discharge']['clearance']);
}
if($discharge_type_id==5){
?>
<!-- this div using for eascape of prisoner -->
<style type="text/css">
    .form-horizontal .controls{margin-left: 0px;}
    .form-horizontal .control-label{width: auto;margin-right: 10px;}
</style>
<table class="table table-responsive table-bordered">
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Date & Time of escape :</label>
                <div class="controls">
                    <?php 
                    echo date("d-m-Y", strtotime($this->data['Discharge']['escape_date']));
                  ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>                    
            <div class="control-group">
                <label class="control-label">Escaped From  :</label>
                <div class="controls">
                    <?php 
                    echo $this->data['Discharge']['escape_from'];
                  ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Total Sentence  :</label>
                <div class="controls">
                    <?php 
                    echo $this->data['Discharge']['total_sentance'];
                  ?>
                   
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Place  :</label>
                <div class="controls">
                    <?php 
                    echo $this->data['Discharge']['place'];
                  ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Manner Of Escape  :</label>
                <div class="controls">
                    <?php 
                    echo $this->data['Discharge']['manner_escape'];
                  ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Person from whose custody escaped  :</label>
                <div class="controls">
                    <?php 
                    $escapeData = array();
                    if(isset($this->data['Discharge']['custody_escaped']) && $this->data['Discharge']['custody_escaped']!=''){
                        foreach (explode(",", $this->data['Discharge']['custody_escaped']) as $custody_escapedkey => $custody_escapedvalue) {
                            $escapeData[] = $funcall->getName($custody_escapedvalue,"User","name");
                        }
                    }
                    echo implode(", ", $escapeData);
                  ?>
            </div>
            </div>
        </td>
    </tr>
     <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Labor employed into at admission  :</label>
                <div class="controls">
                    <?php 
                    echo $this->data['Discharge']['labor_admission'];
                  ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Labour Employed into on escape  :</label>
                <div class="controls">
                    <?php 
                    echo $this->data['Discharge']['labor_escape'];
                  ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="control-group">
                <label class="control-label">Supported Docs  :</label>
                <div class="controls">
                    <?php 
                    if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                       
                        echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                    }                                                      
                    ?>
                </div>
            </div>
        </td>
    </tr>
</table>
<?php
}
/// Female Prisoner with an un-weaned child
if($discharge_type_id==9){
?>
<!-- this div using for eascape of prisoner -->
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Finger Print :</label>
            <div class="controls">
                <?php 
                //echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in'));

                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Signature  :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['signature']) && $this->data['Discharge']['signature']!=''){
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["signature"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));
                }

                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs  :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }                                                       
                ?>
            </div>
        </div>
    </div> 
<?php
}
//Grant of Prerogative of Mercy
if($discharge_type_id==7){
?>
<!-- this div using for eascape of prisoner -->
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Finger Print :</label>
            <div class="controls">
                
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Signature  :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['signature']) && $this->data['Discharge']['signature']!=''){
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["signature"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));
                }

                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs  :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }                                                     
                ?>
            </div>
        </div>
    </div> 
<?php
}
//normal dischagre
if($discharge_type_id==2){
?>
    <!-- this div using for Normal Discharge -->
<table class="table table-responsive table-bordered">
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">EPD :</label>
                <div class="controls">
                    <?php 
                    echo $epd = (isset($prisonerDetails['Prisoner']['epd']) && $prisonerDetails['Prisoner']['epd']!='0000-00-00') ? date("d-m-Y",strtotime($prisonerDetails['Prisoner']['epd'])) : ''; ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">LPD :</label>
                <div class="controls">
                    <?php 
                    echo $lpd = (isset($prisonerDetails['Prisoner']['lpd']) && $prisonerDetails['Prisoner']['lpd']!='0000-00-00') ? date("d-m-Y",strtotime($prisonerDetails['Prisoner']['lpd'])) : ''; ?>
                </div>
            </div>
       </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Remission  :</label>
                <div class="controls">
                    <?php 
                    $lpd = (isset($prisonerDetails['Prisoner']['remission']) && $prisonerDetails['Prisoner']['remission']!='') ? json_decode($prisonerDetails['Prisoner']['remission']) : array(); 
                    $remission = array();
                    if(count($lpd)>0){
                        foreach ($lpd as $key => $value) {
                            $remission[] = $value." ".$key;
                        }
                    }
                    echo implode(", ", $remission);
                    // debug($prisonerDetails['Prisoner']['is_death']);
                    ?>
                </div>
            </div>
       </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label"><?php echo (isset($prisonerDetails['Prisoner']['is_death']) && $prisonerDetails['Prisoner']['is_death']==1) ? 'Destination' : 'Address on release'; ?> :</label>
                <div class="controls">
                    <?php 
                    echo $this->data['Discharge']['address_release'];
                  ?>
                    
                </div>
            </div>
       </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Next Destination <?php echo $req; ?>:</label>
                <div class="controls">
                    <?php 
                    echo $this->data['Discharge']['next_destination'];
                  ?>
                
                </div>
            </div>
       </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Finger Print :</label>
                <div class="controls">
                   
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Supported Docs  :</label>
                <div class="controls">
                    <?php 
                    if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                        echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                    }                                                       
                    ?>
                </div>
            </div>
       </td>
    </tr>
</table>
    
<?php
}
//prisoner death
if($discharge_type_id==3){
?>
    <?php
  

    $medicalDeathDetails = $funcall->getDetails("MedicalDeathRecord",$prisonerDetails['Prisoner']['id']);
    
    // debug($this->request->data);
    ?>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Date & Time of Death :</label>
            <div class="controls">
                <?php 
                echo date("d-m-Y h:i A", strtotime($medicalDeathDetails['MedicalDeathRecord']['check_up_date'])); ?>
            </div>
        </div>
    </div>   
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Place of Death :</label>
            <div class="controls">
                <?php 
               echo $medicalDeathDetails['MedicalDeathRecord']['death_place']; ?>
            </div>
        </div>
    </div>  
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Cause of death :</label>
            <div class="controls">
                <?php 
               echo $medicalDeathDetails['MedicalDeathRecord']['death_cause']; ?>
            </div>
        </div>
    </div>    
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Medical Officer :</label>
            <div class="controls">
                <?php
                echo $funcall->getName($medicalDeathDetails['MedicalDeathRecord']['medical_officer_id_death'],"User","name");
                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Death Certificate :</label>
            <div class="controls">
                <?php 
                echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$medicalDeathDetails['MedicalDeathRecord']['medical_from_attach'], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right')); ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Pathologists Signature  :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['signature']) && $this->data['Discharge']['signature']!=''){
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["signature"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));
                }
                ?>
            </div>
        </div>
    </div>
       <div class="span6">
        <div class="control-group">
            <label class="control-label">Name:</label>
            <div class="controls">
                <?php 
               echo $this->data['Discharge']['prison_death_name']; ?>
            </div>
        </div>
    </div>    
       <div class="span6">
        <div class="control-group">
            <label class="control-label">National Id No :</label>
            <div class="controls">
                <?php 
               echo $this->data['Discharge']['prison_death_id']; ?>
            </div>
        </div>
    </div>    
       <div class="span6">
        <div class="control-group">
            <label class="control-label">Telephone No :</label>
            <div class="controls">
                <?php 
               echo $this->data['Discharge']['prison_death_telephone']; ?>
            </div>
        </div>
    </div>    
       <div class="span6">
        <div class="control-group">
            <label class="control-label">Place Of Residence :</label>
            <div class="controls">
                <?php 
               echo $this->data['Discharge']['prison_death_residence']; ?>
            </div>
        </div>
    </div>  
       <div class="span6">
        <div class="control-group">
            <label class="control-label">District Of Residence :</label>
            <div class="controls">
                <?php 
               echo $this->data['Discharge']['prison_death_district']; ?>
            </div>
        </div>
    </div>      
    <div class="clearfix"></div>
    
<?php
}
//Release Execution
if($discharge_type_id==10){
?>
<!-- this div using for Release Execution -->
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Date & Time of Execution :</label>
            <div class="controls">
                <?php 
                echo date("d-m-Y h:i A",strtotime($this->data['Discharge']['execution_date']));
              ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Issue date of Death Warrant :</label>
            <div class="controls">
                <?php 
                echo date("d-m-Y",strtotime($this->data['Discharge']['death_warrant']));
              ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Approving authority  :</label>
            <div class="controls">
                <?php 
                echo $funcall->getName($this->data['Discharge']['approving_authority'],"User","name");
              ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs  :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }                                                      
                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Cause Of Execution <?php echo $req; ?>:</label>
            <div class="controls">
                <?php 
                echo $this->data['Discharge']['cause_execution'];
              ?>
            </div>
        </div>
    </div> 
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Medical officer's Remark <?php echo $req; ?>:</label>
            <div class="controls">
                <?php 
                echo $this->data['Discharge']['medical_remark'];
              ?>
            </div>
        </div>
    </div> 
<?php
}
//reparted partha
if($discharge_type_id==12){
?>
<!-- this div using for Release Execution -->
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Country:</label>
            <div class="controls">
               <?php 
                echo $funcall->getName($this->data['Discharge']['reparted_country'],"Country","name");
              ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Station Name:</label>
            <div class="controls">
               <?php 
                echo $this->data['Discharge']['reparted_station'];
              ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Finger Print :</label>
            <div class="controls">
                
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs  :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }                                                      
                ?>
            </div>
        </div>
    </div>
<?php
}
//release on bail
if($discharge_type_id==1){
?>
<!-- this div using for Release Execution -->
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Date of Bail :</label>
            <div class="controls">
               <?php 
                echo date("d-m-Y",strtotime($this->data['Discharge']['bail_date']));
              ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">End Date of Bail :</label>
            <div class="controls">
               <?php 
                echo date("d-m-Y",strtotime($this->data['Discharge']['end_bail_date']));
              ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Finger Print :</label>
            <div class="controls">
                
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Supported Docs  :</label>
            <div class="controls">
                <?php 
                if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                    echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                }                                                      
                ?>
            </div>
        </div>
    </div>
<?php
}
//Release on License to be at large
if($discharge_type_id==8){
?>
<!-- this div using for Release Execution -->  
<table class="table table-responsive table-bordered">  
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Finger Print :</label>
                <div class="controls">
                   
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Supported Docs  :</label>
                <div class="controls">
                    <?php 
                    if(isset($this->data['Discharge']['attachment']) && $this->data['Discharge']['attachment']!=''){
                        echo $this->Html->link('View', '../files/prisnors/DISCHARGE/'.$this->data["Discharge"]["attachment"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary pull-right'));

                    }                                                      
                    ?>
                </div>
            </div>
        </td>
    </tr>
<?php
}
if(!in_array($discharge_type_id, array(5))){
?>
<table class="table table-responsive table-bordered">
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label"><?php echo (isset($prisonerDetails['Prisoner']['is_death']) && $prisonerDetails['Prisoner']['is_death']==1) ? 'Destination' : 'Address on release'; ?> :</label>
                <div class="controls">
                    <?php 
                        echo $this->data['Discharge']['address_release'];
                      ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="control-group">
                <label class="control-label">Clearance  :</label>
                <div class="controls uradioBtn">
                    <?php 
                    echo ($this->data['Discharge']['clearance']!='') ? implode(", ", $this->data['Discharge']['clearance']) : '';
                  ?>
                    
                </div>
            </div>
        </td>
    </tr>
</table>
<?php
}
?>
<div class="clearfix"></div>
<script type="text/javascript">
$('.mydatetimepicker1').datetimepicker({
    showMeridian: false,
    defaultTime:false,
    format: 'dd-mm-yyyy hh:ii',
    autoclose:true
}).on('changeDate', function (ev) {
     $(this).datetimepicker('hide');
     $(this).blur();
});

$('.mydate1').datepicker({
    showMeridian: false,
    defaultTime:false,
    format: 'dd-mm-yyyy',
    autoclose:true
}).on('changeDate', function (ev) {
     $(this).datepicker('hide');
     $(this).blur();
});
</script>