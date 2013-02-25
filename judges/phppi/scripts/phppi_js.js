var phppi = {

	//Vars
	image_width: 0,
	image_height: 0,
	up_folder: "",
	prev_image: "",
	next_image: "",
	title_full_path: 0,
	enable_hotkeys: 0,
	enable_up_hotkey: 0,
	site_name: "",
	page_title: "",
	page_title_format: "",
	current_file: 0,
	files: [],

	img: "",
	win: "",
	cnt: "",

	initialize: function() {
		phppi.img = $("#image");
		phppi.win = $(window);
		phppi.cnt = $("#page-image-container");

		phppi.img.hide();
		phppi.cnt.css('height', '350px');

		phppi.img.load(function() {
			$(window).unbind("resize").resize(function() {
				phppi.resize();
			});

			phppi.cnt.css('height', '');

			phppi.img.show();
			phppi.resize();
		});
	},

	go_next_image: function() {
		if (phppi.current_file != (phppi.files.length-1))
		{
			phppi.current_file++;

			var image = $("#image");

			phppi.img.hide();
			phppi.cnt.css('height', '350px');

			temp_image = new Image();
			temp_image.onload = function() {
				phppi.image_width = phppi.files[phppi.current_file][2];
				phppi.image_height = phppi.files[phppi.current_file][3];

				phppi.cnt.css('height', '');

				phppi.img.show();
				phppi.resize();

				image.attr("src", phppi.files[phppi.current_file][0]);

				phppi.show_title();
			}
			temp_image.src = phppi.files[phppi.current_file][0];
		}
	},

	go_prev_image: function() {
		if (phppi.current_file != 0)
		{
			phppi.current_file--;

			var image = $("#image");

			phppi.img.hide();
			phppi.cnt.css('height', '350px');

			temp_image = new Image();
			temp_image.onload = function() {
				phppi.image_width = phppi.files[phppi.current_file][2];
				phppi.image_height = phppi.files[phppi.current_file][3];

				phppi.cnt.css('height', '');

				phppi.img.show();
				phppi.resize();

				image.attr("src", phppi.files[phppi.current_file][0]);

				phppi.show_title();
			}
			temp_image.src = phppi.files[phppi.current_file][0];
		}
	},

	resize: function() {
		var pad = ((phppi.cnt.outerWidth(true) - phppi.cnt.innerWidth()) + parseInt(phppi.cnt.css('padding-left')) + parseInt(phppi.cnt.css('padding-right')));

		if (phppi.image_width >= (phppi.win.width() - pad)) {
			phppi.img.width(phppi.win.width() - pad);
		} else {
			phppi.img.width(phppi.image_width);
		}
	},

	show_title: function() {
		var str = phppi.page_title_format;

		if (phppi.title_full_path == 1) {
			phppi.page_title = '/' + phppi.files[phppi.current_file][0];
		} else {
			phppi.page_title = phppi.files[phppi.current_file][1];
		}

		str = str.replace('[S]', phppi.site_name);
		str = str.replace('[P]', phppi.page_title);

		$(document).attr("title", str);
		$('#page-title').html(str);
	}

};

$(document).keydown(function(e){
    if (e.keyCode == 37 && phppi.enable_hotkeys == 1)
    {
       if (phppi.prev_image != '') { document.location = phppi.prev_image; }
       return false;
    }
    if (e.keyCode == 38 && phppi.enable_up_hotkey == 1)
    {
       document.location = phppi.up_folder;
       return false;
    }
    if (e.keyCode == 39 && phppi.enable_hotkeys == 1)
    {
       if (phppi.next_image != '') { document.location = phppi.next_image; }
       return false;
    }
});