# Guide d'installation - Geo + Lang Variables

## Installation standard

### 1. Via le back-office PrestaShop

1. Téléchargez le fichier ZIP du module
2. Connectez-vous à votre back-office PrestaShop
3. Allez dans **Modules > Module Manager**
4. Cliquez sur **Uploader un module**
5. Sélectionnez le fichier ZIP
6. Cliquez sur **Installer**
7. ✅ Le module est prêt !

### 2. Via FTP/SFTP

1. Décompressez le fichier ZIP
2. Uploadez le dossier `geolangvars` dans `/modules/`
3. Connectez-vous au back-office
4. Allez dans **Modules > Module Manager**
5. Recherchez "Geo + Lang"
6. Cliquez sur **Installer**

## Configuration

### Avec Cloudflare (Recommandé)

1. Créez un compte Cloudflare (gratuit)
2. Ajoutez votre domaine
3. Activez **IP Geolocation** :
    - Dashboard > Network > IP Geolocation > ON
4. Le module détectera automatiquement le header CF-IPCountry

### Sans Cloudflare

1. Allez dans **International > Localisation**
2. Activez **Géolocalisation par adresse IP**
3. Configurez les options
4. Le module utilisera GeoIP PrestaShop

## Utilisation dans vos templates

### Exemple 1 : Bannière par pays
```smarty
{if $visitor_country_iso == 'FR'}
    <div class="banner">🇫🇷 Livraison gratuite en France !</div>
{elseif $visitor_country_iso == 'BE'}
    <div class="banner">🇧🇪 Gratis verzending in België!</div>
{/if}
```

### Exemple 2 : Contenu par langue
```smarty
{if $visitor_lang_iso == 'fr'}
    <p>Contenu en français</p>
{else}
    <p>English content</p>
{/if}
```

## Vérification

Après installation :

1. Allez dans **Modules > Module Manager**
2. Recherchez "Geo + Lang"
3. Cliquez sur **Configurer**
4. Vérifiez que votre pays et langue sont détectés

## Dépannage

### Le pays n'est pas détecté

**Solution** :
- Activez Cloudflare ou GeoIP PrestaShop
- Videz le cache (Performance > Vider le cache)
- Testez depuis une vraie IP (pas localhost)

### Variables non disponibles

**Solution** :
- Vérifiez que le module est installé et activé
- Videz le cache Smarty
- Testez avec `{$visitor_country_iso|@var_dump}`

## Support

Pour toute question, consultez la documentation ou contactez le support via PrestaShop Addons.
```

### ✅ Étape 4 : Tests obligatoires

#### 4.1 - Test sur différentes versions PS

Testez votre module sur :
- ✅ PrestaShop 8.0.5
- ✅ PrestaShop 8.1.7
- ✅ PrestaShop 9.0.0

#### 4.2 - Test de compatibilité thème

Testez avec :
- ✅ Thème Classic (par défaut)
- ✅ Au moins 1 thème tiers populaire

#### 4.3 - Test fonctionnel

- ✅ Installation/désinstallation
- ✅ Détection Cloudflare (si disponible)
- ✅ Détection GeoIP
- ✅ Fallback sur pays par défaut
- ✅ Variables disponibles dans templates
- ✅ Page de configuration accessible
- ✅ Pas d'erreurs PHP
- ✅ Pas de warnings dans les logs

### ✅ Étape 5 : Préparer le package final

#### 5.1 - Structure finale du module
```
geolangvars/
├── geolangvars.php               # Fichier principal avec header AFL
├── config.xml                     # Configuration
├── index.php                      # Sécurité
├── logo.png                       # Logo 128x128
├── LICENSE.txt                    # Licence AFL 3.0 complète
├── README.md                      # Documentation GitHub
├── INSTALLATION.md                # Guide d'installation
├── CHANGELOG.md                   # Historique des versions
├── views/
│   ├── index.php
│   └── templates/
│       ├── index.php
│       └── admin/
│           ├── index.php
│           └── info.tpl          # Template configuration
├── translations/
│   ├── index.php
│   ├── fr.php                    # Traduction française
│   └── en.php                    # Traduction anglaise (à créer)
└── upgrade/
├── index.php
└── install-2.0.0.php         # Script de mise à jour