{"@FILE snippets/setRequest.php"|snippet:['text'=>$_modx->resource['introtext']]}
{set $crumb = '@FILE chunks/common/_crumbs.tpl'|chunk}
{$_modx->setPlaceholder('_crumbs', $crumb)}
{$_modx->setPlaceholder('_content', $_modx->resource.content?$_modx->resource.content:' ')}
{$modx->sendForward(2)}
