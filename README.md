# Sistema Escolar Inteligente Seguro y Escalable

Plataforma en **PHP + MySQL + Bootstrap** con arquitectura MVC, RBAC en español, seguridad reforzada y base empresarial.

## Implementado en esta versión

- RBAC por roles: director, subdirector, secretaria, auxiliar, docente, estudiante y padre.
- Registro **solo por DNI existente** en `personas_institucionales`.
- Al registrarse, el rol se asigna automáticamente desde padrón institucional por DNI.
- Verificación de cuenta por correo antes del primer acceso.
- Login con **CAPTCHA alfanumérico** (con botón de lectura por voz).
- Registro con **CAPTCHA alfanumérico** (con botón de lectura por voz).
- Login con **2FA por correo** (código de 6 dígitos).
- Rate limiting para intentos de login/registro.
- Auditoría de eventos de seguridad (registro, login fallido/exitoso, 2FA, cambio de contraseña).
- Chat privado por permisos y contactos agrupados por rol.
- Asistencia separada: estudiantes y personal.
- Reportes CSV compatibles con Excel (imprimibles a PDF desde navegador).

## Flujo de registro por DNI

1. Usuario ingresa DNI + correo + contraseña + CAPTCHA.
2. El sistema valida que el DNI exista en `personas_institucionales`.
3. Si existe, crea la cuenta con el rol institucional asignado al DNI.
4. Envía correo de verificación.
5. Al verificar, puede iniciar sesión.

## Seguridad aplicada

- Cabeceras de hardening + CSP.
- CSRF en formularios.
- Consultas preparadas PDO.
- Sesiones regeneradas.
- 2FA por correo.
- Rate limiting.
- Auditoría en BD.

## Instalación rápida

```bash
mysql -u root -p < database/schema.sql
php -S 0.0.0.0:8000 -t public
```

Abrir: `http://localhost:8000/login`


## Documentación completa

Para instalación y documentación detallada paso a paso, revisa **`LEAME.md`**.


> Si usas WAMP y te sale **Not Found**, revisa la sección **Configuración específica para WAMP (Windows)** en `LEAME.md`.
