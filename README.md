# Support IT - Sistema de Helpdesk e Inventário

O Support IT é uma aplicação web desenvolvida para a gestão integrada de infraestruturas tecnológicas. O sistema combina duas componentes fundamentais num único painel: o controlo rigoroso do stock e inventário informático do armazém e um sistema completo de tickets para suporte técnico (Helpdesk).

## Características e Funcionalidades

*   **Autenticação Segura:** Área de login e registo de utilizadores para acesso restrito e seguro às ferramentas de gestão.
*   **Gestão de Tickets (Helpdesk):** Fluxo completo de pedidos de assistência técnica organizado por três estados de triagem:
    *   Pedidos por fazer
    *   Pedidos em andamento
    *   Pedidos concluídos
*   **Histórico de Pedidos:** Registo centralizado de todos os tickets finalizados para auditoria e controlo de assistência.
*   **Controlo de Inventário (Armazém):** Módulo dedicado ao registo, edição e controlo de stock de hardware, periféricos e componentes informáticos.
*   **Motor de Pesquisa:** Sistema integrado de filtragem e pesquisa para localização rápida de itens no armazém ou tickets de suporte.
*   **Notificações por Email:** Integração com o PHPMailer para automatização de alertas de tickets e comunicações do sistema.

## Tecnologias Utilizadas

*   **PHP:** Linguagem principal no desenvolvimento da lógica de negócio e processamento de dados do servidor.
*   **MySQL / SQL:** Base de dados relacional utilizada para a persistência de dados de utilizadores, stock e tickets (projeto.sql).
*   **Composer:** Gestor de dependências PHP utilizado para a integração e automação de pacotes de terceiros.
*   **PHPMailer:** Biblioteca utilizada para o envio de e-mails através do servidor.
*   **HTML5 e CSS3:** Estruturação semântica e estilização visual das interfaces do painel e dashboard.

## Estrutura do Projeto

*   `index.php` - Painel principal / Dashboard da aplicação.
*   `armazem.php` e `inventario.php` - Interfaces de visualização e controlo do stock informático.
*   `item.php` e `editar.php` - Páginas para detalhe, inserção e modificação de ativos de TI.
*   `login.php`, `login_bd.php` e `register.php` - Módulos do sistema de autenticação.
*   `database/projeto.sql` - Script de estrutura e configuração da base de dados MySQL.
*   `vendor/` - Diretório de dependências gerido pelo Composer (inclui PHPMailer).
