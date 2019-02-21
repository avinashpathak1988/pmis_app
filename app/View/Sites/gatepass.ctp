
<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Gatepass List</h5>                   
                </div>
                <div class="widget-content nopadding">
                    <div class="table-responsive" id="listingDiv">

                    </div>         
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$biometricSearchAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'getLastPunch'));
$ajaxUrl        = $this->Html->url(array('controller'=>'Sites','action'=>'gatepassAjax'));
echo $this->Html->scriptBlock("
    function showData(){
        var url   = '".$ajaxUrl."';
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);            
        });           
    }

    function checkData(val,id,type){
        var url = '".$biometricSearchAjax."/'+val+'/'+id+'/'+type;
        $.ajax({
            type: 'POST',
            url: url,
            success: function (res) {

                if(res.trim()!='FAIL'){
                    $('#link_biometric_'+type+id).val(res.trim());
                    $('#link_biometric_span_'+type+id).html(res.trim());
                    $('#link_biometric_button_'+type+id).hide();

                }else{
                    alert('Please press figure on biometric');
                }  
            },
            async:false
        });
    }
",array('inline'=>false));
?>
<script type="text/javascript">
 $(document).ready(function(){
    showData();
    });
    
</script>