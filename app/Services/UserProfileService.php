<?php

namespace App\Services;

use App\Models\User;
use App\Models\Interest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UserProfileService
{
    /**
     * Update user profile data.
     */
    public function updateProfile(User $user, array $data): User
    {
        // Handle interests separately
        if (isset($data['interests'])) {
            $this->updateUserInterests($user, $data['interests']);
            unset($data['interests']);
        }

        // Update other profile fields
        if (!empty($data)) {
            $user->update($data);
        }

        return $user->fresh(['area', 'roles', 'interests']);
    }

    /**
     * Update user interests.
     */
    public function updateUserInterests(User $user, array $interestIds): void
    {
        $interests = Interest::whereIn('id', $interestIds)->active()->get();

        if ($interests->count() !== count($interestIds)) {
            throw new \InvalidArgumentException('One or more selected interests are invalid or inactive');
        }

        $user->interests()->sync($interestIds);
    }

    /**
     * Upload user avatar.
     */
    public function uploadAvatar(User $user, UploadedFile $file): array
    {
        // Clear existing avatar
        $user->clearMediaCollection('avatar');

        // Add new avatar
        $media = $user->addMedia($file)->toMediaCollection('avatar');

        // Refresh user to get updated avatar URLs
        $user->refresh();

        return [
            'original' => $user->avatar_url,
            'thumb' => $user->avatar_thumb_url,
            'medium' => $user->avatar_medium_url,
            'media_id' => $media->id,
        ];
    }

    /**
     * Change user password.
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): string
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \InvalidArgumentException('The current password is incorrect.');
        }

        // Update password
        $user->update(['password' => Hash::make($newPassword)]);

        // Revoke all existing tokens for security
        $user->tokens()->delete();

        // Create new token
        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Delete user avatar.
     */
    public function deleteAvatar(User $user): void
    {
        $user->clearMediaCollection('avatar');
    }
}
