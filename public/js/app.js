/**
 * Jech Films - JavaScript principal
 * Funcionalidades interactivas del frontend
 */

document.addEventListener("DOMContentLoaded", function () {
  // Toggle del menú de usuario
  const userMenu = document.getElementById("user-menu");
  const userDropdown = document.getElementById("user-dropdown");

  if (userMenu && userDropdown) {
    userMenu.addEventListener("click", function (e) {
      e.stopPropagation();
      userDropdown.classList.toggle("hidden");
    });

    document.addEventListener("click", function () {
      userDropdown.classList.add("hidden");
    });
  }

  // Modal de búsqueda
  const searchToggle = document.getElementById("search-toggle");
  const searchModal = document.getElementById("search-modal");
  const searchClose = document.getElementById("search-close");

  if (searchToggle && searchModal) {
    searchToggle.addEventListener("click", function () {
      searchModal.classList.remove("hidden");
      searchModal.querySelector("input")?.focus();
    });

    if (searchClose) {
      searchClose.addEventListener("click", function () {
        searchModal.classList.add("hidden");
      });
    }

    // Cerrar con Escape
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !searchModal.classList.contains("hidden")) {
        searchModal.classList.add("hidden");
      }
    });
  }

  // Lazy loading de imágenes
  const lazyImages = document.querySelectorAll('img[loading="lazy"]');

  if ("IntersectionObserver" in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.classList.add("animate-fade-in");
          observer.unobserve(img);
        }
      });
    });

    lazyImages.forEach((img) => imageObserver.observe(img));
  }

  // Auto-ocultar mensajes flash
  const flashMessage = document.getElementById("flash-message");
  if (flashMessage) {
    setTimeout(() => {
      flashMessage.style.opacity = "0";
      flashMessage.style.transform = "translateX(100%)";
      setTimeout(() => flashMessage.remove(), 300);
    }, 5000);
  }

  // Smooth scroll para anclas
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
      }
    });
  });
});

/**
 * Funciones globales para interacciones AJAX
 */

// Toggle de lista (Mi Lista)
async function toggleList(type, id) {
  try {
    const response = await fetch("/jech-films/public/api/list", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ type, id }),
    });

    const data = await response.json();

    if (data.error) {
      console.error(data.error);
      return;
    }

    // Recargar para actualizar UI
    location.reload();
  } catch (error) {
    console.error("Error al actualizar lista:", error);
  }
}

// Reacción (like/dislike)
async function react(type, id, reaction) {
  try {
    const response = await fetch("/jech-films/public/api/react", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ type, id, reaction }),
    });

    const data = await response.json();

    if (data.error) {
      console.error(data.error);
      return;
    }

    location.reload();
  } catch (error) {
    console.error("Error al registrar reacción:", error);
  }
}

// Confirmar eliminación
function confirmDelete(message = "¿Estás seguro de eliminar este elemento?") {
  return confirm(message);
}
