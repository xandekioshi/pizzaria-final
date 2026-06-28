// GRANDE PARTE DO JAVA FOI REVISADO E COMPLEMENTADO PELO CLAUDE OPUS 4.8
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('modal-ia');
  const abrir = document.getElementById('botao-abrir-modal-ia');
  const fechar = document.getElementById('botao-fechar-modal-ia');
  const gerar = document.getElementById('botao-gerar-pizza-ia');
  const mensagemErro = document.getElementById('mensagem-erro-ia');
  const resultado = document.getElementById('resultado-pizza-ia');
  const botaoAdicionar = document.getElementById('botao-adicionar-pizza-ia');

 
  abrir.addEventListener('click', () => modal.showModal());
  fechar.addEventListener('click', () => modal.close());


  gerar.addEventListener('click', async () => {
    mensagemErro.hidden = true;

   
    const marcados = [...document.querySelectorAll('input[name="ingrediente"]:checked')];
    if (marcados.length !== 3) {
      mensagemErro.textContent = 'Selecione exatamente 3 ingredientes.';
      mensagemErro.hidden = false;
      return;
    }

    
    const dados = new URLSearchParams();
    marcados.forEach((c) => dados.append('ingredientes[]', c.value));

    try {
      const resposta = await fetch('actions/gerar_pizza_ia.php', {
        method: 'POST',
        body: dados,
      });
      const pizza = await resposta.json();

      if (pizza.erro) {
        mensagemErro.textContent = pizza.erro;
        mensagemErro.hidden = false;
        return;
      }

     
      document.getElementById('nome-pizza-ia').textContent = pizza.nome;
      document.getElementById('descricao-pizza-ia').textContent = pizza.descricao;
      document.getElementById('preco-pizza-ia').textContent =
        'R$ ' + parseFloat(pizza.preco).toFixed(2).replace('.', ',');

     
      botaoAdicionar.dataset.id = pizza.id;
      botaoAdicionar.dataset.nome = pizza.nome;
      botaoAdicionar.dataset.preco = pizza.preco;

      resultado.hidden = false;
    } catch (erro) {
      mensagemErro.textContent = 'Erro ao gerar a pizza. Tente novamente.';
      mensagemErro.hidden = false;
    }
  });

  
  botaoAdicionar.addEventListener('click', () => {
    adicionarItem({
      id: parseInt(botaoAdicionar.dataset.id, 10),
      nome: botaoAdicionar.dataset.nome,
      preco: parseFloat(botaoAdicionar.dataset.preco),
      tipo: 'pizza',
    });
    modal.close();
  });
});
