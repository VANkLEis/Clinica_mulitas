# Clínica Muelitas — Documento de implementación completa

**Versión del sistema:** 2.2  
**Fecha de referencia:** Mayo 2026  
**Ruta del proyecto:** `clinica_muelitas/`  
**URL local típica:** `http://localhost/clinica_muelitas/`

Este documento describe **todo lo implementado** en el sistema: funcionalidades, seguridad, correcciones técnicas y guía de uso.

---

## 1. Resumen del producto

Sistema web de gestión de citas odontológicas con:

- Registro y consulta de pacientes  
- Asignación de citas (médico, fecha, hora, consultorio)  
- Consulta y cancelación de citas  
- Comprobante PDF por cita  
- Interfaz visual renovada (logo, colores, tipografía)  
- Capa de seguridad (login, registro, sesiones, CSRF, validaciones)  
- Compatibilidad con **PHP 8** y **XAMPP**

---

## 2. Módulos funcionales

### 2.1 Acceso al sistema

| Función | Descripción |
|---------|-------------|
| Iniciar sesión | Usuario y contraseña; bloqueo tras 5 intentos fallidos (15 min) |
| Crear cuenta | Botón visible en login; pantalla `?accion=registro` |
| Primera instalación | Si no hay usuarios, redirección automática a registro |
| Cerrar sesión | Enlace en cabecera |
| Timeout de sesión | 1 hora de inactividad (configurable) |

**Configuración:** `config/config.php` → `permitir_registro`, `session_timeout`, credenciales MySQL.

### 2.2 Inicio

Panel con accesos rápidos a Asignar, Consultar y Cancelar citas.

### 2.3 Asignar cita

1. Documento del paciente → consulta AJAX.  
2. Si no existe → modal para registrar paciente (POST + CSRF).  
3. Selección de médico y fecha → carga AJAX de **horas disponibles**.  
4. Selección de consultorio → envío del formulario (POST + CSRF).  
5. Pantalla de confirmación y enlace a PDF.

**Datepickers:**

- Nacimiento del paciente: 1940–2026.  
- Fecha de cita: desde hoy, años futuros.

### 2.4 Consultar citas

Búsqueda por documento; listado de citas activas; detalle y PDF.

### 2.5 Cancelar citas

Búsqueda por documento; cancelación con confirmación (POST + CSRF).

### 2.6 Reporte PDF

Generación con HTML2PDF/TCPDF; parches para PHP 8 en librería TCPDF embebida.

---

## 3. Seguridad implementada

| Medida | Archivo / detalle |
|--------|-------------------|
| Autenticación | `GestorUsuario.php`, tabla `usuarios` |
| Registro de cuentas | `index.php?accion=registro`, `permitir_registro` |
| Contraseñas bcrypt | `password_hash` / `password_verify` |
| Sesión segura | `Seguridad::iniciar()` — HttpOnly, SameSite Strict |
| CSRF | Token en formularios y `window.CSRF_TOKEN` en JS |
| Validación servidor | `Seguridad.php` — documento, médico, fecha, hora, textos |
| Escape XSS | `Seguridad::esc()` en vistas |
| SQL preparado | `GestorCita`, `GestorUsuario` |
| Cabeceras HTTP | X-Frame-Options, CSP básica, nosniff |
| Proteger config | `config/.htaccess` |

---

## 4. Interfaz y marca

- **CSS:** `vista/css/estilos.css` — paleta verde azulado, acento melocotón, tipografías DM Sans y Fraunces.  
- **Fragmentos:** `vista/html/fragmentos/cabecera.php`, `pie.php`.  
- **Logo:** `vista/imagenes/logo.svg` — muela con corona, surcos y raíces.  
- **Pantallas:** `inicio.php`, `asignar.php`, `consultar.php`, `cancelar.php`, `login.php`, `registro.php`, `confirmarCita.php`.

---

## 5. Arquitectura técnica

```
index.php                 → Enrutador, login, registro, validaciones
controlador/controlador.php
modelo/
  Seguridad.php           → Sesión, CSRF, validaciones, cabeceras
  GestorUsuario.php       → Login y registro
  GestorCita.php          → Citas, pacientes, horas
  conexion.php            → MySQL utf8mb4 desde config
  cita.php, paciente.php
vista/html/               → Vistas PHP
vista/js/script.js        → Asignar, datepickers, horas AJAX
vista/js/script2.js       → Consultar y cancelar
config/config.php         → Parámetros (no subir secretos a repos públicos)
sql/seguridad_usuarios.sql → Opcional
```

**Patrón:** MVC ligero (controlador carga modelos y vistas).

---

## 6. Base de datos

Entidades principales:

- `pacientes`, `medicos`, `consultorios`, `citas`, `horas`, `usuarios`

**Horas disponibles:** se calculan excluyendo citas ya *solicitadas* para el mismo médico y fecha.

**Nota:** los IDs de médico pueden ser cortos (ej. `1`); la validación usa `medicoIdValido()` (1–20 caracteres alfanuméricos), no la misma regla que el documento del paciente (mínimo 4 caracteres).

---

## 7. Correcciones aplicadas (historial relevante)

| Problema | Solución |
|----------|----------|
| TCPDF incompatible con PHP 8 | Parches en `vista/pdf/html2pdf/_tcpdf_5.0.002/` |
| PDF con salida previa | `ob_end_clean` en generación de reporte |
| Datepickers con años incorrectos | Rangos fijos en `script.js` |
| Login sin poder crear cuenta | Pantalla `registro` + botón «Crear cuenta» |
| Warning `Undefined array key "hora"` | Uso de `$_POST['hora'] ?? ''` y validación segura |
| Horas no cargaban en el select | Validación de médico exigía 4+ caracteres; ID `1` fallaba → `medicoIdValido()` |
| Opción de hora `disabled` no enviaba valor | Placeholder sin `disabled`; validación en cliente y servidor |
| Rutas `.html` rotas | Vistas servidas como `.php` desde `index.php` |
| Cancelar cita: «Solicitud no válida» | `accion` solo se leía por GET; cancelación va por POST → lectura GET+POST y URL `?accion=confirmarCancelar` |

---

## 8. Flujo de horas (asignar cita)

1. Usuario elige **médico** y **fecha**.  
2. JavaScript llama `index.php?accion=consultarHoras&medico=X&fecha=YYYY-MM-DD`.  
3. Servidor valida médico y fecha → `GestorCita::consultarHorasDisponibles()`.  
4. Vista `consultarHoras.php` devuelve `<option>` con valores tipo `08:00:00`.  
5. Usuario elige hora y envía el formulario con `name="hora"`.

---

## 9. Instalación rápida

1. Copiar proyecto a `htdocs/clinica_muelitas`.  
2. Importar base de datos del proyecto (pacientes, médicos, citas, horas).  
3. `config/config.example.php` → `config/config.php` (datos MySQL).  
4. Apache + MySQL activos (XAMPP).  
5. Abrir la URL → **Crear cuenta** o iniciar sesión.  
6. (Opcional) `sql/seguridad_usuarios.sql` para precargar usuario.

---

## 10. Prueba recomendada

1. Login o crear cuenta.  
2. Asignar cita: documento existente o nuevo paciente.  
3. Médico + fecha → verificar que aparecen horas en el desplegable.  
4. Consultorio → Enviar → confirmación y PDF.  
5. Consultar y cancelar cita de prueba.  
6. Cerrar sesión.

---

## 11. Archivos de documentación

| Documento | Contenido |
|-----------|-----------|
| `docs/Presentacion_Comercial_Clinica_Muelitas.md` | Presentación para venta / cliente |
| `docs/Documento_Implementacion_Completa.md` | Este archivo — detalle técnico y funcional |

---

## 12. Configuración (`config/config.php`)

```php
'db_host' => 'localhost',
'db_user' => 'root',
'db_pass' => '',
'db_name' => 'clinica_muelitas',
'session_name' => 'CLINICA_MUELITAS_SID',
'session_timeout' => 3600,
'login_max_intentos' => 5,
'login_bloqueo_segundos' => 900,
'permitir_registro' => true,
```

---

## 13. Conclusión

El sistema **Clínica Muelitas** queda operativo para uso en clínica o demostración académica/comercial, con agenda de citas, PDF, interfaz actualizada, seguridad de acceso y corrección del flujo de **selección de horas** para médicos con identificación corta.

Para soporte o ampliaciones (roles, más usuarios desde panel, HTTPS en producción), la base MVC permite extender sin rehacer el núcleo.

---

*Clínica Muelitas — Implementación completa documentada.*
