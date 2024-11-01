<div class="thsa_quotation_table">
    <table cellspacing="0" class="thsa_qg_table" style="width: 100%; <?php echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ]
                    ]
                    ); 
            ?>">
        <thead>
        <tr>
            <th width="55%" style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_header_color',
                        'value' => (isset($params['style']['thsa_qg_header_color']))? $params['style']['thsa_qg_header_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_header_text_color',
                        'value' => (isset($params['style']['thsa_qg_header_text_color']))? $params['style']['thsa_qg_header_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_border_color',
                        'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_padding',
                        'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                        ]
                    ]
                ); 
            ?> text-align: left;"><?php esc_attr_e('Product','thsa_quote_generator'); ?></th>
            <th width="15%" style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_header_color',
                        'value' => (isset($params['style']['thsa_qg_header_color']))? $params['style']['thsa_qg_header_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_header_text_color',
                        'value' => (isset($params['style']['thsa_qg_header_text_color']))? $params['style']['thsa_qg_header_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_border_color',
                        'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_padding',
                        'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                        ]

                    ]
                ); 
            ?> text-align: left;"><?php esc_attr_e('Price','thsa_quote_generator'); ?></th>
            <th width="15%" style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_header_color',
                        'value' => (isset($params['style']['thsa_qg_header_color']))? $params['style']['thsa_qg_header_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_header_text_color',
                        'value' => (isset($params['style']['thsa_qg_header_text_color']))? $params['style']['thsa_qg_header_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_border_color',
                        'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_padding',
                        'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                        ]

                    ]
                ); 
            ?> text-align: left;"><?php esc_attr_e('Quantity','thsa_quote_generator'); ?></th>
            <th width="15%" style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_header_color',
                        'value' => (isset($params['style']['thsa_qg_header_color']))? $params['style']['thsa_qg_header_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_header_text_color',
                        'value' => (isset($params['style']['thsa_qg_header_text_color']))? $params['style']['thsa_qg_header_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_border_color',
                        'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_padding',
                        'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                        ]

                    ]
                ); 
            ?> text-align: left;"><?php esc_attr_e('Amount','thsa_quote_generator'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php 
            if(isset($params['products'])):
                foreach($params['products'] as $pid => $product):
        ?>
        <tr>
            <td style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_border_color',
                            'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_padding',
                            'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                            ]

                    ]
                ); 
            ?>"><?php esc_attr_e($product['text'],'thsa-quote-generator'); ?></td>
            <td style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_border_color',
                        'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_padding',
                        'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                        ]

                    ]
                ); 
            ?>"><?php echo $product['price_html']; ?></td>
            <td style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_border_color',
                        'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_padding',
                        'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                        ]

                    ]
                ); 
            ?>"><?php esc_attr_e($product['qty'],'thsa-quote-generator'); ?></td>
            <td style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_border_color',
                        'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_padding',
                        'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                        ]

                    ]
                ); 
            ?>"><?php echo wp_kses_post( $product['amount'] ); ?></td>
        </tr>
        <?php 
                endforeach;
            else: ?>
        <tr>
            <td colspan="4" style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_border_color',
                            'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_padding',
                            'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                        ]

                    ]
                ); 
            ?>"><?php esc_attr_e('No items available', 'thsa-quote-generator'); ?></td>
        </tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <?php 
            if(isset($params['labels'])): 
                foreach($params['labels'] as $label => $value):
                    if(!$value)
                        continue;

                    if($label != 'Fees'):   
                        $class = ($label == 'Total Today')? 'thsa_qg_public_total' : null;
                        $class = ( isset($params['from_email']) )? $class.' thsa_qg_public_total_email' : null;
            ?>
                    <tr class="<?php esc_html_e($class); ?>">
                        <td style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_border_color',
                            'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_padding',
                            'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                            ]

                    ]
                ); 

                if($label == 'Total Today'){
                    echo $this->render_inline_style(
                        [
                            [
                            'type' => 'thsa_qg_total_row_color',
                            'value' => (isset($params['style']['thsa_qg_total_row_color']))? $params['style']['thsa_qg_total_row_color'] : null
                            ],
                            [
                            'type' => 'thsa_qg_total_font_color',
                            'value' => (isset($params['style']['thsa_qg_total_font_color']))? $params['style']['thsa_qg_total_font_color'] : null
                            ],
                            [
                                'type' => 'thsa_qg_total_font_size',
                                'value' => (isset($params['style']['thsa_qg_total_font_size']))? $params['style']['thsa_qg_total_font_size'] : null
                            ],
                            [
                                'type' => 'thsa_qg_padding',
                                'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                                ]
                        ]
                    );
                }
            ?>"></td>
                        <td style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_border_color',
                            'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_padding',
                            'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                            ]

                    ]
                ); 

                if($label == 'Total Today'){
                    echo $this->render_inline_style(
                        [
                            [
                            'type' => 'thsa_qg_total_row_color',
                            'value' => (isset($params['style']['thsa_qg_total_row_color']))? $params['style']['thsa_qg_total_row_color'] : null
                            ],
                            [
                            'type' => 'thsa_qg_total_font_color',
                            'value' => (isset($params['style']['thsa_qg_total_font_color']))? $params['style']['thsa_qg_total_font_color'] : null
                            ],
                            [
                                'type' => 'thsa_qg_total_font_size',
                                'value' => (isset($params['style']['thsa_qg_total_font_size']))? $params['style']['thsa_qg_total_font_size'] : null
                            ],
                            [
                                'type' => 'thsa_qg_padding',
                                'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                                ]
                        ]
                    );
                }
            ?> font-weight: bold;"><?php esc_html_e($label,'thsa-quote-generator'); ?></td>
                        <td colspan="2" style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_border_color',
                            'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_padding',
                            'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                            ]

                    ]
                ); 

                if($label == 'Total Today'){
                    echo $this->render_inline_style(
                        [
                            [
                            'type' => 'thsa_qg_total_row_color',
                            'value' => (isset($params['style']['thsa_qg_total_row_color']))? $params['style']['thsa_qg_total_row_color'] : null
                            ],
                            [
                            'type' => 'thsa_qg_total_font_color',
                            'value' => (isset($params['style']['thsa_qg_total_font_color']))? $params['style']['thsa_qg_total_font_color'] : null
                            ],
                            [
                                'type' => 'thsa_qg_total_font_size',
                                'value' => (isset($params['style']['thsa_qg_total_font_size']))? $params['style']['thsa_qg_total_font_size'] : null
                            ],
                            [
                                'type' => 'thsa_qg_padding',
                                'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                                ]
                        ]
                    );
                }
            ?> text-align: right; font-weight: bold;"><?php echo wp_kses_post( $value ); ?></td>
                    </tr>
            <?php 
                else:
                    foreach($value as $fee):
            ?>
                    <tr>
                        <td style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_border_color',
                            'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_padding',
                            'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                            ]

                    ]
                ); 
            ?> font-weight: bold;"></td>
                        <td style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_border_color',
                            'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_padding',
                            'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                            ]

                    ]
                ); 
            ?> font-weight: bold;"><?php esc_attr_e($fee['name'],'thsa-quote-generator'); ?></td>
                        <td colspan="2" style="<?php 
                echo $this->render_inline_style(
                    [
                        [
                        'type' => 'thsa_qg_text_color',
                        'value' => (isset($params['style']['thsa_qg_text_color']))? $params['style']['thsa_qg_text_color'] : null
                        ],
                        [
                        'type' => 'thsa_qg_background_color',
                        'value' => (isset($params['style']['thsa_qg_background_color']))? $params['style']['thsa_qg_background_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_border_color',
                            'value' => (isset($params['style']['thsa_qg_border_color']))? $params['style']['thsa_qg_border_color'] : null
                        ],
                        [
                            'type' => 'thsa_qg_padding',
                            'value' => (isset($params['style']['thsa_qg_padding']))? $params['style']['thsa_qg_padding'] : null
                        ]

                    ]
                ); 
            ?> font-weight: bold; text-align: right;"><?php echo wc_price( esc_html( $fee['amount'] ) ); ?></td>
                    </tr>
            <?php
                    endforeach;
                endif;
                endforeach;
            endif; ?>
        </tfoot>
       
    </table>
    <?php do_action('thsa_qg_before_total_button'); ?>
    
</div>