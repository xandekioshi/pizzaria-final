<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erros = [
    'email'  => 'Este e-mail já está cadastrado.',
    'senha'  => 'As senhas não coincidem.',
    'campos' => 'Preencha todos os campos corretamente.',
];
$erro = $_GET['erro'] ?? '';
$mensagemErro = $erros[$erro] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criar conta - Pizzaria Bella Massa</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="pagina-autenticacao">

  <header class="cabecalho cabecalho--simples">
    <div class="container cabecalho__conteudo">
      <a href="index.php" class="logo">
        <span class="logo__icone" aria-hidden="true">🍕</span>
        <span class="logo__texto">Bella Massa</span>
      </a>
    </div>
  </header>

  <main class="conteudo-autenticacao">
    <div class="container">
      <div class="cartao-autenticacao">
        <h1 class="cartao-autenticacao__titulo">Criar conta</h1>

        <form id="form-cadastro" action="actions/cadastro.php" method="POST" class="formulario" novalidate>

          <p id="mensagem-erro-cadastro" class="mensagem-erro" <?php echo $mensagemErro ? '' : 'hidden'; ?>>
            <?php echo htmlspecialchars($mensagemErro); ?>
          </p>

          <div class="campo-formulario">
            <label for="nome-cadastro">Nome completo</label>
            <input type="text" id="nome-cadastro" name="nome" autocomplete="name" required>
          </div>

          <div class="campo-formulario">
            <label for="email-cadastro">E-mail</label>
            <input type="email" id="email-cadastro" name="email" autocomplete="email" required>
          </div>

          <div class="campo-formulario">
            <label for="senha-cadastro">Senha</label>
            <input type="password" id="senha-cadastro" name="senha" autocomplete="new-password" minlength="6" required>
          </div>

          <div class="campo-formulario">
            <label for="confirmar-senha-cadastro">Confirmar senha</label>
            <input type="password" id="confirmar-senha-cadastro" name="confirmar_senha" autocomplete="new-password" minlength="6" required>
          </div>

          <button type="submit" class="botao botao--primario botao--bloco">Criar conta</button>
        </form>

        <p class="cartao-autenticacao__rodape">
          Já tem conta? <a href="login.php">Entrar</a>
        </p>
      </div>
    </div>
  </main>

  <?php include __DIR__ . '/includes/footer.php'; ?>

  <script src="assets/js/autenticacao.js" defer></script>
</body>
</html>
