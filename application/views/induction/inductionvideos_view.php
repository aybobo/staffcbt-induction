
                <div class="pcoded-content">
                    <!-- Page-header start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Citygate E-learning Portal</h5>
                                        <p class="m-b-0">Induction Videos</p>
                                    </div>
                                </div>
                                <!--<div class="col-md-4">
                                    <ul class="breadcrumb-title">
                                        <li class="breadcrumb-item">
                                            <a href="index.html"> <i class="fa fa-home"></i> </a>
                                        </li>
                                        <li class="breadcrumb-item"><a href="#!">Pages</a>
                                        </li>
                                        <li class="breadcrumb-item"><a href="#!">Sample Page</a>
                                        </li>
                                    </ul>
                                </div>-->
                            </div>
                        </div>
                    </div>
                    <!-- Page-header end -->
                    <div class="pcoded-inner-content">
                        <div class="main-body">
                            <div class="page-wrapper">
                                <div class="page-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5></h5>
                                                    <!--<span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span>-->
                                                    <div class="card-header-right">
                                                        <ul class="list-unstyled card-option">
                                                            <li><i class="fa fa-chevron-left"></i></li>
                                                            <li><i class="fa fa-window-maximize full-card"></i></li>
                                                            <li><i class="fa fa-minus minimize-card"></i></li>
                                                            <li><i class="fa fa-refresh reload-card"></i></li>
                                                            <li><i class="fa fa-times close-card"></i></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-block">
                                                    <p>
                                                        Below are the induction videos for each department. Take your time to watch them before proceeding to take assessment test.
                                                    </p>

                                                    <?php 

                                                    foreach ($urls as $url) { ?>
                                                        
                                                   
                                                    <h3 class="text-center"><?php echo $url->deptName;  ?></h3>
                                                    <div class="row">
                                                        <div class="col-md-8 offset-md-2">
                                                            <div class="iframe-container">
                                                                <?php echo $url->url; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php  } ?>

                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <?php  echo $links; ?>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="styleSelector">
                
                </div>
            </div>
        </div>
    </div>
</div>


