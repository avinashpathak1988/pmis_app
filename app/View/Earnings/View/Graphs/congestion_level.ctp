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
                    <h5>Congestion levels</h5>                    
                </div>
                <div class="widget-content nopadding">
                    <div class="" style="display: none;">
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoners</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoners --','options'=>$prisonerListData, 'class'=>'form-control', 'id'=>'prisoner_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Date of Release</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('relese_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate
                                                ','type'=>'text','placeholder'=>'Start Date','id'=>'relese_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('relese_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text','placeholder'=>'End Date','id'=>'relese_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="clearfix"></div> 
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Name of Prisoner</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control
                                                ','type'=>'text','placeholder'=>'Prisoner Name','id'=>'prisoner_name','required'=>false));?>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                                
                            </div>
                            <?php echo $this->Form->end();?>
                    </div>           
                    <div class="table-responsive" id="listingDiv">
                        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                            <script type="text/javascript">

                            // Highcharts.chart('container', {
                            //     chart: {
                            //         type: 'column'
                            //     },
                            //     title: {
                            //         text: 'Column chart with negative values'
                            //     },
                            //     xAxis: {
                            //         categories: [<?php //echo (isset($prisonGraph['name']) && count($prisonGraph['name'])>0) ? "'".implode("','", $prisonGraph['name'])."'" : ""; ?>]
                            //     },
                            //     credits: {
                            //         enabled: false
                            //     },
                            //     series: [{
                            //         name: 'Capacity',
                            //         data: [<?php //echo (isset($prisonGraph['Capacity']) && count($prisonGraph['Capacity'])>0) ? implode(",", $prisonGraph['Capacity']) : ""; ?>]

                            //     },  {
                            //         name: 'Unlock',
                            //         data: [<?php //echo (isset($prisonGraph['Unlock']) && count($prisonGraph['Unlock'])>0) ? implode(",", $prisonGraph['Unlock']) : ""; ?>]

                            //     },{
                            //         name: 'Congestion',
                            //         data: [<?php //echo (isset($prisonGraph['Congestion']) && count($prisonGraph['Congestion'])>0) ? implode(",", $prisonGraph['Congestion']) : ""; ?>]

                            //     }, {
                            //         name: 'Occupancy',
                            //         data: [<?php //echo (isset($prisonGraph['Occupancy']) && count($prisonGraph['Occupancy'])>0) ? implode(",", $prisonGraph['Occupancy']) : ""; ?>]

                            //     }]
                            // });


                             Highcharts.chart('container', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Congestion levels'
                                },
                                subtitle: {
                                    text: ''
                                },
                                xAxis: {
                                    categories: [<?php echo (isset($prisonGraph['name']) && count($prisonGraph['name'])>0) ? "'".implode("','", $prisonGraph['name'])."'" : ""; ?>],
                                    crosshair: true
                                },
                                yAxis: {
                                    title: {
                                        text: 'Prisoner (No.)'
                                    }
                                },
                                tooltip: {
                                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                        '<td style="padding:0"><b>{point.y} </b></td></tr>',
                                    footerFormat: '</table>',
                                    shared: true,
                                    useHTML: true
                                },
                                plotOptions: {
                                    column: {
                                        pointPadding: 0.2,
                                        borderWidth: 0
                                    }
                                },
                                series: [{
                                    name: 'Capacity',
                                    data: [<?php echo (isset($prisonGraph['Capacity']) && count($prisonGraph['Capacity'])>0) ? implode(",", $prisonGraph['Capacity']) : ""; ?>]

                                },  {
                                    name: 'Unlock',
                                    data: [<?php echo (isset($prisonGraph['Unlock']) && count($prisonGraph['Unlock'])>0) ? implode(",", $prisonGraph['Unlock']) : ""; ?>]

                                },{
                                    name: 'Congestion',
                                    data: [<?php echo (isset($prisonGraph['Congestion']) && count($prisonGraph['Congestion'])>0) ? implode(",", $prisonGraph['Congestion']) : ""; ?>]

                                }, {
                                    name: 'Occupancy',
                                    data: [<?php echo (isset($prisonGraph['Occupancy']) && count($prisonGraph['Occupancy'])>0) ? implode(",", $prisonGraph['Occupancy']) : ""; ?>]

                                }]
                            });
                            
                            </script>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>