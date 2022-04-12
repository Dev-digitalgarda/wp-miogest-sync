<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_modal_calculator')):
    function resideo_get_modal_calculator($calc_info) { ?>
        <div class="modal fade" id="modal-calculator" tabindex="-1" role="dialog" aria-labelledby="modal-calculatorLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modal-calculatorLabel"><?php esc_html_e('Mortgage Calculator', 'resideo'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <form id="modal-calculator-form">
                            <input type="hidden" id="modal-calculator-price-init" value="<?php echo esc_attr($calc_info['price']); ?>">
                            <input type="hidden" id="modal-calculator-dp-init" value="20">
                            <input type="hidden" id="modal-calculator-term-init" value="30">
                            <input type="hidden" id="modal-calculator-rate-init" value="4.25">
                            <input type="hidden" id="modal-calculator-currency-init" value="<?php echo esc_attr($calc_info['currency']); ?>">
                            <input type="hidden" id="modal-calculator-currency-pos-init" value="<?php echo esc_attr($calc_info['currency_pos']); ?>">
                            <input type="hidden" id="modal-calculator-locale-init" value="<?php echo esc_attr($calc_info['locale']); ?>">

                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="label-block" for="modal-calculator-price"><?php esc_html_e('Price', 'resideo'); ?></label>
                                        <div class="input-group">
                                            <?php if ($calc_info['currency_pos'] == 'before') { ?>
                                                <div class="input-group-addon"><?php echo esc_html($calc_info['currency']); ?></div>
                                            <?php } ?>
                                            <input type="number" min="0" step="100" name="modal-calculator-price" id="modal-calculator-price" class="form-control" placeholder="<?php esc_html_e('Enter Price', 'resideo'); ?>" autocomplete="off" aria-expanded="false" value="<?php echo esc_attr($calc_info['price']); ?>">
                                            <?php if ($calc_info['currency_pos'] == 'after') { ?>
                                                <div class="input-group-addon"><?php echo esc_html($calc_info['currency']); ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="label-block" for="modal-calculator-dp"><?php esc_html_e('Down Payment', 'resideo'); ?></label>
                                        <div class="input-group">
                                            <input type="number" min="0" step="1" max="100" name="modal-calculator-dp" id="modal-calculator-dp" class="form-control" placeholder="<?php esc_html_e('Enter Down Payment', 'resideo'); ?>" autocomplete="off" aria-expanded="false" value="20">
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="label-block" for="modal-calculator-term"><?php esc_html_e('Term', 'resideo'); ?></label>
                                        <div class="input-group">
                                            <input type="number" min="1" step="1" max="30" name="modal-calculator-term" id="modal-calculator-term" class="form-control" placeholder="<?php esc_html_e('Enter Price', 'resideo'); ?>" autocomplete="off" aria-expanded="false" value="30">
                                            <div class="input-group-addon"><?php esc_html_e('Years', 'resideo'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="label-block" for="modal-calculator-rate"><?php esc_html_e('Rate', 'resideo'); ?></label>
                                        <div class="input-group">
                                            <input type="text" name="modal-calculator-rate" id="modal-calculator-rate" class="form-control" placeholder="<?php esc_html_e('Enter Down Payment', 'resideo'); ?>" autocomplete="off" aria-expanded="false" value="4.25">
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row modal-calculator-result">
                                <div class="col-xs-6">
                                    <div class="modal-calculator-ma-text"><?php esc_html_e('Mortgage Amount', 'resideo'); ?></div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="modal-calculator-ma-val"></div>
                                </div>
                            </div>
                            <div class="row modal-calculator-result">
                                <div class="col-xs-6">
                                    <div class="modal-calculator-dpa-text"><?php esc_html_e('Down Payment Amount', 'resideo'); ?></div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="modal-calculator-dpa-val"></div>
                                </div>
                            </div>
                            <div class="modal-calculator-divider"></div>
                            <div class="row modal-calculator-result">
                                <div class="col-xs-6">
                                    <div class="modal-calculator-tmp-text"><?php esc_html_e('Total Monthly Payment', 'resideo'); ?></div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="modal-calculator-tmp-val"></div>
                                </div>
                            </div>

                            <?php wp_nonce_field('calculator_ajax_nonce', 'modal-calculator-security', true); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php }
endif;
?>