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
                    <h5> Article/Item Price History</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Item/Article Price List',array('controller'=>'Earnings','action'=>'createarticle'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        
                                
                                    
                           
                             <div id="item_listview"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$itemHistoryUrl = $this->Html->url(array('controller'=>'earnings','action'=>'itemPriceHistoryAjax'));
$deleteItemUrl = $this->Html->url(array('controller'=>'earnings','action'=>'deleteItem'));
echo $this->Html->scriptBlock("
   
    jQuery(function($) {
         showDataItem();
    }); 
    
    function showDataItem(){
        var url = '".$itemHistoryUrl."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#item_listview').html(res);
            }
        });
    }

    

",array('inline'=>false));
?>