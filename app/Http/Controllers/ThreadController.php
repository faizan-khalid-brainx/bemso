<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\ThreadParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ThreadController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $returnable = $user->threads()->get();
        $returnable = $this->extract($returnable,['id','thread_name']);
        return response()->json(['threads' => $returnable,'user_id'=>$user->id],200);
    }

    public function create(Request $request,User $user)
    {
        // finding the common threads between users
        $userThreadIds = ThreadParticipant::where('user_id',$user->id)->get(['thread_id'])
            ->pluck('thread_id')->toArray();
        $ownerThreadIds = ThreadParticipant::where('user_id',auth()->id())->get(['thread_id'])
            ->pluck('thread_id')->toArray();
        $thread_ids = array_intersect($userThreadIds,$ownerThreadIds);
        $thread_id = Thread::whereIn('id',$thread_ids)
            ->where('is_group',false)->get()->toArray();
        // if no thread exist create and return otherwise return first found
        if ($thread_id === []){
            $thread = Thread::create(['thread_name'=>$user->name,
                'thread_desc'=>'Lorem','created_at'=>now()]);
            $thread_id = $thread->id;
            ThreadParticipant::create(['thread_id'=>$thread_id,'user_id'=>$user->id]);
            ThreadParticipant::create(['thread_id'=>$thread_id,'user_id'=>auth()->id()]);
        }else{
            $thread_id = reset($thread_id);
            $thread_id = $thread_id['id'];
        }
            return response()->json(['threadId'=>$thread_id],200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'participants' => 'required',
            'groupName' => 'required'
        ]);
//        dd($validatedData['participants']);
        if ($validatedData['groupName'] !== ''){
            $thread = Thread::create(['is_group'=>1,
                'thread_name'=>$validatedData['groupName'],
                'created_at'=>now()]);
        }else{
            $thread = Thread::create(['is_group'=>1,
                'thread_name'=>'Lorem',
                'created_at'=>now()]);
            Thread::where('id',$thread->id)->update(['thread_name'=>"'Group '+$thread->id"]);
        }
        $thread_id = $thread->id;
        $thread->users()->attach([...$validatedData['participants'],...[auth()->id()]]);
//        foreach ($validatedData['participant'] as $participant){
//            ThreadParticipant::create(['thread_id'=>$thread_id,'user_id'=>$participant]);
//        }
//        ThreadParticipant::create(['thread_id'=>$thread_id,'user_id'=>auth()->id()]);
        return response()->json(['threadId'=>$thread_id],200);
    }

    private function extract(Collection $collection,$array){
        return $collection->map(function ($thread) use ($array) {
            return (object)Arr::only($thread->getAttributes(),$array);
        });
    }
}
