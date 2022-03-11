
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

            <?php

                /*if(isset($_GET['id'])) {
                  $id = $_GET['id'];
                }
                else {
                    $id = $this->input->post('id');
                }*/
             ?>

            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                        <?php echo form_open('Admin/activateaccount', ['class' => 'md-float-material form-material']); ?>
                        <!--<form action="{{ route('postlogin') }}" class="md-float-material form-material" method="POST">-->
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
                                    <div class="form-group form-primary">
                                        <input type="text" name="email" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Your Email Address</label>
                                       <?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
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

                                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                                    <!--<div class="row m-t-25 text-left">
                                        <div class="col-12">
                                            <div class="checkbox-fade fade-in-primary d-">
                                                <label>
                                                    <input type="checkbox" value="">
                                                    <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                    <span class="text-inverse">Remember me</span>
                                                </label>
                                            </div>
                                            <div class="forgot-phone text-right f-right">
                                                <a href="#" class="text-right f-w-600"> Forgot Password?</a>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="row m-t-30">
                                        <div class="col-md-12">
                                            <div class="btn-sign-in">
                                                <button type="submit" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20">Activate Account</button>
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