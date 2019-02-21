<?php $controller = $this->params['controller'];
$action = $this->params['action']; 
$siteUrl = $this->webroot;
$currentUrl = $this->here;
$menuUrl = '/'.str_replace($siteUrl,'',$currentUrl);
//echo $menuUrl; exit;
?>
<!--sidebar-menu-->
<div id="sidebar">
    <ul>
        <li class=" <?php if($controller == 'sites' && $action == 'dashboard'){echo 'active';}?>">
            <?php echo $this->Html->link('<i class="icon icon-home"></i> <span>Dashboard</span>',array('controller'=>'sites','action'=>'dashboard'),array('escape'=>false)); ?>
        </li>
<?php
//echo '<pre>'; print_r($menu); exit;
if(isset($menu) && is_array($menu) && count($menu)>0){
    foreach($menu as $menuKey=>$menuVal){
        
        if(is_array($menuVal))
        {
            $submenuUrls = array_values($menuVal);
        }

        $addform = '/add';
        $editform = '/edit';
        if(strpos($menuUrl,$addform) !== false)
        {
            $menu_array = explode($addform,$menuUrl);
            //$menuUrl = str_replace($addform,'',$menuUrl);
            $menuUrl = $menu_array[0];
        }
        else if(strpos($menuUrl,$editform) !== false)
        {
            $menu_array = explode($editform,$menuUrl);
            //$menuUrl = str_replace($addform,'',$menuUrl);
            $menuUrl = $menu_array[0];
        }
        else 
        {
            if($controller == 'prisoners' || $controller == 'sentence' || $controller == 'medicalRecords' || $controller == 'properties' || $controller == 'stages' || $controller == 'inPrisonOffenceCapture' || $controller == 'discharges')
            {
                $menuUrl = '/prisoners';
            }
        }

        //echo $menuUrl;
        //prisoners menus 
        
        
        if(is_array($menuVal) && count($menuVal)>0){

            $multimenu = '';
            if(in_array($menuUrl,$submenuUrls))
            {
                $multimenu = 'open';
            }
?>        
        <li class="submenu <?php echo $multimenu;?>"> 
            <a href="#"><i class="icon icon-plus"></i> <span><?php echo $menuKey?></span></a>
            <ul>
<?php
            foreach($menuVal as $submenuKey=>$subMenuVal){
                
?>            
                <li class=" <?php if(is_string($subMenuVal) && ($menuUrl == $subMenuVal)){echo 'active';}?>">
                    <?php echo $this->Html->link('<i class="icon icon-cog"></i>'.$submenuKey,$subMenuVal,array('escape'=>false));?>
                </li>
<?php
            }
?>                
            </ul>
        </li>
<?php
        }else{
?>
        <li class=" <?php if(is_string($menuVal) && ($menuUrl == $menuVal)){echo 'active';}?>">
            <?php echo $this->Html->link('<i class="icon icon-folder-open"></i>'.$menuKey,$menuVal,array('escape'=>false));?>
        </li>
<?php            
        }
    }
}
?>  
    </ul>
</div>
<script>
$( document ).ready(function() {
    $('li.submenu').click(function(){
        
        //$(this).addClass('active');
    });
});
</script>