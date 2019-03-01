<style type="text/css">
    .info-div{
        color:#333;
    }
    .info-div a{
        color:#333;
    }
    .tooltips {
    position: relative;
    display: inline-block;
  
}

.tooltips .tooltiptexts {
    visibility: hidden;
    width: 660px;
    /*background-color: #f2dede;*/
    background-color: #f0f0f0;
    box-shadow: 0 0 7px #ddd;
    border: 1px solid #ddd;
    color: #333333;
    padding: 0 15px 15px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin: 5px;
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 14px;
    font-weight: normal;
}

.tooltips .tooltiptexts h3{
    font-size: 18px;
    color: #000;
}

.tooltips .tooltiptexts::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 2%;
    margin-left: 5px;
    border-width: 13px;
    border-style: solid;
    /*border-color: #f2dede transparent transparent transparent;*/
    border-color: #ddd transparent transparent transparent;
}

.tooltips:hover .tooltiptexts {
    visibility: visible;
    opacity: 1;
}

</style>
<div class="span12" style="margin-top: 10px;">
    <div style="width: 90%;margin: 0 auto;">
        <div class="scountDiv2"> 
            <div class="row-fluid">
                    <div class="span5">
                        <label class="control-label" style="text-align: left;">Food Item<span style="color:red;">*</span></label>
                    </div>
                    
                    <div class="span5">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;">*</span></label>
                    </div>
                    
                </div>
                <div class="entry2 input-group row-fluid uradioBtn">
                    <div class="span5">
                        <?php echo $this->Form->input('CanteenFoodItem.0.food_item',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text', 'empty'=>array(0=>'-- Select Item --'),'required'=>true, 'style'=>'width:90%'));?>
                    </div> 
                    <div class="span5">
                        <?php echo $this->Form->input('CanteenFoodItem.0.quantity',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11','type'=>'text', 'placeholder'=>'Quantity.','required'=>true));?>
                    </div>
                   
                    
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-add" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                            <span class="icon icon-plus"></span>
                        </button>
                    </span>
                </div> 
        
        </div>
    </div>
</div>     
<style type="text/css">
    .scountDiv2 input {
    margin-bottom: 10px;
}
</style>

<script type="text/javascript">

 $(document).ready(function(){
    $('[data-toggle="popover"]').popover();  


});

$(function()
{
    var scount = 0;
    $(document).on('click', '.btn-add', function(e)
    {
        e.preventDefault();
        $("select").select2("destroy");
        //$("select").select2('val','');
        var controlForm = $('.scountDiv2');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('.entry2:last');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('.quan').val('');
        controlForm.find('.entry2:not(:first) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');
        //change name of inputs 
        scount = parseInt($('.scountDiv2 input[name*="food_item"]').length);
        scount=scount - 1;
        var item_name = "data[CanteenFoodItem]["+scount+"][food_item]";
        var item_id = "CanteenFoodItem"+scount+"food_item";
        //var item_id_radio = "PhysicalPropertyItem"+scount+"item_id";
        $('.scountDiv2 input[name*="food_item"]:last').attr('name',item_name);
        $('.scountDiv2 input[name*="food_item"]:last').attr('id',item_id);
        
        $("#"+item_id).val('');
        
        var quantity_name = "data[CanteenFoodItem]["+scount+"][quantity]";
        var quantity_name_id = "CanteenFoodItem"+scount+"quantity";
        $('.scountDiv2 input[name*="quantity"]:last').attr('name',quantity_name);
        $('.scountDiv2 input[name*="quantity"]:last').attr('id',quantity_name_id);
        $("#"+quantity_name_id).val('');
        

        $('.scountDiv2 .entry2:last label.error').remove();

        //$('select').select2({minimumResultsForSearch: Infinity});
        
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.entry2:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>
