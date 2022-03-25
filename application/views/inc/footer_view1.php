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
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          height: '600',
          width: '600',
          videoId: '<?php echo $video; ?>',
          playerVars: {
            'playsinline': 1,
            'fs': 0,
            'modestbranding': 1,
            'rel': 0
          },
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        event.target.playVideo();
      }

      // 5. The API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      var done = false;
      function onPlayerStateChange(event) {
        uniqueId = '<?php echo $val; ?>';
        
        if (uniqueId == 0) {
            if (player.getPlayerState() == 0) {
                document.getElementById('mybut1').style.display = 'block';
            }
        }
        else {
            document.getElementById('mybut1').style.display = 'block';
        }
      }
</script>

<script>
function myFunction1() {
    var mode = <?php echo $staff->mode; ?>;
    var deptId = <?php echo $deptId; ?>;
    var xnum = <?php echo $numofquestions; ?>;
    var status = '<?php echo $staff->status; ?>';
    <?php
        $first = strtotime($staff->firstLoginDate);
        $first = date('Y-m-d', $first);
        $first  = date('Y-m-d', strtotime('+3 day', strtotime($first)));
        $today = date('Y-m-d');
    ?>
    var firstday = '<?php echo $first; ?>';
    var today = '<?php echo $today; ?>';
    if (status =='Pending') {
        var check = true;
        if (deptId == 14) { check = (mode < 10 && status == 'Pending' && firstday >= today); } //org
        else if (deptId == 10) { check = ((mode >= 10 && mode < 20) && status == 'Pending' && firstday >= today); }//hr
        else if (deptId == 11) { check = ((mode >= 20 && mode < 30) && status == 'Pending' && firstday >= today); }//mkt
        else if (deptId == 4) { check = ((mode >= 30 && mode < 40) && status == 'Pending' && firstday >= today); }//rec
        else if (deptId == 1) { check = ((mode >= 40 && mode < 50) && status == 'Pending' && firstday >= today); }//it
        else if (deptId == 6) { check = ((mode >= 50 && mode < 60) && status == 'Pending' && firstday >= today); }//optn
        else if (deptId == 2) { check = ((mode >= 60 && mode < 70) && status == 'Pending' && firstday >= today); }//fin
        else if (deptId == 3) { check = ((mode >= 70 && mode < 80) && status == 'Pending' && firstday >= today); }//itu
        else if (deptId == 5) { check = ((mode >= 80 && mode < 90) && status == 'Pending' && firstday >= today); }//ctr
        else if (deptId == 12) { check = ((mode >= 90 && mode < 100) && status == 'Pending' && firstday >= today); }//adm
        else if (deptId == 13) { check = ((mode >= 100 && mode < 110) && status == 'Pending' && firstday >= today); }//aud
        else { check = ((mode >= 110 && mode < 120) && status == 'Pending' && firstday >= today); }//legal

        if (check) {
            if (xnum < 5) { alert('Contact admin to upload questions');}
            else {
                var b = document.getElementById("mybut1");
                var x = document.getElementById("myDIV1");
                if (x.style.display === "none") { 
                  b.style.visibility = 'hidden';
                  x.style.display = "block";
                  document.getElementById('timer1').style.display = 'inline';
                  startTimer1();
                }
            }
        }
        else { alert('Sorry you are not permitted to take the test'); }
    }
    else { alert('Sorry you are not permitted to take the test'); }
}
window.onload = function() {
  document.getElementById('myDIV1').style.display = 'none';
  document.getElementById('mybut1').style.display = 'none';
  document.getElementById('timer1').style.display = 'none';
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
    var mode = <?php echo $staff->mode; ?>;
    var deptId = <?php echo $deptId; ?>;
    var rowid = '<?php echo $rowid; ?>';
    window.location.href = "<?php echo base_url('home/updateModeOnTimeOut'); ?>?mode="+mode+"&rowid="+rowid+"&deptId="+deptId;
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
