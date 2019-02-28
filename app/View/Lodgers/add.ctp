<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Lodger Form</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Lodger List',array('controller'=>'Lodgers','action'=>'/index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Lodger',array('class'=>'form-horizontal'));?>
                    <?php 
                        echo $this->Form->input('id',array('type'=>"hidden"));
                    ?>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Original Prison <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('original_prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','onChange'    => "getPrisoner(this.value,'Lodger')",'required','id'=>'original_prison','title'=>'Please select original prison'));?>
                                </div>
                            </div>
                        </div> 
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prisoner Number <?php echo MANDATORY; ?> :</label>
                                <div class="controls" id="prisonerListDiv">
                                    <?php 
                                    //$prisonerList
                                    echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>array(), 'empty'=>'','required','id'=>'prisoner_id','title'=>'Please select prisoner name','onChange'=>'getescapedPrisoner(this.value)'));?>
                                </div>
                            </div>
                        </div>                           
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Date & Time of Arrival <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('in_date',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Select Date Of Lodging','readonly'=>'readonly','class'=>'form-control mydatetim epicker1 span11','required', 'id'=>'in_date', 'value'=>date(Configure::read('UGANDA-DATE-TIME-FORMAT'))));?>
                                </div>
                            </div>
                        </div>                   
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Destination Prison <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('destination_prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','required','id'=>'destination_prison','title'=>'Please select destination prison'));?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Reason <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('reason',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','required','id'=>'reason','rows'=>2,'title'=>'Please provide reason'));?>
                                </div>
                            </div>
                        </div>  
                        <div class="span6">
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
                    </div>
					
                    <div class="row-fluid escaped_div" id="escaped_div_form">
                    	
                    </div>
                    <div class="row-fluid secondDiv" id="prisonerItemForm" >
                        <h5>Prisoner Physical Items</h5>
                        <?php echo $this->element('lodger-prisoner-items');?>
                    </div>

                    <?php echo $this->element('lodger-prisoner-cash-items');?>
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Submit', array('type'=>'submit','class'=>'btn btn-success','div'=>false,'label'=>false))?>
                    </div>
                    <?php echo $this->Form->end();?>
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
    $biometricSearchAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'dataCheck'));
    $escapedAjax = $this->Html->url(array('controller'=>'Lodgers','action'=>'escapedPrisonerAjax'));
?>
<?php
$getPropertyTypeAjax = $this->Html->url(array('controller'=>'Properties','action'=>'getPropertyType'));
$ajaxPrisonerUrl =  $this->Html->url(array('controller'=>'Dangerous','action'=>'getPrisoner'));
echo $this->Html->scriptBlock("

    
",array('inline'=>false));
?>
<script type="text/javascript">
    $( document ).ready(function() {
        <?php 
        if(isset($this->data['Lodger']['original_prison_id']) && $this->data['Lodger']['original_prison_id']!=''){
            ?>
            getPrisoner(<?php echo $this->data['Lodger']['original_prison_id']; ?>,'Lodger');
            <?php
        }
        ?>
    
});

    function getPrisoner(prison_id,model_name) 
    { 
        if(prison_id != '')
        {
            var strURL = '<?= $ajaxPrisonerUrl ?>';
            $.post(strURL,{'prison_id':prison_id,'model_name':model_name},function(data){  
                $('#prisoner_id').html(data);
                <?php
                if(isset($this->data['Lodger']['prisoner_id']) && $this->data['Lodger']['prisoner_id']!=''){
                    ?>
                    $('#prisoner_id').val(<?php echo $this->data['Lodger']['prisoner_id']; ?>);
                    $('#prisoner_id').select2('val',<?php echo $this->data['Lodger']['prisoner_id']; ?>);
                    <?php
                }
                ?>
                
            });
        }
    }
    function getescapedPrisoner(prisoner_id) 
    { 
        if(prisoner_id != '')
        {
            var strURL = '<?= $escapedAjax ?>';
            $.post(strURL,{'prisoner_id':prisoner_id},function(data){
                $('#escaped_div_form').html(data);
                
            });
        }
    }
    function selectedProperty(id){
       // alert(id);
       if(id == 0){
        var propId = $('#item_id2').val();
        var updateElem = 'LodgerPrisonerItem'+id+'PropertyType';

       }else{
        var propId = $('#LodgerPrisonerItem'+id+'item_type').val();
        var updateElem = 'LodgerPrisonerItem'+id+'property_type';

       }

       if(id != ''){
            $('#'+updateElem).attr('required','required');
       }else{
            $('#'+updateElem).removeAttr('required');
       }
        var url = '<?php echo $getPropertyTypeAjax; ?>';
        $.post(url, { 'id':propId }, function(res) {
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
        $(function(){
      $("#LodgerAddForm").validate({
          ignore: "",
          
      });
  });

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