<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Article/Item List</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Item/Article Price History',array('controller'=>'Earnings','action'=>'itemPriceHistory'),array('escape'=>false,'class'=>'btn btn-warning btn-mini')); 
                        echo $this->Html->link('Add Item/Article',array('controller'=>'Earnings','action'=>'createarticle'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
                                  ?>
                                <div class="row-fluid" style="padding-bottom: 14px;">
                                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE'))
                                    {?> 
                                        <div class="span6">
                                          <div class="control-group">
                                                <label class="control-label">Prison Station:</label>
                                                <div class="controls">
                                                    <?php 
                                                    echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonList, 'empty'=>'-- Select Prison Station --','required','id'=>'prison_id'));?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Name Of Item:</label>
                                            <div class="controls">
                                                <?php 
                                                echo $this->Form->input('name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Name of Item','id'=>'name','maxlength'=>20));?>
                                            </div>
                                        </div>
                                    </div>
                                    </div> 
                                </div>

                              <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','onclick'=>"showDataItem();"))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'button', 'class'=>'btn btn-gray','div'=>false,'label'=>false,'onclick'=>"resetSearchForm();"));?>
                        </div>
                                <?php echo $this->Form->end();?>
                            <div id="item_listview"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$itemUrl = $this->Html->url(array('controller'=>'earnings','action'=>'itemAjax'));
$deleteItemUrl = $this->Html->url(array('controller'=>'earnings','action'=>'deleteItem'));
echo $this->Html->scriptBlock("
   
    jQuery(function($) {
         showDataItem();
    }); 

    function resetSearchForm()
    {
        var url = '".$itemUrl."';
        $('#name').val('');
        if($('#prison_id').length > 0)
            $('#prison_id').val('');
        $.post(url, {}, function(res) {
            if (res) {
                $('#item_listview').html(res);
            }
        });
    }
    
    function showDataItem(){
        var url = '".$itemUrl."';
        $.post(url, $('#SearchItemListForm').serialize(), function(res) {
            if (res) {
                $('#item_listview').html(res);
            }
        });
    }

    //delete working party 
    function deleteItem(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteItemUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 1){
                        showDataItem();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

",array('inline'=>false));
?>