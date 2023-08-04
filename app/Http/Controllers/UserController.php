<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $validatedRequest = $request->validated();
        $validatedRequest['password'] = Hash::make($request->password);
        $user = User::create($validatedRequest);
        return response([
            'data' => new UserResource($user),
            'message' => 'Пользователь успешно зарегистрировался',
        ], 201);
    }

    public function showCurrent(Request $request)
    {
        return $request->user();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $validatedRequest = $request->validated();
        if ($validatedRequest['password']) {
            $validatedRequest['password'] = Hash::make($request->password);
        }
        $user = $user->update($validatedRequest);
        return response([
            'data' => $user
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response(null, 200);
    }

    public function login(LoginRequest $request)
    {
        $validatedRequest = $request->validated();

        if(!Auth::attempt($validatedRequest)){
            return response()->json([
                'status' => false,
                'message' => 'Пользователь с такими паролем и почтой не найден',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'Пользователь успешно вошел',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);

    }


}
