<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\ChangePasswordRequest;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Http\Requests\Api\User\UploadAvatarRequest;
use App\Http\Resources\UserResource;
use App\Models\Interest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{


    /**
     * Get the authenticated user's profile.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load(['area', 'roles', 'interests']);

        return $this->successResponse(
            new UserResource($user),
            'Profile retrieved successfully'
        );
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle interests separately
        if (isset($validated['interests'])) {
            $interests = Interest::whereIn('id', $validated['interests'])
                ->active()
                ->get();

            if ($interests->count() !== count($validated['interests'])) {
                return $this->errorResponse(
                    'One or more selected interests are invalid or inactive',
                    400
                );
            }

            $user->interests()->sync($validated['interests']);
            unset($validated['interests']);
        }

        // Update other profile fields
        if (!empty($validated)) {
            $user->update($validated);
        }

        // Reload user with relationships
        $user->load(['area', 'roles', 'interests']);

        return $this->successResponse(
            new UserResource($user),
            'Profile updated successfully'
        );
    }

    /**
     * Upload user avatar.
     */
    public function uploadAvatar(UploadAvatarRequest $request): JsonResponse
    {
        $user = $request->user();

        try {
            // Clear existing avatar
            $user->clearMediaCollection('avatar');

            // Add new avatar
            $media = $user->addMediaFromRequest('avatar')
                ->toMediaCollection('avatar');

            // Reload user to get updated avatar URLs
            $user->refresh();

            return $this->successResponse([
                'avatar' => [
                    'original' => $user->avatar_url,
                    'thumb' => $user->avatar_thumb_url,
                    'medium' => $user->avatar_medium_url,
                ],
            ], 'Avatar uploaded successfully');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to upload avatar: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Change user password.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        // Revoke all existing tokens for security
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Password changed successfully. Please use the new token for future requests.',
        ], 'Password changed successfully');
    }

    /**
     * Delete user avatar.
     */
    public function deleteAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            $user->clearMediaCollection('avatar');

            return $this->successResponse(
                null,
                'Avatar deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete avatar: ' . $e->getMessage(),
                500
            );
        }
    }
}
