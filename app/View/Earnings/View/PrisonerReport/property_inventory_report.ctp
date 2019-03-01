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
                    <h5>Prisoner Account details Receive & Withdrawals</h5>
                    <div style="float:right;padding-top:2px;">
                        <?php //echo $this->Html->link('Back',array('action'=>'index'),array('class' => 'btn btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                   <div class="">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div class="row">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prison</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$prisonList, 'class'=>'span11 pmis_select', 'id'=>'prison_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">UPS Region:</label>
                                        <div class="controls">
                                           <?php echo $this->Form->input('state_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$states, 'empty'=>'','required'=>false,'id'=>'state_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">UPS District:</label>
                                        <div class="controls">
                                           <?php echo $this->Form->input('ups_district_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonDistricts, 'empty'=>'','required'=>false,'id'=>'ups_district_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Geographical District:</label>
                                        <div class="controls">
                                           <?php echo $this->Form->input('geo_district_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$geographicalDistricts, 'empty'=>'','required'=>false,'id'=>'geo_district_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Gender</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('gender_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$genderList, 'class'=>'span11 pmis_select', 'id'=>'gender_id'));?>
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
                                            <?php echo $this->Form->input('from_date', array('type'=>'text', 'id'=>'from_date', 'class'=>'span11 from_date','div'=>false,'label'=>false,'placeholder'=>'Enter from date','required'))?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">To Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('to_date', array('type'=>'text', 'id'=>'to_date', 'class'=>'span11 to_date','div'=>false,'label'=>false,'placeholder'=>'Enter to date','required'))?>
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
                        <!-- <div class="row">
                           <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">EPD :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'epd_from', 'readonly'=>true,'style'=>'width:110px;'));?>
                                        To
                                        <?php echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'epd_to', 'readonly'=>true,'style'=>'width:110px;'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">LPD :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('lpd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'lpd_from', 'readonly'=>true,'style'=>'width:110px;'));?>
                                        To
                                        <?php echo $this->Form->input('lpd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'lpd_to', 'readonly'=>true,'style'=>'width:110px;'));?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>
                        </div> -->
                        <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" onclick="javascript:showData();">Search</button>
                        </div>
                    <?php echo $this->Form->end();?>
                    </div>
                    <div class="table-responsive" id="listingDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'PrisonerReport','action'=>'propertyInventoryReportAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/prison_id:'+$('#prison_id').val();
        url = url + '/state_id:'+$('#state_id').val();
        url = url + '/ups_district_id:'+$('#ups_district_id').val();
        url = url + '/geographical_id:'+$('#geo_district_id').val();
        url = url + '/gender_id:'+$('#gender_id').val();
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
