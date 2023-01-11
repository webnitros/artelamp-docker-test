/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 22.12.2022
 * Time: 1:39
 */ 
 miniShop2.plugin.mcCml = {
    getFields: function () {
        return {
            
                "barcode": {
                    xtype: 'textfield',
                    description: '<b>[[+barcode]]</b><br />' + _('ms2_product_barcode_help'),
                },
            
                "alias_tl": {
                    xtype: 'textfield',
                    description: '<b>[[+alias_tl]]</b><br />' + _('ms2_product_alias_tl_help'),
                },
            
                "show_artikul": {
                    xtype: 'textfield',
                    description: '<b>[[+show_artikul]]</b><br />' + _('ms2_product_show_artikul_help'),
                },
            
                "vendor_code": {
                    xtype: 'textfield',
                    description: '<b>[[+vendor_code]]</b><br />' + _('ms2_product_vendor_code_help'),
                },
            
                "weight_netto": {
                    xtype: 'numberfield',
                    description: '<b>[[+weight_netto]]</b><br />' + _('ms2_product_weight_netto_help'),
                    decimalPrecision: 3
                },
            
                "vid_vyklyuchatelya": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+vid_vyklyuchatelya]]</b><br />' + _('ms2_product_vid_vyklyuchatelya_help'),
                },
            
                "sub_category": {
                    xtype: 'textfield',
                    description: '<b>[[+sub_category]]</b><br />' + _('ms2_product_sub_category_help'),
                },
            
                "input_voltage_v": {
                    xtype: 'textfield',
                    description: '<b>[[+input_voltage_v]]</b><br />' + _('ms2_product_input_voltage_v_help'),
                },
            
                "Input_signal": {
                    xtype: 'textfield',
                    description: '<b>[[+Input_signal]]</b><br />' + _('ms2_product_Input_signal_help'),
                },
            
                "submit_to_divinare_it": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+submit_to_divinare_it]]</b><br />' + _('ms2_product_submit_to_divinare_it_help'),
                },
            
                "box_height": {
                    xtype: 'numberfield',
                    description: '<b>[[+box_height]]</b><br />' + _('ms2_product_box_height_help'),
                    decimalPrecision: 3
                },
            
                "vysota_plafona_abazhura_sm": {
                    xtype: 'numberfield',
                    description: '<b>[[+vysota_plafona_abazhura_sm]]</b><br />' + _('ms2_product_vysota_plafona_abazhura_sm_help'),
                    decimalPrecision: 3
                },
            
                "height": {
                    xtype: 'numberfield',
                    description: '<b>[[+height]]</b><br />' + _('ms2_product_height_help'),
                    decimalPrecision: 3
                },
            
                "output_power_w": {
                    xtype: 'textfield',
                    description: '<b>[[+output_power_w]]</b><br />' + _('ms2_product_output_power_w_help'),
                },
            
                "output_voltage_v": {
                    xtype: 'numberfield',
                    description: '<b>[[+output_voltage_v]]</b><br />' + _('ms2_product_output_voltage_v_help'),
                    decimalPrecision: 3
                },
            
                "output_signal": {
                    xtype: 'textfield',
                    description: '<b>[[+output_signal]]</b><br />' + _('ms2_product_output_signal_help'),
                },
            
                "output_current_a": {
                    xtype: 'textfield',
                    description: '<b>[[+output_current_a]]</b><br />' + _('ms2_product_output_current_a_help'),
                },
            
                "output_channels": {
                    xtype: 'numberfield',
                    description: '<b>[[+output_channels]]</b><br />' + _('ms2_product_output_channels_help'),
                },
            
                "garantiya": {
                    xtype: 'numberfield',
                    description: '<b>[[+garantiya]]</b><br />' + _('ms2_product_garantiya_help'),
                },
            
                "depth_vrezki": {
                    xtype: 'numberfield',
                    description: '<b>[[+depth_vrezki]]</b><br />' + _('ms2_product_depth_vrezki_help'),
                    decimalPrecision: 3
                },
            
                "diametr_vrezki": {
                    xtype: 'numberfield',
                    description: '<b>[[+diametr_vrezki]]</b><br />' + _('ms2_product_diametr_vrezki_help'),
                    decimalPrecision: 3
                },
            
                "diametr_plafona_sm": {
                    xtype: 'numberfield',
                    description: '<b>[[+diametr_plafona_sm]]</b><br />' + _('ms2_product_diametr_plafona_sm_help'),
                    decimalPrecision: 3
                },
            
                "diameter": {
                    xtype: 'numberfield',
                    description: '<b>[[+diameter]]</b><br />' + _('ms2_product_diameter_help'),
                    decimalPrecision: 3
                },
            
                "dimmer": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+dimmer]]</b><br />' + _('ms2_product_dimmer_help'),
                },
            
                "adapter_length_per_track": {
                    xtype: 'numberfield',
                    description: '<b>[[+adapter_length_per_track]]</b><br />' + _('ms2_product_adapter_length_per_track_help'),
                },
            
                "dlina_vrezki": {
                    xtype: 'numberfield',
                    description: '<b>[[+dlina_vrezki]]</b><br />' + _('ms2_product_dlina_vrezki_help'),
                    decimalPrecision: 3
                },
            
                "box_length": {
                    xtype: 'numberfield',
                    description: '<b>[[+box_length]]</b><br />' + _('ms2_product_box_length_help'),
                    decimalPrecision: 3
                },
            
                "track_length": {
                    xtype: 'numberfield',
                    description: '<b>[[+track_length]]</b><br />' + _('ms2_product_track_length_help'),
                },
            
                "length": {
                    xtype: 'numberfield',
                    description: '<b>[[+length]]</b><br />' + _('ms2_product_length_help'),
                    decimalPrecision: 3
                },
            
                "length_shnura": {
                    xtype: 'numberfield',
                    description: '<b>[[+length_shnura]]</b><br />' + _('ms2_product_length_shnura_help'),
                    decimalPrecision: 3
                },
            
                "dopolnitelno": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+dopolnitelno]]</b><br />' + _('ms2_product_dopolnitelno_help'),
                },
            
                "permissible_quantity_per_circuit_breaker_B16": {
                    xtype: 'numberfield',
                    description: '<b>[[+permissible_quantity_per_circuit_breaker_B16]]</b><br />' + _('ms2_product_permissible_quantity_per_circuit_breaker_B16_help'),
                    decimalPrecision: 3
                },
            
                "permissible_quantity_per_circuit_breaker_C16": {
                    xtype: 'numberfield',
                    description: '<b>[[+permissible_quantity_per_circuit_breaker_C16]]</b><br />' + _('ms2_product_permissible_quantity_per_circuit_breaker_C16_help'),
                    decimalPrecision: 3
                },
            
                "restrict_sale_online": {
                    xtype: 'textfield',
                    description: '<b>[[+restrict_sale_online]]</b><br />' + _('ms2_product_restrict_sale_online_help'),
                },
            
                "control_zones": {
                    xtype: 'numberfield',
                    description: '<b>[[+control_zones]]</b><br />' + _('ms2_product_control_zones_help'),
                },
            
                "color_rendering_index": {
                    xtype: 'textfield',
                    description: '<b>[[+color_rendering_index]]</b><br />' + _('ms2_product_color_rendering_index_help'),
                },
            
                "interer": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+interer]]</b><br />' + _('ms2_product_interer_help'),
                },
            
                "noise_level": {
                    xtype: 'textfield',
                    description: '<b>[[+noise_level]]</b><br />' + _('ms2_product_noise_level_help'),
                },
            
                "technical_cat": {
                    xtype: 'textfield',
                    description: '<b>[[+technical_cat]]</b><br />' + _('ms2_product_technical_cat_help'),
                },
            
                "num_of_socket": {
                    xtype: 'numberfield',
                    description: '<b>[[+num_of_socket]]</b><br />' + _('ms2_product_num_of_socket_help'),
                },
            
                "num_of_socket2": {
                    xtype: 'numberfield',
                    description: '<b>[[+num_of_socket2]]</b><br />' + _('ms2_product_num_of_socket2_help'),
                },
            
                "collection": {
                    xtype: 'textfield',
                    description: '<b>[[+collection]]</b><br />' + _('ms2_product_collection_help'),
                },
            
                "collection_web": {
                    xtype: 'textfield',
                    description: '<b>[[+collection_web]]</b><br />' + _('ms2_product_collection_web_help'),
                },
            
                "box_count": {
                    xtype: 'numberfield',
                    description: '<b>[[+box_count]]</b><br />' + _('ms2_product_box_count_help'),
                },
            
                "komplektaciya": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+komplektaciya]]</b><br />' + _('ms2_product_komplektaciya_help'),
                },
            
                "coefficient_power": {
                    xtype: 'numberfield',
                    description: '<b>[[+coefficient_power]]</b><br />' + _('ms2_product_coefficient_power_help'),
                    decimalPrecision: 3
                },
            
                "lamp_included": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+lamp_included]]</b><br />' + _('ms2_product_lamp_included_help'),
                },
            
                "vysota_max_sm": {
                    xtype: 'numberfield',
                    description: '<b>[[+vysota_max_sm]]</b><br />' + _('ms2_product_vysota_max_sm_help'),
                    decimalPrecision: 3
                },
            
                "armature_material": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+armature_material]]</b><br />' + _('ms2_product_armature_material_help'),
                },
            
                "housing_material": {
                    xtype: 'numberfield',
                    description: '<b>[[+housing_material]]</b><br />' + _('ms2_product_housing_material_help'),
                    decimalPrecision: 3
                },
            
                "plafond_material": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+plafond_material]]</b><br />' + _('ms2_product_plafond_material_help'),
                },
            
                "mesto_montaza": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+mesto_montaza]]</b><br />' + _('ms2_product_mesto_montaza_help'),
                },
            
                "mesto_prim": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+mesto_prim]]</b><br />' + _('ms2_product_mesto_prim_help'),
                },
            
                "vysota_min_sm": {
                    xtype: 'numberfield',
                    description: '<b>[[+vysota_min_sm]]</b><br />' + _('ms2_product_vysota_min_sm_help'),
                    decimalPrecision: 3
                },
            
                "power": {
                    xtype: 'numberfield',
                    description: '<b>[[+power]]</b><br />' + _('ms2_product_power_help'),
                },
            
                "power2": {
                    xtype: 'numberfield',
                    description: '<b>[[+power2]]</b><br />' + _('ms2_product_power2_help'),
                },
            
                "power3": {
                    xtype: 'numberfield',
                    description: '<b>[[+power3]]</b><br />' + _('ms2_product_power3_help'),
                },
            
                "power_w_m": {
                    xtype: 'numberfield',
                    description: '<b>[[+power_w_m]]</b><br />' + _('ms2_product_power_w_m_help'),
                    decimalPrecision: 3
                },
            
                "destination": {
                    xtype: 'textfield',
                    description: '<b>[[+destination]]</b><br />' + _('ms2_product_destination_help'),
                },
            
                "shade_direction": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+shade_direction]]</b><br />' + _('ms2_product_shade_direction_help'),
                },
            
                "napravlennyy_svet": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+napravlennyy_svet]]</b><br />' + _('ms2_product_napravlennyy_svet_help'),
                },
            
                "voltage": {
                    xtype: 'numberfield',
                    description: '<b>[[+voltage]]</b><br />' + _('ms2_product_voltage_help'),
                },
            
                "plafon_share": {
                    xtype: 'textfield',
                    description: '<b>[[+plafon_share]]</b><br />' + _('ms2_product_plafon_share_help'),
                },
            
                "osobennost": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+osobennost]]</b><br />' + _('ms2_product_osobennost_help'),
                },
            
                "ottenok": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+ottenok]]</b><br />' + _('ms2_product_ottenok_help'),
                },
            
                "lamp_socket": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+lamp_socket]]</b><br />' + _('ms2_product_lamp_socket_help'),
                },
            
                "lamp_socket2": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+lamp_socket2]]</b><br />' + _('ms2_product_lamp_socket2_help'),
                },
            
                "lamp_socket3": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+lamp_socket3]]</b><br />' + _('ms2_product_lamp_socket3_help'),
                },
            
                "led_density": {
                    xtype: 'numberfield',
                    description: '<b>[[+led_density]]</b><br />' + _('ms2_product_led_density_help'),
                    decimalPrecision: 3
                },
            
                "ploshad_osvesheniya": {
                    xtype: 'numberfield',
                    description: '<b>[[+ploshad_osvesheniya]]</b><br />' + _('ms2_product_ploshad_osvesheniya_help'),
                    decimalPrecision: 3
                },
            
                "technical_subcat": {
                    xtype: 'textfield',
                    description: '<b>[[+technical_subcat]]</b><br />' + _('ms2_product_technical_subcat_help'),
                },
            
                "current_consumption_a": {
                    xtype: 'textfield',
                    description: '<b>[[+current_consumption_a]]</b><br />' + _('ms2_product_current_consumption_a_help'),
                },
            
                "limit_input_voltage_v": {
                    xtype: 'textfield',
                    description: '<b>[[+limit_input_voltage_v]]</b><br />' + _('ms2_product_limit_input_voltage_v_help'),
                },
            
                "manufature": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+manufature]]</b><br />' + _('ms2_product_manufature_help'),
                },
            
                "pult": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+pult]]</b><br />' + _('ms2_product_pult_help'),
                },
            
                "starting_current_a": {
                    xtype: 'textfield',
                    description: '<b>[[+starting_current_a]]</b><br />' + _('ms2_product_starting_current_a_help'),
                },
            
                "working_temperature": {
                    xtype: 'textfield',
                    description: '<b>[[+working_temperature]]</b><br />' + _('ms2_product_working_temperature_help'),
                },
            
                "dissipated_max_power_w_m": {
                    xtype: 'numberfield',
                    description: '<b>[[+dissipated_max_power_w_m]]</b><br />' + _('ms2_product_dissipated_max_power_w_m_help'),
                    decimalPrecision: 3
                },
            
                "diffuser": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+diffuser]]</b><br />' + _('ms2_product_diffuser_help'),
                },
            
                "brightness_control": {
                    xtype: 'textfield',
                    description: '<b>[[+brightness_control]]</b><br />' + _('ms2_product_brightness_control_help'),
                },
            
                "candle_lamp": {
                    xtype: 'textfield',
                    description: '<b>[[+candle_lamp]]</b><br />' + _('ms2_product_candle_lamp_help'),
                },
            
                "light_flow": {
                    xtype: 'numberfield',
                    description: '<b>[[+light_flow]]</b><br />' + _('ms2_product_light_flow_help'),
                },
            
                "light_transmission": {
                    xtype: 'numberfield',
                    description: '<b>[[+light_transmission]]</b><br />' + _('ms2_product_light_transmission_help'),
                },
            
                "console_panel_properties": {
                    xtype: 'textfield',
                    description: '<b>[[+console_panel_properties]]</b><br />' + _('ms2_product_console_panel_properties_help'),
                },
            
                "specialization": {
                    xtype: 'textfield',
                    description: '<b>[[+specialization]]</b><br />' + _('ms2_product_specialization_help'),
                },
            
                "mounting_method": {
                    xtype: 'textfield',
                    description: '<b>[[+mounting_method]]</b><br />' + _('ms2_product_mounting_method_help'),
                },
            
                "life_time": {
                    xtype: 'numberfield',
                    description: '<b>[[+life_time]]</b><br />' + _('ms2_product_life_time_help'),
                },
            
                "video_link": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+video_link]]</b><br />' + _('ms2_product_video_link_help'),
                },
            
                "status_nomenklatury": {
                    xtype: 'textfield',
                    description: '<b>[[+status_nomenklatury]]</b><br />' + _('ms2_product_status_nomenklatury_help'),
                },
            
                "ip_class": {
                    xtype: 'numberfield',
                    description: '<b>[[+ip_class]]</b><br />' + _('ms2_product_ip_class_help'),
                },
            
                "lamp_style": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+lamp_style]]</b><br />' + _('ms2_product_lamp_style_help'),
                },
            
                "country": {
                    xtype: 'textfield',
                    description: '<b>[[+country]]</b><br />' + _('ms2_product_country_help'),
                },
            
                "country_orig": {
                    xtype: 'textfield',
                    description: '<b>[[+country_orig]]</b><br />' + _('ms2_product_country_orig_help'),
                },
            
                "sub_lin_razm": {
                    xtype: 'textfield',
                    description: '<b>[[+sub_lin_razm]]</b><br />' + _('ms2_product_sub_lin_razm_help'),
                },
            
                "sub_oc_razm": {
                    xtype: 'textfield',
                    description: '<b>[[+sub_oc_razm]]</b><br />' + _('ms2_product_sub_oc_razm_help'),
                },
            
                "block_type": {
                    xtype: 'textfield',
                    description: '<b>[[+block_type]]</b><br />' + _('ms2_product_block_type_help'),
                },
            
                "sensor_type": {
                    xtype: 'textfield',
                    description: '<b>[[+sensor_type]]</b><br />' + _('ms2_product_sensor_type_help'),
                },
            
                "krepej": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+krepej]]</b><br />' + _('ms2_product_krepej_help'),
                },
            
                "lamp_type2": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+lamp_type2]]</b><br />' + _('ms2_product_lamp_type2_help'),
                },
            
                "lamp_type3": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+lamp_type3]]</b><br />' + _('ms2_product_lamp_type3_help'),
                },
            
                "lamp_type": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+lamp_type]]</b><br />' + _('ms2_product_lamp_type_help'),
                },
            
                "type_of_tape_accessories": {
                    xtype: 'textfield',
                    description: '<b>[[+type_of_tape_accessories]]</b><br />' + _('ms2_product_type_of_tape_accessories_help'),
                },
            
                "tip_poverhnosti_plafonov_new": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+tip_poverhnosti_plafonov_new]]</b><br />' + _('ms2_product_tip_poverhnosti_plafonov_new_help'),
                },
            
                "tip_podklyucheniya_new": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+tip_podklyucheniya_new]]</b><br />' + _('ms2_product_tip_podklyucheniya_new_help'),
                },
            
                "profile_type": {
                    xtype: 'textfield',
                    description: '<b>[[+profile_type]]</b><br />' + _('ms2_product_profile_type_help'),
                },
            
                "category": {
                    xtype: 'textfield',
                    description: '<b>[[+category]]</b><br />' + _('ms2_product_category_help'),
                },
            
                "led_type": {
                    xtype: 'textfield',
                    description: '<b>[[+led_type]]</b><br />' + _('ms2_product_led_type_help'),
                },
            
                "product_type_for_light_control": {
                    xtype: 'textfield',
                    description: '<b>[[+product_type_for_light_control]]</b><br />' + _('ms2_product_product_type_for_light_control_help'),
                },
            
                "good_type_web": {
                    xtype: 'textfield',
                    description: '<b>[[+good_type_web]]</b><br />' + _('ms2_product_good_type_web_help'),
                },
            
                "tip_upravleniya": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+tip_upravleniya]]</b><br />' + _('ms2_product_tip_upravleniya_help'),
                },
            
                "product_disabled": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+product_disabled]]</b><br />' + _('ms2_product_product_disabled_help'),
                },
            
                "current_a": {
                    xtype: 'numberfield',
                    description: '<b>[[+current_a]]</b><br />' + _('ms2_product_current_a_help'),
                    decimalPrecision: 3
                },
            
                "load_current": {
                    xtype: 'textfield',
                    description: '<b>[[+load_current]]</b><br />' + _('ms2_product_load_current_help'),
                },
            
                "scattering_angle": {
                    xtype: 'numberfield',
                    description: '<b>[[+scattering_angle]]</b><br />' + _('ms2_product_scattering_angle_help'),
                    decimalPrecision: 3
                },
            
                "light_angle": {
                    xtype: 'numberfield',
                    description: '<b>[[+light_angle]]</b><br />' + _('ms2_product_light_angle_help'),
                },
            
                "form": {
                    xtype: 'textfield',
                    description: '<b>[[+form]]</b><br />' + _('ms2_product_form_help'),
                },
            
                "forma_plafona": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+forma_plafona]]</b><br />' + _('ms2_product_forma_plafona_help'),
                },
            
                "forma": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+forma]]</b><br />' + _('ms2_product_forma_help'),
                },
            
                "armature_color": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+armature_color]]</b><br />' + _('ms2_product_armature_color_help'),
                },
            
                "plafond_color": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+plafond_color]]</b><br />' + _('ms2_product_plafond_color_help'),
                },
            
                "light_temperatures2": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+light_temperatures2]]</b><br />' + _('ms2_product_light_temperatures2_help'),
                },
            
                "light_temperatures3": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+light_temperatures3]]</b><br />' + _('ms2_product_light_temperatures3_help'),
                },
            
                "light_temperatures": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+light_temperatures]]</b><br />' + _('ms2_product_light_temperatures_help'),
                },
            
                "tsvet_temp": {
                    xtype: 'minishop2-combo-options',
                    description: '<b>[[+tsvet_temp]]</b><br />' + _('ms2_product_tsvet_temp_help'),
                },
            
                "baud_rate": {
                    xtype: 'numberfield',
                    description: '<b>[[+baud_rate]]</b><br />' + _('ms2_product_baud_rate_help'),
                },
            
                "pwm_frequency": {
                    xtype: 'numberfield',
                    description: '<b>[[+pwm_frequency]]</b><br />' + _('ms2_product_pwm_frequency_help'),
                },
            
                "shirina_vrezki": {
                    xtype: 'numberfield',
                    description: '<b>[[+shirina_vrezki]]</b><br />' + _('ms2_product_shirina_vrezki_help'),
                    decimalPrecision: 3
                },
            
                "box_width": {
                    xtype: 'numberfield',
                    description: '<b>[[+box_width]]</b><br />' + _('ms2_product_box_width_help'),
                    decimalPrecision: 3
                },
            
                "width_for_led_strip_c": {
                    xtype: 'numberfield',
                    description: '<b>[[+width_for_led_strip_c]]</b><br />' + _('ms2_product_width_for_led_strip_c_help'),
                    decimalPrecision: 3
                },
            
                "width": {
                    xtype: 'numberfield',
                    description: '<b>[[+width]]</b><br />' + _('ms2_product_width_help'),
                    decimalPrecision: 3
                },
            
                "light_line_width_c": {
                    xtype: 'numberfield',
                    description: '<b>[[+light_line_width_c]]</b><br />' + _('ms2_product_light_line_width_c_help'),
                    decimalPrecision: 3
                },
            
                "stock": {
                    xtype: 'numberfield',
                    description: '<b>[[+stock]]</b><br />' + _('ms2_product_stock_help'),
                },
            
                "in_stock": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+in_stock]]</b><br />' + _('ms2_product_in_stock_help'),
                },
            
                "type_of_instalation": {
                    xtype: 'textfield',
                    description: '<b>[[+type_of_instalation]]</b><br />' + _('ms2_product_type_of_instalation_help'),
                },
            
                "price": {
                    xtype: 'numberfield',
                    description: '<b>[[+price]]</b><br />' + _('ms2_product_price_help'),
                    decimalPrecision: 2
                },
            
                "old_price": {
                    xtype: 'numberfield',
                    description: '<b>[[+old_price]]</b><br />' + _('ms2_product_old_price_help'),
                    decimalPrecision: 2
                },
            
                "is_price": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+is_price]]</b><br />' + _('ms2_product_is_price_help'),
                },
            
                "sale": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+sale]]</b><br />' + _('ms2_product_sale_help'),
                },
            
                "artikul_1c": {
                    xtype: 'textfield',
                    description: '<b>[[+artikul_1c]]</b><br />' + _('ms2_product_artikul_1c_help'),
                },
            
                "frame_type": {
                    xtype: 'textfield',
                    description: '<b>[[+frame_type]]</b><br />' + _('ms2_product_frame_type_help'),
                },
            
                "sertNumber": {
                    xtype: 'textfield',
                    description: '<b>[[+sertNumber]]</b><br />' + _('ms2_product_sertNumber_help'),
                },
            
                "class1": {
                    xtype: 'textfield',
                    description: '<b>[[+class1]]</b><br />' + _('ms2_product_class1_help'),
                },
            
                "class2": {
                    xtype: 'textfield',
                    description: '<b>[[+class2]]</b><br />' + _('ms2_product_class2_help'),
                },
            
                "class3": {
                    xtype: 'textfield',
                    description: '<b>[[+class3]]</b><br />' + _('ms2_product_class3_help'),
                },
            
                "class4": {
                    xtype: 'textfield',
                    description: '<b>[[+class4]]</b><br />' + _('ms2_product_class4_help'),
                },
            
                "height_in_assembly": {
                    xtype: 'numberfield',
                    description: '<b>[[+height_in_assembly]]</b><br />' + _('ms2_product_height_in_assembly_help'),
                    decimalPrecision: 3
                },
            
                "type_flask": {
                    xtype: 'textfield',
                    description: '<b>[[+type_flask]]</b><br />' + _('ms2_product_type_flask_help'),
                },
            
                "descriptionFrom1C": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+descriptionFrom1C]]</b><br />' + _('ms2_product_descriptionFrom1C_help'),
                },
            
                "file_is_3d_model": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+file_is_3d_model]]</b><br />' + _('ms2_product_file_is_3d_model_help'),
                },
            
                "video_link_new": {
                    xtype: 'textarea',
                    description: '<b>[[+video_link_new]]</b><br />' + _('ms2_product_video_link_new_help'),
                },
            
                "under_order": {
                    xtype: 'xcheckbox',
                    description: '<b>[[+under_order]]</b><br />' + _('ms2_product_under_order_help'),
                },
            
        } 
    },
    getColumns: function () {
        return {
            
                "barcode": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'barcode'
                    }
                },
            
                "alias_tl": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'alias_tl'
                    }
                },
            
                "show_artikul": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'show_artikul'
                    }
                },
            
                "vendor_code": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'vendor_code'
                    }
                },
            
                "weight_netto": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'weight_netto'
                    }
                },
            
                "vid_vyklyuchatelya": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'vid_vyklyuchatelya'
                    }
                },
            
                "sub_category": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'sub_category'
                    }
                },
            
                "input_voltage_v": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'input_voltage_v'
                    }
                },
            
                "Input_signal": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'Input_signal'
                    }
                },
            
                "submit_to_divinare_it": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'submit_to_divinare_it'
                    }
                },
            
                "box_height": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'box_height'
                    }
                },
            
                "vysota_plafona_abazhura_sm": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'vysota_plafona_abazhura_sm'
                    }
                },
            
                "height": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'height'
                    }
                },
            
                "output_power_w": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'output_power_w'
                    }
                },
            
                "output_voltage_v": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'output_voltage_v'
                    }
                },
            
                "output_signal": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'output_signal'
                    }
                },
            
                "output_current_a": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'output_current_a'
                    }
                },
            
                "output_channels": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'output_channels'
                    }
                },
            
                "garantiya": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'garantiya'
                    }
                },
            
                "depth_vrezki": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'depth_vrezki'
                    }
                },
            
                "diametr_vrezki": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'diametr_vrezki'
                    }
                },
            
                "diametr_plafona_sm": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'diametr_plafona_sm'
                    }
                },
            
                "diameter": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'diameter'
                    }
                },
            
                "dimmer": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'dimmer'
                    }
                },
            
                "adapter_length_per_track": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'adapter_length_per_track'
                    }
                },
            
                "dlina_vrezki": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'dlina_vrezki'
                    }
                },
            
                "box_length": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'box_length'
                    }
                },
            
                "track_length": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'track_length'
                    }
                },
            
                "length": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'length'
                    }
                },
            
                "length_shnura": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'length_shnura'
                    }
                },
            
                "dopolnitelno": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'dopolnitelno'
                    }
                },
            
                "permissible_quantity_per_circuit_breaker_B16": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'permissible_quantity_per_circuit_breaker_B16'
                    }
                },
            
                "permissible_quantity_per_circuit_breaker_C16": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'permissible_quantity_per_circuit_breaker_C16'
                    }
                },
            
                "restrict_sale_online": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'restrict_sale_online'
                    }
                },
            
                "control_zones": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'control_zones'
                    }
                },
            
                "color_rendering_index": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'color_rendering_index'
                    }
                },
            
                "interer": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'interer'
                    }
                },
            
                "noise_level": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'noise_level'
                    }
                },
            
                "technical_cat": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'technical_cat'
                    }
                },
            
                "num_of_socket": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'num_of_socket'
                    }
                },
            
                "num_of_socket2": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'num_of_socket2'
                    }
                },
            
                "collection": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'collection'
                    }
                },
            
                "collection_web": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'collection_web'
                    }
                },
            
                "box_count": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'box_count'
                    }
                },
            
                "komplektaciya": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'komplektaciya'
                    }
                },
            
                "coefficient_power": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'coefficient_power'
                    }
                },
            
                "lamp_included": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'lamp_included'
                    }
                },
            
                "vysota_max_sm": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'vysota_max_sm'
                    }
                },
            
                "armature_material": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'armature_material'
                    }
                },
            
                "housing_material": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'housing_material'
                    }
                },
            
                "plafond_material": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'plafond_material'
                    }
                },
            
                "mesto_montaza": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'mesto_montaza'
                    }
                },
            
                "mesto_prim": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'mesto_prim'
                    }
                },
            
                "vysota_min_sm": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'vysota_min_sm'
                    }
                },
            
                "power": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'power'
                    }
                },
            
                "power2": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'power2'
                    }
                },
            
                "power3": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'power3'
                    }
                },
            
                "power_w_m": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'power_w_m'
                    }
                },
            
                "destination": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'destination'
                    }
                },
            
                "shade_direction": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'shade_direction'
                    }
                },
            
                "napravlennyy_svet": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'napravlennyy_svet'
                    }
                },
            
                "voltage": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'voltage'
                    }
                },
            
                "plafon_share": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'plafon_share'
                    }
                },
            
                "osobennost": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'osobennost'
                    }
                },
            
                "ottenok": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'ottenok'
                    }
                },
            
                "lamp_socket": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'lamp_socket'
                    }
                },
            
                "lamp_socket2": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'lamp_socket2'
                    }
                },
            
                "lamp_socket3": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'lamp_socket3'
                    }
                },
            
                "led_density": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'led_density'
                    }
                },
            
                "ploshad_osvesheniya": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'ploshad_osvesheniya'
                    }
                },
            
                "technical_subcat": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'technical_subcat'
                    }
                },
            
                "current_consumption_a": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'current_consumption_a'
                    }
                },
            
                "limit_input_voltage_v": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'limit_input_voltage_v'
                    }
                },
            
                "manufature": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'manufature'
                    }
                },
            
                "pult": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'pult'
                    }
                },
            
                "starting_current_a": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'starting_current_a'
                    }
                },
            
                "working_temperature": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'working_temperature'
                    }
                },
            
                "dissipated_max_power_w_m": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'dissipated_max_power_w_m'
                    }
                },
            
                "diffuser": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'diffuser'
                    }
                },
            
                "brightness_control": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'brightness_control'
                    }
                },
            
                "candle_lamp": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'candle_lamp'
                    }
                },
            
                "light_flow": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'light_flow'
                    }
                },
            
                "light_transmission": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'light_transmission'
                    }
                },
            
                "console_panel_properties": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'console_panel_properties'
                    }
                },
            
                "specialization": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'specialization'
                    }
                },
            
                "mounting_method": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'mounting_method'
                    }
                },
            
                "life_time": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'life_time'
                    }
                },
            
                "video_link": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'video_link'
                    }
                },
            
                "status_nomenklatury": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'status_nomenklatury'
                    }
                },
            
                "ip_class": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'ip_class'
                    }
                },
            
                "lamp_style": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'lamp_style'
                    }
                },
            
                "country": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'country'
                    }
                },
            
                "country_orig": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'country_orig'
                    }
                },
            
                "sub_lin_razm": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'sub_lin_razm'
                    }
                },
            
                "sub_oc_razm": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'sub_oc_razm'
                    }
                },
            
                "block_type": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'block_type'
                    }
                },
            
                "sensor_type": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'sensor_type'
                    }
                },
            
                "krepej": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'krepej'
                    }
                },
            
                "lamp_type2": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'lamp_type2'
                    }
                },
            
                "lamp_type3": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'lamp_type3'
                    }
                },
            
                "lamp_type": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'lamp_type'
                    }
                },
            
                "type_of_tape_accessories": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'type_of_tape_accessories'
                    }
                },
            
                "tip_poverhnosti_plafonov_new": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'tip_poverhnosti_plafonov_new'
                    }
                },
            
                "tip_podklyucheniya_new": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'tip_podklyucheniya_new'
                    }
                },
            
                "profile_type": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'profile_type'
                    }
                },
            
                "category": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'category'
                    }
                },
            
                "led_type": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'led_type'
                    }
                },
            
                "product_type_for_light_control": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'product_type_for_light_control'
                    }
                },
            
                "good_type_web": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'good_type_web'
                    }
                },
            
                "tip_upravleniya": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'tip_upravleniya'
                    }
                },
            
                "product_disabled": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'product_disabled'
                    }
                },
            
                "current_a": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'current_a'
                    }
                },
            
                "load_current": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'load_current'
                    }
                },
            
                "scattering_angle": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'scattering_angle'
                    }
                },
            
                "light_angle": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'light_angle'
                    }
                },
            
                "form": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'form'
                    }
                },
            
                "forma_plafona": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'forma_plafona'
                    }
                },
            
                "forma": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'forma'
                    }
                },
            
                "armature_color": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'armature_color'
                    }
                },
            
                "plafond_color": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'plafond_color'
                    }
                },
            
                "light_temperatures2": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'light_temperatures2'
                    }
                },
            
                "light_temperatures3": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'light_temperatures3'
                    }
                },
            
                "light_temperatures": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'light_temperatures'
                    }
                },
            
                "tsvet_temp": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'minishop2-combo-options',
                        name: 'tsvet_temp'
                    }
                },
            
                "baud_rate": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'baud_rate'
                    }
                },
            
                "pwm_frequency": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'pwm_frequency'
                    }
                },
            
                "shirina_vrezki": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'shirina_vrezki'
                    }
                },
            
                "box_width": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'box_width'
                    }
                },
            
                "width_for_led_strip_c": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'width_for_led_strip_c'
                    }
                },
            
                "width": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'width'
                    }
                },
            
                "light_line_width_c": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'light_line_width_c'
                    }
                },
            
                "stock": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'stock'
                    }
                },
            
                "in_stock": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'in_stock'
                    }
                },
            
                "type_of_instalation": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'type_of_instalation'
                    }
                },
            
                "price": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'price'
                    }
                },
            
                "old_price": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'old_price'
                    }
                },
            
                "is_price": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'is_price'
                    }
                },
            
                "sale": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'sale'
                    }
                },
            
                "artikul_1c": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'artikul_1c'
                    }
                },
            
                "frame_type": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'frame_type'
                    }
                },
            
                "sertNumber": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'sertNumber'
                    }
                },
            
                "class1": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'class1'
                    }
                },
            
                "class2": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'class2'
                    }
                },
            
                "class3": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'class3'
                    }
                },
            
                "class4": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'class4'
                    }
                },
            
                "height_in_assembly": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'numberfield',
                        name: 'height_in_assembly'
                    }
                },
            
                "type_flask": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textfield',
                        name: 'type_flask'
                    }
                },
            
                "descriptionFrom1C": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'descriptionFrom1C'
                    }
                },
            
                "file_is_3d_model": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'file_is_3d_model'
                    }
                },
            
                "video_link_new": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'textarea',
                        name: 'video_link_new'
                    }
                },
            
                "under_order": {
                    width: 50,
                    sortable: false,
                    editor: {
                        xtype: 'xcheckbox',
                        name: 'under_order'
                    }
                },
            
        }
    }
}