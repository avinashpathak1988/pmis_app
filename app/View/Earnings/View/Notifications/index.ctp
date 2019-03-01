<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Notifications List</h5>
                    <!-- <div style="float:right;padding-top: 7px;">
                        <?php //echo $this->Html->link(__('Add Notifications'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div> -->
                </div>
<div class="widget-content nopadding">
    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
    <div class="row" style="padding-bottom: 14px;">
        <div class="span6">
         <div class="control-group">
        <label class="control-label">Date :</label>
        <div class="controls phy-prop-dt">
            <?php echo $this->Form->input('created',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'created',"readonly"=>true, ));?>
            To
            <?php echo $this->Form->input('modified',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'modified',"readonly"=>true,));?>
        </div>
    </div>
</div>
           <!--  <div class="control-group">
                <label class="control-label">Ward :</label>
                <div class="controls">
                    <?php //echo $this->Form->input('ward_id', array('type'=>'select','class'=>'form-control','id'=>'ward_id','options'=>$wardList,'empty'=>'--All--','div'=>false,'label'=>false,'onchange'=>''))?>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Cell Name :</label>
                <div class="controls">
                    <?php //echo $this->Form->input('cell_name', array('type'=>'text','class'=>'form-control','id'=>'cell_name','placeholder'=>'Enter Cell Name','div'=>false,'label'=>false))?>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Cell No :</label>
                <div class="controls">
                    <?php //echo $this->Form->input('cell_no', array('type'=>'text','class'=>'form-control','id'=>'cell_no','div'=>false,'label'=>false))?>
                </div>
            </div>
        </div>   -->

        </div>           
        <div class="form-actions" align="center">
            <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
$ajaxUrl = $this->Html->url(array('controller'=>'Notifications','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/created:' + $('#created').val();
        url = url + '/modified:' + $('#modified').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }
",array('inline'=>false));
?>  












