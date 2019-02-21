<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row-fluid">
    <div class="span5">
        <ul class="pagination">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#listingDiv',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Properties',
                                                    'action'                => 'indexAjax',
                                                    'prisoner_uuid'          => $prisoner_uuid,
                                                    // 'registered_user_id'             => $registered_user_id,
                                                    // 'search_tag_id'             => $search_tag_id,
                                                    // 'search_uploaded_date'             => $search_uploaded_date,
                                                  )
              ));         
              echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
              echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Js->writeBuffer();
          ?>
        </ul>
    </div>
    <div class="span7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "indexAjax/prisoner_uuid:$prisoner_uuid";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    // echo '&nbsp;&nbsp;';
    // echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    </div>
</div>
<?php
    }
?>                    
<table class="table table-bordered data-table table-responsive" id="physicalpropertyidtbl">
    <thead>
        <tr>
          <th style="text-align: left;">SL#</th>
          <th style="text-align: left;">
          <?php                 
                echo $this->Paginator->sort('PhysicalProperty.property_date_time','Datetime',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Properties','action' => 'indexAjax','prisoner_uuid'=> $prisoner_uuid)));
          ?>
          </th>
          <th style="text-align: left;">
            
            <?php                 
                echo $this->Paginator->sort('PhysicalProperty.description','Description',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Properties','action' => 'indexAjax','prisoner_uuid'=> $prisoner_uuid)));
            ?>
          </th>
          <th style="text-align: left;">
          <?php                 
                echo $this->Paginator->sort('PhysicalProperty.source','Source',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Properties','action' => 'indexAjax','prisoner_uuid'=> $prisoner_uuid)));
            ?>
          
          </th>
          
                
             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    $j=0;

    foreach($datas as $data){
      $j++;
      $physical_id=$data['PhysicalProperty']['id'];
?>
        <tr class="collop">
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo date('d/m/Y h:i:s a',strtotime($data['PhysicalProperty']['property_date_time']));?></td>
            <td><?php echo $data['PhysicalProperty']['description'];?></td>
            <td><?php echo $data['PhysicalProperty']['source'];?></td>
            

        </tr>
        <tr id="collapseme" class="collapse out">
        <td colspan="6">
          <table class="table table-bordered" style="width: 100%;">
              <thead>
                  <tr>
                  
                    <th style="text-align: left;">SL#</th>
                    <th style="text-align: left;">Item</th>
                    <th style="text-align: left;">Bag no.</th>
                    <th style="text-align: left;">Quantity</th>
                    <th style="text-align: left;">Type</th>

                          
                       
                  </tr>
              </thead>
              <tbody>
            <?php
            $rowCnt1=0;
            foreach($data["PhysicalPropertyItem"] as $val)
             {
              
                if($val["item_status"]==$status_type){
                  $rowCnt1++;
              ?>
                  <tr>
                    
                  <td><?php echo $rowCnt1; ?></td>
                    <td><?php echo $funcall->getitemname($val['item_id']);?></td>
                    <td><?php echo $val['bag_no'];?></td>
                    <td><?php echo $val['quantity'];?></td>
                    <td><?php echo $val['property_type'];?></td>
                  </tr>
              <?php
            }
            

             } 
              ?>
              </tbody>
          </table>
        </td>
        </tr>
       
<?php
        $rowCnt++;
    }
?>
    </tbody>
</table>

<div id="myDestroyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">X</button>
                <h4 class="modal-title">Property Destroy</h4>
            </div>
            <div class="modal-body">
                <div class="row-fluid">
                    <div class="span10">
                        <div class="control-group">
                            <label class="control-label">Destroy Date<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('destroy_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Destroy Date','class'=>'form-control mydate span11','required', 'id'=>'destroy_date', 'readonly'=>true,'value'=>date('d-m-Y')));?>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span10">
                        <div class="control-group">
                            <label class="control-label">Destroy Cause<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('destroy_cause',array('div'=>false,'label'=>false,'type'=>'textarea','placeholder'=>'Enter Destroy Cause','class'=>'form-control span11','required', 'id'=>'destroy_cause', 'cols'=>30, 'rows'=>3));?>
                            </div>
                        </div>                        
                    </div>                        
                </div> 
                <div class="form-actions" align="center">
                    <?php echo $this->Form->input('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit_desroye','formnovalidate'=>true))?>
                </div>                               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="myOutgoingModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">X</button>
                <h4 class="modal-title">Property Outgoing</h4>
            </div>
            <div class="modal-body">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Outgoing Date<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('outgoing_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Outgoing Date','class'=>'form-control mydate span11','required', 'id'=>'outgoing_date', 'readonly'=>true,'value'=>date('d-m-Y')));?>
                            </div>
                        </div>                        
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Outgoing Source<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('outgoing_source',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Outgoing source','class'=>'form-control  span11','required','id'=>'outgoing_source'));?>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span10">
                        <div class="control-group">
                            <label class="control-label">Outgoing Cause<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('outgoing_cause',array('div'=>false,'label'=>false,'type'=>'textarea','placeholder'=>'Enter Outgoing Cause','class'=>'form-control span11','required', 'id'=>'outgoing_cause', 'cols'=>30, 'rows'=>3));?>
                            </div>
                        </div>                        
                    </div>                        
                </div>  
                         
                <div class="form-actions" align="center">
                    <?php echo $this->Form->input('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit_outgoing','formnovalidate'=>true))?>
                </div>                               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Js->writeBuffer(); 
}

else{
?>
    <span style="color:red;">No records found!</span>
<?php    
}
?>                    
 <script type="text/javascript">

 $(document).on('click', '#submit_outgoing', function(e){
      
        if($('#outgoing_date').val() == ''){
            alert('Please enter outgoing date');
            $('#outgoing_date').focus();
        }else if($('#outgoing_source').val() == ''){
            alert('Please enter outgoing source');
            $('#outgoing_source').focus();
        }else if($('#outgoing_cause').val() == ''){
            alert('Please enter outgoing cause');
            $('#outgoing_cause').focus();
        }else if(jQuery('.propertycheckclass:checked').length == 0) { 
            alert('Please check the boxes to Destroy');
        }else{
        var ids = [];
        $('.propertycheckclass:checked').each(function(i, e) {
            ids.push($(this).val());
        });
        var destroy_date=$("#outgoing_date").val();
        var destroy_cause=$("#outgoing_cause").val();
        var outgoing_source=$("#outgoing_source").val();
        var prisoner_id=$("#prisoner_id").val();
        if (confirm('Are you sure you want to outgoing?')) {
            $.ajax(
              {
                  type: "POST",
                  url: "<?php echo $this->Html->url(array('controller'=>'properties','action'=>'outgoingAjax'));?>",
                  data: {
                      ids:ids,
                      destroy_date:destroy_date,
                      destroy_cause:destroy_cause,
                      outgoing_source:outgoing_source,
                      prisoner_id:prisoner_id,
                  },
                  cache: true,
                  beforeSend: function()
                  {  
                    //$('#delete'+countdata).html('Loading....');
                  },
                  success: function (data) {
                    
                    alertify.success("Outgoing Successfully !");
                    $('#myOutgoingModal').hide();
                    $('.modal-backdrop').hide();
                    showData();
                   
                  },
                  error: function (errormessage) {
                    alert(errormessage.responseText);
                  }
              });
          }
        }

   });

   $(document).on('click', '#submit_desroye', function(e){
      
        if($('#destroy_date').val() == ''){
            alert('Please enter destroy date');
            $('#destroy_date').focus();
        }else if($('#destroy_cause').val() == ''){
            alert('Please enter destroy cause');
            $('#destroy_cause').focus();
        }else if(jQuery('.propertycheckclass:checked').length == 0) { 
            alert('Please check the boxes to Destroy');
        }else{
        var ids = [];
        $('.propertycheckclass:checked').each(function(i, e) {
            ids.push($(this).val());
        });
        var destroy_date=$("#destroy_date").val();
        var destroy_cause=$("#destroy_cause").val();
        var prisoner_id=$("#prisoner_id").val();
        if (confirm('Are you sure you want to destroy?')) {
            $.ajax(
              {
                  type: "POST",
                  url: "<?php echo $this->Html->url(array('controller'=>'properties','action'=>'destroyAjax'));?>",
                  data: {
                      ids:ids,
                      destroy_date:destroy_date,
                      destroy_cause:destroy_cause,
                      prisoner_id:prisoner_id,
                  },
                  cache: true,
                  beforeSend: function()
                  {  
                    //$('#delete'+countdata).html('Loading....');
                  },
                  success: function (data) {
                    
                    alertify.success("Destroyed Successfully !");
                    $('#myDestroyModal').hide();
                    $('.modal-backdrop').hide();
                    showData();
                   
                  },
                  error: function (errormessage) {
                    alert(errormessage.responseText);
                  }
              });
          }
        }

   });
        var cnt="<?php echo $j;?>"
        $(document).ready(function(){

// $(".collop").each(function( index ) {
//   $(this).click(function() {
         
//           if($(this).closest('tr').next('tr').hasClass("out")) {
//               $(this).closest('tr').next('tr').addClass("in");
//               $(this).closest('tr').next('tr').removeClass("out");
//           } else {
//               $(this).closest('tr').next('tr').addClass("out");
//               $(this).closest('tr').next('tr').removeClass("in");
//           }
              
//   });
  
// });


          
        });
        </script>