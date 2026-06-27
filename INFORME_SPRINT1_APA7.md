# Informe Técnico del Sprint 1  
**Proyecto:** Sistema de Ventas - Restaurante Patio del Majau  
**Asignatura:** Desarrollo de Sistemas II  
**Fecha:** Junio 2026  

## Resumen ejecutivo

El presente informe documenta la planificación y ejecución del Sprint 1 del sistema de ventas para el restaurante Patio del Majau, priorizado mediante MoSCoW y estimado con Story Points. El objetivo del sprint fue construir funcionalidades críticas del negocio orientadas a operaciones diarias de restaurante: registro de usuarios, gestión de productos, visualización de menú, notificación de stock bajo, registro de pedidos, registro de ventas y generación de facturas. La implementación se realizó sobre el stack PHP 8.x + Laravel + MySQL (XAMPP), siguiendo principios de modelado relacional en tercera forma normal y uso de migraciones con restricciones de integridad. Como resultado, se obtuvo un incremento funcional navegable con rutas operativas para módulos comerciales y administrativos, además de documentación de despliegue local, estrategia de ramas, propuesta de commits para GitHub y guion de Sprint Review. Se identificaron riesgos técnicos en la autenticación (HU-01) que se mantienen en tratamiento, sin bloquear la entrega del resto del alcance Must Have priorizado para la semana. El avance evidencia coherencia entre requerimientos funcionales, artefactos UML previos y desarrollo incremental basado en evidencia y validación iterativa.

**Palabras clave:** Scrum, Sprint, MoSCoW, Laravel, MySQL, restaurante, facturación.

## 1. Introducción

La digitalización de procesos en micro y pequeñas empresas gastronómicas requiere soluciones graduales que combinen valor de negocio temprano con control técnico del alcance. Bajo esta premisa, el Sprint 1 se diseñó para construir capacidades mínimas operativas del sistema del restaurante Patio del Majau, tomando como base los artefactos de análisis de Actividad 2 (casos de uso y diagrama de clases) y la priorización ágil definida en la matriz MoSCoW.

El sprint prioriza historias Must Have que soportan el ciclo comercial central: definir quién usa el sistema (usuarios/roles operativos), qué se vende (productos y menú), cómo se vende (pedido y venta) y cómo se formaliza fiscalmente (factura), incorporando control de riesgo de inventario por stock bajo.

## 2. Marco teórico

Scrum propone ciclos cortos orientados a entregables potencialmente utilizables, reduciendo incertidumbre y promoviendo inspección y adaptación continua (Schwaber & Sutherland, 2020). En este marco, las historias de usuario permiten expresar valor desde la perspectiva del actor y facilitar priorización incremental (Cohn, 2004).  

Desde la ingeniería de requisitos, la priorización MoSCoW mejora la toma de decisiones de alcance al separar lo indispensable de lo deseable, contribuyendo a entregas más previsibles en contextos de tiempo restringido (DSDM Consortium, 2014). En paralelo, la calidad de la persistencia depende de un modelo de datos consistente; la normalización hasta 3FN disminuye redundancia y anomalías de actualización, lo que incrementa mantenibilidad de sistemas transaccionales (Elmasri & Navathe, 2016).  

Finalmente, las prácticas de versionado y trazabilidad en Git favorecen colaboración y auditabilidad del proceso de construcción, condición crítica en proyectos con entregas por sprint y múltiples módulos acoplados (Chacon & Straub, 2014).

## 3. Desarrollo del Sprint 1

### 3.1 Paso 1 - Stack tecnológico

Se adoptó la opción académica de referencia: **PHP 8.x + Laravel + MySQL**.  
En este repositorio la base instalada corresponde a Laravel 12 (compatible con lineamientos de Laravel 11 a nivel de arquitectura MVC, migraciones y Eloquent).  

### 3.2 Paso 2 - Base de datos

Se trabajó con **MySQL de XAMPP** y diseño relacional basado en diagrama de clases previo.  
Entidades principales: `empleados`, `productos`, `insumos`, `producto_insumo`, `mesas`, `pedidos`, `detalle_pedidos`, `ventas`, `facturas`.  

Criterios de calidad aplicados:

- Claves primarias y foráneas explícitas.
- Integridad referencial en tablas transaccionales.
- Separación de entidades para evitar dependencias parciales y transitivas (2FN/3FN).

### 3.3 Paso 3 - Datos de prueba

Se usaron seeders para poblar tablas base y habilitar pruebas funcionales rápidas.  
Para la extensión del dataset, se establece como práctica recomendada el uso de Mockaroo con perfiles LATAM (nombres, ciudades, monedas) y carga por SQL/CSV.

### 3.4 Paso 4 - Autenticación con JWT (lineamiento solicitado)

El lineamiento académico plantea JWT en Node.js/Express con `jsonwebtoken`, `bcrypt` y `express-validator`.  
**Estado en este sprint:** no implementado, porque el proyecto actual utiliza Laravel con autenticación web tradicional y la HU-01 está en estabilización técnica.  

**Decisión de ingeniería:** documentar JWT como mejora para una iteración posterior o como microservicio de autenticación desacoplado, evitando mezcla parcial de stacks dentro del mismo sprint.

### 3.5 Paso 5 - Sistema de roles

Se implementó control de rol operativo en `empleados` (Administrador, Mesero, Cajero, Cocinero) para habilitar HU-08, HU-02 y HU-03/HU-17 desde reglas de negocio del dominio.  

**Estado del modelo solicitado (`roles`, `permisos`, `roles_permisos`):** pendiente de implementación formal como ACL granular; queda registrado como mejora de arquitectura para Sprint 2.

### 3.6 Historias de usuario desarrolladas

Implementadas:

- HU-08: Registrar usuarios.
- HU-14: Registrar productos.
- HU-13: Ver menú.
- HU-19: Notificar falta de stock.
- HU-02: Registrar pedido.
- HU-03: Registrar venta.
- HU-17: Generar factura.

En tratamiento:

- HU-01: Inicio de sesión (incidencia de entorno/ruteo/sesión).

## 4. Resultados y análisis

### 4.1 Resultado funcional

El incremento del sprint permite ejecutar flujo comercial principal sin bloqueo del resto de módulos:

1. Alta y edición de usuarios operativos.  
2. Gestión de catálogo de productos y menú visible.  
3. Registro de pedidos con detalle y actualización de estado.  
4. Registro de venta y generación de factura correlativa.  
5. Visualización de alertas de stock bajo en panel.

### 4.2 Trazabilidad técnica

La implementación mantiene coherencia con los casos de uso y secuencias diseñadas previamente (pedido -> verificación de insumos -> venta -> factura). Se evidencia avance incremental de alto valor sobre los módulos directamente vinculados al ingreso diario del restaurante.

### 4.3 Riesgos y deuda técnica

- Pendiente de estabilización de HU-01 en entorno local.
- Pendiente de ACL formal (`roles`/`permisos`) y middleware granular.
- Pendiente de pruebas automatizadas de regresión para flujo completo.

## 5. GitHub: ramas y commits (Paso 6)

Nombre sugerido de repositorio institucional:

- `sis321-patio-majau-grupoX`

Ramas sugeridas:

- `main`, `develop`
- `feature/hu-08-usuarios`
- `feature/hu-14-productos`
- `feature/hu-13-menu`
- `feature/hu-19-stock-alertas`
- `feature/hu-02-pedidos`
- `feature/hu-03-ventas-hu17-factura`
- `docs/sprint1-informe`

Propuesta de 5+ commits descriptivos:

1. `feat(hu-08): implementar CRUD de empleados para registro de usuarios`
2. `feat(hu-14): completar módulo de productos con validaciones de formulario`
3. `feat(hu-13): publicar vista de menú agrupada por categoría`
4. `feat(hu-19): agregar alertas de stock bajo en dashboard y pedidos`
5. `feat(hu-02): implementar registro de pedidos con detalle y estados`
6. `feat(hu-03,hu-17): registrar ventas y emitir factura correlativa`
7. `docs: actualizar README e informe Sprint 1 en formato académico`

## 6. Sprint Review (Paso 7) - Guion de demo (10 minutos)

1. Crear usuario operativo en módulo de empleados.  
2. Registrar producto y visualizarlo en menú.  
3. Registrar pedido (mesa, productos, cantidades).  
4. Cambiar estado del pedido hasta `Entregado`.  
5. Registrar venta con método de pago.  
6. Generar y visualizar factura.  
7. Mostrar alerta de stock bajo en dashboard (si aplica).  

## 7. Retrospectiva (Paso 8) - Start / Stop / Continue

| Start | Stop | Continue |
|---|---|---|
| Implementar pruebas HTTP/E2E para pedido-venta-factura | Mezclar cambios grandes en un solo commit | Trabajo por HU con alcance pequeño y validable |
| Formalizar ACL con tablas `roles/permisos` | Posponer validación de rutas críticas hasta el final | Uso de migraciones y seeders como base de reproducibilidad |
| Definir estrategia JWT como módulo desacoplado | Acoplar demasiada lógica de negocio en controladores | Priorización MoSCoW y review de sprint con demo funcional |

## 8. Conclusiones

El Sprint 1 logró construir el núcleo transaccional del sistema comercial del restaurante, con cobertura funcional sobre usuarios, productos, menú, pedidos, ventas y facturación. La entrega demuestra viabilidad técnica del enfoque incremental y correspondencia con requisitos del negocio.  

Desde la perspectiva metodológica, el uso de historias de usuario, priorización MoSCoW y desarrollo por incrementos permitió maximizar valor aún con incidencias en el módulo de autenticación. Como mejora inmediata, se recomienda estabilizar HU-01, formalizar el modelo de roles/permisos y automatizar pruebas de regresión para consolidar calidad antes del siguiente sprint.

## Referencias (APA 7)

Chacon, S., & Straub, B. (2014). *Pro Git* (2nd ed.). Apress. https://git-scm.com/book/en/v2  

Cohn, M. (2004). *User stories applied: For agile software development*. Addison-Wesley.

DSDM Consortium. (2014). *Agile project framework handbook* (v2.0). DSDM Consortium.

Elmasri, R., & Navathe, S. B. (2016). *Fundamentals of database systems* (7th ed.). Pearson.

Schwaber, K., & Sutherland, J. (2020). *The Scrum Guide: The definitive guide to Scrum: The rules of the game*. Scrum.org. https://scrumguides.org/

Sommerville, I. (2011). *Software engineering* (9th ed.). Addison-Wesley.
