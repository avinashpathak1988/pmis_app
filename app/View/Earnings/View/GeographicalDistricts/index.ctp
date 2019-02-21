<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>GeographicalDistricts List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add New Geographical District'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Region :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('state_id', array('type'=>'select','class'=>'span11 pmis_select','id'=>'state_id','options'=>$stateList,'empty'=>'','div'=>false,'label'=>false,'onchange'=>'javascript:showDistrict(this.value);'))?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">UPS Prison District :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('district_id', array('type'=>'select','class'=>'span11 pmis_select','id'=>'district_id','options'=>'','empty'=>'','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Geographical District :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('geodistname', array('type'=>'text','class'=>'form-control','id'=>'geodistname','div'=>false,'label'=>false,'placeholder'=>'Enter Geographical District'))?>
                                </div>
                            </div>
                        </div>  

                    </div>           
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false,'onclick'=>'javascript:showAllData();'))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'GeographicalDistricts','action'=>'indexAjax'));
$districtajacUrl = $this->Html->url(array('controller'=>'GeographicalDistricts','action'=>'getdistrictAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/state_id:' + $('#state_id').val();
        url = url + '/district_id:' + $('#district_id').val();
        url = url + '/geodistname:' + $('#geodistname').val();
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
    function showDistrict(id)
    {
        var url = '".$districtajacUrl."';
        url = url + '/state_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#district_id').html(res);
            }
        });
    }
	function showAllData(){
        var url = '".$ajaxUrl."';
        
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }
",array('inline'=>false));
?>  












