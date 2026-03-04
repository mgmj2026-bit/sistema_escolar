# Sistema Escolar Inteligente Seguro y Escalable

Plataforma en **PHP + MySQL + Bootstrap** con arquitectura MVC, roles en español, seguridad reforzada y base para crecimiento empresarial.

## Mejoras implementadas

- RBAC por roles: director, subdirector, secretaria, auxiliar, docente, estudiante y padre.
- Registro con **verificación por correo** (token) antes de permitir login.
- Notificación por correo al verificar cuenta y al cambiar contraseña.
- Chat privado con reglas de permiso por rol y contactos agrupados por roles.
- Filtro de chat docente -> estudiantes por secciones que sí enseña.
- Asistencia de estudiantes (docente) separada de asistencia de personal.
- Estudiante solo visualiza su asistencia (solo lectura).
- Reportes exportables CSV (compatibles con Excel) y opción de impresión a PDF.

## Reglas de chat aplicadas

- Docente: estudiantes de sus secciones, docentes, director, secretaria y auxiliar (no padres).
- Estudiante: docentes de sus cursos, auxiliar y subdirector.
- Padre: subdirector, auxiliar, secretaria y docentes que enseñan a su hijo.
- Director/Subdirector/Secretaria/Auxiliar: acceso amplio para comunicación operativa.

## Seguridad aplicada

- Cabeceras de hardening y CSP.
- Protección CSRF.
- Consultas preparadas PDO.
- Control de acceso por rol.
- Sesiones regeneradas en login.

## Instalación rápida

```bash
mysql -u root -p < database/schema.sql
php -S 0.0.0.0:8000 -t public
```

Abrir: `http://localhost:8000/login`
