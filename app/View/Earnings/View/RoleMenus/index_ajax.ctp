<div class="card">
<?php
if(is_array($menu) && count($menu)>0){
?>
    <ul style="list-style-type: none;">
<?php   
    foreach($menu as $menuKey=>$menuVal){
        if(isset($menuVal['child']) && is_array($menuVal['child']) && count($menuVal['child'])>0){
?>
        <li>
        <?php echo $menuVal['name']?>
            <ul style="list-style-type: none;">
<?php
            foreach($menuVal['child'] as $childKey=>$childVal){
                $childvalue = $menuVal['id'].'-'.$childVal['id'];
                $checked = '';   
                if(in_array($childVal['id'],$subMenuArr)){
                    $checked = "checked='checked'";
                }               
?>
                <li>
                    <?php echo $this->Form->checkbox("RoleMenu.menu.", array('hiddenField'=> false,'value'=>$childvalue,$checked));?>
                    <?php echo $childVal['name']?>
                </li>
<?php
            }
?>  
            </ul>   
        </li>
<?php
        }else{
            $childvalue = $menuVal['id'].'-0';
            $checked = '';   
            if(in_array($menuVal['id'],$menuArr)){
                $checked = "checked='checked'";
            }           
?>
        <li>
            <?php echo $this->Form->checkbox("RoleMenu.menu.", array('hiddenField'=> false,'value'=>$childvalue,$checked));?>
            <?php echo $menuVal['name']?>
        </li>
<?php   
        }
    }
?>
    </ul>
    <div align="center">
        <?php echo $this->Form->button('Submit', array('type'=>'submit','class'=>'btn btn-success','div'=>false,'label'=>false));?>
    </div>
<?php   
}
?>
</div>