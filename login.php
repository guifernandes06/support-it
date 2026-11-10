<?php
require_once 'login_bd.php';
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
        
    $ligacao = new mysqli($hn, $un, $pw, $db);
    if ($ligacao->connect_error) die("Connection failed: " . $ligacao->connect_error);
    
    $username = $ligacao->real_escape_string($username);
    
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $ligacao->query($query);
    
    if ($result) {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                header("Location: index.php");
                exit();
            } else {
                $message = "Credenciais erradas!";
            }
        } else {
            $message = "Credenciais erradas!";
        }
    } else {
        $message = "Erro na consulta à base de dados!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Desenvolvimento Web">
    <meta name="keywords" content="HTML, CSS, PHP">
    <meta name="author" content="Guilherme Fernandes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="imagens/mini_SML.png" type="image/svg+xml"/>
    <link rel="stylesheet" type="text/css" href="style_conta.css">

    <title>Login</title>
</head>

<body class="sml_bg">
    <div class="login-container">
        <h2>Entrar</h2>
        <form method="post" autocomplete="off">
            <label for="username">Nome de Utilizador:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Palavra Pass:</label>
            <input type="password" id="password" name="password" required><p>
            <input type="submit" value="Entrar">
        </form>
        <?php
        if (!empty($message)) {
            echo "<br> <div class='message' id = 'message'>$message</div>";
        }
        ?>
        <h6>
            Ainda não criou conta?
            <a href="register.php">
                Crie aqui!
            </a>
        </h6>
    </div>        
</body>
</html>

