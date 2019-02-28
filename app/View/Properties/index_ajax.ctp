<?php
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        }
          ?>
<?php

if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<style type="text/css">
  .img_prev_list{
        height: 85px;
    width: 85px;
    padding-left: 20px;
  }
</style>
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
                                                    'item_id'             => $item_id,
                                                    'bag_no'             => $bag_no,
                                                    'propertyfrom_date'             => $propertyfrom_date,
                                                    'propertyto_date'             => $propertyto_date,
                                                    'status_type'=>$status_type
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
    $exUrl = "indexAjax/prisoner_uuid:$prisoner_uuid/item_id:$item_id/bag_no:$bag_no/propertyfrom_date:$propertyfrom_date/propertyto_date:$propertyto_date/status_type:$status_type";
    $urlExcel = $exUrl.'/reqType:XLS';
   $urlDoc = $exUrl.'/reqType:DOC';
   $urlPDF = $exUrl.'/reqType:PDF';
	 $urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
?>
    </div>
</div>

<?php
    }
    if($status_type=="Incoming" || $status_type=="Supplementary Incoming" || $status_type=="Destroy" || $status_type==""){
?>                    
<table class="table table-bordered data-table" id="physicalpropertyidtbl">
    <thead>
        <tr  class="prison-prop">
          <th style="text-align: left;">
          <?php
          if(!isset($is_excel)){          
                echo $this->Paginator->sort('PhysicalProperty.id','SL#',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Properties','action' => 'indexAjax','prisoner_uuid'=> $prisoner_uuid,'item_id'=> $item_id,'bag_no'=> $bag_no,'propertyfrom_date'=> $propertyfrom_date,'propertyto_date'=> $propertyto_date,'status_type'=>$status_type)));
              }else{
          echo 'SL#';}?></th>
          <th style="text-align: left;">
          <?php     
            if(!isset($is_excel)){          
                echo $this->Paginator->sort('PhysicalProperty.property_date_time','Datetime',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Properties','action' => 'indexAjax','prisoner_uuid'=> $prisoner_uuid,'item_id'=> $item_id,'bag_no'=> $bag_no,'propertyfrom_date'=> $propertyfrom_date,'propertyto_date'=> $propertyto_date,'status_type'=>$status_type)));
              }else{
                echo "Datetime";
              }
          ?>
          </th>
        
          <th style="text-align: left;">Image</th> 
          <th style="text-align: left;">
          <?php   
               if(!isset($is_excel)){                            
                echo $this->Paginator->sort('PhysicalProperty.source','Source',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Properties','action' => 'indexAjax','prisoner_uuid'=> $prisoner_uuid,'item_id'=> $item_id,'bag_no'=> $bag_no,'propertyfrom_date'=> $propertyfrom_date,'propertyto_date'=> $propertyto_date,'status_type'=>$status_type)));
              }else{
                echo "Source";
              }
            ?>
          
          </th>
          <th>View Visitor</th>
          <?php
          if(!isset($is_excel)){
          ?> 
          <th style="text-align: left;">Action</th>
          
          <?php
          }
          ?> 
          
             
        </tr>
    </thead>
    <tbody>
<?php
// debug($funcall->params['named']);
// debug($funcall->params['named']['direction']);
// debug($funcall->params['named']['sort']);
if(isset($funcall->params['named']['sort']) && isset($funcall->params['named']['direction'])){
   if($funcall->params['named']['sort'] == "PhysicalProperty.id" && $funcall->params['named']['direction'] == "desc"){
     $rowCnt = $this->Paginator->counter(array('format' => __('{:end}')));
   }else{
      $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
     }
}
else{
  $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
}
// if($_GET["sort"] == "PhysicalProperty.id" && $_GET["direction"] == "desc"){
//   $rowCnt = $this->Paginator->counter(array('format' => __('{:end}')));
// }else{
  
//}
   // $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    $j=0;
            //debug($datas);

    foreach($datas as $data){
      //debug($data);
      $j++;
      $physical_id=$data['PhysicalProperty']['id'];

?>
        <tr class="collop prison-prop-1">
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo date('m-d-Y H:i',strtotime($data['PhysicalProperty']['property_date_time']));?></td>
            
            <td>
                  <?php if($data['PhysicalPropertyItem'][0]['photo'] != null && $data['PhysicalPropertyItem'][0]['photo'] != '') { ?>
                  <span id="previewPane" class="img_preview_panel">
                        <a class="example-image-link prevImage_0" href="" data-lightbox="example-set"><img id="img_prev_0" src="<?php echo $this->webroot; ?>app/webroot/files/physicalitems/<?php echo $data['PhysicalPropertyItem'][0]['photo'];?>" class='img_prev_list' /></a>
                        
                  </span>
                  <?php } ?>
                </td>
            <td><?php echo $data['PhysicalProperty']['source'];?></td>

                
                  <?php if($data['PhysicalProperty']['visitor_id']  != null){ ?>
                     <td> <?php echo $this->Html->link('<i class="icon icon-eye-open" ></i>',array(
                          'action'=>'../visitors/view',
                          $data['PhysicalProperty']['visitor_id']
                      ),array(
                          'escape'=>false,
                          'class'=>'btn btn-success btn-mini'
                      ));
                      ?>
                    </td>
                  <?php }else{ ?>
                    <td></td>
                  <?php } ?>
                  
                
            <?php
            if(!isset($is_excel)){
            ?>              
                
                <td nowrap="nowrap">
                  <?php  foreach($data["PhysicalPropertyItem"] as $val){
                  //echo  $val["item_status"];
                    if( $val["status"] == 'Draft'){
                   ?>
            
                    <?php echo $this->Form->create('PhysicalPropertyEdit',array('url'=>'/Properties/property/'.$prisoner_uuid.'#physical_property','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['PhysicalProperty']['id']));
                    
                    ?>
                    <button class="btn btn-success" type="submit" value="Edit" onclick="javascript:return confirm('Are you sure want to edit?')"><i class="icon icon-edit"></i></button>
                    <?php //echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); 
                    echo $this->Form->end();
                    ?> 
               
                    <?php echo $this->Form->create('PhysicalPropertyDelete',array('url'=>'/Properties/index/'.$prisoner_uuid.'#physical_property','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['PhysicalProperty']['id'])); ?>
                    <button class="btn btn-danger" type="submit" value="Delete" onclick="javascript:return confirm('Are you sure want to delete?')"><i class="icon icon-trash"></i></button>
                    <?php //echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')"));
                    echo $this->Form->end();
                     ?>
                   <?php }else if($val["status"] != 'Approved'){
                       echo $this->Form->create('PhysicalPropertyDelete',array('url'=>'/Properties/index/'.$prisoner_uuid.'#physical_property','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['PhysicalProperty']['id'])); ?>
                        <button class="btn btn-danger" type="submit" value="Delete" onclick="javascript:return confirm('Are you sure want to delete?')"><i class="icon icon-trash"></i></button>
                        <?php //echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')"));
                        echo $this->Form->end();
                         
                       }

                      break;
                    } ?>
                </td>
                
          <?php
        }
          ?>

        </tr>
        <tr id="collapseme" class="collapse out child_tr">
        <td colspan="7" class="prison-prop-2">
          <table class="table table-bordered child_physical_item" style="width: 100%;">
              <thead>
                  <tr class="prison-prop-3">
                    <th></th>
                    <th style="text-align: left;">SL#</th>
                    <th style="text-align: left;">Item</th>
                    <th style="text-align: left;">Bag no.</th>
                    <th style="text-align: left;">Quantity</th>
                    <th style="text-align: left;">Item Description</th>
                    <th style="text-align: left;">Type</th>
                    <th style="text-align: left;">Item Status</th>

                    <th style="text-align: left;">Outgoing</th>

                          
                       
                  </tr>
              </thead>
              <tbody>
            <?php
            $rowCnt1=0;
            foreach($data["PhysicalPropertyItem"] as $val)
             {
              if($item_id=="" && $bag_no=="")
               {
                  if($val["item_status"]=="Incoming" || $val["item_status"]=="Supplementary Incoming" || $val["item_status"]=="Destroy"){
                  $rowCnt1++;
                  ?>
                  <tr>
                  <?php
                    if($val["status"]=="Approved"){

                          if(!isset($is_excel)){
            
                  ?>
                    <td>Approved </td>
                    <?php
                          }
                          else{
                            ?>
                            <td>Approved</td>
                            <?php
                          }
                  }
                    else{
                    ?>
                    <td><?php $val["status"] ; ?> not approved</td>
                    <?php
                  }
                    ?>
                    <td><?php echo $rowCnt1; ?></td>
                    <td><?php echo $funcall->getitemname($val['item_id']);?></td>
                    <td><?php echo $val['bag_no'];?></td>
                    <td><?php echo $val['quantity'];?></td>
                    <td><?php echo $val['description'];?></td>
                    <td><?php echo $val['property_type'];?></td>
                    <td><?php echo $val['item_status'];?></td>

                     <?php if($val["status"]=="Approved"){ ?>

                     <?php if ($this->Session->read('Auth.User.usertype_id')==4) {
                      
                      ?>


                        <td>
                          <?php if($val["quantity_remaining"] == '' || $val["quantity_remaining"] == NULL ){ ?>
                              <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary outgoing_btn','div'=>false,'label'=>false,'data-id'=>$val['id'],'onclick'=>'setOutgoingModal('.$val["quantity"].','.$val["quantity"].',' . $val["id"].')','id'=>'btnOutgoing'))?>
                          <?php }else{ ?>
                              <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary outgoing_btn','div'=>false,'label'=>false,'data-id'=>$val['id'],'onclick'=>'setOutgoingModal('.$val["quantity"].','.$val["quantity_remaining"].',' . $val["id"].')','id'=>'btnOutgoing'))?>
                          <?php } ?>
                         
                           
                         </td>
                         <?php } ?>


                    <?php }else{ ?>
                      <td></td>
                    <?php } ?>
                  </tr>
                  <?php
                  }
               } 
               else{
                  if($item_id!="" && $bag_no==""){
                     if(($val["item_status"]=="Incoming" || $val["item_status"]=="Supplementary Incoming") && $val["item_id"]==$item_id){
                      
                       $rowCnt1++;
                    ?>
                    <tr>
                     <?php
                    if($val["status"]=="Approved"){
                       if(!isset($is_excel)){
                  ?>
                    <td><input type="checkbox" class="propertycheckclass" name="chk[]" value="<?php echo $val['id'] ?>"> </td>
                    <?php
                    }
                    else{
                      ?>
                      <td>Approved</td>
                      <?php
                    }
                  }
                    else{
                    ?>
                    <td>Not Approved</td>
                    <?php
                  }
                    ?>
                      <td><?php echo $rowCnt1; ?></td>
                      <td><?php echo $funcall->getitemname($val['item_id']);?></td>
                      <td><?php echo $val['bag_no'];?></td>
                      <td><?php echo $val['quantity'];?></td>
                      <td><?php echo $val['property_type'];?></td>
                      <td><?php echo $val['item_status'];?></td>

                    </tr>
                    <?php
                
                    }
                }
                else if($bag_no!="" && $item_id==""){
                  if(($val["item_status"]=="Incoming" || $val["item_status"]=="Supplementary Incoming") && $val["bag_no"]==$bag_no){
                      
                       $rowCnt1++;
                    ?>
                    <tr>
                      <?php
                    if($val["status"]=="Approved"){
                         if(!isset($is_excel)){
                  ?>
                    <td><input type="checkbox" class="propertycheckclass" name="chk[]" value="<?php echo $val['id'] ?>"> </td>
                    <?php
                        }
                        else{
                          ?>
                          <td>Approved</td>
                          <?php
                        }
                  }
                    else{
                    ?>
                    <td>Not Approved</td>
                    <?php
                  }
                    ?>
                    <td><?php echo $rowCnt1; ?></td>
                      <td><?php echo $funcall->getitemname($val['item_id']);?></td>
                      <td><?php echo $val['bag_no'];?></td>
                      <td><?php echo $val['quantity'];?></td>
                      <td><?php echo $val['property_type'];?></td>
                      <td><?php echo $val['item_status'];?></td>

                    </tr>
                    <?php
                
                    }
                }
                else if($bag_no!="" && $item_id!=""){
                  if($val["item_status"]=="Incoming" && $val["bag_no"]==$bag_no && $val["item_id"]==$item_id){
                      
                       $rowCnt1++;
                    ?>
                    <tr>
                      <?php
                    if($val["status"]=="Approved"){
                       if(!isset($is_excel)){
                  ?>
                    <td><input type="checkbox" class="propertycheckclass" name="chk[]" value="<?php echo $val['id'] ?>"> </td>
                    <?php
                      }
                      else{
                        ?>
                        <td>Approved</td>
                        <?php
                      }
                  }
                  else{
                    ?>
                    <td>Not Approved</td>
                    <?php
                  }
                    ?>
                    <td><?php echo $rowCnt1; ?></td>
                      <td><?php echo $funcall->getitemname($val['item_id']);?></td>
                      <td><?php echo $val['bag_no'];?></td>
                      <td><?php echo $val['quantity'];?></td>
                      <td><?php echo $val['property_type'];?></td>
                      <td><?php echo $val['item_status'];?></td>

                    </tr>
                    <?php
                
                    }
                }
               }
                
              
            

             } 
              ?>
              </tbody>
          </table>
        </td>
        </tr>
       
<?php


// debug($funcall->params['named']);
// debug($funcall->params['named']['direction']);
// debug($funcall->params['named']['sort']);
if(isset($funcall->params['named']['sort']) && isset($funcall->params['named']['direction'])){
 if($funcall->params['named']['sort'] == "PhysicalProperty.id" && $funcall->params['named']['direction'] == "desc"){
   $rowCnt--;
 }
 else{$rowCnt++;}
}

  }
?>
    </tbody>
</table>
<?php
}
else{
  ?>
  <table class="table table-bordered data-table prison-prop" id="physicalpropertyidtbl">
    <thead>
        <tr>
          <th style="text-align: left;">SL#</th>
          <th style="text-align: left;">
          <?php             
            if(!isset($is_excel)){    
                echo $this->Paginator->sort('PhysicalProperty.property_date_time','Datetime',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Properties','action' => 'indexAjax','prisoner_uuid'=> $prisoner_uuid,'item_id'=> $item_id,'bag_no'=> $bag_no,'propertyfrom_date'=> $propertyfrom_date,'propertyto_date'=> $propertyto_date,'status_type'=>$status_type)));
              }else{
                echo "Datetime";
              }
          ?>
          </th>
          <th style="text-align: left;">
            
            <?php 
            if(!isset($is_excel)){                
                echo $this->Paginator->sort('PhysicalProperty.description','Description',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Properties','action' => 'indexAjax','prisoner_uuid'=> $prisoner_uuid,'item_id'=> $item_id,'bag_no'=> $bag_no,'propertyfrom_date'=> $propertyfrom_date,'propertyto_date'=> $propertyto_date,'status_type'=>$status_type)));
              }else{
                echo "Description";
              }
            ?>
          </th>
          <th style="text-align: left;">
          <?php       
            if(!isset($is_excel)){                          
                echo $this->Paginator->sort('PhysicalProperty.source','Source',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Properties','action' => 'indexAjax','prisoner_uuid'=> $prisoner_uuid,'item_id'=> $item_id,'bag_no'=> $bag_no,'propertyfrom_date'=> $propertyfrom_date,'propertyto_date'=> $propertyto_date,'status_type'=>$status_type)));
              }else{
                echo "Source";
              }
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

            <td><?php echo date('m-d-Y h:i',strtotime($data['PhysicalProperty']['property_date_time']));?></td>
            <td><?php echo $data['PhysicalProperty']['description'];?></td>
            <td><?php echo $data['PhysicalProperty']['source'];?></td>
            

        </tr>
        <tr id="collapseme" class="collapse out child_tr">
        <td colspan="6">
          <table class="table table-bordered child_physical_item" style="width: 100%;">
              <thead>
                  <tr>
                  
                    <th style="text-align: left;">SL#</th>
                    <th style="text-align: left;">Item</th>
                    <th style="text-align: left;">Bag no.</th>
                    <th style="text-align: left;">Quantity</th>
                    <th style="text-align: left;">Outgoing Quantity</th>
                    <th style="text-align: left;">Remaining Quantity</th>

                    <th style="text-align: left;">Type</th>
                    <th>Outgoing</th>


                          
                       
                  </tr>
              </thead>
              <tbody>
            <?php
            $rowCnt1=0;

            foreach($data["PhysicalPropertyItem"] as $val)
             {
              if($item_id=="" && $bag_no=="")
               {
                
                  $rowCnt1++;
                  ?>
                  <tr>
                    
                  <td><?php echo $rowCnt1; ?></td>
                    <td><?php echo $funcall->getitemname($val['item_id']);?></td>
                    <td><?php echo $val['bag_no'];?></td>
                    <td><?php echo $val['quantity'];?></td>
                    <td><?php echo $val['quantity_outgoing'];?></td>
                    <td><?php echo $val['quantity_remaining'];?></td>
                    <td><?php echo $val['property_type'];?></td>
                     <?php if($val["outgoing_status"]=="Approved"){ ?>


                        <td>
                          <?php if($val["quantity_remaining"] == '' || $val["quantity_remaining"] == NULL ){ ?>
                              <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary outgoing_btn','div'=>false,'label'=>false,'data-id'=>$val['id'],'onclick'=>'setOutgoingModal('.$val["quantity"].','.$val["quantity"].',' . $val["id"].')','id'=>'btnOutgoing'))?>
                          <?php }else{ ?>
                              <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary outgoing_btn','div'=>false,'label'=>false,'data-id'=>$val['id'],'onclick'=>'setOutgoingModal('.$val["quantity"].','.$val["quantity_remaining"].',' . $val["id"].')','id'=>'btnOutgoing'))?>
                          <?php } ?>
                         
                           
                         </td>


                    <?php }else{ ?>
                      <td></td>
                    <?php } ?>
                  </tr>
                  <?php
                
               } 
               else{
                  if($item_id!="" && $bag_no==""){
                     if($val["item_id"]==$item_id){
                      
                       $rowCnt1++;
                    ?>
                    <tr>
                      
                    <td><?php echo $rowCnt1; ?></td>
                      <td><?php echo $funcall->getitemname($val['item_id']);?></td>
                      <td><?php echo $val['bag_no'];?></td>
                      <td><?php echo $val['quantity'];?></td>

                    <td><?php echo $val['quantity_outgoing'];?></td>
                    <td><?php echo $val['quantity_remaining'];?></td>
                      <td><?php echo $val['property_type'];?></td>
                       <?php if($val["outgoing_status"]=="Approved"){ ?>


                        <td>
                          <?php if($val["quantity_remaining"] == '' || $val["quantity_remaining"] == NULL ){ ?>
                              <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary outgoing_btn','div'=>false,'label'=>false,'data-id'=>$val['id'],'onclick'=>'setOutgoingModal('.$val["quantity"].','.$val["quantity"].',' . $val["id"].')','id'=>'btnOutgoing'))?>
                          <?php }else{ ?>
                              <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary outgoing_btn','div'=>false,'label'=>false,'data-id'=>$val['id'],'onclick'=>'setOutgoingModal('.$val["quantity"].','.$val["quantity_remaining"].',' . $val["id"].')','id'=>'btnOutgoing'))?>
                          <?php } ?>
                         
                           
                         </td>


                    <?php }else{ ?>
                      <td></td>
                    <?php } ?>

                    </tr>
                    <?php
                
                    }
                }
                else if($bag_no!="" && $item_id==""){
                  if($val["bag_no"]==$bag_no){
                      
                       $rowCnt1++;
                    ?>
                    <tr>
                      
                    <td><?php echo $rowCnt1; ?></td>
                      <td><?php echo $funcall->getitemname($val['item_id']);?></td>
                      <td><?php echo $val['bag_no'];?></td>
                      <td><?php echo $val['quantity'];?></td>

                    <td><?php echo $val['quantity_outgoing'];?></td>
                    <td><?php echo $val['quantity_remaining'];?></td>
                      <td><?php echo $val['property_type'];?></td>
                       <?php if($val["outgoing_status"]=="Approved"){ ?>


                        <td>
                          <?php if($val["quantity_remaining"] == '' || $val["quantity_remaining"] == NULL ){ ?>
                              <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary outgoing_btn','div'=>false,'label'=>false,'data-id'=>$val['id'],'onclick'=>'setOutgoingModal('.$val["quantity"].','.$val["quantity"].',' . $val["id"].')','id'=>'btnOutgoing'))?>
                          <?php }else{ ?>
                              <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary outgoing_btn','div'=>false,'label'=>false,'data-id'=>$val['id'],'onclick'=>'setOutgoingModal('.$val["quantity"].','.$val["quantity_remaining"].',' . $val["id"].')','id'=>'btnOutgoing'))?>
                          <?php } ?>
                         
                           
                         </td>


                    <?php }else{ ?>
                      <td></td>
                    <?php } ?>

                    </tr>
                    <?php
                
                    }
                }
                else if($bag_no!="" && $item_id!=""){
                  if($val["bag_no"]==$bag_no && $val["item_id"]==$item_id){
                      
                       $rowCnt1++;
                    ?>
                    <tr>
                      
                    <td><?php echo $rowCnt1; ?></td>
                      <td><?php echo $funcall->getitemname($val['item_id']);?></td>
                      <td><?php echo $val['bag_no'];?></td>
                      <td><?php echo $val['quantity'];?></td>

                    <td><?php echo $val['quantity_outgoing'];?></td>
                    <td><?php echo $val['quantity_remaining'];?></td>
                      <td><?php echo $val['property_type'];?></td>
                       <?php if($val["outgoing_status"]=="Approved"){ ?>


                        <td>
                          <?php if($val["quantity_remaining"] == '' || $val["quantity_remaining"] == NULL ){ ?>
                              <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary outgoing_btn','div'=>false,'label'=>false,'data-id'=>$val['id'],'onclick'=>'setOutgoingModal('.$val["quantity"].','.$val["quantity"].',' . $val["id"].')','id'=>'btnOutgoing'))?>
                          <?php }else{ ?>
                              <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary outgoing_btn','div'=>false,'label'=>false,'data-id'=>$val['id'],'onclick'=>'setOutgoingModal('.$val["quantity"].','.$val["quantity_remaining"].',' . $val["id"].')','id'=>'btnOutgoing'))?>
                          <?php } ?>
                         
                           
                         </td>


                    <?php }else{ ?>
                      <td></td>
                    <?php } ?>
                    </tr>
                    <?php
                
                    }
                }
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
  <?php
}
?>
<?php
            if(!isset($is_excel)){
            ?>
<div id="myDestroyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">X</button>
                <h4 class="modal-title">Property Destroy</h4>
            </div>
            <div class="modal-body">
            <form id="destroy-form" enctype="multipart/form-data" method="post">
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Destroy Date & Time<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('destroy_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Destroy Date','class'=>'form-control span12','required', 'id'=>'destroy_date', 'readonly'=>true,'value'=>date('d-m-Y h:i:s')));?>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Reason for Destruction<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('destroy_cause',array('div'=>false,'label'=>false,'type'=>'textarea','placeholder'=>'Enter Reason for Destruction','class'=>'form-control span12','required', 'id'=>'destroy_cause', 'cols'=>30, 'rows'=>3));?>
                            </div>
                        </div>                        
                    </div>                        
                </div> 
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Property Description<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('destroy_desc',array('div'=>false,'label'=>false,'type'=>'textarea','placeholder'=>'Enter Property Description','class'=>'form-control span12','required', 'id'=>'destroy_desc', 'cols'=>30, 'rows'=>3));?>
                            </div>
                        </div>                        
                    </div>                        
                </div> 
                <div class="control-group">
                    <label class="control-label">Witness Name:</label>
                    <div class="controls">
                        <?php 
                        echo $this->Form->input('property_witness',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$witnessList,'multiple'=>'multiple', 'empty'=>'-- Select witness --','required'=>true,'hiddenField'=>false));?>
                    </div>
                </div>
                <div class="control-group">
                        <label class="control-label">Upload Pciture Destroyed Item:</label>
                        <div class="controls">
                        <?php echo $this->Form->input('photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'photo','data-id'=>'0', 'onchange'=>'readURL(this);', 'required'=>false));?>
                        </div>
                        <div id='"prevImage_0' class="">
                        <?php $is_photo = '';
                            if(isset($this->request->data["PhysicalPropertyItem"]["photo"]))
                            {
                                $is_photo = 1;?>
                               <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/physicalitems/<?php echo $this->request->data["PhysicalPropertyItem"]["photo"];?>" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/physicalitems/<?php echo $this->request->data["PhysicalPropertyItem"]["photo"];?>" alt="" width="150px" height="150px"></a>
                            <?php }?>
                        </div>
                        <span id="previewPane" class="img_preview_panel">
                            <a class="example-image-link prevImage_0" href="" data-lightbox="example-set"><img id="img_prev_0" src="#" class="img_prev_0" alt="" /></a>
                            <span id="x" class="remove_img">[X]</span>
                        </span>
                </div>
                <div class="control-group">
                    <label class="control-label">Mode of Destruction <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                    <div class="controls">
                       <?php echo $this->Form->input('destruction_mode',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Mode of Destruction','id'=>'destruction_mode','rows'=>3,'required'=>false));?>
                    </div>
                </div>

                <div class="form-actions" align="center" style="padding: 19px 20px 0;margin-top: 0;margin-bottom: 0;">
                    <?php echo $this->Form->button('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit_desroye','formnovalidate'=>true))?>
                </div>    
                </form>                           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
              <?php echo $this->Form->input('item_id',array('div'=>false,'label'=>false,'type'=>'hidden','class'=>'form-control  span12','required','id'=>'item_id'));?>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Outgoing Date & Time<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('outgoing_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Outgoing Date','class'=>'form-control span12','required', 'id'=>'outgoing_date', 'readonly'=>true,'value'=>date('d-m-Y h:i:s')));?>
                            </div>
                        </div>                        
                    </div>
                    <div class="span6" style="margin-left: 2% !important;">
                        <div class="control-group">
                            <label class="control-label"><!-- Outgoing Source -->Recipient of the Property<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('outgoing_source',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Outgoing source','class'=>'form-control  span12','required','id'=>'outgoing_source'));?>
                            </div>
                        </div>                        
                    </div>
                    <div class="span6" style="margin-left: 2% !important;">
                        <div class="control-group">
                            <label class="control-label">Contact of reciepient<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('recipient_contact',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Outgoing contact of reciepient','class'=>'form-control  span12','required','id'=>'recipient_contact'));?>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Recipient Address<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('recipient_address',array('div'=>false,'label'=>false,'type'=>'textarea','placeholder'=>'Enter Outgoing Recipient Address','class'=>'form-control span12','required', 'id'=>'recipient_address', 'cols'=>30, 'rows'=>3));?>
                            </div>
                        </div>                        
                    </div>                        
                </div> 
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Outgoing Reason<?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('outgoing_cause',array('div'=>false,'label'=>false,'type'=>'textarea','placeholder'=>'Enter Outgoing Cause','class'=>'form-control span12','required', 'id'=>'outgoing_cause', 'cols'=>30, 'rows'=>3));?>
                            </div>
                        </div>                        
                    </div>                        
                </div>  
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Property Description <?php echo $req; ?>  :</label>
                            <div class="controls">
                                <?php echo $this->Form->input('outgoing_desc',array('div'=>false,'label'=>false,'type'=>'textarea','placeholder'=>'Enter Property Description','class'=>'form-control span12','required', 'id'=>'outgoing_desc', 'cols'=>30, 'rows'=>3));?>
                            </div>
                        </div>                        
                    </div>                        
                </div>

                <div class="row-fluid">
                <div class="span12">
                <div class="control-group">
                  <?php if($this->Session->read('Auth.User.usertype_id') == Configure::read('OFFICERINCHARGE_USERTYPE')){ ?>
                    <label class="control-label">Finger Print :</label>
                    <div class="controls">

                        <?php 
                        echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in','onclick'=>"start()"));
                        ?>
                        <?php echo $this->Form->input('is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','title'=>"Please verifiy from biometric","id"=>"link_biometric_verified",'value'=>1));?>
                      
                    </div>
                    <?php }else{ ?>

                       <label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
                        <div class="controls">

                        <?php 
                        echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in','onclick'=>"start()"));
                        ?>
                        <?php echo $this->Form->input('is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','required','title'=>"Please verifiy from biometric","id"=>"link_biometric_verified"));?>
                      
                    </div>

                    <?php } ?>
                </div>
                </div>
                </div>
                <!-- // check the death condition for prisoner -->
                <?php
                $funcall->loadModel('MedicalDeathRecord');     
                $prisonerId = $funcall->Prisoner->field("id",array("Prisoner.uuid"=>$prisoner_uuid));    
                $deathRecord = $funcall->MedicalDeathRecord->find("count", array(
                    "conditions"=> array(
                        "MedicalDeathRecord.prisoner_id"=>$prisonerId,
                        "MedicalDeathRecord.status"=>"Approved",
                        )
                    ));
                
                
                ?>
                <div class="row-fluid" style="display:<?php echo ($deathRecord==1) ? 'block': 'none'; ?>;">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">NOK<?php echo $req; ?>  :</label>
                            <div class="controls">
                                 <?php 
                                 $prisonerKinList = array();
                                 if($deathRecord==1){
                                    $prisonerKinArr = $funcall->PrisonerKinDetail->find('all',array(
                                          'recursive'     => -1,
                                          'fields'        => array(
                                              'PrisonerKinDetail.id',
                                              'PrisonerKinDetail.first_name',
                                              'PrisonerKinDetail.middle_name',
                                              'PrisonerKinDetail.last_name',
                                          ),
                                          'conditions'    => array(
                                              'PrisonerKinDetail.is_trash'     => 0,
                                               'PrisonerKinDetail.prisoner_id'     => $prisonerId,
                                               'PrisonerKinDetail.status'     => "Approved",
                                          ),
                                          'order'=>array(
                                              'PrisonerKinDetail.id' => 'desc',
                                          )
                                      )); 
                                     
                                     if(isset($prisonerKinArr) && is_array($prisonerKinArr) && count($prisonerKinArr)){
                                        foreach ($prisonerKinArr as $prisonerKinArrKey => $prisonerKinArrValue) {
                                            $prisonerKinList[$prisonerKinArrValue['PrisonerKinDetail']['id']] = $prisonerKinArrValue['PrisonerKinDetail']['first_name']." ".$prisonerKinArrValue['PrisonerKinDetail']['middle_name']." ".$prisonerKinArrValue['PrisonerKinDetail']['last_name'];
                                        }
                                     }
                                 }
                                 echo $this->Form->input('prisoner_kin_detail_id',array('div'=>false,'label'=>false,'class'=>'form-control span12','type'=>'select','options'=>$prisonerKinList, 'empty'=>'-- Select --','required'=>false,'id'=>'prisoner_kin_detail_id'));?>
                            </div>
                        </div>                        
                    </div>                        
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Status<?php echo $req; ?>  :</label>
                            <div class="controls">
                                 <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'class'=>'form-control span12','type'=>'select','options'=>$outgoingStatusList, 'empty'=>'-- Select Status --','required'=>false,'id'=>'status'));?>
                            </div>
                        </div>                        
                    </div>   
                    <?php $outgoingTypeList=array('Supplementary Outgoing'=>'Supplementary Outgoing','Outgoing'=>'Outgoing') ?>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Status<?php echo $req; ?>  :</label>
                            <div class="controls">
                                 <?php echo $this->Form->input('outgoing_type',array('div'=>false,'label'=>false,'class'=>'form-control span12','type'=>'select','options'=>$outgoingTypeList, 'empty'=>'-- Select Outgoing type --','required'=>true,'id'=>'outgoing_type'));?>
                            </div>
                        </div>                        
                    </div>                      
                </div> 

                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Quantity total :</label>
                            <div class="controls">
                                 <?php echo $this->Form->input('quantity_total',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'hidden','readonly','required'=>false,'id'=>'quan_total'));?>
                                 <?php echo $this->Form->input('quantity_remaining',array('div'=>false,'label'=>false,'class'=>'form-control span8','type'=>'text','readonly','required'=>false,'id'=>'quantity_remaining'));?>
                                 
                            </div>
                        </div>                        
                    </div>  
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Quantity Outgoing :</label>
                            <div class="controls">
                                 <?php echo $this->Form->input('quantity_outgoing',array('div'=>false,'label'=>false,'class'=>'form-control span8 numeric','type'=>'text','required'=>true,'id'=>'quan_outgoing'));?>
                            </div>
                        </div>                         
                    </div>                       
                </div> 

                <div class="form-actions" align="center" style="padding: 19px 20px 0;margin-top: 0;margin-bottom: 0;">
                    <?php echo $this->Form->input('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit_outgoing','formnovalidate'=>true))?>
                </div>                               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
<?php
echo $this->Js->writeBuffer(); 
}

else{
echo '...';   
}
?>   
<?php if(@$file_type != 'pdf' ) { ?>  
             
 <script type="text/javascript">
  $('#property_witness').select2();
  function setOutgoingModal(quan,rem,id){
          $('#myOutgoingModal #item_id').val(id);
          $('#myOutgoingModal #quan_total').val(quan);
          $('#myOutgoingModal #quantity_remaining').val(rem);

          
          $('#myOutgoingModal').modal('show');

        }
  // $("#prisoner_kin_detail_id").select2();
 $(document).on('click', '#submit_outgoing', function(e){
      
        if($('#outgoing_date').val() == ''){recipient_contact
            alert('Please enter outgoing date');
            $('#outgoing_date').focus();
        }else if($('#outgoing_source').val() == ''){
            alert('Please enter outgoing source');
            $('#outgoing_source').focus();
        }else if($('#recipient_contact').val() == ''){
            alert('Please enter recipient contact');
            $('#recipient_contact').focus();
        }else if($('#recipient_address').val() == ''){
            alert('Please enter recipient address');
            $('#recipient_address').focus();
        }else if($('#outgoing_cause').val() == ''){
            alert('Please enter outgoing cause');
            $('#outgoing_cause').focus();
        }else if($('#outgoing_desc').val() == ''){
            alert('Please enter outgoing Description');
            $('#outgoing_desc').focus();
        }else if($('#status').val() == ''){
            alert('Please select status');

        }else if($('#link_biometric_verified').val() == ''){
            alert('Please verify biometric');

        }
        <?php
        if($deathRecord==1){
            ?>
            else if($('#prisoner_kin_detail_id').val() == ''){
                alert('Please select kin details');

            }
            <?php
        }
        ?>
        else{
        var quan_outgoing=$("#quan_outgoing").val();
        var quan_total=$("#quan_total").val();
        var quantity_remaining=$("#quantity_remaining").val();
        var outgoing_type=$("#outgoing_type").val();



        var ids = [];
        ids.push($("#myOutgoingModal #item_id").val());

        var destroy_date=$("#outgoing_date").val();
        var destroy_cause=$("#outgoing_cause").val();
        var outgoing_source=$("#outgoing_source").val();
        var outgoing_desc=$("#outgoing_desc").val();
        var prisoner_id=$("#prisoner_id").val();
        var status=$("#status").val();
        var recipient_contact=$("#recipient_contact").val();
        var recipient_address=$("#recipient_address").val();
        var prisoner_kin_detail_id=$("#prisoner_kin_detail_id").val();
        var is_biometric_verified=$("#link_biometric_verified").val();
        if(quan_outgoing != '' && (parseInt(quan_outgoing) <= parseInt(quan_total)) ){
            if (confirm('Are you sure you want to outgoing?')) {
            $.ajax(
              {
                  type: "POST",
                  url: "<?php echo $this->Html->url(array('controller'=>'properties','action'=>'outgoingAjax'));?>",
                  data: {
                      ids:ids,
                      destroy_date:destroy_date,
                      destroy_cause:destroy_cause,
                      outgoing_desc:outgoing_desc,
                      outgoing_source:outgoing_source,
                      prisoner_id:prisoner_id,
                      recipient_contact:recipient_contact,
                      recipient_address:recipient_address,
                      status:status,
                      prisoner_kin_detail_id:prisoner_kin_detail_id,
                      is_biometric_verified:is_biometric_verified,
                      quan_outgoing:quan_outgoing,
                      quan_total:quan_total,
                      quantity_remaining:quantity_remaining,
                      outgoing_type:outgoing_type

                  },
                  cache: true,
                  beforeSend: function()
                  {  
                    //$('#delete'+countdata).html('Loading....');
                  },
                  success: function (data) {
                    alert(data.trim());
                    if(data.trim() == "allowed"){
                      alertify.success("Outgoing Successfully !");
                      $('#myOutgoingModal').hide();
                      $('.modal-backdrop').hide();
                      showData();
                    }else{
                      alert('Outgoing not allowed');
                    }
                    
                  },
                  error: function (errormessage) {
                    alert(errormessage.responseText);
                  }
              });
          }
        }else{
            alert('Please enter proper quantity');
        }


        
        }

   });

   $(document).on('click', '#submit_desroye', function(e){
      //alert(1);
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
        var destroy_desc=$("#destroy_desc").val();
        var property_witness=$("#property_witness").val();
        var destruction_mode=$("#destruction_mode").val();
        //alert(status);
        
        var prisoner_id=$("#prisoner_id").val();
         var fd = new FormData($('#destroy-form')[0]);
         fd.append("ids", ids);
         fd.append("prisoner_id", prisoner_id);
        if (confirm('Are you sure you want to destroy?')) {
            $.ajax(
              {

                  url: "http://192.168.1.220/uganda/properties/destroyAjax",
                  type: 'POST',
                  // dataType: "JSON",
                  data: fd,
                  processData: false,
                  contentType: false,
                  // type: "POST",
                  // url: "<?php echo $this->Html->url(array('controller'=>'properties','action'=>'destroyAjax'));?>",
                  // data: new FormData($('#destroy-form')),
                  // // data: {
                  // //     ids:ids,
                  // //     destroy_date:destroy_date,
                  // //     destroy_cause:destroy_cause,
                  // //     destroy_desc:destroy_desc,
                  // //     prisoner_id:prisoner_id,

                  // // },
                  // cache: true,
                  beforeSend: function()
                  {  
                    //alert(2);
                    $('#destroy-form').validate({

                    });
                    //$('#delete'+countdata).html('Loading....');
                  },
                  success: function (data) {
                    //return false;
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
          $('.mydate').datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: 'dd-mm-yy',
                changeYear: true,
                maxDate: '0'               
            });

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
        </script>
<?php } ?>