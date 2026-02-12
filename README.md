# Lucio Torres Blog

> Periodismo de investigación y análisis político sobre Cartagena, Bolívar y Colombia.

🌐 **Sitio en vivo:** [luciotorres.online](https://luciotorres.online)

## Sobre el Proyecto

Este es el sitio web personal de **Lucio Torres**, periodista e investigador colombiano con más de 10 años de trayectoria cubriendo temas de corrupción, política local y poder en la región Caribe.

### Características

- **1,000+ artículos** publicados desde 2018
- **Paginación SEO-friendly** con enlaces numerados y navegación prev/next
- **Imágenes optimizadas** con lazy loading y relación de aspecto 16:9
- **Diseño responsive** optimizado para móviles
- **Alto rendimiento:** Build estático con Astro 5
- **SEO completo:** Meta tags, Open Graph, sitemap.xml, RSS feed

## Stack Tecnológico

- **Framework:** [Astro](https://astro.build/) v5.17
- **Lenguaje:** TypeScript
- **Estilos:** CSS vanilla con variables CSS
- **Deploy:** Railway (Docker)
- **Gestión de contenido:** Content Collections de Astro
- **Imágenes:** Componente LazyImage personalizado con aspect-ratio fijo

## Estructura del Proyecto

```
├── src/
│   ├── components/          # Componentes reutilizables
│   │   ├── LazyImage.astro  # Imágenes con lazy loading
│   │   ├── Pagination.astro # Paginación con números y ellipsis
│   │   ├── Header.astro
│   │   ├── Footer.astro
│   │   └── BaseHead.astro   # Metadatos SEO
│   ├── content/
│   │   └── articulos/       # Colección de artículos en Markdown
│   ├── layouts/
│   │   └── BlogPost.astro   # Layout de artículos individuales
│   ├── pages/
│   │   ├── articulos/
│   │   │   ├── [...page].astro  # Listado paginado de artículos
│   │   │   └── [...id].astro    # Página de artículo individual
│   │   ├── index.astro
│   │   └── about.astro
│   └── styles/
├── public/
│   └── pdfs/               # Documentos públicos (tutelas, contratos, etc.)
├── astro.config.mjs
├── Dockerfile
└── railway.json
```

## Comandos

```bash
# Instalar dependencias
npm install

# Servidor de desarrollo
npm run dev

# Build de producción
npm run build

# Preview local del build
npm run preview
```

## Características Destacadas

### Paginación Inteligente

El sistema de paginación implementa:
- Números de página visibles (ventana de 5 páginas)
- Ellipsis (...) para saltar páginas lejanas
- Enlaces "Anterior" y "Siguiente" con atributos `rel="prev"` y `rel="next"`
- Títulos y descripciones únicos por página para SEO

### Optimización de Imágenes

El componente `LazyImage` proporciona:
- Carga diferida (lazy loading) nativa
- Relación de aspecto fija 16:9 en todas las tarjetas
- `object-fit: cover` para recorte uniforme
- Placeholder con gradiente mientras carga
- Transición suave al cargar

### SEO Avanzado

Cada página incluye:
- Títulos descriptivos únicos
- Meta descripciones personalizadas
- URLs canónicas
- Open Graph tags (Facebook/Twitter)
- Sitemap XML generado automáticamente
- Feed RSS

## Licencia

© 2026 Lucio Torres. Todos los derechos reservados.

---

**Contacto:** [luciotorres.online](https://luciotorres.online) | Twitter: [@luciotorres](https://twitter.com/luciotorres)
