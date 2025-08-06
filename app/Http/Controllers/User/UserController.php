<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
    public function show(int $id)
    {
        //
        $user =  User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'کاربری با این مشخصات یافت نشد'
            ], 404);
        }
        return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
        $user =  User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'کاربری با این مشخصات یافت نشد'
            ], 404);
        }

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
            $user->verification_token = User::generateVerifiactionCode();
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
    public function destroy(int $id)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'کاربری با این مشخصات یافت نشد'
            ], 404);
        }
        $user->delete();
        return $this->showOne($user);
    }

    public function verify($token)
    {

        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('اکانت تایید شده است');
    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse('کاربر از قبل تایید شده است.', 409);
        }

        Mail::to($user->email)->queue(new UserCreated($user));

        return $this->showMessage('ارسال تاییدایمیل فرستاده شد.');
    }
}
