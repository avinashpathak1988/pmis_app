<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    
                    <h5>Search</h5>
                </div>
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div id="searchPrisonerTwo" class="row collapse" style="height:auto;">
                          <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Theme Name. :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('stheme_name',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','required'=>false,'placeholder'=>'Search by theme Name.','id'=>'stheme_name'));?>
                                    </div>
                                </div>
                                 
                                
                          </div>
                          <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Theme Type  :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('stheme_type',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','id'=>'stheme_type','options'=>$themeTypes,'empty'=>'','required'=>false));?>
                                    </div>
                                </div>
                                
                                
                          </div>
                          <div class="span12 add-top" align="center" valign="center">
                                <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'btnsearchcash', 'onclick'=>"showListSearch();"))?>
                                <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false,'onclick'=>'resetData("SearchIndexForm");', 'class'=>'btn btn-danger'))?>
                            </div> 
                    </div> 
                                <?php echo $this->Form->end();?>
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Social Theme List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Add New Social Theme',array('action'=>'add'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="table-responsive" id="listingDiv">
                        <table id="example2" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">SL#</th>
                                    <th>Name</th>
                                    <th>Is Enable ?</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

<?php
    $i=0;
    foreach($datas as $data){
?>
                                <tr>
                                    <td class="text-center"><?php echo ++$i; ?></td>
                                    <td><?php echo $data['SocialTheme']['name']; ?></td>
                                    <td>
<?php
        if($data['SocialTheme']['is_enable'] == 1){
            echo "<font color='green'>Yes</font>";
        }else{
            echo "<font color='red'>No</font>";
        }
?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $this->Form->create('SocialThemeEdit',array('url'=>'/SocialThemes/add','admin'=>false));?> 
                                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['SocialTheme']['id'])); ?>
                                        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini onlyIcon','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                                        <?php echo $this->Form->end();?> 
                                    
                                        <?php echo $this->Form->create('SocialThemeDelete',array('url'=>'/SocialThemes/index','admin'=>false));?> 
                                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['SocialTheme']['id'])); ?>
                                        <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini onlyIcon','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
                                        <?php echo $this->Form->end();?>
                                    </td>
                                    <td>
                                        <div class="table-responsive" id="listingDiv">

                                           </div>
                                    </td>
                                </tr>
                                        
                                    
<?php
    }
?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$ajaxUrl = $this->Html->url(array('controller'=>'SocialThemes','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url;
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }
    function resetData(id){
        $('#'+id)[0].reset();
        $('select').select2({minimumResultsForSearch: Infinity});
        showData();
    }
    function showListSearch(){
        var url = '".$ajaxUrl."';

        url = url;
        $.post(url,$('#SearchIndexForm').serialize(), function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
}
",array('inline'=>false));
?> 

<script type="text/javascript">
function confirmdelete(){
    var c=confirm("Are you sure to delete ?");
    if(c == false){
        return false;
    }else{
        return true;
    }
}

</script>
