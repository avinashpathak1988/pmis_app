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
            

            <?php 
            $total_scount = 0; $editPhysicalPropertyItemData = '';
            
            if(isset($this->request->data['PhysicalPropertyItem']) && count($this->request->data['PhysicalPropertyItem']) > 0)
            {
                $editPhysicalPropertyItemData  =   $this->request->data['PhysicalPropertyItem'];
            }
            if($editPhysicalPropertyItemData != '' && count($editPhysicalPropertyItemData) > 0)
            {
                $i = 0;
                $total_scount = count($editPhysicalPropertyItemData);
                // echo '<pre>'; print_r($this->data); exit;
                ?>
                
                <div class="row-fluid">
                    <div class="span3">
                        <label class="control-label" style="text-align: left;">Property Name <span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Bag No.<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;">*</span></label>
                    </div>
                    <div class="span3">
                    <label class="control-label" style="text-align: left;">Item Description<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2 property_type">
                        <label class="control-label" style="text-align: left;">Property Type <span style="color:red;">*</span></label>
                    </div>
                </div>
                <?php
                foreach($editPhysicalPropertyItemData as $physicalPropertyItem)
                {
                    // /debug($physicalPropertyItem);
                    //$physicalPropertyItem = $physicalPropertyItem['PhysicalPropertyItem'];
                     //if($physicalPropertyItem["item_status"]=="Incoming"){
                    ?>

                    <div class="entry2 input-group row-fluid uradioBtn">
                    <div class="span3">
                        <?php echo $this->Form->input('PhysicalPropertyItem.'.$i.'.item_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$propertyItemList,'onChange'   => 'selectedProperty('.$i.')', 'empty'=>array(''=>'-- Select Item --'),'required'=>false, 'style'=>'width:90%'));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('PhysicalPropertyItem.'.$i.'.bag_no',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11 bagnumber','type'=>'text', 'placeholder'=>'Bag No.','required'=>false,'value'=>$physicalPropertyItem["bag_no"]));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('PhysicalPropertyItem.'.$i.'.quantity',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11 quan','type'=>'text', 'placeholder'=>'Quantity','required'=>false,'value'=>$physicalPropertyItem["quantity"]));?>
                    </div>
                    <div class="span3">
                        <?php //echo $this->Form->input('PhysicalPropertyItem.'.$i.'.property_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$propertypropertytype, 'empty'=>array(''=>'-- Select Property Type --'),'required'=>false, 'style'=>'width:90%'));?>
                        <?php echo $this->Form->input('PhysicalPropertyItem.'.$i.'.description',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Description','required'=>false,'value'=>$physicalPropertyItem["description"]));?>
                    </div>
                    <div class="span2 property_type">
                        <?php 
                            $types =array();
                            $res = $funcall->getPropertyTypeNew($physicalPropertyItem['item_id']);
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

                        <?php echo $this->Form->input('PhysicalPropertyItem.'.$i.'.property_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$types, 'empty'=>array(''=>'-- Select Item --'),'required'=>true, 'style'=>'width:90%'));?>
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
            {
                ?>
                <div class="row-fluid">
                    <div class="span3">
                        <label class="control-label" style="text-align: left;">Property Name<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Bag No.<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;">*</span></label>
                    </div>
                    <div class="span3">
                    <label class="control-label" style="text-align: left;">Item Description<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2 property_type">
                        <label class="control-label" style="text-align: left;">Property Type <span style="color:red;">*</span></label>
                    </div>
                </div>
                <div class="entry2 input-group row-fluid uradioBtn">
                    <?php 
                    if($current_usertype_id == Configure::read('GATEKEEPER_USERTYPE'))
                    {
                        echo $this->Form->input('PhysicalPropertyItem.0.status',array('div'=>false,'label'=>false,'type'=>'hidden','required'=>false, 'value'=> 'G-Draft'));
                    }
                    ?>
                    <div class="span3">
                        <?php echo $this->Form->input('PhysicalPropertyItem.0.item_id',array('div'=>false,'label'=>false,'class'=>'form-control itemSelected','data-index'=>0,'type'=>'select','options'=>$propertyItemList, 'empty'=>array(0=>'-- Select Item --'),'required'=>false, 'style'=>'width:90%'));?>
                    </div> 
                    <div class="span2">
                        <?php echo $this->Form->input('PhysicalPropertyItem.0.bag_no',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11 bagnumber','type'=>'text', 'placeholder'=>'Bag No.','required'=>false,"onchange"=>"check_val(this)"));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('PhysicalPropertyItem.0.quantity',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11 quan','type'=>'text', 'placeholder'=>'Quantity','required'=>false));?>
                    </div>
                    <div class="span3">
                        <?php //echo $this->Form->input('PhysicalPropertyItem.0.property_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$propertypropertytype, 'empty'=>array(0=>'-- Select Property Type --'),'required'=>false, 'style'=>'width:90%'));?>
                        <?php echo $this->Form->input('PhysicalPropertyItem.0.description',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Description','required'=>false));?>
                    </div>
                    <div class="span2 property_type">
                        <?php echo $this->Form->input('PhysicalPropertyItem.0.property_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>array(), 'empty'=>array(''=>''),'required'=>true, 'style'=>'width:90%'));?>
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
<?php 
    $ajaxUrlCheckPropertyProhibition = $this->Html->url(array('controller'=>'SchoolPrograms','action'=>'indexAjax'));
    
?>
<script type="text/javascript">
 // function check_val(e) { 
 //     $('.bagnumber').each(function(i, ele){
 //            if(ele != e && ele.value == e.value){
 //                   //Throw an error Here <---
 //                  alert(e.value+' bag no. already used');   
 //                  e.value="";
 //                  e.focus();
 //            }
 //      })
 //    }

 $(document).ready(function(){
    $('[data-toggle="popover"]').popover();  

    $('.itemSelected').on('change',function(){
        var id = $(this).attr('data-index');
        selectedProperty(id);
    });

});
 function readURL(input) {
   // var dataId = $(this).attr('data-id');
   // alert(dataId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img_prev_0')
            .attr('src', e.target.result)
            .width(100);
            $('#img_prev_0').closest('.prevImage_0').attr('href', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
    else {
      var img = input.value;
        $('#img_prev_0').attr('src',img).width(100);
    }
    $('#prevImage_0').hide();
    $('#img_prev_0').show();
    $("#x").show().css("margin-right","10px");
}
  $("#x").click(function() {
    $('#photo').val("");
    $("#img_prev_0").attr("src",'');
    $('#img_prev_0').hide();
    $("#x").hide();  
    $('span.filename').html('');
    $('#prevImage_0').show();
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
        scount = parseInt($('.scountDiv2 select[name*="item_id"]').length);
        scount = scount-1; 
        var item_id_name = "data[PhysicalPropertyItem]["+scount+"][item_id]";
        var item_id_id = "PhysicalPropertyItem"+scount+"ItemId";
        var status_name = "data[PhysicalPropertyItem]["+scount+"][status]";
        //var item_id_radio = "PhysicalPropertyItem"+scount+"item_id";
        $('.scountDiv2 input[name*="status"]:last').attr('name',status_name);
        $('.scountDiv3 input[name*="status"]:last').val('G-Draft');
        $('.scountDiv2 select[name*="item_id"]:last').attr('name',item_id_name);
        $('.scountDiv2 select[name*="item_id"]:last').attr('data-index',scount);
        $('.scountDiv2 select[name*="item_id"]:last').attr('id',item_id_id);
        $('#'+item_id_id).bind('change',function(){
            selectedProperty(scount);
        });
        $('#'+item_id_id).select2();
        //$('#'+item_id_id).select2();
        //$('#'+item_id_id).select2('val','');

        //$('.radio').attr('id',item_id_id);

        var bag_no_name = "data[PhysicalPropertyItem]["+scount+"][bag_no]";
        var bag_no_id = "PhysicalPropertyItem"+scount+"bag_no";
        $('.scountDiv2 input[name*="bag_no"]:last').attr('name',bag_no_name);
        $('.scountDiv2 input[name*="bag_no"]:last').attr('id',bag_no_id);
        $('#'+bag_no_id).val('');

        var quantity_name = "data[PhysicalPropertyItem]["+scount+"][quantity]";
        var quantity_name_id = "PhysicalPropertyItem"+scount+"quantity";
        $('.scountDiv2 input[name*="quantity"]:last').attr('name',quantity_name);
        $('.scountDiv2 input[name*="quantity"]:last').attr('id',quantity_name_id);
        

        var description = "data[PhysicalPropertyItem]["+scount+"][description]";
        var description_name_id = "PhysicalPropertyItem"+scount+"description";
        $('.scountDiv2 input[name*="description"]:last').attr('name',description);
        $('.scountDiv2 input[name*="description"]:last').attr('id',description_name_id);
        $('#'+description_name_id).val('');

        var property_type_name = "data[PhysicalPropertyItem]["+scount+"][property_type]";
        var property_type_id = "PhysicalPropertyItem"+scount+"PropertyType";
        $('.scountDiv2 select[name*="property_type"]:last').attr('name',property_type_name);
        $('.scountDiv2 select[name*="property_type"]:last').attr('id',property_type_id);
        $('#'+property_type_id).html('');
        $('#'+property_type_id).removeAttr('disabled');
        $('#'+property_type_id).removeAttr('readonly');

        var property_photo_name = "data[PhysicalPropertyItem]["+scount+"][photo]";
        $('.scountDiv2 input[name*="photo"]:last').attr('name',property_photo_name);


        //$('.scountDiv2 select[name*="item_id"]:last option:selected').removeAttr("selected");

        $('.scountDiv2 .entry2:last label.error').remove();

        //$('select').select2({minimumResultsForSearch: Infinity});
        $('#region').select2('reload');
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.entry2:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>
