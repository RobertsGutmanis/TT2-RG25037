@include('header')
<div class="auth-page">
    <div class="auth-card">
        <h1 class="auth-heading">Sign in to your account</h1>

        @if($errors->any())
        <ul class="auth-errors">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        <form method="POST" action="{{ route('auth.login.post') }}">
        @csrf
        <div class="field">
            <label for="email">E-mail</label>
            <input name="email" id="email" placeholder="you@example.com" value="{{ old('email') }}" >
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="••••••••" >
        </div>
        <button type="submit" class="auth-btn">Login</button>
        </form>

        <p class="auth-link">Don't have an account? <a href="{{ route('auth.register') }}">Register</a></p>
    </div>
</div>
@include('footer')