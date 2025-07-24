<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form id="login-form" method="POST" action="{{ route('login') }}">
        @csrf

        <h2 class="login-title">Log in</h2>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <div class="form-group">
            <div class="input-group-icon right">
                <div class="input-icon"><i class="fa fa-user"></i></div>
                <input class="form-control" type="text" name="login" placeholder="Name or Email" value="{{ old('login') }}" required autofocus>
            </div>
            @error('login')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <div class="input-group-icon right">
                <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                <input class="form-control" type="password" name="password" placeholder="Password" required>
            </div>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group d-flex justify-content-between">
            <label class="ui-checkbox ui-checkbox-info">
                <input type="checkbox" name="remember">
                <span class="input-span"></span>Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-info btn-block" type="submit">Login</button>
        </div>
        {{-- <div class="text-center">Not a member?
            <a class="color-blue" href="{{ route('register') }}">Create account</a>
        </div> --}}
    </form>
</x-guest-layout>
