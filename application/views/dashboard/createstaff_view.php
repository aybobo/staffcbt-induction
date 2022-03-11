
<!-- ico font -->
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/icon/icofont/css/icofont.css">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/css.css">

</head>

<body themebg-pattern="theme1">
  <!-- Pre-loader start -->
  <div class="theme-loader">
      <div class="loader-track">
          <div class="preloader-wrapper">
              <div class="spinner-layer spinner-blue">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
              <div class="spinner-layer spinner-red">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
            
              <div class="spinner-layer spinner-yellow">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
            
              <div class="spinner-layer spinner-green">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- Pre-loader end -->

    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <?php if($msg = $this->session->flashdata('msg')) {
                        echo '<div class="text-danger"><strong>' . $msg . '</strong></div>';  } ?>
                    <?php if($msg = $this->session->flashdata('success')) {
                        echo '<div class="text-success"><strong>' . $msg . '</strong></div>';  } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                        <?php echo form_open('Admin/addstaff', ['class' => 'md-float-material form-material']); ?>
                            <div class="text-center">
                                <img src="<?=base_url()?>assets/images/logo.png" alt="logo.png">
                            </div>
                            <div class="auth-box card">
                                <div class="card-block">
                                    <div class="row m-b-20">
                                        <div class="col-md-12">
                                            <h3 class="text-center">Citygate E-learning</h3>
                                        </div>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" required="" name="fname" class="form-control" value="<?php echo (isset($_POST['fname']) ? $_POST['fname'] : ''); ?>">
                                        <span class="form-bar"></span>
                                        <label class="float-label">First Name</label>
                                        <?php echo form_error('fname', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" required="" name="lname" class="form-control" value="<?php echo (isset($_POST['lname']) ? $_POST['lname'] : ''); ?>">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Last Name</label>
                                        <?php echo form_error('lname', '<div class="text-danger">', '</div>'); ?>
                                    </div>

                                    <div class="form-group form-default">
                                        <input type="text" required="" name="email" class="form-control" value="<?php echo (isset($_POST['email']) ? $_POST['email'] : ''); ?>">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Your Official Email Address</label>
                                        <?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
                                    </div>

                                    <!-- branch -->

                                    <div class="form-group row">
                                        <!--<label class="col-sm-3 col-form-label">Department</label>-->
                                        <div class="col-sm-12">
                                            <select name="branch" class="form-control" id="branch1" onchange="mybranch()">
                                                <option value="">Choose branch</option>
                                                <?php
                                                foreach($branches as $branch)
                                                    { 
                                                      echo '<option value="'.$branch->branchId.'">'.$branch->branchName.'</option>';
                                                    }
                                                ?>
                                            </select>
                                            <?php echo form_error('branch', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>


                                    <!-- dept  -->

                                    <div class="form-group row">
                                        <!--<label class="col-sm-3 col-form-label">Department</label>-->
                                        <div class="col-sm-12">
                                            <select name="dept" id="dept1" class="form-control" onchange="mydept()" disabled="FALSE">
                                                <option value="">Choose department</option>
                                                <?php
                                                foreach($depts as $dept)
                                                    { 
                                                      echo '<option value="'.$dept->deptId.'">'.$dept->deptName.'</option>';
                                                    }
                                                ?>
                                            </select>
                                            <?php echo form_error('dept', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>

                                    <!-- post -->

                                    <div class="form-group row">
                                        <!--<label class="col-sm-3 col-form-label">Department</label>-->
                                        <div class="col-sm-12">
                                            <select name="post" id="post1" class="form-control" disabled="FALSE">
                                                <option value="">Choose staff post</option>
                                            </select>
                                            <?php echo form_error('post', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-primary">
                                        <input type="password" name="password" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Password</label>
                                        <?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
                                    </div>

                                    <div class="form-group form-primary">
                                        <input type="password" name="cpassword" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Confirm Password</label>
                                        <?php echo form_error('cpassword', '<div class="text-danger">', '</div>'); ?>
                                    </div>

                                    <div class="row m-t-30">
                                        <div class="col-md-12">
                                            <div class="btn-sign-in">
                                                <button type="submit" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<hr/>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <p class="text-inverse text-left m-b-0">Thank you.</p>
                                            <p class="text-inverse text-left"><a href="index.html"><b>Back to website</b></a></p>
                                        </div>
                                        <div class="col-md-2">
                                            <img src="assets/images/auth/Logo-small-bottom.png" alt="small-logo.png">
                                        </div>
                                    </div>-->
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                        <!-- end of form -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>