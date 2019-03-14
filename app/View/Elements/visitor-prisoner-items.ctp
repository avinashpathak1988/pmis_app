
<div class="span12" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
    <div style="width: 100%;
    margin-left: 112px;;">
        <div class="scountDiv2" id="visitorPrisonerItemsDiv"> 
                
            <?php 
            $total_scount = 0; $editPhysicalPropertyItemData = '';
            /*debug($weight_units);*/
            if(isset($this->request->data['VisitorPrisonerItem']) && count($this->request->data['VisitorPrisonerItem']) > 0)
            {
                $VisitorPrisonerItem  =   $this->request->data['VisitorPrisonerItem'];
            }
            if(isset($VisitorPrisonerItem)  && count($VisitorPrisonerItem) > 0)
            {
                $i = 0;
                $total_scount = count($VisitorPrisonerItem);
                //echo '<pre>'; print_r($VisitorItem); exit;
                ?>
                <div class="row-fluid">
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Items<span style="color:red;"></span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;"></span></label>
                    </div>
                    <div class="span3">
                    <label class="control-label" style="text-align: left;">Weight<span style="color:red;"></span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Property Type<span style="color:red;"></span></label>
                    </div>
                </div>
                <?php
                foreach($VisitorPrisonerItem as $VisitorItem)
                {
                    //debug($VisitorItem);
                   // debug($this->request->data['VisitorPrisonerItem']);
                    //$VisitorItem = $VisitorItem['VisitorItem'];
                     //if($VisitorItem["item_status"]=="Incoming"){
                    ?>

                    <div class="entry2 visitorItemEntry input-group row-fluid uradioBtn">
                    <div class="span2">
                    <?php echo $this->Form->input('VisitorPrisonerItem.'.$i.'.id', array('type'=>'hidden'))?>

                    <?php echo $this->Form->input('VisitorPrisonerItem.'.$i.'.item_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$propertyItemList, 'empty'=>array(''=>'-- Select Item --'),'onChange'   => 'selectedProperty('.$i.')','required', 'style'=>'width:92%','id'=>'item_id2'));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VisitorPrisonerItem.'.$i.'.quantity',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11 quan','type'=>'text','maxlength'=>'3' ,'placeholder'=>'Quantity','required','value'=>$VisitorItem["quantity"]));?>
                        
                    </div>
                    <div class="span4">
                        <?php echo $this->Form->input('VisitorPrisonerItem.'.$i.'.weight',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','maxlength'=>'10' ,'placeholder'=>'Weight','style'=>'width:50%','required','value'=>$VisitorItem["weight"]));?>
                        <?php echo $this->Form->input('VisitorPrisonerItem.'.$i.'.weight_unit',array('div'=>false,'label'=>false,'class'=>'form-control','style'=>'width:20%','type'=>'select','options'=>$weight_units, 'empty'=>array(''=>'-- Select unit --'),'required', 'style'=>'width:92%'));?>
                        <?php echo $this->Form->input('VisitorPrisonerItem.'.$i.'.is_collected',array('div'=>false,'label'=>false,'type'=>'hidden' ,'required'=>false,'value'=> $VisitorItem["is_collected"]==1?'true':'false'));?>
                    </div>
                     <div class="span2 property_type">
                        <?php 
                            $types =array();
                            $res = $funcall->getPropertyTypeNew($physicalPropertyItem['item_type']);
                            $match = explode(',', $res);
                            if($match[0] == 'allowed'){
                                $types += array('In Use'=>'In Use','In Store'=>'In Store');
                               /* array_push($types, 'In Use'=>'In Use');
                                array_push($types, 'In Store'=>'In Store');*/

                            }else{
                                $types += array($match[1]=>$match[1]);

                                //array_push($types, "'".$match[1]. "'" =>"'". $match[1] ."'");
                            }
                           //debug($types);
                        ?>
                        <?php echo $this->Form->input('VisitorPrisonerItem'.$i.'property_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$types, 'empty'=>array(''=>'-- Select Item --'),'required'=>true, 'style'=>'width:90%'));?>
                    </div>
                    <?php if($i == 0)
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-add2" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                    <span class="icon icon-plus"></span>
                                </button>
                            </span>
                        <?php }
                        else 
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-danger btn-remove2" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                    <span class="icon icon-minus"></span>
                                </button>
                            </span>
                        <?php }?>
                        <span>
                           <?php if( $VisitorItem["is_collected"] ==1 ){ ?>
                                <span style="color:green;">Collected</span>
                           <?php }else{ ?>
                                <span style="color:red;">Not yet collected</span>
                           <?php } ?>
                        </span>
                </div>

                   
                <?php $i++;
           // }
                }
            }
            else 
            {?>
                <div class="row-fluid">
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Items<span style="color:red;"></span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;"></span></label>
                    </div>
                    <div class="span4">
                    <label class="control-label" style="text-align: left;">Weight<span style="color:red;"></span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Property Type<span style="color:red;"></span></label>
                    </div>
                </div>
                <div class="entry2 visitorItemEntry input-group row-fluid uradioBtn">
                    <div class="span2">
                        
                        <?php echo $this->Form->input('VisitorPrisonerItem.0.item_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','data-index'=>0,'options'=>$propertyItemList, 'empty'=>array(''=>'-- Select Item --'),'required'=>false, 'style'=>'width:92%','id'=>'item_id2'));?>
                                    
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VisitorPrisonerItem.0.quantity',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11 quan','type'=>'text', 'placeholder'=>'Quantity','maxlength'=>'3','required'=>false));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VisitorPrisonerItem.0.weight',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','maxlength'=>'10' ,'placeholder'=>'Weight','required'=>false));?>
                        
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VisitorPrisonerItem.0.weight_unit',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$weight_units, 'empty'=>array(''=>'-unit-'),'required'=>false,));?>
                        
                    </div>
                     <div class="span2 property_type">

                        <?php echo $this->Form->input('VisitorPrisonerItem.0.property_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>array(), 'empty'=>array(''=>'-- Select Item --'),'required'=>false, 'style'=>'width:90%'));?>
                    </div>
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-add2" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
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
    $(document).ready(function(){

    $('#item_id2').on('change',function(){
        var id = $(this).attr('data-index');
        selectedProperty(id);
    });

});
$(function()
{
    var scount2 = 0;
    $(document).on('click', '.btn-add2', function(e)
    {
        e.preventDefault();
        $("#visitorPrisonerItemsDiv select").select2("destroy");
        //$("select").select2('val','');
        var controlForm = $('#visitorPrisonerItemsDiv');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('#visitorPrisonerItemsDiv .visitorItemEntry:last');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('.quan').val('');
        controlForm.find('.visitorItemEntry:not(:first) .btn-add2')
            .removeClass('btn-add2').addClass('btn-remove2')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');
        //change name of inputs 
        scount2 = parseInt($('#visitorPrisonerItemsDiv select[name*="item_type"]').length);
        scount2 = scount2-1; 
        //alert(scount2);
        var item_id_name = "data[VisitorPrisonerItem]["+scount2+"][item_type]";
        var item_id_id = "VisitorPrisonerItem"+scount2+"item_type";
        $('#visitorPrisonerItemsDiv select[name*="item_type"]:last').attr('name',item_id_name);
        $('#visitorPrisonerItemsDiv select[name*="item_type"]:last').attr('id',item_id_id);
        $('#visitorPrisonerItemsDiv select[name*="item_type"]:last').attr('required','required');

        

        $('#'+item_id_id).bind('change',function(){
            selectedProperty(scount2);
        });
        $('#'+item_id_id).val('');


        var quantity_name = "data[VisitorPrisonerItem]["+scount2+"][quantity]";
        var quantity_name_id = "VisitorPrisonerItem"+scount2+"quantity";
        $('#visitorPrisonerItemsDiv input[name*="quantity"]:last').attr('name',quantity_name);
        $('#visitorPrisonerItemsDiv input[name*="quantity"]:last').attr('id',quantity_name_id);
        $('#visitorPrisonerItemsDiv input[name*="quantity"]:last').attr('required','required');
        $('#'+quantity_name_id).val('');
        

         var quantity_name = "data[VisitorPrisonerItem]["+scount2+"][weight]";
        var quantity_name_id = "VisitorPrisonerItem"+scount2+"weight";
        $('#visitorPrisonerItemsDiv input[name*="weight"]:last').attr('name',quantity_name);
        $('#visitorPrisonerItemsDiv input[name*="weight"]:last').attr('id',quantity_name_id);
        $('#visitorPrisonerItemsDiv input[name*="weight"]:last').attr('required','required');

        $('#'+quantity_name_id).val('');
        
        var item_id_name = "data[VisitorPrisonerItem]["+scount2+"][weight_unit]";
        var item_id_id = "VisitorPrisonerItem"+scount2+"weight_unit";
        $('#visitorPrisonerItemsDiv select[name*="weight_unit"]:last').attr('name',item_id_name);
        $('#visitorPrisonerItemsDiv select[name*="weight_unit"]:last').attr('id',item_id_id);
        $('#visitorPrisonerItemsDiv select[name*="weight_unit"]:last').attr('required','required');

        $('#'+item_id_id).val('');
        

        var property_type_name = "data[VisitorPrisonerItem]["+scount2+"][property_type]";
        var property_type_id = "VisitorPrisonerItem"+scount2+"property_type";
        $('#visitorPrisonerItemsDiv select[name*="property_type"]:last').attr('name',property_type_name);
        $('#visitorPrisonerItemsDiv select[name*="property_type"]:last').attr('id',property_type_id);
        $('#visitorPrisonerItemsDiv select[name*="property_type"]:last').attr('required','required');

        $('#'+property_type_id).html('');
        $('#'+item_id_id).val('');

        $('#visitorPrisonerItemsDiv select').select2();
        $('#visitorPrisonerItemsDiv .visitorItemEntry:last label.error').remove();
               
    }).on('click', '.btn-remove2', function(e)
    {
        $(this).parents('.visitorItemEntry:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>