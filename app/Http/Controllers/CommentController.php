<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Policies\StatusPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Resources\StatusResource;
use App\Http\Resources\CommentResource;
use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class CommentController extends Controller
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
    


    /**
     *  refactor
    */
    public function getStatus(User $user, int $idstatus)
    {
        $status = Status::where('user_id', $user->id)->where('id', $idstatus)->first();

        if(!$status)
        {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => ['not found']
                ]
                ], 404));
        }
        return $status;
    }


    /**
     *  refactor get comment
     *  
    */

    public function getComment(Status $status, int $idcomment)
    {
         $comment = Comment::where('status_id', $status->id)->where('id', $idcomment)->first();
         if(!$comment)
         {
            throw new HttpResponseException(response([
                "errors" => [
                    'message' => ['not found']
                ]
                ], 404));
         }
         return $comment;
    }


    public function riwayatComment(Request $request)
    {
        $user = Auth::user();
    
        $comments = Comment::where('user_id', $user->id)->get();
    
        return CommentResource::collection($comments);
    }



    /**
     *  refactor
     *  php artisan make:policy StatusPolicy --model=Status
     *  
    */
    public function getStatusUsers(User $user, int $idstatus)
    {
        $status = Status::findOrFail($idstatus);
    
        if (app(StatusPolicy::class)->view(Auth::user(), $status)) {
            return new StatusResource($status);
        }
    
        return response()->json([
            'message' => 'You are not authorized to access this status.',
        ], Response::HTTP_FORBIDDEN);
    }



    /**
     *  CREATE
     *  
    */
    public function create($idstatus, CommentCreateRequest $request)
    {
        $user = Auth::user();
      
        $status = $this->getStatusUsers($user, $idstatus);
    
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
    
        $comment = new Comment($data);
        
        $comment->status_id = $status->id;
        $comment->user_id = $user->id;
        $comment->save();
        $comment->load('status', 'user');
    
        return (new CommentResource($comment))->response()->setStatusCode(201);
    }




    /**
     *  GET
     *  
    */


    public function update( int $idstatus, int $idcomment , CommentUpdateRequest $request)
    {
        // ambil user yg login sekarang
        $user = Auth::user();
        // ambil status dari user yg login sekarang
        $status = $this->getStatus($user, $idstatus);
        // ambil comment dari status user yg login sekarang
        $comment = $this->getComment($status, $idcomment);
          
        $data = $request->validated();
        if($request->file('picture'))
        {
            $file = $request->file('picture');
            $fileName = $file->getClientOriginalName();
            $filePath = 'post-images/'.$fileName;
            // cek jika file tidak ada
            if(!File::exists($filePath))
            {
               $data['picture'] = $file->storeAs('post-images', $fileName);
            } else {
            // selain dari itu jika file nya ada simpan di
              $data['picture'] = $filePath;    
            }
        }
        
        $comment->fill($data);
        $comment->save(); 
        dd($comment);
        return new CommentResource($comment);
        
    }


    public function delete(int $idstatus, int $idcomment)
    {
         $user = Auth::user();
         $status = $this->getStatus($user, $idstatus);
         $comment = $this->getComment($status, $idcomment);

         $comment->delete();
         return response()->json([
            "data" => true
         ])->setStatusCode(200);

    }

    

    

}
