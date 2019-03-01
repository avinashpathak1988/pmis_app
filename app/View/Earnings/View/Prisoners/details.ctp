<?php
if(is_array($data) && count($data)>0){
?>
<div class="container-fluid"><hr>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> 
                    <h5>Prisoner's Details </h5>
                </div>
                <div class="widget-content">
                    <div class="row-fluid">
                        <div id="commonheader"></div>
                    </div>
                </div>
                <br/>
                <ul class="prisonerTabs">
                    <li>
                        <div class="prisoner-box modules">
                            <h5 class="text-center">
                                Admission details
                            </h5>
                            <p class="text-center">
                                <?php 
                                if($isAccess == 1 && $data["Prisoner"]["status"] == 'Draft')
                                {
                                    echo $this->Html->link($this->Html->image('gallery/admission.png', array('class'=>'img img-responsive')), array('controller'=>'prisoners', 'action'=>'edit', $data['Prisoner']['uuid']), array('escape'=>false));
                                }
                                else 
                                {
                                    echo $this->Html->link($this->Html->image('gallery/admission.png', array('class'=>'img img-responsive')), array('controller'=>'prisoners', 'action'=>'edit', $data['Prisoner']['uuid']), array('escape'=>false));
                                }
                                ?>
                            </p>
                        </div>
                    </li>
                    <?php 
                    if($data["Prisoner"]["is_approve"] == 1)
                    {
                        if(isset($data['Prisoner']['prisoner_type_id']) && $data['Prisoner']['prisoner_type_id'] == Configure::read('CONVICTED'))
                        {?>
                            <li>
                                <div class="prisoner-box modules">
                                    <h5 class="text-center">
                                         Sentences
                                    </h5>
                                    <p class="text-center">
                                        <?php echo $this->Html->link($this->Html->image('gallery/sentence.png', array('class'=>'img img-responsive')), array('controller'=>'prisoners', 'action'=>'../sentence/index', $data['Prisoner']['uuid']), array('escape'=>false))?>
                                    </p>
                                </div>
                            </li>
                        <?php }?>
                        <?php if ($this->Session->read('Auth.User.usertype_id')== 6) {
                            ?>
                            

                          
                        <li>
                            <div class="prisoner-box modules">
                                <h5 class="text-center">
                                    Medical Records
                                </h5>
                                <p class="text-center">
                                    <?php echo $this->Html->link($this->Html->image('gallery/medical.png', array('class'=>'img img-responsive')), array('controller'=>'medicalRecords', 'action'=>'add', $data['Prisoner']['uuid']), array('escape'=>false))?>
                                </p>
                            </div>
                        </li>
                        <?php } ?>
                    <?php }
                    if($data["Prisoner"]["status"] != 'Admitted'){?>
                    <li>
                        <div class="prisoner-box modules">
                            <h5 class="text-center">
                                Property
                            </h5>
                            <p class="text-center">
                                <?php echo $this->Html->link($this->Html->image('gallery/properties.png', array('class'=>'img img-responsive')), array('controller'=>'properties', 'action'=>'index', $data['Prisoner']['uuid']), array('escape'=>false))?>
                            </p>
                        </div>
                    </li>
                    <?php }
                    if($data["Prisoner"]["is_approve"] == 1)
                    {?>
                        <!-- <li>
                            <div class="prisoner-box modules">
                                <h5 class="text-center">
                                    Properties
                                </h5>
                                <p class="text-center">
                                    <?php //echo $this->Html->link($this->Html->image('gallery/properties.png', array('class'=>'img img-responsive')), array('controller'=>'properties', 'action'=>'index', $data['Prisoner']['uuid']), array('escape'=>false))?>
                                </p>
                            </div>
                        </li> --> 
                        <?php if(isset($data['Prisoner']['prisoner_type_id']) && $data['Prisoner']['prisoner_type_id'] != Configure::read('DEBTOR'))
                        {?>
                            <li>
                                <div class="prisoner-box modules">
                                    <h5 class="text-center">
                                        Court Attendance
                                    </h5>
                                    <p class="text-center">
                                        <?php echo $this->Html->link($this->Html->image('gallery/court_attendance.png', array('class'=>'img img-responsive')), array('controller'=>'courtattendances', 'action'=>'index', $data['Prisoner']['uuid']), array('escape'=>false))?>
                                    </p>
                                </div>  
                            </li>
                        <?php }?>
                        <?php if(isset($data['Prisoner']['prisoner_type_id']) && $data['Prisoner']['prisoner_type_id'] == Configure::read('CONVICTED'))
                        {?>
                            <li>
                                <div class="prisoner-box modules">
                                    <h5 class="text-center">
                                        Stages
                                    </h5>
                                    <p class="text-center">
                                        <?php echo $this->Html->link($this->Html->image('gallery/court_attendance.png', array('class'=>'img img-responsive')), array('controller'=>'stages', 'action'=>'stagesAssign', $data['Prisoner']['uuid']), array('escape'=>false))?>
                                    </p>
                                </div>  
                            </li> 
                        <?php }?>
                        
                        <li>
                            <div class="prisoner-box modules">
                                <h5 class="text-center">
                                    Discipline
                                </h5>
                                <p class="text-center">
                                    <?php echo $this->Html->link($this->Html->image('gallery/discipline.png', array('class'=>'img img-responsive')), array('controller'=>'inPrisonOffenceCapture', 'action'=>'index', $data['Prisoner']['uuid']), array('escape'=>false))?>
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="prisoner-box modules">
                                <h5 class="text-center">
                                    Discharge
                                </h5>
                                <p class="text-center">
                                    <?php echo $this->Html->link($this->Html->image('gallery/discharge.png', array('class'=>'img img-responsive')), array('controller'=>'discharges', 'action'=>'index', $data['Prisoner']['uuid']), array('escape'=>false))?>
                                </p>
                            </div>
                        </li>
                        
                    <?php }?>
                </ul>
                <div class="row-fluid">
                    <!-- <div class="span3">
                        <div class="prisoner-box modules">
                            <h5 class="text-center">
                                Admission details
                            </h5>
                            <p class="text-center">
                                <?php 
                                if($isAccess == 1 && $data["Prisoner"]["status"] == 'Draft')
                                {
                                    echo $this->Html->link($this->Html->image('gallery/admission.png', array('class'=>'img img-responsive')), array('controller'=>'prisoners', 'action'=>'edit', $data['Prisoner']['uuid']), array('escape'=>false));
                                }
                                else 
                                {
                                    echo $this->Html->link($this->Html->image('gallery/admission.png', array('class'=>'img img-responsive')), array('controller'=>'prisoners', 'action'=>'edit', $data['Prisoner']['uuid']), array('escape'=>false));
                                }
                                ?>
                            </p>
                        </div>    
                    </div> -->
                    <?php 
                    if($data["Prisoner"]["is_approve"] == 1)
                    {?>
                         
                    <?php }?>
                    
                    <!-- <div class="span3">
                        <div class="prisoner-box modules">
                            <h5 class="text-center">
                                Earnings
                            </h5>
                            <p class="text-center">
                                 <?php echo $this->Html->link($this->Html->image('gallery/earnings.png', array('class'=>'img img-responsive')), array('controller'=>'prisoners', 'action'=>'edit', $data['Prisoner']['uuid']), array('escape'=>false))?>
                            </p>
                        </div>
                    </div> -->
                     
                    
                </div>
                <br/>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$prisoner_id = $data['Prisoner']['id'];
echo $this->Html->scriptBlock("
    var tabs;
    jQuery(function($) {

        showCommonHeader();

    });

    //common header
    function showCommonHeader(){
        var prisoner_id = ".$prisoner_id.";
        console.log(prisoner_id);  
        var uuid        = '".$uuid."';
        var url         = '".$commonHeaderUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
           
            if (res) {
                $('#commonheader').html(res);
            }
        }); 
    }
",array('inline'=>false));
?>