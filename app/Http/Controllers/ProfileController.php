<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user()
        ]);
    }

    function update(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'nullable|string',
            'username' => [
                'required',
                Rule::unique('users', 'username')->whereNot('id', auth()->id())
            ],
            'password' => 'nullable|min:6',
            'email' => [
                'required',
                Rule::unique('users', 'email')->whereNot('id', auth()->id())
            ]
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->fill($data);

        try {
            $user->save();
        } catch (\Exception $ex) {
            unset($data['password']);
            return redirect()->back()->withInput($data)->withErrors(['Failed to update profile']);
        }

        session()->flash('message:success', 'Profile updated');
        return redirect(route('profile.edit'));
    }
}
