// Script para debug - definir usuário no localStorage
// Execute este código no console do browser (F12)

// Definir usuário administrador
localStorage.setItem('chatUser', JSON.stringify({
  id: 1,
  nome: 'administrador',
  email: 'administrador@tci.com.br'
}));

console.log('Usuário definido:', JSON.parse(localStorage.getItem('chatUser')));

// Recarregar a página
window.location.reload();

