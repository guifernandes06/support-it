<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'login_bd.php';
$ligacao = new mysqli($hn, $un, $pw, $db);

mysqli_set_charset($ligacao, "utf8");
if ($ligacao->connect_error) die("Erro Fatal: " . $ligacao->connect_error);

function get_post($ligacao, $var) {
    return $ligacao->real_escape_string($_POST[$var]);
}

$username = $_SESSION['username'];
$query_user = "SELECT username, email FROM users WHERE username = '$username' LIMIT 1";
$result_user = $ligacao->query($query_user);

if (!$result_user) die("Falha na consulta: " . $ligacao->error);

$user_data = $result_user->fetch_assoc();
$username = $user_data['username'];
$email = $user_data['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nome']) &&
        isset($_POST['email']) &&
        isset($_POST['pergunta_local']) &&
        isset($_POST['problema']) &&
        isset($_POST['importante'])) {

        $nome = get_post($ligacao, 'nome');
        $email = get_post($ligacao, 'email');
        $pergunta_local = get_post($ligacao, 'pergunta_local');
        $problema = get_post($ligacao, 'problema');
        $importante = get_post($ligacao, 'importante');

        $query = "INSERT INTO form (nome, email, pergunta_local, problema, importante) VALUES ('$nome', '$email', '$pergunta_local', '$problema', '$importante')";
        $result = $ligacao->query($query);

        if ($result) {
            $last_id = $ligacao->insert_id;
            $pdd_number = rand(1, 1000);

            $query_insert = "UPDATE form SET pdd_number = $pdd_number WHERE id = '$last_id'";
            $result_insert = $ligacao->query($query_insert);

            if ($result_insert) {
                require 'vendor/autoload.php';
                $mail = new PHPMailer(true);

                try {
                    // Configurações do servidor
                    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Habilita saída de depuração detalhada
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();                       // Envia usando SMTP
                    $mail->Host       = 'smtp-mail.outlook.com';  // Define o servidor SMTP para enviar através de
                    $mail->SMTPAuth   = true;              // Habilita a autenticação SMTP
                    $mail->Username   = 'email'; // Nome de usuário SMTP
                    $mail->Password   = '*****'; // Senha de aplicativo SMTP
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita criptografia TLS com STARTTLS
                    $mail->Port       = 587;               // Porta TCP para conectar-se

                    // Destinatários
                    $mail->setFrom('email', 'Support IT');
                    $mail->addAddress('email'); // Adiciona o destinatário fixo
                    $mail->addReplyTo('email', 'Support IT');

                    // Conteúdo
                    $mail->isHTML(true);                   // Define o formato do email como HTML
                    $mail->Subject = 'PEDIDO DE AJUDA';
                    $mail->Body    = "O utilizador <b>$username</b> pediu assistência! <p> O problema deste utilizador é '$problema' e é <b> $importante </b>.";
                    $mail->AltBody = "O utilizador $username pediu ajuda!";

                    $mail->send();
                } catch (Exception $e) {
                    echo "";
                }
            }
        } 
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

    <link rel="shortcut icon" href="imagens/mini_SML.png" type="imagens/svg"/>
    <link rel="stylesheet" type="text/css" href="styles.css">

    <title>Problema</title>
	
	<style>
        input[type="submit"]:hover {
        background-color: #555;
    }
    </style>
</head>
<body>
    <div class="row">
        <div class="header">
            <div class="header_logo">
                <a href="index.php">
                    <img src="imagens/SML_logo.png">
                </a>
            </div>
            <p>
            <div class="menu">
                <ul>
                    <li>
                        <a href="index.php">
                            Fazer Pedido
                        </a>
                    </li>
                    <li>
                        <a href="fazer.php">
                            Lista de Pedidos
                        </a>
                    </li>
                    <li>
                        <a href="armazem.php">
                            Histórico
                        </a>
                    </li>
                    <li>
                        <a href="pessoal.php">
                            Pedidos
                        </a>
                    </li>
                    <li>
                        <a href="inventario.php">
                            Inventário IT
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            Logout
                        </a> 
                    </li>
                </ul>
            </div>
        </div>
		
        <div class="formulario">
            <form action="index.php" method="post" autocomplete="off">
                <center><h1>Fazer Pedido</h1></center>
                <h3>Nome de Utilizador:</h3>
                <input type="text" name="nome" value="<?php echo $username; ?>" readonly required>

                <h3>Email:</h3>
                <input type="text" name="email" value="<?php echo $email; ?>" readonly required>

                <h3>Qual é o seu posto?</h3>
                <select name="pergunta_local" required>
                    <option value="" disabled selected></option>
                    <optgroup label="Escritório">
                        <option>Customer Service</option>
                        <option>Finances</option>
                        <option>IT</option>
                        <option>Outsource</option>
                        <option>Production Admin</option>
                        <option>Purchase</option>
                        <option>Sala Manutenção</option>
                    </optgroup>
                    <option disabled> </option>
                    <optgroup label="Produção">
                        <option>Aplicadora de Inlays </option>
                        <option>Corte e Vinco (UltraSonic)</option>
                        <option>Digital</option>
                        <option>Gráfica</option>
                        <option>Logística</option>
                        <option>Ofsets</option>
                        <option>Pre-Press</option>
                        <option>RFID</option>
						<option>Rotativas</option>
                        <option>Termo Transferência</option>
                    </optgroup>
                </select>

                <h3>Problema:</h3>
                <textarea name="problema" rows="5" maxlength="250" required></textarea>

                <h3>Importante:</h3><b>
                <input type="radio" name="importante" value="Muito Importante" required> Muito Importante
                <input type="radio" name="importante" value="Importante" required> Importante
                <input type="radio" name="importante" value="Pouco Importante" required> Pouco Importante
                </b>
                <p>
                            
                <p>
                <center>
                <input type="submit" value="Submeter">
                </center>
            </form>
        </div>
    </div>
</body>
</html>
