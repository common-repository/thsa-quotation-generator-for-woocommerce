<div class="thsa_qg_wrapper thsa_qg_settings_wrapper">
    <div class="thsa_qg_inner">
        <h2><?php esc_html_e('Settings','thsa-quote-generator'); ?></h2>
        <ul class="thsa_qg_settings_tab">
            <?php 
                echo $this->tab_settings_manager( $params['tabs'] );
            ?>
        </ul>
        <div class="thsa_settings_content">
            <?php 
            $this->tab_content_manager( $params['tab_targets'] );
            ?>
        </div>
    </div>
</div>