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
                    <h5>Add Purchased Items</h5>
                    <a class="toggleBtn" href="#searchPurchasedItems" data-toggle="collapse"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                    
                        <div style="float:right;padding-top: 7px;padding-right:5px;">
                            <?php echo $this->Html->link('Purchased Items List',array('controller'=>'Earnings','action'=>'itemReceivedByPriosner'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        </div>
                    
                </div>
                <div class="widget-content nopadding"> 
                        <?php echo $this->Form->create('PurchaseItem',array('class'=>'form-horizontal','url'=>'/earnings/itemReceivedByPriosner','enctype'=>'multipart/form-data'));
                        echo $this->Form->input('id',array('type'=>'hidden'));
                        echo $this->Form->input('prison_id',array(
                            'type'=>'hidden',
                            'class'=>'prison_id',
                            'value'=>$this->Session->read('Auth.User.prison_id')
                        ));
                        echo $this->Form->input('status',array(
                            'type'=>'hidden',
                            'value'=>'Draft'
                        ));?>
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
                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$prisonerList, 'empty'=>array('0'=>'-- Select Prisoner Number --'),'required','id'=>'prisoner_id'));?>
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
                                        <?php echo $this->Form->input('price',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Price ','id'=>'price','readonly', 'required'=>false));?>
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
                        <div class="form-actions" align="center">
                            <?php echo $this->Form->input('Save', array('type'=>'submit','div'=>false,'label'=>false, 'class'=>'btn btn-success'));?>
                        </div> 
                        <?php echo $this->Form->end();?> 
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
                
                if(data) { 
                    $('#price').val(data); 
                    
                }
            });
        }
        else 
        {
            $('#price').val(''); 
        }
    }
</script>
<script>
$(function(){

    $("#PurchaseItemPurchaseItemsForm").validate({
 
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
