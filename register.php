<?php
require_once 'login_bd.php';

$ligacao = new mysqli($hn, $un, $pw, $db);
mysqli_set_charset($ligacao, "utf8");
if ($ligacao->connect_error) die("Erro Fatal: " . $ligacao->connect_error);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $ligacao->real_escape_string($_POST['username']);
    $password = $ligacao->real_escape_string($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $email = $ligacao->real_escape_string($_POST['email']);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $ligacao->query($query);

    if (!$result) {
        $message = "Erro ao verificar o nome de usuário: " . $ligacao->error;
    } else {
        if ($result->num_rows > 0) {
            $message = "Nome de usuário já existe! <p> Escolha outro nome.";
        } else {
            $query = "INSERT INTO users (username, password, role, email) VALUES ('$username', '$hashed_password', 'user', '$email')";
            if ($ligacao->query($query)) {
                $message = "Registrado com sucesso! <p> Vai ser redirecionado em segundos!";
				echo 
				" <script>
				var seconds = 0;
				
				function displayseconds() {
					seconds +=1;
				}
				setInterval(displayseconds, 1000);
				
				function redirectpage() {
					window.location='login.php';
				}
				setTimeout('redirectpage()', 3000);
				</script>";
            } else {
                $message = "Registro falhado! " . $ligacao->error;
            }
        }
        $result->close();
    }
    $ligacao->close();
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

    <title>Registro</title>
</head>

<script>
	function convertMenor(input) {
		input.value = input.value.toLowerCase();
	}
</script>

<body class="sml_bg" id="secondsdisplay">
    <div class="register-container">
        <h2>Registrar</h2>
        <form method="post" autocomplete="off">
            <label for="username">Nome de Utilizador:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" onkeyup="convertMenor(this);" required><br>
            <label for="password">Palavra Pass:</label>
            <input type="password" id="password" name="password" required><p>
            <input type="submit" value="Registrar" >
        </form>
        <?php
        if (!empty($message)) {
            echo "<br> <div class='message' id = 'message'>$message</div>";
        }
        ?>
        <h6>
            Já tem a conta criada?
            <a href="login.php">
                Entre aqui!
            </a>
            <p>
        </h6>
    </div>
</body>
</html>