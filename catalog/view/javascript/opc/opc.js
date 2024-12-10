(function($) {
	var OnePageCheckout = function(options) {

		var self = this;
		self.$elem = $('#onepcheckout');
		self.options = options;
		self.$sfields = $('.checkout_form input[type=\'text\'], .checkout-address input[type=\'radio\'], .checkout-address select', self.$elem);
		self.clickSelectors = '.cart-list .btn, [id^="button-"],input[name="register"]';

		self.init = function() {
			self.response();
			self.initSelect2();
			self.authorization();
			self.attachEventHandlers();
			self.saveFields();

			$(document).ajaxComplete(function( event, xhr, settings ) {
				if ( settings.url === "index.php?route=checkout/cart/remove" || settings.url === "index.php?route=checkout/cart/add" ) {
					self.opcReloadAll();
				}
			});
		};

		self.attachEventHandlers = function(){

			if ($('select[name=\'country_id\']').length > 0) {
				$('select[name=\'country_id\']').trigger('change');
			}

			self.$elem.on('click', self.clickSelectors, function (e) {

				e.preventDefault();
				var $target = $(this);

				if ($target.hasClass('btn') && $target.closest('.cart-list').length > 0) {
					var action = $target.data('action');
					if (action === 'minus' || action === 'plus') {
						self.plusMinusQty($target, action);
					} else if (action === 'remove') {
						self.removeProduct($target.data('key'));
					}
				} else if ($target.attr('id') && $target.attr('id').startsWith('button-')) {
					var buttonId = $target.attr('id');
					if (buttonId === 'button-coupon') {
						self.handleCouponButtonClick();
					} else if (buttonId === 'button-reward') {
						self.handleRewardButtonClick();
					} else if (buttonId === 'button-voucher') {
						self.handleVoucherButtonClick();
					} else if (buttonId === 'button-register') {
						self.opcValidateForm();
					}
				} else if ($target.attr('name') === 'register') {
					self.customerUpdate();
				}
			});

			$(document).on('change', self.sfields, function () {
				self.saveFields();
			});

			$(document).on('change', 'select[name=\'country_id\'], select[name=\'zone_id\'], input[name=\'shipping_method\'], input[name=\'payment_method\']', function(e) {
				e.preventDefault();
				if (this.name == 'contry_id') {
					$("select[name=\'zone_id\']").val("");
					self.getZones(this.value);
				} else if(this.name == 'payment_method'){
					$('.payment').empty().addClass('hidden');
					$('#button-register').show();
					$('#button-confirm').remove();
					self.updateCart();
				} else {
					self.opcReloadAll();
				}
			});

			var inputTimeout;
			$(document).on('input', '.cart-item-price-quantity .form-control', function () {
				var input = this;
				clearTimeout(inputTimeout);
				inputTimeout = setTimeout(function() {
					self.opcValidateQty(input);
				}, 600);
			});

			self.initMaskPhone();
			self.initDateTimePicker();
		};

		self.opcValidateForm = function(){
			var data = $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked,.checkout-totals input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select').serialize();
			data += '&_shipping_method='+ $('.checkout_form input[name=\'shipping_method\']:checked').prop('title') + '&_payment_method=' + $('.checkout_form input[name=\'payment_method\']:checked').prop('title');

			$.ajax({
				url: 'index.php?route=checkout/onepcheckout/validate',
				type: 'post',
				data: data,
				dataType: 'json',
				beforeSend: function() {
					$('.ch-alert-danger').remove();
					$('#button-register').button('loading');
					self.loading_mask(true);
				},
				complete: function() {
					$('#button-register').button('reset');
				},
				success: function(json) {
					$('.alert,.opc-text-error').remove();
					$('.form-control').removeClass('error_input_checkout');
					$('.control-label').removeClass('error_input_checkout');
					$('#onepcheckout .form-group').find('input').removeClass('error');
					$('#onepcheckout .form-group').find('.form-error').text('');

					if (json['error']) {
						self.loading_mask(false);
						for (i in json['error']) {
							if (i.includes('custom_field')) {
								// $('#input-' + i.replaceAll('_', '-')).after('<div class="opc-text-error">'+ json['error'][i] +'</div>');
								$('#input-' + i.replaceAll('_', '-')).closest('.form-group').find('input').addClass('error');
								$('#input-' + i.replaceAll('_', '-')).closest('.form-group').find('.form-error').html(json['error'][i]);
								$('#input-' + i.replaceAll('_', '-')).closest('.form-group').find('.control-label').addClass('error_input_checkout');
							} else {
								// $('[name="' + i + '"]').closest('.form-group').find('.control-label').after('<div class="opc-text-error">'+ json['error'][i] +'</div>');
								$('[name="' + i + '"]').closest('.form-group').find('input').addClass('error');
								$('[name="' + i + '"]').closest('.form-group').find('.form-error').html(json['error'][i]);
								$('[name="' + i + '"]').closest('.form-group').find('.control-label').addClass('error_input_checkout');

							}
						}

						var errorElement = $('.control-label.error_input_checkout').first();

						if (errorElement.length > 0) {
							$('html, body').animate({
								scrollTop: errorElement.offset().top - 240
							}, 'slow');
						}

						var arr = [];

						for (i in json['error']) {
							arr.push(json['error'][i]);
						}

						var time_a = 5000;
						var index = -1;
						var timer = setInterval(function () {
						if (++index == arr.length) {
							clearInterval(timer);
						} else {
							(function (currentIndex) {
								var block_alert = $('<div class="alert ch-alert-danger alert-' + currentIndex + '"><img class="warning-icon" alt="warning-icon" src="catalog/view/javascript/opc/image/warning-icon.svg"><div class="text-modal-block">' + arr[currentIndex] + '</div><button type="button" class="close" data-dismiss="alert"></button></div>');
								$('body').append(block_alert);
								setTimeout(() => {
								$(`.ch-alert-danger.alert-${currentIndex}`).remove();
								}, time_a);
							})(index);
						}
						time_a = time_a + 1000;
						}, 10);
					}

					if (json['success']) {
						$('#button-register').hide();
						$('.payment').empty();
						$('.payment').html(json['success']['payment']);

						if ($('.payment h2, .payment p, .payment form, .payment .proposition').length) {

							if( $('.payment').find('#button-confirm').length ){
								$('.confirm-block').prepend( $('.payment').find('#button-confirm').addClass('w-100') );
							}
							setTimeout(function() {
								$('.payment').removeClass('hidden');
								$('html, body').animate({ scrollTop: $('.payment').offset().top - document.querySelector('header').clientHeight - 50}, 250);
							}, 300);

							$('#button-confirm').click(function(e) {
								e.preventDefault();

								var $paymentForm = $('.payment form');
								if ($paymentForm.length > 0) {
									$paymentForm.submit();
								}
							});

							self.loading_mask(false);
						} else {
							$('.payment').css('display', 'none');
							setTimeout(function() {
								$('.payment #button-confirm, .payment input[type=\'submit\'], .payment button, .payment a, .payment input[type=\'button\'], .payment .btn-primary').click();
							}, 300);
							if($('.payment a').length) {
								$('.payment a')[0].click();
							}
						}
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		};

		self.opcReloadAll = function(){
			var data = $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked,.checkout-totals input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select').serialize();
			
			$.ajax({
				url: 'index.php?route=checkout/onepcheckout/reloadAll',
				type: 'post',
				data: data,
				dataType: 'json',
				cache: false,
				beforeSend: function() {
					$('.ch-alert-danger').remove();
					self.loading_mask(true);
				},
				complete: function() {
					self.loading_mask(false);
				},
				success: function(json) {
					if(json['redirect']){
						location = json['redirect'];
					} else {
						for (var key in json) {
							switch (key) {
								case 'shipping_method':
									if(json.shipping_method){
										$('.opc_block_shipping_method').html(json.shipping_method);
									} else {
										location = 'index.php?route=checkout/cart';
									}
									break;
								case 'shipping_address':
									if($(json.shipping_address).find('.checkout-address-info .row').length > 0){
										$('.opc_block_shipping_address').html(json.shipping_address);
										$('.opc_block_shipping_address').removeClass('hidden');
									} else {
										$('.opc_block_shipping_address').addClass('hidden');
									}
									break;
								case 'payment_method':
									if(json.payment_method !== ''){
										$('.opc_block_payment_method').html(json.payment_method);
									}
									break;
								case 'customer':
									$('.opc_block_customer').html(json.customer);
									self.initMaskPhone();
									break;
								case 'cart':
									$(".cart-list").html($(json.cart).find(".cart-list").html());
									new Function(self.options.load_script)();
									break;
								case 'totals':
									if($(".opc-cart-weight").length){
										$(".opc-cart-weight").html($(json.totals).find(".opc-cart-weight").html());
									}
									// $(".table_total").html($(json.totals).find(".table_total").html());
									$(".checkout-totals-inner").html($(json.totals).find(".checkout-totals-inner").html());
									break;
							}
						}

						//#++
						self.initSelect2();
						//#++

						self.initDateTimePicker();
					}
				}
			});
		};

		self.shippingUpdate = function(){
			var data = $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked,.checkout-totals input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select').serialize();

			$.ajax({
				url: 'index.php?route=checkout/onepcheckout/shipping_method',
				type: 'post',
				data: data,
				dataType: 'html',
				cache: false,
				beforeSend: function() {
					self.loading_mask(true);
				},
				complete: function() {
					self.loading_mask(false);
				},
				success: function(html) {
					if(html.length){
						$('.opc_block_shipping_method').html(html);
					} else {
						location = 'index.php?route=checkout/cart';
					}
				}
			});
		};

		self.paymentUpdate = function(){
			var data = $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked,.checkout-totals input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select').serialize();

			$.ajax({
				url: 'index.php?route=checkout/onepcheckout/payment_method',
				type: 'post',
				data: data,
				dataType: 'html',
				cache: false,
				beforeSend: function() {
					self.loading_mask(true);
				},
				complete: function() {
					self.loading_mask(false);
				},
				success: function(html) {
					$('.opc_block_payment_method').html(html);
				}
			});
		};

		self.updateCart = function(){
			var data = $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select');

			$.ajax({
				url: 'index.php?route=checkout/onepcheckout/cart',
				type: 'post',
				data: data,
				dataType: 'html',
				cache: false,
				success: function(html) {
					$(".table_total").html($(html).find(".table_total").html());
					$(".cart-list").html($(html).find(".cart-list").html());
					$(".panel-group").html($(html).find(".panel-group").html());
					new Function(self.options.load_script)();
				}
			});
		};

		self.shippingAddressUpdate = function(){
			var data = $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked,.checkout-totals input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select').serialize();

			$.ajax({
				url: 'index.php?route=checkout/onepcheckout/shipping_address',
				type: 'post',
				data: data,
				dataType: 'html',
				cache: false,
				complete: function() {
					loading_mask(false);
				},
				success: function(html) {
					if($(html).find('.checkout-address-info .row').length){
						$('.opc_block_shipping_address').html(html);
						$('.opc_block_shipping_address').removeClass('hidden');
					} else {
						$('.opc_block_shipping_address').addClass('hidden');
					}
					self.initDateTimePicker();
				}
			}).done(function() {
				if (typeof initSelect2 == 'function') {
					initSelect2();
				}
			});
		};

		self.customerUpdate = function(){
			var data = $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked,.checkout-totals input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select').serialize();
			$.ajax({
				url: 'index.php?route=checkout/onepcheckout/customer',
				type: 'post',
				data: data,
				dataType: 'html',
				cache: false,
				beforeSend: function() {
					self.loading_mask(true);
				},
				complete: function() {
					self.loading_mask(false);
				},
				success: function(data) {
					$('.opc_block_customer').html(data);
					self.initMaskPhone();
					self.initDateTimePicker();
				}
			});
		};

		self.getZones = function(value){
			$.ajax({
				url: 'index.php?route=checkout/onepcheckout/country&country_id=' + value,
				dataType: 'json',
				success: function(json) {

					html = '<option value="">'+ self.options.text_select +'</option>';

					if (json['zone'] && json['zone'] != '') {
						for (i = 0; i < json['zone'].length; i++) {
							html += '<option value="' + json['zone'][i]['zone_id'] + '"';

							if (json['zone'][i]['zone_id'] == json['active_zone_id']) {
								html += ' selected="selected"';
							}

							html += '>' + json['zone'][i]['name'] + '</option>';
						}
					}
					$('select[name=\'zone_id\']').html(html);
					self.shippingUpdate();
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		};

		self.initMaskPhone = function(){
			if(self.options.tel_mask.length){
				$("#input-opc-telephone").mask(self.options.tel_mask);
			}
		};

		self.initDateTimePicker = function(){
			$('.date').each(function() {
				$(this).datetimepicker({
					pickTime: false,
					minDate: new Date()
				});
			});

			$('.time').each(function() {
				$(this).datetimepicker({
					pickDate: false
				});
			});

			$('.datetime').each(function() {
				$(this).datetimepicker({
					pickDate: true,
					pickTime: true
				});
			});
		};

		self.handleCouponButtonClick = function(){
			$.ajax({
				url: 'index.php?route=extension/total/coupon/coupon',
				type: 'post',
				data: 'coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
				dataType: 'json',
				beforeSend: function() {
					$('input[name=\'coupon\']').attr('disabled', 'disabled');
				},
				complete: function() {
					$('input[name=\'coupon\']').removeAttr('disabled');
				},
				success: function(json) {
					$('.alert').remove();
					self.opcReloadAll();
					if (json['error']) {
						$('input[name=\'coupon\']').val('');
						$('body').append('<div class="alert ch-alert-danger"><img class="warning-icon" alt="warning-icon" src="catalog/view/javascript/opc/image/warning-icon.svg"><div class="text-modal-block">' + json['error'] + '</div><button type="button" class="close" data-dismiss="alert"></button></div>');
					}
					if (json['success']) {
						$('body').append('<div class="alert ch-alert-success"><img class="success-icon" alt="success-icon" src="catalog/view/javascript/opc/image/success-icon.svg"><div class="text-modal-block">' + json['success'] + '</div><button type="button" class="close" data-dismiss="alert"></button></div>');
					}
				}
			});
		};

		self.handleRewardButtonClick = function(){
			$.ajax({
				url: 'index.php?route=extension/total/reward/reward',
				type: 'post',
				data: 'reward=' + encodeURIComponent($('input[name=\'reward\']').val()),
				dataType: 'json',
				beforeSend: function() {
					$('input[name=\'reward\']').attr('disabled', 'disabled');
				},
				complete: function() {
					$('input[name=\'reward\']').removeAttr('disabled');
				},
				success: function(json) {
					$('.alert').remove();

					if (json['error']) {
						$('body').append('<div class="alert ch-alert-danger"><img class="warning-icon" alt="warning-icon" src="catalog/view/javascript/opc/image/warning-icon.svg"><div class="text-modal-block">' + json['error'] + '</div><button type="button" class="close" data-dismiss="alert"></button></div>');
					}
					if (json['success']) {
						$('body').append('<div class="alert ch-alert-success"><img class="success-icon" alt="success-icon" src="catalog/view/javascript/opc/image/success-icon.svg"><div class="text-modal-block">' + json['success'] + '</div><button type="button" class="close" data-dismiss="alert"></button></div>');
					}
					self.opcReloadAll();
				}
			});
		};

		self.handleVoucherButtonClick = function(){
			$.ajax({
				url: 'index.php?route=extension/total/voucher/voucher',
				type: 'post',
				data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
				dataType: 'json',
				beforeSend: function() {
					$('input[name=\'voucher\']').attr('disabled', 'disabled');
				},
				complete: function() {
					$('input[name=\'voucher\']').removeAttr('disabled');
				},
				success: function(json) {
					$('.alert').remove();
					self.opcReloadAll();
					if (json['error']) {
						$('body').append('<div class="alert ch-alert-danger"><img class="warning-icon" alt="warning-icon" src="catalog/view/javascript/opc/image/warning-icon.svg"><div class="text-modal-block">' + json['error'] + '</div><button type="button" class="close" data-dismiss="alert"></button></div>');
					}
					if (json['success']) {
						$('body').append('<div class="alert ch-alert-success"><img class="success-icon" alt="success-icon" src="catalog/view/javascript/opc/image/success-icon.svg"><div class="text-modal-block">' + json['success'] + '</div><button type="button" class="close" data-dismiss="alert"></button></div>');
					}
				}
			});
		};

		self.plusMinusQty = function(elem, action){
			var $parent = elem.closest('.ch-cart-quantity');

			var key = $parent.find('input').data('key');
			var minimum = parseFloat($parent.find('input').data('minimum'));
			minimum = minimum < 1 ? 1 : minimum;
			var quantity = parseFloat($parent.find('input').val().replace(/[^\d]/g, ''));

			if (quantity === '' || quantity === 0) {
				quantity = minimum;
			} else if (action === 'plus') {
				quantity += minimum;
			} else if (action === 'minus') {
				if (quantity <= minimum) {
					quantity = minimum;
				} else {
					quantity -= minimum;
				}
			}

			$parent.find('input').val(quantity).change();
			self.updateQty(key, quantity, minimum);
		};

		self.updateQty = function(key, quantity, minimum = 1){

			if(quantity >= minimum){
				$.ajax({
					url: 'index.php?route=checkout/onepcheckout/cart_edit',
					type: 'post',
					data: 'quantity[' + key + ']='+ quantity,
					dataType: 'json',
					beforeSend: function() {
						self.loading_mask(true);
					},
					complete: function() {
						self.loading_mask(false);
					},
					success: function(json) {
						self.opcReloadAll();
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}

		self.opcValidateQty = function(elem) {
			var input = $(elem);

			var minimum = input.data('minimum');
			var value = input.val().trim();
			var key = input.data('key');

			if (/^0/.test(value)) {
				input.val(minimum);
			} else {
				var count = value.replace(/[^\d]/g, '');
				if (count === '') count = minimum;
				if (count === '0') count = minimum;
				if (count < minimum) count = minimum;
				input.val(count);
			}

			input.change();
			self.updateQty(key, count, minimum);
		};

		self.removeProduct = function(key){
			$.ajax({
				url: 'index.php?route=checkout/cart/remove',
				type: 'post',
				data: 'key=' + key,
				dataType: 'json',
				beforeSend: function() {
					self.loading_mask(true);
				},
				complete: function() {
					self.loading_mask(false);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		};

		self.saveFields = function(){
			$.ajax({
				url: 'index.php?route=checkout/onepcheckout/save_fields',
				type: 'post',
				data: $('.checkout_form input[type=\'text\']:not([id*=\'input_pr_quantity_\']), .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select'),
				cache: false,
				//# ++
				success:function (json) {
					if (json['city'] == '') {
						$('.form-group-address-1').addClass('opacity-0-height-1').find('[name="address_1"]').addClass('focus');
					} else {
						$('.form-group-address-1').removeClass('opacity-0-height-1');
					}
				}
				//# ++
			});
		};

		self.initSelect2 = function () {
			$('.opc_block_shipping_address').find("select[data-type=select2]").each(function() {
				$(this).select2();
			});
		};

		self.authorization = function () {
			$(document).on('click', '.opc_login', function (e) {
				e.preventDefault();
				$.ajax({
					type:'get',
					url:'index.php?route=checkout/onepcheckout/authorization',
					beforeSend: function() {
						self.loading_mask(true);
					},
					complete: function() {
						self.loading_mask(false);
					},
					success:function (data) {
						$('html body').append('<div id="login-form-popup" class="modal fade" role="dialog">'+ data +'</div>');
						$('#login-form-popup').modal('show');
						self.validateAuthorization();
						$(document).on('hide.bs.modal', '#login-form-popup.modal.fade', function () {
							$('#login-form-popup').remove();
						});
					}
				});
			});
		};

		self.validateAuthorization = function () {
			$(document).on('click', '#button-login-popup', function (e) {
				e.preventDefault();
				$.ajax({
					url: 'index.php?route=checkout/onepcheckout/validate_authorization',
					type: 'post',
					data: $('#opc_authorization input'),
					dataType: 'json',
					beforeSend: function() {
						self.loading_mask(true);
					},
					complete: function() {
						self.loading_mask(false);
					},
					success: function(json) {
						$('.alert.ch-alert-danger').remove();

						if(json['islogged']){
							window.location.href="index.php?route=account/account";
						}
						if (json['error']) {
							$('body').append('<div class="alert ch-alert-danger"><img class="success-icon" alt="success-icon" src="catalog/view/javascript/opc/image/warning-icon.svg"><div class="text-modal-block">' + json['error'] + '</div><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}

						setTimeout(function () {
							$('.ch-alert-danger').remove();
						}, 3000);

						if(json['success']){
							location.reload();
							$('#login-form-popup').modal('hide');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			});
		};

		self.addTopCartRight = function () {
			if(self.viewport().width > 991){
				if($('header').hasClass('fix-header')){
					$('.checkout-col-fix-right').css('top', document.querySelector('header').clientHeight + 30);
				} else {
					$('.checkout-col-fix-right').css('top', 30);
				}
			} else {
				$('.checkout-col-fix-right').css('top', 0);
			}
		};

		self.response = function () {
			var base = this,
			smallDelay,
			lastWindowWidth;

			lastWindowWidth = $(window).width();
			self.addTopCartRight();
			base.resizer = function () {
				if ($(window).width() !== lastWindowWidth) {

					window.clearTimeout(smallDelay);
					smallDelay = window.setTimeout(function () {
						lastWindowWidth = $(window).width();
						self.addTopCartRight();
					}, 200);
				}
			};
			$(window).resize(base.resizer);
		};

		self.loading_mask = function(action){
			if (action) {
				if(!$('.loading_mask').length){
					$('body').append('<div class="loading_mask"></div>');
				}
				$('.loading_mask').html('<div class="center-body"><div class="opc-loader-circle"></div></div>');
				$('.loading_mask').show();
			} else {
				$('.loading_mask').html('');
				$('.loading_mask').hide();
			}
		};

		self.viewport = function(){
			let e = window, a = 'inner';
			if (!('innerWidth' in window )) {
				a = 'client';
				e = document.documentElement || document.body;
			}
			return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
		}
	}

	window.OnePageCheckout = OnePageCheckout;
})(jQuery);
