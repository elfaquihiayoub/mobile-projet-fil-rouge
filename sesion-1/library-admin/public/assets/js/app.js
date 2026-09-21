const sidebar = document.querySelector('#sidebar');
document.querySelector('#menuToggle')?.addEventListener('click', () => sidebar?.classList.toggle('open'));

const modal = document.querySelector('#confirmModal');
let pendingForm = null;
document.querySelectorAll('[data-delete-form]').forEach((form) => {
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    pendingForm = form;
    modal?.classList.add('open');
  });
});
document.querySelector('[data-close-modal]')?.addEventListener('click', () => modal?.classList.remove('open'));
document.querySelector('#confirmDelete')?.addEventListener('click', () => pendingForm?.submit());
