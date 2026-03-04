# LÉAME — Sistema Escolar Inteligente Seguro y Escalable

Este documento explica **paso a paso** cómo instalar, configurar, ejecutar y validar el sistema.

---

## 1) Requisitos previos

Antes de instalar, verifica que tengas:

- PHP 8.1+ (recomendado 8.2 o superior)
- Extensiones PHP: `pdo`, `pdo_mysql`, `mbstring`, `json`
- MySQL 8+
- Servidor web local (para desarrollo puedes usar el servidor embebido de PHP)
- Git (opcional, para clonar)

### Comandos de verificación

```bash
php -v
php -m | grep -E "pdo|pdo_mysql|mbstring|json"
mysql --version
git --version
```

---

## 2) Obtener el proyecto

Si ya tienes el código, salta al paso 3. Si no:

```bash
git clone <URL_DEL_REPOSITORIO>
cd sistema_escolar
```

---

## 3) Configurar base de datos

### 3.1 Crear/importar esquema

Desde la raíz del proyecto:

```bash
mysql -u root -p < database/schema.sql
```

Esto crea:

- Base de datos `sistema_escolar`
- Tablas de usuarios, personas institucionales (DNI), chat, asistencias, auditoría, etc.
- Datos semilla (incluye ejemplo de `Maximiliano Juarez` con DNI `12345678`)

### 3.2 Validar que se creó correctamente

```bash
mysql -u root -p -e "USE sistema_escolar; SHOW TABLES;"
```

---

## 4) Configurar conexión (si necesitas cambiar credenciales)

Edita `config/database.php` y ajusta:

- `host`
- `port`
- `database`
- `username`
- `password`

También puedes definir variables de entorno:

- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

---

## 5) Levantar el sistema

Desde la raíz del proyecto:

```bash
php -S 0.0.0.0:8000 -t public
```

Abrir en navegador:

- `http://localhost:8000/login`

---

## 6) Flujo de registro (paso a paso)

1. Ir a `http://localhost:8000/registro`
2. Ingresar:
   - DNI (8 dígitos)
   - Correo
   - Contraseña
   - CAPTCHA
3. El sistema valida el DNI en `personas_institucionales`.
4. Si existe, crea la cuenta con rol automático según padrón.
5. Envía correo de verificación.
6. Verificar cuenta con el enlace recibido.
7. Iniciar sesión en `/login`.
8. Ingresar código 2FA recibido por correo.

---

## 7) Documentación funcional (paso a paso)

## 7.1 Autenticación y seguridad

- Login con correo + contraseña + CAPTCHA
- Segundo factor 2FA por correo
- CSRF en formularios
- Rate limiting para mitigar abuso
- Auditoría en tabla `audit_logs`

## 7.2 Roles y acceso

Roles institucionales soportados:

- director
- subdirector
- secretaria
- auxiliar
- docente
- estudiante
- padre

El rol se asigna automáticamente por DNI en el registro.

## 7.3 Chat privado por permisos

- Contactos agrupados por rol
- Restricciones por reglas institucionales (docente/estudiante/padre/etc.)
- Filtro de estudiantes por sección para docentes
- Sonido cuando llegan mensajes nuevos

## 7.4 Asistencias

- Asistencia de estudiantes (docente)
- Asistencia de personal (docente/subdirector/secretaria/auxiliar/director)
- Vista de asistencia del estudiante en solo lectura
- Mensaje por voz al marcar asistencia de personal

## 7.5 Reportes

- Exportación CSV compatible con Excel
- Opción de imprimir como PDF desde navegador

---

## 8) Comandos útiles para soporte

### Validar sintaxis PHP

```bash
find app config public -name '*.php' -print0 | xargs -0 -n1 php -l
```

### Revisar errores de correo fallback

Si el servidor no tiene SMTP, los correos se guardan en:

- `storage/logs/correos.log`

Comando:

```bash
tail -f storage/logs/correos.log
```

---


## Configuración específica para WAMP (Windows)

Si lo copiaste en `C:\wamp64\www\sistema_escolar` y te aparece **Not Found**, sigue esto exactamente:

1. Activa módulos Apache en WAMP:
   - `rewrite_module`
   - `headers_module`

2. Reinicia todos los servicios de WAMP.

3. Entra por URL correcta:
   - Opción A (con reescritura del proyecto): `http://localhost/sistema_escolar/`
   - Opción B (directa al front): `http://localhost/sistema_escolar/public/`

4. Verifica que exista `.htaccess` en:
   - Raíz del proyecto (`sistema_escolar/.htaccess`)
   - Carpeta pública (`sistema_escolar/public/.htaccess`)

5. Si usas VirtualHost, apunta el `DocumentRoot` a `.../sistema_escolar/public`.

Ejemplo de VirtualHost:

```apache
<VirtualHost *:80>
    ServerName sistema-escolar.local
    DocumentRoot "c:/wamp64/www/sistema_escolar/public"
    <Directory "c:/wamp64/www/sistema_escolar/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Luego agrega en `C:\Windows\System32\drivers\etc\hosts`:

```txt
127.0.0.1   sistema-escolar.local
```

Y reinicia WAMP.

## 9) Solución de problemas

### Error de conexión a MySQL

- Verifica usuario/clave en `config/database.php`
- Confirma que MySQL esté encendido

### No llegan correos

- Configura SMTP en tu entorno, o
- Revisa `storage/logs/correos.log` para los mensajes simulados

### No puedo registrarme

- Verifica que el DNI exista en `personas_institucionales`
- Revisa que el correo no esté ya registrado

### 2FA inválido

- El código expira (10 minutos)
- Vuelve a iniciar sesión para generar uno nuevo

---

## 10) Recomendaciones para producción

- Configurar HTTPS obligatorio
- Usar SMTP transaccional (SES, Mailgun, etc.)
- Mover rate limiting a Redis/WAF (en vez de sesión)
- Activar monitoreo y alertas sobre `audit_logs`
- Ejecutar pruebas de seguridad periódicas (SAST/DAST/Pentest)

---

## 11) Resumen rápido

Instalación mínima:

```bash
mysql -u root -p < database/schema.sql
php -S 0.0.0.0:8000 -t public
```

Luego abrir:

- `http://localhost:8000/login`
