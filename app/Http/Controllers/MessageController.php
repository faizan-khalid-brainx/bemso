<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class MessageController extends Controller
{
    //
    public function index(Request $request, $threadId): \Illuminate\Http\JsonResponse
    {
        $messages = Message::where('thread_id', $threadId)
            ->with('user:id,name')
            ->get();
        $messages = $this->extract($messages);
        return response()->json(['messages' => $messages], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'thread_id' => 'required'
        ]);
        $message = Message::create(['sender_id' => auth()->id(),
            'thread_id'=>$request['thread_id'],
            'content'=>$request['content']]);
        return response()->json(['message'=>'message sent'],200);
    }

    private function extract(Collection $collection): Collection
    {
        $array = ['id', 'content', 'sent'];
        return $collection->map(function ($thread) use ($array) {
            $returnable = (object)Arr::only($thread->getAttributes(), $array);
            $returnable->user = (object)$thread->getRelation('user')->getAttributes();
            $returnable->sent = Carbon::parse($returnable->sent)->format('h:i A');
            return $returnable;
        });
    }
}
