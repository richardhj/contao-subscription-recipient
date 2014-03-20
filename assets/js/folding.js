$(document).addEvent('domready', function() {
	$$('.tl_listing img.expand')
		.setStyles({ 'display': 'inline' })
		.addEvent('click', function(e) {
			this.setStyles({ 'display': 'none' });
			this.getAllNext('img.fold').setStyles({ 'display': 'inline' });
			this.getAllNext('table.subscriptions').setStyles({ 'display': 'table' });
			e.stopPropagation();
		});
	$$('.tl_listing img.fold')
		.addEvent('click', function(e) {
			this.setStyles({ 'display': 'none' });
			this.getAllPrevious('img.expand').setStyles({ 'display': 'inline' });
			this.getAllNext('table.subscriptions').setStyles({ 'display': 'none' });
			e.stopPropagation();
		});
	if (location.hash) {
		var anchor = $$('a[name="' + location.hash.substr(1) + '"]');
		if (anchor.length) {
			anchor = anchor[0];
			anchor.getAllPrevious('img.fold').setStyles({ 'display': 'inline' });
			anchor.getAllPrevious('img.expand').setStyles({ 'display': 'none' });
			anchor.getAllNext('table.subscriptions').setStyles({ 'display': 'table' });
		}
	}
});
