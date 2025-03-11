// js/script.js

document.addEventListener('DOMContentLoaded', () => {
    const formRegister = document.querySelector('#formRegister');
    if(formRegister) {
      formRegister.addEventListener('submit', (e) => {
        // Exemplo de validação rápida
        const senha = document.querySelector('#senha').value;
        if(senha.length < 6) {
          alert("A senha deve ter pelo menos 6 caracteres!");
          e.preventDefault();
        }
      });
    }
  });
  