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
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label">From <?php echo $req; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('from_date',array('div'=>false,'label'=>false,'class'=>'form-control ','data-date-format'=>"dd-mm-yyyy", 'readonly'=>'readonly','class'=>'form-control mydate','type'=>'text','placeholder'=>'Enter From Date ','id'=>'from_date'));?>
                                        <div class="error-message nodisplay" id="from_date_err">From date is required.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label">To<?php echo $req; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('to_date',array('div'=>false,'label'=>false,'class'=>'form-control ','data-date-format'=>"dd-mm-yyyy", 'readonly'=>'readonly','class'=>'form-control mydate','type'=>'text','placeholder'=>'Enter To Date ','id'=>'to_date'));?>
                                        <div class="error-message nodisplay" id="to_date_err">To date is required.</div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                          

                        <div class="form-actions" align="right">
                            <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>'javascript:validateSearchForm();'))?>
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
$ajaxUrl    = $this->Html->url(array('controller'=>'earnings','action'=>'freeWorkingPrisonerAjax'));
echo $this->Html->scriptBlock("

    jQuery(function($) {
         showPrisonersList();
    }); 
    
    function validateSearchForm(){

        var url = '".$ajaxUrl."';
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var working_party_id = $('#working_party_id').val();
        if(from_date == '' || to_date == '' || working_party_id == '')
        { 
            if(from_date == '')
            {
                $('#from_date_err').removeClass('nodisplay');
            }
            else 
            {
                $('#from_date_err').addClass('nodisplay');
            }
            if(to_date == '')
            {
                $('#to_date_err').removeClass('nodisplay');
            }
            else 
            {
                $('#to_date_err').addClass('nodisplay');
            }
            if(working_party_id == '')
            {
                $('#working_party_err').removeClass('nodisplay');
            }
            else 
            {
                $('#working_party_err').addClass('nodisplay');
            }
        }
        else 
        {
            $('#from_date_err').addClass('nodisplay');
            $('#to_date_err').addClass('nodisplay');
            $('#working_party_err').addClass('nodisplay');

            $.post(url, {'from_date':from_date, 'to_date':to_date, 'working_party_id':working_party_id}, function(res) {
                if (res) {
                    $('#listingDiv').html(res);
                }
            });
        }
        
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