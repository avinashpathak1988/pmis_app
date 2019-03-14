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
$ajaxUrl = $this->Html->url(array('controller'=>'PrisonerReport','action'=>'prisonerAccountReportAjax'));
$getPrisonersAjax = $this->Html->url(array('controller'=>'PrisonerReport','action'=>'getPrisoners'));
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
        url = url + '/gender_id:'+$('#gender_id').val();
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
    $(document).ready(function(){
        $('#prison_id').on('change',function(){
            getPrisoners($(this).val());
        });
    });

    function getPrisoners(prisonId){
   // alert(id);
    var updateElem = 'SearchPrisonerId';
    var url = '<?php echo $getPrisonersAjax; ?>';
    $.post(url, { 'prisonId':prisonId }, function(res) {
                $('#'+updateElem).html(res);
                $('#'+updateElem).select2();
      /*  console.log(res);
            $('#'+updateElem).html('');
             var match = res.split(',');
            var opt = '';
            if(res == 'allowed'){
                opt += '<option value="In Use">In Use</option>';
                opt += '<option value="In Store">In Store</option>';
                 $('#'+updateElem).html(opt);
                $('#'+updateElem).val('In Use');
                $('#'+updateElem).change();
                $('#'+updateElem).removeAttr('readonly');
                $('#'+updateElem).removeAttr('disabled');

            }else if(match[0] == 'prohibited'){
                opt += '<option value="'+match[1]+'">'+match[1]+'</option>';
                $('#'+updateElem).html(opt);
                $('#'+updateElem).val(match[1]);
                $('#'+updateElem).change();
                $('#'+updateElem).attr('readonly','readonly');
                $('#'+updateElem).attr('disabled','disabled');


            }else{
                 $('#'+updateElem).html(opt);
            }
*/
        });
    }
</script>