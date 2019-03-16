<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Property Item List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php if(($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') ) || ($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE') ) )
            {?>
                <?php echo $this->Html->link(__('Add New Property Item'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-primary btn-mini')); ?>
           <?php } ?>
                        
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Property Item :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name', array('type'=>'text','class'=>'form-control','id'=>'name','div'=>false,'label'=>false,'placeholder'=>'Enter Property Item'))?>
                                </div>
                            </div>
                        </div>  
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Property Type :</label>
                                <div class="controls">
                                    <?php $typelist = array('allowed'=>'Allowed Items','prohibited'=>'Prohibited Items'); ?>
                                       <?php echo $this->Form->input('property_type',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$typelist, 'empty'=>'','required'=>false,'id'=>'search_property_type'));?>
                                    </div>
                            </div>
                        </div> 
                        <?php  if($this->Session->read('Auth.User.usertype_id') != Configure::read('ADMIN_USERTYPE')){ ?>
                             <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Property Added by :</label>
                                <div class="controls">
                                    <?php $addedByList = array('admin'=>'Admin','receptionist'=>'Receptionist'); ?>
                                       <?php echo $this->Form->input('added_by',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$addedByList, 'empty'=>'','required'=>false,'default'=>$default,'id'=>'property_added_by')); ?>
                                    </div>
                            </div>
                        </div>    
                        <?php }else{ ?>

                        <?php } ?>
                        
                    </div>           
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false, 'onclick'=>'javascript:resetData();'))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'propertyitems','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function resetData()
    {
       // alert('here');
        $('#SearchIndexForm')[0].reset();
        showData();
    }
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/name:' + $('#name').val();
        url = url + '/property_type:' + $('#search_property_type').val();
        url = url + '/added_by:' + $('#property_added_by').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }
",array('inline'=>false));
?>  












