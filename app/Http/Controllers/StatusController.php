<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Resources\StatusResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StatusCreateRequest;
use App\Http\Requests\StatusUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class StatusController extends Controller
{
        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    // REFACTOR CODE 
    public function getStatus(User $user, int $id)
    {
        
        $status = Status::where("id", $id)->where('user_id', $user->id)->first();
        if(!$status)
        {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => ['your search not found']
                ]
            ])->setStatusCode(404)
           );
        }

        return $status;  
    }


     
    
    public function create(StatusCreateRequest $request): JsonResponse
    {
         $data = $request->validated();

         $user = Auth::user();

         if($request->file('picture'))
         {
              $file = $request->file('picture');
              $fileName = $file->getClientOriginalName();
              $filePath = 'post-images/'.$fileName;
     
              if(!File::exists($filePath)) {
                  $data['picture'] = $file->storeAs('post-images', $fileName);
              } else {
                  $data['picture'] = $filePath;
              }
         }

         $status = new Status($data);
         $status->user_id = $user->id;
         $status->save();
         return (new StatusResource($status))->response()->setStatusCode(201);

    }


    public function getAll(Request $request)
    {
        $user = Auth::user();
        $statuses = $user->statuses()->get();
        
        return StatusResource::collection($statuses);
    }






    public function update(StatusUpdateRequest $request, int $id): StatusResource
    {
        $user = Auth::user();
        $status = $this->getStatus($user, $id);
        $data = $request->validated();
    
        if($request->file('picture'))
        {
             $file = $request->file('picture');
             $fileName = $file->getClientOriginalName();
             $filePath = 'post-images/'.$fileName;
    
             if(!File::exists($filePath)) {
                 $data['picture'] = $file->storeAs('post-images', $fileName);
             } else {
                 $data['picture'] = $filePath;
             }
        }
    
        $status->fill($data);
        $status->save();
    
        return new StatusResource($status);
    }


    




    public function get(int $id): StatusResource
    {
        $user = Auth::user();
        $status = $this->getStatus($user, $id); 
        return new StatusResource($status);
    }




    public function search(Request $request)
    {
        $user = Auth::user();
    
        $statusQuery = Status::where('user_id', $user->id);
    
        $statusQuery->where(function (Builder $builder) use ($request) {
            $caption = $request->input('caption');
            if ($caption) {
                $builder->where('caption', 'like', '%' . $caption . '%');
            }
        });
    
        $status = $statusQuery->get();
    
        return StatusResource::collection($status);
    }



    public function delete(int $id)
    {
        $user = Auth::user();
        $status = $this->getStatus($user, $id);

        $status->delete();
        return response()->json([
            'data' => true
        ])->setStatusCode(200);
        
    }







}
