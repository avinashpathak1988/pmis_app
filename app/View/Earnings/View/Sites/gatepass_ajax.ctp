<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#gatepassListingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
            'url'                   => array(
            'controller'            => 'discharges',
            'action'                => 'gatepassAjax'
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
    </div>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
            <th><?php echo $this->Paginator->sort('Gate Pass No.'); ?></th>                
            <th><?php echo $this->Paginator->sort('Escort'); ?></th>
            <th><?php echo $this->Paginator->sort('Date'); ?></th>
            <th><?php echo $this->Paginator->sort('Permission is granted for'); ?></th>
            <th><?php echo $this->Paginator->sort('Purpose'); ?></th>            
            <th><?php echo $this->Paginator->sort('in_time'); ?></th>            
            <th><?php echo $this->Paginator->sort('out_time'); ?></th>            
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
    // debug($data['GateBioPass']);
?>
        <tr>
            <td><?php echo $rowCnt; ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['GateBioPass']['gp_no'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($funcall->getName($data['GateBioPass']['user_id'],"User","name"))); ?>&nbsp;</td>
            <td>
            <?php 
            if($data['GateBioPass']['gp_date'] != '0000-00-00')
                echo ucwords(h(date('d-m-Y', strtotime($data['GateBioPass']['gp_date']))));
            else
                echo 'N/A';?>
            &nbsp;
            </td> 
            <td><?php echo ucwords(h($data['GateBioPass']['destination'])); ?>&nbsp;</td>            
            <td><?php echo ucwords(h($data['GateBioPass']['purpose'])); ?>&nbsp;</td>
            <td>
            <?php
            if(isset($data['GateBioPass']['in_time']) && $data['GateBioPass']['in_time']=='0000-00-00 00:00:00'){
                ?>
                <span id="link_biometric_span_in<?php echo $data['GateBioPass']['id']; ?>"></span>
                <?php 
                echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn','id'=>'link_biometric_button_in'.$data['GateBioPass']['id'],"onclick"=>"checkData(".$data['GateBioPass']['prisoner_id'].",".$data['GateBioPass']['id'].",'in')"));

                ?>&nbsp;
                <?php
            }else{
                echo h(date("d-m-Y h:i A",strtotime($data['GateBioPass']['in_time'])));
            }
            ?>
            </td>
            <td>
            <?php
            if(isset($data['GateBioPass']['out_time']) && $data['GateBioPass']['out_time']=='0000-00-00 00:00:00'){
                ?>
                <span id="link_biometric_span_out<?php echo $data['GateBioPass']['id']; ?>"></span>
                <?php 
                echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn','id'=>'link_biometric_button_out'.$data['GateBioPass']['id'],"onclick"=>"checkData(".$data['GateBioPass']['prisoner_id'].",".$data['GateBioPass']['id'].",'out')"));

                ?>&nbsp;
                <?php
            }else{
                echo h(date("d-m-Y h:i A",strtotime($data['GateBioPass']['out_time'])));
            }
                ?>
            </td>
        </tr>
<?php
        $rowCnt++;
    }
?>
    </tbody>
</table>
<?php
}else{
?>
    ...
<?php    
}
?>    