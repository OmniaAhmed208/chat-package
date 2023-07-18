<footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
</footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('tools/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('tools/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('tools/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('tools/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('tools/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('tools/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('tools/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('tools/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('tools/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('tools/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('tools/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('tools/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('tools/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('tools/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('tools/dist/js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('tools/dist/js/pages/dashboard.js') }}"></script>


<script>
  
  // colors
  var colors = ['#2180f3', '#2196F3', '#00BCD4', '#3F51B5', '#673AB7', '#4CAF50', '#FFC107', '#FF9800', '#ff2522', '#9C27B0'];
  var colorContainer = document.querySelector('.colors');
  var html = '';

  colors.forEach((color,index) => 
  {
    html += `<span data-color="${color}" style="background: ${color};"></span>`;
    if(index == 4){
      html += `<span style="width:100%; height: 0; flex-basis: 100%;"></span>`;
    }
  });
  colorContainer.innerHTML += html;
  
  var colorSpans = document.querySelectorAll('.colors span');
  
  colorSpans.forEach(function(span) 
  {
    span.addEventListener('click',function()
    {
      colorSpans.forEach(function(item) {
        item.classList.remove('selected');
      });
      span.classList.add('selected');
    });
  });

  // colors
  var colorSpans = document.querySelectorAll('.colors span');
  var savedColor = getCookie('defaultColor');
  if (savedColor) {
    colorSpans.forEach(function(span) {
      if (span.dataset.color === savedColor) {
        span.classList.add('selected');
      }
    });
    document.querySelector('.main-sidebar .brand-link').style.cssText = `background-color: ${savedColor} !important`;
    document.querySelector('.nav .nav-item .active').style.cssText = `background-color: ${savedColor} !important`;
    document.querySelector('.chatbox__header').style.cssText = `background-color: ${savedColor} !important`;
  }

  // fonts
  var fonts = document.querySelectorAll('.update-messengerFont span');
  var savedFontSize = getCookie('defaultFontSize');
  fonts.forEach(function(font) 
  {
      font.addEventListener('click',function()
      {
          fonts.forEach(function(item) {
              item.classList.remove('active');
          });
          font.classList.add('active');
          var dataFont = font.getAttribute('data-font');
      });
  });

  if (savedFontSize) {
    fonts.forEach(function(font) {
        if (font.dataset.font === savedFontSize) {
            font.classList.add('active');
        }
    });
  }

  var saveButton = document.querySelector('#settings-chat .modal-footer .save-changes');

  saveButton.addEventListener('click', function() 
  {
    var selectedColor = null;
    colorSpans.forEach(function(span) {
      if (span.classList.contains('selected')) {
        selectedColor = span.dataset.color;
      }
    });

    var selectedFontSize = null;
        
    fonts.forEach(function(font) {
      if (font.classList.contains('active')) {
        selectedFontSize = font.dataset.font;
      }
    });

    setCookie('defaultColor', selectedColor, 30); // Save the color in a cookie for 30 days
    setCookie('defaultFontSize', selectedFontSize, 30);

    $('#settings-chat').modal('hide'); // Hide the modal after saving the color
    location.reload();
  });


  function setCookie(name, value, days) 
  {
    var expires = '';
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = '; expires=' + date.toUTCString();
    }
    document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/';
  }

  function getCookie(name) 
  {
    var cookieName = name + '=';
    var cookieArray = document.cookie.split(';');
    for (var i = 0; i < cookieArray.length; i++) {
      var cookie = cookieArray[i].trim();
      if (cookie.indexOf(cookieName) === 0) {
        return decodeURIComponent(cookie.substring(cookieName.length));
      }
    }
    return null;
  }
</script>

</body>
</html>
