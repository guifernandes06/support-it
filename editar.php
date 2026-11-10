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

    <title>Editar</title>

    <style>
        input[type="text"],
        input[type="number"] {
            width:  50%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1.2em;
        }

        select {
            width:  50%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }

        textarea {
            width:  50%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1.3em;
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
                        <a href="index.php">
                            Fazer Pedido
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
                </ul>
            </div>
        </div>

        <div class = "center-content">
            <h2>
                Editar Item
            </h2>
        </div>
        <?php
            //Recebe os dados a serem editados
            $id = filter_input(INPUT_POST, 'id');
            $nome = filter_input(INPUT_POST, 'nome');
            $quantidade = filter_input(INPUT_POST, 'quantidade');
            $utilidade = filter_input(INPUT_POST, 'utilidade');
            $detalhes = filter_input(INPUT_POST, 'detalhes');
            $item_creat = filter_input(INPUT_POST, 'item_creat');
        ?>

        <form action="salvar.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>"> 
            <input type="hidden" name="item_creat" value="<?php echo $item_creat; ?>"> 

            <input type="text" name="nome" value="<?php echo $nome; ?>">

            <input type="number" name="quantidade" value="<?php echo $quantidade; ?>">

            <select name="utilidade">
            <option disabled></option>
                <optgroup label="Cabos">
                    <option <?php if($utilidade == "Cabos de Rede") echo "selected"; ?>> Cabos de Rede </option>
                    <option <?php if($utilidade == "Cabos Display Port") echo "selected"; ?>> Cabos Display Port </option>
                    <option <?php if($utilidade == "Cabos HDMI") echo "selected"; ?>> Cabos HDMI </option>
                    <option <?php if($utilidade == "Cabos Power") echo "selected"; ?>> Cabos Power </option>
                    <option <?php if($utilidade == "Cabos VGA") echo "selected"; ?>> Cabos VGA </option>
                    <option <?php if($utilidade == "Carregador Diversos") echo "selected"; ?>> Carregador Diversos </option>
                    <option <?php if($utilidade == "Carregador Portátil") echo "selected"; ?>> Carregador Portátil </option>
                    <option disabled> </option>
                </optgroup>
                <optgroup label="Componentes PC">
                    <option <?php if($utilidade == "Computador") echo "selected"; ?>> Computador </option>
                    <option <?php if($utilidade == "Disco MVME") echo "selected"; ?>> Disco MVME </option>
                    <option <?php if($utilidade == "Disco Rígido") echo "selected"; ?>> Disco Rígido </option>
                    <option <?php if($utilidade == "Fonte") echo "selected"; ?>> Fonte </option>
                    <option <?php if($utilidade == "Memória") echo "selected"; ?>> Memória </option>
                    <option <?php if($utilidade == "Monitor") echo "selected"; ?>> Monitor </option>
                    <option <?php if($utilidade == "MotherBoard") echo "selected"; ?>> MotherBoard </option>
                    <option <?php if($utilidade == "Pasta Térmica") echo "selected"; ?>> Pasta Térmica </option>
                    <option <?php if($utilidade == "Placa de Rede") echo "selected"; ?>> Placa de Rede </option>
                    <option <?php if($utilidade == "Placa Gráfica") echo "selected"; ?>> Placa Gráfica </option>
                    <option <?php if($utilidade == "Processador (CPU)") echo "selected"; ?>> Processador (CPU) </option>
                    <option <?php if($utilidade == "RAM") echo "selected"; ?>> RAM </option>
                    <option <?php if($utilidade == "SSD") echo "selected"; ?>> SSD</option>
                    <option <?php if($utilidade == "UPS") echo "selected"; ?>> UPS </option>
                    <option <?php if($utilidade == "Ventoinha") echo "selected"; ?>> Ventoinha </option>
                    <option disabled> </option>
                </optgroup>
                <optgroup label="Impressora">
                    <option <?php if($utilidade == "Impressora") echo "selected"; ?>> Impressora </option>
                    <option <?php if($utilidade == "Impressora Bar Code") echo "selected"; ?>> Impressora Bar Code </option>
                    <option <?php if($utilidade == "Tambor") echo "selected"; ?>> Tambor </option>
                    <option <?php if($utilidade == "Tooner") echo "selected"; ?>> Tooner </option>
                    <option disabled> </option>
                </optgroup>
                <optgroup label="Telemóvel">
                    <option <?php if($utilidade == "Telemóvel Fixo") echo "selected"; ?>> Telemóvel Fixo </option>
                    <option <?php if($utilidade == "SmartPhone") echo "selected"; ?>> SmartPhone </option>
                    <option disabled> </option>
                </optgroup>
                <optgroup label="Outros">
                    <option <?php if($utilidade == "Auscultadores") echo "selected"; ?>> Auscultadores </option>
                    <option <?php if($utilidade == "CD's") echo "selected"; ?>> CD's </option>
                    <option <?php if($utilidade == "Combo Tecl. e Rato") echo "selected"; ?>> Combo Tecl. e Rato </option>
                    <option <?php if($utilidade == "Pastas Partilhadas") echo "selected"; ?>> Pastas Partilhadas </option>
                    <option <?php if($utilidade == "Pen USB") echo "selected"; ?>> Pen USB </option>
                    <option <?php if($utilidade == "Pistola Bar Code") echo "selected"; ?>> Pistola Bar Code </option>
                    <option <?php if($utilidade == "Portátil") echo "selected"; ?>> Portátil </option>
                    <option <?php if($utilidade == "Rato c/ fio") echo "selected"; ?>> Rato c/ fio </option>
                    <option <?php if($utilidade == "Rato s/ fio") echo "selected"; ?>> Rato s/ fio </option>
                    <option <?php if($utilidade == "Rooters") echo "selected"; ?>> Rooters </option>
                    <option <?php if($utilidade == "Servidor") echo "selected"; ?>> Servidor </option>
                    <option <?php if($utilidade == "Switchs") echo "selected"; ?>> Switchs </option>
                    <option <?php if($utilidade == "Switchs Servidor") echo "selected"; ?>> Switchs Servidor </option>
                    <option <?php if($utilidade == "Teclado") echo "selected"; ?>> Teclado </option>
                    <option <?php if($utilidade == "Triplas") echo "selected"; ?>> Triplas </option>
                    <option disabled> </option>
                </optgroup>
            </select>

            <textarea name="detalhes" rows="5" maxlength="250" > <?php echo $detalhes; ?> </textarea> <p> 
            <input type="submit" name="submit" value="Cancelar" formnovalidate> &nbsp; <input type="submit" value="Editar"> 
        </form>
    </body>
</html>