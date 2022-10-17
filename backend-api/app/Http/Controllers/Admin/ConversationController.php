<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Conversation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ConversationController extends Controller
{
    public function list()
    {
        $conversations = DB::table('conversations')->latest()->get();
        return view('admin-views.messages.index', compact('conversations'));
    }

    public function view($user_id)
    {
        $convs = Conversation::where(['user_id' => $user_id])->get();
        Conversation::where(['user_id' => $user_id])->update(['checked' => 1]);
        $user = User::find($user_id);
        return response()->json([
            'view' => view('admin-views.messages.partials._conversations', compact('convs', 'user'))->render()
        ]);
    }

    public function store(Request $request, $user_id)
    {
        if (!$request->reply && empty($request->file('images'))) {
            return response()->json([], 403);
        }

        //if image is given
        if ($request->images) {
            $id_img_names = [];
            foreach ($request->images as $img) {
                $image = Helpers::upload('conversation/', 'png', $img);
                $image_url = $image;
                array_push($id_img_names, $image_url);
            }
            $images = $id_img_names;
        } else {
            $images = null;
        }

        DB::table('conversations')->insert([
            'user_id' => $user_id,
            'reply' => $request->reply,
            'image' => json_encode($images),
            'checked' => 1,
            'is_reply' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $convs = Conversation::where(['user_id' => $user_id])->get();
        $user = User::find($user_id);

        //send push notification
        $fcm_token = $user->cm_firebase_token;
        $data = [
            'title' => translate('New message arrived'),
            'description' => Str::limit($request->reply??'', 500),
            'order_id' => '',
            'image' => '',
            'type'=>'order_status',
        ];
        try {
            Helpers::send_push_notif_to_device($fcm_token, $data);
        } catch (\Exception $exception) {
            //
        }

        return response()->json([
            'view' => view('admin-views.messages.partials._conversations', compact('convs', 'user'))->render()
        ]);
    }

    public function update_fcm_token(Request $request)
    {
        try {
            $admin = Admin::find(auth('admin')->id());
            $admin->fcm_token = $request->fcm_token;
            $admin->save();

            return response()->json(['message' => translate('FCM token updated successfully.')], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => translate('FCM token updated failed.')], 200);
        }
    }

    public function get_conversations(Request $request)
    {
        $conversations = DB::table('conversations')->latest()->get();
        return response()->json([
            'conversation_sidebar' => view('admin-views.messages.partials._list', compact('conversations'))->render(),
        ]);
    }

    public function get_firebase_config(Request $request)
    {
        $config = Helpers::get_business_settings('firebase_message_config');
        return $config;
    }
}
