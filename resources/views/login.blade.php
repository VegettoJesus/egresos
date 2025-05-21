<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - EGRESOS</title>
  <link rel="icon" href="{{ asset('img/logoCorp.ico') }}" type="image/x-icon">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
  <div class="container">
      <div class="info">
          <h2>Bienvenido</h2>
          <hr/>
          <div class="escudo">
              <img src="{{ asset('img/logoCorp.png') }}" alt="Logo">
          </div>
          <p class="txt-1">EGRESOS</p>
      </div>

      <form id="loginForm" method="POST" action="{{ url('/login') }}" class="form">
        @csrf
        <h2>Login</h2>
        <div class="inputs">
            <input type="text" class="box" name="Login" id="user" placeholder="Ingrese tu usuario" required>
            <i class="bx2 fa fa-user fa-2x"></i>
        </div>
        <div class="inputs">
            <input type="password" class="box pass" name="password" id="pass" placeholder="Ingrese tu contraseña" required>
            <i class="bx2 fa fa-lock fa-2x"></i>
            <i class="fa fa-eye-slash" id="togglePassword"></i>
        </div>
        <div class="inputs">
            <button type="submit" id="btnIngresar" class="submit">Iniciar Sesión</button>
        </div>
    </form>
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ $errors->first() }}'
            });
        </script>
    @endif
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
