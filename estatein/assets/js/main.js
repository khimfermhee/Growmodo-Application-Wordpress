/**
 * Estatein – front-end behaviour.
 *
 * Vanilla JS, no dependencies, loaded with `defer`. Every feature is
 * progressive enhancement: the site is fully usable without JavaScript.
 *
 * Modules: announcement · navigation · carousels · property gallery ·
 *          office filter · AJAX forms · FAQ deep links · scroll reveal
 */
( function () {
	'use strict';

	var data = window.estateinData || { ajaxUrl: '', i18n: {} };
	var i18n = data.i18n || {};
	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/** Zero-pad a number: 3 → "03". */
	function pad( n ) {
		return ( n < 10 ? '0' : '' ) + n;
	}

	/** Run fn for every element matching selector. */
	function each( selector, fn, root ) {
		Array.prototype.forEach.call( ( root || document ).querySelectorAll( selector ), fn );
	}

	/* ------------------------------------------------------------------
	 * Announcement bar – dismiss and remember (localStorage may throw).
	 * ---------------------------------------------------------------- */
	each( '[data-announcement-close]', function ( button ) {
		button.addEventListener( 'click', function () {
			document.documentElement.classList.add( 'announcement-dismissed' );
			try {
				window.localStorage.setItem( 'estatein-announcement', 'dismissed' );
			} catch ( e ) {}
		} );
	} );

	/* ------------------------------------------------------------------
	 * Mobile navigation.
	 * ---------------------------------------------------------------- */
	( function () {
		var header = document.getElementById( 'site-header' );
		var toggle = document.querySelector( '[data-nav-toggle]' );
		if ( ! header || ! toggle ) {
			return;
		}
		var label = toggle.querySelector( '[data-nav-toggle-label]' );
		var nav = document.getElementById( 'site-nav' );

		function setOpen( open ) {
			header.classList.toggle( 'is-open', open );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			if ( label ) {
				label.textContent = open ? i18n.close || 'Close menu' : i18n.open || 'Open menu';
			}
			if ( open ) {
				var first = nav && nav.querySelector( 'a' );
				if ( first ) {
					first.focus();
				}
			}
		}

		toggle.addEventListener( 'click', function () {
			setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && header.classList.contains( 'is-open' ) ) {
				setOpen( false );
				toggle.focus();
			}
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( header.classList.contains( 'is-open' ) && ! header.contains( event.target ) ) {
				setOpen( false );
			}
		} );

		// Close after following an in-page link.
		if ( nav ) {
			nav.addEventListener( 'click', function ( event ) {
				if ( event.target.closest( 'a' ) ) {
					setOpen( false );
				}
			} );
		}

		window.matchMedia( '(min-width: 1024px)' ).addEventListener( 'change', function ( mq ) {
			if ( mq.matches ) {
				setOpen( false );
			}
		} );
	}() );

	/* ------------------------------------------------------------------
	 * Carousels – CSS scroll-snap track, JS only adds paging buttons and
	 * the "01 of 10" counter. Touch/trackpad swiping works natively.
	 * ---------------------------------------------------------------- */
	function initCarousel( root ) {
		var track = root.querySelector( '[data-carousel-track]' );
		var prev = root.querySelector( '[data-carousel-prev]' );
		var next = root.querySelector( '[data-carousel-next]' );
		var current = root.querySelector( '[data-carousel-current]' );
		var total = root.querySelector( '[data-carousel-total]' );
		if ( ! track || ! track.children.length ) {
			return;
		}
		var slides = track.children;
		var ticking = false;

		function perView() {
			var slide = slides[ 0 ].getBoundingClientRect().width;
			var gap = parseFloat( getComputedStyle( track ).columnGap ) || 0;
			return Math.max( 1, Math.round( ( track.clientWidth + gap ) / ( slide + gap ) ) );
		}

		function pages() {
			return Math.max( 1, Math.ceil( slides.length / perView() ) );
		}

		function currentPage() {
			var max = track.scrollWidth - track.clientWidth;
			if ( max <= 1 ) {
				return 1;
			}
			if ( track.scrollLeft >= max - 2 ) {
				return pages();
			}
			var slide = slides[ 0 ].getBoundingClientRect().width;
			var gap = parseFloat( getComputedStyle( track ).columnGap ) || 0;
			var index = Math.round( track.scrollLeft / ( slide + gap ) );
			return Math.min( pages(), Math.floor( index / perView() ) + 1 );
		}

		function update() {
			ticking = false;
			var page = currentPage();
			var count = pages();
			if ( current ) {
				current.textContent = pad( page );
			}
			if ( total ) {
				total.textContent = pad( count );
			}
			if ( prev ) {
				prev.disabled = page <= 1;
			}
			if ( next ) {
				next.disabled = page >= count;
			}
			root.classList.toggle( 'is-static', count <= 1 );

			// Hide off-screen slides from keyboard focus order.
			var start = track.getBoundingClientRect().left - 2;
			var end = start + track.clientWidth + 4;
			Array.prototype.forEach.call( slides, function ( slide ) {
				var r = slide.getBoundingClientRect();
				var visible = r.left >= start && r.right <= end;
				slide.toggleAttribute( 'inert', ! visible );
			} );
		}

		function go( direction ) {
			var slide = slides[ 0 ].getBoundingClientRect().width;
			var gap = parseFloat( getComputedStyle( track ).columnGap ) || 0;
			track.scrollBy( {
				left: direction * perView() * ( slide + gap ),
				behavior: reduceMotion ? 'auto' : 'smooth',
			} );
		}

		if ( prev ) {
			prev.addEventListener( 'click', function () {
				go( -1 );
			} );
		}
		if ( next ) {
			next.addEventListener( 'click', function () {
				go( 1 );
			} );
		}

		track.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( update );
			}
		}, { passive: true } );

		window.addEventListener( 'resize', function () {
			window.requestAnimationFrame( update );
		} );

		update();
	}

	each( '[data-carousel]', initCarousel );

	/* ------------------------------------------------------------------
	 * Property gallery – thumbnails, arrows and dash indicators.
	 * ---------------------------------------------------------------- */
	each( '[data-gallery]', function ( root ) {
		var track = root.querySelector( '[data-gallery-track]' );
		var thumbs = root.querySelectorAll( '[data-gallery-thumb]' );
		var prev = root.querySelector( '[data-gallery-prev]' );
		var next = root.querySelector( '[data-gallery-next]' );
		var dotsWrap = root.querySelector( '[data-gallery-dots]' );
		if ( ! track ) {
			return;
		}
		var slides = track.children;
		var dots = [];

		function step() {
			var gap = parseFloat( getComputedStyle( track ).columnGap ) || 0;
			return slides[ 0 ].getBoundingClientRect().width + gap;
		}

		function perView() {
			return Math.max( 1, Math.round( ( track.clientWidth + 30 ) / step() ) );
		}

		function index() {
			return Math.round( track.scrollLeft / step() );
		}

		function maxIndex() {
			return Math.max( 0, slides.length - perView() );
		}

		function buildDots() {
			if ( ! dotsWrap ) {
				return;
			}
			dotsWrap.innerHTML = '';
			dots = [];
			for ( var i = 0; i <= maxIndex(); i++ ) {
				var dot = document.createElement( 'span' );
				dot.className = 'gallery__dot';
				dotsWrap.appendChild( dot );
				dots.push( dot );
			}
		}

		function goTo( i ) {
			i = Math.max( 0, Math.min( i, maxIndex() ) );
			track.scrollTo( { left: i * step(), behavior: reduceMotion ? 'auto' : 'smooth' } );
		}

		function update() {
			var i = Math.min( index(), maxIndex() );
			var view = perView();
			dots.forEach( function ( dot, d ) {
				dot.classList.toggle( 'is-active', d === i );
			} );
			Array.prototype.forEach.call( thumbs, function ( thumb, t ) {
				var active = t >= i && t < i + view;
				thumb.classList.toggle( 'is-active', active );
				thumb.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
			} );
			if ( prev ) {
				prev.disabled = i <= 0;
			}
			if ( next ) {
				next.disabled = i >= maxIndex();
			}
		}

		Array.prototype.forEach.call( thumbs, function ( thumb ) {
			thumb.addEventListener( 'click', function () {
				goTo( parseInt( thumb.getAttribute( 'data-gallery-thumb' ), 10 ) );
			} );
		} );
		if ( prev ) {
			prev.addEventListener( 'click', function () {
				goTo( index() - 1 );
			} );
		}
		if ( next ) {
			next.addEventListener( 'click', function () {
				goTo( index() + 1 );
			} );
		}

		var raf = false;
		track.addEventListener( 'scroll', function () {
			if ( ! raf ) {
				raf = true;
				window.requestAnimationFrame( function () {
					raf = false;
					update();
				} );
			}
		}, { passive: true } );

		window.addEventListener( 'resize', function () {
			buildDots();
			update();
		} );

		buildDots();
		update();
	} );

	/* ------------------------------------------------------------------
	 * Office filter (All / Regional / International).
	 * ---------------------------------------------------------------- */
	each( '[data-office-filter]', function ( root ) {
		var buttons = root.querySelectorAll( '[data-filter]' );
		var cards = root.querySelectorAll( '[data-type]' );
		Array.prototype.forEach.call( buttons, function ( button ) {
			button.addEventListener( 'click', function () {
				var filter = button.getAttribute( 'data-filter' );
				Array.prototype.forEach.call( buttons, function ( b ) {
					b.setAttribute( 'aria-pressed', b === button ? 'true' : 'false' );
				} );
				Array.prototype.forEach.call( cards, function ( card ) {
					card.hidden = filter !== 'all' && card.getAttribute( 'data-type' ) !== filter;
				} );
			} );
		} );
	} );

	/* ------------------------------------------------------------------
	 * Forms – inline validation + AJAX submit with graceful fallback.
	 * ---------------------------------------------------------------- */
	function fieldMessage( input ) {
		if ( input.validity.valueMissing ) {
			if ( input.type === 'checkbox' ) {
				return input.form.getAttribute( 'data-terms-message' ) || 'Please accept the Terms of Use and Privacy Policy.';
			}
			var label = input.form.querySelector( 'label[for="' + input.id + '"]' );
			var name = label ? label.textContent.replace( '(required)', '' ).trim() : 'This field';
			return name + ' is required.';
		}
		if ( input.validity.typeMismatch && input.type === 'email' ) {
			return 'Please enter a valid email address.';
		}
		return input.validationMessage;
	}

	function setError( form, name, message ) {
		var wrap = form.querySelector( '[data-field="' + name + '"]' );
		if ( ! wrap ) {
			return;
		}
		var error = wrap.querySelector( '.field__error' );
		var input = wrap.querySelector( 'input:not([type=hidden]), select, textarea' );
		wrap.classList.toggle( 'has-error', !! message );
		if ( error ) {
			error.textContent = message || '';
		}
		if ( input ) {
			if ( message ) {
				input.setAttribute( 'aria-invalid', 'true' );
			} else {
				input.removeAttribute( 'aria-invalid' );
			}
		}
	}

	function validate( form ) {
		var firstInvalid = null;
		each( 'input, select, textarea', function ( input ) {
			if ( ! input.name || input.type === 'hidden' || input.closest( '.hp-field' ) ) {
				return;
			}
			var wrap = input.closest( '[data-field]' );
			if ( ! wrap ) {
				return;
			}
			var valid = input.checkValidity();
			setError( form, wrap.getAttribute( 'data-field' ), valid ? '' : fieldMessage( input ) );
			if ( ! valid && ! firstInvalid ) {
				firstInvalid = input;
			}
		}, form );
		return firstInvalid;
	}

	each( '[data-ajax-form]', function ( form ) {
		var status = form.querySelector( '[data-form-status]' );

		// Clear an error as soon as the user fixes the field.
		form.addEventListener( 'input', function ( event ) {
			var wrap = event.target.closest( '[data-field]' );
			if ( wrap && wrap.classList.contains( 'has-error' ) && event.target.checkValidity() ) {
				setError( form, wrap.getAttribute( 'data-field' ), '' );
			}
		} );
		form.addEventListener( 'change', function ( event ) {
			var wrap = event.target.closest( '[data-field]' );
			if ( wrap && wrap.classList.contains( 'has-error' ) && event.target.checkValidity() ) {
				setError( form, wrap.getAttribute( 'data-field' ), '' );
			}
		} );

		form.addEventListener( 'submit', function ( event ) {
			if ( ! window.fetch || ! window.FormData || ! data.ajaxUrl ) {
				return; // Classic POST fallback.
			}
			event.preventDefault();

			var invalid = validate( form );
			if ( form.classList.contains( 'newsletter' ) && invalid ) {
				status.className = 'form-status form-status--error';
				status.textContent = fieldMessage( invalid );
				invalid.focus();
				return;
			}
			if ( invalid ) {
				invalid.focus();
				return;
			}

			form.classList.add( 'is-loading' );
			form.setAttribute( 'aria-busy', 'true' );
			status.className = 'form-status';
			status.textContent = i18n.sending || 'Sending…';

			window.fetch( data.ajaxUrl, {
				method: 'POST',
				body: new FormData( form ),
				credentials: 'same-origin',
			} )
				.then( function ( response ) {
					return response.json();
				} )
				.then( function ( json ) {
					status.className = 'form-status form-status--' + ( json.success ? 'success' : 'error' );
					status.textContent = json.message || '';
					if ( json.errors ) {
						Object.keys( json.errors ).forEach( function ( key ) {
							setError( form, key, json.errors[ key ] );
						} );
					}
					if ( json.success ) {
						form.reset();
					}
				} )
				.catch( function () {
					status.className = 'form-status form-status--error';
					status.textContent = i18n.error || 'Something went wrong. Please try again.';
				} )
				.finally( function () {
					form.classList.remove( 'is-loading' );
					form.removeAttribute( 'aria-busy' );
				} );
		} );
	} );

	// Typing into a "Preferred Contact Method" input selects that method.
	each( '.contact-method__option', function ( option ) {
		var text = option.querySelector( '.contact-method__text' );
		var radio = option.querySelector( '.contact-method__radio' );
		if ( text && radio ) {
			text.addEventListener( 'focus', function () {
				radio.checked = true;
			} );
		}
	} );

	/* ------------------------------------------------------------------
	 * FAQ page – open the answer linked from a card (#question-slug).
	 * ---------------------------------------------------------------- */
	function openFromHash() {
		if ( ! window.location.hash ) {
			return;
		}
		var target = document.getElementById( decodeURIComponent( window.location.hash.slice( 1 ) ) );
		if ( target && target.tagName === 'DETAILS' ) {
			target.open = true;
			target.querySelector( 'summary' ).focus( { preventScroll: true } );
		}
	}
	if ( document.querySelector( '[data-accordion]' ) ) {
		openFromHash();
		window.addEventListener( 'hashchange', openFromHash );
	}

	/* ------------------------------------------------------------------
	 * Scroll reveal for section content (skipped with reduced motion).
	 * ---------------------------------------------------------------- */
	if ( ! reduceMotion && 'IntersectionObserver' in window ) {
		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-visible' );
					observer.unobserve( entry.target );
				}
			} );
		}, { rootMargin: '0px 0px -8% 0px' } );

		each( '.section .section-heading, .section .carousel, .section .grid, .services-grid, .services-split, .values-grid, .inquiry-form, .property-details, .pricing__layout, .world', function ( el ) {
			var rect = el.getBoundingClientRect();
			if ( rect.top > window.innerHeight ) {
				el.classList.add( 'reveal' );
				observer.observe( el );
			}
		} );
	}
}() );
