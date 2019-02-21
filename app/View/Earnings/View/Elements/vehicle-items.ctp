
<div class="span12"  style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
    <div style="width: 100%;
    margin-left: 112px;;">
        <div class="scountDiv2" id="vehicleItemsDiv"> 
                
            <?php 
            $total_scount = 0; $editPhysicalPropertyItemData = '';
            
            if(isset($this->request->data['VehicleItem']) && count($this->request->data['VehicleItem']) > 0)
            {
                $vehicleItems  =   $this->request->data['VehicleItem'];
            }
            if(isset($vehicleItems)  && count($vehicleItems) > 0)
            {
                $i = 0;
                $total_scount = count($vehicleItems);
                //echo '<pre>'; print_r($VisitorItem); exit;
                ?>
                <div class="row-fluid">
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Voucher No.<span style="color:red;"></span></label>
                    </div>
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Items<span style="color:red;"></span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;"></span></label>
                    </div>
                    <div class="span3">
                    
                    <label class="control-label" style="text-align: left;">Description<span style="color:red;"></span></label>
                    </div>
                </div>
                <?php
                foreach($vehicleItems as $vehicleItem)
                {
                    //$VisitorItem = $VisitorItem['VisitorItem'];
                     //if($VisitorItem["item_status"]=="Incoming"){
                    ?>

                    <div class="entry2 visitorItemEntry input-group row-fluid uradioBtn">
                    
                    <div class="span2">
                        <?php echo $this->Form->input('VehicleItem.'.$i.'.voucher_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','title'=>'Please enter voucher number.', 'placeholder'=>'Enter Voucher Number','required'=>false,));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VehicleItem.'.$i.'.item',array('div'=>false,'label'=>false,'class'=>'form-control span11 ','type'=>'text', 'title'=>'Please enter item.','placeholder'=>'Item Name','required'=>false,));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VehicleItem.'.$i.'.quantity',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11 quan','type'=>'text','title'=>'Please enter quantity.', 'placeholder'=>'Quantity','maxlength'=>'3','required'=>false));?>
                    </div>
                      <div class="span3">
                        <?php echo $this->Form->input('VehicleItem.'.$i.'.description',array('div'=>false,'label'=>false,'class'=>'form-control span11 ','type'=>'text','title'=>'Please enter description.', 'placeholder'=>'Item Name','required'=>false,));?>
                    </div>
                    <?php if($i == 0)
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-add4" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                    <span class="icon icon-plus"></span>
                                </button>
                            </span>
                        <?php }
                        else 
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-danger btn-remove" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                    <span class="icon icon-minus"></span>
                                </button>
                            </span>
                        <?php }?>
                </div>

                   
                <?php $i++;
           // }
                }
            }
            else 
            {?>
                <div class="row-fluid">
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Voucher No.<span style="color:red;"></span></label>
                    </div>
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Items<span style="color:red;"></span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;"></span></label>
                    </div>
                    <div class="span3">
                    
                    <label class="control-label" style="text-align: left;">Description<span style="color:red;"></span></label>
                    </div>
                    
                </div>
                <div class="entry2 visitorItemEntry input-group row-fluid uradioBtn">
                    <div class="span2">
                        <?php echo $this->Form->input('VehicleItem.0.voucher_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','title'=>'Please enter voucher number.', 'placeholder'=>'Enter Voucher Number','required'=>false,));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VehicleItem.0.item',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Item Name','title'=>'Please enter item.','required'=>false,));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VehicleItem.0.quantity',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11 quan','type'=>'text','title'=>'Please enter quantity.', 'placeholder'=>'Quantity','maxlength'=>'3','required'=>false));?>
                    </div>
                      <div class="span3">
                        <?php echo $this->Form->input('VehicleItem.0.description',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','title'=>'Please enter description.', 'placeholder'=>'Item Name','required'=>false,));?>
                    </div>
                    
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-add4" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                            <span class="icon icon-plus"></span>
                        </button>
                    </span>
                </div> 
            <?php }?>
        </div>
    </div>
</div>     
<style type="text/css">
    .scountDiv2 input {
    margin-bottom: 10px;
}
</style>
<script type="text/javascript">
$(function()
{
    var scount = 0;
    $(document).on('click', '.btn-add4', function(e)
    {
        e.preventDefault();
        $("select").select2("destroy");
        //$("select").select2('val','');
        var controlForm = $('#vehicleItemsDiv');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('#vehicleItemsDiv .visitorItemEntry:last');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('.quan').val('');
        controlForm.find('.visitorItemEntry:not(:first) .btn-add4')
            .removeClass('btn-add4').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');
        //change name of inputs 
        scount = parseInt($('#vehicleItemsDiv input[name*="item"]').length);
        scount = scount-1; 
        

        var voucher_name = "data[VehicleItem]["+scount+"][voucher_no]";
        var voucher_id = "VehicleItem"+scount+"voucher_no";
        $('#vehicleItemsDiv input[name*="voucher_no"]:last').attr('name',voucher_name);
        $('#vehicleItemsDiv input[name*="voucher_no"]:last').attr('id',voucher_id);
        $('#vehicleItemsDiv input[name*="voucher_no"]:last').attr('required','required');

        $('#'+voucher_id).val('');

        var item_id_name = "data[VehicleItem]["+scount+"][item]";
        var item_id_id = "VehicleItem"+scount+"item";
        $('#vehicleItemsDiv input[name*="item"]:last').attr('name',item_id_name);
        $('#vehicleItemsDiv input[name*="item"]:last').attr('id',item_id_id);
        $('#vehicleItemsDiv input[name*="item"]:last').attr('required','required');

        $('#'+item_id_id).val('');

        var quantity_name = "data[VehicleItem]["+scount+"][quantity]";
        var quantity_name_id = "VehicleItem"+scount+"quantity";
        $('#vehicleItemsDiv input[name*="quantity"]:last').attr('name',quantity_name);
        $('#vehicleItemsDiv input[name*="quantity"]:last').attr('id',quantity_name_id);
        $('#vehicleItemsDiv input[name*="quantity"]:last').attr('required','required');


        var desc_name = "data[VehicleItem]["+scount+"][description]";
        var desc_name_id = "VehicleItem"+scount+"description";
        $('#vehicleItemsDiv input[name*="description"]:last').attr('name',desc_name);
        $('#vehicleItemsDiv input[name*="description"]:last').attr('id',desc_name_id);
        $('#vehicleItemsDiv input[name*="description"]:last').attr('required','required');


        $('#VehicleItem'+scount+'weight').val('');
        
        $('#prisoner_no').select2();
        $('#pp_cash').select2();
        $('.relation').select2();

        $('#vehicleItemsDiv .visitorItemEntry:last label.error').remove();
               
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.visitorItemEntry:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>