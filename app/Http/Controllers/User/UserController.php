<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::all();
        return $this->showAll($users);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => $validator->errors()
            ]);
        }

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verifiaction_token'] = User::generateVerifiactionCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::Create($data);

        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::findOrFail($id);
        return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = User::findOrFail($id);;

        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'required|min:6',
            'admin' => 'in:' . User::ADMIN_USER . ';' . User::REGULAR_USER
        ];

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::VERIFIED_USER;
            $user->verfication_token = User::generateVerifiactionCode();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorResponse(
                    'Only verifird users can modify the admin fieled',
                    409
                );
            }
            $user->admin = $request->admin;
        }

        if (!$user->isDirty()) {
            return $this->errorResponse(
                'You need to specify a differnet value to update',
                422
            );
        }

        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $user = User::findOrFail($id);

        $user->delete();
        return $this->showOne($user);
    }
}
