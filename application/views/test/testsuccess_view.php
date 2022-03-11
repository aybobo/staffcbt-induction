
                <div class="pcoded-content">
                    <!-- Page-header start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Citygate E-learning Portal</h5>
                                        <p class="m-b-0">Assessment Test Result</p>
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
                                                    <h5>Test Result</h5>
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
                                                       Dear <?php echo $name; ?>, below is the breakdown of your just concluded test:
                                                       <ul>
                                                           <li>
                                                               <strong>Total number of questions: </strong><?php echo $numofquestions; ?>
                                                           </li>
                                                           <li>
                                                               <strong>Induction Test Attempt(s): </strong><?php echo $attemptedquestions; ?>
                                                           </li>
                                                           <li>
                                                               <strong>Correct answer: </strong><?php echo $correct; ?>
                                                           </li>
                                                           <li>
                                                               <strong>Percentage Score: </strong><?php echo $score; ?>
                                                           </li>
                                                       </ul>
                                                    </p>
                                                    <?php

                                                        $message = '';
                                                        if ($score >= 50) {
                                                            $message = 'Congratulations you have passed the assessment test. Contact HR for further directives';
                                                        }
                                                        else {
                                                            $message = 'Oops! You did not meet the pass mark. You are advised to watch the videos again before retaking the test.';
                                                        }

                                                    ?>
                                                    <p>
                                                        <?php echo $message; ?>
                                                    </p>
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


