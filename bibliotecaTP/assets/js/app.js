document.addEventListener('DOMContentLoaded', function () {
    // --- Variables DOM ---
    const addBookForm = document.getElementById('addBookForm');
    const toggleAddBtn = document.getElementById('toggleAddBtn'); // botón para mostrar/ocultar (si existe)
    const addSection = document.getElementById('addSection'); // contenedor del formulario (si existe)
    const searchInput = document.getElementById('searchBooks'); // input de búsqueda (si existe)
    const booksTable = document.getElementById('booksTable'); // tbody o table para filtrar (si existe)
    const themeToggle = document.getElementById('themeToggle'); // botón toggle-theme
  
    // --- Validación del formulario ---
    if (addBookForm) {
      addBookForm.addEventListener('submit', function (e) {
        const title = document.getElementById('title').value.trim();
        const author = document.getElementById('author').value.trim();
        const year = document.getElementById('year').value.trim();
  
        const errors = [];
        if (!title) errors.push('El título es obligatorio.');
        if (!author) errors.push('El autor es obligatorio.');
        if (!year || isNaN(year)) errors.push('El año es obligatorio y debe ser numérico.');
  
        if (errors.length) {
          e.preventDefault();
          // Mostrar errores
          alert(errors.join('\\n'));
        }
      });
    }
  
  });
  