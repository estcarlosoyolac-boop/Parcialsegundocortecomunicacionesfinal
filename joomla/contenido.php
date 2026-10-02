<?php
// Crea el articulo de portada de Joomla (solo si no existe).
// Usa las mismas variables de entorno que el instalador automatico.
$hostPort = getenv('JOOMLA_DB_HOST') ?: 'database:5432';
[$host, $port] = array_pad(explode(':', $hostPort, 2), 2, '5432');
$db     = getenv('JOOMLA_DB_NAME') ?: 'joomla';
$user   = getenv('JOOMLA_DB_USER') ?: 'joomla';
$pass   = getenv('JOOMLA_DB_PASSWORD') ?: '';
$prefix = getenv('JOOMLA_DB_PREFIX') ?: 'jos_';
$alias  = 'portal-parcial-2';

$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass,
               [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$q = $pdo->prepare("SELECT count(*) FROM {$prefix}content WHERE alias = ?");
$q->execute([$alias]);
if ((int) $q->fetchColumn() > 0) {
    fwrite(STDERR, "[parcial] Articulo de portada ya existe.\n");
    exit(0);
}

$html = file_get_contents('/parcial/portada.html');
$images = json_encode([
    'image_intro' => 'images/parcial/portada.svg', 'image_intro_alt' => 'Portal Mecatronica UMNG',
    'float_intro' => '', 'image_intro_caption' => '',
    'image_fulltext' => '', 'image_fulltext_alt' => '', 'float_fulltext' => '', 'image_fulltext_caption' => '',
]);

$pdo->beginTransaction();
$ins = $pdo->prepare("INSERT INTO {$prefix}content
  (asset_id, title, alias, introtext, \"fulltext\", state, catid, created, created_by, created_by_alias,
   modified, modified_by, publish_up, images, urls, attribs, version, ordering, metakey, metadesc,
   access, hits, metadata, featured, language, note)
  VALUES (0, ?, ?, ?, '', 1, 2, NOW(), 0, 'Grupo Parcial 2', NOW(), 0, NOW(), ?, '{}', '{}', 1, 0, '', '',
   1, 0, '{\"robots\":\"\",\"author\":\"\",\"rights\":\"\"}', 1, '*', '')
  RETURNING id");
$ins->execute(['Portal del Parcial 2 — Comunicaciones', $alias, $html, $images]);
$id = (int) $ins->fetchColumn();

$pdo->prepare("INSERT INTO {$prefix}content_frontpage (content_id, ordering) VALUES (?, 1)")->execute([$id]);
$pdo->prepare("INSERT INTO {$prefix}workflow_associations (item_id, stage_id, extension) VALUES (?, 1, 'com_content.article')")->execute([$id]);
$pdo->commit();
fwrite(STDERR, "[parcial] Articulo de portada creado (id $id).\n");
