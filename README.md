# Login System en PHP con Bootstrap

Proyecto final de autenticación en PHP, desarrollado con XAMPP y estilizado con Bootstrap 5.  
Incluye registro de usuarios, inicio de sesión, dashboard protegido y cierre de sesión.

---

## 🚀 Características
- Registro de usuarios con validación de datos
- Hash seguro de contraseñas (`password_hash`)
- Verificación de contraseñas (`password_verify`)
- Manejo de sesiones en PHP (`session_start`, `session_regenerate_id`)
- Dashboard protegido (solo accesible con sesión activa)
- Logout que destruye la sesión
- Estilos modernos con Bootstrap 5

---

### 📊 Script SQL para crear la base de datos y tabla

Ejecuta este código en phpMyAdmin para preparar la base de datos:

```sql
CREATE DATABASE foro_b1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE foro_b1;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(190) NOT NULL UNIQUE,
  contrasena_hash VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
´´´

---



## 🛠️ Tecnologías
- PHP 8
- MariaDB/MySQL
- Bootstrap 5
- XAMPP

---

## ⚙️ Instalación y configuración

### 1. Clonar el repositorio
```bash
git clone https://github.com/VinicioMarquez/login-system.git
