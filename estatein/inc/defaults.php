<?php
/**
 * Default content taken 1:1 from the Figma design.
 *
 * This is the single source of truth for design copy:
 *  - templates fall back to it when a field is empty (the theme renders the
 *    full design even before any content is entered), and
 *  - the demo importer writes it into real posts, terms and fields.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get a default value by dot path, e.g. estatein_default( 'home.hero_title' ).
 *
 * @param string $path    Dot separated key path.
 * @param mixed  $default Value returned when the path does not exist.
 * @return mixed
 */
function estatein_default( $path, $default = null ) {
	$value = estatein_defaults();
	foreach ( explode( '.', $path ) as $key ) {
		if ( ! is_array( $value ) || ! array_key_exists( $key, $value ) ) {
			return $default;
		}
		$value = $value[ $key ];
	}
	return $value;
}

/**
 * Full default content tree. Filterable so a child theme can change copy.
 *
 * @return array
 */
function estatein_defaults() {
	static $defaults = null;
	if ( null !== $defaults ) {
		return $defaults;
	}

	$img = ESTATEIN_URI . '/demo/images/';

	$defaults = array(

		/* ---------------------------------------------------------------
		 * Site wide settings (Theme Settings options page).
		 * ------------------------------------------------------------- */
		'options' => array(
			'announcement_enabled' => true,
			'announcement_text'    => __( 'Discover Your Dream Property with Estatein', 'estatein' ),
			'announcement_link'    => __( 'Learn More', 'estatein' ),
			'announcement_url'     => '/properties/',
			'header_button_label'  => __( 'Contact Us', 'estatein' ),
			'header_button_url'    => '/contact-us/',
			'contact_email'        => 'info@estatein.com',
			'contact_phone'        => '+1 (123) 456-7890',
			'contact_hq_label'     => __( 'Main Headquarters', 'estatein' ),
			'contact_hq_url'       => 'https://maps.google.com/?q=Metropolis',
			'social_facebook'      => 'https://facebook.com/',
			'social_linkedin'      => 'https://linkedin.com/',
			'social_twitter'       => 'https://twitter.com/',
			'social_youtube'       => 'https://youtube.com/',
			'social_instagram'     => 'https://instagram.com/',
			'stats'                => array(
				array( 'number' => '200+', 'label' => __( 'Happy Customers', 'estatein' ) ),
				array( 'number' => '10k+', 'label' => __( 'Properties For Clients', 'estatein' ) ),
				array( 'number' => '16+', 'label' => __( 'Years of Experience', 'estatein' ) ),
			),
			'quick_links'          => array(
				array( 'icon' => 'store', 'title' => __( 'Find Your Dream Home', 'estatein' ), 'url' => '/properties/' ),
				array( 'icon' => 'banknote', 'title' => __( 'Unlock Property Value', 'estatein' ), 'url' => '/services/#unlock-property-value' ),
				array( 'icon' => 'building-2', 'title' => __( 'Effortless Property Management', 'estatein' ), 'url' => '/services/#effortless-property-management' ),
				array( 'icon' => 'sun', 'title' => __( 'Smart Investments, Informed Decisions', 'estatein' ), 'url' => '/services/#smart-investments-informed-decisions' ),
			),
			'cta_title'            => __( 'Start Your Real Estate Journey Today', 'estatein' ),
			'cta_text'             => __( 'Your dream property is just a click away. Whether you\'re looking for a new home, a strategic investment, or expert real estate advice, Estatein is here to assist you every step of the way. Take the first step towards your real estate goals and explore our available properties or get in touch with our team for personalized assistance.', 'estatein' ),
			'cta_button_label'     => __( 'Explore Properties', 'estatein' ),
			'cta_button_url'       => '/properties/',
			'copyright'            => __( '@2023 Estatein. All Rights Reserved.', 'estatein' ),
			'terms_url'            => '/terms-conditions/',
			'privacy_url'          => '/privacy-policy/',
			'form_recipient'       => '',
			'pricing_note'         => __( 'The figures provided above are estimates and may vary depending on the property, location, and individual circumstances.', 'estatein' ),
		),

		/* ---------------------------------------------------------------
		 * Home page.
		 * ------------------------------------------------------------- */
		'home'    => array(
			'hero_title'             => __( 'Discover Your Dream Property with Estatein', 'estatein' ),
			'hero_text'              => __( 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.', 'estatein' ),
			'hero_secondary_label'   => __( 'Learn More', 'estatein' ),
			'hero_secondary_url'     => '/about-us/',
			'hero_primary_label'     => __( 'Browse Properties', 'estatein' ),
			'hero_primary_url'       => '/properties/',
			'hero_badge_text'        => __( 'Discover Your Dream Property', 'estatein' ),
			'featured_title'         => __( 'Featured Properties', 'estatein' ),
			'featured_text'          => __( 'Explore our handpicked selection of featured properties. Each listing offers a glimpse into exceptional homes and investments available through Estatein. Click "View Details" for more information.', 'estatein' ),
			'featured_button'        => __( 'View All Properties', 'estatein' ),
			'testimonials_title'     => __( 'What Our Clients Say', 'estatein' ),
			'testimonials_text'      => __( 'Read the success stories and heartfelt testimonials from our valued clients. Discover why they chose Estatein for their real estate needs.', 'estatein' ),
			'testimonials_button'    => __( 'View All Testimonials', 'estatein' ),
			'faq_title'              => __( 'Frequently Asked Questions', 'estatein' ),
			'faq_text'               => __( 'Find answers to common questions about Estatein\'s services, property listings, and the real estate process. We\'re here to provide clarity and assist you every step of the way.', 'estatein' ),
			'faq_button'             => __( 'View All FAQ\'s', 'estatein' ),
		),

		/* ---------------------------------------------------------------
		 * About page.
		 * ------------------------------------------------------------- */
		'about'   => array(
			'journey_title'      => __( 'Our Journey', 'estatein' ),
			'journey_text'       => __( 'Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary. Over the years, we\'ve expanded our reach, forged valuable partnerships, and gained the trust of countless clients.', 'estatein' ),
			'values_title'       => __( 'Our Values', 'estatein' ),
			'values_text'        => __( 'Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary.', 'estatein' ),
			'values'             => array(
				array( 'icon' => 'star', 'title' => __( 'Trust', 'estatein' ), 'text' => __( 'Trust is the cornerstone of every successful real estate transaction.', 'estatein' ) ),
				array( 'icon' => 'graduation-cap', 'title' => __( 'Excellence', 'estatein' ), 'text' => __( 'We set the bar high for ourselves. From the properties we list to the services we provide.', 'estatein' ) ),
				array( 'icon' => 'users', 'title' => __( 'Client-Centric', 'estatein' ), 'text' => __( 'Your dreams and needs are at the center of our universe. We listen, understand.', 'estatein' ) ),
				array( 'icon' => 'star', 'title' => __( 'Our Commitment', 'estatein' ), 'text' => __( 'We are dedicated to providing you with the highest level of service, professionalism.', 'estatein' ) ),
			),
			'achievements_title' => __( 'Our Achievements', 'estatein' ),
			'achievements_text'  => __( 'Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary.', 'estatein' ),
			'achievements'       => array(
				array( 'title' => __( '3+ Years of Excellence', 'estatein' ), 'text' => __( 'With over 3 years in the industry, we\'ve amassed a wealth of knowledge and experience.', 'estatein' ) ),
				array( 'title' => __( 'Happy Clients', 'estatein' ), 'text' => __( 'Our greatest achievement is the satisfaction of our clients. Their success stories fuel our passion for what we do.', 'estatein' ) ),
				array( 'title' => __( 'Industry Recognition', 'estatein' ), 'text' => __( 'We\'ve earned the respect of our peers and industry leaders, with accolades and awards that reflect our commitment to excellence.', 'estatein' ) ),
			),
			'process_title'      => __( 'Navigating the Estatein Experience', 'estatein' ),
			'process_text'       => __( 'At Estatein, we\'ve designed a straightforward process to help you find and purchase your dream property with ease. Here\'s a step-by-step guide to how it all works.', 'estatein' ),
			'steps'              => array(
				array( 'title' => __( 'Discover a World of Possibilities', 'estatein' ), 'text' => __( 'Your journey begins with exploring our carefully curated property listings. Use our intuitive search tools to filter properties based on your preferences, including location,', 'estatein' ) ),
				array( 'title' => __( 'Narrowing Down Your Choices', 'estatein' ), 'text' => __( 'Once you\'ve found properties that catch your eye, save them to your account or make a shortlist. This allows you to compare and revisit your favorites as you make your decision.', 'estatein' ) ),
				array( 'title' => __( 'Personalized Guidance', 'estatein' ), 'text' => __( 'Have questions about a property or need more information? Our dedicated team of real estate experts is just a call or message away.', 'estatein' ) ),
				array( 'title' => __( 'See It for Yourself', 'estatein' ), 'text' => __( 'Arrange viewings of the properties you\'re interested in. We\'ll coordinate with the property owners and accompany you to ensure you get a firsthand look at your potential new home.', 'estatein' ) ),
				array( 'title' => __( 'Making Informed Decisions', 'estatein' ), 'text' => __( 'Before making an offer, our team will assist you with due diligence, including property inspections, legal checks, and market analysis. We want you to be fully informed.', 'estatein' ) ),
				array( 'title' => __( 'Getting the Best Deal', 'estatein' ), 'text' => __( 'We\'ll help you negotiate the best terms and prepare your offer. Our goal is to secure the property at the right price and on favorable terms.', 'estatein' ) ),
			),
			'team_title'         => __( 'Meet the Estatein Team', 'estatein' ),
			'team_text'          => __( 'At Estatein, our success is driven by the dedication and expertise of our team. Get to know the people behind our mission to make your real estate dreams a reality.', 'estatein' ),
			'clients_title'      => __( 'Our Valued Clients', 'estatein' ),
			'clients_text'       => __( 'At Estatein, we have had the privilege of working with a diverse range of clients across various industries. Here are some of the clients we\'ve had the pleasure of serving', 'estatein' ),
		),

		/* ---------------------------------------------------------------
		 * Properties page.
		 * ------------------------------------------------------------- */
		'properties' => array(
			'hero_title'    => __( 'Find Your Dream Property', 'estatein' ),
			'hero_text'     => __( 'Welcome to Estatein, where your dream property awaits in every corner of our beautiful world. Explore our curated selection of properties, each offering a unique story and a chance to redefine your life. With categories to suit every dreamer, your journey', 'estatein' ),
			'listing_title' => __( 'Discover a World of Possibilities', 'estatein' ),
			'listing_text'  => __( 'Our portfolio of properties is as diverse as your dreams. Explore the following categories to find the perfect property that resonates with your vision of home', 'estatein' ),
			'form_title'    => __( 'Let\'s Make it Happen', 'estatein' ),
			'form_text'     => __( 'Ready to take the first step toward your dream property? Fill out the form below, and our real estate wizards will work their magic to find your perfect match. Don\'t wait; let\'s embark on this exciting journey together.', 'estatein' ),
		),

		/* ---------------------------------------------------------------
		 * Services page.
		 * ------------------------------------------------------------- */
		'services' => array(
			'hero_title' => __( 'Elevate Your Real Estate Experience', 'estatein' ),
			'hero_text'  => __( 'Welcome to Estatein, where your real estate aspirations meet expert guidance. Explore our comprehensive range of services, each designed to cater to your unique needs and dreams.', 'estatein' ),
		),

		/* ---------------------------------------------------------------
		 * Contact page.
		 * ------------------------------------------------------------- */
		'contact' => array(
			'hero_title'    => __( 'Get in Touch with Estatein', 'estatein' ),
			'hero_text'     => __( 'Welcome to Estatein\'s Contact Us page. We\'re here to assist you with any inquiries, requests, or feedback you may have. Whether you\'re looking to buy or sell a property, explore investment opportunities, or simply want to connect, we\'re just a message away. Reach out to us, and let\'s start a conversation.', 'estatein' ),
			'form_title'    => __( 'Let\'s Connect', 'estatein' ),
			'form_text'     => __( 'We\'re excited to connect with you and learn more about your real estate goals. Use the form below to get in touch with Estatein. Whether you\'re a prospective client, partner, or simply curious about our services, we\'re here to answer your questions and provide the assistance you need.', 'estatein' ),
			'offices_title' => __( 'Discover Our Office Locations', 'estatein' ),
			'offices_text'  => __( 'Estatein is here to serve you across multiple locations. Whether you\'re looking to meet our team, discuss real estate opportunities, or simply drop by for a chat, we have offices conveniently located to serve your needs. Explore the categories below to find the Estatein office nearest to you', 'estatein' ),
			'gallery_title' => __( 'Explore Estatein\'s World', 'estatein' ),
			'gallery_text'  => __( 'Step inside the world of Estatein, where professionalism meets warmth, and expertise meets passion. Our gallery offers a glimpse into our team and workspaces, inviting you to get to know us better.', 'estatein' ),
			'gallery'       => array(
				$img . 'office-workspace.jpg',
				$img . 'office-team.jpg',
				$img . 'office-meeting.jpg',
				$img . 'office-team-2.jpg',
				$img . 'office-team-3.jpg',
				$img . 'office-handshake.jpg',
			),
		),

		/* ---------------------------------------------------------------
		 * Property listings (custom post type "property").
		 * ------------------------------------------------------------- */
		'property_pricing_note' => '',
		'properties_list'       => array(
			array(
				'title'       => 'Seaside Serenity Villa',
				'slug'        => 'seaside-serenity-villa',
				'excerpt'     => 'A stunning 4-bedroom, 3-bathroom villa in a peaceful suburban neighborhood',
				'content'     => 'Discover your own piece of paradise with the Seaside Serenity Villa. With an open floor plan, breathtaking ocean views from every room, and direct access to a pristine sandy beach, this property is the epitome of coastal living.',
				'tagline'     => 'Coastal Escapes - Where Waves Beckon',
				'location'    => 'Malibu, California',
				'location_term' => 'California',
				'type'        => 'Villa',
				'price'       => 1250000,
				'bedrooms'    => 4,
				'bathrooms'   => 3,
				'area'        => 2500,
				'build_year'  => 2021,
				'featured'    => true,
				'image'       => 'property-seaside-villa.jpg',
				'gallery'     => array( 'gallery-main-exterior.jpg', 'gallery-main-interior.jpg', 'property-seaside-villa.jpg', 'gallery-resort-pool.jpg', 'gallery-loungers.jpg', 'gallery-living-1.jpg', 'gallery-living-2.jpg', 'gallery-dining.jpg', 'gallery-living-dining.jpg', 'gallery-bedroom.jpg' ),
				'features'    => array(
					'Expansive oceanfront terrace for outdoor entertaining',
					'Gourmet kitchen with top-of-the-line appliances',
					'Private beach access for morning strolls and sunset views',
					'Master suite with a spa-inspired bathroom and ocean-facing balcony',
					'Private garage and ample storage space',
				),
			),
			array(
				'title'       => 'Metropolitan Haven',
				'slug'        => 'metropolitan-haven',
				'excerpt'     => 'A chic and fully-furnished 2-bedroom apartment with panoramic city views',
				'content'     => 'Immerse yourself in the energy of the city. This modern apartment in the heart of downtown offers floor-to-ceiling windows, panoramic skyline views and every urban amenity within walking distance.',
				'tagline'     => 'Urban Oasis - Life in the Heart of the City',
				'location'    => 'Manhattan, New York',
				'location_term' => 'New York',
				'type'        => 'Apartment',
				'price'       => 650000,
				'bedrooms'    => 2,
				'bathrooms'   => 2,
				'area'        => 1200,
				'build_year'  => 2018,
				'featured'    => true,
				'image'       => 'property-metropolitan-haven.jpg',
				'gallery'     => array( 'property-metropolitan-haven.jpg', 'gallery-living-2.jpg', 'gallery-dining.jpg', 'gallery-living-dining.jpg', 'gallery-bedroom.jpg' ),
				'features'    => array(
					'Floor-to-ceiling windows with skyline views',
					'Fully furnished with designer interiors',
					'24/7 concierge and secure parking',
					'Rooftop lounge and fitness center',
				),
			),
			array(
				'title'       => 'Rustic Retreat Cottage',
				'slug'        => 'rustic-retreat-cottage',
				'excerpt'     => 'An elegant 3-bedroom, 2.5-bathroom townhouse in a gated community',
				'content'     => 'Find tranquility in the countryside. This charming cottage is nestled amidst rolling hills, offering a peaceful escape with modern comforts, a landscaped garden and a cozy fireplace.',
				'tagline'     => 'Countryside Charm - Escape to Nature\'s Embrace',
				'location'    => 'Austin, Texas',
				'location_term' => 'Texas',
				'type'        => 'Townhouse',
				'price'       => 350000,
				'bedrooms'    => 3,
				'bathrooms'   => 3,
				'area'        => 1800,
				'build_year'  => 2012,
				'featured'    => true,
				'image'       => 'property-rustic-retreat.jpg',
				'gallery'     => array( 'property-rustic-retreat.jpg', 'gallery-living-1.jpg', 'gallery-dining.jpg', 'gallery-bedroom.jpg' ),
				'features'    => array(
					'Gated community with private security',
					'Landscaped garden with patio',
					'Open-plan kitchen and dining area',
					'Two-car garage',
				),
			),
			array(
				'title'       => 'Azure Bay Residence',
				'slug'        => 'azure-bay-residence',
				'excerpt'     => 'A modern 5-bedroom, 4-bathroom home with an infinity pool and hillside views',
				'content'     => 'Clean architectural lines, glass walls and an infinity pool make the Azure Bay Residence a statement home designed for indoor-outdoor living.',
				'tagline'     => 'Modern Living - Designed Around the Light',
				'location'    => 'Miami, Florida',
				'location_term' => 'Florida',
				'type'        => 'Villa',
				'price'       => 2150000,
				'bedrooms'    => 5,
				'bathrooms'   => 4,
				'area'        => 4200,
				'build_year'  => 2023,
				'featured'    => true,
				'image'       => 'gallery-main-exterior.jpg',
				'gallery'     => array( 'gallery-main-exterior.jpg', 'gallery-main-interior.jpg', 'gallery-loungers.jpg', 'gallery-living-dining.jpg', 'gallery-bedroom.jpg' ),
				'features'    => array(
					'Infinity pool with hillside views',
					'Smart-home automation throughout',
					'Home cinema and wine cellar',
					'Guest suite with private entrance',
				),
			),
			array(
				'title'       => 'Palm Grove Resort Home',
				'slug'        => 'palm-grove-resort-home',
				'excerpt'     => 'A resort-style 4-bedroom villa with lagoon pool and private cabanas',
				'content'     => 'Live every day like a holiday. Palm Grove combines resort amenities with the privacy of a family home, just minutes from the beach.',
				'tagline'     => 'Resort Living - Holiday Every Day',
				'location'    => 'San Diego, California',
				'location_term' => 'California',
				'type'        => 'Villa',
				'price'       => 1480000,
				'bedrooms'    => 4,
				'bathrooms'   => 4,
				'area'        => 3100,
				'build_year'  => 2016,
				'featured'    => true,
				'image'       => 'gallery-resort-pool.jpg',
				'gallery'     => array( 'gallery-resort-pool.jpg', 'gallery-loungers.jpg', 'gallery-living-1.jpg', 'gallery-bedroom.jpg' ),
				'features'    => array(
					'Lagoon-style pool with cabanas',
					'Outdoor kitchen and dining pavilion',
					'Walking distance to the beach',
				),
			),
			array(
				'title'       => 'Sunset Loft',
				'slug'        => 'sunset-loft',
				'excerpt'     => 'A bright 1-bedroom loft with poolside terrace in a boutique building',
				'content'     => 'An airy loft with double-height ceilings and a poolside terrace — the perfect first home or investment property.',
				'tagline'     => 'City Lofts - Small Space, Big Living',
				'location'    => 'Las Vegas, Nevada',
				'location_term' => 'Nevada',
				'type'        => 'Apartment',
				'price'       => 420000,
				'bedrooms'    => 1,
				'bathrooms'   => 1,
				'area'        => 850,
				'build_year'  => 2005,
				'featured'    => false,
				'image'       => 'gallery-loungers.jpg',
				'gallery'     => array( 'gallery-loungers.jpg', 'gallery-living-2.jpg', 'gallery-dining.jpg' ),
				'features'    => array(
					'Double-height ceilings',
					'Shared pool and sun deck',
					'Secure underground parking',
				),
			),
		),

		/* Pricing breakdown shown on every property (scaled by price on import). */
		'pricing_groups' => array(
			array(
				'title' => 'Additional Fees',
				'rows'  => array(
					array( 'label' => 'Property Transfer Tax', 'amount' => '$25,000', 'note' => 'Based on the sale price and local regulations' ),
					array( 'label' => 'Legal Fees', 'amount' => '$3,000', 'note' => 'Approximate cost for legal services, including title transfer' ),
					array( 'label' => 'Home Inspection', 'amount' => '$500', 'note' => 'Recommended for due diligence' ),
					array( 'label' => 'Property Insurance', 'amount' => '$1,200', 'note' => 'Annual cost for comprehensive property insurance' ),
					array( 'label' => 'Mortgage Fees', 'amount' => 'Varies', 'note' => 'If applicable, consult with your lender for specific details' ),
				),
			),
			array(
				'title' => 'Monthly Costs',
				'rows'  => array(
					array( 'label' => 'Property Taxes', 'amount' => '$1,250', 'note' => 'Approximate monthly property tax based on the sale price and local rates', 'full' => true ),
					array( 'label' => 'Homeowners\' Association Fee', 'amount' => '$300', 'note' => 'Monthly fee for common area maintenance and security', 'full' => true ),
				),
			),
			array(
				'title' => 'Total Initial Costs',
				'rows'  => array(
					array( 'label' => 'Listing Price', 'amount' => '$1,250,000', 'note' => '' ),
					array( 'label' => 'Additional Fees', 'amount' => '$29,700', 'note' => 'Property transfer tax, legal fees, inspection, insurance' ),
					array( 'label' => 'Down Payment', 'amount' => '$250,000', 'note' => '20%' ),
					array( 'label' => 'Mortgage Amount', 'amount' => '$1,000,000', 'note' => 'If applicable' ),
				),
			),
			array(
				'title' => 'Monthly Expenses',
				'rows'  => array(
					array( 'label' => 'Property Taxes', 'amount' => '$1,250', 'note' => '' ),
					array( 'label' => 'Homeowners\' Association Fee', 'amount' => '$300', 'note' => '' ),
					array( 'label' => 'Mortgage Payment', 'amount' => 'Varies based on terms and interest rate', 'note' => 'If applicable' ),
					array( 'label' => 'Property Insurance', 'amount' => '$100', 'note' => 'Approximate monthly cost' ),
				),
			),
		),

		'testimonials' => array(
			array( 'title' => 'Exceptional Service!', 'quote' => 'Our experience with Estatein was outstanding. Their team\'s dedication and professionalism made finding our dream home a breeze. Highly recommended!', 'name' => 'Wade Warren', 'location' => 'USA, California', 'rating' => 5, 'image' => 'avatar-wade.jpg' ),
			array( 'title' => 'Efficient and Reliable', 'quote' => 'Estatein provided us with top-notch service. They helped us sell our property quickly and at a great price. We couldn\'t be happier with the results.', 'name' => 'Emelie Thomson', 'location' => 'USA, Florida', 'rating' => 5, 'image' => 'avatar-emelie.jpg' ),
			array( 'title' => 'Trusted Advisors', 'quote' => 'The Estatein team guided us through the entire buying process. Their knowledge and commitment to our needs were impressive. Thank you for your support!', 'name' => 'John Mans', 'location' => 'USA, Nevada', 'rating' => 5, 'image' => 'avatar-john.jpg' ),
			array( 'title' => 'Smooth From Start to Finish', 'quote' => 'From the first viewing to the final signature everything was handled with care. Estatein made relocating our family stress-free.', 'name' => 'Leslie Alexander', 'location' => 'USA, Texas', 'rating' => 5, 'image' => '' ),
			array( 'title' => 'Great Investment Advice', 'quote' => 'Their market insight helped me pick a rental property that has outperformed my expectations. I will definitely work with them again.', 'name' => 'Robert Fox', 'location' => 'USA, New York', 'rating' => 5, 'image' => '' ),
			array( 'title' => 'Highly Professional', 'quote' => 'Responsive, transparent and always one step ahead. The team negotiated a fantastic deal for us.', 'name' => 'Jenny Wilson', 'location' => 'USA, Arizona', 'rating' => 5, 'image' => '' ),
		),

		'faqs' => array(
			array( 'question' => 'How do I search for properties on Estatein?', 'summary' => 'Learn how to use our user-friendly search tools to find properties that match your criteria.', 'answer' => 'Head to the Properties page and type a keyword into the search bar, or narrow the results with the Location, Property Type, Pricing Range, Property Size and Build Year filters. Every result links to a detailed page with photos, pricing and an inquiry form.' ),
			array( 'question' => 'What documents do I need to sell my property through Estatein?', 'summary' => 'Find out about the necessary documentation for listing your property with us.', 'answer' => 'You will typically need proof of ownership (title deed), a valid ID, recent property tax statements, any outstanding mortgage information and existing inspection or appraisal reports. Our team will send you a personalised checklist once you get in touch.' ),
			array( 'question' => 'How can I contact an Estatein agent?', 'summary' => 'Discover the different ways you can get in touch with our experienced agents.', 'answer' => 'Use the contact form on our Contact Us page, email info@estatein.com, call +1 (123) 456-7890 or visit one of our offices. Every property page also has an inquiry form that goes straight to the responsible agent.' ),
			array( 'question' => 'Can Estatein help me with financing?', 'summary' => 'See how we connect you with trusted mortgage partners.', 'answer' => 'Yes. We work with a network of trusted lenders and mortgage brokers and can introduce you to partners who match your financial situation.' ),
			array( 'question' => 'Do you offer property management services?', 'summary' => 'Learn about our tenant, maintenance and financial management services.', 'answer' => 'Our Property Management Service covers tenant screening, rent collection, maintenance and legal compliance so you can enjoy the benefits of ownership without the hassle.' ),
			array( 'question' => 'How long does it take to buy a property?', 'summary' => 'Understand the typical timeline from viewing to closing.', 'answer' => 'Most purchases close within 30 to 60 days after an offer is accepted, depending on financing, inspections and legal checks. Your agent will give you a clear timeline at the start.' ),
		),

		'team' => array(
			array( 'name' => 'Max Mitchell', 'role' => 'Founder', 'image' => 'team-max.jpg' ),
			array( 'name' => 'Sarah Johnson', 'role' => 'Chief Real Estate Officer', 'image' => 'team-sarah.jpg' ),
			array( 'name' => 'David Brown', 'role' => 'Head of Property Management', 'image' => 'team-david.jpg' ),
			array( 'name' => 'Michael Turner', 'role' => 'Legal Counsel', 'image' => 'team-michael.jpg' ),
		),

		'clients' => array(
			array( 'name' => 'ABC Corporation', 'since' => '2019', 'domain' => 'Commercial Real Estate', 'category' => 'Luxury Home Development', 'quote' => 'Estatein\'s expertise in finding the perfect office space for our expanding operations was invaluable. They truly understand our business needs.', 'website' => 'https://example.com' ),
			array( 'name' => 'GreenTech Enterprises', 'since' => '2018', 'domain' => 'Commercial Real Estate', 'category' => 'Retail Space', 'quote' => 'Estatein\'s ability to identify prime retail locations helped us expand our brand presence. They are a trusted partner in our growth.', 'website' => 'https://example.com' ),
			array( 'name' => 'Skyline Holdings', 'since' => '2020', 'domain' => 'Residential Real Estate', 'category' => 'Urban Apartments', 'quote' => 'Their market knowledge and negotiation skills secured us an outstanding portfolio of city apartments.', 'website' => 'https://example.com' ),
			array( 'name' => 'Bluewave Hospitality', 'since' => '2021', 'domain' => 'Hospitality', 'category' => 'Boutique Hotels', 'quote' => 'Estatein found us two beachfront locations that became our best performing hotels.', 'website' => 'https://example.com' ),
		),

		'service_categories' => array(
			array(
				'name'       => 'Unlock Property Value',
				'slug'       => 'unlock-property-value',
				'intro'      => 'Selling your property should be a rewarding experience, and at Estatein, we make sure it is. Our Property Selling Service is designed to maximize the value of your property, ensuring you get the best deal possible. Explore the categories below to see how we can help you at every step of your selling journey',
				'layout'     => 'grid',
				'cta_title'  => 'Unlock the Value of Your Property Today',
				'cta_text'   => 'Ready to unlock the true value of your property? Explore our Property Selling Service categories and let us help you achieve the best deal possible for your valuable asset.',
				'cta_label'  => 'Learn More',
				'cta_url'    => '/contact-us/',
				'services'   => array(
					array( 'title' => 'Valuation Mastery', 'icon' => 'bar-chart', 'text' => 'Discover the true worth of your property with our expert valuation services.' ),
					array( 'title' => 'Strategic Marketing', 'icon' => 'pie-chart', 'text' => 'Selling a property requires more than just a listing; it demands a strategic marketing approach.' ),
					array( 'title' => 'Negotiation Wizardry', 'icon' => 'database', 'text' => 'Negotiating the best deal is an art, and our negotiation experts are masters of it.' ),
					array( 'title' => 'Closing Success', 'icon' => 'megaphone', 'text' => 'A successful sale is not complete until the closing. We guide you through the intricate closing process.' ),
				),
			),
			array(
				'name'       => 'Effortless Property Management',
				'slug'       => 'effortless-property-management',
				'intro'      => 'Owning a property should be a pleasure, not a hassle. Estatein\'s Property Management Service takes the stress out of property ownership, offering comprehensive solutions tailored to your needs. Explore the categories below to see how we can make property management effortless for you',
				'layout'     => 'grid',
				'cta_title'  => 'Experience Effortless Property Management',
				'cta_text'   => 'Ready to experience hassle-free property management? Explore our Property Management Service categories and let us handle the complexities while you enjoy the benefits of property ownership.',
				'cta_label'  => 'Learn More',
				'cta_url'    => '/contact-us/',
				'services'   => array(
					array( 'title' => 'Tenant Harmony', 'icon' => 'layout-grid', 'text' => 'Our Tenant Management services ensure that your tenants have a smooth and reducing vacancies.' ),
					array( 'title' => 'Maintenance Ease', 'icon' => 'wrench', 'text' => 'Say goodbye to property maintenance headaches. We handle all aspects of property upkeep.' ),
					array( 'title' => 'Financial Peace of Mind', 'icon' => 'sparkles', 'text' => 'Managing property finances can be complex. Our financial experts take care of rent collection' ),
					array( 'title' => 'Legal Guardian', 'icon' => 'sun', 'text' => 'Stay compliant with property laws and regulations effortlessly.' ),
				),
			),
			array(
				'name'       => 'Smart Investments, Informed Decisions',
				'slug'       => 'smart-investments-informed-decisions',
				'intro'      => 'Building a real estate portfolio requires a strategic approach. Estatein\'s Investment Advisory Service empowers you to make smart investments and informed decisions.',
				'layout'     => 'split',
				'cta_title'  => 'Unlock Your Investment Potential',
				'cta_text'   => 'Explore our Property Management Service categories and let us handle the complexities while you enjoy the benefits of property ownership.',
				'cta_label'  => 'Learn More',
				'cta_url'    => '/contact-us/',
				'services'   => array(
					array( 'title' => 'Market Insight', 'icon' => 'bar-chart', 'text' => 'Stay ahead of market trends with our expert Market Analysis. We provide in-depth insights into real estate market conditions' ),
					array( 'title' => 'ROI Assessment', 'icon' => 'flame', 'text' => 'Make investment decisions with confidence. Our ROI Assessment services evaluate the potential returns on your investments' ),
					array( 'title' => 'Customized Strategies', 'icon' => 'lightbulb', 'text' => 'Every investor is unique, and so are their goals. We develop Customized Investment Strategies tailored to your specific needs' ),
					array( 'title' => 'Diversification Mastery', 'icon' => 'sun', 'text' => 'Diversify your real estate portfolio effectively. Our experts guide you in spreading your investments across various property types and locations' ),
				),
			),
		),

		'offices' => array(
			array( 'label' => 'Main Headquarters', 'type' => 'regional', 'address' => '123 Estatein Plaza, City Center, Metropolis', 'text' => 'Our main headquarters serve as the heart of Estatein. Located in the bustling city center, this is where our core team of experts operates, driving the excellence and innovation that define us.', 'email' => 'info@estatein.com', 'phone' => '+1 (123) 456-7890', 'city' => 'Metropolis', 'map' => 'https://maps.google.com/?q=Metropolis' ),
			array( 'label' => 'Regional Offices', 'type' => 'regional', 'address' => '456 Urban Avenue, Downtown District, Metropolis', 'text' => 'Estatein\'s presence extends to multiple regions, each with its own dynamic real estate landscape. Discover our regional offices, staffed by local experts who understand the nuances of their respective markets.', 'email' => 'info@restatein.com', 'phone' => '+1 (123) 628-7890', 'city' => 'Metropolis', 'map' => 'https://maps.google.com/?q=Metropolis' ),
			array( 'label' => 'International Office', 'type' => 'international', 'address' => '78 Harbour Street, Marina Bay, Singapore', 'text' => 'Our international office supports overseas investors and global clients looking for premium real estate opportunities in fast-growing markets.', 'email' => 'global@estatein.com', 'phone' => '+65 6123 4567', 'city' => 'Singapore', 'map' => 'https://maps.google.com/?q=Marina+Bay+Singapore' ),
		),

		'posts' => array(
			array( 'title' => '5 Questions to Ask Before Buying Your First Home', 'image' => 'property-rustic-retreat.jpg', 'excerpt' => 'Buying your first home is exciting — and a little overwhelming. Start with these five questions to stay focused on what matters.', 'content' => "Buying your first home is one of the biggest decisions you will ever make. Before you start booking viewings, take a moment to answer these questions.\n\n<h2>1. What can I really afford?</h2>\nLook beyond the listing price. Include taxes, insurance, association fees and maintenance in your monthly budget.\n\n<h2>2. Which location fits my lifestyle?</h2>\nCommute times, schools and amenities often matter more than an extra bedroom.\n\n<h2>3. New build or resale?</h2>\nNew homes need less maintenance, while resale homes often come with established neighbourhoods and room to negotiate.\n\n<h2>4. What are my must-haves?</h2>\nSeparate the non-negotiables from the nice-to-haves before emotions take over.\n\n<h2>5. Who is on my team?</h2>\nA trusted agent, lender and inspector will save you time, money and stress." ),
			array( 'title' => 'How to Stage Your Home to Sell Faster', 'image' => 'gallery-living-dining.jpg', 'excerpt' => 'Small changes can make a big difference. Our agents share the staging tips that consistently attract higher offers.', 'content' => "First impressions count. Buyers decide within seconds whether a home feels right.\n\n<h2>Declutter and depersonalise</h2>\nClear surfaces and remove personal items so buyers can picture themselves living there.\n\n<h2>Let the light in</h2>\nOpen curtains, replace dim bulbs and clean the windows.\n\n<h2>Fix the small things</h2>\nLoose handles, dripping taps and scuffed walls signal neglect. A weekend of repairs pays for itself." ),
			array( 'title' => 'Real Estate Investing: Diversify Like a Pro', 'image' => 'gallery-main-exterior.jpg', 'excerpt' => 'Spreading your investments across property types and locations reduces risk. Here is how to build a balanced portfolio.', 'content' => "A diversified property portfolio is more resilient to market changes.\n\n<h2>Mix property types</h2>\nCombine residential, commercial and short-term rentals to balance risk and income.\n\n<h2>Think geographically</h2>\nDifferent regions move at different speeds. Investing across markets smooths out your returns.\n\n<h2>Review regularly</h2>\nRebalance once a year with the help of an advisor who understands your goals." ),
		),
	);

	/**
	 * Filter the theme default content.
	 *
	 * @param array $defaults Default content tree.
	 */
	$defaults = apply_filters( 'estatein_defaults', $defaults );

	return $defaults;
}
