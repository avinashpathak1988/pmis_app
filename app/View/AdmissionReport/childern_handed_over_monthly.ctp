<style>
.row-fluid [class*="span"]{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">

    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> 
                    <?php echo $this->element('reportheader'); ?>
                    <div style="float:right;padding-top:2px;">
                       
                        &nbsp;&nbsp;
                    </div>
                </div>
                 <div class="clearfix"></div> 
                <div class="widget-content nopadding">
                        <?php echo $this->element('report-search');?>   
                           
                    <div class="table-responsive" id="listingDiv" style="overflow-x: scroll;">

                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'AdmissionReport','action'=>'monthlyChildrenDueAjax'));
$ajaxDistrictUrl        = $this->Html->url(array('controller'=>'AdmissionReport','action'=>'getDistrictList'));
$ajaxPrisonUrl        = $this->Html->url(array('controller'=>'AdmissionReport','action'=>'getPrisonList'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();

        $('#state_id').on('change', function(e){
                var url = '".$ajaxDistrictUrl."';
                $.post(url, {'state_id':$('#state_id').val()}, function(res){
                    $('#district_id').html(res);               
                });
        });

         $('#district_id').on('change', function(e){
                var url = '".$ajaxPrisonUrl."';
                $.post(url, {'district_id':$('#district_id').val()}, function(res){
                    $('#prison_id').html(res);               
                });
        });

    });    
      function resetData(){

       
        $('select').select2('val', '');
        showData();
        
    }
   function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/prison_id:'+$('#prison_id').val();
        url = url + '/state_id:'+$('#state_id').val();
        url = url + '/ups_district_id:'+$('#ups_district_id').val();
        url = url + '/geographical_id:'+$('#geo_district_id').val();
        url = url + '/from_date:'+$('#from_date').val();
        url = url + '/to_date:'+$('#to_date').val();
        url = url + '/selected_month_id:'+$('#selected_month_id').val();
        url = url + '/selected_year_id:'+$('#selected_year_id').val();

        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });
    }



",array('inline'=>false));
?>