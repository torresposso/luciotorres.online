# Project Context & Architecture Guidelines (luciotorres.online)

This repository is a WordPress site powered by Bedrock and Roots Sage 11.

## Tech Stack & Conventions
- **Framework**: Roots Acorn (Laravel components in WordPress: View Composers, Service Providers, Blade templates).
- **View Composers**: Located in `web/app/themes/luciotorres/app/View/Composers/`. Bind data to templates. Register them in Service Providers or theme setup hooks.
- **Blade Components**: Explicitly register class-based Blade components in `app/setup.php`. The class MUST actually exist in the project to avoid container resolution failures.
- **Services & CLI**: Custom services and WP-CLI commands (located under `app/Seo/`) must be registered in Laravel-style Service Providers under `app/Providers/`.
- **Bundler**: Vite is used for asset compilation and hot reloading.
- **Design System & Colors (Strict OKLCH)**: We strictly use only two base brand colors: **Midnight** and **Orange**, both defined and manipulated using **OKLCH** coordinates. All other semantic values (hover states, texts, bases) are derived by adjusting the lightness or chroma of these two colors. Never introduce new base colors (like Gold or Slate) or hardcoded hex colors. Refer to [brandbook.md](file:///home/erick/Projects/webs/luciotorres.online/brandbook.md) for specs.

## Docker Dev Environment (Host Isolation - MANDATORY)
All dependencies and the stack are strictly managed by Docker. Do NOT run PHP, Composer, Node, npm, or WP-CLI tools directly on the host machine.

- **Start Environment**: `docker compose up -d`
- **Stop Environment**: `docker compose down`
- **Composer (Root)**: `docker compose exec app composer install` or `docker compose exec app composer require <package>`
- **Composer (Theme)**: `docker compose exec app composer --working-dir=web/app/themes/luciotorres install`
- **Node / NPM**: `docker compose run --rm node npm install` or `docker compose run --rm node npm run dev`
- **WP-CLI**: `docker compose exec app wp <command>`
- **Laravel Pint (Code Formatting)**: Managed inside Docker via `.docker/pint.sh` wrapper, which translates host paths to container paths (`/app`) for seamless VS Code integration.
- **PHP Validation & Linting**: Local PHP validation is disabled (`php.validate.enable: false` in `.vscode/settings.json`) to prevent host dependency issues. Rely on the Docker environment for linting and execution.

---

<!-- gitnexus:start -->
# GitNexus — Code Intelligence

This project is indexed by GitNexus as **luciotorres.online** (542 symbols, 1127 relationships, 16 execution flows). Use the GitNexus MCP tools to understand code, assess impact, and navigate safely.

> If any GitNexus tool warns the index is stale, run `npx gitnexus analyze` in terminal first.

## Always Do

- **MUST run impact analysis before editing any symbol.** Before modifying a function, class, or method, run `gitnexus_impact({target: "symbolName", direction: "upstream"})` and report the blast radius (direct callers, affected processes, risk level) to the user.
- **MUST run `gitnexus_detect_changes()` before committing** to verify your changes only affect expected symbols and execution flows.
- **MUST warn the user** if impact analysis returns HIGH or CRITICAL risk before proceeding with edits.
- When exploring unfamiliar code, use `gitnexus_query({query: "concept"})` to find execution flows instead of grepping. It returns process-grouped results ranked by relevance.
- When you need full context on a specific symbol — callers, callees, which execution flows it participates in — use `gitnexus_context({name: "symbolName"})`.

## Never Do

- NEVER edit a function, class, or method without first running `gitnexus_impact` on it.
- NEVER ignore HIGH or CRITICAL risk warnings from impact analysis.
- NEVER rename symbols with find-and-replace — use `gitnexus_rename` which understands the call graph.
- NEVER commit changes without running `gitnexus_detect_changes()` to check affected scope.

## Resources

| Resource | Use for |
|----------|---------|
| `gitnexus://repo/luciotorres.online/context` | Codebase overview, check index freshness |
| `gitnexus://repo/luciotorres.online/clusters` | All functional areas |
| `gitnexus://repo/luciotorres.online/processes` | All execution flows |
| `gitnexus://repo/luciotorres.online/process/{name}` | Step-by-step execution trace |

## CLI

| Task | Read this skill file |
|------|---------------------|
| Understand architecture / "How does X work?" | `.claude/skills/gitnexus/gitnexus-exploring/SKILL.md` |
| Blast radius / "What breaks if I change X?" | `.claude/skills/gitnexus/gitnexus-impact-analysis/SKILL.md` |
| Trace bugs / "Why is X failing?" | `.claude/skills/gitnexus/gitnexus-debugging/SKILL.md` |
| Rename / extract / split / refactor | `.claude/skills/gitnexus/gitnexus-refactoring/SKILL.md` |
| Tools, resources, schema reference | `.claude/skills/gitnexus/gitnexus-guide/SKILL.md` |
| Index, status, clean, wiki CLI commands | `.claude/skills/gitnexus/gitnexus-cli/SKILL.md` |

<!-- gitnexus:end -->
