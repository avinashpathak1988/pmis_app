
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
                    <h5>User Access Controls</h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php echo $this->Form->create('UserAccessControl',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prison <?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonList, 'empty'=>'-- Select Prison --','required','id'=>'prison_id'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">User type <?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('user_type',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$usertypeList, 'empty'=>'-- Select User type --','required','id'=>'user_type'));?>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <?php 
                        if(count($modules) > 0)
                        {
                            foreach($modules as $key=>$name)
                            {?>
                                <div class="row-fluid">
                                    <div class="span2">
                                        <?php echo ucfirst($name);?>
                                    </div>
                                    <div class="span10">
                                        <div class="span2">
                                            <?php echo $this->Form->input('add',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false));?>
                                        </div>
                                        <div class="span2">
                                            <?php echo $this->Form->input('edit',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false));?>
                                        </div>
                                        <div class="span2">
                                            <?php echo $this->Form->input('delete()',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false));?>
                                        </div>
                                        <div class="span2">
                                            <?php echo $this->Form->input('view',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false));?>
                                        </div>
                                        <div class="span2">
                                            <?php echo $this->Form->input('review',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false));?>
                                        </div>
                                        <div class="span2">
                                            <?php echo $this->Form->input('approve',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false));?>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                            }
                        }
                        ?>
                       
                        <div class="form-actions" align="center">
                            <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
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
$ajaxUrl        = $this->Html->url(array('controller'=>'gatepasses','action'=>'indexAjax'));
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
        showCommonHeader();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        var uuid  = '".$uuid."';
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }

    //common header
    function showCommonHeader(){ 
        var prisoner_id = ".$prisoner_id.";;
        console.log(prisoner_id);  
        var uuid        = '".$uuid."';
        var url         = '".$commonHeaderUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
           
            if (res) {
                $('#commonheader').html(res);
            }
        }); 
    }

",array('inline'=>false));
?>