<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-1">
  <!-- Brand Logo -->
  <a href="#" class="brand-link bg-info">
    {{-- <img src="{{ asset('liveChat/tools/chat/logo/admin.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
    <i class="fa fa-comments px-3"></i>
    <span class="brand-text font-weight-light">Live Chat</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 mb-3 d-flex align-items-center justify-content-between border-0">
      <div class="d-flex align-items-center">
        <div class="image">
            <img src="{{ asset('liveChat/tools/chat/logo/admin.png') }}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info ml-1">
            <a href="{{ route('admin.chat') }}" class="d-block">{{ Auth::user()->name }}</a>
          </div>
        </div>
        <i class="nav-icon fas fa-cog text-white ml-auto pr-2" style="cursor: pointer" data-toggle="modal" data-target="#settings-chat"></i>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ route('admin.index') }}" class="nav-link py-3 bg-info active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
      </ul>

      <ul class="nav nav-pills nav-sidebar flex-column nav-flat mx-1" data-widget="treeview" role="menu" data-accordion="false" id="userList"></ul>
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
        <form class="form-group" action="{{ route('updateStatus', Auth::user()->id) }}"  method="POST" id="status-form">
            @csrf
            @method('put')
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="customSwitch1"  name="status" {{ Auth::user()->status_for_messages == 'online' ? 'checked' : '' }} onchange="this.form.submit()">
              <label class="custom-control-label" for="customSwitch1">Status of admin connection</label>
            </div>
        </form>

        <p>Change default color:</p>
        {{-- <input type="color" id="colorPicker" class="form-control" value="#000000"> --}}
        <div class="colors d-flex flex-wrap mb-3"></div>

        <p>Change font size:</p>
        <div class="update-messengerFont d-flex">
          <span data-font="14px">Small</span>
          <span data-font="16px">Medium</span>
          <span data-font="18px">Large</span>
          <span data-font="20px">XLarge</span>
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
                              <img src="{{ asset('liveChat/tools/chat/logo/user.png') }}" class="img-circle elevation-2" alt="User Image">
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

