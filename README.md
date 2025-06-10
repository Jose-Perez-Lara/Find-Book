# 📘 Find&Book

Aplicación web de reservas para negocios de servicios y clientes, desarrollada como Proyecto de Fin de Grado del ciclo **Desarrollo de Aplicaciones Web**.

## 📌 Descripción

**Find&Book** es una plataforma donde los clientes pueden buscar negocios según su categoría (peluquerías, estética, masajes, etc.), consultar sus servicios y reservar citas de forma rápida y sencilla. Las empresas pueden gestionar sus servicios, personalizar su perfil, y recibir reservas con control total de su disponibilidad.

---

## 🧑‍💼 Roles de usuario

- **Cliente**
  - Buscar negocios
  - Reservar servicios
  - Consultar historial
  - Marcar negocios como favoritos

- **Empresa**
  - Publicar servicios
  - Personalizar su negocio
  - Configurar disponibilidad
  - Ver y gestionar citas recibidas

---

## 🖥️ Tecnologías utilizadas

### 🔧 Frontend
- [Vue.js 3](https://vuejs.org/)
- [Vuetify 3](https://vuetifyjs.com/) (tema claro)
- [Axios](https://axios-http.com/) (consumo de API REST)

### ⚙️ Backend
- [Laravel](https://laravel.com/) (API RESTful)
- [PostgreSQL](https://www.postgresql.org/) (base de datos relacional)

### 🔐 Autenticación
- [Firebase Authentication](https://firebase.google.com/products/auth)

### 📦 Otras herramientas
- Postman (para testeo de endpoints)
- Git + GitHub (control de versiones)
- Figma (diseño del prototipo)
- draw.io (modelo E-R)

---

## 🗺️ Navegación de la app

- **Inicio**
  - Búsqueda de negocios
  - Acceso a login / registro

- **Registro/Login**
  - Registro cliente (acceso inmediato)
  - Registro empresa (requiere validación manual)

- **Panel cliente**
  - Historial de reservas
  - Negocios favoritos
  - Perfil

- **Panel empresa**
  - Gestión de servicios
  - Citas recibidas
  - Personalización del negocio
  - Calendario de disponibilidad

---

## 🛠️ Estructura del proyecto

```bash
📁 frontend/        # Aplicación Vue.js
📁 backend/         # API Laravel
📄 README.md        # Documentación principal del proyecto
```

## 📄 Base de datos

Modelo relacional en PostgreSQL. Incluye:
- Usuarios
- Negocios
- Servicios
- Horarios
- Citas
- Comentarios
- Favoritos


## 🧪 Cómo ejecutar el proyecto

### 🚀 Frontend (Vue + Vuetify)

```bash
cd frontend
npm install
npm run dev
```

La app quedará disponible en `http://localhost:3000`.

---

### ⚙️ Backend (Laravel)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

El backend arrancará en `http://localhost:8000`.

---

### 🧱 Base de datos (PostgreSQL)

Asegúrate de tener PostgreSQL instalado y corriendo.  
Crea una base de datos llamada `findandbook`.  
Luego, configura los datos de conexión en el archivo `.env` de Laravel:

```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=findandbook
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### Firebase
Se debe de tener una cuenta en [firebase.google.com](https://firebase.google.com/),así como tambien ambos archivos de configuración:
- El del backend, debe de estar situado en -> 📁 api/storage/app/private,
- El del frontend, debe de estar situado en -> 📁 frontend/src

- Luego, configura los datos de conexión en el archivo `.env` de Laravel:

```ini
FIREBASE_CREDENTIALS=/ruta-completa-al-archivo
```

## 👨‍🎓 Autor

- José Pérez Lara  
- Proyecto de Fin de Grado – DAW  
- GitHub: [github.com/Jose-Perez-Lara](https://github.com/Jose-Perez-Lara)
