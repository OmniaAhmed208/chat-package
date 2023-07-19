@extends('liveChat::layouts.layoutChat')

@section('content')
<div class="content-wrapper">
  <div class="container">

    <div class="chat mt-5 mb-0">
        <div class="chatbox__header bg-info">
            <div class="chatbox__image--header">
                <img src="{{ asset('/liveChat/tools/dist/img/user2-160x160.jpg') }}" alt="image">
            </div>
            <div class="chatbox__content--header">
                <h4 class="chatbox__heading--header">Choose person to chat</h4>
            </div>
        </div>
    
        <div class="chatbox__messages overflow-hidden" id="msgContainer">
            <div>
              <div class="row">
                <div class="col-6">
                  {{-- @if(auth()->check())
                      <p>Welcome, {{ auth()->user()->name }}</p>
                    @else
                      <p>not logged in</p>
                  @endauth --}}

                  {{-- @dd(Auth::user()) --}}
                </div>
                <div class="col-6">
                  <img src="{{ asset('/liveChat/tools/chat/images/chatPage.png') }}" class="img-fluid" alt="">
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