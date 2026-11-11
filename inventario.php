<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Desenvolvimento Web">
    <meta name="keywords" content="HTML, CSS, PHP">
    <meta name="author" content="Guilherme Fernandes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="imagens/mini_SML.png" type="image/svg+xml"/>
    <link rel="stylesheet" type="text/css" href="style_item.css">

    <title>Inventário</title>

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
    </style>

</head>
<body>
    <div class="row">
        <div class="header">
            <div class="header_logo">
                <a href="index.php">
                    <img src="imagens/SML_logo.png" alt="Logo"> 
                </a>
            </div>
            <div class="menu">
                <ul>
                    <li>
                        <a href="fazer.php">
                            Lista de Pedidos
                        </a>
                    </li>
                    <?php
                        session_start();

                        $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';

                        if ($isAdmin) {
                            echo <<<_END
                            <li>
                                <a href="item.php">Novo Item</a>
                            </li>
                            _END;
                        }
                    ?>
                    <li>
                        <a href="inventario.php">Inventário IT</a>
                    </li>
                    <li>
                        <a href="logout.php">Logout</a>
                    </li>
                    <li id="search_li">
                        <form method="post" autocomplete="off">
                            <input type="text" name="search" id="search" placeholder="Pesquisar...">
                            <button type="submit" name="submit" id="submit" class="search-button">
                                <img src="imagens/search.png" >
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container">
        <?php
        if (!isset($_SESSION['username'])) {
            header("Location: login.php");
            exit();
        }

        require_once 'login_bd.php';
        $ligacao = new mysqli($hn, $un, $pw, $db);

        if ($ligacao->connect_error) {
            die("Connection failed: " . $ligacao->connect_error);
        }

        if (isset($_POST["submit"])) {
            $str = $_POST["search"];
            $str = "%" . $str . "%"; 

            $sth = $ligacao->prepare("SELECT * FROM item WHERE nome LIKE ?");
            $sth->bind_param("s", $str);
            $sth->execute();

            $result = $sth->get_result();

            $rows = $result->num_rows;

            while ($dados = mysqli_fetch_assoc($result)) {
                echo <<< _END
                <div class="inventario_search">
                    <table class="table_search">
                        <tr> <td> <b> Nome do Item: </b> </td> <td>{$dados['nome']}</td> </tr>
                        <tr> <td> <b> Quantidade: </b> </td> <td>{$dados['quantidade']}</td> </tr>
                        <tr> <td> <b> Utilidade do Item: </b> </td> <td>{$dados['utilidade']}</td> </tr>
                        <tr> <td> <b> Detalhes: </b> </td> <td>{$dados['detalhes']}</td> </tr>
                        <tr> <td> <b> Item criado em: </b> </td> <td>{$dados['item_creat']}</td> </tr>
                    </table>
                _END;
            
                if ($isAdmin) {
                    echo "
                    <form action='editar.php' method='post'>
                        <input name='id' type='hidden' value='" .$dados['id']. "'>
                        <input name='nome' type='hidden' value='" .$dados['nome']. "'>
                        <input name='quantidade' type='hidden' value='" .$dados['quantidade']. "'>
                        <input name='utilidade' type='hidden' value='" .$dados['utilidade']. "'>
                        <input name='detalhes' type='hidden' value='" .$dados['detalhes']. "'>
                        <input name='item_creat' type='hidden' value='" .$dados['item_creat']. "'>
                        <input type='submit' value='Alterar item'>";
            
                    if ($dados['quantidade'] > 0) {
                        echo "
							<input type='hidden' name='id' value='" .$dados['id']. "'>
							<input type='hidden' name='quantidade' value='1'>
							<input type='submit' name='consumir' formaction='inventario.php' value='Consumir'>
						</form>";
                    }
					
					echo " <p>
						<form action='inventario.php' method='post'>
							<center> <input type='submit' name='delete' value='Apagar Item'> </center>
							<input type='hidden' name='delete' value='yes'>
							<input type='hidden' name='id' value='" .$dados['id']. "'>
						</form>";
                }
                echo "</div>";
            }
            
        } 

        $ligacao->close();
        ?>
        </div>

        <div class = "center-content">
            <h2>
                Inventário
            </h2>
        </div>
        
        <div class="container">
        <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        if (!isset($_SESSION['username'])) {
            header("Location: login.php");
            exit();
        }

        $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';

        require_once 'login_bd.php';
        require 'vendor/autoload.php'; 

        $ligacao = new mysqli($hn, $un, $pw, $db);

        if ($ligacao->connect_error) die("Erro Fatal");

        function get_post($ligacao, $var) {
            return $ligacao->real_escape_string($_POST[$var]);
        }

        function sendOutOfStockEmail($nome, $utilidade) {
            $mail = new PHPMailer(true);
        
            try {
                // Configuração do PHPMailer
                $mail->CharSet = 'UTF-8'; // Define o conjunto de caracteres como UTF-8
                $mail->isSMTP();
                $mail->Host       = 'smtp-mail.outlook.com';  // Define o servidor SMTP para enviar através de
                $mail->SMTPAuth = true;
                $mail->Username   = 'email'; // Nome de usuário SMTP
                $mail->Password   = '*****'; // Senha de aplicativo SMTP
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita criptografia TLS com STARTTLS
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
        
                // Configuração do email
                // Destinatários
                $mail->setFrom('email', 'Support IT');
                $mail->addAddress('email'); // Adiciona o destinatário fixo
                $mail->addReplyTo('email', 'Support IT');
        
                // Conteúdo
                $mail->isHTML(true);                   // Define o formato do email como HTML
                $mail->Subject = 'ITEM CONSUMIDO';
                $mail->Body    = "O item <b> $nome </b> chegou a <b> 0 </b>, tem a utilidade de '$utilidade'! <p> Necessário ligar fornecedor!";
                $mail->AltBody = "O item $nome chegou a 0, tem a utilidade de '$utilidade'! <p> Necessário ligar fornecedor!";
        
                // Envia o email
                $mail->send();
            } catch (Exception $e) {
                echo "Erro ao enviar email: {$mail->ErrorInfo}";
            }
        }
        
		function sendOutOfOneEmail($nome, $utilidade) {
            $mail = new PHPMailer(true);
        
            try {
                // Configuração do PHPMailer
                $mail->CharSet = 'UTF-8'; // Define o conjunto de caracteres como UTF-8
                $mail->isSMTP();
                $mail->Host       = 'smtp-mail.outlook.com';  // Define o servidor SMTP para enviar através de
                $mail->SMTPAuth = true;
                $mail->Username   = 'email'; // Nome de usuário SMTP
                $mail->Password   = '*****'; // Senha de aplicativo SMTP
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita criptografia TLS com STARTTLS
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
        
                // Configuração do email
                // Destinatários
                $mail->setFrom('email', 'Support IT');
                $mail->addAddress('email'); // Adiciona o destinatário fixo
                $mail->addReplyTo('email', 'Support IT');
        
                // Conteúdo
                $mail->isHTML(true);                   // Define o formato do email como HTML
                $mail->Subject = 'ALERTA QUANTIDADE';
                $mail->Body    = "O item <b> $nome </b> chegou à quantidade <b> 1 </b> e tem a utilidade de '$utilidade'! <p> Necessário ligar fornecedor!";
                $mail->AltBody = "O item $nome chegou à quantidade 1 e tem a utilidade de '$utilidade'! <p> Necessário ligar fornecedor!";
        
                // Envia o email
                $mail->send();
            } catch (Exception $e) {
                echo "Erro ao enviar email: {$mail->ErrorInfo}";
            }
        }
		
        if ($isAdmin && isset($_POST['consumir']) && isset($_POST['id']) && isset($_POST['quantidade'])) {
            $id = get_post($ligacao, 'id');
            $quantidade = get_post($ligacao, 'quantidade');

            $query = "SELECT * FROM item WHERE id = '$id'";
            $result = $ligacao->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $query_consumir = "UPDATE item SET quantidade = quantidade - 1 WHERE id = '$id'";
                $result_consumir = $ligacao->query($query_consumir);
				
				if ($result_consumir) {
					$query_hora = "UPDATE item SET item_creat = CURRENT_TIMESTAMP WHERE id = '$id'";
					$result_hora = $ligacao->query($query_hora);
				}

                if (!$result_consumir) {
                    echo "Falha ao atualizar a quantidade <br><br>";
                } else {
                    $query_check = "SELECT quantidade FROM item WHERE id = '$id'";
                    $result_check = $ligacao->query($query_check);
                    $updated_row = $result_check->fetch_assoc();

                    if ($updated_row['quantidade'] == 0) {
                        sendOutOfStockEmail($row['nome'], $row['utilidade']);
                    }
					
					if ($updated_row['quantidade'] == 1) {
                        sendOutOfOneEmail($row['nome'], $row['utilidade']);
                    }
                }
            } else {
                echo "Falha ao encontrar o item <br><br>";
            }
        }
		
		if (isset($_POST['delete']) && isset($_POST['id'])) {

			$id = $_POST['id'];

			function sendEmailItemDeleted($nome) {
				$mail = new PHPMailer(true);

				try {
					// Configuração do PHPMailer
					$mail->CharSet = 'UTF-8'; // Define o conjunto de caracteres como UTF-8
					$mail->isSMTP();
					$mail->Host       = 'smtp-mail.outlook.com';  // Define o servidor SMTP para enviar através de
					$mail->SMTPAuth   = true;
					$mail->Username   = 'email'; // Nome de usuário SMTP
					$mail->Password   = '*****'; // Senha de aplicativo SMTP
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita criptografia TLS com STARTTLS
					$mail->Port       = 587;

					// Configuração do email
					// Destinatários
					$mail->setFrom('email', 'Support IT');
					$mail->addAddress('email'); // Adiciona o destinatário fixo
					$mail->addReplyTo('email', 'Support IT');

					// Conteúdo
					$mail->isHTML(true);  // Define o formato do email como HTML
					$mail->Subject = 'ITEM ELIMINADO';
					$mail->Body    = "O item <b>$nome</b> foi eliminado com sucesso!";
					$mail->AltBody = "O item $nome foi eliminado com sucesso!";

					// Envia o email
					$mail->send();
				} catch (Exception $e) {
					echo "Erro ao enviar email: {$mail->ErrorInfo}";
				}
			}

			// Obter o nome do item antes de deletar
			$stmt = $ligacao->prepare("SELECT nome FROM item WHERE id = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$nome = $row['nome'];

				// Enviar email
				sendEmailItemDeleted($nome);

				// Deletar o item da base de dados
				$stmt = $ligacao->prepare("DELETE FROM item WHERE id = ?");
				$stmt->bind_param("i", $id);
				$stmt->execute();

			} else {
				echo "Item não encontrado.";
			}
		}
		

        $query = "SELECT * FROM item ORDER BY nome";
        $result = $ligacao->query($query);

        if (!$result) die("Falha no acesso à base de dados");

        $rows = $result->num_rows;

        while($dados = mysqli_fetch_assoc($result)) {
            echo <<< _END
            <div class="inventario_search">
                <table>
                    <tr> <td> <b> Nome do Item: </b> </td> <td>{$dados['nome']}</td> </tr>
                    <tr> <td> <b> Quantidade: </b> </td> <td>{$dados['quantidade']}</td> </tr>
                    <tr> <td> <b> Tipo de Item: </b> </td> <td>{$dados['utilidade']}</td> </tr>
                    <tr> <td> <b> Detalhes: </b> </td> <td>{$dados['detalhes']}</td> </tr>
                    <tr> <td> <b> Item criado em: </b> </td> <td>{$dados['item_creat']}</td> </tr>
                </table>
            _END;
			
            if ($isAdmin) {
                echo "
                <form action='editar.php' method='post'>
                    <input name='id' type='hidden' value='" .$dados['id']. "'>
                    <input name='nome' type='hidden' value='" .$dados['nome']. "'>
                    <input name='quantidade' type='hidden' value='" .$dados['quantidade']. "'>
                    <input name='utilidade' type='hidden' value='" .$dados['utilidade']. "'>
                    <input name='detalhes' type='hidden' value='" .$dados['detalhes']. "'>
                    <input name='item_creat' type='hidden' value='" .$dados['item_creat']. "'>
                    <input type='submit' value='Alterar item'>
                ";
				
                if ($dados['quantidade'] > 0) {
                    echo "
                        <input type='hidden' name='id' value='" .$dados['id']. "'>
                        <input type='hidden' name='quantidade' value='1'>
                        <input type='submit' name='consumir' formaction='inventario.php' value='Consumir'>
                    </form>";
                }
			
				echo " <p>
				<form action='inventario.php' method='post'>
					 <input type='submit' value='Apagar Item'> 
					<input type='hidden' name='delete' value='yes'>
					<input type='hidden' name='id' value='" .$dados['id']. "'>
				</form>";
					
                echo "</div>";
            } 
        }
        ?>

        </div>
    </div>
</body>
</html>
