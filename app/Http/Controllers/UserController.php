<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\HttpResponses;
use App\Models\User;
use App\Http\Resources\UserResource;


class UserController extends Controller
{

    use HttpResponses;

    public function storeUser(StoreUserRequest $request)
    {
        $request->validated($request->all());
        
        /*validate if unique*/
        if(!$request->id){
            $request->validate(['email' => ['unique:users']]);
            $user = new User;
        }else{
            $user = User::where('id',$request->id)->first();
        }
        $user->name =  $request->name;
        $user->email =  $request->email;
        $user->password = Hash::make('password');
        !$request->id ? $user->save() : $user->update();

        /* delete role first and then assign to roles */
        if(!!$request->id){
            $roles = DB::table('user_link_roles')
            ->where('user_id',$request->id) 
            ->delete();
        }
        
        foreach($request->roles as $role_id){
            DB::table('user_link_roles')->insert(
                ['role_id' => $role_id, 'user_id'=> $user->id]
            );
        }
        
        $response = new UserResource($user);

        return $this->success($response);
    }

     /**
     * Show the list of users
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1; 
        $perPage = $request->perPage ?? 100;  

        $users = User::join('user_link_roles','users.id','user_link_roles.user_id')
                    ->join('roles','roles.id','user_link_roles.role_id')
                    ->where(function($query) use($search) {
                        return $query->orWhere('name','like',"%$search%")
                                ->orWhere('email','like',"%$search%")
                                ->orWhere('roles.description','like', "%$search%");
                    })->select('users.*')
                    ->distinct()
                    ->get()
                    ->collect();

        $response = UserResource::collection($users)->paginate($perPage,count($users),$page);

        return $this->success($response);
    }

     /**
     * Show the details of a user
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser($user_id)
    {
        $user = User::where('id', $user_id)->first();
        if(!$user){
            return $this->error('User is not existing.', 'Invalid User Request', 400);
        }

        $response =  new UserResource($user);

        return $this->success($response);
    }

    /**
     * Show the details of a user
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteUser($user_id)
    {
        $user = User::where('id', $user_id)->first();
        if(!$user){
            return $this->error('User is not existing.', 'Invalid User Request', 400);
        }

        $user->delete();

        return $this->success(null."Successfully deleted!");
    }
}
