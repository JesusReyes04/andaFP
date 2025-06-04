# AndaFP

**AndaFP** es una plataforma web desarrollada para conectar a estudiantes de Formación Profesional (FP) con empresas que ofrecen prácticas en **Andalucía**. El proyecto tiene como objetivo facilitar la búsqueda, gestión y aplicación a ofertas de prácticas de forma eficiente y accesible para ambas partes.

---

## Características principales

### 👨‍🎓 Estudiantes
- Registro con validación y control de errores.
- Búsqueda avanzada de ofertas por provincia, título formativo, y más.
- Aplicación a ofertas directamente desde la plataforma.
- Panel de usuario con control de solicitudes realizadas.

### Empresas
- Registro con datos fiscales, sector y perfil empresarial.
- Publicación de ofertas con validación y gestión de errores.
- Edición de ofertas ya publicadas.
- Visualización de estudiantes que han aplicado a sus ofertas.
- Panel de control completo para gestionar publicaciones y postulantes.

---

## Tecnologías utilizadas

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Base de datos**: MySQL
- **Otros**:
  - Manejo de sesiones para errores y estados (`$_SESSION`)
  - Validaciones en frontend y backend
  - Diseño responsive y profesional

---

## Estructura de la base de datos

La base de datos `andafp` contiene las siguientes tablas clave:

- `students`: Información personal y académica de los estudiantes.
- `companies`: Información fiscal, de contacto y sector de las empresas.
- `offers`: Ofertas publicadas por las empresas.
- `applications`: Registro de las solicitudes realizadas por los estudiantes.

---
## Contribuciones
Este proyecto está siendo desarrollado como parte de un módulo de FP. No se aceptan contribuciones externas por el momento, pero puedes hacer sugerencias o reportar errores mediante issues.