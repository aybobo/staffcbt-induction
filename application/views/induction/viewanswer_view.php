
                <div class="pcoded-content">
                    <!-- Page-header start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Citygate E-learning Portal</h5>
                                        <p class="m-b-0">Answer Sheet</p>
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
                                                    <h5>Answer Sheet</h5>
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
                                                   <?php
                                                    $i = 0; $j = 0; $k = 0;
                                                    foreach ($ansonly as $v) {
                                                        foreach ($questions as $key => $question) {
                                                            if ($v->questionId == $question->questionId) {
                                                                $i++;
                                                                if ($v->optionId == 0) { //queston not attempted
                                                                    echo '<p><strong>' . $i . ' ' . $question->question . '<br><span class="text-danger">Not attempted</span></strong></p>';
                                                                }
                                                                else {
                                                                    echo '<p><strong>'.$i.' '.$question->question.'</strong>';

                                                                    if ($question->questiontype == 1) { //multichoice
                                                                        foreach ($options as $option) {
                                                                            if ($v->optionId == $option->optionId) {
                                                                                if ($option->answer == 1) { //answer is right
                                                                                    echo '<br><span class="text-success">' . $option->option . '</span>';
                                                                                    $j++;
                                                                                    $k++;
                                                                                }
                                                                                else {
                                                                                    echo '<br><span class="text-danger">' . $option->option . '</span>';
                                                                                    $k++;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    else {
                                                                        foreach ($truefalse as $key) {
                                                                            if ($key->questionId == $v->questionId) {
                                                                                if ($key->answer == $v->optionId) {
                                                                                    if ($key->answer == 1) {
                                                                                        echo '<br><span class="text-success">True</span>';
                                                                                        $j++;
                                                                                        $k++;
                                                                                    }
                                                                                    else {
                                                                                        echo '<br><span class="text-success">False</span>';
                                                                                        $j++;
                                                                                        $k++;
                                                                                    }
                                                                                }
                                                                                else {
                                                                                    if ($key->answer == 1) {
                                                                                        echo '<br><span class="text-danger">True</span>';
                                                                                        $k++;
                                                                                    }
                                                                                    else {
                                                                                        echo '<br><span class="text-danger">False</span>';
                                                                                        $k++;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    echo '</p>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                   ?>
                                                </div>
                                                <div class="card-block">
                                                    <?php
                                                    echo '<h3> Total number of questions = 100 </h3>';
                                                    echo '<h3>Attempted question(s) = ' . $k . '</h3>';
                                                    echo '<h3>Correct question(s) = ' . $j . '</h3>';

                                                    $score = (($j/60) * 100);
                                                    $score = (round($score, 2));

                                                    echo '<h3>Score = ' . $score . '%</h3>';
                                                    ?>
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


