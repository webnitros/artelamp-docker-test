<?php
	/** @var modX $modx */
	$eventName = $modx->event->name;
	switch ($eventName) {
		case 'OnManagerPageBeforeRender':
			$assets      = rtrim($modx->getOption('assets_url', NULL, '/assets'), '/') . '/';
			$extraExtUrl = $assets . "components/extraext/";
			$modx->controller->addHtml(<<<HTML
<script defer async class="extraExt-plugin" type="text/javascript">
	setTimeout(async ()=>{
		var fontawesome = document.createElement( "link" );
		fontawesome.rel = "stylesheet";
		fontawesome.href = "{$extraExtUrl}css/fontawesome.min.css";
		document.head.insertBefore( fontawesome, document.head.childNodes[ document.head.childNodes.length - 1 ].nextSibling );
		var firacode = document.createElement( "link" );
		firacode.rel = "stylesheet";
		firacode.href = "{$extraExtUrl}css/firacode.min.css";
		document.head.insertBefore( firacode, document.head.childNodes[ document.head.childNodes.length - 1 ].nextSibling );
	},10)
</script>
HTML
			);
			unset($assets);
			break;
	}