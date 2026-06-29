# Sistema de Diseño — Patio Majau

> Adaptación del enfoque “Anillos” al sistema del restaurante.

## Concepto visual

Interfaz cálida y cercana, inspirada en colores tierra y superficies redondeadas para transmitir hospitalidad y claridad operativa.

## Principios

- **Legibilidad primero**: priorizar lectura rápida en caja/cocina.
- **Consistencia**: mismo patrón de botones, formularios y tablas.
- **Mobile-first**: formularios de pedido funcionales en tablet/móvil.
- **Estado visible**: badges claros para pedido, stock y factura.

## Tokens sugeridos

```css
:root {
  --color-primario: #1b1410;
  --color-secundario: #5e483a;
  --color-fondo: #f5f2eb;
  --color-superficie: #fffdfa;
  --color-alerta: #ff8c00;
  --color-peligro: #b42318;
  --radius-card: 24px;
  --radius-pill: 9999px;
}
```

## Componentes base

- Botón primario redondeado
- Botón secundario contorneado
- Tarjeta de módulo (dashboard)
- Tabla con badge de estado
- Formulario con validación visual

## Estados recomendados

- Pedido: Pendiente / En Preparación / Listo / Entregado
- Stock: Normal / Bajo
- Factura: Emitida

