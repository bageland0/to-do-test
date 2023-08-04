<?php
namespace App\Actions;

class ValidateUserRequest
{
    public function __invoke($request)
    {
        $validatedRequest = $request->validated();
        $validatedRequest['user_id'] = $request->user()->id;

        return $validatedRequest;
    }
}
