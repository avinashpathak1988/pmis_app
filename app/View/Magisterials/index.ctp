<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Jurisdiction Area List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Add New Jurisdiction',array('action'=>'add'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Name :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name', array('type'=>'text', 'id'=>'name', 'class'=>'span11','div'=>false,'label'=>false,'placeholder'=>'Enter Magisterial Area Name','required'))?>
                                </div>
                            </div>
                        </div>
                          

                    </div>           
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button', 'class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>"javascript:return showData();"))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
                    </div>
                    <?php echo $this->Form->end();?> 
                     <div class="widget-content">
                        <div class="table-responsive" id="listingDiv">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'Magisterials','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/name:'+$('#name').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }
",array('inline'=>false));
?> 