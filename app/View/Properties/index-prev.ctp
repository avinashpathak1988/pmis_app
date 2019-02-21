<?php
if(isset($this->data['Property']['property_date']) && $this->data['Property']['property_date'] != ''){
    $this->request->data['Property']['property_date'] = date('d-m-Y', strtotime($this->data['Property']['property_date']));
}
?>
<div class="container-fluid">
    <div class="row-fluid">
    <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Property List </h5>
                </div>
                <div class="widget-content nopadding">
                <!--Add New Records-->
                <div class="span12">
                        <?php echo $this->Form->create('Property',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                        <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Property Name<?php echo $req; ?>  :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('propertyitem_id',array('div'=>false,'label'=>false,'type'=>'select','options'=>$propertyitem,'empty'=>'-- Select Property Name --','placeholder'=>'Enter Property Name','class'=>'form-control property_name span11','required', 'id'=>'propertyitem_id'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                    <div class="control-group">
                                    <label class="control-label">Date<?php echo $req; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('property_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date ','required','readonly'=>'readonly','id'=>'property_date'));?>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Property Description :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->textarea('property_description',array('div'=>false,'label'=>false,'placeholder'=>'Enter Property Descrption','class'=>'form-control span11','required'=>false, 'id'=>'property_description'));?>
                                    </div>
                                </div>
                            </div>                        
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Source :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->textarea('source',array('div'=>false,'label'=>false,'class'   => 'form-control span11','type'=>'text','required'=>false, 'id'=>'source'));?>
                                    </div>
                                </div>
                            </div>     
                            <div class="form-actions" align="center">
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->input('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
                                <?php }?>
                                <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
                                <?php }?>
                            </div>
                        </div>

                        <?php echo $this->Form->end();?>
                    </div>
                    <div class="span12">
                        <div class="text-right">
                            
                            <?php if($isAccess == 1){?>
                                <div class="span6">
                                    <?php echo $this->Form->button('Destroy', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myDestroyModal'))?>
                                    <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false,'data-toggle'=>'modal', 'data-target'=>'#myOutgoingModal'))?>
                                    <?php echo $this->Form->button('Final Discharge', array('type'=>'button', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false,'data-toggle'=>'modal', 'data-target'=>'#myDischargeModal'))?>
                                </div>
                            <?php }?>
                            <div class="span6">
                            <?php echo $this->Form->input('status_type', array('type'=>'select', 'div'=>false, 'label'=>false, 'options'=>$statusList, 'default'=>'', 'class'=>'form-control span6', 'id'=>'status_type', 'onchange'=>'javascript:getPropertyList();'))?>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="listingDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
/*
 *Modal box start for destroy property
 */
?>
<div id="myDestroyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">X</button>
                <h4 class="modal-title">Property Destroy</h4>
            </div>
            <div class="modal-body">
                <div class="row-fluid">
                    <div class="span10">
                        <div class="control-group">
                            <label class="control-label">Destroy Date<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('destroy_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Destroy Date','class'=>'form-control mydate span11','required', 'id'=>'destroy_date', 'readonly'=>true));?>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span10">
                        <div class="control-group">
                            <label class="control-label">Destroy Cause<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('destroy_cause',array('div'=>false,'label'=>false,'type'=>'textarea','placeholder'=>'Enter Destroy Cause','class'=>'form-control span11','required', 'id'=>'destroy_cause', 'cols'=>30, 'rows'=>3));?>
                            </div>
                        </div>                        
                    </div>                        
                </div> 
                <div class="form-actions" align="center">
                    <?php echo $this->Form->input('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>'javascript:destData();'))?>
                </div>                               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
/*
 *Modal box end for destroy property
 *Modal box start for outgoing
 */
?>
<div id="myOutgoingModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">X</button>
                <h4 class="modal-title">Property Outgoing</h4>
            </div>
            <div class="modal-body">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Outgoing Date<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('outgoing_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Outgoing Date','class'=>'form-control mydate span11','required', 'id'=>'outgoing_date', 'readonly'=>true));?>
                            </div>
                        </div>                        
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Outgoing Source<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('outgoing_source',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Outgoing source','class'=>'form-control  span11','required'));?>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span10">
                        <div class="control-group">
                            <label class="control-label">Outgoing Cause<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('outgoing_cause',array('div'=>false,'label'=>false,'type'=>'textarea','placeholder'=>'Enter Outgoing Cause','class'=>'form-control span11','required', 'id'=>'outgoing_cause', 'cols'=>30, 'rows'=>3));?>
                            </div>
                        </div>                        
                    </div>                        
                </div>  
                         
                <div class="form-actions" align="center">
                    <?php echo $this->Form->input('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>'javascript:outgoingData();'))?>
                </div>                               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
/*
 *Modal box end for outgoing property
 *Modal box start for final discharge 
 */
?>

<div id="myDischargeModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">X</button>
                <h4 class="modal-title">Property Final Discharge</h4>
            </div>
            <div class="modal-body">
                <div class="row-fluid">
                    <div class="span10">
                        <div class="control-group">
                            <label class="control-label">Final Discharge Date<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('discharge_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Destroy Date','class'=>'form-control mydate span11','required', 'id'=>'discharge_date', 'readonly'=>true));?>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span10">
                        <div class="control-group">
                            <label class="control-label">Final Discharge Description<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('discharge_cause',array('div'=>false,'label'=>false,'type'=>'textarea','placeholder'=>'Enter Final Discharge Description','class'=>'form-control span11','required', 'id'=>'discharge_cause', 'cols'=>30, 'rows'=>3));?>
                            </div>
                        </div>                        
                    </div>                        
                </div> 
                <div class="form-actions" align="center">
                    <?php echo $this->Form->input('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>'javascript:dischargeData();'))?>
                </div>                               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl      = $this->Html->url(array('controller'=>'properties','action'=>'indexAjax'));
$destroyUrl   = $this->Html->url(array('controller'=>'properties','action'=>'destroyAjax'));
$outgoingUrl  = $this->Html->url(array('controller'=>'properties','action'=>'outgoingAjax'));
$dischargeUrl = $this->Html->url(array('controller'=>'properties','action'=>'finaldischargeAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData('Incoming');
    });
    function showData(param){
        var url = '".$ajaxUrl."';
        var uuid = '".$uuid."';
        var prisoner_id = ".$prisoner_id.";
        url = url + '/propertyitem_id:' + $('#propertyitem_id').val();
        url = url + '/property_date:'+$('#property_date').val();
        url = url + '/property_description:' + $('#property_description').val();
        url = url + '/source:' + $('#source').val();
        url = url + '/param:' + param;
        url = url + '/uuid:'  + uuid;
        url = url + '/prisoner_id:'  + prisoner_id;
        $.post(url, {}, function(res) {
           // console.log(res);
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }
    function getPropertyList(){
        showData($('#status_type').val());
    }
    function destData(){
        if($('#destroy_date').val() == ''){
            alert('Please enter destroy date');
            $('#destroy_date').focus();
        }else if($('#destroy_cause').val() == ''){
            alert('Please enter destroy cause');
            $('#destroy_cause').focus();
        }else if(jQuery('input[type=checkbox]:checked').length == 0) { 
            alert('Please check the boxes to Destroy');
        }else{
            if(confirm('Are you sure?')){
                var prisoner_id = ".$prisoner_id.";
                var dest_arr = '';
                $('.propertycheckclass').each(function(){
                    if($(this).is(':checked')){
                        if(dest_arr != ''){
                            dest_arr = dest_arr +','+$(this).val(); 
                        }else{
                            dest_arr = $(this).val();
                        }
                    }
                });
                var url = '".$destroyUrl."';
                $.post(url, {'destdata':dest_arr, 'destroy_date':$('#destroy_date').val(), 'destroy_cause':$('#destroy_cause').val(), 'prisoner_id':prisoner_id}, function(res) {
                    if(res==1){
                        showData('Incoming');
                        $('#destroy_date').val('');
                        $('#destroy_cause').val('');
                        $('.modal').hide();
                        $('.modal-backdrop').hide();                        
                    }
                });   
            }             
        }
    } 
    function outgoingData(){
        if($('#outgoing_date').val() == ''){
            alert('Please enter outgoing date');
            $('#outgoing_date').focus();
        }else if($('#outgoing_cause').val() == ''){
            alert('Please enter outgoing cause');
            $('#outgoing_cause').focus();
        }else if($('#outgoing_source').val() == ''){
            alert('Please enter outgoing source');
            $('#outgoing_source').focus();
        }else if(jQuery('input[type=checkbox]:checked').length == 0) { 
            alert('Please check the boxes for outgoing property');
        }else{
            if(confirm('Are you sure?')){

                var prisoner_id = ".$prisoner_id.";
                var dest_arr = '';
                $('.propertycheckclass').each(function(){
                    if($(this).is(':checked')){
                        if(dest_arr != ''){
                            outgoing_arr = outgoing_arr +','+$(this).val(); 
                        }else{
                           outgoing_arr = $(this).val();
                        }
                    }
                });
                
                var url = '".$outgoingUrl."';
               
                $.post(url, {'outgoingdata':outgoing_arr, 'outgoing_date':$('#outgoing_date').val(), 'outgoing_cause':$('#outgoing_cause').val(),'outgoing_source':$('#outgoing_source').val(), 'prisoner_id':prisoner_id}, function(res) {
                    
                    if(res==1){
                        //console.log(res);
                        showData('Incoming');
                        $('#outgoing_date').val('');
                        $('#outgoing_cause').val('');
                        $('.modal').hide();
                        $('.modal-backdrop').hide();                        
                    }
                });   
            }             
        }
    } 
    function dischargeData(){
        if($('#discharge_date').val() == ''){

            alert('Please enter final discharge date');
            $('#discharge_date').focus();
        }else if($('#discharge_cause').val() == ''){
            alert('Please enter discharge description');
            $('#discharge_cause').focus();
        }else if(jQuery('input[type=checkbox]:checked').length == 0) { 
            alert('Please check the boxes for final discharge property');
        }else{
            if(confirm('Are you sure?')){

                var prisoner_id = ".$prisoner_id.";
                var discharge_arr = '';
                $('.propertycheckclass').each(function(){
                    if($(this).is(':checked')){
                        if(discharge_arr != ''){
                            discharge_arr =discharge_arr +','+$(this).val(); 
                        }else{
                           discharge_arr = $(this).val();
                        }
                    }
                });
               
                var url = '".$dischargeUrl."';
               
                $.post(url, {'dischargedata':discharge_arr, 'discharge_date':$('#discharge_date').val(), 'discharge_cause':$('#discharge_cause').val(),'prisoner_id':prisoner_id}, function(res) {
                    //console.log(res);
                    if(res==1){
                        
                        showData('Incoming');
                        $('#discharge_date').val('');
                        $('#discharge_cause').val('');
                        $('.modal').hide();
                        $('.modal-backdrop').hide();                        
                    }
                });   
            }             
        }
    }  
",array('inline'=>false));
?>  
<?php

echo $this->Html->scriptBlock("
   
    
",array('inline'=>false));
?> 

<?php
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$ajaxUrl      = $this->Html->url(array('controller'=>'properties','action'=>'finalAjax'));
echo $this->Html->scriptBlock("
   
   jQuery(function($) {

        showCommonHeader();

    });

    function finalData(){
             if(jQuery('input[type=checkbox]:checked').length >0) { 
            var r=confirm('Are you sure?');
            if(r)
            {
                var final_arr=[];
                $(\":checked\").each(function(){
                    final_arr.push($(this).val());
                });
                
                var final_data=JSON.stringify(final_arr);
                var url = '".$ajaxUrl."';
                url = url + '/finaldata:' + final_data;
                
             
                $.post(url, {}, function(res) {
                    if (res) {
                        
                        if(res==1)
                        {
                          $(\":checked\").each(function(){
                             $(this).closest('tr').remove();
                            });
                        }
                    
                     }
                 });   
          } 
      } 
      else{
        alert('Please check the boxes to discharge the property to prisoner');
      }
    }

    //common header
    function showCommonHeader(){
        var prisoner_id = ".$prisoner_id.";;
        console.log(prisoner_id);  
        var uuid        = '".$uuid."';
        var url         = '".$commonHeaderUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
           
            if (res) {
                $('#commonheader').html(res);
            }
        }); 
    }

",array('inline'=>false));
?> 