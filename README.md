# 📘 Find&Book

Aplicación web de reservas para negocios de servicios y clientes, desarrollada como Proyecto de Fin de Grado del ciclo **Desarrollo de Aplicaciones Web** (DAW).

## 📌 Descripción

**Find&Book** es una plataforma donde los clientes pueden buscar negocios según su categoría (peluquerías, estética, masajes, etc.), consultar sus servicios y reservar citas de forma rápida y sencilla. Las empresas pueden gestionar sus servicios, personalizar su perfil, y recibir reservas con control total de su disponibilidad. También se incluye un chat interno entre cliente y negocio para mejorar la comunicación.

## 🧑‍💼 Roles de usuario

**Cliente**
- Buscar negocios
- Reservar servicios
- Consultar historial

**Empresa**
- Publicar servicios
- Personalizar perfil (colores, imágenes, datos)
- Configurar disponibilidad
- Ver y gestionar citas recibidas

## 🖥️ Tecnologías utilizadas

**Frontend**
- Vue.js 3
- Vuetify 3 
- Axios

**Backend**
- Laravel (API RESTful)
- PostgreSQL

**Autenticación**
- Firebase Authentication

**Otras herramientas**
- Postman
- Git + GitHub
- Figma
- draw.io

## 🗺️ Navegación de la app

**Inicio**
- Búsqueda de negocios
- Acceso a login / registro

**Registro/Login**
- Registro cliente (acceso inmediato)
- Registro empresa (requiere validación por parte del administrador)

**Panel cliente**
- Reservas activas
- Historial
- Perfil
- Mensajes

**Panel empresa**
- Gestión de servicios
- Citas recibidas
- Personalización del negocio
- Calendario de disponibilidad
- Mensajes

## 🛠️ Estructura del proyecto

📁 frontend/        → Aplicación Vue.js  
📁 api/         → API Laravel  
📄 README.md        → Documentación del proyecto  

## 📄 Base de datos

Modelo relacional en PostgreSQL. Incluye:
- Usuarios y roles
- Negocios
- Servicios
- Citas
- Mensajes
- Vistas y procedimientos

Disponible en `/database/schema.sql`

🧪 Cómo ejecutar el proyecto
🚀 Frontend (Vue + Vuetify)
'''arduino
cd frontend
npm install
npm run dev

⚙️ Backend (Laravel)
'''bash
Copiar
Editar
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

🧱 Base de datos (PostgreSQL)
Asegúrate de tener PostgreSQL instalado y corriendo.

Crea una base de datos llamada findandbook.

Configura los datos de conexión en el archivo .env de Laravel:

'''ini
Copiar
Editar
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=findandbook
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña 

## 👨‍🎓 Autor

- José Pérez Lara  
- Proyecto de Fin de Grado – DAW  
- GitHub: [github.com/tunombre](https://github.com/tunombre)
