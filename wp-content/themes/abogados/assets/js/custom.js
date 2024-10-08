document.addEventListener(
	"DOMContentLoaded", () => {
		new Mmenu("#menu", {
			"theme": "light",
			"slidingSubmenus": true,
			"offCanvas": {
				"position": "right-front"
			},
			"setSelected": {
				"hover": true
			},
		});
	}
);






