<?php
// Simple login form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CineTick Log-in</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: conic-gradient(from 180deg at 50% 50%, #451C7B 0deg, #5E1471 144deg, #231A7E 270deg, #451C7B 360deg);
        }
        .login-container {
            background: #161616;
            padding: 4rem;
            border-radius: 36px;
            box-shadow: 4px 8px 24px 8px rgba(0, 0, 0, 0.25);
            width: 320px;
        }
        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 3rem;
            gap: 1rem;
        }
        .logo-container img {
            max-width: 60px;
        }
        h2 {
            margin: 0;
            color: #F0E5FF;
            font-size: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
            color: #F0E5FF;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            text-align: left;
            font-size: 0.8rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 0.5px solid #F0E5FF;
            border-radius: 12px;
            background-color: #F0E5FF;
            color: #161616;
        }
        .form-group input::placeholder {
            color: rgba(22, 22, 22, 0.5);
        }
        .login-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
            /* Centraliza os botões */
            align-items: center;
        }
        .login-buttons button {
            width: 80%; /* Diminui a largura dos botões para que a centralização seja visível */
            max-width: 250px; /* Limita a largura máxima para manter a estética */
            padding: 0.75rem;
            color: #F0E5FF;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            font-size: 1rem;
            text-align: center; /* Garante que o texto dentro do botão esteja centralizado */
        }
        .login-buttons .login-main-button {
            background-color: #451C7B;
        }
        .login-buttons .login-main-button:hover {
            background-color: #5E1471;
        }
        .login-buttons .google-button {
            background-color: transparent;
            border: 1px solid #F0E5FF;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .login-buttons .google-button:hover {
            background-color: rgba(240, 229, 255, 0.1);
        }
        .google-icon {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="header-container">
        <div class="logo-container">
            <img src="logo-cinetick.png" alt="Logo CineTick">
        </div>
        <h2>CineTick Log-in</h2>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; text-align: center;"><?php echo $_SESSION['error_message']; ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <form action="/login" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="seu@email.com" required>
        </div>
        <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" placeholder="Sua senha" required>
        </div>
        <div class="login-buttons">
            <button type="submit" class="login-main-button">Entrar</button>
            <button type="button" class="google-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20.66 10.2c.096 0 .179.067.195.161c.094.526.145 1.092.145 1.639a8.97 8.97 0 0 1-2.293 6.001a.197.197 0 0 1-.274.018l-2.445-2.07a.206.206 0 0 1-.016-.297a5.4 5.4 0 0 0 1.114-1.852H12.2a.2.2 0 0 1-.2-.2v-3.2c0-.11.09-.2.2-.2zm-6.187 6.6a.21.21 0 0 1 .226.024l2.568 2.173a.196.196 0 0 1-.01.309A8.96 8.96 0 0 1 12 21a9 9 0 0 1-7.548-4.097a.197.197 0 0 1 .046-.263l2.545-1.962a.207.207 0 0 1 .303.062a5.4 5.4 0 0 0 7.127 2.06M6.68 12.926a.2.2 0 0 1-.076.197L3.869 15.23a.196.196 0 0 1-.304-.084A9 9 0 0 1 3 12c0-1.152.217-2.254.612-3.267a.196.196 0 0 1 .299-.085l2.732 2.004c.065.047.095.13.078.208a5.4 5.4 0 0 0-.042 2.066m.468-3.765c.096.07.231.042.295-.058A5.4 5.4 0 0 1 12 6.6a5.37 5.37 0 0 1 3.44 1.245a.205.205 0 0 0 .276-.01l2.266-2.267a.197.197 0 0 0-.007-.286A8.95 8.95 0 0 0 12 3a8.99 8.99 0 0 0-7.484 4a.197.197 0 0 0 .049.267z"/></svg>
                Entrar com Google
            </button>
        </div>
    </form>
</div>
</body>
</html>