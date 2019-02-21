<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Approve  Working Party Rejection</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <div id="listingDiv"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl    = $this->Html->url(array('controller'=>'Earnings','action'=>'workingPartyRejectList'));
echo $this->Html->scriptBlock("
    
    function showWorkingPartyTransferList()
    {
        var url = '".$ajaxUrl."';

        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }

    window.onload = function() {
      showWorkingPartyTransferList();
    }
       
",array('inline'=>false));
?>