# Changelog

Toutes les évolutions notables de `nopost-theme` sont documentées ici.

Format inspiré de [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/), versionnage
[SemVer](https://semver.org/lang/fr/). La section `[Unreleased]` accumule au fil de l'eau et
est renommée en numéro de version au moment de poser le tag.

Ce fichier est créé le 2026-09-05, après la mise en service : les évolutions antérieures ne sont
pas reconstituées, ce qui serait de la réécriture d'historique plutôt que de la documentation.
L'historique git reste la source de vérité pour ce qui précède.

## [Unreleased]

## [1.3.0] - 2026-09-19

⛔ **Cette version existe parce que le site tournait encore en 1.2.3 alors que les
correctifs ci-dessous etaient dans le depot depuis le 3 septembre.** Le releve du
2026-09-18 a trouve `nopost.fr` appelant toujours `fonts.googleapis.com`. Deux causes
cumulees : aucun tag n'avait ete pose depuis, et **le workflow de release ne deploie
rien**, il publie une archive que quelqu'un doit installer. Un artefact que personne
n'installe laisse un site en retard indefiniment, sans qu'aucune CI ne vire au rouge.

### Sécurité

- **Les polices ne sont plus chargees chez Google** (commit du 2026-09-03, anterieur a ce
  fichier). EB Garamond et Cormorant Garamond viennent de `cdn.nopost.fr`. Une page qui
  charge une police depuis les serveurs de Google leur transmet l'**adresse IP de chaque
  visiteur**, ce qui a deja valu une condamnation (LG Munchen, 2022).
- ⚠️ Les deux `preconnect` vers Google restants etaient enregistres par `wp_enqueue_style`,
  donc emis en `<link rel="stylesheet" href="https://fonts.googleapis.com">` : le
  navigateur les demandait **vraiment** comme feuilles de style. Une indication de
  performance devenue une requete reelle.

### Corrigé

- **Erreur fatale sur la page de recherche** (`search.php`, commit du 2026-09-03,
  anterieur a ce fichier). Elle est restee en production tout ce temps pour la meme raison.

### Corrigé

- **Convention de fins de ligne du parc posée dans `.gitattributes`.** Le bloc `run:` d'un
  workflow GitHub Actions est un script shell exécuté sur un runner Linux : un antislash de
  continuation suivi d'un retour chariot **ne continue pas** la ligne, la commande est coupée en
  deux, et le message d'erreur ne parle jamais de fins de ligne.
- Cas réel du 2026-09-07 sur `bzhzion/cabanon` : un `.yml` recommité en CRLF depuis une machine
  Windows (où `core.autocrlf` est actif) a fait échouer le déploiement de l'API sur un
  `usage: ssh`, la destination de la commande ayant disparu avec la continuation.
- LF forcé sur ce qu'exécute Linux (`*.sh`, `*.yml`, `*.yaml`, `Dockerfile`), CRLF sur ce
  qu'exécute Windows (`*.ps1`, `*.bat`, `*.cmd`), et `* text=auto` comme filet général.
  Référence : `admin/.claude/gitattributes-parc`.


### Ajouté

- **Convention changelog du parc posée sur ce dépôt** : ce fichier, les hooks `pre-commit` et
  `pre-push` dans `.githooks/`, et le workflow `changelog-guard.yml` qui rejoue les mêmes
  contrôles en CI au moment du tag. Ce dépôt en était dépourvu alors qu'il est déployé, ce qui
  le laissait hors de la garantie que les autres ont.

