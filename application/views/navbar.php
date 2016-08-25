            <!-- Static navbar -->
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">                        
                        <a class="navbar-brand" href="#">#</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <?php $class = strtolower($this->router->fetch_class()); ?>
                            <li class="<?php echo $class == 'apps' ? 'active' : '' ?>">
                                <a href="<?php echo base_url('apps') ?>">Apps Data</a>
                            </li>
                            <li class="<?php echo $class == 'push' ? 'active' : '' ?>">
                                <a href="<?php echo base_url('push') ?>">Push Data</a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="<?php echo base_url('signin/out') ?>">Signout</a></li>
                        </ul>                        
                    </div><!--/.nav-collapse -->
                </div><!--/.container-fluid -->
            </nav>

