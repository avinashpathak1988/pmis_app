<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
.paginate{
    display: none;
    margin-bottom: 10px;
}

</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Gatebook Report</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php //echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                    <?php echo $this->Form->create('GatePass',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                    <?php echo $this->Form->input('page',array('div'=>false,'label'=>false,'type'=>'hidden','class'=>'form-control','value'=>'1', 'id'=>'page_selected'));?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoners</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoners --','options'=>$prisonerListData, 'class'=>'form-control', 'id'=>'prisoner_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label"> Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'date_from', 'readonly'=>true,'style'=>'width:43%;','value'=>date("d-m-Y")));?>
                                            To
                                            <?php echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'date_to', 'readonly'=>true,'style'=>'width:43%;','value'=>date("d-m-Y")));?>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchgatebook" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                                
                            </div>
                            <div class="paginate" align="left">
                                <button id="prev" class="btn btn-success" type="button" formnovalidate="formnovalidate">Prev</button>
                                 <button id="next" class="btn btn-success" type="button" formnovalidate="formnovalidate">Next</button>
                                 <span style="float: right;margin-right: 5%;"><b>Showing Page <span id="pageNo">1</span></b>
                                 <?php
                                    $exUrl = "indexAjax";
                                    $urlExcel = $exUrl.'/reqType:XLS';
                                    $urlDoc = $exUrl.'/reqType:DOC';
                                    $urlPDF = $exUrl.'/reqType:PDF';
                                  $urlPrint = $exUrl.'/reqType:PRINT';
                                    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
                                     echo '&nbsp;&nbsp;';
                                    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
                                    echo '&nbsp;&nbsp;';
                                    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
                                  echo '&nbsp;&nbsp;';
                                  echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
                                  
                                ?>
                                   </span>                     
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
        $ajaxUrl = $this->Html->url(array('controller'=>'GatebookReport','action'=>'indexAjax'));

 ?>
<script type="text/javascript">

$( document ).ready(function() {

    var currPage = $('#page_selected').val();
     if(parseInt(currPage) == 1 ){
        $('#prev').attr('disabled','disabled');
        $('#prev').removeClass('btn-success');
        $('#prev').addClass('btn-warning');
     }else{
        $('#prev').removeAttr('disabled');
        $('#prev').removeClass('btn-warning');
        $('#prev').addClass('btn-success');
     }


    showListSearch();

    $('#btnsearchgatebook').click(function(){
        $('#page_selected').val(1);
        $('#pageNo').html(1);
        $('#prev').attr('disabled','disabled');
        $('#prev').removeClass('btn-success');
        $('#prev').addClass('btn-warning');
        $('#next').removeAttr('disabled');
        $('#next').removeClass('btn-warning');
        $('#next').addClass('btn-success');
        showListSearch();
    });

    $('#prev').click(function(){
        var currPage = $('#page_selected').val();
        var prevPage = parseInt(currPage) - 1;
        $('#page_selected').val(prevPage);
        $('#pageNo').html(prevPage);
        if(parseInt(prevPage) == 1 ){
            $('#prev').attr('disabled','disabled');
            $('#prev').removeClass('btn-success');
            $('#prev').addClass('btn-warning');
         }else{
            $('#prev').removeAttr('disabled');
            $('#prev').removeClass('btn-warning');
            $('#prev').addClass('btn-success');
         }

        showListSearch();
    });


    $('#next').click(function(){
        var currPage = $('#page_selected').val();
        var nextPage = parseInt(currPage) + 1;
        $('#pageNo').html(nextPage);

        $('#page_selected').val(nextPage);

            $('#prev').removeAttr('disabled');
            $('#prev').removeClass('btn-warning');
            $('#prev').addClass('btn-success');
         
        showListSearch();
    });

});

 function showListSearch(){
        var currPage = $('#page_selected').val();
        
        var url ='<?php echo $ajaxUrl?>';
        $.post(url, $('#GatePassIndexForm').serialize(), function(res) {
            if (res) {
                        $('#listingDiv').html(res);

                        $('#next').removeAttr('disabled');
                        $('#next').removeClass('btn-warning');
                        $('#next').addClass('btn-success');
                 }else{
                        $('#listingDiv').html('No records found !');
                        $('#next').attr('disabled','disabled');
                        $('#next').removeClass('btn-success');
                        $('#next').addClass('btn-warning');

                     }

                     $('.paginate').show();
            });
 }
</script>