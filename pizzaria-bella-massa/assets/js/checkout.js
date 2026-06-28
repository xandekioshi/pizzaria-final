// GRANDE PARTE DO JAVA FOI REVISADO E COMPLEMENTADO PELO CLAUDE OPUS 4.8

const CHAVE_CARRINHO = 'carrinho';

function lerCarrinho() {
  return JSON.parse(localStorage.getItem(CHAVE_CARRINHO)) || [];
}

function formatarReais(valor) {
  return 'R$ ' + valor.toFixed(2).replace('.', ',');
}

let subtotalAtual = 0;
let freteAtual = 0;


function montarResumo() {
  const carrinho = lerCarrinho();
  const lista = document.getElementById('resumo-itens-pedido');
  lista.innerHTML = '';
  subtotalAtual = 0;

  carrinho.forEach((item) => {
    const subtotalItem = item.preco * item.quantidade;
    subtotalAtual += subtotalItem;

    const li = document.createElement('li');
    li.className = 'item-carrinho';
    li.innerHTML = `
      <span class="item-carrinho__nome">${item.nome}</span>
      <span class="item-carrinho__quantidade-valor">${item.quantidade}x</span>
      <span class="item-carrinho__subtotal">${formatarReais(subtotalItem)}</span>
    `;
    lista.appendChild(li);
  });

  document.getElementById('resumo-subtotal').textContent = formatarReais(subtotalAtual);
  atualizarTotal();
}


function atualizarTotal() {
  const total = subtotalAtual + freteAtual;
  document.getElementById('resumo-total-geral').textContent = formatarReais(total);


  document.getElementById('frete-valor-oculto').value = freteAtual.toFixed(2);
  document.getElementById('total-valor-oculto').value = total.toFixed(2);
}


async function calcularFrete() {
  const cep = document.getElementById('cep').value;
  const estado = document.getElementById('estado').value;

  if (cep.replace(/\D/g, '').length !== 8) {
    alert('Preencha o CEP antes de calcular o frete.');
    return;
  }

  const dados = new URLSearchParams();
  dados.append('cep', cep);
  dados.append('estado', estado);

  try {
    const resposta = await fetch('actions/calcular_frete.php', {
      method: 'POST',
      body: dados,
    });
    const json = await resposta.json();

    if (json.frete !== undefined) {
      freteAtual = parseFloat(json.frete);
      document.getElementById('valor-frete').textContent = formatarReais(freteAtual);
      atualizarTotal();
    } else {
      alert('Não foi possível calcular o frete.');
    }
  } catch (erro) {
    alert('Erro ao calcular o frete.');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  montarResumo();

  document.getElementById('botao-calcular-frete').addEventListener('click', calcularFrete);

  
  document.getElementById('form-finalizar-pedido').addEventListener('submit', (e) => {
    const carrinho = lerCarrinho();

    if (carrinho.length === 0) {
      e.preventDefault();
      alert('Seu carrinho está vazio.');
      return;
    }
    if (freteAtual === 0) {
      e.preventDefault();
      alert('Calcule o frete antes de confirmar o pedido.');
      return;
    }

   
    const itensSimples = carrinho.map((item) => ({
      id: item.id,
      quantidade: item.quantidade,
    }));
    document.getElementById('carrinho-json').value = JSON.stringify(itensSimples);

   
    document.getElementById('end-cep').value = document.getElementById('cep').value;
    document.getElementById('end-rua').value = document.getElementById('rua').value;
    document.getElementById('end-numero').value = document.getElementById('numero').value;
    document.getElementById('end-complemento').value = document.getElementById('complemento').value;
    document.getElementById('end-bairro').value = document.getElementById('bairro').value;
    document.getElementById('end-cidade').value = document.getElementById('cidade').value;
    document.getElementById('end-estado').value = document.getElementById('estado').value;

   
  });
});
