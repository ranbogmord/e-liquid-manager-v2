
@include("partials.form.input", [
    'label' => 'Username',
    'value' => old('username', $user->username)
])

@include("partials.form.input", [
    'label' => 'Email',
    'type' => 'email',
    'value' => old('email', $user->email)
])

@include("partials.form.input", [
    'label' => 'Password',
    'type' => 'password'
])

@include("partials.form.select", [
    'label' => 'Role',
    'options' => [
        ['label' => 'User', 'value' => 'user'],
        ['label' => 'Admin', 'value' => 'admin']
    ],
    'value' => old('role', $user->role)
])
