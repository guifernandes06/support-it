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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['nome']) &&
        isset($_POST['quantidade']) &&
        isset($_POST['utilidade']) &&
        isset($_POST['detalhes'])) {

        $nome = get_post($ligacao, 'nome');
        $quantidade = get_post($ligacao, 'quantidade');
        $utilidade = get_post($ligacao, 'utilidade');
        $detalhes = get_post($ligacao, 'detalhes');

        $query = "INSERT INTO item (nome, quantidade, utilidade, detalhes) VALUES ('$nome', '$quantidade', '$utilidade', '$detalhes')";
        $result = $ligacao->query($query);

        if ($result) {
                
            // Seleciona o username da tabela users
            $query_mail = "SELECT nome FROM item WHERE nome = '$nome' LIMIT 1";
            $result_mail = $ligacao->query($query_mail);

            if (!$result_mail) die("Falha na consulta: " . $ligacao->error);
            
            if ($result_mail->num_rows > 0) {
    
                require 'vendor/autoload.php';
                $mail = new PHPMailer(true);
    
                try {
                    // Configurações do servidor
                    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Habilita saída de depuração detalhada
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();                       // Envia usando SMTP
                    $mail->Host       = 'smtp-mail.outlook.com';  // Define o servidor SMTP para enviar através de
                    $mail->SMTPAuth   = true;              // Habilita a autenticação SMTP
                    $mail->Username   = 'sml.ticket@outlook.pt'; // Nome de usuário SMTP
                    $mail->Password   = 'SMLticketIT#2024'; // Senha de aplicativo SMTP
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita criptografia TLS com STARTTLS
                    $mail->Port       = 587;               // Porta TCP para conectar-se
    
                    // Destinatários
                    $mail->setFrom('sml.ticket@outlook.pt', 'Support IT');
                    $mail->addAddress('sml.ticket@outlook.pt'); // Adiciona o destinatário fixo
                    $mail->addReplyTo('sml.ticket@outlook.pt', 'Support IT');
    
                    // Conteúdo
                    $mail->isHTML(true);                   // Define o formato do email como HTML
                    $mail->Subject = 'NOVO ITEM';
                    $mail->Body    = "O item <b> $nome </b> foi adicionado ao stock!";
                    $mail->AltBody = "O item <b> $nome </b> foi adicionado ao stock!";
    
                    $mail->send();
                } catch (Exception $e) {
                    echo "O email não foi enviado.";
                }
            } else {
                echo "Username do usuário não encontrado.";
            }
                
            // Libera recursos
            $result_mail->free();
            $ligacao->close();
        } else {
            echo "Falha ao inserir dados: " . $ligacao->error;
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
    <link rel="stylesheet" type="text/css" href="style_item.css">

    <title>Novo Item</title>
	
	<style>
	.capName {
		text-transform: capitalize;
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
                        <a href="fazer.php">
                            Lista de Pedidos
                        </a>
                    </li>
                    <li>
                        <a href="item.php">
                            Novo Item
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
            <form action="item.php" method="post" autocomplete="off">
                <center><h1>Novo Item</h1></center>
                <h3>Nome Item:</h3>
                <input type="text" name="nome" class="capName" required>

                <h3>Quantidade:</h3>
                <input type="number" name="quantidade" required>

                <h3>Tipo de Item</h3>
                <select name="utilidade" required>
                    <option disabled selected></option>
                    <optgroup label="Cabos">
                        <option> Cabos de Rede </option>
                        <option> Cabos Display Port </option>
						<option> Cabos Diversos </option>
						<option> Cabos DOC </option>
                        <option> Cabos HDMI </option>
                        <option> Cabos Power </option>
						<option> Cabos Rede Telefone </option>
						<option> Cabos SATA </option>
						<option> Cabos VGA </option>
						<option> Carregador Diversos </option>
                        <option> Carregador Portátil </option>
                        <option disabled> </option>
                    </optgroup> 
                    <optgroup label="Componentes PC">
                        <option> Computador </option>
                        <option> Disco MVME </option>
                        <option> Disco Rígido </option>
                        <option> Fonte </option>
                        <option> Memória </option>
                        <option> Monitor </option>
                        <option> MotherBoard </option>
                        <option> Pasta Térmica </option>
                        <option> Placa de Rede </option>
                        <option> Placa Gráfica </option>
                        <option> Processador (CPU) </option>
                        <option> RAM </option>
                        <option> SSD</option>
                        <option> UPS </option>
                        <option> Ventoinha </option>
                        <option disabled> </option>
                    </optgroup>
                    <optgroup label="Impressora">
                        <option> Impressora </option>
                        <option> Impressora Bar Code </option>
                        <option> Tambor </option>
                        <option> Toner </option>
						<option> Rolo p/ Impressora Bar Code </option>
                        <option disabled> </option>
                    </optgroup>
                    <optgroup label="Telemóvel">
						<option> Base Telefone Fixo </option>
						<option> Combo Base e Telefone Fixo </option>
                        <option> Telefone Fixo </option>
                        <option> SmartPhone </option>
                        <option disabled> </option>
                    </optgroup>
                    <optgroup label="Outros">
						<option> Adaptadores </option>
                        <option> Auscultadores </option>
                        <option> CD's </option> 
                        <option> Combo Tecl. e Rato </option>
						<option> Diversos </option>
						<option> Mousepad </option>
                        <option> Pastas Partilhadas </option> 
                        <option> Pen USB </option>
                        <option> Pistola Bar Code </option> 
						<option> Pistola RFID </option>
						<option> Pistola RFID Combo </option>
                        <option> Portátil </option>
                        <option> Rato c/ fio </option>
                        <option> Rato s/ fio </option>
                        <option> Rooters </option>
                        <option> Servidor </option> 
                        <option> Switchs </option> 
                        <option> Switchs Servidor </option> 
                        <option> Teclado c/ fio </option>
						<option> Teclado s/fio </option>
                        <option> Triplas </option> 
                    </optgroup>
                </select>

                <h3>Detalhes:</h3>
                <textarea name="detalhes" rows="5" maxlength="250" required></textarea>

                <p>
                <center>
                <input type="submit" value="Submeter">
                </center>
            </form>
        </div>
    </div>
</body>
</html>
