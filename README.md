# 🎬 Jech Films

Sistema local de streaming multimedia tipo Netflix para organizar, reproducir y gestionar contenido audiovisual propio.

![PHP](https://img.shields.io/badge/PHP-8+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Video.js](https://img.shields.io/badge/Video.js-Player-green?style=for-the-badge)

---

## 🎯 Objetivo

Proporcionar una plataforma de streaming personal que permita:

- 🎬 Organizar películas y series propias
- ▶️ Reproducir contenido desde el navegador
- 👥 Gestionar usuarios y preferencias
- ⚙️ Administrar un catálogo completo
- 🔒 Proteger contenido sensible con Caja Fuerte

---

## ✨ Características Principales

### Interfaz de Usuario Premium

- **Diseño Glassmorphism**: Navbar con efecto blur y transparencias
- **Logo Premium**: Tipografía Bebas Neue con icono de play
- **Cards de Categorías**: Con iconos únicos, gradientes y efectos hover
- **Dropdowns Personalizados**: Sin selects nativos, con animaciones suaves
- **Tema Oscuro**: Gradientes sutiles y colores vibrantes

### Panel de Administración

- **Dashboard**: Estadísticas y accesos rápidos
- **Gestión de Usuarios**: Con iconos de acción y modal de confirmación
- **Gestión de Contenido**: Películas, series y categorías
- **Caja Fuerte**: Contenido privado protegido con código

### Funcionalidades de Usuario

- **Mi Lista**: Guardar contenido para ver después
- **Búsqueda Avanzada**: Con sugerencias y resultados organizados
- **Reacciones**: Sistema de likes en contenido
- **Perfil**: Avatar y configuración personal

---

## 🛠️ Tecnologías Utilizadas

| Componente    | Tecnología                       |
| ------------- | -------------------------------- |
| Backend       | PHP 8+ puro                      |
| Base de datos | MySQL 8+                         |
| Frontend      | HTML5, Tailwind CSS (CDN)        |
| Tipografía    | Inter, Bebas Neue (Google Fonts) |
| Reproductor   | Video.js                         |
| Arquitectura  | MVC simplificado                 |

---

## 📁 Estructura del Proyecto

```text
jech-films/
├── config/              # Configuración de BD y app
│   ├── app.php          # Variables de aplicación
│   └── database.php     # Conexión MySQL
├── controllers/         # Controladores MVC
│   ├── admin/           # Panel de administración
│   ├── AuthController.php
│   ├── HomeController.php
│   ├── MovieController.php
│   └── ...
├── database/            # Script SQL
│   └── jech_films.sql   # Estructura y datos iniciales
├── helpers/             # Funciones auxiliares
│   ├── auth.php         # Autenticación
│   ├── functions.php    # Utilidades generales
│   └── security.php     # Validación y seguridad
├── media/               # Videos (películas, series)
│   ├── movies/
│   └── series/
├── models/              # Modelos de datos
│   ├── User.php
│   ├── Movie.php
│   ├── Series.php
│   └── Category.php
├── public/              # Punto de entrada y assets
│   ├── index.php        # Router principal
│   ├── css/
│   ├── js/
│   └── uploads/         # Imágenes subidas
└── views/               # Vistas PHP
    ├── admin/           # Panel de admin
    ├── auth/            # Login y registro
    ├── components/      # Cards reutilizables
    ├── layouts/         # Layouts main y admin
    ├── home/            # Página de inicio
    ├── movies/          # Catálogo películas
    ├── series/          # Catálogo series
    ├── search/          # Búsqueda
    ├── vault/           # Caja fuerte
    └── list/            # Mi lista
```

---

## ⚙️ Instalación

### 1. Requisitos

- PHP 8.0 o superior
- MySQL 8.0 o superior
- Servidor web (Apache/Nginx) o PHP built-in server

### 2. Clonar o copiar el proyecto

```bash
# Copiar a tu servidor local
cp -r jech-films /var/www/html/

# o para XAMPP/WAMP
cp -r jech-films C:/xampp/htdocs/
```

### 3. Crear la base de datos

```bash
mysql -u root -p < database/jech_films.sql
```

### 4. Configurar conexión

Editar `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'jech_films');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 5. Configurar URL base

Editar `config/app.php`:

```php
define('BASE_URL', 'http://localhost:8000');
```

### 6. Iniciar servidor

```bash
cd jech-films/public
php -S localhost:8000

# Acceder a: http://localhost:8000
```

---

## 👤 Credenciales por Defecto

| Usuario | Email                   | Contraseña | Rol           |
| ------- | ----------------------- | ---------- | ------------- |
| admin   | <admin@jechfilms.local> | admin123   | Administrador |

**Código Caja Fuerte:** `1234`

---

## 🎬 Agregar Contenido

### Películas

1. Copiar el archivo de video a `media/movies/`
2. Ir a **Admin → Películas → Nueva Película**
3. Completar datos y escribir la ruta: `movies/nombre-archivo.mp4`

### Series

1. Copiar episodios a `media/series/nombre-serie/`
2. Crear la serie en **Admin → Series**
3. Agregar episodios con rutas: `series/nombre-serie/s01e01.mp4`

---

## 🔒 Seguridad

| Amenaza       | Protección                      |
| ------------- | ------------------------------- |
| SQL Injection | PDO con prepared statements     |
| XSS           | Escape con `htmlspecialchars()` |
| CSRF          | Tokens en formularios           |
| Contraseñas   | Hashing con bcrypt              |
| Fuerza bruta  | Rate limiting                   |

---

## 📱 Responsividad

El diseño es completamente responsive:

- 📱 Smartphones
- 📱 Tablets
- 💻 Laptops
- 🖥️ Monitores grandes
- 📺 Smart TVs (navegador)

---

## 🚀 Mejoras Futuras

- [ ] Continuar viendo (progreso de reproducción)
- [ ] Múltiples perfiles por cuenta
- [ ] Subtítulos y audio alternativo
- [ ] Recomendaciones personalizadas
- [ ] Integración con TMDB para metadatos
- [ ] Transcodificación automática de videos
- [ ] PWA para instalación en dispositivos
- [ ] Notificaciones de nuevo contenido

---

## 📝 Licencia

Proyecto personal para uso local. Libre de modificar y adaptar.

---

> _El mejor código no es el más complejo, sino el que otro desarrollador puede entender, mejorar y mantener sin miedo._
