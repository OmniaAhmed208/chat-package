<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-info elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link bg-info">
    <img src="{{ asset('tools/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">AdminLTE 3</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
      <div class="image">
        <img src="{{ asset('/tools/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Alexander Pierce</a>
      </div>
      <i class="nav-icon fas fa-cog text-white ml-auto pr-2" style="cursor: pointer" data-toggle="modal" data-target="#settings-chat"></i>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item menu-open">
          <a href="{{ route('admin.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
      </ul>

      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false" id="userList"></ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

{{-- modal of settings --}}
<div class="modal fade" id="settings-chat">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update default chat</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Change default color:</p>
        {{-- <input type="color" id="colorPicker" class="form-control" value="#000000"> --}}
        <div class="colors d-flex flex-wrap mb-3"></div>
        
        <p>Change font size:</p>
        <div class="update-messengerFont d-flex">
          <span data-font="14px">SM</span>
          <span data-font="16px">MD</span>
          <span data-font="18px">L</span>
          <span data-font="20px">XL</span>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary save-changes">Save changes</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<script>

  var userViewId;
  @if(isset($user->id))
    userViewId = '{{ $user->id }}';
  @endif

  setInterval(function() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '{{ route('fetchNewMessages') }}', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            
            var users = response.users;
            var data = response.data;
            // console.log(data)

            var userList = document.getElementById('userList'); 
            userList.innerHTML = '';

            data.forEach(function(user) {
              var userId = user.id;
              var url = "{{ route('viewChat', ['id' => '__id__']) }}"; 
              var finalUrl = url.replace('__id__', userId);
              
              if(userViewId == user.id)
              {
                user.unseen_count = 0
              }

              var html = `
                  <li class="nav-item">
                      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                          <div class="image">
                              <img src="{{ asset('/tools/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
                          </div>
                          <div class="info">
                              <a href="${finalUrl}" class="d-block">${user.name}</a>
                              ${user.unseen_count > 0 ? `
                                <span id="msgNum">
                                  <span class="badge badge-danger navbar-badge text-white" style="right:32px;font-size: .8rem">${user.unseen_count}</span>
                                </span>` : ''}
                          </div>
                      </div>
                  </li>
              `;

                userList.innerHTML += html;
            });

        } else {
            console.log('Error: ' + xhr.status);
        }
    };

    xhr.send();

}, 1000);

</script>

