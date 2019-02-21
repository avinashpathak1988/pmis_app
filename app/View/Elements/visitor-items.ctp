
<div class="span12" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
    <div style="width: 100%;
    margin-left: 112px;;">
        <div class="scountDiv2" id="visitorItemsDiv"> 
                
            <?php 
            $total_scount = 0; $editPhysicalPropertyItemData = '';
            
            if(isset($this->request->data['VisitorItem']) && count($this->request->data['VisitorItem']) > 0)
            {
                $VisitorItem  =   $this->request->data['VisitorItem'];
            }
            if(isset($VisitorItem)  && count($VisitorItem) > 0)
            {
                $i = 0;
                $total_scount = count($VisitorItem);
                //echo '<pre>'; print_r($VisitorItem); exit;
                ?>
                <div class="row-fluid">
                    <div class="span3">
                        <label class="control-label" style="text-align: left;">Items<span style="color:red;"></span></label>
                    </div>
                    <div class="span3">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;"></span></label>
                    </div>
                    <div class="span4">
                    <label class="control-label" style="text-align: left;">Weight<span style="color:red;"></span></label>
                    </div>
                </div>
                <?php
                foreach($VisitorItem as $VisitorItem)
                {
                    //$VisitorItem = $VisitorItem['VisitorItem'];
                     //if($VisitorItem["item_status"]=="Incoming"){
                    ?>

                    <div class="entry2 visitorItemEntry input-group row-fluid uradioBtn">
                    <div class="span3">
                    <?php echo $this->Form->input('VisitorItem.'.$i.'.id', array('type'=>'hidden'))?>
                        <?php echo $this->Form->input('VisitorItem.'.$i.'.item',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Item Name','required'=>false,'value'=>$VisitorItem["item"]));?>
                    </div>
                    <div class="span3">
                        <?php echo $this->Form->input('VisitorItem.'.$i.'.quantity',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11 quan','type'=>'text','maxlength'=>'3' ,'placeholder'=>'Quantity','required'=>false,'value'=>$VisitorItem["quantity"]));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VisitorItem.'.$i.'.weight',array('div'=>false,'label'=>false,'class'=>'form-control  span11 quan','type'=>'text' ,'placeholder'=>'Weight','required'=>false,'value'=>$VisitorItem["weight"]));?>
                           
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VisitorItem.'.$i.'.weight_unit',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$weight_units, 'empty'=>array(''=>'-- Select unit --'),'required'=>false, 'style'=>'width:92%'));?>
                    </div>
                    <?php if($i == 0)
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-add" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
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
                    <div class="span3">
                        <label class="control-label" style="text-align: left;">Items<span style="color:red;"></span></label>
                    </div>
                    <div class="span3">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;"></span></label>
                    </div>
                    <div class="span4">
                    
                    <label class="control-label" style="text-align: left;">Weight<span style="color:red;"></span></label>
                    </div>
                    
                </div>
                <div class="entry2 visitorItemEntry input-group row-fluid uradioBtn">
                    <div class="span3">
                        <?php echo $this->Form->input('VisitorItem.0.item',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Item Name','required'=>false,));?>
                    </div>
                    <div class="span3">
                        <?php echo $this->Form->input('VisitorItem.0.quantity',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11 quan','type'=>'text', 'placeholder'=>'Quantity','maxlength'=>'3','required'=>false));?>
                    </div>
                      <div class="span2">
                        <?php echo $this->Form->input('VisitorItem.0.weight',array('div'=>false,'label'=>false,'class'=>'form-control  span11 ','type'=>'text','maxlength'=>'10' ,'placeholder'=>'Weight','required'=>false));?>
                        
                    </div>
                      <div class="span2">
                         <?php echo $this->Form->input('VisitorItem.0.weight_unit',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$weight_units, 'empty'=>array(''=>'-- Select unit --'),'required'=>false, 'style'=>'width:92%'));?>
                       
                      </div>
                    
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-add" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
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
    $(document).on('click', '.btn-add', function(e)
    {
        e.preventDefault();
        $("#visitorItemsDiv select").select2("destroy");
        //$("select").select2('val','');
        var controlForm = $('#visitorItemsDiv');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('#visitorItemsDiv .visitorItemEntry:last');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('.quan').val('');
        controlForm.find('.visitorItemEntry:not(:first) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');
        //change name of inputs 
        scount = parseInt($('#visitorItemsDiv input[name*="item"]').length);
        scount = scount-1; 
        
        var item_id_name = "data[VisitorItem]["+scount+"][item]";
        var item_id_id = "VisitorItem"+scount+"item";
        $('#visitorItemsDiv input[name*="item"]:last').attr('name',item_id_name);
        $('#visitorItemsDiv input[name*="item"]:last').attr('id',item_id_id);
        $('#visitorItemsDiv input[name*="item"]:last').attr('required','required');


        $('#'+item_id_id).val('');


        var quantity_name = "data[VisitorItem]["+scount+"][quantity]";
        var quantity_name_id = "VisitorItem"+scount+"quantity";
        $('#visitorItemsDiv input[name*="quantity"]:last').attr('name',quantity_name);
        $('#visitorItemsDiv input[name*="quantity"]:last').attr('id',quantity_name_id);
        $('#visitorItemsDiv input[name*="quantity"]:last').attr('required','required');


        var quantity_name = "data[VisitorItem]["+scount+"][weight]";
        var quantity_name_id = "VisitorItem"+scount+"weight";
        $('#visitorItemsDiv input[name*="weight"]:last').attr('name',quantity_name);
        $('#visitorItemsDiv input[name*="weight"]:last').attr('id',quantity_name_id);
        $('#visitorItemsDiv input[name*="weight"]:last').attr('required','required');

        //$('#'+quantity_name_id).select2();
        var currency_name = "data[VisitorPrisonerCashItem]["+scount+"][weight_unit]";
        var currency_name_id = "VisitorPrisonerCashItem"+scount+"weight_unit";
        $('#visitorItemsDiv select[name*="weight_unit"]:last').attr('name',currency_name);
        $('#visitorItemsDiv select[name*="weight_unit"]:last').attr('id',currency_name_id);
        $('#visitorItemsDiv select[name*="weight_unit"]:last').attr('required','required');

        $('#VisitorItem'+scount+'weight').val('');
        
        $('#visitorItemsDiv select').select2();

        $('#visitorItemsDiv .visitorItemEntry:last label.error').remove();
               
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.visitorItemEntry:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>