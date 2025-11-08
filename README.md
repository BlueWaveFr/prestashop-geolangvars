# Geo + Lang Variables for PrestaShop

![Version](https://img.shields.io/badge/version-2.3.0-blue.svg)
![PrestaShop](https://img.shields.io/badge/PrestaShop-8.0--9.x-orange.svg)
![License](https://img.shields.io/badge/license-AFL--3.0-green.svg)

Module PrestaShop qui assigne automatiquement le code pays ISO du visiteur et le code langue actuel dans des variables Smarty, avec support Cloudflare optimisé.

**Par Bluewave - Stéphane Géraut**

## 📋 Description

Ce module détecte automatiquement le pays du visiteur via :
1. **Cloudflare** (CF-IPCountry header) - Méthode recommandée
2. **GeoIP PrestaShop** - Fallback automatique
3. **Pays par défaut** - Fallback ultime

Les variables sont ensuite disponibles dans tous vos templates Smarty pour personnaliser l'affichage selon la localisation.

## ✨ Fonctionnalités

### Version 2.3.0 - Nouveau ! 🎉

- ✅ **Tableau de bord statistiques** - Analysez les détections par pays et méthode
- ✅ **Paramètres avancés** - Contrôlez quelles méthodes de détection utiliser
- ✅ **Gestion GeoIP intégrée** - Activez la géolocalisation en un clic
- ✅ **Upload de base GeoIP** - Importez votre fichier GeoIP directement
- ✅ **Interface à onglets** - Navigation intuitive (Status, Stats, Settings, GeoIP)
- ✅ **Collecte de statistiques** - Données anonymes (désactivable)
- ✅ **Nettoyage automatique** - Suppression des vieilles stats

### Fonctionnalités de base

- ✅ Détection automatique du pays via Cloudflare (CF-IPCountry)
- ✅ Fallback sur GeoIP PrestaShop si Cloudflare indisponible
- ✅ Détection de la langue courante de l'utilisateur
- ✅ Variables Smarty disponibles dans tous les templates
- ✅ Page de configuration avec statut en temps réel
- ✅ Compatible PrestaShop 8.0 à 9.x
- ✅ Performances optimisées (pas de requête externe)
- ✅ Support multilingue (FR/EN)

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

## ⚙️ Configuration

### Accéder à la page de configuration

**Via le menu International (Recommandé)**
1. Allez dans **International**
2. Cliquez sur **"Geo + Lang Variables"**
3. Accédez aux 4 onglets de configuration

### Onglets disponibles

#### 1️⃣ Status
- Détection en temps réel de votre pays/langue
- État des services (Cloudflare, GeoIP)
- Exemples de code Smarty
- Recommandations

#### 2️⃣ Statistics
- Top 10 des pays détectés (30 derniers jours)
- Répartition par méthode de détection
- Évolution quotidienne des détections
- Bouton de nettoyage des anciennes stats

#### 3️⃣ Settings
- **Activer/désactiver les statistiques**
- **Période de rétention** (combien de jours garder les stats)
- **Méthodes de détection** :
   - ☑️ Cloudflare (priorité 1)
   - ☑️ PrestaShop GeoIP (priorité 2)
   - ☑️ Pays par défaut (priorité 3)

#### 4️⃣ GeoIP Setup
- **Activation en un clic** de la géolocalisation PrestaShop
- **Upload de fichier GeoIP** (.dat ou .mmdb)
- Liste des fichiers GeoIP installés
- Lien direct vers MaxMind pour télécharger GeoLite2

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

## 📊 Statistiques

Le module collecte des statistiques anonymes pour vous aider à comprendre :
- **Quels pays** visitent votre boutique
- **Quelle méthode de détection** fonctionne le mieux
- **L'évolution** des détections dans le temps

### Données collectées (anonymes)

- Code pays ISO
- Code langue
- Méthode de détection utilisée
- Date de détection

### Données NON collectées

- ❌ Adresse IP complète (seulement stockée temporairement)
- ❌ Données personnelles
- ❌ Comportement de navigation
- ❌ Informations de compte utilisateur

### Désactiver les statistiques

1. Allez dans **International > Geo + Lang Variables**
2. Onglet **Settings**
3. Désactivez **"Enable Statistics"**
4. Sauvegardez

## 🔧 Configuration avancée

### Ordre de priorité des méthodes

Par défaut :
```
1. Cloudflare (si activé et disponible)
   ↓
2. PrestaShop GeoIP (si activé et configuré)
   ↓
3. Pays par défaut (si activé)
```

Vous pouvez désactiver individuellement chaque méthode dans **Settings**.

### Configuration recommandée

**Pour les meilleurs résultats** :
1. ✅ Utilisez **Cloudflare** (gratuit, rapide, précis)
2. ✅ Activez **GeoIP** comme fallback
3. ✅ Gardez le **fallback par défaut** activé

**Si vous n'utilisez pas Cloudflare** :
1. ✅ Activez **PrestaShop GeoIP**
2. ✅ Uploadez une base **GeoLite2** (gratuite)
3. ✅ Gardez le **fallback par défaut** activé

### Upload de base GeoIP

1. Créez un compte gratuit sur [MaxMind](https://www.maxmind.com/en/geolite2/signup)
2. Téléchargez **GeoLite2 Country** (.mmdb)
3. Allez dans **International > Geo + Lang Variables > GeoIP Setup**
4. Uploadez le fichier
5. Cliquez sur **"Enable Geolocation Now"**

## 📊 Compatibilité

| PrestaShop | Statut |
|------------|--------|
| 8.0.x | ✅ Testé |
| 8.1.x | ✅ Testé |
| 9.0.x | ✅ Compatible |

**PHP** : 7.2 minimum (recommandé : 8.1+)

## 🔍 Dépannage

### Le pays n'est pas détecté

**Problème** : `{$visitor_country_iso}` est vide

**Solutions** :
1. Vérifiez le **Status** dans la configuration
2. Activez Cloudflare avec IP Geolocation
3. OU activez GeoIP et uploadez une base de données
4. Vérifiez que au moins une méthode est activée dans **Settings**

### Les statistiques ne s'affichent pas

**Problème** : L'onglet Statistics est vide

**Solutions** :
1. Vérifiez que **"Enable Statistics"** est activé (Settings)
2. Attendez quelques visites sur votre site
3. Vérifiez que la table `ps_geolangvars_stats` existe en base

### Erreur lors de l'upload GeoIP

**Problème** : Échec de l'upload du fichier

**Solutions** :
1. Vérifiez que le fichier est bien `.dat` ou `.mmdb`
2. Vérifiez les permissions du dossier `/app/Resources/geoip/`
3. Vérifiez la taille max d'upload PHP (`upload_max_filesize`)

## 📝 Changelog

Voir [CHANGELOG.md](CHANGELOG.md) pour l'historique complet des versions.

## 👤 Auteur

**Bluewave - Stéphane Géraut**

- GitHub: [@votre-username](https://github.com/votre-username)
- Site: [bluewave.example.com](https://bluewave.example.com)

## 📄 Licence

[Academic Free License (AFL 3.0)](https://opensource.org/licenses/AFL-3.0)

## 🤝 Support

Pour toute question ou problème :
1. Consultez la section **Dépannage** ci-dessus
2. Vérifiez l'onglet **Status** du module
3. Activez le **mode debug** pour voir les valeurs
4. Ouvrez une [issue sur GitHub](https://github.com/votre-username/prestashop-geolangvars/issues)

## 🌟 Contribuer

Les contributions sont les bienvenues ! N'hésitez pas à proposer des améliorations.

---

**Made with ❤️ by Bluewave - Stéphane Géraut**
```

---

## 📁 Étape 8 : Structure finale du module v2.3.0
```
geolangvars/
├── geolangvars.php                                (v2.3.0 - Bluewave)
├── config.xml                                     (v2.3.0)
├── LICENSE.txt
├── README.md                                      (mis à jour)
├── CHANGELOG.md                                   (mis à jour)
├── INSTALLATION.md
├── index.php
├── logo.png
├── controllers/
│   ├── index.php
│   └── admin/
│       ├── index.php
│       └── AdminGeoLangVarsController.php        (v2.3.0 - avec 4 onglets)
├── views/
│   ├── index.php
│   └── templates/
│       ├── index.php
│       └── admin/
│           ├── index.php
│           ├── configure.tpl                      (v2.3.0 - navigation)
│           └── tabs/
│               ├── index.php                      ← NOUVEAU
│               ├── status.tpl                     ← NOUVEAU
│               ├── stats.tpl                      ← NOUVEAU
│               ├── settings.tpl                   ← NOUVEAU
│               └── geoip.tpl                      ← NOUVEAU
├── translations/
│   ├── index.php
│   ├── fr.php
│   └── en.php
└── upgrade/
├── index.php
├── install-2.0.0.php
├── install-2.2.0.php
└── install-2.3.0.php                          ← NOUVEAU