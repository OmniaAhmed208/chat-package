<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge" id='count'></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

          <!-- Process and display the retrieved users -->
          <div id="latestSendersContainer"></div>
    
          <a href="{{ route('admin.chat') }}" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->


  <script>
    var userViewId;
    @if(isset($user->id))
      userViewId = '{{ $user->id }}';
    @endif
    // console.log(userViewId);

    setInterval(function() {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', '{{ route('fetchNewMessages') }}', true);
  
      xhr.onload = function() {
          if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);

            var unSeenUsersCount = response.unSeenUsersCount;
            var latestSenders = response.latestSenders;

              var count = document.getElementById('count'); 
              count.innerHTML = '';
              count.innerHTML = unSeenUsersCount;

              var latestSendersContainer = document.getElementById('latestSendersContainer'); 
              latestSendersContainer.innerHTML = '';

              latestSenders.forEach(function(user) {
                var userId = user.id;
                var url = "{{ route('viewChat', ['id' => '__id__']) }}"; 
                var finalUrl = url.replace('__id__', userId);

                  var html = `
                      <a href="${finalUrl}" class="dropdown-item">
                        <div class="media">
                          <img src="{{ asset('/tools/dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                          <div class="media-body">
                            <h3 class="dropdown-item-title">
                              ${user.name}
                            </h3>
                            <p class="text-sm" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;width:150px">${user.msg}</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>${user.created_at}</p>
                          </div>
                        </div>
                      </a>
                      <div class="dropdown-divider"></div>
                  `;

                  latestSendersContainer.innerHTML += html;
              });

          } else {
              console.log('Error: ' + xhr.status);
          }
      };
  
      xhr.send();
  
    }, 1000);
  
  </script>