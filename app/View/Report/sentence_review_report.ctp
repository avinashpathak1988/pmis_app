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
                <div class="widget-title"> <!-- <span class="icon"> <i class="icon-align-justify"></i> </span> -->
                    <?php echo $this->element('reportheader'); ?>
                    <div style="float:right;padding-top:2px;">
                        <?php echo $this->Html->link('Back',array('action'=>'index'),array('class' => 'btn btn-primary'));?>
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
$ajaxUrl        	  = $this->Html->url(array('controller'=>'report','action'=>'sentenceReviewReportAjax'));


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