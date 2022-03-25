
                <div class="pcoded-content">
                    <!-- Page-header start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Citygate E-learning Portal</h5>
                                        <p class="m-b-0">Prep Test Answers - <?php echo $deptname; ?></p>
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
                                                    <h5>Prep Answers - <?php echo $deptname; ?> </h5>
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
                                                    <?php $i = 0;
                                                    foreach ($values as $value) {
                                                       foreach ($questions as $question) {
                                                           if ($question->questionId == $value) {
                                                               $i++;
                                                                echo '<p><strong>'.$i.' '.$question->question.'</strong>';

                                                                if ($question->questiontype == 1) {
                                                                    // multichoice
                                                                    foreach ($multi as $option) {
                                                                        if ($option->questionId == $question->questionId) {
                                                                            if ($option->answer == 1) {
                                                                                echo '<br><span class="text-success">Answer: '.$option->option.'</span>';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                else {
                                                                    foreach ($true as $m) {
                                                                        //true or false
                                                                        if ($m->questionId == $question->questionId) {
                                                                            if ($m->answer == 1) {
                                                                                // true
                                                                                echo '<br><span class="text-success">Answer: True</span>';
                                                                            }
                                                                            else {
                                                                                echo '<br><span class="text-success">Answer: False</span>';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                
                                                                echo '</p>';
                                                           } //question
                                                       }//foreach question
                                                    } //foreach values
                                                     ?>
                                                     
                                                </div>
                                                <div class="card-block">
                                                    <div class="row">
                                                         <div class="col-md-6">
                                                             <a href="<?=site_url('home/preporg')?>" class="btn btn-primary">Module Menu</a>
                                                         </div>
                                                         <div class="col-md-3 offset-md-3">
                                                             <?php
                                                            if ($deptId == 0) { ?>
                                                                <a href="<?=site_url('home/inductiontest')?>" class="btn btn-primary">Take Final Assessment Test</a>
                                                           <?php }
                                                            else { ?>
                                                                <?php echo form_open('home/orgtest', ['class' => 'form-horizontal']); ?>
                                                                    <input type="hidden" name="val" value="0">
                                                                    <input type="hidden" name="deptId" value="<?php echo $deptId; ?>">
                                                                    <button type="submit" class="btn btn-primary">Next Module</button>
                                                                <?php echo form_close(); ?>
                                                           <?php }
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
                    </div>
                </div>
                <div id="styleSelector">
                
                </div>
            </div>
        </div>
    </div>
</div>


