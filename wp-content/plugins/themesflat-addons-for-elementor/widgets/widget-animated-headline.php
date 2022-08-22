<?php
class TFAnimated_Headline_Widget_Free extends \Elementor\Widget_Base {

    public function get_name() {
        return 'tfanimated_headline';
    }
    
    public function get_title() {
        return esc_html__( 'TF Animated Headline', 'tfanimated-headline-addon-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-animated-headline';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons' ];
    }

    protected function register_controls() {
        // Start Headline Setting
        $this->start_controls_section( 
            'section_headline',
            [
                'label' => esc_html__('Headline', 'tfanimated-headline-addon-for-elementor'),
            ]
        );      

        $this->add_control(
            'headline_shape_highlight',
            [
                'label' => esc_html__( 'Shape', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'clip',
                'options' => [
                    'type' => esc_html__( 'Type', 'tfanimated-headline-addon-for-elementor' ),
                    'clip' => esc_html__( 'Clip', 'tfanimated-headline-addon-for-elementor' ),
                    'scale' => esc_html__( 'Scale', 'tfanimated-headline-addon-for-elementor' ),
                    'rotate-1' => esc_html__( 'Rotate 1', 'tfanimated-headline-addon-for-elementor' ),
                    'rotate-2' => esc_html__( 'Rotate 2', 'tfanimated-headline-addon-for-elementor' ),
                    'rotate-3' => esc_html__( 'Rotate 3', 'tfanimated-headline-addon-for-elementor' ),
                    'zoom' => esc_html__( 'Zoom', 'tfanimated-headline-addon-for-elementor' ),
                    'slide' => esc_html__( 'Slide', 'tfanimated-headline-addon-for-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'section_separator_content',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'headline_before_text',
            [
                'label' => esc_html__( 'Before Text', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Your Headline',
                'default' => 'Before Text',
                'label_block' => true,
                'separator' => 'none',
            ]
        );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 
            'headline_text_animation',
            [
                'label' => esc_html__( 'Highlighted Text', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Highlighted Text',
                'placeholder' => 'Your Headline',
                'label_block' => true,
            ]
        );
        $this->add_control( 'repeater_list',
            [                   
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'headline_text_animation' => esc_html__( 'Highlighted Text 1', 'tf-addon-for-elementer' ),                        
                    ],
                    [
                        'headline_text_animation' => esc_html__( 'Highlighted Text 2', 'tf-addon-for-elementer' ),                        
                    ],
                    [
                        'headline_text_animation' => esc_html__( 'Highlighted Text 3', 'tf-addon-for-elementer' ),                        
                    ],
                ],
                'title_field' => '{{{ headline_text_animation }}}',
            ]
        );

        $this->add_control(
            'headline_after_text',
            [
                'label' => esc_html__( 'After Text', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Your Headline',
                'default' => 'After Text',
                'label_block' => true,
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();
        // .End Headline

        // Start Setting
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Setting', 'tfanimated-headline-addon-for-elementor'),
            ]
        );


        $this->add_responsive_control(
            'alignment_text',
            [
                'label' => esc_html__( 'Alignment', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'tfanimated-headline-addon-for-elementor' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'tfanimated-headline-addon-for-elementor' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'tfanimated-headline-addon-for-elementor' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .tf-headline' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $possible_tags = [
            'div',
            'section',
            'span',
            'h1',
            'h2',
            'h3',
            'h4',
            'h5',
            'h6',
        ];

        $options = [
                '' => esc_html__( 'Default', 'tfanimated-headline-addon-for-elementor' ),
            ] + array_combine( $possible_tags, $possible_tags );

        $this->add_control(
            'headline_html_tag',
            [
                'label' => esc_html__( 'HTML Tag', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'div',
                'options' => $options,
            ]
        );

        $this->add_control(
            'headline_break',
            [
                'label' => esc_html__( 'Break', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'headline_break_tablet',
            [
                'label' => esc_html__( 'Tablet Break', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'headline_break_mobile',
            [
                'label' => esc_html__( 'Mobile Break', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'section_separator_setting',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->end_controls_section();
        // .End Setting

        // Section Style.
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Style', 'tfanimated-headline-addon-for-elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'headline_color_default',
            [
                'label' => esc_html__( 'Default Color', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .tf-headline' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'headline_color_highlight',
            [
                'label' => esc_html__( 'Highlight Color', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#23A455',
                'selectors' => [
                    '{{WRAPPER}} .tf-highlighted-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography_default',
                'label' => esc_html__( 'Typography Default', 'tfanimated-headline-addon-for-elementor' ),
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => [
                        'default' => 'Roboto',
                    ],
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '20',
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .tf-headline',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography_highlight',
                'label' => esc_html__( 'Typography Highlight', 'tfanimated-headline-addon-for-elementor' ),
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => [
                        'default' => 'Roboto',
                    ],
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '20',
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .tf-highlighted-text',
            ]
        );

        $this->add_control(
            'spacing_highlight',
            [
                'label' => esc_html__( 'Spacing Highlight', 'tfanimated-headline-addon-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tf-highlighted-text' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        // .End Style
        
    }

    protected function render($instance = []) {
        $settings = apply_filters('tf_settings_for_display', $this->get_settings_for_display());

        $html_animation = $html_words = $break = $highlight = '';
        foreach ($settings['repeater_list'] as $key => $repeater_list) {
            if ( $repeater_list['headline_text_animation'] != '' ){
                if($key == 0){
                    $html_words .= '<span class="item-text is-visible">'.$repeater_list['headline_text_animation'].'</span>';
                }else{
                    $html_words .= '<span class="item-text ">'.$repeater_list['headline_text_animation'].'</span>';
                }                
            }            
        }
        
        $highlight = 'tf-highlighted ';
        
        switch ( $settings['headline_shape_highlight'] ) {
            case 'type':
                $highlight .= 'animationtext letters type'; 
                $html_animation = '<span class="cd-words-wrapper">'.$html_words.'</span>';                   
                break;
            case 'rotate-2':
                $highlight .= 'animationtext letters rotate-2';
                $html_animation = '<span class="cd-words-wrapper">'.$html_words.'</span>'; 
                break;
            case 'rotate-3':
                $highlight .= 'animationtext letters rotate-3';
                $html_animation = '<span class="cd-words-wrapper">'.$html_words.'</span>'; 
                break;
            case 'scale':
                $highlight .= 'animationtext  scale';
                $html_animation = '<span class="cd-words-wrapper">'.$html_words.'</span>'; 
                break;

            case 'clip':
                $highlight .= 'animationtext clip';
                $html_animation = '<span class="cd-words-wrapper">'.$html_words.'</span>';
                break;                
            case 'rotate-1':
                $highlight .= 'animationtext rotate-1';
                $html_animation = '<span class="cd-words-wrapper">'.$html_words.'</span>';
                break;
            case 'slide':
                $highlight .= 'animationtext slide';
                $html_animation = '<span class="cd-words-wrapper">'.$html_words.'</span>';
                break;
            case 'zoom':
                $highlight .= 'animationtext zoom';
                $html_animation = '<span class="cd-words-wrapper">'.$html_words.'</span>'; 
                break;
            default:
                break;
        }
                
       

        if($settings['headline_break'] == 'yes'){
            $break .= ' tf-headline-break ';
        }

        if($settings['headline_break_tablet'] == 'yes'){
            $break .= ' tf-headline-break-tablet ';
        }

        if($settings['headline_break_mobile'] == 'yes'){
            $break .= ' tf-headline-break-mobi ';
        }

        echo sprintf (
            '<div class="tf-headline-wrap %5$s">
                <%1$s class="tf-headline">
                    <span class="tf-text tf-before-text">
                        %2$s
                    </span>
                    <span class="tf-text tf-highlighted-text %6$s">
                        %3$s 
                    </span>
                    <span class="tf-text tf-after-text">
                        %4$s
                    </span>
                </%1$s>
            </div>',
            $settings['headline_html_tag'],
            $settings['headline_before_text'],
            $html_animation,
            $settings['headline_after_text'],           
            $break,
            $highlight
        );

    }
}