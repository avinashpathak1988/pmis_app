<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Incident Management List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add Incident'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
<div class="widget-content nopadding">
    <?php echo $this->Form->create('Incident Management',array('class'=>'form-horizontal'));?>
    <div class="row" style="padding-bottom: 14px;">
         <div class="span6">
            <div class="control-group">
                <label class="control-label">Incident Type :</label>
                <div class="controls">
                    <?php   $options = array('Attempted Escape'=>'Attempted Escape','Attempted Suicide'=>'Attempted Suicide','Strike'=>'Strike','Others'=>'Others'); ?>
                    <?php echo $this->Form->input('incident_type', array('type'=>'select','class'=>'form-control pmis_select','id'=>'incident_type','options'=> $options,'empty'=>'--All--','div'=>false,'label'=>false))?>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Incident Name :</label>
                <div class="controls">
                    <?php echo $this->Form->input('incident_name', array('type'=>'text','class'=>'form-control','id'=>'incident_name','placeholder'=>'Enter Incident','div'=>false,'label'=>false))?>
                </div>
            </div>
        </div>
    </div>
        <div class="row" style="padding-bottom: 14px;">
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Prisoner Number :</label>
                <div class="controls">
                    <?php echo $this->Form->input('prisoner_no', array('type'=>'select','class'=>'form-control pmis_select','options'=>$prisonerList,'multiple'=>true,'id'=>'prisoner_no','empty'=>'-- Select Prisoner Number --','div'=>false,'label'=>false,'hiddenField'=>false))?>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
            <label class="control-label">Prisoner Name:</label>
            <div class="controls">
                <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$PrisonerList, 'empty'=>'','required','id'=>'prisoner_name','hiddenField'=>false,));?>
            </div>
        </div>
        </div>
        <div class="row" style="padding-bottom: 14px;">
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Remarks :</label>
                <div class="controls">
                    <?php echo $this->Form->input('remarks', array('type'=>'textarea','class'=>'form-control','id'=>'remarks','div'=>false,'label'=>false))?>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Officer Present :</label>
                <div class="controls">
                    <?php echo $this->Form->input('officer_present', array('type'=>'text','class'=>'form-control','id'=>'officer_present','div'=>false,'label'=>false))?>
                </div>
            </div>
        </div> 
        </div>
        <div class="row" style="padding-bottom: 14px;">
            <div class="span6">
            <div class="control-group">
                <label class="control-label">Date :</label>
                <div class="controls">
                    <?php echo $this->Form->input('date', array('type'=>'text','class'=>'form-control','id'=>'date','div'=>false,'label'=>false))?>
                </div>
            </div>
        </div>     
        </div>       
        <div class="form-actions" align="center">
            <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
            <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
        </div>
        <?php echo $this->Form->end();?> 
        <div class="table-responsive" id="listingDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'IncidentManagements','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/ward_id:' + $('#ward_id').val();
        url = url + '/prison_id:' + $('#prison_id').val();
        url = url + '/cell_name:' + $('#cell_name').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }
    function confirmdelete(){
        var c=confirm('Are you sure to delete ?');
        if(c == false){
            return false;
        }else{
            return true;
        }
    }
",array('inline'=>false));
?>  
<script>
    function showWard(id)
{
    // alert(1);
    var strURL = '<?php echo $this->Html->url(array('controller'=>'IncidentManagements','action'=>'stationList'));?>';
    $.post(strURL,{"prison_id":id},function(data){  
        
        if(data) { 
            $('#ward_id').html(data); 
            if(id == 1)
            {
                $('#ward_id').val(1);
            }
            else 
            {
                $('#ward_id').val(0);
            }
            $('#ward_id').select2();
        }
        else
        {
            alert("Error...");  
        }
        
    });
}
</script>











