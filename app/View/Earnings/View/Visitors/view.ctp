<style>
.table.detail th
{
    text-align:left;
}
.span12.heading{padding-left:10px;}
</style>
<div class="container-fluid">
    
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Visitors Details  &nbsp;&nbsp;   <?php
            echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),'view/'.$visitorList[0]["Visitor"]['id'].'/reqType:PRINT', array("escape" => false,'target'=>"")))
            ?></h5> 
                    <div style="float:right;padding-top:2px;">
                        <?php echo $this->Html->link('Back',array('action'=>'index'),array('class' => 'btn btn-success'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                        <div class="row-fluid">
                           <!--  <?php debug($visitorList); ?> -->
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table detail table-bordered table-responsive" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid;">Visitor Category</th>
                                                    <th style="border: 1px solid;">Date</th>
                                                    <th style="border: 1px solid;">Reason</th>
                                                    <th style="border: 1px solid;">Gate keeper</th>
                                                    <th style="border: 1px solid;">Bag No</th>
                                                    <th style="border: 1px solid;">Vehicle No</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                    <tr>
                                                        <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['category'];?></td>
                                                        <td style="border: 1px solid;"><?php echo  date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($visitorList[0]["Visitor"]['date']));?></td>
                                                        <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['reason'];?></td>
                                                        <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['gate_keeper'];?></td>
                                                        <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['bag_no'];?></td>
                                                        <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['vehicle_no'];?></td>

                                                    </tr>
                                                
                                            </tbody>
                                        </table>
                                        <table class="table detail" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid;">Address</th>
                                                    <th style="border: 1px solid;">Contact No</th>
                                                    <th style="border: 1px solid;">Cash Details</th>
                                                    <th style="border: 1px solid;">PP Cash</th>
                                                    <th style="border: 1px solid;">PP Amount</th>
                                                    <th style="border: 1px solid;">Personal Property</th>
                                                    <th style="border: 1px solid;">To Whom To Meet</th>
                                                    <th style="border: 1px solid;">Prisoner No</th>
                                                    <th style="border: 1px solid;">Prisoner Name</th>




                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['address'];?></td>
                                                    <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['contact_no'];?></td>
                                                    <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['cash_details'];?></td>
                                                    <td style="border: 1px solid;"><?php if($visitorList[0]["Visitor"]['pp_cash'] !=''){ ?>
                                                    <?php echo  $funcall->getPPCashName($visitorList[0]["Visitor"]['pp_cash']);?>
                                                    <?php } ?>
                                                    </td>
                                                    <td style="border: 1px solid;"><?php if($visitorList[0]["Visitor"]['pp_amount'] !=''){ ?>
                                                    <?php echo  $visitorList[0]["Visitor"]['pp_amount'];?>
                                                    <?php } ?>
                                                    </td>
                                                    <td style="border: 1px solid;"><?php if($visitorList[0]["Visitor"]['Personal_property'] !=''){ ?>
                                                    <?php echo  $visitorList[0]["Visitor"]['Personal_property'];?>
                                                    <?php } ?>
                                                    </td>
                                                    <td style="border: 1px solid;"><?php if( $visitorList[0]["Visitor"]['to_whom'] != ''){ ?>
                                                    <?php echo  $visitorList[0]["Visitor"]['to_whom'];?>
                                                    <?php } ?>
                                                    </td>
                                                    <td style="border: 1px solid;"><?php if($visitorList[0]["Visitor"]['prisoner_no'] !=''){ ?>
                                                    <?php echo  $visitorList[0]["Visitor"]['prisoner_no'];?>
                                                    <?php } ?>
                                                    </td>
                                                    <td style="border: 1px solid;"><?php if($visitorList[0]["Visitor"]['name'] !=''){ ?>
                                                    <?php echo  $funcall->getPrisonerName($visitorList[0]["Visitor"]['name']);?>
                                                    <?php } ?>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                        <table class="table detail" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid;">Time In</th>
                                                    <th style="border: 1px solid;">Time Out</th>
                                                    <th style="border: 1px solid;">Duration</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['time_in'];?></td>
                                                    <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['time_out'];?></td>
                                                    <td style="border: 1px solid;"><?php echo  $visitorList[0]["Visitor"]['duration']." Min";?></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                          
                            
                            <div class="row-fluid">
                                <div class="span12 heading">
                                    <h5>Item Details</h5>
                                </div>
                            </div>
                            <?php if(count($visitorList[0]['VisitorItem'])>0){?>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table detail" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid;">Item Name</th>
                                                    <th style="border: 1px solid;">Quantity</th>
                                                    <th style="border: 1px solid;">Returned</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($visitorList[0]['VisitorItem'] as $item){
                                                	?>
                                                    <tr>
                                                        <td style="border: 1px solid;"><?php echo $item['item'];?></td>
                                                        <td style="border: 1px solid;"><?php echo $item['quantity'];?></td>
                                                        <?php if($item['is_collected'] == 1){ ?>
                                                            <td style="color: green;border: 1px solid;">Returned</td>
                                                       <?php }else{ ?>
                                                            <td style="color: red;border: 1px solid;">Not yet Returned</td>
                                                       <?php  } ?>
                                                    </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="row-fluid">
                                <div class="span12 heading">
                                    <h5>Item for Prisoner Details</h5>
                                </div>
                            </div>
                            <?php if(count($visitorList[0]['VisitorPrisonerItem'])>0){?>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table detail" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid;">Item Name</th>
                                                    <th style="border: 1px solid;">Quantity</th>
                                                    <th style="border: 1px solid;">Collected</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($visitorList[0]['VisitorPrisonerItem'] as $item){
                                                    ?>

                                                    <tr>
                                                        <?php 
                                                        $itemName = '';
                                                        foreach($propertyItemList as $propertyitem){
                                                        
                                                          if($item['item_type'] == $propertyitem['Propertyitem']['id']){
                                                            $itemName=$propertyitem['Propertyitem']['name'] ;
                                                            break;
                                                          }  
                                                    
                                                    }?>
                                                        <td style="border: 1px solid;"><?php echo $itemName?></td>
                                                        <td style="border: 1px solid;"><?php echo $item['quantity'];?></td>
                                                        <?php if($item['quantity'] != ''){ ?>
                                                            <?php if($item['is_collected'] == 1){ ?>
                                                            <td style="color: green;border: 1px solid;">Collected</td>
                                                           <?php }else{ ?>
                                                                <td style="color: red;border: 1px solid;">Not yet Collected</td>
                                                           <?php  } ?>
                                                        <?php }else{ ?>
                                                            <td style="border: 1px solid;"></td>
                                                        <?php } ?>
                                                        
                                                    </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="row-fluid">
                                <div class="span12 heading">
                                    <h5>Cash for Prisoner Details</h5>
                                </div>
                            </div>
                            <?php if(count($visitorList[0]['VisitorPrisonerCashItem'])>0){?>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table detail" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid;">Cash Details</th>
                                                    <th style="border: 1px solid;">Currency</th>
                                                    <th style="border: 1px solid;">Amount</th>
                                                    <th style="border: 1px solid;">Collected</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($visitorList[0]['VisitorPrisonerCashItem'] as $item){
                                                    ?>

                                                    <tr>
                                                        <td style="border: 1px solid;"><?php echo $item['cash_details']?></td>
                                                        <td style="border: 1px solid;"><?php echo isset($item['CashCurrency']['name'])?$item['CashCurrency']['name']:'' ; ?></td>
                                                        <td style="border: 1px solid;"><?php echo $item['pp_amount']?></td>
                                                       </td>
                                                       <?php if($item['pp_amount'] != ''){ ?>

                                                        <?php if($item['is_collected'] == 1){ ?>
                                                            <td style="color: green;border: 1px solid;">Collected</td>
                                                       <?php }else{ ?>
                                                            <td style="color: red;border: 1px solid;">Not yet Collected</td>
                                                       <?php  } ?>
                                                       <?php }else{ ?>
                                                            <td style="border: 1px solid;" ></td>
                                                       <?php } ?>

                                                    </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>


                            <div class="row-fluid">
                                <div class="span12 heading">
                                    <h5>Food Collected from Canteen</h5>
                                </div>
                            </div>
                            <?php if(count($visitorList[0]['CanteenFoodItem'])>0){?>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table detail" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid;">Food Item</th>
                                                    <th style="border: 1px solid;">Quantity</th>

                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($visitorList[0]['CanteenFoodItem'] as $item){
                                                    ?>

                                                    <tr>
                                                        <td style="border: 1px solid;"><?php echo $item['food_item']; ?></td>
                                                        <td style="border: 1px solid;"><?php echo $item['quantity']; ?></td>

                                                    </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>


                            <div class="row-fluid">
                                <div class="span12 heading">
                                    <h5>Visitors Details</h5>
                                </div>
                            </div>
                            <?php if(count($visitorList[0]['VisitorName'])>0){
                            	//debug($visitorList[0]['VisitorName']);
                            	?>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table detail" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid;">Visitor Name</th>
                                                    <th style="border: 1px solid;">Relation</th>
                                                    <th style="border: 1px solid;">Photo</th>
                                                     <th style="border: 1px solid;">National Id</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($visitorList[0]['VisitorName'] as $visitor){
                                                	//debug($visitor);
                                                	?>
                                                    <tr>
                                                        <td style="border: 1px solid;"><?php echo $visitor['name'];?></td>
                                                        
                                                        <?php if(isset($visitor['relation']) && $visitor['relation'] != '' ){ ?>
                                                        <td style="border: 1px solid;"><?php echo $funcall->getRelatioName($visitor['relation']);?></td>
                                                            <?php }else{ ?>
                                                            <td style="border: 1px solid;"></td>
                                                            <?php } ?>
                                                        <td style="border: 1px solid;">
                                                            
                                                        <?php
                                                        echo $this->Html->image('../files/visitors/'.$visitor["photo"], array('escape'=>false, 'class'=>'img', 'alt'=>'visitor photo','style'=>'width: 100px;'));
                                                        ?>
                                                        </td>
                                                        <td style="border: 1px solid;"><?php echo $visitor['nat_id'];?></td>
                                                    </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
           
                               