<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Offence List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add New Offence'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Offence :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('offencename', array('type'=>'text','class'=>'form-control','id'=>'offencename','div'=>false,'label'=>false,'placeholder'=>'Enter Offence'))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'offence','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/offencename:' + $('#offencename').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }
",array('inline'=>false));
?>  












