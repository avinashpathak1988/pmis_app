<style>
.table.detail th
{
    text-align:left;
}
.span12.heading{padding-left:10px;}
</style>
<!-- <?php debug($priviousPrisonerIds); ?> -->
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Detection of recidivism</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php 
                        echo $this->Html->link('Back',array('action'=>'view/'.$uuid),array('escape'=>false,'class'=>'btn btn-success btn-mini pull-left')); ?>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php if(isset($priviousPrisonerIds) && count($priviousPrisonerIds)>0)
                        {
                            ?>     

                           <!--  Previous Prison details -->
                            <div class="row-fluid">
                                <div class="span12">
                                    <table class="table detail">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Prison Name</th>
                                                <th>Prison Code</th>
                                                <th>Prison Phone</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                        <?php 
                                $srNo=1;
                        foreach($priviousPrisonerIds as $key=>$value)
                            {
                                $prisonerdata = $funcall->getPrisonerDetails($value);
                                //debug($prisonerdata);
                                if(isset($prisonerdata['Prisoner']['id']) && ($prisonerdata['Prisoner']['id'] != ''))
                                {
                                    $data['Prisoner'] = $prisonerdata['Prisoner'];
                                }
                        ?>   
                                                <tr>
                                                    <td><?php echo $srNo; ?></td>
                                                    <td><?php echo $prisonerdata['Prison']['name'];?></td>
                                                    <td><?php echo $prisonerdata['Prison']['code'];?></td>
                                                    <td><?php echo $prisonerdata['Prison']['phone'];?></td>
                                                    <td>
                                                        <?php 
                        echo $this->Html->link('View',array('action'=>'viewCriminalRecord/'.$value),array('escape'=>false,'class'=>'btn btn-success btn-primary')); ?>
                                                        
                                                    </td>

                                                </tr>
                                <?php $srNo++;
                                        }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>  
                          <?php  }else{?>
                                No records Found.
                          <?php } ?>
                            <!-- previous prison details end -->    
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
// $commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
// $uuid = $data['Prisoner']['uuid'];
// $prisoner_id = $data['Prisoner']['prisoner_id'];
// echo $this->Html->scriptBlock("
//     jQuery(function($) {
//         alert(1);
//         showCommonHeader();
//     });
//     //common header
//     function showCommonHeader(){
//         var prisoner_id = '".$prisoner_id."';
//         console.log(prisoner_id);  
//         var uuid        = '".$uuid."';
//         var url         = '".$commonHeaderUrl."';
//         url = url + '/prisoner_id:'+prisoner_id;
//         url = url + '/uuid:'+uuid;
//         $.post(url, {}, function(res) {
           
//             if (res) {
//                 $('#commonheader').html(res);
//             }
//         }); 
//     }
// ",array('inline'=>false));
?>

