@include('header')

<div class="auth-page">
  <div class="auth-card">
    <h1 class="auth-heading">Create your account</h1>
    <div class="auth-accent"></div>

    @if($errors->any())
      <ul class="auth-errors">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif

    <form method="POST" action="{{ route('auth.register.post') }}">
      @csrf
      <div class="field-row">
        <div class="field">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" placeholder="Jānis" value="{{ old('name') }}">
        </div>
        <div class="field">
          <label for="last_name">Last name</label>
          <input type="text" name="last_name" id="last_name" placeholder="Bērziņš" value="{{ old('last_name') }}">
        </div>
      </div>
      <div class="field">
        <label for="email">E-mail</label>
        <input  name="email" id="email" placeholder="you@example.com" value="{{ old('email') }}" >
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="••••••••" >
      </div>
      <div class="field">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" >
      </div>
      <button type="submit" class="auth-btn">Register →</button>
    </form>

    <p class="auth-link">Already registered? <a href="{{ route('login') }}">Login</a></p>
  </div>
</div>

@include('footer')