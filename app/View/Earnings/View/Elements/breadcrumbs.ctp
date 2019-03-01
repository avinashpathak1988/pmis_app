 <div id="content-header">
    <div id="breadcrumb">
      <?php //echo '<pre>'; print_r($this->params); exit; 

      echo $this->Html->link('<i class="icon icon-home"></i> Home',array(
          'controller'=>'sites',
          'action'=>'dashboard'
      ),array(
          'escape'=>false
      )); 

      $breadcrumb = $funcall->getBreadcrumb();
      echo $breadcrumb;
        
      // $controller = $this->params['controller'];
      // $action = $this->params['action']; 
      // $pagename = '';
      // if($action == '' || $action == 'index')
      // {
      //     $pagename = ucfirst($controller);
      // }
      // else 
      // {
      //     $pagename = ucfirst($action);
      // }
      //if($pagename != '')
      //{?>
        <!-- <a><font style="color:#08c;"><?php echo $pagename;?></font></a> -->
      <?php //}?>
   </div>
</div>