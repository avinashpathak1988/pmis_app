<?php
if(isset($this->data['DeathInCustody']['date_of_death']) && $this->data['DeathInCustody']['date_of_death'] != ''){
    $this->request->data['DeathInCustody']['date_of_death'] = date('d-m-Y', strtotime($this->data['DeathInCustody']['date_of_death']));
}
?>
<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Death in custody Records</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('controller'=>'prisoners', 'action'=>'details',$uuid),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php echo $this->Form->create('DeathInCustody',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                        <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date of Death <?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('date_of_death',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Date of Death','readonly'=>'readonly','class'=>'form-control mydate','required', 'id'=>'date_of_death'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Place Of Death :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('place_of_death',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control','required', 'id'=>'place_of_death'));?>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Pathologist Signeture :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('pathologist_sign',array('type'=>'file', 'div'=>false,'label'=>false,'class'=>'form-control','required'));?>
                                    </div>
                                </div>
                            </div>                     
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Cause Of Death :</label>
                                    <div class="controls">
                                       <?php echo $this->Form->textarea('cause_death',array('div'=>false,'label'=>false,'class'=>'form-control','required', 'id'=>'cause_death'));?>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Medical Officer  :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('medical_officer_id',array('type'=>'select', 'div'=>false,'label'=>false,'type'=>'select','empty'=>'--Select Medical Officer--','options'=>$medicalOfficers,'class'=>'form-control','required', 'id'=>'medicalOfficers'));?>
                                    </div>
                                </div>
                            </div> 
                            <div class="clearfix"></div> 
                        </div>
                        <div class="form-actions" align="center">
                            <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
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
<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'discharges','action'=>'DeathInCustodyAjax'));

echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
        
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        var uuid  = '".$uuid."';
        url = url + '/date_of_death:'+$('#date_of_death').val();
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }

    

",array('inline'=>false));
?>