# Sistema de Gestión Odontológica — Clínica Muelitas

## Documento comercial de presentación (completo)

**Versión:** 2.1  
**Producto:** Plataforma web segura de administración de citas odontológicas  
**Cliente objetivo:** Clínicas dentales, consultorios odontológicos y centros de salud oral  

---

## 1. Resumen ejecutivo

**Clínica Muelitas** es un sistema web para digitalizar la gestión de citas en una clínica odontológica: registro de pacientes, asignación de consultas (médico, fecha, hora, consultorio), consulta y cancelación de citas, y generación de comprobantes en PDF.

Está pensado para equipos pequeños y medianos que hoy usan papel, Excel o procesos dispersos. La interfaz es clara, con identidad visual propia (logo de muela, verde azulado y acento melocotón) y flujos guiados para la recepción.

**Seguridad integrada:** solo personal autorizado accede a los datos. Si es la **primera vez** que se instala el sistema y **no existe ningún usuario**, no hace falta crear la cuenta a mano en la base de datos: la propia pantalla de acceso muestra un **asistente de configuración inicial** para crear la cuenta de administrador en un solo paso.

---

## 2. Primera vez: ¿qué ve el usuario si no tiene cuenta?

Esta es la duda más frecuente al instalar. El comportamiento es el siguiente:

| Situación | Qué aparece en pantalla |
|-----------|-------------------------|
| **No hay usuarios** en la base de datos (instalación nueva) | Formulario **«Crear cuenta administrador»** (nombre, usuario, contraseña, confirmación). Al enviarlo, entra directo al sistema. |
| **Ya existe al menos un usuario** | Formulario normal **«Iniciar sesión»** con usuario y contraseña. |
| **Error de conexión** a MySQL | Mensaje indicando revisar `config/config.php` y que el servicio MySQL esté activo. |

**No es obligatorio** ejecutar scripts SQL para tener un usuario: el sistema crea la tabla `usuarios` automáticamente si no existe. El archivo `sql/seguridad_usuarios.sql` quedó como **opcional** (por ejemplo, para precargar un usuario en entornos de demostración).

### Pasos para quien instala por primera vez

1. Copiar el proyecto a Apache (ej. `htdocs/clinica_muelitas`).
2. Crear la base de datos `clinica_muelitas` e importar el SQL principal de pacientes, médicos y citas (el que ya traía el proyecto).
3. Copiar `config/config.example.php` → `config/config.php` y poner host, usuario y contraseña de MySQL.
4. Abrir en el navegador: `http://localhost/clinica_muelitas/`
5. Si no hay usuarios, completar **Crear cuenta administrador** y guardar las credenciales en un lugar seguro.
6. A partir de ahí, siempre usar **Iniciar sesión** con ese usuario.

### Usuario de demostración (solo si se usa el SQL opcional)

Si en phpMyAdmin ejecuta el `INSERT` comentado dentro de `sql/seguridad_usuarios.sql`:

| Campo | Valor |
|-------|--------|
| Usuario | `admin` |
| Contraseña | `AdminMuelitas2026` |

En ese caso verá directamente la pantalla de login, no el asistente de creación.

---

## 3. El problema que resolvemos

| Sin sistema | Con Clínica Muelitas |
|-------------|----------------------|
| Doble reserva de horarios | Horas disponibles en tiempo real |
| Pacientes sin registro central | Alta desde el flujo de cita |
| Pérdida de historial | Consulta por documento |
| Cancelaciones sin control | Estado actualizado en base de datos |
| Comprobantes a mano | PDF con datos de la cita |
| Cualquiera con el enlace ve datos | Acceso con login y sesión protegida |
| No sé cómo crear el usuario del sistema | Asistente automático la primera vez |

---

## 4. Módulos y funcionalidades

### 4.1 Acceso y configuración inicial

- Pantalla de ingreso para personal autorizado.
- **Asistente de primera cuenta** cuando la tabla de usuarios está vacía.
- Contraseñas guardadas con hash **bcrypt** (`password_hash` / `password_verify`).
- Bloqueo temporal tras 5 intentos fallidos de login (15 minutos).
- Cierre de sesión desde el encabezado.
- Sesión que expira tras 1 hora de inactividad (configurable en `config/config.php`).

### 4.2 Panel de inicio

- Bienvenida y accesos rápidos a Asignar, Consultar y Cancelar.
- Menú lateral con sección activa resaltada.
- Logo de muela y marca Clínica Muelitas.

### 4.3 Asignación de citas

1. Consulta de paciente por documento.  
2. Si no existe, registro en ventana modal (nombres, apellidos, nacimiento, sexo).  
3. Elección de médico, fecha (desde hoy), hora disponible y consultorio.  
4. Confirmación con resumen y enlace a PDF.

Validaciones: campos obligatorios, fechas coherentes, documento y nombres validados en servidor, token CSRF en envíos.

### 4.4 Consulta de citas

- Búsqueda por documento.  
- Listado de citas activas con enlace al detalle.

### 4.5 Cancelación de citas

- Búsqueda por documento.  
- Cancelación con confirmación; estado *cancelada* en base de datos.

### 4.6 Detalle y reporte PDF

- Datos de paciente, médico, cita y consultorio.  
- PDF descargable para el paciente o archivo interno.

---

## 5. Seguridad y protección de datos sensibles

| Medida | Descripción |
|--------|-------------|
| Autenticación | Solo usuarios en tabla `usuarios` |
| Primera cuenta | Solo se puede crear si no hay usuarios activos |
| Contraseñas | Bcrypt; mínimo 8 caracteres en alta inicial |
| Sesión | Cookie HttpOnly, SameSite Strict, regeneración de ID al entrar |
| Timeout | Cierre por inactividad |
| Anti fuerza bruta | 5 intentos → bloqueo 15 min |
| CSRF | Token en formularios y AJAX que modifican datos |
| SQL | Consultas preparadas |
| XSS | Escape HTML en vistas |
| Cabeceras HTTP | X-Frame-Options, CSP básica, nosniff, etc. |
| Validación | Documento, nombres, fechas, horas en servidor |
| Configuración | Carpeta `config/` bloqueada con `.htaccess` |
| POST sensible | Alta de paciente y cancelación solo por POST + CSRF |

**Recomendaciones en producción:** HTTPS, contraseña fuerte, no exponer phpMyAdmin, respaldos periódicos de MySQL.

---

## 6. Beneficios para la clínica

**Operativos:** menos tiempo en recepción, menos choques de horario, un solo lugar para la información.

**Administrativos:** base única de pacientes y citas, estados trazables, PDF por cita.

**Confianza:** datos de pacientes no visibles sin login; primera instalación guiada sin depender de scripts ocultos.

**Imagen:** interfaz moderna y marca consistente.

---

## 7. Aspectos técnicos

| Componente | Tecnología |
|------------|------------|
| Backend | PHP 8 (XAMPP / Apache) |
| Base de datos | MySQL / MariaDB (utf8mb4) |
| Frontend | HTML5, CSS3, JavaScript |
| UI | jQuery + jQuery UI |
| Reportes | HTML2PDF / TCPDF |
| Arquitectura | MVC |
| Seguridad | `Seguridad.php`, `GestorUsuario.php`, `config/config.php` |

### Requisitos

- Apache (o similar), PHP 8+, MySQL 5.7+ / MariaDB.  
- Navegador actual (Chrome, Firefox, Edge).  
- Base de datos del proyecto importada (pacientes, médicos, citas).  
- Archivo `config/config.php` configurado.

### Estructura útil

```
clinica_muelitas/
├── config/config.php           # BD y parámetros de sesión
├── index.php                   # Login, setup inicial y rutas
├── modelo/Seguridad.php
├── modelo/GestorUsuario.php    # Login + creación primera cuenta
├── sql/seguridad_usuarios.sql  # Opcional
├── vista/html/login.php        # Login o asistente inicial
└── docs/                       # Este documento
```

---

## 8. Modelo de datos

- **Usuarios:** acceso al sistema (usuario, clave hash, nombre, activo).  
- **Pacientes:** identificación, nombres, apellidos, nacimiento, sexo.  
- **Médicos:** identificación, nombres, apellidos.  
- **Consultorios:** número y nombre.  
- **Citas:** fecha, hora, paciente, médico, consultorio, estado, observaciones.  
- **Horas:** franjas con disponibilidad según citas existentes.

---

## 9. Público objetivo

- Clínicas odontológicas familiares.  
- Consultorios con uno o varios odontólogos.  
- Clínicas universitarias o de práctica.  
- Quien busca agenda digital simple sin software hospitalario pesado.

---

## 10. Diferenciadores

1. Flujo odontológico (médico + consultorio + bloques de hora).  
2. Registro de paciente en el mismo flujo de la cita.  
3. Horas libres calculadas por médico y día.  
4. PDF con marca Clínica Muelitas.  
5. Seguridad y asistente de primera cuenta integrados.  
6. Instalación ligera en XAMPP o hosting PHP.  
7. Código MVC mantenible.

---

## 11. Hoja de ruta (ampliaciones futuras)

- Roles (recepción, médico, admin).  
- Historial clínico / odontograma.  
- Recordatorios email/SMS.  
- Dashboard de estadísticas.  
- Facturación, app móvil, auditoría de acciones.

---

## 12. Propuesta de valor comercial

> *"Ordene su agenda, proteja los datos de sus pacientes y empiece el mismo día: si es la primera instalación, el sistema le guía para crear su cuenta de administrador sin técnicos ni scripts complicados."*

### Paquete sugerido de entrega

| Ítem | Incluido |
|------|----------|
| Sistema web instalado | Sí |
| Base de datos inicial (citas/pacientes) | Sí |
| Asistente primera cuenta + login seguro | Sí |
| Capacitación básica (1 sesión) | Sí |
| Este manual / presentación | Sí |
| Soporte 30 días | Opcional |
| Logo y colores | Sí |
| Hosting + HTTPS | Opcional |

---

## 13. Demostración del sistema

**URL local:** `http://localhost/clinica_muelitas/`

### Escenario A — Instalación nueva (sin usuarios)

1. Abrir la URL.  
2. Ver **Crear cuenta administrador**.  
3. Completar nombre, usuario y contraseña (mín. 8 caracteres).  
4. Pulsar **Crear cuenta y entrar** → pantalla de inicio.  
5. Demo: asignar cita, PDF, consultar, cancelar, cerrar sesión.

### Escenario B — Ya hay usuario (login)

1. Abrir la URL → **Iniciar sesión**.  
2. Ingresar usuario y contraseña.  
3. Mismo flujo de demo.

### Guion rápido (5–7 min)

1. Explicar login o asistente inicial.  
2. Asignar cita (paciente nuevo si aplica).  
3. Generar PDF.  
4. Consultar citas del paciente.  
5. (Opcional) Cancelar una cita.  
6. Cerrar sesión.

---

## 14. Preguntas frecuentes

**¿Debo ejecutar `seguridad_usuarios.sql`?**  
No es obligatorio. El sistema crea la tabla y, si no hay usuarios, muestra el asistente de cuenta.

**Solo veo «Iniciar sesión» y no recuerdo la clave.**  
Ya existe un usuario en la base de datos. Un administrador de MySQL puede restablecer la contraseña (nuevo hash bcrypt) o vaciar la tabla `usuarios` para volver a mostrar el asistente (solo en entornos de prueba).

**¿Puedo tener varios administradores?**  
La primera cuenta se crea con el asistente. Usuarios adicionales pueden añadirse después en base de datos o en una futura versión con gestión de usuarios.

**¿Funciona sin internet?**  
Sí, en red local (XAMPP/LAN). Para acceso remoto se recomienda hosting con HTTPS.

---

## 15. Conclusión

**Clínica Muelitas** combina gestión de citas odontológicas, comprobantes PDF, interfaz cuidada y **seguridad práctica**, incluyendo un **arranque sin cuenta previa**: quien instala por primera vez no se queda bloqueado en un login imposible, sino que crea su administrador en pantalla y empieza a trabajar.

Listo para presentación comercial, venta llave en mano o proyecto académico con nivel profesional.

---

*Clínica Muelitas — Gestión segura de citas odontológicas. Documento comercial v2.1.*
