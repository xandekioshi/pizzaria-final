# Pizzaria Bella Massa

Sistema de pedidos de pizzaria feito em **PHP + PostgreSQL**, com visão do
**cliente** e do **administrador**. Projeto simples, organizado seguindo a
separação entre front-end (HTML/CSS/JS) e back-end (PHP).

## Estrutura de pastas

```
pizzaria-bella-massa/
├── assets/
│   ├── css/        (style.css, admin.css)
│   ├── js/         (carrinho, viacep, checkout, rastreio, admin-*, ...)
│   └── img/
├── config/
│   └── database.php   (conexão PDO com o PostgreSQL)
├── includes/
│   ├── header.php     (menu do site)
│   └── footer.php     (rodapé)
├── actions/           (back-end: scripts PHP sem tela)
│   ├── login.php, cadastro.php, logout.php
│   ├── calcular_frete.php, processar_pedido.php
│   ├── status_pedido.php, atualizar_status.php
│   ├── produtos_salvar.php, produtos_excluir.php
│   ├── dados_grafico.php, gerar_pizza_ia.php
├── admin/             (painel restrito)
│   ├── dashboard.php, produtos.php, pedidos.php
├── index.php          (cardápio)
├── checkout.php       (finalizar pedido)
├── login.php, cadastro.php
├── pedido-confirmado.php
├── prints/             (imagens das telas usadas neste README)
└── banco.sql          (script do banco de dados)
```

## Como rodar

### 1. Criar o banco de dados
Abra o PostgreSQL (pelo psql ou pela sua ferramenta) e crie o banco:

```sql
CREATE DATABASE pizzaria;
```

Depois conecte-se a esse banco e rode o arquivo `banco.sql`, que cria as
tabelas e já insere alguns produtos e usuários de teste.

No psql, por exemplo:
```
\c pizzaria
\i banco.sql
```

### 2. Configurar a conexão
Abra `config/database.php` e ajuste se necessário (host, porta, usuário e,
principalmente, a **senha** do seu PostgreSQL):

```php
$DB_HOST  = '127.0.0.1';
$DB_PORT  = '5432';
$DB_NOME  = 'pizzaria';
$DB_USER  = 'postgres';
$DB_SENHA = 'postgres';   // <-- troque pela sua senha
```

### 3. Rodar o servidor PHP
Dentro da pasta do projeto:

```
php -S localhost:8000
```

E acesse no navegador: http://localhost:8000

> É preciso ter o PHP com a extensão **pdo_pgsql** habilitada.

## Usuários de teste

| Perfil  | E-mail                  | Senha       |
|---------|-------------------------|-------------|
| Admin   | admin@bellamassa.com    | admin123    |
| Cliente | cliente@teste.com       | cliente123  |

## Telas do sistema

O sistema é dividido em duas áreas: a do **cliente** (pública) e a do
**administrador** (restrita). Abaixo, uma descrição das principais telas.

### 1. Cardápio (`index.php`)

![Tela do cardápio](prints/print_1.png)

É a página inicial e o coração do site. O menu superior se adapta ao usuário:
mostra "Entrar" e "Cadastrar" para visitantes, ou o nome da pessoa e o link de
sair quando há login (e o link do Painel Admin, se for administrador). Logo
abaixo vem o banner de apresentação e o **cardápio dinâmico**, com as pizzas e
bebidas vindas direto do banco de dados (separadas pelas abas "Pizzas" e
"Bebidas"). Cada produto tem o botão "Adicionar", que joga o item no carrinho
lateral e atualiza o total em tempo real.

![Detalhe dos cards do cardápio](prints/print_3.png)

Ainda nesta página fica a seção **"Crie sua pizza com Inteligência Artificial"**,
onde o cliente escolhe 3 ingredientes e a IA gera um nome e uma descrição para a
pizza personalizada.

![Seção do gerador de pizza com IA](prints/print_4.png)

No fim da página estão os blocos "Sobre" e "Contato".

![Seções Sobre e Contato](prints/print_2.png)

### 2. Cadastro (`cadastro.php`)

![Tela de cadastro](prints/print_5.png)

Formulário para o cliente criar uma conta, com nome, e-mail, senha e confirmação
de senha. A senha é guardada no banco com hash (`password_hash`), nunca em texto
puro, e todo novo usuário entra como cliente comum. Há validação tanto no
navegador (as senhas precisam coincidir) quanto no back-end.

### 3. Login (`login.php`)

![Tela de login](prints/print_6.png)

Tela de autenticação onde o usuário informa e-mail e senha. Ao entrar, o sistema
cria a sessão (`$_SESSION`): clientes são levados ao cardápio e administradores
ao painel. Só quem está logado consegue finalizar um pedido.

### 4. Finalizar pedido / Checkout (`checkout.php`)

![Tela de checkout](prints/print_7.png)

Acessível apenas para usuários logados. De um lado fica o **endereço de
entrega**, onde o cliente digita o CEP e a integração com o **ViaCEP** preenche
rua, bairro, cidade e estado automaticamente. O botão "Calcular frete" envia os
dados ao back-end, que é o único responsável por calcular a taxa de entrega. Do
outro lado fica o **resumo do pedido**, com os itens, o subtotal, o frete e o
total. Ao confirmar, o pedido é validado e salvo no banco de forma segura (o
preço de cada item é sempre reconferido no servidor).

### 5. Pedido confirmado (`pedido-confirmado.php`)

![Tela de pedido confirmado](prints/print_8.png)

Mostrada logo após fechar o pedido. Exibe o número do pedido e uma **linha do
tempo de rastreio** (Pedido recebido → Em preparo → Saiu para entrega →
Entregue) que se atualiza sozinha, sem recarregar a página, conforme o admin
muda o status. Traz também o resumo com frete e total e um botão que monta um
link do **WhatsApp** já com o resumo do pedido pronto para enviar ao restaurante.

### Área administrativa (`admin/`)
Restrita a administradores, reúne três telas: o **Dashboard** (com cartões de
resumo e um gráfico de vendas), a tela de **Produtos** (cadastrar, editar e
excluir itens do cardápio) e a de **Pedidos** (acompanhar os pedidos e mudar o
status de cada um).

## O que está implementado

Obrigatórios:
- Cardápio dinâmico (lido do banco)
- Carrinho com JavaScript (adicionar, remover, total em tempo real, localStorage)
- Integração ViaCEP no checkout
- Login/cadastro com sessões ($_SESSION) e senha com hash
- Banco PostgreSQL com as 4 tabelas e suas chaves (PK/FK)

Desejável/Opcional:
- Cálculo do frete feito no back-end (PHP) — `actions/calcular_frete.php`
- Processamento seguro do pedido (o preço é sempre recalculado no servidor)
- Área administrativa (dashboard, produtos com CRUD, gestão de pedidos)

Extras (bônus):
- **Extra 1** — Checkout via WhatsApp (link wa.me montado pelo PHP)
- **Extra 2** — Dashboard com gráfico (Chart.js + GROUP BY/SUM)
- **Extra 3** — Rastreio do pedido ao vivo (polling com setInterval)
- **Extra 4** — Gerador de sabores. Funciona localmente sem chave; se você
  colocar uma chave do Gemini em `actions/gerar_pizza_ia.php`, ele usa a IA
  de verdade via cURL.

## Observações
- As fotos dos produtos foram trocadas por emojis (🍕 / 🥤), porque a tabela
  `produtos` não tem coluna de imagem (seguindo a modelagem pedida).
- O número do WhatsApp do restaurante está como exemplo em
  `pedido-confirmado.php` (variável `$whatsappRestaurante`) — troque pelo real.
