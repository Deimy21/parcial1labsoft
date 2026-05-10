<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistema de Viveros</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial, Helvetica, sans-serif;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            overflow:hidden;
            position:relative;
        }

        /* Fondo */
        .background{
            position:fixed;
            inset:0;
            background-image:url('https://images.unsplash.com/photo-1416879595882-3373a0480b5b?q=80&w=1920&auto=format&fit=crop');
            background-size:cover;
            background-position:center;
            z-index:-2;
        }

        /* Overlay oscuro */
        .overlay{
            position:fixed;
            inset:0;
            background:rgba(0,0,0,.45);
            z-index:-1;
        }

        /* Card */
        .login-card{
            width:100%;
            max-width:420px;
            background:rgba(255,255,255,.95);
            backdrop-filter:blur(10px);
            border-radius:24px;
            padding:38px;
            box-shadow:0 20px 60px rgba(0,0,0,.25);
        }

        .logo{
            width:78px;
            height:78px;
            border-radius:22px;
            background:linear-gradient(135deg,#1b5e20,#43a047);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:2.2rem;
            margin:0 auto 22px;
            color:white;
        }

        h1{
            text-align:center;
            font-size:2rem;
            color:#1b4332;
            margin-bottom:8px;
        }

        .subtitle{
            text-align:center;
            color:#6b7280;
            font-size:.92rem;
            margin-bottom:30px;
        }

        .input-group{
            margin-bottom:20px;
        }

        .input-group label{
            display:block;
            margin-bottom:8px;
            font-size:.84rem;
            font-weight:600;
            color:#374151;
        }

        .input-group input{
            width:100%;
            padding:14px 16px;
            border:1px solid #d1d5db;
            border-radius:14px;
            outline:none;
            font-size:.92rem;
            transition:.2s;
        }

        .input-group input:focus{
            border-color:#43a047;
            box-shadow:0 0 0 4px rgba(67,160,71,.15);
        }

        .login-btn{
            width:100%;
            padding:14px;
            border:none;
            border-radius:14px;
            background:linear-gradient(135deg,#1b5e20,#43a047);
            color:white;
            font-size:.95rem;
            font-weight:600;
            cursor:pointer;
            transition:.2s;
            margin-top:10px;
        }

        .login-btn:hover{
            transform:translateY(-2px);
            box-shadow:0 10px 25px rgba(0,0,0,.2);
        }

        .footer{
            margin-top:24px;
            text-align:center;
            font-size:.78rem;
            color:#6b7280;
        }

        .error-box{
            background:#fee2e2;
            border:1px solid #fecaca;
            color:#b91c1c;
            padding:12px 14px;
            border-radius:12px;
            margin-bottom:20px;
            font-size:.84rem;
        }

        .logo img{
            background:linear-gradient(135deg, #0d47a1, #42a5f5);
            border-radius:22px;
            width: 100px;
            height: 100px;
            object-fit: contain;
}
    </style>
</head>
<body>

    {{-- Fondo --}}
    <div class="background"></div>
    <div class="overlay"></div>

    {{-- Login --}}
    <div class="login-card">

        <div class="logo">
            <img src="{{ asset('favicon.png') }}" alt="Logo">
        </div>

        <h1>Sistema de Viveros</h1>

        <p class="subtitle">
            Inicia sesión para continuar
        </p>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="input-group">
                <label>Correo electrónico</label>

                <input 
                    type="email"
                    name="email"
                    required
                    placeholder="correo@ejemplo.com"
                    value="{{ old('email') }}"
                >
            </div>

            <div class="input-group">
                <label>Contraseña</label>

                <input 
                    type="password"
                    name="password"
                    required
                    placeholder="••••••••"
                >
            </div>

            <button type="submit" class="login-btn">
                Ingresar
            </button>
        </form>

        <div class="footer">
            © {{ date('Y') }} Sistema de Gestión de Viveros
        </div>

    </div>

</body>
</html>