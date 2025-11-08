# Geo + Lang Variables for PrestaShop

## Description courte (200 caractères max)
Détectez automatiquement le pays et la langue de vos visiteurs via Cloudflare ou GeoIP. Variables Smarty prêtes à l'emploi pour personnaliser votre boutique.

## Description complète

### Fonctionnalités principales
- ✅ Détection automatique du pays via Cloudflare (CF-IPCountry)
- ✅ Fallback intelligent sur GeoIP PrestaShop
- ✅ Détection de la langue active du visiteur
- ✅ Variables Smarty disponibles dans tous vos templates
- ✅ Page de configuration avec statut temps réel
- ✅ Compatible PrestaShop 8.0 à 9.x
- ✅ Performances optimales (pas de requête externe)
- ✅ Support multilingue (FR/EN)

### Cas d'usage

**Bannières promotionnelles par pays**
Affichez des promotions ciblées selon le pays du visiteur.

**Messages de réassurance localisés**
Adaptez vos messages de livraison, paiement et SAV par pays.

**Redirection intelligente**
Suggérez automatiquement la bonne langue selon le pays.

**Contenu personnalisé**
Adaptez vos textes, images et offres selon la localisation.

### Variables Smarty disponibles

- `{$visitor_country_iso}` - Code pays ISO (FR, US, GB, etc.)
- `{$visitor_lang_iso}` - Code langue active (fr, en, es, etc.)

### Installation

1. Téléchargez le module
2. Uploadez via Modules > Module Manager
3. Installez et configurez
4. Utilisez les variables dans vos templates !

### Configuration recommandée

**Option 1 : Cloudflare (Recommandé)**
- Performances maximales
- Précision optimale
- Activez "IP Geolocation" dans votre dashboard Cloudflare

**Option 2 : GeoIP PrestaShop**
- Activez la géolocalisation dans International > Localisation
- Fallback automatique si Cloudflare indisponible

### Support

Documentation complète incluse dans le module.
Support technique disponible via Addons.

### Compatibilité

- PrestaShop 8.0.x ✅
- PrestaShop 8.1.x ✅
- PrestaShop 9.0.x ✅
- PHP 7.2+ (Recommandé : PHP 8.1+)

### Licence

Academic Free License (AFL 3.0)