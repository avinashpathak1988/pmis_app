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
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <?php echo $this->element('reportheader'); ?>
                    
                    <div style="float:right;padding-top:2px;">
                        <?php //echo $this->Html->link('Back',array('action'=>'index'),array('class' => 'btn btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->element('report-search');?>                       
                   
                    <div class="table-responsive" id="listingDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'PrisonerReport','action'=>'maritalStatusReportAjax'));


echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
            $('#listingDiv').html('Loading....');
            $('#btnsearchReport').attr('disabled','disabled');
        url = url + '/geographical_region_id:'+$('#geographical_region_id').val();
        url = url + '/prison_id:'+$('#prison_id').val();
        url = url + '/state_id:'+$('#state_id').val();
        url = url + '/district_id:'+$('#district_id').val();
        url = url + '/geographical_id:'+$('#geographical_id').val();
        url = url + '/marital_status_id:'+$('#marital_status_id').val();
        url = url + '/from_date:'+$('#from_date').val();
        url = url + '/to_date:'+$('#to_date').val();
        url = url + '/selected_month_id:'+$('#selected_month_id').val();
        url = url + '/selected_year_id:'+$('#selected_year_id').val();

        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
            $('#btnsearchReport').removeAttr('disabled');

        });
    }
",array('inline'=>false));
?>
<script type="text/javascript">
    function showGeographical(id)
    {
        var url = '<?php echo $geographicalajaxUrl ?>';
        url = url + '/geographical_region_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#state_id').html(res);
            }
        });
    }
    function showDistrict(id)
    {
        var url = '<?php echo $districtajaxUrl ?>';
        url = url + '/state_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#district_id').html(res);
            }
        });
    }
    function showGeoDistrict(id)
    {
        var url = '<?php echo $geodistrictajaxUrl ?>';
        url = url + '/district_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#geographical_id').html(res);
            }
        });
    }
    function showDistrictPrison(id)
    {
        var url = '<?php echo $getDistrictPrisonajaxUrl ?>';
        url = url + '/district_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#prison_id').html(res);
            }
        });
    }
</script>