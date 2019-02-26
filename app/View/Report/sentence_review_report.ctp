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
                    <div class="">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div class="row" style="padding-bottom: 14px;">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo isset($modelArr['country_id'])?$modelArr['country_id']:'Country'?> </label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('country_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'', 'class'=>'form-control pmis_select', 'id'=>'country_id', 'options'=>$countryList, 'multiple'=>true));?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo isset($modelArr['state_id'])?$modelArr['state_id']:'Region'?> </label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('state_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'', 'class'=>'form-control pmis_select', 'id'=>'state_id', 'options'=>$regionList,'multiple'=>true));?>
                                    </div>
                                </div>
                            </div>
                                      
                           <!--  <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo isset($modelArr['district_id'])?$modelArr['district_id']:'District'?></label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'', 'class'=>'form-control pmis_select', 'id'=>'district_id', 'options'=>array()));?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo isset($modelArr['prison_id'])?$modelArr['prison_id']:'Prison Station'?></label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'', 'class'=>'form-control pmis_select', 'id'=>'prison_id', 'options'=>array()));?>
                                    </div>
                                </div>
                            </div>           -->   
                        </div>  
                <div class="row" style="padding-bottom: 14px;"> 
                  <div class="span4">
                    <div class="control-group">
                                 
                    <label class="control-label"><?php echo isset($modelArr['month'])?$modelArr['month']:'Month'?></label>

                                    <div class="controls">
                                        <?php
                                         $month = array('01 '=>'JANUARY','02'=>'FEBRUARY','03'=>'MARCH','04'=>'APRIL','05'=>'MAY','06'=>'JUNE','07'=>'JULY','08'=>'AUGUST','09'=>'SEPTEMBER','10'=>'OCTOBER','11'=>'NOVEMBER','12'=>'DECEMBER');
                                         echo $this->Form->input('month',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control', 'id'=>'month','style'=>'width:120px;','type'=>'select','empty'=>'--Select--','options'=>$month));

                                        
                                         ?>
                                    </div>                  
                                    
                                </div>
                            </div>
                             
                             <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo isset($modelArr['year'])?$modelArr['year']:'Year'?></label>
                                       <div class="controls">
                                            <select name = "year" id= "year">
                                                <?php
                                                   for($i = 2018; $i <= date("Y"); $i++){
                                                     echo "<option>" . $i . "</option>";
                                                   }
                                                  ?>
                                            </select>
                                        </div> 
                                </div>
                            </div>
                                    
                            <div class="clearfix"></div> 
                        </div>

                        <div class="row" style="padding-bottom: 14px;">

                         <div class="span4">
                                <div class="control-group">
                                    <label class="control-label">From Date</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('from_date',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control', 'id'=>'from_date', 'readonly'=>true));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label">To Date</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('to_date',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control', 'id'=>'to_date', 'readonly'=>true));?>
                                    </div>
                                </div>
                            </div>     


                                                     
                            <div class="clearfix"></div> 
                        </div>
                        <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" onclick="javascript:showData();">Search</button>
                             <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData()"))?>   
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
$ajaxUrl        	  = $this->Html->url(array('controller'=>'report','action'=>'sentenceReviewReportAjax'));
$ajaxDistrictUrl      = $this->Html->url(array('controller'=>'report','action'=>'getDistrictList'));
$ajaxPrisonUrl        = $this->Html->url(array('controller'=>'report','action'=>'getPrisonList'));
$resturl            = $this->Html->url(array('controller'=>'report','action'=>'sentenceReviewReport'));

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
        window.location.href = '".$resturl."' ;
    }
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/country_id:'+$('#country_id').val();
        url = url + '/state_id:'+$('#state_id').val();  
        url = url + '/district_id:'+$('#district_id').val();
        url = url + '/prison_id:'+$('#prison_id').val();            
        url = url + '/from_date:'+$('#from_date').val();
        url = url + '/to_date:'+$('#to_date').val();
        url = url + '/month:'+$('#month').val();
        url = url + '/year:'+$('#year').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }



",array('inline'=>false));
?>