<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Visitors Blacklisted List</h5>
                </div>
            </div>

            <div class="widget-content nopadding">
                  <div class="table-responsive" id="listingDiv">
            </div>

        </div>
    </div>
</div>
<?php 
$ajaxUrl      = $this->Html->url(array('controller'=>'BlacklistVisitor','action'=>'indexAjax'));

echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });

function showData(){
        var url = '".$ajaxUrl."';
        
        $.post(url, {}, function(res) {
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
            }
        });    
    }

   ",array('inline'=>false));
?>  