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

<script>
function myFunction1() {
    var b = document.getElementById("mybut1");
    var x = document.getElementById("myDIV1");
    if (x.style.display === "none") { 
      b.style.visibility = 'hidden';
      x.style.display = "block";
      startTimer1();
    }
}
window.onload = function() {
  document.getElementById('myDIV1').style.display = 'none';
};
</script>

<script>

document.getElementById('timer1').innerHTML = '<?php echo $time; ?>';
  //03 + ":" + 00 ;

function startTimer1() {
  var presentTime = document.getElementById('timer1').innerHTML;
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var x = '';
  var s = checkSecond1((timeArray[1] - 1));
  if(s==59){m=m-1}
  if(m==0 && s==0){
    window.location.href = "<?php echo site_url('admin/logout');?>";
  }
  document.getElementById('timer1').innerHTML =
    m + ":" + s;
  setTimeout(startTimer1, 1000);
}

function checkSecond1(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}

</script>
</body>
</html>
