@extends('liveChat::layouts.layoutChat')

@section('content')

<div class="content-wrapper p-5">
  <div class="container">

    <div class="chat mb-0 bg-white shadow" style="border-radius: 30px">
        <div class="chatbox__header bg-info">
            <div class="chatbox__image--header">
                <img src="{{ asset('liveChat/tools/chat/logo/user.png') }}" alt="image">
            </div>
            <div class="chatbox__content--header">
                <h4 class="chatbox__heading--header">{{ $user->name }}</h4>
                {{-- <p class="chatbox__description--header">There are many variations of passages of Lorem Ipsum available</p> --}}
            </div>
        </div>

        <div class="chatbox__messages" id="msgContainer">
            <div style="word-break: break-word;">
                @php
                    $data = Omnia\Oalivechat\Models\Messages::all();
                @endphp

                @foreach ($data as $item)
                    @if ($item->sender == $user->id)
                        <div class="messages__item messages__item--visitor">
                            {{ $item->msg }}
                        </div>
                    @endif

                    @if ($item->receiver == $user->id)
                        <div class="messages__item messages__item--operator">
                            {{ $item->msg }}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <form method="POST" id="chat-form" enctype="multipart/form-data">
            @csrf
            <div class="chatbox__footer" style="flex-direction: column">
                <div class="content text-white d-flex align-items-center"></div>

                <div class="chatbox__footer p-0 m-0 bg-transparent shadow-none d-flex w-100">
                    <div class="file position-absolute">
                        <input type="file" class="position-absolute p-0 opacity-0" name="file" accept=".jpg, .jpeg, .png, .gif, .pdf, .doc, .txt" onchange="getImagePreview(event)">
                        <i class="fa-solid fa-paperclip px-2"></i>
                    </div>
                    <input type="text" placeholder="Write a message..." name="msg" id="msg">
                    <button type="submit" id="send"><i class="fa-solid fa-paper-plane text-white"></i></button>
                </div>
            </div>
        </form>

    </div>

  </div>
</div>

<script>
    var sendBtn = document.getElementById('send');

    sendBtn.addEventListener('click', function(e) {

        e.preventDefault();
        var container = document.querySelector('.chatbox__footer .content');
        container.innerHTML = '';

        var form = document.getElementById('chat-form');
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('storeChat',$user->id) }}', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                getData();
                form.reset();
            } else {
                console.log('Error: ' + xhr.status);
            }
        };

        xhr.send(formData);

    });

    function getData(){
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '{{ route('getChatAdmin',$user->id) }}', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText;

                var messages = JSON.parse(response);

                var msgContainer = document.querySelector('#msgContainer div');

                var html = '';

                messages.forEach(function(message) {
                    var messageId = message.id;
                    var url = "{{ route('image', ['id' => '__id__']) }}";
                    var finalUrl = url.replace('__id__', messageId);

                    var messageColor = getCookie('defaultColor');
                    var messageColorStyle = '';
                    if (messageColor) {
                        messageColorStyle = `background-color: ${messageColor} !important`;
                    }
                    var messageFont = getCookie('defaultFontSize');
                    var messageFontStyle = '';
                    if (messageFont) {
                        messageFontStyle = `font-size: ${messageFont} !important`;
                    }


                    if (message.sender === '{{ $user->id }}') {
                        if(message.msg != null){
                            html += `<div class="messages__item messages__item--visitor" style="${messageFontStyle}"> ${message.msg} </div>`;
                        }
                        if (message.attachment != null) {
                            if (isImage(message.attachment)) {
                                html += `<div class="messages__item messages__item--visitor" style="${messageFontStyle}">
                                            <a href="${finalUrl}" target="_blank">
                                                <img src="{{ asset('${message.attachment}') }}" class="attachment_image" alt="image">
                                            </a>
                                        </div>`;
                            } else {
                                html += `<div class="messages__item messages__item--visitor" style="${messageFontStyle}">
                                            <a href="{{ asset('${message.attachment}') }}" download> ${message.attachment} </a>
                                        </div>`;
                            }
                        }
                    } else {
                        if(message.msg != null){
                            html += `<div class="messages__item messages__item--operator" style="${messageColorStyle};${messageFontStyle}" > ${message.msg} </div>`;
                        }
                        if (message.attachment != null) {
                            if (isImage(message.attachment)) {
                                html += `<div class="messages__item messages__item--operator" style="${messageColorStyle};${messageFontStyle}">
                                            <a href="${finalUrl}" target="_blank">
                                                <img src="{{ asset('${message.attachment}') }}" class="attachment_image" alt="image">
                                            </a>
                                        </div>`;
                            } else {
                                html += `<div class="messages__item messages__item--operator" style="${messageColorStyle};${messageFontStyle}">
                                            <a href="{{ asset('${message.attachment}') }}" class="text-white" download> ${message.attachment} </a>
                                        </div>`;
                            }
                        }
                    }
                });

                msgContainer.innerHTML = html;

            } else {
                console.log('Error: ' + xhr.status);
            }
        };

        xhr.send();
    }

    function isImage(url) { // check image or file
        var imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        var extension = url.split('.').pop().toLowerCase();
        return imageExtensions.indexOf(extension) !== -1;
    }

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
            var html = `<img src="${img}" style="width: 50px; margin: 0 0 8px -60px; border-radius: 6px;">
                        <i class="fa-solid fa-close px-2" onclick="closeFile()"></i>`;
            container.innerHTML += html;
        }
        else{
            var fileName = event.target.files[0].name;
            var container = document.querySelector('.chatbox__footer .content');
            container.innerHTML = '';
            var html = `<span style="margin: 0px -60px 10px;">${fileName}
                            <i class="fa-solid fa-close px-2" onclick="closeFile()"></i>
                        </span>`;
            container.innerHTML += html;
        }
    }

    // remove image selected from input
    function closeFile(){
        document.querySelector('.chatbox__footer .content').innerHTML = '';
        document.querySelector('.chatbox__footer .file input').value = '';
    }

    function getCookie(name) {
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

@if(session('success'))
    <script>
        toastr.success('{{ session('success') }}');
    </script>
@endif

@if(session('error'))
    <script>
        toastr.error('{{ session('error') }}');
    </script>
@endif

@endsection
