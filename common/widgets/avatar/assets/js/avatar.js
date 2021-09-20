(function ($) {
	var jcrop_api;
	$.fn.cropper = function (options, width, height) {
		var $widget = $(this).closest('.avatar-widget'),
			cropper = {
				$widget: $widget,
				$avatar_image_container: $widget.find('.avatar__image-container'),
				$avatar_image: $widget.find('.avatar__image'),
				$photo_field: $widget.find('.photo-field'),
				$new_photo_area: $widget.find('.new-photo-area'),
				$avatar_chose: $widget.find('.avatar__container'),
				$avatar_add: $widget.find('.avatar__add'),
				$modal: $widget.find('#cropper-modal'),
				uploader: null,
				reader: null,
				selectedFile: null,
				data: null,
				init: function () {
					cropper.reader = new FileReader();
					cropper.reader.onload = function (e) {
						cropper.clearOldImg();
						cropper.$modal.modal('show');
						cropper.$modal.on("shown.bs.modal", function () {
							var img = new Image();
							img.src = e.target.result;
							img.onload = function () {
								cropper.$new_photo_area.html('<img src="' + e.target.result + '">');
								cropper.$img = cropper.$new_photo_area.find('img');
								var x1 = (img.width - width) / 2;
								var y1 = (img.width - height) / 2;
								var x2 = x1 + width;
								var y2 = y1 + height;
								console.log('box:', cropper.$new_photo_area.width());
								cropper.$img.Jcrop({
									aspectRatio: width / height,
									minSize: [100, 100],
									//setSelect: [x1, y1, x2, y2],
									setSelect: [0,0, width, height],
									boxWidth: cropper.$new_photo_area.width(),
									boxHeight: cropper.$new_photo_area.height(),
									keySupport: false,
									onSelect: cropper.updateData,
									onChange: cropper.updateData,
								}, function () {
									jcrop_api = this;
								});
							};
						});
					};
					var settings = $.extend({
						button: [
							cropper.$avatar_chose
						],
						responseType: 'json',
						noParams: true,
						multipart: true,
						onChange: function () {
							if (cropper.selectedFile) {
								cropper.selectedFile = null;
								cropper.uploader._queue = [];
							}
							return true;
						},
						onSubmit: function () {
							if (cropper.selectedFile) {
								return true;
							}
							cropper.selectedFile = cropper.uploader._queue[0];
							cropper.showError('');
							cropper.reader.readAsDataURL(this._queue[0].file);
							return false;
						},
						onComplete: function (filename, response) {
							if (response['error']) {
								cropper.showError(response['error']);
								return;
							}
							cropper.showError('');
							var src = response['avatar'] + '?' + Math.random().toString(36).substring(7);
							cropper.$avatar_image.attr({'src': src});
							cropper.$avatar_chose.removeClass('avatar__empty');
							cropper.$avatar_image_container.removeClass('hide');
							cropper.$avatar_add.addClass('hide');
							cropper.$photo_field.val(response['avatar']);
							cropper.$modal.modal('hide');
							if ((typeof options.onCompleteJcrop !== "undefined") && (typeof options.onCompleteJcrop === "string")) {
								eval('var onCompleteJcrop = ' + options.onCompleteJcrop);
								onCompleteJcrop(filename, response);
							}
						},
						onSizeError: function () {
							cropper.showError(options['size_error_text']);
						},
						onExtError: function () {
							cropper.showError(options['ext_error_text']);
						}
					}, options);
					cropper.uploader = new ss.SimpleUpload(settings);
					cropper.$widget
						.on('click', '.delete-photo', function () {
							cropper.deletePhoto();
						})
						.on('click', '.crop-photo', function () {
							var data = cropper.data;
							data[yii.getCsrfParam()] = yii.getCsrfToken();
							if (cropper.uploader._queue.length) {
								cropper.selectedFile = cropper.uploader._queue[0];
							} else {
								cropper.uploader._queue[0] = cropper.selectedFile;
							}
							cropper.uploader.setData(data);
							cropper.readyForSubmit = true;
							cropper.uploader.submit();
						});
				},
				showError: function (error) {
					if (error == '') {
						cropper.$widget.parents('.form-group').removeClass('has-error').find('.help-block').text('');
					} else {
						cropper.$widget.parents('.form-group').addClass('has-error').find('.help-block').text(error);
					}
					cropper.$modal.modal('hide');
				},
				deletePhoto: function () {
					cropper.$photo_field.val('');
					cropper.$avatar_image.attr({'src': cropper.$avatar_image.data('no-photo')});
				},
				clearOldImg: function () {
					if (cropper.$img) {
						cropper.$img.data('Jcrop').destroy();
						cropper.$img.remove();
						cropper.$img = null;
					}
				},
				updateData: function (e) {
					cropper.data = e;
				}
			};
		cropper.init();
	};
})(jQuery);
