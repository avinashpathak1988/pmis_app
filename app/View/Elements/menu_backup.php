<!--sidebar-menu-->

<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>


  
    <li class="active">
        <?php echo $this->Html->link('<i class="icon icon-home"></i> <span>Dashboard</span>',array(
            'controller'=>'sites',
            'action'=>'dashboard'
        ),array(
            'escape'=>false
        )); ?>
    </li>

     <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Masters</span>
            <!-- <span class="label label-important">3</span> --> </a>
      <ul>
          <li>
            <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Designations</span>',array(
            'controller'=>'designations',
            'action'=>'index'
        ),array(
            'escape'=>false
        ));
            ?>
          </li>
          <li>
            <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Departments</span>',array(
            'controller'=>'departments',
            'action'=>'index'
        ),array(
            'escape'=>false
        ));
            ?>
          </li>
          <li>
            <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Website Links</span>',array(
            'controller'=>'websites',
            'action'=>'index'
        ),array(
            'escape'=>false
        ));
            ?>
          </li>
          <li>
            <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>User Roles</span>',array(
            'controller'=>'userRoles',
            'action'=>'index'
        ),array(
            'escape'=>false
        ));
            ?>
          </li>
          <li>
            <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Users</span>',array(
            'controller'=>'users',
            'action'=>'index'
        ),array(
            'escape'=>false
        ));
            ?>
          </li>
      </ul>
    </li>


    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Manage Menu</span>
            <!-- <span class="label label-important">3</span> --> </a>
      <ul>
          <li>
            <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Menu</span>',array(
                        'controller'=>'Rollmenus',
                        'action'=>'m_menu'
                    ),array(
                        'escape'=>false
                    ));
            ?>
          </li>
          <li>
            <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Submenu</span>',array(
                      'controller'=>'Rollmenus',
                      'action'=>'m_sub_menu'
                  ),array(
                      'escape'=>false
                  ));
            ?>
          </li>
          <li>
            <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Role Menu</span>',array(
            'controller'=>'Rollmenus',
            'action'=>'m_role_menu'
        ),array(
            'escape'=>false
        ));
            ?>
          </li>
          
      </ul>
    </li>
    <!--<li> <a href="charts.html"><i class="icon icon-signal"></i> <span>Charts &amp; graphs</span></a> </li>
    <li> <a href="widgets.html"><i class="icon icon-inbox"></i> <span>Widgets</span></a> </li>
    <li><a href="tables.html"><i class="icon icon-th"></i> <span>Tables</span></a></li>
    <li><a href="grid.html"><i class="icon icon-fullscreen"></i> <span>Full width</span></a></li>
    <li><a href="buttons.html"><i class="icon icon-tint"></i> <span>Buttons &amp; icons</span></a></li>
    <li><a href="interface.html"><i class="icon icon-pencil"></i> <span>Eelements</span></a></li>-->
    <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>Archivals</span></a>
      <ul>
        <li>
          <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Categories</span>',array(
          'controller'=>'categories',
          'action'=>'index'
      ),array(
          'escape'=>false
      ));
          ?>
        </li>
        
        <li>
          <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Medias</span>',array(
          'controller'=>'medias',
          'action'=>'index'
      ),array(
          'escape'=>false
      ));
          ?>
        </li>
        <li>
          <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>New Archival</span>',array(
          'controller'=>'documents',
          'action'=>'add'
      ),array(
          'escape'=>false
      ));
          ?>
        </li>
        <li>
          <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Upload Zip</span>',array(
          'controller'=>'documents',
          'action'=>'uploadzip'
      ),array(
          'escape'=>false
      ));
          ?>
        </li>
        <li>
          <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Archivals List</span>',array(
          'controller'=>'documents',
          'action'=>'index'
      ),array(
          'escape'=>false
      ));
          ?>
        </li>
        <li>
          <?php echo $this->Html->link('<i class="icon icon-th-list"></i> <span>Folders</span>',array(
          'controller'=>'folders',
          'action'=>'index'
      ),array(
          'escape'=>false
      ));
          ?>
        </li>
      </ul>
    </li>
    <!--<li class="submenu"> <a href="#"><i class="icon icon-info-sign"></i> <span>Error</span> <span class="label label-important">4</span></a>
      <ul>
        <li><a href="error403.html">Error 403</a></li>
        <li><a href="error404.html">Error 404</a></li>
        <li><a href="error405.html">Error 405</a></li>
        <li><a href="error500.html">Error 500</a></li>
      </ul>
    </li>
    <li class="content"> <span>Monthly Bandwidth Transfer</span>
      <div class="progress progress-mini progress-danger active progress-striped">
        <div style="width: 77%;" class="bar"></div>
      </div>
      <span class="percent">77%</span>
      <div class="stat">21419.94 / 14000 MB</div>
    </li>
    <li class="content"> <span>Disk Space Usage</span>
      <div class="progress progress-mini active progress-striped">
        <div style="width: 87%;" class="bar"></div>
      </div>
      <span class="percent">87%</span>
      <div class="stat">604.44 / 4000 MB</div>
    </li>-->
  </ul>
</div>
<!--sidebar-menu-->
