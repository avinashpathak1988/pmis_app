<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
.prison-prop-3 th{
        background: #854545;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
    <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Prisoner Property</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php echo $this->Html->link('Property List',array('controller'=>'properties','action'=>'index/'.$prisoner_uuid.'#physical_property'),array('escape'=>false,'class'=>'btn btn-success btn-mini', 'style'=>'float: right;')); ?>
                        <ul class="nav nav-tabs">
                           
                            <li><a href="#physical_property" id="physical_property_tab">Physical Property</a></li>
                            <!-- <li><a href="#cash_property" id="cash_property_tab">Cash</a></li> -->
                            
                            <!-- <li class="pull-right controls"> -->
                            <!-- <li class="controls pull-right">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li> -->
                        </ul>
                        <div class="tabscontent">
                            <div id="physical_property">
                                <?php //if($isAccess == 1){?>
                                    <?php echo $this->Form->create('PhysicalProperty',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <div class="row" style="padding-bottom: 14px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date Time<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                <?php
                                                $property_date_time= date('d-m-Y H:i');
                                                if(isset($this->data["PhysicalProperty"]["property_date_time"])){
                                                        $property_date_time=date("d-m-Y H:i", strtotime($this->data["PhysicalProperty"]["property_date_time"]));
                                                        
                                                    }
                                                ?>
                                                
                                                <?php echo $this->Form->input('property_date_time',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Date Time','required','readonly'=>'readonly','id'=>'property_date_time','value'=>$property_date_time));?>
                                                </div>
                                            </div>
										<!-- 	 <div class="control-group">
                                                <label class="control-label">Description<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('description',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'description','placeholder'=>'Enter Description', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>  -->
                                            <div class="control-group">
                                                <label class="control-label">Source<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('source',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','placeholder'=>'Enter Source','id'=>'source', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div> 
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Property Received on<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                <?php
                                                $property_received_date= '';
                                                if(isset($this->data["PhysicalProperty"]["property_received_date"])){
                                                        $property_received_date=date("d-m-Y H:i", strtotime($this->data["PhysicalProperty"]["property_received_date"]));
                                                        
                                                    }
                                                ?>
                                                <?php echo $this->Form->input('property_received_date',array('div'=>false,'label'=>false,'class'=>'form-control datetimepicker span11','type'=>'text', 'placeholder'=>'Enter Property Received Date','required','readonly'=>'readonly','id'=>'property_received_date','value'=>$property_received_date));?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">NOK:</label>
                                                <div class="controls">
                                                    <?php 
                                                    $nokList = array();
                                                    if(isset($prisonerKin) && is_array($prisonerKin) && count($prisonerKin)>0){
                                                        foreach ($prisonerKin as $key => $value) {
                                                            $nokList[$value['PrisonerKinDetail']['id']] = $value['PrisonerKinDetail']['first_name']." ".$value['PrisonerKinDetail']['middle_name']." ".$value['PrisonerKinDetail']['last_name'];
                                                        }
                                                    }
                                                    echo $this->Form->input('property_nok_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$nokList, 'empty'=>array(''=>'-- Select NOK --'),'required'=>false, 'style'=>'width:90%'));?>
                                                    <?php //echo $this->Form->input('property_nok',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control span11','value' => $nok,'required','readonly'=>'readonly','placeholder'=>'Name of Kin','id'=>'property_nok', 'cols'=>30, 'rows'=>1));?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
                                                <div class="controls">
                                                    <?php 
                                                    echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in','onclick'=>"start()"));
                                                    ?>
                                                    <?php echo $this->Form->input('is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','required','title'=>"Please verifiy from biometric","id"=>"link_biometric_verified"));?>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label">Attachment:</label>
                                                <div class="controls">
                                                <?php echo $this->Form->input('photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'attachment','data-id'=>'0', 'onchange'=>'readURL(this);', 'required'=>false));?>
                                                </div>
                                                <div id='"prevImage_0' class="">
                                                <?php $is_photo = '';
                                                    if(isset($this->request->data["PhysicalProperty"]["photo"]))
                                                    {
                                                        $is_photo = 1;?>
                                                       <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/physicalitems/<?php echo $this->request->data["PhysicalProperty"]["photo"];?>" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/physicalitems/<?php echo $this->request->data["PhysicalProperty"]["photo"];?>" alt="" width="150px" height="150px"></a>
                                                    <?php }?>
                                                </div>
                                                <span id="previewPane" class="" style="margin-left: 200px;">
                                                    <a class="example-image-link prevImage_0" href="" data-lightbox="example-set"><img id="img_prev_0" src="#" class="img_prev_0" alt="" /></a>
                                                    <span id="x" class="remove_img" style="color:red">[X] remove</span>
                                                </span>
                                        </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <?php echo $this->element('property-items');?> 
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php
                                            if($isEdit==0){
                                                echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true,'id'=>"btnsubmit"));
                                            }
                                            else{
                                               echo $this->Form->button('Update', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'id'=>"btnsubmitupdate")); 
                                            }

                                           // echo $this->Form->button('Cancel', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure to save?')"));
                                        ?>&nbsp;&nbsp;
        <a class="btn btn-danger" href="../index/<?php echo $prisoner_uuid?>#physical_property">Cancel</a> 
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php //}?>
                                <div class="table-responsive" id="checkupListingDiv">

                                </div>
                            </div> 
                            <div id="cash_property">
                                <?php //if($isAccess == 1){?>
                                    <?php //echo $this->Form->create('PhysicalProperty',array('class'=>'form-horizontal','type'=>'file'));?>
                                    <?php echo $this->Form->create('PhysicalProperty',array('id'=>'cashproperty','class'=>'form-horizontal','url' => '/properties/cashproperty/'.$prisoner_uuid));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <div class="row" style="padding-bottom: 14px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date Time<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                
                                                <?php echo $this->Form->input('property_date_time',array('div'=>false,'label'=>false,'class'=>'form-control datetimepicker span11','type'=>'text', 'placeholder'=>'Enter Date Time','required','readonly'=>'readonly','id'=>'property_date_time'));?>
                                                </div>
                                            </div>
                                            
											<div class="control-group">
                                                <label class="control-label">Description<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('description',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'description', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Source<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('source',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'source', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div> 
                                        <?php echo $this->element('cash-items');?> 
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure to save?')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php //}?>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="biometricModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header" style="height: 40px;">
        <h5 class="modal-title" id="exampleModalLabel" style="float: left;">Biometric Search</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" align="center">
        <?php echo $this->Html->image('finger.gif', array('alt' => '', 'border' => '0')); ?>
        <br />
        <p>Please press finger on biometric</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="testttt" onclick="stop()" class="btn btn-danger" data-dismiss="modal">Stop</button>
      </div>
    </div>
  </div>
</div>
<?php
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
echo $this->Html->scriptBlock("
    var tab_param = '';
    var tabs;
    jQuery(function($) {
        $('#PhysicalPropertyPropertyNok').select2();
        //$('select').select2();
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });
    });
   
",array('inline'=>false));
?> 

<?php
    $biometricSearchAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'dataCheck'));
    $getPropertyTypeAjax = $this->Html->url(array('controller'=>'Properties','action'=>'getPropertyType'));

?>
<script type="text/javascript">
    var timer = null;

    function start() {
        $('#biometricModal').modal('show');
        tick();
        timer = setTimeout(start, 1000);  
    };

    function startOther() {
        $('#biometricModal').modal('show');
        timer = setTimeout(stopOther, 1000);  
    };

    function tick() {
        $("#link_biometric_button_in").html("Searching...");
        var url = '<?php echo $biometricSearchAjax; ?>';
        $.ajax({
            type: 'POST',
            url: url,
            success: function (res) {
                if(res.trim()!='FAIL'){
                    startOther();
                    $("#link_biometric_button_in").html("Verified");
                    $("#link_biometric_button_in").attr("onclick","");
                    $("#link_biometric_verified").val(1);
                    $("#link_biometric_button_in").addClass("btn btn-success");
                }
            },
            async:false
        });
    };

    function stop() {
        $('#biometricModal').modal('hide');
        $("#link_biometric_button_in").html("Get Punch");
        $("#link_biometric_button_in").addClass("btn btn-warning");

        clearTimeout(timer);
    };

    function stopOther() {
        $('#biometricModal').modal('hide');
        clearTimeout(timer);
    };
</script>
<script>


$(document).ready(function(){
    
    $('.remove_img').click(function(){
        $('#attachment').val('')
    });
    $('#propertyList').click(function(){
        var uuid = $(this).attr('data-href');
        window.location =location.origin + '/uganda_dev/properties/index/'+uuid +'#physical_property';
    });
    $('#btnsubmit').click(function(){
        if($("#PhysicalPropertyPropertyForm").valid()){
            if( !confirm('Are you sure to save?')) {
                            return false;
            }
        }
    });
    $('#btnsubmitupdate').click(function(){
        if($("#PhysicalPropertyPropertyForm").valid()){
            if( !confirm('Are you sure to update?')) {
                            return false;
            }
        }
    });
    $('#PhysicalPropertyItem0ItemId').on('change',function(){
            selectedProperty(0);
        });
    
        showCommonHeader();
});
     $('.datetimepicker').datetimepicker({format: 'dd-mm-yyyy hh:ii:ss',autoclose: true});
     //common header
    function showCommonHeader(){
        var prisoner_id = "<?php echo $prisoner_id; ?>";
        console.log(prisoner_id);  
        var uuid        = "<?php echo $prisoner_uuid; ?>";
        var url         = "<?php echo $commonHeaderUrl; ?>";
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
           
            if (res) {
                $('#commonheader').html(res);
            }
        }); 
    }


   function selectedProperty(id){
   // alert(id);
    var propId = $('#PhysicalPropertyItem'+id+'ItemId').val();
    var updateElem = 'PhysicalPropertyItem'+id+'PropertyType';
    var url = '<?php echo $getPropertyTypeAjax; ?>';
    $.post(url, { 'id':propId }, function(res) {
        console.log(res);
            $('#'+updateElem).html('');
             var match = res.split(',');
            var opt = '';
            if(res == 'allowed'){
                opt += '<option value="In Use">In Use</option>';
                opt += '<option value="In Store">In Store</option>';
                 $('#'+updateElem).html(opt);
                $('#'+updateElem).val('In Use');
                $('#'+updateElem).change();
                $('#'+updateElem).removeAttr('readonly');
                $('#'+updateElem).removeAttr('disabled');

            }else if(match[0] == 'prohibited'){
                opt += '<option value="'+match[1]+'">'+match[1]+'</option>';
                $('#'+updateElem).html(opt);
                $('#'+updateElem).val(match[1]);
                $('#'+updateElem).change();
                $('#'+updateElem).attr('readonly','readonly');
                $('#'+updateElem).attr('disabled','disabled');


            }else{
                 $('#'+updateElem).html(opt);
            }

        });
    }
</script>
<script type="text/javascript">
$(function(){
    var url = "<?php echo $this->Html->url(array('controller'=>'Properties','action'=>'bagnoExistproperty'))?>";
    var url1 = "<?php echo $this->Html->url(array('controller'=>'Properties','action'=>'bagnoExistproperty1'))?>";
    $("#cashproperty").validate({
     
      ignore: "",
            rules: {  
                'data[PhysicalProperty][property_date_time]': {
                    required: true,
                },
                'data[PhysicalProperty][source]': {
                    required: true,
                    maxlength: 146,
                },
                'data[PhysicalProperty][description]': {
                    required: true,
                },
                'data[CashItem][0][amount]': {
                    required: true,
                },
                'data[CashItem][0][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][1][amount]': {
                    required: true,
                },
                'data[CashItem][1][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][2][amount]': {
                    required: true,
                },
                'data[CashItem][2][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][3][amount]': {
                    required: true,
                },
                'data[CashItem][3][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][4][amount]': {
                    required: true,
                },
                'data[CashItem][4][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][5][amount]': {
                    required: true,
                },
                'data[CashItem][5][currency_id]': {
                    required: true,
                },
                
                'data[CashItem][6][amount]': {
                    required: true,
                },
                'data[CashItem][6][currency_id]': {
                    required: true,
                },
                
                'data[CashItem][7][amount]': {
                    required: true,
                },
                'data[CashItem][7][currency_id]': {
                    required: true,
                },
                


                'data[CashItem][8][amount]': {
                    required: true,
                },
                'data[CashItem][8][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][9][amount]': {
                    required: true,
                },
                'data[CashItem][9][currency_id]': {
                    required: true,
                },
                
                
            },
            messages: {
                'data[PhysicalProperty][property_date_time]': {
                    required: "Please choose datetime.",
                },
                'data[PhysicalProperty][source]': {
                    required: "Please enter source.",
                    maxlength: "Please enter no more than 146 characters.",
                },
                'data[PhysicalProperty][description]': {
                    required: "Please enter description.",
                },
                'data[CashItem][0][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][0][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][1][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][1][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][2][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][2][currency_id]': {
                    required: "Please select currency.",
                },
                
                'data[CashItem][3][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][3][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][4][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][4][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][5][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][5][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][6][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][6][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][7][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][7][currency_id]': {
                    required: "Please select currency.",
                },


                'data[CashItem][8][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][8][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][9][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][9][currency_id]': {
                    required: "Please select currency.",
                },
                

            },
               
    });
    $("#PhysicalPropertyPropertyForm").validate({
     
      ignore: ".ignore, .select2-input",
            rules: {  
                'data[PhysicalProperty][property_date_time]': {
                    required: true,
                },
                'data[PhysicalProperty][source]': {
                    required: true,
                    maxlength: 150,
                },
                'data[PhysicalProperty][description]': {
                    required: true,
                    maxlength: 150,
                },
                'data[PhysicalPropertyItem][0][item_id]': {
                    valueNotEquals: "0",
                },
                'data[PhysicalPropertyItem][0][bag_no]': {
                    required: true,
                    maxlength:3,
                    minlength:3,
                    remote:
                    {
                      url: url,
                      type: "POST",
                      data: {
                        bagNo: function() {
                          return $( "#PhysicalPropertyItem0BagNo" ).val();
                        },
                        propertyid: function() {
                          return $( "#PhysicalPropertyId" ).val();
                        },
                        
                      },
                    },
                },
                'data[PhysicalPropertyItem][0][quantity]': {
                    required: true,
                    maxlength:2,
                    minlength:1,
                },
                'data[PhysicalPropertyItem][0][description]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][0][property_type]': {
                    valueNotEquals: "0",
                },

                'data[PhysicalPropertyItem][1][item_id]': {
                    valueNotEquals: "0",
                },
                'data[PhysicalPropertyItem][1][bag_no]': {
                    required: true,
                    maxlength:3,
                    minlength:3,
                    remote:
                    {
                      url: url,
                      type: "POST",
                      data: {
                        bagNo: function() {
                          return $( "#PhysicalPropertyItem1bag_no" ).val();
                        },
                        propertyid: function() {
                          return $( "#PhysicalPropertyId" ).val();
                        },
                        
                      },
                    },
                    
                },
                'data[PhysicalPropertyItem][1][quantity]': {
                    required: true,
                    maxlength:2,
                    minlength:1,
                },
                'data[PhysicalPropertyItem][1][description]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][1][property_type]': {
                    valueNotEquals: "0",
                },

                'data[PhysicalPropertyItem][2][item_id]': {
                    valueNotEquals: "0",
                },
                'data[PhysicalPropertyItem][2][bag_no]': {
                    required: true,
                    maxlength:3,
                    minlength:3,
                    remote:
                    {
                      url: url,
                      type: "POST",
                      data: {
                        bagNo: function() {
                          return $( "#PhysicalPropertyItem2bag_no" ).val();
                        },
                        propertyid: function() {
                          return $( "#PhysicalPropertyId" ).val();
                        },
                        
                      },
                    },
                },
                'data[PhysicalPropertyItem][2][quantity]': {
                    required: true,
                    maxlength:2,
                    minlength:1,
                },
                'data[PhysicalPropertyItem][2][description]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][2][property_type]': {
                    valueNotEquals: "0",
                },

                'data[PhysicalPropertyItem][3][item_id]': {
                    valueNotEquals: "0",
                },
                'data[PhysicalPropertyItem][3][bag_no]': {
                    required: true,
                    maxlength:3,
                    minlength:3,
                    remote:
                    {
                      url: url,
                      type: "POST",
                      data: {
                        bagNo: function() {
                          return $( "#PhysicalPropertyItem3bag_no" ).val();
                        },
                        propertyid: function() {
                          return $( "#PhysicalPropertyId" ).val();
                        },
                        
                      },
                    },
                },
                'data[PhysicalPropertyItem][3][quantity]': {
                    required: true,
                    maxlength:2,
                    minlength:1,
                },
                'data[PhysicalPropertyItem][3][property_type]': {
                    valueNotEquals: "0",
                },

                'data[PhysicalPropertyItem][4][item_id]': {
                    valueNotEquals: "0",
                },
                'data[PhysicalPropertyItem][4][bag_no]': {
                    required: true,
                    remote:
                    {
                      url: url,
                      type: "POST",
                      data: {
                        bagNo: function() {
                          return $( "#PhysicalPropertyItem4bag_no" ).val();
                        },
                        propertyid: function() {
                          return $( "#PhysicalPropertyId" ).val();
                        },
                        
                      },
                    },
                },
                'data[PhysicalPropertyItem][4][quantity]': {
                    required: true,
                    maxlength:2,
                    minlength:1,
                },
                'data[PhysicalPropertyItem][4][property_type]': {
                    valueNotEquals: "0",
                },

                'data[PhysicalPropertyItem][5][item_id]': {
                    valueNotEquals: "0",
                },
                'data[PhysicalPropertyItem][5][bag_no]': {
                    required: true,
                    maxlength:3,
                    minlength:3, 
                    remote:
                    {
                      url: url,
                      type: "POST",
                      data: {
                        bagNo: function() {
                          return $( "#PhysicalPropertyItem5bag_no" ).val();
                        },
                        propertyid: function() {
                          return $( "#PhysicalPropertyId" ).val();
                        },
                        
                      },
                    },
                },
                'data[PhysicalPropertyItem][5][quantity]': {
                    required: true,
                    maxlength:2,
                    minlength:1,
                },
                'data[PhysicalPropertyItem][5][property_type]': {
                    valueNotEquals: "0",
                },

                'data[PhysicalPropertyItem][6][item_id]': {
                    valueNotEquals: "0",
                },
                'data[PhysicalPropertyItem][6][bag_no]': {
                    required: true,
                    remote:
                    {
                      url: url,
                      type: "POST",
                      data: {
                        bagNo: function() {
                          return $( "#PhysicalPropertyItem6bag_no" ).val();
                        },
                        propertyid: function() {
                          return $( "#PhysicalPropertyId" ).val();
                        },
                        
                      },
                    },
                },
                'data[PhysicalPropertyItem][6][quantity]': {
                    required: true,
                    maxlength:2,
                    minlength:1,
                },
                'data[PhysicalPropertyItem][6][property_type]': {
                    valueNotEquals: "0",
                },

                'data[PhysicalPropertyItem][7][item_id]': {
                    valueNotEquals: "0",
                },
                'data[PhysicalPropertyItem][7][bag_no]': {
                    required: true,
                    maxlength:3,
                    minlength:3,
                    remote:
                    {
                      url: url,
                      type: "POST",
                      data: {
                        bagNo: function() {
                          return $( "#PhysicalPropertyItem7bag_no" ).val();
                        },
                        propertyid: function() {
                          return $( "#PhysicalPropertyId" ).val();
                        },
                        
                      },
                    },
                },
                'data[PhysicalPropertyItem][7][quantity]': {
                    required: true,
                    maxlength:2,
                    minlength:1,
                },
                'data[PhysicalPropertyItem][7][property_type]': {
                    valueNotEquals: "0",
                },


                'data[PhysicalPropertyItem][8][item_id]': {
                    valueNotEquals: "0",
                },
                'data[PhysicalPropertyItem][8][bag_no]': {
                    required: true,
                    maxlength:3,
                    minlength:3,
                    remote:
                    {
                      url: url,
                      type: "POST",
                      data: {
                        bagNo: function() {
                          return $( "#PhysicalPropertyItem8bag_no" ).val();
                        },
                        propertyid: function() {
                          return $( "#PhysicalPropertyId" ).val();
                        },
                        
                      },
                    },
                },
                'data[PhysicalPropertyItem][8][quantity]': {
                    required: true,
                    maxlength:2,
                    minlength:1,
                },
                'data[PhysicalPropertyItem][8][property_type]': {
                    valueNotEquals: "0",
                },

                'data[PhysicalPropertyItem][9][item_id]': {
                    valueNotEquals: "0",
                },
                'data[PhysicalPropertyItem][9][bag_no]': {
                    required: true,
                    maxlength:3,
                    minlength:3,
                    remote:
                    {
                      url: url,
                      type: "POST",
                      data: {
                        bagNo: function() {
                          return $( "#PhysicalPropertyItem9bag_no" ).val();
                        },
                        propertyid: function() {
                          return $( "#PhysicalPropertyId" ).val();
                        },
                        
                      },
                    },
                },
                'data[PhysicalPropertyItem][9][quantity]': {
                    required: true,
                    maxlength:2,
                    minlength:1,
                },
                'data[PhysicalPropertyItem][9][property_type]': {
                    valueNotEquals: "0",
                },
                
            },
            messages: {
                'data[PhysicalProperty][property_date_time]': {
                    required: "Please choose datetime.",
                },
                'data[PhysicalProperty][source]': {
                    required: "Please enter source.",
                    maxlength: "Please enter no more than 150 characters.",
                },
                'data[PhysicalProperty][description]': {
                    required: "Please enter description.",
                    maxlength: "Please enter no more than 150 characters.",
                },
                'data[PhysicalPropertyItem][0][item_id]': {
                    valueNotEquals: "Please select item.",
                },
                'data[PhysicalPropertyItem][0][bag_no]': {
                    required: "Please enter bag no.",
                    remote: "Bag no. already exist.",
                },
                'data[PhysicalPropertyItem][0][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][0][description]': {
                    required: "Please Enter the item description",
                },
                'data[PhysicalPropertyItem][0][property_type]': {
                    valueNotEquals: "Please select property type.",
                },

                'data[PhysicalPropertyItem][1][item_id]': {
                    valueNotEquals: "Please select item.",
                },
                'data[PhysicalPropertyItem][1][bag_no]': {
                    required: "Please enter bag no.",
                    remote: "Bag no. already exist.",
                },
                'data[PhysicalPropertyItem][1][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][1][description]': {
                    required: "Please Enter the item description",
                },
                'data[PhysicalPropertyItem][1][property_type]': {
                    valueNotEquals: "Please select property type.",
                },

                'data[PhysicalPropertyItem][2][item_id]': {
                    valueNotEquals: "Please select item.",
                },
                'data[PhysicalPropertyItem][2][bag_no]': {
                    required: "Please enter bag no.",
                    remote: "Bag no. already exist.",
                },
                'data[PhysicalPropertyItem][2][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][2][description]': {
                    required: "Please Enter the item description",
                },
                'data[PhysicalPropertyItem][2][property_type]': {
                    valueNotEquals: "Please select property type.",
                },

                'data[PhysicalPropertyItem][3][item_id]': {
                    valueNotEquals: "Please select item.",
                },
                'data[PhysicalPropertyItem][3][bag_no]': {
                    required: "Please enter bag no.",
                    remote: "Bag no. already exist.",
                },
                'data[PhysicalPropertyItem][3][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][3][property_type]': {
                    valueNotEquals: "Please select property type.",
                },

                'data[PhysicalPropertyItem][4][item_id]': {
                    valueNotEquals: "Please select item.",
                },
                'data[PhysicalPropertyItem][4][bag_no]': {
                    required: "Please enter bag no.",
                    remote: "Bag no. already exist.",
                },
                'data[PhysicalPropertyItem][4][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][4][property_type]': {
                    valueNotEquals: "Please select property type.",
                },

                'data[PhysicalPropertyItem][5][item_id]': {
                    valueNotEquals: "Please select item.",
                },
                'data[PhysicalPropertyItem][5][bag_no]': {
                    required: "Please enter bag no.",
                    remote: "Bag no. already exist.",
                },
                'data[PhysicalPropertyItem][5][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][5][property_type]': {
                    valueNotEquals: "Please select property type.",
                },

                'data[PhysicalPropertyItem][6][item_id]': {
                    valueNotEquals: "Please select item.",
                },
                'data[PhysicalPropertyItem][6][bag_no]': {
                    required: "Please enter bag no.",
                    remote: "Bag no. already exist.",
                },
                'data[PhysicalPropertyItem][6][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][6][property_type]': {
                    valueNotEquals: "Please select property type.",
                },

                'data[PhysicalPropertyItem][7][item_id]': {
                    valueNotEquals: "Please select item.",
                },
                'data[PhysicalPropertyItem][7][bag_no]': {
                    required: "Please enter bag no.",
                    remote: "Bag no. already exist.",
                },
                'data[PhysicalPropertyItem][7][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][7][property_type]': {
                    valueNotEquals: "Please select property type.",
                },

                'data[PhysicalPropertyItem][8][item_id]': {
                    valueNotEquals: "Please select item.",
                },
                'data[PhysicalPropertyItem][8][bag_no]': {
                    required: "Please enter bag no.",
                    remote: "Bag no. already exist.",
                },
                'data[PhysicalPropertyItem][8][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][8][property_type]': {
                    valueNotEquals: "Please select property type.",
                },

                'data[PhysicalPropertyItem][9][item_id]': {
                    valueNotEquals: "Please select item.",
                },
                'data[PhysicalPropertyItem][9][bag_no]': {
                    required: "Please enter bag no.",
                },
                'data[PhysicalPropertyItem][9][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][9][property_type]': {
                    valueNotEquals: "Please select property type.",
                },

            },
               
    });
});


</script>