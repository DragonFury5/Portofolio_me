<?php

namespace App\Http\Controllers;

use App\Services\ImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('settings.index', [
            'user' => $user,
            'pageCount' => $user->portfolios()->count(),
            'publishedCount' => $user->portfolios()->where('is_published', true)->count(),
            'totalViews' => (int) $user->portfolios()->sum('views'),
        ]);
    }

    public function update(Request $request, ImageUploader $uploader): RedirectResponse
    {
        $user = $request->user();
        $request->merge([
            'username' => strtolower(trim((string) $request->input('username'))),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'username' => [
                'required',
                'string',
                'regex:/^[a-z0-9](?:[a-z0-9_]{1,22})[a-z0-9]$/',
                Rule::notIn(reserved_slugs()),
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'bio' => ['nullable', 'string', 'max:700'],
            'location' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'string', 'max:190'],
            'avatar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'username.regex' => 'Use 3–24 lowercase letters, numbers or underscores.',
            'username.not_in' => 'That username is reserved.',
            'username.unique' => 'That username is already taken.',
        ]);

        $website = trim((string) ($data['website'] ?? ''));

        if ($website !== '') {
            if (! preg_match('#^https?://#i', $website)) {
                $website = 'https://'.$website;
            }

            if (! filter_var($website, FILTER_VALIDATE_URL) || ! preg_match('#^https?://#i', $website)) {
                throw ValidationException::withMessages(['website' => 'Use a full web address, such as https://your.site.']);
            }
        }

        $updates = [
            'name' => $data['name'],
            'username' => $data['username'],
            'bio' => $data['bio'] ?? null,
            'location' => $data['location'] ?? null,
            'website' => $website !== '' ? $website : null,
        ];

        if ($request->hasFile('avatar')) {
            try {
                $upload = $uploader->store($request->file('avatar'), $user->id, 512);
                $this->deleteUploadedAvatar($user->getOriginal('avatar'));
                $updates['avatar'] = $upload['path'];
            } catch (RuntimeException $e) {
                throw ValidationException::withMessages(['avatar' => $e->getMessage()]);
            }
        } elseif ($request->boolean('remove_avatar')) {
            $this->deleteUploadedAvatar($user->getOriginal('avatar'));
            $updates['avatar'] = null;
        }

        $user->update($updates);

        return redirect()->route('settings.edit')->with('status', 'Profile saved.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
        ], [
            'current_password.current_password' => 'Your current password is not right.',
        ]);

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        $request->session()->regenerate();

        return redirect()->route('settings.edit')->with('status', 'Password changed.');
    }

    private function deleteUploadedAvatar(?string $path): void
    {
        if (! is_string($path) || ! str_starts_with($path, 'uploads/')) {
            return;
        }

        Storage::disk('uploads')->delete(ltrim(substr($path, strlen('uploads/')), '/'));
    }
}
