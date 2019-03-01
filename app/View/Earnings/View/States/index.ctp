<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Region List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add New Region'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Geographical Region :</label>
                                <div class="controls">
                                     <?php
                                    echo $this->Form->input('geographical_region_id',array(
                                   'type'=>'select',
                                   'div'=>false,
                                   'label'=>false,
                                   'id'=>'geographical_region_id',
                                   'options'=>$geographical,
                                   'empty'=>'',
                                   'class'=>'span11 pmis_select',
                                   //'onchange'=>'javascript:showData(this.value);',
                                   'required'
                                 ));
                                 ?>

                                </div>
                            </div>
                        </div>  
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Region :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('statename', array('type'=>'text','class'=>'form-control','id'=>'statename','div'=>false,'label'=>false,'placeholder'=>'Enter Region'))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'states','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/statename:' + $('#statename').val();
        url = url + '/geographical_region_id:' + $('#geographical_region_id').val();
        $.post(url, {}, function(res) {          
             $('#listingDiv').html(res);
            
        });    
    }
",array('inline'=>false));
?>  












