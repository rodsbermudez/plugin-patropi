<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Patropi_Addon_FAQ {
    private $post_type = 'faq-patropi';
    private $meta_key = 'patropi_faq_pages';
    private $enabled = null;

    public function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_meta_box' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_shortcode( 'faq-patropi', array( $this, 'render_shortcode' ) );
    }

    private function check_enabled() {
        if ( $this->enabled === null ) {
            $options = get_option( 'patropi_addon_settings', array() );
            $this->enabled = ! empty( $options['faq_enabled'] ) && $options['faq_enabled'] == 1;
        }
        return $this->enabled;
    }

    public function register_post_type() {
        if ( ! $this->check_enabled() ) {
            return;
        }

        $labels = array(
            'name'               => __( 'FAQs', 'patropi-addon' ),
            'singular_name'      => __( 'FAQ', 'patropi-addon' ),
            'menu_name'          => __( 'FAQs', 'patropi-addon' ),
            'name_admin_bar'     => __( 'FAQ', 'patropi-addon' ),
            'add_new'            => __( 'Adicionar FAQ', 'patropi-addon' ),
            'add_new_item'       => __( 'Adicionar FAQ', 'patropi-addon' ),
            'edit_item'          => __( 'Editar FAQ', 'patropi-addon' ),
            'new_item'           => __( 'Novo FAQ', 'patropi-addon' ),
            'view_item'          => __( 'Ver FAQ', 'patropi-addon' ),
            'search_items'       => __( 'Pesquisar FAQs', 'patropi-addon' ),
            'not_found'          => __( 'Nenhum FAQ encontrado', 'patropi-addon' ),
            'not_found_in_trash' => __( 'Nenhum FAQ na lixeira', 'patropi-addon' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'supports'           => array( 'title', 'editor' ),
            'hierarchical'       => false,
            'menu_icon'          => 'dashicons-editor-help',
            'show_in_rest'      => false,
            'register_meta_box_cb' => null,
        );

        register_post_type( $this->post_type, $args );
    }

    public function add_meta_box() {
        if ( ! $this->check_enabled() ) {
            return;
        }

        add_meta_box(
            'patropi_faq_pages_meta',
            __( 'Disponibilidade', 'patropi-addon' ),
            array( $this, 'render_meta_box' ),
            $this->post_type,
            'side',
            'default'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( 'patropi_faq_save_meta', 'patropi_faq_nonce' );

        $selected_pages = get_post_meta( $post->ID, $this->meta_key, true );
        if ( ! is_array( $selected_pages ) ) {
            $selected_pages = array();
        }

        $pages = get_pages( array( 'post_status' => 'publish' ) );
        ?>
        <p><strong><?php _e( 'Selecione as páginas onde este FAQ deve aparecer:', 'patropi-addon' ); ?></strong></p>
        <div class="patropi-faq-pages-list" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
            <?php if ( empty( $pages ) ) : ?>
                <p><?php _e( 'Nenhuma página encontrada.', 'patropi-addon' ); ?></p>
            <?php else : ?>
                <?php foreach ( $pages as $page ) : ?>
                    <label style="display: block; margin-bottom: 5px;">
                        <input type="checkbox" 
                               name="patropi_faq_pages[]" 
                               value="<?php echo esc_attr( $page->ID ); ?>"
                               <?php checked( in_array( $page->ID, $selected_pages ), true ); ?>>
                        <?php echo esc_html( $page->post_title ); ?>
                    </label>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <p class="description" style="margin-top: 10px; color: #666;">
            <?php _e( 'Use o shortcode [faq-patropi] nas páginas selecionadas.', 'patropi-addon' ); ?>
        </p>
        <?php
    }

    public function save_meta_box( $post_id ) {
        if ( ! isset( $_POST['patropi_faq_nonce'] ) ) {
            return $post_id;
        }

        if ( ! wp_verify_nonce( $_POST['patropi_faq_nonce'], 'patropi_faq_save_meta' ) ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        if ( isset( $_POST['patropi_faq_pages'] ) && is_array( $_POST['patropi_faq_pages'] ) ) {
            $pages = array_map( 'intval', $_POST['patropi_faq_pages'] );
            update_post_meta( $post_id, $this->meta_key, $pages );
        } else {
            delete_post_meta( $post_id, $this->meta_key );
        }

        return $post_id;
    }

    public function enqueue_assets() {
        global $post;

        if ( ! is_a( $post, 'WP_Post' ) ) {
            return;
        }

        $faq_posts = get_posts( array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => $this->meta_key,
            'meta_compare' => 'EXISTS'
        ) );

        $page_ids = array();
        foreach ( $faq_posts as $faq ) {
            $pages = get_post_meta( $faq->ID, $this->meta_key, true );
            if ( is_array( $pages ) && in_array( $post->ID, $pages ) ) {
                $page_ids[] = $post->ID;
                break;
            }
        }

        if ( empty( $page_ids ) ) {
            return;
        }

        wp_enqueue_style(
            'patropi-faq',
            PATROPI_ADDON_PLUGIN_URL . 'assets/css/faq.css',
            array(),
            PATROPI_ADDON_VERSION
        );

        wp_enqueue_script(
            'patropi-faq',
            PATROPI_ADDON_PLUGIN_URL . 'assets/js/faq.js',
            array( 'jquery' ),
            PATROPI_ADDON_VERSION,
            true
        );

        $settings = new Patropi_Addon_Settings();
        wp_localize_script( 'patropi-faq', 'patropiFaqSettings', array(
            'openFirst' => $settings->is_faq_open_first(),
            'closeOthers' => $settings->is_faq_close_others()
        ) );
    }

    public function render_shortcode( $atts ) {
        $settings = new Patropi_Addon_Settings();

        if ( ! $settings->is_faq_enabled() ) {
            return '';
        }

        global $post;
        if ( ! is_a( $post, 'WP_Post' ) ) {
            return '';
        }

        $current_page_id = $post->ID;

        $faqs = get_posts( array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        ) );

        $filtered_faqs = array();
        foreach ( $faqs as $faq ) {
            $pages = get_post_meta( $faq->ID, $this->meta_key, true );
            if ( is_array( $pages ) && in_array( $current_page_id, $pages ) ) {
                $filtered_faqs[] = $faq;
            }
        }

        if ( empty( $filtered_faqs ) ) {
            return '';
        }

        ob_start();
        ?>
        <div class="patropi-faq-accordion">
            <?php foreach ( $filtered_faqs as $index => $faq ) : ?>
                <div class="patropi-faq-item">
                    <div class="patropi-faq-question" data-index="<?php echo esc_attr( $index ); ?>">
                        <span class="patropi-faq-question-text"><?php echo esc_html( $faq->post_title ); ?></span>
                        <span class="patropi-faq-icon">+</span>
                    </div>
                    <div class="patropi-faq-answer">
                        <div class="patropi-faq-answer-content">
                            <?php echo apply_filters( 'the_content', $faq->post_content ); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}