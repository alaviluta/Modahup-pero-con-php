# 🛒 Catálogo de Tiendas de Ropa Deportiva y Urbana (Unidad 4 - PHP & SSR)

Aplicación web modularizada desarrollada para la gestión y visualización de catálogos de indumentaria urbana y calzado deportivo de marcas líderes (Nike, Adidas, Zara). Este proyecto corresponde a la evolución del hito estático a un entorno de servidor dinámico con PHP, Server Side Includes (SSI) y Renderizado Server-Side (SSR).

---

## 🎨 Enlace al Prototipo (Figma)
* **Diseño original UI/UX:** [Ver prototipo en Figma](https://www.figma.com/design/yhwdbeoqApCoIEwYfxAJXX/Sin-t%C3%ADtulo?node-id=3-17&t=XJZbYbaPcR1jmXXs-1)

---

## 🛠️ Tecnologías Utilizadas
* **Lenguajes:** PHP 8.x, HTML5, CSS3, JavaScript (ES6+).
* **Entorno de Servidor Local:** XAMPP (Apache).
* **Control de Versiones:** Git y GitHub (con flujo de ramas y commits atómicos).
* **Arquitectura:** Modularización mediante plantillas parciales (SSI).

---

## 🚀 Instrucciones de Instalación y Ejecución Local

Para clonar y poner en marcha este proyecto en tu entorno local:

1. Clonar el repositorio o descargar el código fuente dentro de la carpeta raíz de tu servidor local (`C:\xampp\htdocs\ModaHup`).
2. Duplicar el archivo `.env.example` y renombrarlo como `env.php` (o `.env` según configuración local).
3. Iniciar el servicio **Apache** desde el Panel de Control de XAMPP.
4. Abrir el navegador web e ingresar a la URL:
   `http://localhost/ModaHup/index.php`

---

## 📸 Evidencia de Funcionamiento
### 1. Servidor Local Ejecutándose
*Vista previa del sitio web corriendo de forma local en el navegador bajo el entorno de XAMPP:*
![Servidor Local](imagenes/captura.png)
### 2. Estructura Modular (SSI)
*Vista del código fuente y organización de carpetas donde se aprecia la separación en plantillas (`header.php`, `footer.php`) y su inclusión mediante PHP:*
![Estructura Modular SSI](imagenes/ssi.png)

### 3. Navegación Dinámica y SSR
*Demostración del título dinámico en la pestaña del navegador combinando las variables del entorno con cada vista:*
![Navegación Dinámica SSR](imagenes/nave.png)

### 4. Configuración de Variables de Entorno
*Evidencia del archivo `.env.example` presente en la raíz del repositorio:*
![Variables de Entorno](imagenes/env.png)
