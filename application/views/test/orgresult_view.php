
                <div class="pcoded-content">
                    <!-- Page-header start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Citygate E-learning Portal</h5>
                                        <p class="m-b-0">Prep Test - Result (<?php echo $deptname; ?>)</p>
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
                                                    <h5>Test Result - <?php echo $deptname; ?></h5>
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
                                                        $parts = explode("@", $value);
                                                        foreach ($questions as $question) {
                                                            

                                                            //one of the questions
                                                            if ($question->questionId == $parts[0]) {
                                                                $i++;
                                                                echo '<p><strong>'.$i.' '.$question->question.'</strong>';

                                                                if (count($parts) > 2) {//multichoice ?>
                                                                    <span>
                                                                        <?php
                                                                            foreach ($multi as $mm) {
                                                                                if ($question->questionId == $mm->questionId) {
                                                                                  if ($mm->answer == 1) {
                                                                                      echo '<br><span class="text-success">'.$mm->option.'</span>';
                                                                                  }
                                                                                }
                                                                            }

                                                                            if ($parts[2] == 0) {
                                                                              foreach ($multi as $mm) {
                                                                                  if ($mm->optionId == $parts[1]) {
                                                                                     echo '<br><span class="text-danger">'.$mm->option.'</span>';
                                                                                  }
                                                                              }
                                                                            }
                                                                         ?>
                                                                    </span>
                                                              <?php  }
                                                                else { ?>
                                                                    <span>
                                                                        <?php 
                                                                        foreach ($true as $xx) {
                                                                            if ($xx->questionId == $question->questionId) {
                                                                                 if ($xx->answer == $parts[1]) {
                                                                                     $y = 'True';
                                                                                 echo '<br><span class="text-success">'.$y.'</span>';
                                                                                 }
                                                                                  else {
                                                                                $z = 'False';
                                                                                echo '<br><span class="text-danger">'.$z.'</span>';
                                                                                }
                                                                            }
                                                                        }

                                                                         ?>
                                                                    </span>
                                                              <?php  }
                                                              echo '</p>' ;
                                                            }
                                                        }
                                                    }
                                                     ?>
                                                     
                                                </div>
                                                <div class="card-block">
                                                    <div class="row">
                                                         <div class="col-md-6">
                                                             <a href="<?=site_url('home/preporg')?>" class="btn btn-primary">Module Menu</a>
                                                         </div>
                                                         <div class="col-md-2 offset-md-4">
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


