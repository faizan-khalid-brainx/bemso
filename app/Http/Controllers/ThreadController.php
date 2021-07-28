<?php

namespace App\Http\Controllers;

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
        return response()->json(['threads' => $returnable],200);
    }

    private function extract(Collection $collection,$array){
        return $collection->map(function ($thread) use ($array) {
            return (object)Arr::only($thread->getAttributes(),$array);
        });
    }
}
