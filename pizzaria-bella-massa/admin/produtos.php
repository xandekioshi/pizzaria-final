<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['is_admin'])) {
    header('Location: ../login.php');
    exit;
}


$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Admin - Produtos</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="pagina-admin">

  <div class="admin-layout">

    <aside class="admin-menu" aria-label="Menu administrativo">
      <div class="admin-menu__logo">
        <span>Bella Massa</span>
        <small>Painel Admin</small>
      </div>
      <nav>
        <ul class="admin-menu__lista">
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="produtos.php" class="is-ativo">Produtos</a></li>
          <li><a href="pedidos.php">Pedidos</a></li>
          <li><a href="../index.php">Ver site</a></li>
          <li><a href="../actions/logout.php" id="link-logout-admin">Sair</a></li>
        </ul>
      </nav>
    </aside>

    <main class="admin-conteudo">
      <header class="admin-cabecalho">
        <h1>Produtos</h1>
        <button type="button" id="botao-novo-produto" class="botao botao--primario">+ Novo produto</button>
      </header>

      <section class="admin-tabela-secao">
        <table class="admin-tabela">
          <thead>
            <tr>
              <th>ID</th><th>Nome</th><th>Descrição</th><th>Tipo</th><th>Preço</th><th>Ações</th>
            </tr>
          </thead>
          <tbody id="tabela-produtos">
            <?php foreach ($produtos as $p): ?>
              <tr data-produto-id="<?php echo $p['id']; ?>"
                  data-nome="<?php echo htmlspecialchars($p['nome']); ?>"
                  data-descricao="<?php echo htmlspecialchars($p['descricao']); ?>"
                  data-tipo="<?php echo $p['tipo']; ?>"
                  data-preco="<?php echo $p['preco']; ?>">
                <td><?php echo $p['id']; ?></td>
                <td><?php echo htmlspecialchars($p['nome']); ?></td>
                <td><?php echo htmlspecialchars($p['descricao']); ?></td>
                <td><?php echo ucfirst($p['tipo']); ?></td>
                <td>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>
                <td class="admin-tabela__acoes">
                  <button type="button" class="botao botao--secundario botao-editar-produto" data-id="<?php echo $p['id']; ?>">Editar</button>
                  <button type="button" class="botao botao--perigo botao-excluir-produto" data-id="<?php echo $p['id']; ?>">Excluir</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </section>

      
      <dialog id="modal-produto" class="modal-produto">
        <form id="form-produto" action="../actions/produtos_salvar.php" method="POST" class="formulario">
          <header class="modal-produto__cabecalho">
            <h2 id="titulo-modal-produto">Novo produto</h2>
            <button type="button" id="botao-fechar-modal-produto" aria-label="Fechar">&times;</button>
          </header>

          <input type="hidden" id="produto-id" name="id" value="">

          <div class="campo-formulario">
            <label for="produto-nome">Nome</label>
            <input type="text" id="produto-nome" name="nome" required>
          </div>

          <div class="campo-formulario">
            <label for="produto-descricao">Descrição</label>
            <textarea id="produto-descricao" name="descricao" rows="3"></textarea>
          </div>

          <div class="campo-formulario-linha">
            <div class="campo-formulario">
              <label for="produto-tipo">Tipo</label>
              <select id="produto-tipo" name="tipo" required>
                <option value="pizza">Pizza</option>
                <option value="bebida">Bebida</option>
              </select>
            </div>
            <div class="campo-formulario">
              <label for="produto-preco">Preço (R$)</label>
              <input type="number" id="produto-preco" name="preco" step="0.01" min="0" required>
            </div>
          </div>

          <button type="submit" class="botao botao--primario botao--bloco">Salvar produto</button>
        </form>
      </dialog>

    </main>
  </div>

  <div id="overlay-admin" class="overlay" hidden></div>

  <script src="../assets/js/admin-produtos.js" defer></script>
</body>
</html>
