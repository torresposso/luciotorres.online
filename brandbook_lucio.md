# Manual de Estrategia de Producto, Identidad y Sistema de Diseño — Edison Lucio Torres

Este documento constituye el **Brandbook Estratégico (v3)** y el sistema de diseño del producto digital oficial de **Edison Lucio Torres Moreno**. 

Ha sido auditado y estructurado bajo la perspectiva de un **Product Manager Senior especializado en Periodismo Digital y Marketing Político**. Su propósito es transformar la plataforma en un motor de conversión política, movilización cívica y difusión periodística de alto impacto en Colombia, de cara a la campaña de 2026.

---

## 1. Posicionamiento Estratégico y Propuesta de Valor

En el saturado ecosistema de medios y política en Colombia, la plataforma se posiciona como una **alternativa disruptiva e independiente**.

```
                           [ TRADICIONAL ]
                    Medios Oligopólicos / Centralismo
                                  │
                                  ▼
                     [ LA CRISIS DE CREDIBILIDAD ]
                     Polarización / Desinformación
                                  │
                                  ▼
                   [ LA PLATAFORMA DE LUCIO TORRES ]
               Deconstrucción Mental + Propuesta "Pan con Paz"
```

### Propuesta de Valor (Value Proposition)
> *"El único canal independiente en el Caribe colombiano que deconstruye la manipulación mediática centralista y ofrece una alternativa cívica real (Pan con Paz) para superar la polarización."*

---

## 2. Segmentación de Audiencias y Personas (User Personas)

Para que el producto digital sea efectivo, el diseño y los copys deben hablarle directamente a tres perfiles clave de usuarios:

### A. El Ciudadano Desilusionado (The Skeptic Voter)
* **Perfil:** Habitante de las regiones (Caribe principalmente), cansado de la corrupción de los clanes tradicionales y de las promesas incumplidas.
* **Necesidad:** Encontrar propuestas reales, pragmáticas y sin sesgos ideológicos extremos.
* **Acción Clave en la Web:** Consumir la sección **"Pan con Paz"** y registrar su apoyo digital.

### B. El Multiplicador de Opinión (The Community Leader)
* **Perfil:** Periodistas locales, líderes comunitarios, docentes y jóvenes universitarios activos en redes.
* **Necesidad:** Acceso a datos duros, investigaciones independientes y marcos conceptuales robustos.
* **Acción Clave en la Web:** Compartir investigaciones por WhatsApp y redes, y suscribirse al boletín estratégico.

---

## 3. Embudos de Conversión (Core Funnels)

Como producto digital, el éxito del sitio se mide a través de embudos de conversión claros:

```
[ Atracción: Redes/SEO ] ──► [ Interés: Artículos/Opinión ] ──► [ Conversión: Lead Form ] ──► [ Movilización: Voluntario ]
```

1. **Embudo de Información Cívica (Lectura activa):**
   * *Entrada:* Tráfico orgánico desde redes sociales (X, Instagram) o SEO de noticias.
   * *Retención:* Tiempo de permanencia optimizado mediante una lectura cómoda (Inter Font, tipografía amplia, espaciado premium).
2. **Embudo de Conversión Política (Captura de apoyos):**
   * *Acción:* Suscripción al comité de **"La Gran Colombia"** para recibir propuestas directas y sumarse a la recolección de firmas.
   * *Métrica Clave (KPI):* Tasa de conversión del formulario (CVR) > 8%.

---

## 4. Guía Editorial y Voz de Marca

### Líneas Rojas (Lo que NO somos)
* **NO somos amarillistas:** Las denuncias se sustentan en documentos y hechos, no en rumores de pasillo.
* **NO somos polarizadores:** Se ataca al sistema ("la dictadura del Ego") y al bogocentrismo, no a las bases ciudadanas del contrario.
* **NO somos aburridos ni rígidos:** Explicamos conceptos filosóficos (como el "Yo Soy") de forma práctica y cercana.

### Directrices de Redacción
* **Titulares:** Directos, que generen urgencia o inviten a la reflexión (ej. *"Los colombianos merecemos una segunda oportunidad"*).
* **Botones (CTAs):** Orientados a la acción cívica (ej. *"Súmate a la Gran Colombia"*, *"Descubre Pan con Paz"*).

---

## 5. Arquitectura del Sistema de Diseño (Tailwind CSS v4 & DaisyUI v5)

Los colores y estilos están diseñados para reflejar solemnidad y disrupción a través de un contraste estudiado.

### A. Paleta de Colores Estratégicos (Design Tokens)

```css
@theme {
  /* Colores Base en OKLCH */
  --color-brand-midnight: oklch(20.31% 0.031 269.4);  /* Midnight: Solemnidad presidencial y contraste */
  --color-brand-orange: oklch(66.06% 0.199 38.6);     /* Orange: Lealtad, acción y "Pan con Paz" (Para CTA grandes/texto bold) */
}
```
