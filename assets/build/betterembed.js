(function(){
	var NAMESPACE = 'wp-block-betterembed-embed';

	var SWITCH_CLASS        = NAMESPACE + '__switch';
	var SWITCH_CLASS_HIDDEN = NAMESPACE + '__switch--hidden';
	var CANCEL_CLASS        = NAMESPACE + '__cancel-remote';
	var LOAD_REMOTE_CLASS   = NAMESPACE + '__load-remote';
	var EMBED_CLASS         = NAMESPACE + '__embed';
	var EMBED_RAW_CLASS     = NAMESPACE + '__embed-raw';

	var REMOTE_VISIBLE_CLASS    = 'is-betterembed-remote-visible';
	var SHOW_DIALOG_CLASS       = 'is-betterembed-dialog-visible';

	var betterEmbeds = document.querySelectorAll('.' + NAMESPACE);

	Array.prototype.forEach.call(betterEmbeds, function(blockRoot, i){

		var switchButton   = blockRoot.querySelector('.' + SWITCH_CLASS);
		var cancelButton   = blockRoot.querySelector('.' + CANCEL_CLASS);
		var loadButton     = blockRoot.querySelector('.' + LOAD_REMOTE_CLASS);
		var embedContainer = blockRoot.querySelector('.' + EMBED_CLASS);
		var embedRaw       = blockRoot.querySelector('.' + EMBED_RAW_CLASS);

		if(!switchButton || !cancelButton || !loadButton || !embedContainer || !embedRaw) return;

		switchButton.classList.remove(SWITCH_CLASS_HIDDEN);

		switchButton.addEventListener('click', function(event) {
			// If the embedContainer is empty the embedHtml hasn't been loaded yet.
			var isConfirmed = embedContainer.innerHTML.trim();
			// If the embedHtml has already been loaded once we can skip the confirmation dialog.
			blockRoot.classList.toggle(isConfirmed ? REMOTE_VISIBLE_CLASS : SHOW_DIALOG_CLASS);
		});

		cancelButton.addEventListener('click', function(event) {
			blockRoot.classList.remove(SHOW_DIALOG_CLASS);
		});

		loadButton.addEventListener('click', function(event) {

			if(!embedContainer.innerHTML){
				// This is the first-time click
				embedContainer.innerHTML = JSON.parse(embedRaw.innerHTML);
				var scriptTags = embedContainer.querySelectorAll('script');

				// https://www.danielcrabtree.com/blog/25/gotchas-with-dynamically-adding-script-tags-to-html
				Array.prototype.forEach.call(scriptTags, function(scriptTag, i){
					//TODO: Check how this behaves with CSP restricting JS execution.
					var tag = document.createElement('script');
					if (scriptTag.src) {
						tag.src = scriptTag.src;
						blockRoot.appendChild(tag);
					} else {
						var inlineScript = document.createTextNode(scriptTag.innerHTML);
						tag.appendChild(inlineScript);
						blockRoot.appendChild(tag);
					}
				});
			}

			blockRoot.classList.add(REMOTE_VISIBLE_CLASS);
			blockRoot.classList	.remove(SHOW_DIALOG_CLASS);
		});

	});

})();
