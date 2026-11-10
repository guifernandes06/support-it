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

    <title>Armazem</title>

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
                            Inventário
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

        <div class="container">
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
        
        $sth = $ligacao->prepare("SELECT * FROM armazem WHERE nome = ?");
        
        $sth->bind_param("s", $str);
        $sth->execute();
        
        $result = $sth->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $r0 = htmlspecialchars($row['nome']);
                $r1 = htmlspecialchars($row['email']);
                $r2 = htmlspecialchars($row['pergunta_local']);
                $r3 = htmlspecialchars($row['problema']);
                $r4 = htmlspecialchars($row['importante']);
                $r6 = htmlspecialchars($row['hora_timestamp']);
                $r7 = htmlspecialchars($row['andamento_timestamp']);
                $r8 = htmlspecialchars($row['feito_timestamp']);
                $r9 = htmlspecialchars($row['pdd_number']);
                $r10 = htmlspecialchars($row['resolve']);
                
                echo <<< _END
                    <div class="armazem_pdd">
                        <table class="table_search">
                            <tr><td><b>Número do Pedido:</b></td><td>$r9</td></tr>
                            <tr><td><b>Nome:</b></td><td>$r0</td></tr>
                            <tr><td><b>Email:</b></td><td>$r1</td></tr>
                            <tr><td><b>Local:</b></td><td>$r2</td></tr>
                            <tr><td><b>Problema:</b></td><td>$r3</td></tr>
                            <tr><td><b>Importância:</b></td><td>$r4</td></tr>
                            <tr><td><b>Data do pedido:</b></td><td>$r6</td></tr>
                            <tr><td><b>Data do progresso:</b></td><td>$r7</td></tr>
                            <tr><td><b>Data terminado:</b></td><td>$r8</td></tr>
                            <tr><td><b>Como foi resolvido:</b></td><td>$r10</td></tr>
                        </table>
                    </div>
                _END;
            }
        } else {
            echo "<b> O nome que inseriu não existe! </b>";
        }
        $sth->close();
    }
    $ligacao->close();
    ?>

        <div class="col-12 center-content">
            <h2>Histórico de Pedidos Fechados</h2>
        </div>
    
        <div class="armazem">
        <?php

        $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';

        require_once 'login_bd.php';
        $ligacao = new mysqli($hn, $un, $pw, $db);

        mysqli_set_charset($ligacao, "utf8");
        if ($ligacao->connect_error) die("Erro Fatal");

        if (isset($_POST['delete']) && isset($_POST['id'])) {

            $id = get_post($ligacao, 'id');
            $query = "SELECT * FROM armazem WHERE id = '$id'";
            $result = $ligacao->query($query);

            if ($result && $result->num_rows > 0) {

                $row = $result->fetch_assoc();
                $query_insert = "INSERT INTO pessoal (nome, email, pergunta_local, problema, importante, hora_timestamp, andamento_timestamp, feito_timestamp, pdd_number) VALUES ('{$row['nome']}', '{$row['email']}', '{$row['pergunta_local']}', '{$row['problema']}', '{$row['importante']}', '{$row['hora_timestamp']}', '{$row['andamento_timestamp']}', '{$row['feito_timestamp']}', '{$row['pdd_number']}')";
                $result_insert = $ligacao->query($query_insert);

                if ($result_insert) {

                    $query_delete = "DELETE FROM armazem WHERE id = '$id'";
                    $ligacao->query($query_delete);
                    
                } else {
                    echo "Falha ao mudar registo <br><br>";
                }
            }
        }

        $query = "SELECT * FROM armazem";
        $result = $ligacao->query($query);
        if (!$result) die ("Falha no acesso à base de dados");
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
            <div class="armazem_pdd">
                <center>
                    <table>
                        <tr> <td> <b>Número do Pedido:</b> </td> <td> $r9 </td> </tr>
                        <tr> <td> <b>Nome:</b> </td> <td> $r0 </td> </tr>
                        <tr> <td> <b>Email:</b> </td> <td> $r1 </td> </tr>
                        <tr> <td> <b>Local:</b> </td> <td> $r2 </td> </tr>
                        <tr> <td> <b>Problema:</b> </td> <td> $r3 </td> </tr>
                        <tr> <td> <b>Importante:</b> </td> <td> $r4 </td> </tr>
                        <tr> <td> <b>Data do pedido:</b> </td> <td> $r6 </td> </tr>
                        <tr> <td> <b>Data do progresso:</b> </td> <td> $r7 </td> </tr>
                        <tr> <td> <b>Data terminado:</b> </td> <td> $r8 </td> </tr>
                        <tr> <td> <b>Como foi resolvido:</b> </td> <td> $r10 </td> </tr>
                    </table>
                    <br>
            _END;
            if ($isAdmin) {
                echo <<<_END
                    <form action="armazem.php" method="post">
                        <input type="submit" value="Eliminar Pedido">
                        <input type="hidden" name="delete" value="yes">
                        <input type="hidden" name="id" value="$r5">
                    </form>
                </center>
            </div>
            _END;
            } else {
                echo "</center></div>";
            }
        }

        function get_post($ligacao, $var) {
            return $ligacao->real_escape_string($_POST[$var]);
        }
        ?>
    </div>
</div>

    </div>
</body>
</html>
