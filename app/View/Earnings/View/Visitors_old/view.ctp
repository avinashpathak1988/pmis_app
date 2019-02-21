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
                    <h5>Visitors Details</h5> 
                    <div style="float:right;padding-top:2px;">
                        <?php echo $this->Html->link('Back',array('action'=>'index'),array('class' => 'btn btn-success'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                        <div class="row-fluid">
                           <?php //debug($visitorList); ?>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table detail table-bordered table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Visitor Category</th>
                                                    <th>Date</th>
                                                    <th>Reason</th>
                                                    <th>Gate keeper</th>
                                                    <th>Bag No</th>
                                                    <th>Vehicle No</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                    <tr>
                                                        <td><?php echo  $visitorList[0]["Visitor"]['category'];?></td>
                                                        <td><?php echo  date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($visitorList[0]["Visitor"]['date']));?></td>
                                                        <td><?php echo  $visitorList[0]["Visitor"]['reason'];?></td>
                                                        <td><?php echo  $visitorList[0]["Visitor"]['gate_keeper'];?></td>
                                                        <td><?php echo  $visitorList[0]["Visitor"]['bag_no'];?></td>
                                                        <td><?php echo  $visitorList[0]["Visitor"]['vehicle_no'];?></td>

                                                    </tr>
                                                
                                            </tbody>
                                        </table>
                                        <table class="table detail">
                                            <thead>
                                                <tr>
                                                    <th>Address</th>
                                                    <th>Contact No</th>
                                                    <th>Cash Details</th>
                                                    <th>PP Cash</th>
                                                    <th>PP Amount</th>
                                                    <th>Personal Property</th>
                                                    <th>To Whom To Meet</th>
                                                    <th>Prisoner No</th>
                                                    <th>Prisoner Name</th>




                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo  $visitorList[0]["Visitor"]['address'];?></td>
                                                    <td><?php echo  $visitorList[0]["Visitor"]['contact_no'];?></td>
                                                    <td><?php echo  $visitorList[0]["Visitor"]['cash_details'];?></td>
                                                    <td><?php if($visitorList[0]["Visitor"]['pp_cash'] !=''){ ?>
                                                    <?php echo  $funcall->getPPCashName($visitorList[0]["Visitor"]['pp_cash']);?>
                                                    <?php } ?>
                                                    </td>
                                                    <td><?php if($visitorList[0]["Visitor"]['pp_amount'] !=''){ ?>
                                                    <?php echo  $visitorList[0]["Visitor"]['pp_amount'];?>
                                                    <?php } ?>
                                                    </td>
                                                    <td><?php if($visitorList[0]["Visitor"]['Personal_property'] !=''){ ?>
                                                    <?php echo  $visitorList[0]["Visitor"]['Personal_property'];?>
                                                    <?php } ?>
                                                    </td>
                                                    <td><?php if( $visitorList[0]["Visitor"]['to_whom'] != ''){ ?>
                                                    <?php echo  $visitorList[0]["Visitor"]['to_whom'];?>
                                                    <?php } ?>
                                                    </td>
                                                    <td><?php if($visitorList[0]["Visitor"]['prisoner_no'] !=''){ ?>
                                                    <?php echo  $visitorList[0]["Visitor"]['prisoner_no'];?>
                                                    <?php } ?>
                                                    </td>
                                                    <td><?php if($visitorList[0]["Visitor"]['name'] !=''){ ?>
                                                    <?php echo  $funcall->getPrisonerName($visitorList[0]["Visitor"]['name']);?>
                                                    <?php } ?>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                        <table class="table detail">
                                            <thead>
                                                <tr>
                                                    <th>Time In</th>
                                                    <th>Time Out</th>
                                                    <th>Duration</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo  $visitorList[0]["Visitor"]['time_in'];?></td>
                                                    <td><?php echo  $visitorList[0]["Visitor"]['time_out'];?></td>
                                                    <td><?php echo  $visitorList[0]["Visitor"]['duration']." Min";?></td>
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
                                        <table class="table detail">
                                            <thead>
                                                <tr>
                                                    <th>Item Name</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($visitorList[0]['VisitorItem'] as $item){
                                                	?>
                                                    <tr>
                                                        <td><?php echo $item['item'];?></td>
                                                        <td><?php echo $item['quantity'];?></td>
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
                                        <table class="table detail">
                                            <thead>
                                                <tr>
                                                    <th>Visitor Name</th>
                                                    <th>Relation</th>
                                                    <th>Photo</th>
                                                     <th>National Id</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($visitorList[0]['VisitorName'] as $visitor){
                                                	//debug($visitor);
                                                	?>
                                                    <tr>
                                                        <td><?php echo $visitor['name'];?></td>
                                                        <td><?php echo $funcall->getRelatioName($visitor['relation']);?></td>
                                                        <td>
                                                        <?php
                                                        echo $this->Html->image('../files/visitors/'.$visitor["photo"], array('escape'=>false, 'class'=>'img', 'alt'=>'visitor photo','style'=>'width: 100px;'));
                                                        ?>
                                                        </td>
                                                        <td><?php echo $visitor['nat_id'];?></td>
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
           
                               