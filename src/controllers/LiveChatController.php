<?php

namespace Omnia\Oalivechat\Controllers;

use Omnia\Oalivechat\Models\User;
use Omnia\Oalivechat\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LiveChatController extends Controller
{
    public function test(){
        return 'hjh';
    }
    
    public function saveData(Request $request)
    {
        try {

            $img = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $img = $file->move('attachments/',$filename);
        }

        if($img == null){
            $request->validate([
                'msg' => 'required'
            ]);
        }

        $adminId = User::where('role_for_messages', 'admin')->first();

        $message = new Messages;
        $message->msg = $request->msg;
        $message->attachment = $img;
        $message->sender = Auth::user()->id;
        $message->receiver = $adminId->id;
        $message->save();
           
        } catch (\Exception $e) {
            // Log or display the exception
            Log::error($e);
            return response('An error occurred', 500);
        }
    }

    public function getChat()
    {
        $user_id = Auth::user()->id;

        $data =  Messages::where('sender', $user_id)
        ->orWhere('receiver', $user_id)
        ->get();

        $data->transform(function ($message) {
            if ($message->attachment !== null) {
                $message->attachment_url = asset($message->attachment);
            }
            return $message;
        });

        $status = User::where('role_for_messages', 'admin')->first()->status_for_messages;

        return response()->json([
            'data' => $data,
            'status' => $status,
        ]);
        
        // return response()->json($data);
        // return $data;
    }

    public function getImage($id)
    {
        $img = Messages::find($id)->attachment;

        return view('liveChat::pages.image',compact('img'));
    }

}
