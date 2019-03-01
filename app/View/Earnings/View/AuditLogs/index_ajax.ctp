<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'AuditLogs',
            'action'                => 'indexAjax',
            'from_date'             => $from_date,
            'to_date'               => $to_date,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} ')
));
?>
<?php
    $exUrl = "indexAjax/from_date:$from_date/to_date:$to_date";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    </div>
</div>
<?php
    }
?>                    
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Prison</th>
            <th>User</th>
            <th>IP Address</th>
            <th>Audit Date</th>            
            <th>Mac Address</th>
            <th>Operation Type</th>
            <th>Operation Details</th>
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
        //$modelArr = $funcall->getLabelsByModel($data['AuditLog']['model_name']);
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo isset($data['Prison']['name'])?$data['Prison']['name']:'--'; ?></td>
            <td><?php echo isset($data['User']['name'])?$data['User']['name']:'--'; ?></td>
            <td><?php echo $data['AuditLog']['ip_address']; ?></td>
            <td><?php echo date('d-m-Y H:i:s a', strtotime($data['AuditLog']['audit_date_time'])); ?></td>
            <td><?php echo $data['AuditLog']['mac_address']; ?></td>
            <td><?php echo $data['AuditLog']['operation_type']; ?></td>
            <td>
                <?php echo $this->Form->button('View', array('type'=>'button', 'class'=>'btn btn-success btn-mini', 'data-toggle'=>'modal', 'data-target'=>'#myModal'.$rowCnt))?>
                <div id="myModal<?php echo $rowCnt?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Operation Details</h4>
                            </div>
                            <div class="modal-body">
<?php   
                        //echo $data['AuditLog']['operation_details'];
                            if(!empty($data['AuditLog']['operation_details']))
                            {
                                $opdetails = json_decode($data['AuditLog']['operation_details']);
                                $opdetailsData = '';
                                //echo '<pre>'; print_r($opdetails); count($opdetails);
                                if(count((array)$opdetails) > 0 && is_array((array)$opdetails))
                                {  
                                    foreach($opdetails as $opdetailsKey=>$opdetailsValue)
                                    {
                                        if(!empty($opdetailsData))
                                        {
                                            $opdetailsData .= '<br>';
                                        }
                                        if(isset($opdetailsValue) && count((array)$opdetailsValue) > 1  && is_array((array)$opdetailsValue))
                                        {
                                            $i = 0;
                                            foreach($opdetailsValue as $opdetailsValueKey=>$opdetailsValueValue)
                                            {
                                                if($i > 0)
                                                {
                                                    $opdetailsData .= '<br>';
                                                }
                                                if(is_string($opdetailsValueValue))
                                                {
                                                    $opdetailsData .= '<strong>'.ucfirst($opdetailsValueKey).'</strong>: '.$opdetailsValueValue;
                                                }
                                                // else 
                                                // {
                                                //     $opdetailsValueValue =(array)$opdetailsValueValue;
                                                // }
                                                $i++;
                                            }
                                        }
                                        else
                                        {
                                            if($data['AuditLog']['model_name'] == 'ApprovalProcess')
                                            {
                                                if(isset($opdetailsValue) && count((array)$opdetailsValue) > 0  && is_array((array)$opdetailsValue))
                                                {
                                                    $i = 0;
                                                    foreach($opdetailsValue as $opdetailsValueKey=>$opdetailsValueValue)
                                                    { 
                                                        if(count((array)$opdetailsValueValue) > 0)
                                                        {
                                                            foreach((array)$opdetailsValueValue as $appKey=>$appValue)
                                                            {
                                                               if($i > 0)
                                                                {
                                                                    $opdetailsData .= '<br>';
                                                                }
                                                                $opdetailsData .= '<strong>'.ucfirst($appKey).'</strong>: '.$appValue;
                                                                $i++; 
                                                            }                
                                                        }
                                                    }
                                                }
                                            }
                                            else 
                                            {
                                                if(is_string($opdetailsValue))
                                                {
                                                    $opdetailsData .= '<strong>'.ucfirst($opdetailsKey).'</strong>: '.$opdetailsValue;
                                                }
                                                else 
                                                {
                                                    $opdetailsValue = (array)$opdetailsValue;
                                                    $i = 0;
                                                    foreach((array)$opdetailsValue as $appKey=>$appValue)
                                                    {
                                                       if($i > 0)
                                                        {
                                                            $opdetailsData .= '<br>';
                                                        }
                                                        $opdetailsData .= '<strong>'.ucfirst($appKey).'</strong>: '.$appValue;
                                                        $i++; 
                                                    } 
                                                }
                                            }
                                        }
                                    }
                                }
                                else 
                                {
                                    $opdetailsData = $data['AuditLog']['operation_details'];
                                }
                                echo $opdetailsData;
                            }?>
                            </div>
                        </div>
                    </div>
                </div>                
            </td>
        </tr>
<?php
        $rowCnt++;
    }
?>
    </tbody>
</table>
<?php echo $this->Js->writeBuffer();
}else{
echo Configure::read('NO-RECORD');   
}
?>                    