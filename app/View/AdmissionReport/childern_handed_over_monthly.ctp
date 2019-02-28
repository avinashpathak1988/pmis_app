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
                    <h5>Monthly List on children due for Handed Over </h5>
                  
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div class="row" style="padding-bottom: 14px;">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo isset($modelArr['state_id'])?$modelArr['state_id']:'Region'?> </label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('state_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'', 'class'=>'form-control pmis_select', 'id'=>'state_id', 'options'=>$regionList));?>
                                    </div>
                                </div>
                            </div>
                                      
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo isset($modelArr['district_id'])?$modelArr['district_id']:'District'?></label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'', 'class'=>'form-control pmis_select', 'id'=>'district_id', 'options'=>[]));?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo isset($modelArr['prison_id'])?$modelArr['prison_id']:'Prison Station'?></label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'', 'class'=>'form-control pmis_select', 'id'=>'prison_id', 'options'=>[]));?>
                                    </div>
                                </div>
                            </div>                       
                            <div class="clearfix"></div> 
                        </div>

                         <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">From Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('from_date', array('type'=>'text', 'id'=>'from_date', 'class'=>'span11 from_date','readonly'=>'readonly','div'=>false,'label'=>false,'placeholder'=>'Enter from date','required'))?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">To Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('to_date', array('type'=>'text', 'id'=>'to_date', 'class'=>'span11 to_date','div'=>false,'readonly'=>'readonly','label'=>false,'placeholder'=>'Enter to date','required'))?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Month - Year:</label>
                                        <div class="controls">
                                            <?php $monthsList=array(
                                                ''=>'-- Month -- ',
                                                '01'=>'Jan',
                                                '02'=>'Feb',
                                                '03'=>'Mar',
                                                '04'=>'Apr',
                                                '05'=>'May',
                                                '06'=>'Jun',
                                                '07'=>'Jul',
                                                '08'=>'Aug',
                                                '09'=>'Sep',
                                                '10'=>'Oct',
                                                '11'=>'Nov',
                                                '12'=>'Dec',
                                                );

                                            $yearsList=array(
                                                ''=>'-- Year -- ',
                                                '2013'=>'2013',
                                                '2014'=>'2014',
                                                '2015'=>'2015',
                                                '2016'=>'2016',
                                                '2017'=>'2017',
                                                '2018'=>'2018',
                                                '2019'=>'2019',
                                                '2020'=>'2020',
                                                '2021'=>'2021',
                                            );

                                            ?>

                                        <?php echo $this->Form->input('selected_month',array('div'=>false,'label'=>false,'class'=>'span5 pmis_select','type'=>'select','options'=>$monthsList, 'empty'=>'','required'=>false,'id'=>'selected_month_id'));?>
                                        <?php echo $this->Form->input('selected_year',array('div'=>false,'label'=>false,'class'=>'span5 pmis_select','type'=>'select','options'=>$yearsList, 'empty'=>'','required'=>false,'style'=>'margin-left:5px;','id'=>'selected_year_id'));?>
                                        </div>
                                    </div>
                                </div>  

                    </div> 
                        <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" onclick="javascript:showData();">Search</button>
                             <?php echo $this->Form->input('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData()"))?>
                        </div>
                    <?php echo $this->Form->end();?>
                    </div>           
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
        url = url + '/state_id:'+$('#state_id').val();  
        url = url + '/district_id:'+$('#district_id').val();
        url = url + '/prison_id:'+$('#prison_id').val();            
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