# Geo + Lang Variables for Smarty

![Version](https://img.shields.io/badge/version-2.0.0-blue.svg)
![PrestaShop](https://img.shields.io/badge/PrestaShop-8.0--9.x-orange.svg)
![License](https://img.shields.io/badge/license-AFL--3.0-green.svg)

Module PrestaShop qui assigne automatiquement le code pays ISO du visiteur et le code langue actuel dans des variables Smarty, avec support Cloudflare optimisé.

## 📋 Description

Ce module détecte automatiquement le pays du visiteur via :
1. **Cloudflare** (CF-IPCountry header) - Méthode recommandée
2. **GeoIP PrestaShop** - Fallback automatique
3. **Pays par défaut** - Fallback ultime

Les variables sont ensuite disponibles dans tous vos templates Smarty pour personnaliser l'affichage selon la localisation.

## ✨ Fonctionnalités

- ✅ Détection automatique du pays via Cloudflare (CF-IPCountry)
- ✅ Fallback sur GeoIP PrestaShop si Cloudflare indisponible
- ✅ Détection de la langue courante de l'utilisateur
- ✅ Variables Smarty disponibles dans tous les templates
- ✅ Page de configuration avec statut en temps réel
- ✅ Compatible PrestaShop 8.0 à 9.x
- ✅ Performances optimisées (détection par priorité)
- ✅ Gestion intelligente des erreurs
- ✅ Support multilingue

## 📦 Installation

### Méthode 1 : Via le back-office PrestaShop

1. Téléchargez le fichier `geolangvars.zip`
2. Allez dans **Modules > Module Manager**
3. Cliquez sur **Uploader un module**
4. Sélectionnez le fichier ZIP
5. Cliquez sur **Installer**
6. Profitez ! 🎉

### Méthode 2 : Via FTP/SFTP

1. Uploadez le dossier `geolangvars` dans `/modules/`
2. Allez dans **Modules > Module Manager**
3. Recherchez "Geo + Lang"
4. Cliquez sur **Installer**

## 🚀 Utilisation

### Variables Smarty disponibles

Une fois le module installé, deux variables sont automatiquement disponibles dans **tous vos templates** :

| Variable | Description | Exemple de valeur |
|----------|-------------|-------------------|
| `{$visitor_country_iso}` | Code pays ISO du visiteur | `FR`, `US`, `GB`, `BE`, etc. |
| `{$visitor_lang_iso}` | Code langue actuelle | `fr`, `en`, `es`, `de`, etc. |

### Exemples d'utilisation

#### 1. Affichage conditionnel selon le pays
```smarty
{* Dans header.tpl ou n'importe quel template *}

{if $visitor_country_iso == 'FR'}
    <div class="banner france">
        🇫🇷 Livraison gratuite en France métropolitaine !
    </div>
{elseif $visitor_country_iso == 'BE'}
    <div class="banner belgium">
        🇧🇪 Gratis verzending in België!
    </div>
{elseif $visitor_country_iso == 'US'}
    <div class="banner usa">
        🇺🇸 Free shipping across the United States!
    </div>
{/if}
```

#### 2. Affichage selon la langue
```smarty
{if $visitor_lang_iso == 'fr'}
    <p class="promo">Profitez de -20% avec le code PROMO20</p>
{elseif $visitor_lang_iso == 'en'}
    <p class="promo">Get 20% off with code PROMO20</p>
{/if}
```

#### 3. Combinaison pays + langue
```smarty
{if $visitor_country_iso == 'FR' && $visitor_lang_iso == 'fr'}
    {* Contenu spécifique pour visiteurs français francophones *}
    <div class="local-info">
        <h3>Nos magasins en France</h3>
        <p>Retrouvez-nous à Paris, Lyon et Marseille.</p>
    </div>
{/if}
```

#### 4. Classes CSS dynamiques
```smarty
<body class="country-{$visitor_country_iso|lower} lang-{$visitor_lang_iso}">
    {* Votre contenu *}
</body>
```

Résultat HTML :
```html
<body class="country-fr lang-fr">
```

Vous pouvez ensuite cibler dans votre CSS :
```css
.country-fr .shipping-info {
    background: blue;
}

.country-us .shipping-info {
    background: red;
}
```

#### 5. Messages de réassurance localisés
```smarty
{if $visitor_country_iso == 'FR'}
    <div class="trust-badges">
        <span>✓ Paiement 100% sécurisé</span>
        <span>✓ Livraison 48h</span>
        <span>✓ SAV français</span>
    </div>
{elseif $visitor_country_iso|in_array:['BE','LU','CH']}
    <div class="trust-badges">
        <span>✓ Livraison internationale</span>
        <span>✓ Support multilingue</span>
    </div>
{/if}
```

#### 6. Redirections automatiques
```smarty
{* Redirection automatique vers la bonne langue selon le pays *}
{if $visitor_country_iso == 'ES' && $visitor_lang_iso != 'es'}
    <div class="language-suggestion">
        <p>¿Prefieres ver esta página en español?</p>
        <a href="{$link->getLanguageLink(3)}">Cambiar a español</a>
    </div>
{/if}
```

#### 7. Debug (mode développement)
```smarty
{if $smarty.const._PS_MODE_DEV_}
    <div style="position: fixed; bottom: 10px; right: 10px; background: #333; color: #fff; padding: 10px; font-size: 12px; z-index: 9999; border-radius: 5px;">
        <strong>Debug Geo+Lang:</strong><br>
        Pays: <strong>{$visitor_country_iso|default:'N/A'}</strong><br>
        Langue: <strong>{$visitor_lang_iso|default:'N/A'}</strong>
    </div>
{/if}
```

## ⚙️ Configuration

### Accéder à la page de configuration

1. Allez dans **Modules > Module Manager**
2. Recherchez "Geo + Lang"
3. Cliquez sur **Configurer**

La page affiche :
- ✅ Pays détecté actuellement
- ✅ Langue active actuellement
- ✅ Méthode de détection utilisée (Cloudflare/GeoIP/Défaut)
- ✅ Statut Cloudflare
- ✅ Statut GeoIP PrestaShop
- ✅ Documentation d'utilisation

### Optimisation avec Cloudflare (recommandé)

Pour obtenir les meilleures performances :

1. **Créez un compte Cloudflare** (gratuit) : https://cloudflare.com
2. **Ajoutez votre domaine** à Cloudflare
3. **Activez IP Geolocation** :
    - Dashboard Cloudflare
    - Network
    - Activez "IP Geolocation"
4. Le header `CF-IPCountry` sera automatiquement ajouté à chaque requête

#### Avantages Cloudflare :
- ⚡ **Ultra rapide** : Pas de requête externe
- 🎯 **Précis** : Base de données GeoIP mise à jour en continu
- 🌍 **Global** : Réseau mondial de serveurs
- 🆓 **Gratuit** : Disponible sur le plan gratuit

### Alternative : GeoIP PrestaShop

Si vous n'utilisez pas Cloudflare :

1. Allez dans **International > Localisation**
2. Activez **Géolocalisation par adresse IP**
3. Configurez les options de géolocalisation

## 🔍 Méthodes de détection (ordre de priorité)
```
1. Cloudflare (CF-IPCountry header)
   ↓ si non disponible
2. GeoIP PrestaShop (service intégré)
   ↓ si non disponible
3. Pays par défaut de la boutique
```

## 📊 Compatibilité

| PrestaShop | Statut |
|------------|--------|
| 8.0.x | ✅ Testé |
| 8.1.x | ✅ Testé |
| 9.0.x | ✅ Compatible |

**PHP** : 7.2 minimum (recommandé : 8.1+)

## 🔧 Hooks utilisés

- `actionFrontControllerSetVariables` (principal pour PS 8-9)
- `displayHeader` (fallback pour compatibilité)

## 📁 Structure du module
```
geolangvars/
├── geolangvars.php              # Fichier principal
├── config.xml                    # Configuration
├── index.php                     # Sécurité
├── logo.png                      # Logo 128x128
├── README.md                     # Documentation
├── views/
│   └── templates/
│       └── admin/
│           └── info.tpl         # Template de configuration
├── translations/
│   └── fr.php                   # Traductions françaises
└── upgrade/
    └── install-2.0.0.php        # Script de mise à jour
```

## 🆙 Mise à jour depuis v1.x

La mise à jour est automatique :

1. **Uploadez la nouvelle version** via Module Manager
2. **Réinstallez** ou **Mettez à jour** le module
3. Le script `upgrade/install-2.0.0.php` s'exécute automatiquement
4. Les nouveaux hooks sont enregistrés
5. Vos configurations sont préservées

### Changements v2.0.0

- ✅ Compatibilité PrestaShop 8-9
- ✅ Nouveau hook `actionFrontControllerSetVariables`
- ✅ Page de configuration améliorée
- ✅ Détection optimisée
- ✅ Gestion d'erreurs renforcée
- ✅ Code refactorisé et documenté

## 🐛 Dépannage

### Le pays n'est pas détecté

**Problème** : `{$visitor_country_iso}` est vide ou affiche le pays par défaut

**Solutions** :
1. Vérifiez que Cloudflare est actif avec IP Geolocation
2. Activez la géolocalisation PrestaShop (International > Localisation)
3. Vérifiez dans la configuration du module quel système est actif
4. Testez depuis une vraie IP (pas localhost)

### Variables non disponibles dans le template

**Problème** : Les variables ne s'affichent pas

**Solutions** :
1. Videz le cache : **Paramètres avancés > Performance > Vider le cache**
2. Désactivez la compilation Smarty en mode dev
3. Vérifiez que le module est installé et activé
4. Testez avec `{$visitor_country_iso|@var_dump}` pour voir le contenu

### Cloudflare détecté mais pays incorrect

**Problème** : Cloudflare est actif mais le pays est faux

**Solutions** :
1. Vérifiez que IP Geolocation est activée dans Cloudflare
2. Purgez le cache Cloudflare
3. Testez depuis une IP différente
4. Vérifiez les headers avec : `var_dump($_SERVER['HTTP_CF_IPCOUNTRY']);`

### Performance lente

**Problème** : Le site charge lentement

**Solutions** :
1. Utilisez Cloudflare (plus rapide que GeoIP)
2. Activez le cache Smarty
3. Désactivez le mode debug en production

## 💡 Cas d'usage avancés

### Bannière promotionnelle par pays
```smarty
{capture name="country_promo"}
    {if $visitor_country_iso == 'FR'}
        🎉 Black Friday : -50% sur tout le site !
    {elseif $visitor_country_iso == 'US'}
        🎉 Black Friday: 50% OFF on everything!
    {elseif $visitor_country_iso == 'DE'}
        🎉 Black Friday: 50% Rabatt auf alles!
    {else}
        🎉 Black Friday: Special offers!
    {/if}
{/capture}

<div class="promo-banner">
    {$smarty.capture.country_promo}
</div>
```

### Formulaire de contact adapté
```smarty
<form action="{$urls.pages.contact}" method="post">
    {if $visitor_country_iso == 'FR'}
        <input type="tel" name="phone" placeholder="Téléphone (ex: 06 12 34 56 78)">
    {elseif $visitor_country_iso == 'US'}
        <input type="tel" name="phone" placeholder="Phone (e.g., (555) 123-4567)">
    {else}
        <input type="tel" name="phone" placeholder="Phone">
    {/if}
    
    {* Reste du formulaire *}
</form>
```

### Affichage de devises selon le pays
```smarty
{if $visitor_country_iso|in_array:['FR','BE','LU','DE','ES','IT']}
    {* Zone Euro *}
    <span class="price">{$product.price} €</span>
{elseif $visitor_country_iso == 'GB'}
    <span class="price">£{$product.price_gbp}</span>
{elseif $visitor_country_iso == 'US'}
    <span class="price">${$product.price_usd}</span>
{/if}
```

### Analytics et tracking
```smarty
<script>
    // Envoi à Google Analytics
    gtag('event', 'page_view', {
        'country': '{$visitor_country_iso}',
        'language': '{$visitor_lang_iso}'
    });
    
    // Données disponibles en JavaScript
    window.geoData = {
        country: '{$visitor_country_iso}',
        language: '{$visitor_lang_iso}'
    };
</script>
```

## 📝 Changelog

### Version 2.0.0 (2025-01-08)
- ✨ Compatibilité PrestaShop 8.0 à 9.x
- ✨ Nouveau hook `actionFrontControllerSetVariables`
- ✨ Page de configuration avec statut temps réel
- ✨ Code refactorisé et optimisé
- ✨ Gestion d'erreurs améliorée
- ✨ Documentation complète

### Version 1.6.0
- ✅ Support Cloudflare CF-IPCountry
- ✅ Fallback GeoIP
- ✅ Variables Smarty de base

## 👤 Auteur

**Stephane Geraut**

## 📄 Licence

[Academic Free License (AFL 3.0)](https://opensource.org/licenses/AFL-3.0)

## 🤝 Support

Pour toute question ou problème :
1. Consultez la section **Dépannage** ci-dessus
2. Vérifiez la **page de configuration** du module
3. Activez le **mode debug** pour voir les valeurs
4. Contactez le support

## 🌟 Contribuer

Les contributions sont les bienvenues ! N'hésitez pas à proposer des améliorations.

---

**Made with ❤️ for PrestaShop community**