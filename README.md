# Sistema de Mensagens em PHP

Este projeto é um sistema simples para envio e gerenciamento de mensagens utilizando PHP, MySQL, JavaScript e CSS.

Funcionalidades principais incluem cadastro de mensagens, listagem, edição e exclusão protegidas por senha. As senhas são armazenadas de forma segura utilizando hash.

---

Configuração básica

No arquivo php/config.php é feita a conexão com o banco de dados utilizando MySQLi e a sessão é iniciada para permitir mensagens temporárias ao usuário. Também existe uma função que exibe essas mensagens quando necessário.

É necessário criar um banco de dados chamado mensagens_site e a tabela mensagens com os campos id, nome, email, mensagem, senha e data de criação.

---

Arquivos do sistema

index.php
Este é o arquivo principal, responsável por processar cadastro, edição e exclusão de mensagens. Ele valida a senha antes de excluir, criptografa novas senhas com password_hash() e atualiza mensagens quando solicitado. Também consulta o banco para listar todas as mensagens e inclui o HTML da interface. Possui ainda um script em JavaScript que controla o modal de edição e faz a verificação de senha via AJAX.

verificar_senha.php
Este arquivo é usado para validar a senha antes da edição. Ele recebe os dados via POST, busca a senha no banco e utiliza password_verify() para comparação, retornando "ok" ou "erro" para o JavaScript.

php/config.php
Responsável pela conexão com o banco de dados usando mysqli e pela inicialização da sessão. Também contém uma função simples para exibir mensagens armazenadas na sessão, usadas como feedback ao usuário.

css/style.css
Define o visual da aplicação, incluindo layout centralizado, formulários, botões, cartões de mensagens e o modal de edição. Também aplica cores e espaçamento para melhorar a apresentação.

---

Segurança

* Senhas armazenadas com password_hash()
* Validação com password_verify()
* Uso de prepared statements para evitar SQL Injection
* Uso de htmlspecialchars() para evitar XSS
