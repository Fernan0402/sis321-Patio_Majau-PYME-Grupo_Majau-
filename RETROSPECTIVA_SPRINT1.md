# Retrospectiva del Proyecto (Sprint 1)
**Proyecto:** Sistema de Ventas - Restaurante Patio del Majau  
**Periodo evaluado:** Trabajo realizado hasta la fecha (Sprint 1)  
**Metodología:** Scrum - Retrospectiva Start / Stop / Continue

## 1. Objetivo de la retrospectiva

Analizar cómo trabajamos en el Sprint 1 para identificar:

- Qué prácticas funcionaron bien.
- Qué prácticas nos frenaron.
- Qué acciones concretas debemos aplicar en el siguiente sprint.

El enfoque es de mejora continua del equipo, del proceso y del producto.

## 2. Contexto del Sprint 1

Durante este periodo se avanzó en la implementación de funcionalidades críticas del sistema:

- HU-08: Registrar usuarios.
- HU-14: Registrar productos.
- HU-13: Ver menú.
- HU-19: Notificar falta de stock.
- HU-02: Registrar pedido.
- HU-03: Registrar venta.
- HU-17: Generar factura.

Además, se organizaron commits por historia, documentación técnica (`README`) y un informe de desarrollo del sistema web.

## 3. Qué salió bien (fortalezas)

- **Priorización efectiva por valor de negocio:** Se trabajó primero lo más crítico para operar el restaurante.
- **Implementación modular:** Cada HU se desarrolló en controladores, rutas y vistas separadas, facilitando mantenimiento.
- **Trazabilidad técnica:** Se logró ordenar el historial de cambios con commits semánticos claros.
- **Integración funcional del flujo comercial:** Pedido -> venta -> factura quedó operativo para demostración.
- **Documentación del proyecto:** Se fortaleció la parte de README e informe para evidencias académicas.

## 4. Qué no salió bien (dolores / obstáculos)

- **Incidencia en autenticación (HU-01):** El login presentó problemas de compatibilidad y comportamiento en entorno local.
- **Demoras por ajustes de historial Git:** Reestructurar commits después de haber consolidado cambios implicó retrabajo.
- **Dependencia de pruebas manuales:** Faltó automatización de pruebas para validar regresiones rápidamente.
- **Gestión tardía de archivos académicos:** Algunos documentos no necesarios terminaron en el repositorio y luego hubo que limpiarlos.

## 5. Start / Stop / Continue

## Start (Empezar a hacer)

1. **Definir checklist de “Definition of Done” por HU** antes de codificar (código, pruebas, docs, commit).
2. **Crear rama por HU desde el inicio** para evitar reescritura de historial.
3. **Agregar pruebas funcionales mínimas** para rutas críticas (pedidos, ventas y facturas).
4. **Configurar plantilla de PR/merge** con criterios de aceptación obligatorios.
5. **Registrar riesgos técnicos temprano** (ej. autenticación) y tratarlos en paralelo.

## Stop (Dejar de hacer)

1. **No esperar al final para ordenar commits/documentación.**
2. **No mezclar cambios de varias HU en un solo bloque grande.**
3. **No subir archivos académicos pesados al repositorio de código** cuando no aportan al despliegue.
4. **No depender únicamente de validación visual/manual** para decidir que una HU está lista.

## Continue (Continuar haciendo)

1. **Seguir priorizando por MoSCoW y Story Points.**
2. **Mantener estructura MVC limpia** (rutas, controladores, modelos, vistas).
3. **Conservar mensajes de commit descriptivos** con prefijos `feat`, `chore`, `docs`.
4. **Documentar decisiones técnicas** y avances de sprint para facilitar evaluación y mantenimiento.

## 6. Lecciones aprendidas

- La velocidad no depende solo de programar rápido, sino de mantener orden en ramas, commits y validación.
- Resolver primero el núcleo del negocio (pedido-venta-factura) entrega valor real aunque exista deuda técnica en módulos secundarios.
- Una buena documentación reduce fricción al momento de preparar demo, evaluación y entrega final.

## 7. Plan de mejora para Sprint 2

- **Meta técnica 1:** estabilizar HU-01 (autenticación) y dejar sesiones confiables.
- **Meta técnica 2:** incorporar control de permisos/roles más granular.
- **Meta de calidad:** implementar pruebas mínimas automatizadas para flujo comercial.
- **Meta de proceso:** trabajar todas las HU con rama propia y merge ordenado.
- **Meta de documentación:** actualizar retrospectiva e informe al cierre de cada sprint.

## 8. Cierre

El Sprint 1 fue productivo y permitió consolidar la base funcional del sistema web del restaurante.  
La principal oportunidad de mejora está en reforzar la disciplina de ingeniería (pruebas, ramas, checklists) desde el inicio de cada HU.  
Con las acciones propuestas, el equipo puede incrementar estabilidad y reducir retrabajo en los próximos sprints.
