<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Prisoner</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li><a href="#tab-1">Tab 1</a></li>
                            <li><a href="#tab-2">Tab 2</a></li>
                            <!-- <li class="pull-right controls"> -->
                            <li class="controls">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="tabscontent">
                            <div id="tab-1">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">First Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text','placeholder'=>'Enter First Name','required'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Surname<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text','placeholder'=>'Enter First Name','required'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Father's Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text','placeholder'=>"Enter Father's Name",'required'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Mother's Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('mother_name',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text','placeholder'=>"Enter Mother's Name",'required'));?>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Date of Birth<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control my_date','type'=>'text', 'placeholder'=>'Enter Date of Birth','required'));?>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Date of Place<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_place',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text', 'placeholder'=>'Enter Date of Place','required'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div>                                                                     
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Gender<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('gender',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$genderList, 'empty'=>'-- Select Gender --','required'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Nationality<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('country_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$countryList, 'empty'=>'-- Select Nationality --','required'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Tribe<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('tribe_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$tribeList, 'empty'=>'-- Select Tribe --','required'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Photo<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file'));?>
                                            </div>
                                        </div>
                                    </div>                            
                                </div>
                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                            <div id="tab-2" class="lorem">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Type<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'span11 alpha','type'=>'text','placeholder'=>'Enter First Name','required'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Last Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'span11 alpha','type'=>'text','placeholder'=>'Enter First Name','required'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div>  
                                </div>
                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'users','action'=>'getDistrict'));
echo $this->Html->scriptBlock("
   var tabs;
    jQuery(function($) {
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });
        $('#saveBtn').on('click', function(e){
            var action = $(this).attr('tabcls');
            tabs[action]();
            e.preventDefault();
        });
    }); 
    function getDistrict(){
        var url = '".$ajaxUrl."';
        $.post(url, {'state_id':$('#state_id').val()}, function(res) {
            if (res) {
                $('#district_id').html(res);
            }
        });
    }
",array('inline'=>false));
?> 