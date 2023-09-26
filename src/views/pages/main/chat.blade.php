<!doctype html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- chat --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,600;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('/liveChat/tools/chat/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('/liveChat/tools/chat/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/liveChat/tools/chat/css/typing.css') }}">
    <link rel="stylesheet" href="{{ asset('/liveChat/tools/chat/css/final.css') }}">
</head>
<body class="liveChat">
    {{-- @yield('chat') --}}
    
    <div class="chatbox">
            @auth
        <div class="chatbox__support" id="chatbox__support" style="display:none;">
            @else
        <div class="chatbox__support" style="display:none;">
            @endauth

            @php
                $admin = Omnia\Oalivechat\Models\User::where('role_for_messages', 'admin')->first();
            @endphp

            <div class="chatbox__header py-2">
                <div class="chatbox__image--header">
                    <img src="{{ asset('/liveChat/tools/chat/logo/admin.png') }}" class="img-fluid" alt="image">
                </div>
                <div class="chatbox__content--header pt-3">
                    <h4 class="chatbox__heading--header" id="comp_name">Chat with admin</h4>
                </div>
            </div>
            <div class="chatbox__messages" id="msgContainer">
                <div style="word-break: break-word;">
                    @auth

                        @php
                            $dataCout = Omnia\Oalivechat\Models\Messages::count();
                        @endphp

                        @if ($dataCout == 0)
                            <div class="" id="No_msgs"></div>
                        @else
                            @php
                                $data = Omnia\Oalivechat\Models\Messages::all();
                            @endphp

                            @foreach ($data as $item)
                                @if ($item->sender == Auth::user()->id)
                                    <div class="messages__item messages__item--operator">
                                        {{ $item->msg }}
                                    </div>
                                @endif
                                
                                @if ($item->receiver == Auth::user()->id)
                                    <div class="messages__item messages__item--visitor">
                                        {{ $item->msg }}
                                    </div>
                                @endif
                            @endforeach
                        @endif

                    @else
                        <a href="{{ route('login') }}">
                            <div class="chatbox__button">
                                <img src="{{ asset('/liveChat/tools/chat/images/icons/chatbox-icon.svg') }}" alt="">
                            </div>
                        </a>
                    @endauth
                </div>
            </div>
            <form method="POST" id="chat-form" enctype="multipart/form-data">
                @csrf
                <div class="chatbox__footer" style="flex-direction: column" id="chatbox__footer">
                    <div class="content text-white d-flex align-items-center"></div><br>
                    <div class="chatbox__footer p-0 m-0 shadow-none d-flex" style="background: transparent">
                        <div class="file position-absolute">
                            <input type="file" class="position-absolute p-0 opacity-0" style="opacity:0" name="file" accept=".jpg, .jpeg, .png, .gif, .pdf, .doc, .txt" onchange="getImagePreview(event)">
                            <i class="fa-solid fa-paperclip px-2"></i>
                        </div>
                        <input type="text" placeholder="Write a message..." name="msg" id="msg">
                        <button type="submit" id="send"><i class="fa-solid fa-paper-plane text-white"></i></button>
                    </div>
                </div>
            </form>
        </div>

        @if (Route::has('login'))
            <div class="">
                @auth
                    <div class="chatbox__button position-relative" id="chatbox__button">
                        <i class="fa fa-comments"></i>
                    </div>
                @else
                    <a href="{{ route('login') }}">
                        <div class="chatbox__button" id="toBeLogin">
                            @if ($admin && $admin->status_for_messages == 'online')
                                <i class="fa fa-comments" ></i>
                                <span class="position-absolute">live</span>
                            @else
                                <i class="fa fa-comments"></i>
                            @endif
                        </div>
                    </a>
                @endauth
            </div>
        @endif
        {{-- <a href="{{ route('test') }}">ggg</a> --}}
    </div>
    {{-- <script src="{{ asset('/liveChat/tools/chat/js/Chat.js') }}"></script> --}}
    <script src="{{ asset('/liveChat/tools/chat/js/appChat.js') }}"></script>
    
    <script>
        // chat style
        let websiteName = `<?php echo $websiteName; ?>`;
        let chatColor = `<?php echo $chatColor; ?>`;

        let comp_name = document.getElementById('comp_name');
        if(websiteName != '')
        {
            comp_name.innerHTML = websiteName;
        }
        document.querySelector('.chatbox__button i').style.color = chatColor;
        document.querySelector('.chatbox__header').style.background = chatColor;
        document.querySelector('.chatbox__footer').style.background = chatColor;
        // document.querySelector('.messages__item--operator').style.background = chatColor;
    </script>

    @auth
    <script>
    var sendBtn = document.getElementById('send');

    sendBtn.addEventListener('click', function(e) {
        e.preventDefault();
        var container = document.querySelector('.chatbox__footer .content');
        container.innerHTML = '';

        var form = document.getElementById('chat-form');
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('saveData') }}', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Data saved successfully');
                var response = xhr.responseText;
                getData();
                form.reset(); 
            } else {
                console.log('Error: ' + xhr.status);
            }
        };

        xhr.onerror = function() {
            console.log('Request failed');
        };

        xhr.send(formData);
    });

    let isStatusSpanCreated = false;

    function getData(){
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '{{ route('getChat') }}', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                var responseData = JSON.parse(response);
                
                var messages = responseData.data;
                var status = responseData.status;
                // var messages = JSON.parse(response);
                // console.log(messages)

                var msgContainer = document.querySelector('#msgContainer div');

                var html = '';

                messages.forEach(function(message) {
                    var messageId = message.id;
                    var url = "{{ route('image', ['id' => '__id__']) }}"; 
                    var finalUrl = url.replace('__id__', messageId);

                    if (message.sender === '{{ Auth::user()->id }}') {
                        if(message.msg != null){
                            html += '<div class="messages__item messages__item--operator" style="background:'+chatColor+'">' + message.msg + '</div>';
                        }
                        if (message.attachment != null) {
                            if (isImage(message.attachment)) {
                                html += `<div class="messages__item messages__item--operator" style="background:${chatColor}">
                                            <a href="${finalUrl}" target="_blank">
                                                <img src="{{ asset('${message.attachment}') }}" class="attachment_image" alt="image">
                                            </a>
                                        </div>`;
                            } else {
                                html += `<div class="messages__item messages__item--operator" style="background:'+chatColor+'">
                                            <a href="${message.attachment}" class="text-white" download>${message.attachment}</a>
                                        </div>`;
                            }
                        }
                    } else {
                        if(message.msg != null){
                            html += '<div class="messages__item messages__item--visitor">' + message.msg + '</div>';
                        }
                        if (message.attachment != null) {
                            if (isImage(message.attachment)) {
                                html += `<div class="messages__item messages__item--visitor">
                                            <a href="${finalUrl}" target="_blank">
                                                <img src="{{ asset('${message.attachment}') }}" class="attachment_image" alt="image">
                                            </a>
                                        </div>`;
                            } else {
                                html += `<div class="messages__item messages__item--visitor">
                                            <a href="${message.attachment}" class="text-black" download>${message.attachment}</a>
                                        </div>`;
                            }
                        }
                    }
                });

                msgContainer.innerHTML = html;

                var statusDiv = document.querySelector('#chatbox__button');
                var statusinLogin = document.querySelector('#toBeLogin');
                var htmlStatus = '';
                
                if(status == 'online' && !isStatusSpanCreated){
                    htmlStatus += `<span class="position-absolute">live</span>`;
                    statusDiv.innerHTML += htmlStatus;
                    isStatusSpanCreated = true;
                }
                else if (status === 'offline' && isStatusSpanCreated) {
                    statusDiv.querySelector('span').remove();
                    isStatusSpanCreated = false; 
                }
                
            } else {
                console.log('Error: ' + xhr.status);
            }
        };

        xhr.send();
    }

    function isImage(url) {
        var imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        var extension = url.split('.').pop().toLowerCase();
        return imageExtensions.indexOf(extension) !== -1;
    }
    getData();
    
    setInterval(function() {
        getData();
    }, 1000);


    // show image after choose it 
    function getImagePreview(event){
        // console.log(event.target.files[0])
        if(isImage(event.target.files[0].name)){
            var img = URL.createObjectURL(event.target.files[0])
            var container = document.querySelector('.chatbox__footer .content');
            container.innerHTML = '';
            var html = `<img src="${img}" style="width: 50px; margin: 10px 0 -20px;border-radius: 6px;">
                        <i class="fa-solid fa-close px-2" onclick="closeFile()"></i>`;
            container.innerHTML += html;
        }
        else{
            var fileName = event.target.files[0].name;
            var container = document.querySelector('.chatbox__footer .content');
            container.innerHTML = '';
            var html = `<span style="margin:13px 0 -15px">${fileName} 
                            <i class="fa-solid fa-close px-2" onclick="closeFile()"></i>
                        </span>`;
            container.innerHTML += html;
        }
    }

    function closeFile(){
        document.querySelector('.chatbox__footer .content').innerHTML = '';
        document.querySelector('.chatbox__footer .file input').value = '';
    }
    </script>    
    @endauth
    
</body>
