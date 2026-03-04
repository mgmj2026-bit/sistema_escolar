# Sistema Escolar Inteligente (PHP + MySQL + Bootstrap)

Base robusta y modular para un sistema escolar empresarial con enfoque en seguridad por defecto.

## Módulos incluidos

- Estudiantes
- Padres
- Docentes
- Administradores
- Personal de servicios
- Finanzas
- Reportes
- Matrículas
- Años académicos
- Grados
- Secciones
- Aula virtual
- Biblioteca
- Asignaciones
- Salud y bienestar
- Transporte
- Inventario
- Compliance
- Chat súper en vivo con alerta sonora por nuevo mensaje

## Arquitectura

- **MVC ligero** con separación de `Controllers`, `Models`, `Views`.
- **Router central** para endpoints HTTP.
- **Hardening básico** con headers de seguridad y CSP.
- **Autenticación con sesión regenerada** y verificación de contraseña con hash seguro.
- **Protección CSRF** en formularios críticos.
- **Consultas preparadas con PDO** contra inyección SQL.

## Instalación rápida

1. Crear base de datos e importar el esquema:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
2. Configurar variables de entorno del servidor web:
   - `DB_HOST`
   - `DB_PORT`
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`
3. Levantar servidor local:
   ```bash
   php -S 0.0.0.0:8000 -t public
   ```
4. Abrir `http://localhost:8000/login`

### Credenciales demo
- Correo: `admin@school.local`
- Clave: `Admin123*`

## Nota de seguridad realista

No existe software con **“cero vulnerabilidades” garantizadas**. Este proyecto aplica buenas prácticas iniciales, pero para producción se recomienda además:

- MFA y políticas de contraseñas avanzadas.
- Rate limiting y protección anti-bot/WAF.
- Auditorías de seguridad periódicas (SAST/DAST/Pentest).
- Cifrado de datos sensibles en reposo.
- Backups inmutables + plan de continuidad.
- SIEM/observabilidad y alertamiento centralizado.
