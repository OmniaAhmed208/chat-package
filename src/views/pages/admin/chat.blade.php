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
                <h4 class="chatbox__heading--header">Choose person to chat</h4>
            </div>
        </div>

        <div class="chatbox__messages overflow-hidden" id="msgContainer">
            <div>
              <div class="row">
                <div class="col-6"></div>
                <div class="col-6">
                  <img src="{{ asset('/liveChat/tools/chat/images/chatPage.png') }}" class="img-fluid" style="max-width: 80%;" alt="">
                </div>
              </div>
            </div>
        </div>

        <form>
            <div class="chatbox__footer">
                <input type="text" placeholder="Write a message...">
                <i class="fa-solid fa-paper-plane text-white"></i>
            </div>
        </form>

    </div>

  </div>
</div>

@endsection
