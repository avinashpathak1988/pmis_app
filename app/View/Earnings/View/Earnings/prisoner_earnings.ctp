<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Prisoner Earnings</h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <div class="row-fluid" style="padding-bottom: 14px;">

                            <?php if($isAdmin == 1)
                            {?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prison :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonList,'empty'=>''));?>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>
                            
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner No.:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonerList,'required'=>false, 'empty'=>''));?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">From:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('from_date',array('div'=>false,'label'=>false,'class'=>'form-control ','data-date-format'=>"dd-mm-yyyy", 'readonly'=>'readonly','class'=>'form-control mydate','type'=>'text','placeholder'=>'Enter From Date ','id'=>'from_date'));?>
                                    </div>
                                </div>
                            </div>
                           
                            
                        </div> 
                          <div class="row-fluid" style="padding-bottom: 14px;">

                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">To:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('to_date',array('div'=>false,'label'=>false,'class'=>'form-control ','data-date-format'=>"dd-mm-yyyy", 'readonly'=>'readonly','class'=>'form-control mydate','type'=>'text','placeholder'=>'Enter To Date ','id'=>'to_date'));?>
                                    </div>
                                </div>
                            </div>
                            
                        </div> 
                        
                          

                        <div class="form-actions" align="center">
                            <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>'javascript:validateSearchForm();'))?>
                             <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
                        </div>
                        <?php echo $this->Form->end();?>
                        <div id="listingDiv"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl    = $this->Html->url(array('controller'=>'earnings','action'=>'prisonerEarningAjax'));
echo $this->Html->scriptBlock("

    jQuery(function($) {
         showPrisonersList();
    }); 
    
    function validateSearchForm(){

        var url = '".$ajaxUrl."';

        $.post(url, $('#SearchPrisonerEarningsForm').serialize(), function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    
    }
    
    function showPrisonersList()
    {
        var url = '".$ajaxUrl."';

        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var working_party_id = $('#working_party_id').val();

        $.post(url, {'from_date':from_date, 'to_date':to_date, 'working_party_id':working_party_id}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }
 
       
",array('inline'=>false));
?>