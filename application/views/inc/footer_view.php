<!-- Warning Section Starts -->
<!-- Older IE warning message -->
<!--[if lt IE 10]>
<div class="ie-warning">
    <h1>Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers
        to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="assets/images/browser/chrome.png" alt="Chrome">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="assets/images/browser/firefox.png" alt="Firefox">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="assets/images/browser/opera.png" alt="Opera">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="assets/images/browser/safari.png" alt="Safari">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="assets/images/browser/ie.png" alt="">
                    <div>IE (9 & above)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->
<!-- Warning Section Ends -->
<!-- Required Jquery -->
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-ui/jquery-ui.min.js "></script>    <script type="text/javascript" src="<?=base_url()?>assets/js/popper.js/popper.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/bootstrap/js/bootstrap.min.js "></script>
<!-- waves js -->
<script src="<?=base_url()?>assets/pages/waves/js/waves.min.js"></script>
<!-- jquery slimscroll js -->
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-slimscroll/jquery.slimscroll.js"></script>
<!-- modernizr js -->
<script type="text/javascript" src="<?=base_url()?>assets/js/SmoothScroll.js"></script>
<script src="<?=base_url()?>assets/js/jquery.mCustomScrollbar.concat.min.js "></script>
<script src="<?=base_url()?>assets/js/pcoded.min.js"></script>
<script src="<?=base_url()?>assets/js/vertical-layout.min.js "></script>
<script src="<?=base_url()?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- Custom js -->
<script type="text/javascript" src="<?=base_url()?>assets/js/script.js"></script>
<!-- data table -->
<script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<!-- datepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
<script>
   $(document).ready( function () {
        $('#datatable').DataTable();
    } );
</script>

<script>
  $(document).ready(function() {
    $('#dept').change(function() {
      var deptId = $('#dept').val();
      if (deptId != '') 
      {
        $.ajax({
          url:"<?php echo base_url(); ?>superadmin/fetchposts",
          method:"POST",
          data:{deptId:deptId},
          success:function(data)
          {
            $('#post').html(data);
          }
        });
      }
    });
  });
</script>
<script>
  function mybranch1() {
    var e = document.getElementById("branch");
    var branchid = e.options[e.selectedIndex].value;
    if (branchid =='')
    {
      document.getElementById('dept').disabled = true;
      document.getElementById('post').disabled = true;
      
      document.getElementById("dept").value = '';
      document.getElementById("post").value = '';
    }
    else {
      document.getElementById('dept').disabled = false;
    }
  }
</script>
<script>
  function mydept1() {
    var e = document.getElementById("dept");
    var deptid = e.options[e.selectedIndex].value;
    if (deptid =='')
    {
      document.getElementById('post').disabled = true;
      
      document.getElementById("post").value = '';
    }
    else {
      document.getElementById('post').disabled = false;
    }
  }
</script>

<script>
function myFunction() {
      var b = document.getElementById("mybut");
      var x = document.getElementById("myDIV");
      var staffid = <?php echo $staff->userId; ?>;
      if (x.style.display === "none") { 
      b.style.visibility = 'hidden';
      if (staffid != '') 
      {
        $.ajax({
          url:"<?php echo base_url(); ?>home/updateCount",
          method:"POST",
          data:{staffid:staffid},
          success:function(data)
          {
            document.getElementById("noofattempts").value = data;
            document.getElementById('timer').style.display = 'inline';
            x.style.display = "block";
            startTimer();
          }
        });
      } 
    }
}

window.onload = function() {
  document.getElementById('timer').style.display = 'none';
  document.getElementById('myDIV').style.display = 'none';
};
</script>

<script>

document.getElementById('timer').innerHTML = '<?php echo $time; ?>';
  //03 + ":" + 00 ;

function startTimer() {
  var presentTime = document.getElementById('timer').innerHTML;
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var x = '';
  //var staffid = <?php echo $staff->userId; ?>;
  var s = checkSecond((timeArray[1] - 1));
  if(s==59){m=m-1}
  if(m==0 && s==0){
    window.location.href = "<?php echo site_url('admin/logout');?>";
  }
  document.getElementById('timer').innerHTML =
    m + ":" + s;
  setTimeout(startTimer, 1000);
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}

</script>

<script>
  $(function() {
    $('#picker').datepicker({
      'format': 'yyyy-mm-dd',
      'autoclose': true
    });
  });
</script>
<script>
  $('#confirm').on('show.bs.modal', function(e) {
      var id = $(e.relatedTarget).data('id');
      $(e.currentTarget).find('textarea[name="id"]').val(id);
  });
</script>

<!-- -->
<script>
  $(document).ready(function() {
    $('#dept1').change(function() {
      var deptId = $('#dept1').val();
      if (deptId != '') 
      {
        $.ajax({
          url:"<?php echo base_url(); ?>admin/fetchposts",
          method:"POST",
          data:{deptId:deptId},
          success:function(data)
          {
            $('#post1').html(data);
          }
        });
      }
    });
  });
</script>
<script>
  function mybranch() {
    var e = document.getElementById("branch1");
    var branchid = e.options[e.selectedIndex].value;
    if (branchid =='')
    {
      document.getElementById('dept1').disabled = true;
      document.getElementById('post1').disabled = true;
      
      document.getElementById("dept1").value = '';
      document.getElementById("post1").value = '';
    }
    else {
      document.getElementById('dept1').disabled = false;
    }
  }
</script>
<script>
  function mydept() {
    var e = document.getElementById("dept1");
    var deptid = e.options[e.selectedIndex].value;
    if (deptid =='')
    {
      document.getElementById('post1').disabled = true;
      
      document.getElementById("post1").value = '';
    }
    else {
      document.getElementById('post1').disabled = false;
    }
  }
</script>
<script>
  function qtypes() {
    var e = document.getElementById("qtype");
    var x = e.options[e.selectedIndex].value;
    if (x =='')
    {
      document.getElementById('yes').disabled = true;
      document.getElementById("yes").value = '';
      document.getElementById('option1').disabled = true;
      document.getElementById("option1").value = '';
      document.getElementById('option2').disabled = true;
      document.getElementById("option2").value = '';
      document.getElementById('option3').disabled = true;
      document.getElementById("option3").value = '';
      document.getElementById('option4').disabled = true;
      document.getElementById("option4").value = '';
    }
    else if (x == 1) {
      document.getElementById('yes').disabled = true;
      document.getElementById("yes").value = '';
      document.getElementById('option1').disabled = false;
      document.getElementById('option2').disabled = false;
      document.getElementById('option3').disabled = false;
      document.getElementById('option4').disabled = false;
    }
    else {
      document.getElementById('yes').disabled = false;
      document.getElementById('option1').disabled = true;
      document.getElementById("option1").value = '';
      document.getElementById('option2').disabled = true;
      document.getElementById("option2").value = '';
      document.getElementById('option3').disabled = true;
      document.getElementById("option3").value = '';
      document.getElementById('option4').disabled = true;
      document.getElementById("option4").value = '';
    }
  }
</script>
</body>
</html>
