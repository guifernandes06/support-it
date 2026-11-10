<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Desenvolvimento Web">
    <meta name="keywords" content="HTML, CSS, PHP">
    <meta name="author" content="Guilherme Fernandes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="imagens/mini_SML.png" type="image/svg+xml"/>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta http-equiv="refresh" content="20">

    <title>Tarefas</title>

    <style>
    .search-button {
        background-color: #222222;
        color: white;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 1em;
        transition: background-color 0.3s;
        /* Ajuste o tamanho do botão para coincidir com a imagem */
        width: auto;
        height: auto;
        line-height: 0; /* Evita espaço extra ao redor da imagem */
    }

    .search-button img {
        vertical-align: middle; /* Alinha verticalmente a imagem no botão */
        max-width: 100%; /* Garante que a imagem não exceda o tamanho do botão */
    }

    input[type="submit"]:hover {
        background-color: #555;
    }

    textarea {
        width: calc(100% - 22px);
        height: 50%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 1em;
    }

    textarea {
        resize: vertical;
        font-size: 1em;
    }
    </style>

</head>
<body>
    <div class = "row">
        <div class="header">
            <div class="header_logo">
                <a href="index.php">
                    <img src="imagens/SML_logo.png" alt="Logo"> 
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
                    <li id="search_li">
                        <form method="post" autocomplete="off">
                            <input type="text" name="search" id="search" placeholder="Pesquisar nome">
                            <button type="submit" name="submit" id="submit" class="search-button">
                                <img src="imagens/search.png">
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container">
        <?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();

$username = $_SESSION['username'];
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';


require_once 'login_bd.php';
$ligacao = new mysqli($hn, $un, $pw, $db);
mysqli_set_charset($ligacao,"utf8");

if ($ligacao->connect_error) {
    die("Connection failed: " . $ligacao->connect_error);
}

if (isset($_POST["submit"])) {
    $str = $_POST["search"];
	
	$username = $_SESSION['username'];

    // As consultas SQL para pesquisar em três tabelas
    $queries = [
        "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, NULL AS andamento_timestamp, NULL AS feito_timestamp FROM form WHERE nome LIKE ?",
        "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, andamento_timestamp, NULL AS feito_timestamp FROM andamento WHERE nome LIKE ?",
        "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, andamento_timestamp, feito_timestamp, resolve FROM feito WHERE nome LIKE ?"
    ];

    $all_results = [];
    foreach ($queries as $query) {
		
        $sth = $ligacao->prepare($query);
		$param = "%" . $str . "%"; 
		$sth->bind_param("s", $param); // Adicione o parâmetro de usuário
		
		$sth->execute();
		$result = $sth->get_result();

		while ($row = $result->fetch_assoc()) {
			$all_results[] = $row;
		}

		$sth->close();
    }

    if (!empty($all_results)) {
        foreach ($all_results as $row) {
            $nome = htmlspecialchars($row['nome'] ?? '');
			$local = htmlspecialchars($row['local'] ?? '');
			$problema = htmlspecialchars($row['problema'] ?? '');
			$importante = htmlspecialchars($row['importante'] ?? '');
            $id = htmlspecialchars($row['id'] ?? '');
			$hora = htmlspecialchars($row['hora'] ?? '');
			$pdd = htmlspecialchars($row['pdd'] ?? '');
			$andamento_timestamp = htmlspecialchars($row['andamento_timestamp'] ?? '');
			$feito_timestamp = htmlspecialchars($row['feito_timestamp'] ?? '');
            $resolve = htmlspecialchars($row['resolve'] ?? '');

            echo <<< _END
                <div class="column_search">
                    <table class="table_search">
                        <tr> <td> <b> Número do Pedido: </b> </td> <td> $pdd </td> </tr>
                        <tr> <td> <b> Nome: </b> </td> <td> $nome </td> </tr>
                        <tr> <td> <b> Local: </b> </td> <td> $local </td> </tr>
                        <tr> <td> <b> Problema: </b> </td> <td> $problema </td> </tr>
                        <tr> <td> <b> Importância: </b> </td> <td> $importante </td> </tr>
                        <tr> <td> <b> Pedido feito em: </b> </td> <td> $hora </td> </tr>
_END;
            if ($andamento_timestamp) {
                echo <<< _END
                        <tr><td><b>Pedido começado em:</b></td><td>$andamento_timestamp</td></tr>
                    </table>
_END;
                if($isAdmin) {
                    echo <<< _END
                    <form action='fazer.php' method='post'>
                        <textarea name="resolve" rows="5" maxlength="250" placeholder="Como resolveu este problema?" required></textarea><p> 
                    </form>
                    _END;
                }
            }
            if ($feito_timestamp) {
                echo <<< _END
                        <tr><td><b>Pedido terminado em:</b></td><td>$feito_timestamp</td></tr>
                        <tr><td><b>Como foi resolvido:</b></td><td>$resolve</td></tr>
_END;
            }
            
            echo "</table>";

            if ($isAdmin) {
                echo <<<_END
                    <form action='fazer.php' method='post'>
                        <input type='hidden' name='mudar_andamento' value='yes'>
                        <input type='hidden' name='id' value='$id'>
                        <input type='submit' value='Mudar para Feito'>
                    </form>
_END;
            }
            echo "</div>";
        }
    }
}

$ligacao->close();
?>
        </div>

        <div class="container">
            <div class="column">
                <h2>Pedidos por Fazer</h2>

                <?php

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';

require_once 'login_bd.php';
$ligacao = new mysqli($hn, $un, $pw, $db);

if ($ligacao->connect_error) die("Erro Fatal");

function get_post($ligacao, $var) {
    return $ligacao->real_escape_string($_POST[$var]);
}

if (isset($_POST['mudar_fazer']) && isset($_POST['id']) && $isAdmin) {

    $id = get_post($ligacao, 'id'); // Call the function directly

    $query = "SELECT * FROM form WHERE id = '$id'";
    $result = $ligacao->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Fetch the result row

        $email_destinatario = $row['email'];  // Supondo que a coluna chama-se 'email_criador'
        $numero = $row['pdd_number'];
        $problem = $row['problema'];

        $query_insert = "INSERT INTO andamento (nome, email, pergunta_local, problema, importante, hora_timestamp, pdd_number) VALUES ('{$row['nome']}', '{$row['email']}', '{$row['pergunta_local']}', '{$row['problema']}', '{$row['importante']}', '{$row['hora_timestamp']}', '{$row['pdd_number']}')";
        $result_insert = $ligacao->query($query_insert);

        if ($result_insert) {
            $query_delete = "DELETE FROM form WHERE id = '$id'";
            $result_delete = $ligacao->query($query_delete);

            if ($result_delete) {
                require 'vendor/autoload.php';
                $mail = new PHPMailer(true);

                try {
                    // Configurações do servidor
                    $mail->SMTPDebug = SMTP::DEBUG_OFF;
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host = 'smtp-mail.outlook.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'sml.ticket@outlook.pt';
                    $mail->Password = 'SMLticketIT#2024';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Destinatários
                    $mail->setFrom('sml.ticket@outlook.pt', 'Support IT');
                    $mail->addAddress($email_destinatario);
                    $mail->addReplyTo('sml.ticket@outlook.pt', 'Support IT');

                    // Conteúdo
                    $mail->isHTML(true);
                    $mail->Subject = 'PEDIDO EM ANDAMENTO';
                    $mail->Body = "O seu pedido <b> nº $numero </b> começou a ser tratado! <p> Este pedido tem como problema '$problem'.";
                    $mail->AltBody = "O seu pedido <b> nº $numero </b> começou a ser tratado! <p> Este pedido tem como problema '$problem'.";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("");
                }
            } 
        } 
    } 
}

$query = $isAdmin ? "SELECT * FROM form" : "SELECT * FROM form WHERE nome = '$username'";
$result = $ligacao->query($query);

if (!$result) die("Falha no acesso à base de dados");

$rows = $result->num_rows;

for ($j = 0; $j < $rows; ++$j) {
    $row = $result->fetch_array(MYSQLI_NUM);
    $r0 = htmlspecialchars($row[0]); // Nome
    $r1 = htmlspecialchars($row[1]); // Usuário associado ao pedido
    $r2 = htmlspecialchars($row[2]); // Local
    $r3 = htmlspecialchars($row[3]); // Problema
    $r4 = htmlspecialchars($row[4]); // Importância
    $r5 = htmlspecialchars($row[5]); // ID do pedido
    $r6 = htmlspecialchars($row[6]); // Pedido feito em
    $r7 = htmlspecialchars($row[7]); // Número do Pedido

    echo <<<HTML
    <table>
        <tr> <td> <b> Número do Pedido: </b> </td> <td> $r7 </td> </tr>
        <tr> <td> <b> Nome: </b> </td> <td> $r0 </td> </tr>
        <tr> <td> <b> Local: </b> </td> <td> $r2 </td> </tr>
        <tr> <td> <b> Problema: </b> </td> <td> $r3 </td> </tr>
        <tr> <td> <b> Importância: </b> </td> <td> $r4 </td> </tr>
        <tr> <td> <b> Pedido feito em: </b> </td> <td> $r6 </td> </tr>
    </table> 
    <p>
HTML;

    if ($isAdmin) {
        echo <<<HTML
        <form action='fazer.php' method='post'>
            <input type='hidden' name='mudar_fazer' value='yes'>
            <input type='hidden' name='id' value='$r5'>
            <input type='submit' value='Pedido em Andamento'>
        </form> 
        <p>
HTML;
    }
}
?>
            </div>

            <div class="column">
                <h2>
                    Pedidos em Andamento
                </h2>

                <?php

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // Fetch username from the session
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';

require_once 'login_bd.php';
$ligacao = new mysqli($hn, $un, $pw, $db);

if ($ligacao->connect_error) {
    die("Erro Fatal: " . $ligacao->connect_error);
}

if (isset($_POST['mudar_andamento']) && isset($_POST['id']) && isset($_POST['resolve'])) {
    $id = $ligacao->real_escape_string($_POST['id']);
    $resolve = $ligacao->real_escape_string($_POST['resolve']);

    $query = "SELECT * FROM andamento WHERE id = '$id'";
    $result = $ligacao->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Obter o email do criador do ticket
        $email_destinatario = $row['email'];
        $numero = $row['pdd_number'];
        $problem = $row['problema'];

        $query_insert = "INSERT INTO feito (nome, email, pergunta_local, problema, importante, hora_timestamp, andamento_timestamp, pdd_number, resolve) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $ligacao->prepare($query_insert);
        $stmt_insert->bind_param('sssssssss', $row['nome'], $row['email'], $row['pergunta_local'], $row['problema'], $row['importante'], $row['hora_timestamp'], $row['andamento_timestamp'], $row['pdd_number'], $resolve);
        $result_insert = $stmt_insert->execute();

        if ($result_insert) {
            $query_delete = "DELETE FROM andamento WHERE id = '$id'";
            $result_delete = $ligacao->query($query_delete);

            if ($result_delete) {
                require 'vendor/autoload.php';
                $mail = new PHPMailer(true);

                try {
                    // Configurações do servidor
                    $mail->SMTPDebug = SMTP::DEBUG_OFF;
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host = 'smtp-mail.outlook.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'sml.ticket@outlook.pt';
                    $mail->Password = 'SMLticketIT#2024';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Destinatários
                    $mail->setFrom('sml.ticket@outlook.pt', 'Support IT');
                    $mail->addAddress($email_destinatario);
                    $mail->addReplyTo('sml.ticket@outlook.pt', 'Support IT');

                    // Conteúdo
                    $mail->isHTML(true);
                    $mail->Subject = 'PEDIDO CONCLUIDO';
                    $mail->Body = "O seu pedido <b> nº $numero </b> foi concluído com sucesso! <p> Este pedido tinha o problema '$problem'. <p> Solucionou-se com: '$resolve'.";
                    $mail->AltBody = "O seu pedido nº $numero foi concluído com sucesso! Este pedido tinha o problema '$problem'.";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("Erro ao enviar o email: " . $mail->ErrorInfo);
                }
            }
        }
    }
}

$query = $isAdmin ? "SELECT * FROM andamento" : "SELECT * FROM andamento WHERE nome = '$username'";
$result = $ligacao->query($query);

if (!$result) {
    die("Falha no acesso à base de dados");
}

$rows = $result->num_rows;

for ($j = 0; $j < $rows; ++$j) {
    $row = $result->fetch_array(MYSQLI_NUM);
    $r0 = htmlspecialchars($row[0]);
    $r1 = htmlspecialchars($row[1]);
    $r2 = htmlspecialchars($row[2]);
    $r3 = htmlspecialchars($row[3]);
    $r4 = htmlspecialchars($row[4]);
    $r5 = htmlspecialchars($row[5]);
    $r6 = htmlspecialchars($row[6]);
    $r7 = htmlspecialchars($row[7]);
    $r8 = htmlspecialchars($row[8]);

    echo <<<_END
    <table>
        <tr><td><b>Número do Pedido:</b></td><td>$r8</td></tr>
        <tr><td><b>Nome:</b></td><td>$r0</td></tr>
        <tr><td><b>Local:</b></td><td>$r2</td></tr>
        <tr><td><b>Problema:</b></td><td>$r3</td></tr>
        <tr><td><b>Importância:</b></td><td>$r4</td></tr>
        <tr><td><b>Pedido feito em:</b></td><td>$r6</td></tr>
        <tr><td><b>Pedido começado em:</b></td><td>$r7</td></tr>
    </table> 
    <p>
_END;

    if ($isAdmin) {
        echo <<<_END
        <form action='fazer.php' method='post'>
            <textarea name="resolve" rows="5" maxlength="250" placeholder="Como resolveu este problema?" required></textarea><p> 
            <input type='hidden' name='mudar_andamento' value='yes'>
            <input type='hidden' name='id' value='$r5'>
            <input type='submit' value='Mudar para Feito'>
        </form>
        <p>
_END;
    }
}
?>

            </div>

            <div class="column">
                <h2>
                    Pedidos Feitos
                </h2>

                <?php
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    require_once 'login_bd.php';
    $ligacao = new mysqli($hn, $un, $pw, $db);
    mysqli_set_charset($ligacao, "utf8");
    if ($ligacao->connect_error) die("Erro Fatal");

    $isAdmin = $_SESSION['role'] == 'admin';

    if ($isAdmin && isset($_POST['mudar_feito']) && isset($_POST['id'])) {

        $id = get_post($ligacao, 'id');
        $query = "SELECT * FROM feito WHERE id = '$id'";
        $result = $ligacao->query($query);

        if ($result && $result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $query_insert = "INSERT INTO armazem (nome, email, pergunta_local, problema, importante, hora_timestamp, andamento_timestamp, feito_timestamp, pdd_number, resolve) VALUES ('{$row['nome']}', '{$row['email']}', '{$row['pergunta_local']}', '{$row['problema']}', '{$row['importante']}', '{$row['hora_timestamp']}', '{$row['andamento_timestamp']}', '{$row['feito_timestamp']}', '{$row['pdd_number']}', '{$row['resolve']}')";
            $result_insert = $ligacao->query($query_insert);

            if ($result_insert) {
                $query_delete = "DELETE FROM feito WHERE id = '$id'";
                $ligacao->query($query_delete);
                
            } else {
                echo "Falha ao mudar registo <br><br>";
            }
        }
    }

    if ($isAdmin && isset($_POST['erro_feito']) && isset($_POST['id'])) {

        $id = get_post($ligacao, 'id');
        $query = "SELECT * FROM feito WHERE id = '$id'";
        $result = $ligacao->query($query);

        if ($result && $result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $query_insert = "INSERT INTO andamento (nome, email, pergunta_local, problema, importante, hora_timestamp, andamento_timestamp, pdd_number) VALUES ('{$row['nome']}', '{$row['email']}', '{$row['pergunta_local']}', '{$row['problema']}', '{$row['importante']}', '{$row['hora_timestamp']}', '{$row['andamento_timestamp']}', '{$row['pdd_number']}')";
            $result_insert = $ligacao->query($query_insert);

            if ($result_insert) {

                $email_destinatario = $row['email'];
                $numero = $row['pdd_number'];
                $problem = $row['problema'];        

                require 'vendor/autoload.php';
                $mail = new PHPMailer(true);

                try {
                    // Configurações do servidor
                    $mail->SMTPDebug = SMTP::DEBUG_OFF;
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host = 'smtp-mail.outlook.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'sml.ticket@outlook.pt';
                    $mail->Password = 'SMLticketIT#2024';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Destinatários
                    $mail->setFrom('sml.ticket@outlook.pt', 'Support IT');
                    $mail->addAddress($email_destinatario);
                    $mail->addReplyTo('sml.ticket@outlook.pt', 'Support IT');

                    // Conteúdo
                    $mail->isHTML(true);
                    $mail->Subject = 'PROBLEMA COM O PEDIDO';
                    $mail->Body = "O seu pedido <b> nº $numero </b> teve um problema e voltou a ser resolvido! <p> Este pedido tem como problema '$problem'.";
                    $mail->AltBody = "O seu pedido <b> nº $numero </b> teve um problema e voltou a ser resolvido! <p> Este pedido tem como problema '$problem'.";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("Erro ao enviar o email: " . $mail->ErrorInfo);
                }
                $query_delete = "DELETE FROM feito WHERE id = '$id'";
                $ligacao->query($query_delete);

            } else {
                echo "Falha ao mudar registo <br><br>";
            }
        }
    }

    $query = $isAdmin ? "SELECT * FROM feito" : "SELECT * FROM feito WHERE nome = '$username'";
    $result = $ligacao->query($query);

    if (!$result) die("Falha no acesso à base de dados");
    $rows = $result->num_rows;

    for ($j = 0; $j < $rows; ++$j) {
        $row = $result->fetch_array(MYSQLI_NUM);
        $r0 = htmlspecialchars($row[0]);
        $r1 = htmlspecialchars($row[1]);
        $r2 = htmlspecialchars($row[2]);
        $r3 = htmlspecialchars($row[3]);
        $r4 = htmlspecialchars($row[4]);
        $r5 = htmlspecialchars($row[5]);
        $r6 = htmlspecialchars($row[6]);
        $r7 = htmlspecialchars($row[7]);
        $r8 = htmlspecialchars($row[8]);
        $r9 = htmlspecialchars($row[9]);
        $r10 = htmlspecialchars($row[10]);

        echo <<<_END
        <table>
            <tr><td><b>Número do Pedido:</b></td><td>$r9</td></tr>
            <tr><td><b>Nome:</b></td><td>$r0</td></tr>
            <tr><td><b>Local:</b></td><td>$r2</td></tr>
            <tr><td><b>Problema:</b></td><td>$r3</td></tr>
            <tr><td><b>Importância:</b></td><td>$r4</td></tr>
            <tr><td><b>Pedido feito em:</b></td><td>$r6</td></tr>
            <tr><td><b>Pedido começado em:</b></td><td>$r7</td></tr>
            <tr><td><b>Pedido terminado em:</b></td><td>$r8</td></tr>
            <tr><td><b>Como foi resolvido:</b></td><td>$r10</td></tr>
        </table> 
        <br>
    _END;
        if ($isAdmin) {
            echo <<<_END
            <form action='fazer.php' method='post'>
                <input type='hidden' name='mudar_feito' value='yes'>
                <input type='hidden' name='id' value='$r5'>
                <input type='submit' value='Mudar para Histórico'>
            </form>
            <form action='fazer.php' method='post'>
                <input type='hidden' name='erro_feito' value='yes'>
                <input type='hidden' name='id' value='$r5'>
                <input type='submit' value='Mudar para em Andamento'>
            </form>
            <p>
    _END;
        }
    }
                ?>
            </div>
    </div>
</body>
</html>
