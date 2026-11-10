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

    <title>Pessoal</title>

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
                    <li id="search_li">
                        <form method="post" autocomplete="off">
                            <input type="text" name="search" id="search" placeholder="Pesquisar...">
                            <button type="submit" name="submit" id="submit" class="search-button">
                                <img src="imagens/search.png" alt="Ícone de pesquisa">
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class = "container">
        <?php
    session_start();

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
		
        $username = $_SESSION['username'];

        $queries = [
            "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, NULL AS andamento_timestamp, NULL AS feito_timestamp FROM form WHERE problema LIKE ? AND nome = ?",
            "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, andamento_timestamp, NULL AS feito_timestamp FROM andamento WHERE problema LIKE ? AND nome = ?",
            "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, andamento_timestamp, feito_timestamp FROM feito WHERE problema LIKE ? AND nome = ?",
            "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, andamento_timestamp, feito_timestamp FROM armazem WHERE problema LIKE ? AND nome = ?"
        ];

        $all_results = [];
		
        foreach ($queries as $query) {
			
            $sth = $ligacao->prepare($query);
            $param = "%" . $str . "%"; 
            $sth->bind_param("ss", $param, $username); // Adicione o parâmetro de usuário
			
            $sth->execute();
            $result = $sth->get_result();

            while ($row = $result->fetch_assoc()) {
                $all_results[] = $row;
            }

            $sth->close();
        }

        if (count($all_results) > 0) {
            foreach ($all_results as $row) {
                $r0 = htmlspecialchars($row['nome'] ?? '');
                $r2 = htmlspecialchars($row['local'] ?? '');
                $r3 = htmlspecialchars($row['problema'] ?? '');
                $r4 = htmlspecialchars($row['importante'] ?? '');
                $r5 = htmlspecialchars($row['hora'] ?? '');
                $r6 = htmlspecialchars($row['pdd'] ?? '');
                $r7 = htmlspecialchars($row['andamento_timestamp'] ?? '');
                $r8 = htmlspecialchars($row['feito_timestamp'] ?? '');

                echo <<< _END
                    <div class="column_search">
                        <table class="table_search">
                            <tr><td><b>Número do Pedido:</b></td><td>$r6</td></tr>
                            <tr><td><b>Nome:</b></td><td>$r0</td></tr>
                            <tr><td><b>Local:</b></td><td>$r2</td></tr>
                            <tr><td><b>Problema:</b></td><td>$r3</td></tr>
                            <tr><td><b>Importância:</b></td><td>$r4</td></tr>
                            <tr><td><b>Pedido feito em:</b></td><td>$r5</td></tr>
                _END;
                if ($r7) {
                    echo <<< _END
                            <tr><td><b>Andamento em:</b></td><td>$r7</td></tr>
                    _END;
                }
                if ($r8) {
                    echo <<< _END
                            <tr><td><b>Feito em:</b></td><td>$r8</td></tr>
                    _END;
                }
                echo <<< _END
                        </table>
                    </div>
                _END;
            }
        } else {
            echo "<b> O problema que inseriu não existe! </b>";
        }
    }

    $ligacao->close();
?>


        </div>

        <div class="center-content">
            <h2>Página pessoal de Pedidos</h2>
        </div>

        <div class = "container">
        <?php
        
        require_once 'login_bd.php';
        $ligacao = new mysqli($hn, $un, $pw, $db);

        if ($ligacao->connect_error) {
            die("Connection failed: " . $ligacao->connect_error);
        }

        $username = $_SESSION['username'];

        $queries = [
            "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, NULL AS andamento_timestamp, NULL AS feito_timestamp FROM form WHERE nome = ?",
            "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, andamento_timestamp, NULL AS feito_timestamp FROM andamento WHERE nome = ?",
            "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, andamento_timestamp, feito_timestamp, resolve FROM feito WHERE nome = ?",
            "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, andamento_timestamp, feito_timestamp, resolve FROM armazem WHERE nome = ?",
            "SELECT nome, email, pergunta_local AS local, problema, importante, hora_timestamp AS hora, pdd_number AS pdd, andamento_timestamp, feito_timestamp, resolve FROM pessoal WHERE nome = ?"
        ];

        $all_results = [];

        foreach ($queries as $query) {

            if ($stmt = $ligacao->prepare($query)) {

                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $all_results[] = $row;
                }

                $stmt->close();
            } else {

            die("Falha no acesso à base de dados");
        }
        }

        if (count($all_results) > 0) {

            foreach ($all_results as $row) {
                $r0 = htmlspecialchars($row['nome'] ?? '');
                $r1 = htmlspecialchars($row['email'] ?? '');
                $r2 = htmlspecialchars($row['local'] ?? '');
                $r3 = htmlspecialchars($row['problema'] ?? '');
                $r4 = htmlspecialchars($row['importante'] ?? '');
                $r6 = htmlspecialchars($row['hora'] ?? '');
                $r7 = htmlspecialchars($row['pdd'] ?? '');
                $r8 = htmlspecialchars($row['andamento_timestamp'] ?? '');
                $r9 = htmlspecialchars($row['feito_timestamp'] ?? '');
                $r10 = htmlspecialchars($row['resolve'] ?? '');
            
                echo <<< _END
                    <div class="coluna_pessoal">
                        <table class="table_pessoal">
                            <tr><td><b>Número do Pedido:</b></td><td>$r7</td></tr>
                            <tr><td><b>Nome:</b></td><td>$r0</td></tr>
                            <tr><td><b>Local:</b></td><td>$r2</td></tr>
                            <tr><td><b>Problema:</b></td><td>$r3</td></tr>
                            <tr><td><b>Importância:</b></td><td>$r4</td></tr>
                            <tr><td><b>Pedido feito em:</b></td><td>$r6</td></tr>
                _END;
                if ($r8 !== '') {
                    echo <<< _END
                            <tr><td><b>Andamento em:</b></td><td>$r8</td></tr>
                    _END;
                }
                if ($r9 !== '') {
                    echo <<< _END
                            <tr><td><b>Feito em:</b></td><td>$r9</td></tr>
                            <tr><td><b>Como foi resolvido:</b></td><td>$r10</td></tr>
                _END;
                }
                echo <<< _END
                        </table>
                    </div>
                _END;
            }
        }

    $ligacao->close();
?>
            </div>
    </div>
</body>
</html>
