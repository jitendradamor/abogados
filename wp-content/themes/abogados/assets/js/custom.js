document.addEventListener(
	"DOMContentLoaded", () => {
		new Mmenu("#mobile-menu", {
			"theme": "light",
			"slidingSubmenus": true,
			"offCanvas": {
				"position": "left-front"
			},
			"setSelected": {
				"hover": true
			},
		});
	}
);