<?php

namespace Omnia\Oalivechat\Controllers;

use Illuminate\Http\Request;
use Omnia\Oalivechat\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Omnia\Oalivechat\Models\Messages;
use Illuminate\Database\QueryException;

class AdminLiveChatController extends Controller
{
    public function index(){
        return view('liveChat::pages.admin.chat');
    }

    public function chat(){
        return view('liveChat::pages.admin.chat');
    }

    public function viewChat($userId)
    {
        try{
            $user = User::findOrFail($userId);

            $userMsg = Messages::where('sender', $userId)
            ->where('is_seen', 0);

            $userMsg->update(['is_seen' => 1]);

            if(Auth::user()->role_for_messages == 'admin'){
                return view('liveChat::pages.admin.viewChat',compact('user'));
            }
        }
        catch(QueryException $exception){
            if ($exception->getCode() === 1024) {
                // $message = "Error 1024 occurred: " . $exception->getMessage();
                $message = "Code 1024: Please wait...";
                return redirect()->back()->with('success', $message);
            } else {
                $message = "An error occurred: " . $exception->getMessage();
                return redirect()->back()->with('error', $message);
            }
        } 
    }

    public function storeChat(Request $request, $userId)
    {
        try{
            $img = null;

            $data = User::find($userId);

            if ($data !== null) {
                $id = $data->id;

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
        
                $message = new Messages;
                $message->msg = $request->input('msg');
                $message->attachment = $img;
                $message->sender = Auth::user()->id;
                $message->receiver = $id;
                $message->save();
            } else {
                return response()->json(['error' => 'An error occurred while saving the chat'], 500);
            }
        }
        catch(QueryException $exception){
            if ($exception->getCode() === 1024) {
                // $message = "Error 1024 occurred: " . $exception->getMessage();
                $message = "Code 1024: Please wait...";
                return redirect()->back()->with('success', $message);
            } else {
                $message = "An error occurred: " . $exception->getMessage();
                return redirect()->back()->with('error', $message);
            }
        }     
    }


    public function getChat($userId)
    {
        try{
            $data =  Messages::where('sender', $userId)
            ->orWhere('receiver', $userId)
            ->get();

            $this->viewChat($userId); // to update unseen to 1 if i stop in userChat

            if(Auth::user()->role_for_messages == 'admin'){
                return $data;
            }
        }
        catch(QueryException $exception){
            if ($exception->getCode() === 1024) {
                // $message = "Error 1024 occurred: " . $exception->getMessage();
                $message = "Code 1024: Please wait...";
                return redirect()->back()->with('success', $message);
            } else {
                $message = "An error occurred: " . $exception->getMessage();
                return redirect()->back()->with('error', $message);
            }
        }     
    }

    public function fetchNewMessages()
    {   
        try
        {
            $userIds = Messages::pluck('sender');
            $users = User::whereIn('id', $userIds)->get();

            // for navbar blade
            $unSeenUsersCount = DB::table('messages')
            ->join('users', 'users.id', '=', 'messages.sender')
            ->where('users.role_for_messages', '=', 'user')
            ->where('is_seen', 0)
            ->distinct('sender')
            ->count('sender');

            $latestSenders = DB::table('users')
            ->select('users.*', 'messages.msg', 'messages.created_at')
            ->join('messages', function ($join) {
                $join->on('users.id', '=', 'messages.sender')
                    ->whereRaw('messages.id = (
                        SELECT MAX(id) FROM messages WHERE sender = users.id
                    )'); // to get last message for this user and not repeat him
            })
            ->where('users.role_for_messages', '=', 'user')
            ->where('messages.is_seen', '=', 0)
            ->orderBy('messages.created_at', 'desc')
            ->distinct('')
            ->take(3)
            ->get();


            // for sidebar
            $usersWithUnseenMessages = DB::table('users')
            ->where('role_for_messages', 'user')
            ->join('messages', function ($join) {
                $join->on('users.id', '=', 'messages.sender')
                    ->orWhere('users.id', '=', 'messages.receiver');
            })
            ->select('users.id', 'users.name', DB::raw('SUM(CASE WHEN messages.is_seen = 0 THEN 1 ELSE 0 END) as unseen_count'))
            ->groupBy('users.id', 'users.name')
            ->orderByRaw('(SELECT MAX(created_at) FROM messages WHERE sender = users.id OR receiver = users.id) DESC')
            ->get();
            
            if(Auth::user()->role_for_messages == 'admin'){
                return response()->json([
                    'users' => $users,
                    'unSeenUsersCount' => $unSeenUsersCount,
                    'latestSenders' => $latestSenders,
                    'data' => $usersWithUnseenMessages,
                ]);
            }
        }
        catch(QueryException $exception){
            if ($exception->getCode() === 1024) {
                // $message = "Error 1024 occurred: " . $exception->getMessage();
                $message = "Code 1024: Please wait...";
                return redirect()->back()->with('success', $message);
            } else {
                $message = "An error occurred: " . $exception->getMessage();
                return redirect()->back()->with('error', $message);
            }
        }
    }

    public function updateStatus(Request $request,$id)
    {
        try{
            $user = User::findOrFail($id);

            if($request->status == 'on'){
                $user->update(['status_for_messages' => 'online']);
            }
            else{
                $user->update(['status_for_messages' => 'offline']);
            }

            return redirect()->back();
        }
        catch(QueryException $exception){
            if ($exception->getCode() === 1024) {
                // $message = "Error 1024 occurred: " . $exception->getMessage();
                $message = "Code 1024: Please wait...";
                return redirect()->back()->with('success', $message);
            } else {
                $message = "An error occurred: " . $exception->getMessage();
                return redirect()->back()->with('error', $message);
            }
        } 
    }
}   
