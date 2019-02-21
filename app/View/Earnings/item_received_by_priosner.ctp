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
                    <h5>Item Received By Prisoners</h5>
                    <a class="toggleBtn" href="#searchPurchasedItems" data-toggle="collapse"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){?>
                        <div style="float:right;padding-top: 7px;padding-right:5px;">
                            <?php echo $this->Html->link('Add Purchase Items','#addPurchaseItems',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse")); ?>
                        </div>
                    <?php }?>
                </div>
                <div class="widget-content nopadding">
                    <div id="searchPurchasedItems" class="collapse <?php if($isEdit == 0){echo 'in';}?>" <?php if($isEdit == 1){?> style="height: 0px;" <?php }?>>
                        <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">From Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('date_from',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate span11','type'=>'text', 'placeholder'=>'From Date ','required','readonly'=>'readonly','id'=>'date_from', 'style'=>'width:40%'));?>
                                            To
                                            <?php echo $this->Form->input('date_to',array('div'=>false,'label'=>false,'class'=>'form-control to_date mydate span11','type'=>'text', 'placeholder'=>'To Date ','required','readonly'=>'readonly','id'=>'date_to', 'style'=>'width:40%'));?>
                                        </div>
                                    </div> 
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Approval Status :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$approvalStatusList,'required'=>false, 'empty'=>array('0'=>'-- Select Approval Status --'), 'style'=>'width:90%', 'id'=>'status', 'default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$prisonerList, 'empty'=>'-- Select Prisoner Number --','required','id'=>'prisoner_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Item :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('item_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$itemList,'required'=>false, 'empty'=>array('0'=>'-- Select Item --'), 'style'=>'width:90%'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>"showDatapurchaseItem();"))?>
                                        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchItemReceivedByPriosnerForm')"))?>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <?php echo $this->Form->end();?> 
                    </div> 
                    <div id="addPurchaseItems" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;" <?php }?>>
                        <?php echo $this->Form->create('PurchaseItem',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
                        echo $this->Form->input('id',array('type'=>'hidden'));
                        echo $this->Form->input('prison_id',array(
                            'type'=>'hidden',
                            'class'=>'prison_id',
                            'value'=>$this->Session->read('Auth.User.prison_id')
                        ));
                        echo $this->Form->input('status',array(
                            'type'=>'hidden',
                            'value'=>'Draft'
                        ));
                        echo $this->Form->input('prisoner_baalance',array(
                            'type'=>'hidden',
                            'id'=>'prisoner_baalance'
                        ));?>
                        <div class="span12">
                            <div class="row-fluid" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date <?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('item_rcv_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter date','required','readonly'=>'readonly','id'=>'item_rcv_date'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$prisonerList, 'empty'=>array('0'=>'-- Select Prisoner Number --'),'required','id'=>'prisoner_id',  'onchange'=>'getPrisonerBalance(this.value);'));?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Name Of Item:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('item_id',array('div'=>false,'label'=>false,'onChange'=>'getItemPrice(this.value)','class'=>'form-control','type'=>'select','options'=>$itemList, 'empty'=>array('-- Select Item --'),'required','id'=>'item_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Price<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('price',array('div'=>false,'label'=>false,'class'=>'form-control span11','class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Price ','id'=>'price','readonly'));?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row-fluid">
                                <div class="clearfix"></div> 
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Issued By :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('issued_by',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$userList, 'empty'=>array('-- Select Issued By --'),'required','id'=>'issued_by'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6 hide">
                                    <div class="control-group">
                                        <label class="control-label">Is Enable?<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php 
                                            if(isset($this->request->data['PurchaseItem']['is_enable']) && ($this->request->data['PurchaseItem']['is_enable'] == 0))
                                            {
                                                echo $this->Form->input('is_enable', array('checked'=>false,'div'=>false,'label'=>false));
                                            }
                                            else 
                                            {
                                                echo $this->Form->input('is_enable', array('checked'=>true,'div'=>false,'label'=>false));
                                            }?>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="span12">
                            <div class="form-actions" align="center">
                                <?php echo $this->Form->input('Save', array('type'=>'submit','div'=>false,'label'=>false, 'class'=>'btn btn-success'));?>
                                <?php //echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('PurchaseItemItemReceivedByPriosnerForm'); showDatapurchaseItem();"));?>
                            </div> 
                        </div>  
                        <?php echo $this->Form->end();?> 
                    </div>
                    <div id="purchaseItem_listview"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
//get item price 
    function getItemPrice(id) 
    { 
        if(id != '')
        {
            var strURL = '<?php echo $this->Html->url(array('controller'=>'earnings','action'=>'getItemPrice'));?>';
        
            $.post(strURL,{"item_id":id},function(data){  
                
                if(data) 
                { 
                    var prisoner_baalance = $('#prisoner_baalance').val();
                    var item_price = $('#price').val(); 
                    if(parseFloat(prisoner_baalance) < parseFloat(item_price))
                    {
                        $('#price').val(data); 
                    }
                    else
                    {
                        dynamicAlertBox('Error','Insufficient fund to purchase.');
                        $('#item_id').val('');
                        $('#price').val(''); 
                    }
                }
            });
             
        }
        else 
        {
            $('#price').val(''); 
        }
    }
</script>
<?php
$purchaseItemUrl = $this->Html->url(array('controller'=>'earnings','action'=>'purchaseItemAjax'));
$deletepurchaseItemUrl = $this->Html->url(array('controller'=>'earnings','action'=>'deletePurchaseItem'));
$getPrisonerPBalance = $this->Html->url(array('controller'=>'earnings','action'=>'getPrisonerPBalance'));
echo $this->Html->scriptBlock("

    function getPrisonerBalance(pid)
    {
        var url = '".$getPrisonerPBalance."';
        $.post(url, {'prisoner_id':pid}, function(res) {
            if (res) {
                $('#prisoner_baalance').val(res);
            }
        });
    }

    function resetData(id){
        $('#'+id)[0].reset();
        $('select').select2({minimumResultsForSearch: Infinity});
        showDatapurchaseItem();
    }
   
    jQuery(function($) {
         showDatapurchaseItem();
         $('.toggleBtn').click(function(){
            $('.in.collapse').css('height','0');
            $('.in.collapse').removeClass('in');
         });
    }); 
    
    function showDatapurchaseItem(){
        var url = '".$purchaseItemUrl."';
        $.post(url, $('#SearchItemReceivedByPriosnerForm').serialize(), function(res) {
            if (res) {
                $('#purchaseItem_listview').html(res);
            }
        });
    }

    //delete working party 
    function deletepurchaseItem(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deletepurchaseItemUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showDatapurchaseItem();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

",array('inline'=>false));
?>
<script>
$(function(){

    $("#PurchaseItemItemReceivedByPriosnerForm").validate({
 
    ignore: "",
        rules: {  
            'data[PurchaseItem][item_rcv_date]': {
                required: true,
                datevalidateformat: true
            },
            'data[PurchaseItem][prisoner_id]': {
                required: true,
                min: 1
            },
            'data[PurchaseItem][item_id]': {
                required: true,
                min: 1
            },
            // 'data[PurchaseItem][price]': {
            //     required: true
            // }
        },
        messages: {
            'data[PurchaseItem][item_rcv_date]': {
                required: "Please choose item purchase date.",
                datevalidateformat: "Wrong Date Format"
            },
            'data[PurchaseItem][prisoner_id]': {
                required: "Please select prisoner no.",
                min: "Please select prisoner no."
            },
            'data[PurchaseItem][item_id]': {
                required: "Please select item.",
                min: "Please select Item."
            },
            // 'data[PurchaseItem][price]': {
            //     required: "Price is required."
            // }
        }, 
    });
});
</script>
