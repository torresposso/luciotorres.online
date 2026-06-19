# Auditoría de Código — luciotorres.online

**Fecha**: 2026-06-19
**Riesgo General**: MEDIO

---

## Executive Summary

Se auditaron **34 archivos fuente** del theme Sage 11 + Bedrock (excluyendo vendor, WordPress core, y dependencias). 3 sub-agentes paralelos revisaron Seguridad, Arquitectura y Clean Code.

### Top 5 Issues

| # | Severidad | Archivo | Problema | Esfuerzo |
|---|-----------|---------|----------|----------|
| 1 | 🔴 CRITICAL | `SeoServiceProvider.php` | **Violación masiva de SRP** — maneja meta boxes, persistencia, sitemaps, HTTP headers y echo/exit. 5+ responsabilidades en una clase. | M |
| 2 | 🟠 HIGH | `View/Composers/Index.php` | **God object** — sección config, cache, bulk queries, term mapping, fallbacks, cache priming en un solo método `with()`. | M |
| 3 | 🟠 HIGH | `View/Composers/Seo.php` | **Renders HTML/JSON en el Composer** en vez de devolver datos estructurados para el template. Viola el patrón View Composer. | S |
| 4 | 🟠 HIGH | `SeoServiceProvider.php:319` | **Hard `exit`** sin pasar por `wp_die()`. Omite hooks de shutdown de WordPress. | S |
| 5 | 🟡 MEDIUM | `Vite.php:17-37` | **Host header injection** en URLs de assets HMR — sin validación de host en modo dev. Combinado con Vite expuesto en `0.0.0.0`. | S |

---

## Project Map

```
luciotorres.online — WordPress 6.9.4 / Bedrock / Sage 11 / Acorn 6
  PHP 8.3+ • FrankenPHP • SQLite/MySQL • Tailwind v4 + DaisyUI v5
  Vite • Pest PHP 4 • Docker multi-stage • Railway

app/  (source ~2500 LOC)
├── setup.php          272 loc — hooks, Vite binding, GA4/Meta Pixel
├── filters.php        218 loc — REST auth, URL rewrite, security
├── Vite.php            41 loc — HMR host override
├── Providers/
│   └── SeoServiceProvider.php  321 loc — SRP violation
├── View/Composers/
│   ├── Seo.php        212 loc — HTML render in composer
│   ├── Post.php       219 loc — queries in composer
│   ├── Index.php      112 loc — god object
│   ├── Archive.php     80 loc
│   └── Comments.php    90 loc
└── Seo/
    ├── JsonLd.php     147 loc — ✅ well-structured
    ├── SeoMeta.php    177 loc — ✅ value object
    ├── MetaRenderer.php  95 loc
    ├── Sitemap.php    116 loc
    ├── TitleExpander.php  46 loc
    └── Migration.php  207 loc — static CLI command

tests/  (Pest PHP, 9 suites)
  ├── FiltersTest.php         — solo is_allowed_dev_host cubierto
  ├── Providers/              — solo method_exists
  └── Seo/ (6 files)          — ✅ buena cobertura de unidades puras

Audit coverage: 34/34 archivos fuente revisados (100%).
```

---

## Findings Consolidados

### 🔴 CRITICAL

| ID | Archivo:línea | Categoría | Resumen | Esfuerzo |
|----|---------------|-----------|---------|----------|
| A1 | `SeoServiceProvider.php:17-321` | SRP | **SeoServiceProvider hace de todo**: registra rewrites, renderiza meta boxes HTML+JS, persiste postmeta, maneja requests de sitemap con WP_Query + HTTP headers + echo/exit. Una clase = 5 responsabilidades. Extraer a `SeoMetaBox`, `SitemapController`, y mantener solo wiring en el provider. | M |

### 🟠 HIGH

| ID | Archivo:línea | Categoría | Resumen | Esfuerzo |
|----|---------------|-----------|---------|----------|
| B1 | `View/Composers/Index.php:17-79` | God Object | `with()` hace cache management, bulk WP_Query, term mapping, per-section filtering, fallback queries, y _prime_caches. Extraer a `HomepageService`. | M |
| B2 | `View/Composers/Seo.php:108-180` | Pattern Violation | Retorna strings HTML/JSON desde `with()`. Debe retornar `SeoMeta` y arrays, renderizar en el Blade. | S |
| B3 | `SeoServiceProvider.php:319` | WordPress | `exit` duro corta shutdown hooks de WP. Usar `wp_die()`. | S |
| B4 | `View/Composers/Comments.php:88` | Type Safety | Comparación `!=` contra string `'0'`. Usar `!==`. | S |
| B5 | `setup.php:208` + `Post.php:96` | Duplicación | Cálculo de word count / reading time duplicado. Extraer a `ReadingTimeService`. | S |
| B6 | `filters.php:113-187` | Function Size | Filtro `the_content` de 74 líneas con 3 responsabilidades: host rewrite, path rewrite, thumbnail fallback. | M |

### 🟡 MEDIUM

| ID | Archivo:línea | Categoría | Resumen | Esfuerzo |
|----|---------------|-----------|---------|----------|
| C1 | `Vite.php:17-37` | **XSS** | Host header injection en `hotAsset()` — sin `is_allowed_dev_host()`. Permite a un atacante LAN inyectar URLs de scripts. | S |
| C2 | `docker-compose.yml:28` | Config | Vite expuesto en `0.0.0.0:5174`. Cambiar a `127.0.0.1:5174:5174`. | S |
| C3 | `Dockerfile:90` | Config | `COPY . /app` puede incluir `.env` con credenciales en la imagen. Verificar `.dockerignore`. | S |
| C4 | `setup.php:221-272` | Module Boundary | GA4 + Meta Pixel inline en setup, no en servicio dedicado. Extraer a `AnalyticsService`. | S |
| C5 | `SeoProvider.php:249-320` | SRP | Sitemap handler en provider. Mover a `SitemapController`. | S |
| C6 | `Post.php:141-218` | Pattern Violation | WP_Query directo en composer para suggestedPosts/featuredPost. Extraer a `PostRepository`. | S |
| C7 | `SeoProvider.php:145` | Admin JS | JS del meta box inline. Encolar con `admin_enqueue_scripts`. | S |
| C8 | `SeoMeta.php:45` | Error Handling | Constructor acepta data inválida silenciosamente. Agregar validación con excepciones. | S |
| C9 | `JsonLd.php` | Pattern | `@context` seteado y luego `unset()` 4 veces en el caller. Agregar flag `$includeContext`. | S |
| C10 | `footer.blade.php:1,13` | **Brand Rule** | Usa `text-slate-400/500` violando la regla del proyecto (solo Midnight + Orange vía OKLCH). | S |
| C11 | `setup.php:66` | Error Handling | `json_decode` de `editor.deps.json` sin validación. | S |
| C12 | `setup.php:233` | Error Handling | `WP_ENV` usado sin `defined()`. | S |
| C13 | `SeoProvider:296` + `Sitemap.php:90` | Duplicación | Defaults de priority/changefreq duplicados. Mover a constantes en `Sitemap`. | S |
| C14 | `Migration.php:110-206` | Testability | `handle()` 97 líneas estático + WP_Query directo + llamadas a funciones WP. Solo `mapYoastMeta()` es testeable. | M |
| C15 | `Index.php:17` | Complexity | Cache callback de ~60 líneas con anidamiento profundo. Extraer a servicio. | M |

### 🔵 LOW

| ID | Archivo:línea | Categoría | Resumen | Esfuerzo |
|----|---------------|-----------|---------|----------|
| D1 | `application.php:96` | Validation | Host header injection en `WP_HOME` (dev only, mitigado por `is_allowed_dev_host` en filters). | S |
| D2 | `Dockerfile:128` | Config | `GODEBUG=cgocheck=0` desactiva safety checks de Go runtime. | S |
| D3 | `MetaRenderer.php:93` | XSS | `double_encode: false` permite entidades pre-encoded pasen como están. | S |
| D4 | `setup.php:16` | Style | Bindings redundantes: `instance()` + `singleton()` para los mismos 3 contratos. | S |
| D5 | `setup.php:200` | Module Boundary | `save_post` con word count en setup.php. Mover a `ReadingTimeService`. | S |
| D6 | `Post.php:121` | Coupling | Dependencia directa en `WPSEO_Primary_Term`. | S |
| D7 | Varios | Type Safety | Múltiples closures sin return types. | L |
| D8 | Varios | Style | Inconsistencias: Yoda conditions, snake_case vs camelCase, FQCN sin import. | S |

### ⚪ INFO

| ID | Archivo:línea | Categoría | Resumen | Esfuerzo |
|----|---------------|-----------|---------|----------|
| E1 | `setup.php:229` | Secrets | Meta domain verification token hardcodeado en source. | S |
| E2 | `setup.php:236` | Validation | GA4/Meta Pixel IDs sin validación de formato antes de interpolar en JS. | S |
| E3 | Seo/ domain | Contracts | Sin interfaces para ninguna clase del módulo SEO. | S |
| E4 | Varios | Magic Numbers | Puerto 5174, cache TTL 3600, reading speed 200 WPM hardcodeados. | S |
| E5 | `Pest.php:29` | Test Quality | Stub `app()` frágil que devuelve null. Usar Orchestra Testbench. | M |
| E6 | Seo/Tests | Test Coverage | `SeoServiceProviderTest` solo verifica `method_exists`. `FiltersTest` solo cubre `is_allowed_dev_host`. | M |

---

## Cross-Cutting Concerns

1. **SeoServiceProvider como monolito** — Aparece en Seguridad (✅ limpio), Arquitectura (🔴 CRITICAL), y Clean Code (B3, C5, C7). Es el archivo con mayor densidad de issues. Refactor prioritario.

2. **Inconsistencia de patrón View Composer** — `Seo.php` renderiza HTML, `Post.php` e `Index.php` hacen queries directas a WP. Ningún composer sigue estrictamente el patrón "delegar a servicios, retornar data".

3. **SEO module quality gap** — Las clases puras (JsonLd, SeoMeta, Sitemap, TitleExpander) están bien diseñadas y con tests sólidos. El provider que las conecta y el CLI command están en el otro extremo.

4. **Seguridad ok en producción, vulnerable en dev** — Los 2 hallazgos MEDIUM (Host header injection + Vite en 0.0.0.0) son exclusivos de entorno de desarrollo, pero se combinan para permitir RCE vía scripts injectados.

5. **Brand rule violación** — Footer usa `text-slate-*` en vez de derivados OKLCH de Midnight/Orange. Posible regresión visual si no se alinea con `brandbook.md`.

---

## Recommended Next Steps

1. **Extraer SitemapController** de `SeoServiceProvider` (M)
2. **Extraer SeoMetaBox** de `SeoServiceProvider` (M)
3. **Crear HomepageService** para la lógica del Index composer (M)
4. **Agregar `is_allowed_dev_host` a `Vite::hotAsset()`** (S) y restringir Vite a loopback (S)
5. **Reemplazar `exit` por `wp_die()`** en sitemap handler (S)
6. **Extraer ReadingTimeService** y eliminar duplicación (S)
7. **Separar `the_content` filter** en funciones nombradas (M)
8. **Agregar interfaces** para módulo SEO (`SeoMetaInterface`, etc.) (S)
9. **Corregir `.dockerignore`** para excluir `.env*` (S)
10. **Reemplazar `text-slate-*`** con derivados OKLCH en footer (S)
11. **Agregar tests** para `SeoServiceProvider` (sitemap, meta save) y `Migration::handle()` (M)
12. **Arreglar `double_encode: false`** en MetaRenderer (S)
13. **Reemplazar stubs frágiles** de `Pest.php` con Orchestra Testbench (M)

---

## Audit Coverage

| Scope | Archivos revisados | Saltados | Cobertura |
|-------|--------------------|----------|-----------|
| Seguridad | 10 | 0 (ninguno > 500 loc) | 100% |
| Arquitectura | 22 | 0 | 100% |
| Clean Code | 26 | 0 | 100% |
| **Total** | **34** | **0** | **100%** |

Ningún archivo excedió 500 líneas, todos fueron auditados sin skips.
