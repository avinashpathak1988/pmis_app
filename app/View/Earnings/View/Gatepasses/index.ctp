<?php
if(isset($this->data['Discharge']['date_of_discharge']) && $this->data['Discharge']['date_of_discharge'] != ''){
    $this->request->data['Discharge']['date_of_discharge'] = date('d-m-Y', strtotime($this->data['Discharge']['date_of_discharge']));
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
                    <h5>Gate Pass List</h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php echo $this->Form->create('GatePass',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                        <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                        <?php echo $this->Form->input('is_enable', array('type'=>'hidden','value'=>1))?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Escort <?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('escort',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Escort','class'=>'form-control span11','required', 'id'=>'escort'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Destination <?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('destination',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Escort','class'=>'form-control span11','required', 'id'=>'destination'));?>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('gp_date',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Select Date','readonly'=>'readonly','class'=>'form-control mydate span11','required', 'id'=>'gp_date'));?>
                                    </div>
                                </div>
                            </div>                     
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Purpose <?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('purpose',array('type'=>'textarea', 'div'=>false,'label'=>false,'placeholder'=>'Enter Escort','class'=>'form-control span11','required', 'id'=>'purpose','rows'=>2));?>
                                    </div>
                                </div>
                            </div> 
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
$ajaxUrl        = $this->Html->url(array('controller'=>'gatepasses','action'=>'indexAjax'));
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
        showCommonHeader();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        var uuid  = '".$uuid."';
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
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