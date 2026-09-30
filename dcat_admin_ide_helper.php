<?php

/**
 * A helper file for Dcat Admin, to provide autocomplete information to your IDE
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author jqh <841324345@qq.com>
 */

namespace Dcat\Admin {
    use Illuminate\Support\Collection;

    /**
     * @property Grid\Column|Collection created_at
     * @property Grid\Column|Collection detail
     * @property Grid\Column|Collection id
     * @property Grid\Column|Collection name
     * @property Grid\Column|Collection type
     * @property Grid\Column|Collection updated_at
     * @property Grid\Column|Collection version
     * @property Grid\Column|Collection is_enabled
     * @property Grid\Column|Collection icon
     * @property Grid\Column|Collection is_active
     * @property Grid\Column|Collection sort
     * @property Grid\Column|Collection category_id
     * @property Grid\Column|Collection content
     * @property Grid\Column|Collection link
     * @property Grid\Column|Collection link_target
     * @property Grid\Column|Collection extension
     * @property Grid\Column|Collection order
     * @property Grid\Column|Collection parent_id
     * @property Grid\Column|Collection uri
     * @property Grid\Column|Collection admin_user_id
     * @property Grid\Column|Collection notification_id
     * @property Grid\Column|Collection read_at
     * @property Grid\Column|Collection input
     * @property Grid\Column|Collection ip
     * @property Grid\Column|Collection method
     * @property Grid\Column|Collection panel_code
     * @property Grid\Column|Collection path
     * @property Grid\Column|Collection user_id
     * @property Grid\Column|Collection menu_id
     * @property Grid\Column|Collection permission_id
     * @property Grid\Column|Collection http_method
     * @property Grid\Column|Collection http_path
     * @property Grid\Column|Collection slug
     * @property Grid\Column|Collection role_id
     * @property Grid\Column|Collection group_name
     * @property Grid\Column|Collection value
     * @property Grid\Column|Collection avatar
     * @property Grid\Column|Collection default_role_id
     * @property Grid\Column|Collection email
     * @property Grid\Column|Collection password
     * @property Grid\Column|Collection remember_token
     * @property Grid\Column|Collection username
     * @property Grid\Column|Collection wx_openid
     * @property Grid\Column|Collection expiration
     * @property Grid\Column|Collection key
     * @property Grid\Column|Collection owner
     * @property Grid\Column|Collection category_count
     * @property Grid\Column|Collection category_level
     * @property Grid\Column|Collection category_name
     * @property Grid\Column|Collection deleted_at
     * @property Grid\Column|Collection img_url
     * @property Grid\Column|Collection sorted
     * @property Grid\Column|Collection url
     * @property Grid\Column|Collection exec_status
     * @property Grid\Column|Collection file_name
     * @property Grid\Column|Collection file_path
     * @property Grid\Column|Collection rfq_id
     * @property Grid\Column|Collection category_desc
     * @property Grid\Column|Collection mnf_count
     * @property Grid\Column|Collection mnf_desc
     * @property Grid\Column|Collection mnf_img
     * @property Grid\Column|Collection mnf_name
     * @property Grid\Column|Collection manufacturer_id
     * @property Grid\Column|Collection relation_type
     * @property Grid\Column|Collection mnf_id
     * @property Grid\Column|Collection code
     * @property Grid\Column|Collection remark
     * @property Grid\Column|Collection category_one_id
     * @property Grid\Column|Collection category_two_id
     * @property Grid\Column|Collection data_sheet_url
     * @property Grid\Column|Collection img
     * @property Grid\Column|Collection in_stock
     * @property Grid\Column|Collection is_battery
     * @property Grid\Column|Collection is_rohs
     * @property Grid\Column|Collection mpn
     * @property Grid\Column|Collection price_1
     * @property Grid\Column|Collection price_1_currency
     * @property Grid\Column|Collection price_2
     * @property Grid\Column|Collection price_2_currency
     * @property Grid\Column|Collection price_3
     * @property Grid\Column|Collection price_3_currency
     * @property Grid\Column|Collection price_4
     * @property Grid\Column|Collection price_4_currency
     * @property Grid\Column|Collection price_5
     * @property Grid\Column|Collection price_5_currency
     * @property Grid\Column|Collection price_6
     * @property Grid\Column|Collection price_6_currency
     * @property Grid\Column|Collection price_break_1
     * @property Grid\Column|Collection price_break_2
     * @property Grid\Column|Collection price_break_3
     * @property Grid\Column|Collection price_break_4
     * @property Grid\Column|Collection price_break_5
     * @property Grid\Column|Collection price_break_6
     * @property Grid\Column|Collection price_unit
     * @property Grid\Column|Collection product_desc
     * @property Grid\Column|Collection product_package
     * @property Grid\Column|Collection quantity
     * @property Grid\Column|Collection sku
     * @property Grid\Column|Collection specifications
     * @property Grid\Column|Collection standard_packing_quantity
     * @property Grid\Column|Collection weight
     * @property Grid\Column|Collection download_error
     * @property Grid\Column|Collection download_status
     * @property Grid\Column|Collection downloaded_at
     * @property Grid\Column|Collection file_size
     * @property Grid\Column|Collection local_path
     * @property Grid\Column|Collection md5_hash
     * @property Grid\Column|Collection retry_count
     * @property Grid\Column|Collection s3_key
     * @property Grid\Column|Collection param_name
     * @property Grid\Column|Collection param_unit
     * @property Grid\Column|Collection param_value
     * @property Grid\Column|Collection product_id
     * @property Grid\Column|Collection currency_code
     * @property Grid\Column|Collection price
     * @property Grid\Column|Collection stock
     * @property Grid\Column|Collection stock_type
     * @property Grid\Column|Collection accept_language
     * @property Grid\Column|Collection company
     * @property Grid\Column|Collection contact_name
     * @property Grid\Column|Collection ip_address
     * @property Grid\Column|Collection message
     * @property Grid\Column|Collection phone
     * @property Grid\Column|Collection source
     * @property Grid\Column|Collection upload_file_id
     * @property Grid\Column|Collection user_agent
     * @property Grid\Column|Collection manufacturer
     * @property Grid\Column|Collection target_price
     * @property Grid\Column|Collection param
     * @property Grid\Column|Collection connection
     * @property Grid\Column|Collection exception
     * @property Grid\Column|Collection failed_at
     * @property Grid\Column|Collection payload
     * @property Grid\Column|Collection queue
     * @property Grid\Column|Collection uuid
     * @property Grid\Column|Collection first_category_name
     * @property Grid\Column|Collection batch
     * @property Grid\Column|Collection check_label
     * @property Grid\Column|Collection check_name
     * @property Grid\Column|Collection ended_at
     * @property Grid\Column|Collection meta
     * @property Grid\Column|Collection notification_message
     * @property Grid\Column|Collection short_summary
     * @property Grid\Column|Collection status
     * @property Grid\Column|Collection cancelled_at
     * @property Grid\Column|Collection failed_job_ids
     * @property Grid\Column|Collection failed_jobs
     * @property Grid\Column|Collection finished_at
     * @property Grid\Column|Collection pending_jobs
     * @property Grid\Column|Collection total_jobs
     * @property Grid\Column|Collection attempts
     * @property Grid\Column|Collection available_at
     * @property Grid\Column|Collection reserved_at
     * @property Grid\Column|Collection is_index
     * @property Grid\Column|Collection manufacture_name
     * @property Grid\Column|Collection info_avatar
     * @property Grid\Column|Collection info_nick
     * @property Grid\Column|Collection member_user_id
     * @property Grid\Column|Collection open_id
     * @property Grid\Column|Collection avatar_big
     * @property Grid\Column|Collection avatar_medium
     * @property Grid\Column|Collection balance
     * @property Grid\Column|Collection delete_at_time
     * @property Grid\Column|Collection email_verified
     * @property Grid\Column|Collection freeze_price
     * @property Grid\Column|Collection gender
     * @property Grid\Column|Collection group_id
     * @property Grid\Column|Collection is_certified
     * @property Grid\Column|Collection is_deleted
     * @property Grid\Column|Collection junior_at
     * @property Grid\Column|Collection last_login_ip
     * @property Grid\Column|Collection last_login_time
     * @property Grid\Column|Collection message_count
     * @property Grid\Column|Collection nickname
     * @property Grid\Column|Collection phone_verified
     * @property Grid\Column|Collection points
     * @property Grid\Column|Collection realname
     * @property Grid\Column|Collection register_ip
     * @property Grid\Column|Collection signature
     * @property Grid\Column|Collection temp_parent_id
     * @property Grid\Column|Collection vip_expire
     * @property Grid\Column|Collection vip_id
     * @property Grid\Column|Collection message_id
     * @property Grid\Column|Collection uid
     * @property Grid\Column|Collection token
     * @property Grid\Column|Collection abilities
     * @property Grid\Column|Collection expires_at
     * @property Grid\Column|Collection last_used_at
     * @property Grid\Column|Collection tokenable_id
     * @property Grid\Column|Collection tokenable_type
     * @property Grid\Column|Collection datasheet_link
     * @property Grid\Column|Collection factory_lead_time
     * @property Grid\Column|Collection first_category_id
     * @property Grid\Column|Collection is_last_stock
     * @property Grid\Column|Collection manufacture_id
     * @property Grid\Column|Collection manufacturer_no
     * @property Grid\Column|Collection package
     * @property Grid\Column|Collection product_link
     * @property Grid\Column|Collection product_name
     * @property Grid\Column|Collection product_slug
     * @property Grid\Column|Collection published
     * @property Grid\Column|Collection date_code
     * @property Grid\Column|Collection info_id
     * @property Grid\Column|Collection mnf
     * @property Grid\Column|Collection part_no
     * @property Grid\Column|Collection qty
     * @property Grid\Column|Collection company_name
     * @property Grid\Column|Collection phone_no
     * @property Grid\Column|Collection admin_id
     * @property Grid\Column|Collection app_name
     * @property Grid\Column|Collection attr_name
     * @property Grid\Column|Collection attr_type
     * @property Grid\Column|Collection attr_value
     * @property Grid\Column|Collection created_name
     * @property Grid\Column|Collection end_time
     * @property Grid\Column|Collection request_type
     * @property Grid\Column|Collection response
     * @property Grid\Column|Collection response_type
     * @property Grid\Column|Collection start_time
     * @property Grid\Column|Collection task_status
     * @property Grid\Column|Collection task_type
     *
     * @method Grid\Column|Collection created_at(string $label = null)
     * @method Grid\Column|Collection detail(string $label = null)
     * @method Grid\Column|Collection id(string $label = null)
     * @method Grid\Column|Collection name(string $label = null)
     * @method Grid\Column|Collection type(string $label = null)
     * @method Grid\Column|Collection updated_at(string $label = null)
     * @method Grid\Column|Collection version(string $label = null)
     * @method Grid\Column|Collection is_enabled(string $label = null)
     * @method Grid\Column|Collection icon(string $label = null)
     * @method Grid\Column|Collection is_active(string $label = null)
     * @method Grid\Column|Collection sort(string $label = null)
     * @method Grid\Column|Collection category_id(string $label = null)
     * @method Grid\Column|Collection content(string $label = null)
     * @method Grid\Column|Collection link(string $label = null)
     * @method Grid\Column|Collection link_target(string $label = null)
     * @method Grid\Column|Collection extension(string $label = null)
     * @method Grid\Column|Collection order(string $label = null)
     * @method Grid\Column|Collection parent_id(string $label = null)
     * @method Grid\Column|Collection uri(string $label = null)
     * @method Grid\Column|Collection admin_user_id(string $label = null)
     * @method Grid\Column|Collection notification_id(string $label = null)
     * @method Grid\Column|Collection read_at(string $label = null)
     * @method Grid\Column|Collection input(string $label = null)
     * @method Grid\Column|Collection ip(string $label = null)
     * @method Grid\Column|Collection method(string $label = null)
     * @method Grid\Column|Collection panel_code(string $label = null)
     * @method Grid\Column|Collection path(string $label = null)
     * @method Grid\Column|Collection user_id(string $label = null)
     * @method Grid\Column|Collection menu_id(string $label = null)
     * @method Grid\Column|Collection permission_id(string $label = null)
     * @method Grid\Column|Collection http_method(string $label = null)
     * @method Grid\Column|Collection http_path(string $label = null)
     * @method Grid\Column|Collection slug(string $label = null)
     * @method Grid\Column|Collection role_id(string $label = null)
     * @method Grid\Column|Collection group_name(string $label = null)
     * @method Grid\Column|Collection value(string $label = null)
     * @method Grid\Column|Collection avatar(string $label = null)
     * @method Grid\Column|Collection default_role_id(string $label = null)
     * @method Grid\Column|Collection email(string $label = null)
     * @method Grid\Column|Collection password(string $label = null)
     * @method Grid\Column|Collection remember_token(string $label = null)
     * @method Grid\Column|Collection username(string $label = null)
     * @method Grid\Column|Collection wx_openid(string $label = null)
     * @method Grid\Column|Collection expiration(string $label = null)
     * @method Grid\Column|Collection key(string $label = null)
     * @method Grid\Column|Collection owner(string $label = null)
     * @method Grid\Column|Collection category_count(string $label = null)
     * @method Grid\Column|Collection category_level(string $label = null)
     * @method Grid\Column|Collection category_name(string $label = null)
     * @method Grid\Column|Collection deleted_at(string $label = null)
     * @method Grid\Column|Collection img_url(string $label = null)
     * @method Grid\Column|Collection sorted(string $label = null)
     * @method Grid\Column|Collection url(string $label = null)
     * @method Grid\Column|Collection exec_status(string $label = null)
     * @method Grid\Column|Collection file_name(string $label = null)
     * @method Grid\Column|Collection file_path(string $label = null)
     * @method Grid\Column|Collection rfq_id(string $label = null)
     * @method Grid\Column|Collection category_desc(string $label = null)
     * @method Grid\Column|Collection mnf_count(string $label = null)
     * @method Grid\Column|Collection mnf_desc(string $label = null)
     * @method Grid\Column|Collection mnf_img(string $label = null)
     * @method Grid\Column|Collection mnf_name(string $label = null)
     * @method Grid\Column|Collection manufacturer_id(string $label = null)
     * @method Grid\Column|Collection relation_type(string $label = null)
     * @method Grid\Column|Collection mnf_id(string $label = null)
     * @method Grid\Column|Collection code(string $label = null)
     * @method Grid\Column|Collection remark(string $label = null)
     * @method Grid\Column|Collection category_one_id(string $label = null)
     * @method Grid\Column|Collection category_two_id(string $label = null)
     * @method Grid\Column|Collection data_sheet_url(string $label = null)
     * @method Grid\Column|Collection img(string $label = null)
     * @method Grid\Column|Collection in_stock(string $label = null)
     * @method Grid\Column|Collection is_battery(string $label = null)
     * @method Grid\Column|Collection is_rohs(string $label = null)
     * @method Grid\Column|Collection mpn(string $label = null)
     * @method Grid\Column|Collection price_1(string $label = null)
     * @method Grid\Column|Collection price_1_currency(string $label = null)
     * @method Grid\Column|Collection price_2(string $label = null)
     * @method Grid\Column|Collection price_2_currency(string $label = null)
     * @method Grid\Column|Collection price_3(string $label = null)
     * @method Grid\Column|Collection price_3_currency(string $label = null)
     * @method Grid\Column|Collection price_4(string $label = null)
     * @method Grid\Column|Collection price_4_currency(string $label = null)
     * @method Grid\Column|Collection price_5(string $label = null)
     * @method Grid\Column|Collection price_5_currency(string $label = null)
     * @method Grid\Column|Collection price_6(string $label = null)
     * @method Grid\Column|Collection price_6_currency(string $label = null)
     * @method Grid\Column|Collection price_break_1(string $label = null)
     * @method Grid\Column|Collection price_break_2(string $label = null)
     * @method Grid\Column|Collection price_break_3(string $label = null)
     * @method Grid\Column|Collection price_break_4(string $label = null)
     * @method Grid\Column|Collection price_break_5(string $label = null)
     * @method Grid\Column|Collection price_break_6(string $label = null)
     * @method Grid\Column|Collection price_unit(string $label = null)
     * @method Grid\Column|Collection product_desc(string $label = null)
     * @method Grid\Column|Collection product_package(string $label = null)
     * @method Grid\Column|Collection quantity(string $label = null)
     * @method Grid\Column|Collection sku(string $label = null)
     * @method Grid\Column|Collection specifications(string $label = null)
     * @method Grid\Column|Collection standard_packing_quantity(string $label = null)
     * @method Grid\Column|Collection weight(string $label = null)
     * @method Grid\Column|Collection download_error(string $label = null)
     * @method Grid\Column|Collection download_status(string $label = null)
     * @method Grid\Column|Collection downloaded_at(string $label = null)
     * @method Grid\Column|Collection file_size(string $label = null)
     * @method Grid\Column|Collection local_path(string $label = null)
     * @method Grid\Column|Collection md5_hash(string $label = null)
     * @method Grid\Column|Collection retry_count(string $label = null)
     * @method Grid\Column|Collection s3_key(string $label = null)
     * @method Grid\Column|Collection param_name(string $label = null)
     * @method Grid\Column|Collection param_unit(string $label = null)
     * @method Grid\Column|Collection param_value(string $label = null)
     * @method Grid\Column|Collection product_id(string $label = null)
     * @method Grid\Column|Collection currency_code(string $label = null)
     * @method Grid\Column|Collection price(string $label = null)
     * @method Grid\Column|Collection stock(string $label = null)
     * @method Grid\Column|Collection stock_type(string $label = null)
     * @method Grid\Column|Collection accept_language(string $label = null)
     * @method Grid\Column|Collection company(string $label = null)
     * @method Grid\Column|Collection contact_name(string $label = null)
     * @method Grid\Column|Collection ip_address(string $label = null)
     * @method Grid\Column|Collection message(string $label = null)
     * @method Grid\Column|Collection phone(string $label = null)
     * @method Grid\Column|Collection source(string $label = null)
     * @method Grid\Column|Collection upload_file_id(string $label = null)
     * @method Grid\Column|Collection user_agent(string $label = null)
     * @method Grid\Column|Collection manufacturer(string $label = null)
     * @method Grid\Column|Collection target_price(string $label = null)
     * @method Grid\Column|Collection param(string $label = null)
     * @method Grid\Column|Collection connection(string $label = null)
     * @method Grid\Column|Collection exception(string $label = null)
     * @method Grid\Column|Collection failed_at(string $label = null)
     * @method Grid\Column|Collection payload(string $label = null)
     * @method Grid\Column|Collection queue(string $label = null)
     * @method Grid\Column|Collection uuid(string $label = null)
     * @method Grid\Column|Collection first_category_name(string $label = null)
     * @method Grid\Column|Collection batch(string $label = null)
     * @method Grid\Column|Collection check_label(string $label = null)
     * @method Grid\Column|Collection check_name(string $label = null)
     * @method Grid\Column|Collection ended_at(string $label = null)
     * @method Grid\Column|Collection meta(string $label = null)
     * @method Grid\Column|Collection notification_message(string $label = null)
     * @method Grid\Column|Collection short_summary(string $label = null)
     * @method Grid\Column|Collection status(string $label = null)
     * @method Grid\Column|Collection cancelled_at(string $label = null)
     * @method Grid\Column|Collection failed_job_ids(string $label = null)
     * @method Grid\Column|Collection failed_jobs(string $label = null)
     * @method Grid\Column|Collection finished_at(string $label = null)
     * @method Grid\Column|Collection pending_jobs(string $label = null)
     * @method Grid\Column|Collection total_jobs(string $label = null)
     * @method Grid\Column|Collection attempts(string $label = null)
     * @method Grid\Column|Collection available_at(string $label = null)
     * @method Grid\Column|Collection reserved_at(string $label = null)
     * @method Grid\Column|Collection is_index(string $label = null)
     * @method Grid\Column|Collection manufacture_name(string $label = null)
     * @method Grid\Column|Collection info_avatar(string $label = null)
     * @method Grid\Column|Collection info_nick(string $label = null)
     * @method Grid\Column|Collection member_user_id(string $label = null)
     * @method Grid\Column|Collection open_id(string $label = null)
     * @method Grid\Column|Collection avatar_big(string $label = null)
     * @method Grid\Column|Collection avatar_medium(string $label = null)
     * @method Grid\Column|Collection balance(string $label = null)
     * @method Grid\Column|Collection delete_at_time(string $label = null)
     * @method Grid\Column|Collection email_verified(string $label = null)
     * @method Grid\Column|Collection freeze_price(string $label = null)
     * @method Grid\Column|Collection gender(string $label = null)
     * @method Grid\Column|Collection group_id(string $label = null)
     * @method Grid\Column|Collection is_certified(string $label = null)
     * @method Grid\Column|Collection is_deleted(string $label = null)
     * @method Grid\Column|Collection junior_at(string $label = null)
     * @method Grid\Column|Collection last_login_ip(string $label = null)
     * @method Grid\Column|Collection last_login_time(string $label = null)
     * @method Grid\Column|Collection message_count(string $label = null)
     * @method Grid\Column|Collection nickname(string $label = null)
     * @method Grid\Column|Collection phone_verified(string $label = null)
     * @method Grid\Column|Collection points(string $label = null)
     * @method Grid\Column|Collection realname(string $label = null)
     * @method Grid\Column|Collection register_ip(string $label = null)
     * @method Grid\Column|Collection signature(string $label = null)
     * @method Grid\Column|Collection temp_parent_id(string $label = null)
     * @method Grid\Column|Collection vip_expire(string $label = null)
     * @method Grid\Column|Collection vip_id(string $label = null)
     * @method Grid\Column|Collection message_id(string $label = null)
     * @method Grid\Column|Collection uid(string $label = null)
     * @method Grid\Column|Collection token(string $label = null)
     * @method Grid\Column|Collection abilities(string $label = null)
     * @method Grid\Column|Collection expires_at(string $label = null)
     * @method Grid\Column|Collection last_used_at(string $label = null)
     * @method Grid\Column|Collection tokenable_id(string $label = null)
     * @method Grid\Column|Collection tokenable_type(string $label = null)
     * @method Grid\Column|Collection datasheet_link(string $label = null)
     * @method Grid\Column|Collection factory_lead_time(string $label = null)
     * @method Grid\Column|Collection first_category_id(string $label = null)
     * @method Grid\Column|Collection is_last_stock(string $label = null)
     * @method Grid\Column|Collection manufacture_id(string $label = null)
     * @method Grid\Column|Collection manufacturer_no(string $label = null)
     * @method Grid\Column|Collection package(string $label = null)
     * @method Grid\Column|Collection product_link(string $label = null)
     * @method Grid\Column|Collection product_name(string $label = null)
     * @method Grid\Column|Collection product_slug(string $label = null)
     * @method Grid\Column|Collection published(string $label = null)
     * @method Grid\Column|Collection date_code(string $label = null)
     * @method Grid\Column|Collection info_id(string $label = null)
     * @method Grid\Column|Collection mnf(string $label = null)
     * @method Grid\Column|Collection part_no(string $label = null)
     * @method Grid\Column|Collection qty(string $label = null)
     * @method Grid\Column|Collection company_name(string $label = null)
     * @method Grid\Column|Collection phone_no(string $label = null)
     * @method Grid\Column|Collection admin_id(string $label = null)
     * @method Grid\Column|Collection app_name(string $label = null)
     * @method Grid\Column|Collection attr_name(string $label = null)
     * @method Grid\Column|Collection attr_type(string $label = null)
     * @method Grid\Column|Collection attr_value(string $label = null)
     * @method Grid\Column|Collection created_name(string $label = null)
     * @method Grid\Column|Collection end_time(string $label = null)
     * @method Grid\Column|Collection request_type(string $label = null)
     * @method Grid\Column|Collection response(string $label = null)
     * @method Grid\Column|Collection response_type(string $label = null)
     * @method Grid\Column|Collection start_time(string $label = null)
     * @method Grid\Column|Collection task_status(string $label = null)
     * @method Grid\Column|Collection task_type(string $label = null)
     */
    class Grid {}

    class MiniGrid extends Grid {}

    /**
     * @property Show\Field|Collection created_at
     * @property Show\Field|Collection detail
     * @property Show\Field|Collection id
     * @property Show\Field|Collection name
     * @property Show\Field|Collection type
     * @property Show\Field|Collection updated_at
     * @property Show\Field|Collection version
     * @property Show\Field|Collection is_enabled
     * @property Show\Field|Collection icon
     * @property Show\Field|Collection is_active
     * @property Show\Field|Collection sort
     * @property Show\Field|Collection category_id
     * @property Show\Field|Collection content
     * @property Show\Field|Collection link
     * @property Show\Field|Collection link_target
     * @property Show\Field|Collection extension
     * @property Show\Field|Collection order
     * @property Show\Field|Collection parent_id
     * @property Show\Field|Collection uri
     * @property Show\Field|Collection admin_user_id
     * @property Show\Field|Collection notification_id
     * @property Show\Field|Collection read_at
     * @property Show\Field|Collection input
     * @property Show\Field|Collection ip
     * @property Show\Field|Collection method
     * @property Show\Field|Collection panel_code
     * @property Show\Field|Collection path
     * @property Show\Field|Collection user_id
     * @property Show\Field|Collection menu_id
     * @property Show\Field|Collection permission_id
     * @property Show\Field|Collection http_method
     * @property Show\Field|Collection http_path
     * @property Show\Field|Collection slug
     * @property Show\Field|Collection role_id
     * @property Show\Field|Collection group_name
     * @property Show\Field|Collection value
     * @property Show\Field|Collection avatar
     * @property Show\Field|Collection default_role_id
     * @property Show\Field|Collection email
     * @property Show\Field|Collection password
     * @property Show\Field|Collection remember_token
     * @property Show\Field|Collection username
     * @property Show\Field|Collection wx_openid
     * @property Show\Field|Collection expiration
     * @property Show\Field|Collection key
     * @property Show\Field|Collection owner
     * @property Show\Field|Collection category_count
     * @property Show\Field|Collection category_level
     * @property Show\Field|Collection category_name
     * @property Show\Field|Collection deleted_at
     * @property Show\Field|Collection img_url
     * @property Show\Field|Collection sorted
     * @property Show\Field|Collection url
     * @property Show\Field|Collection exec_status
     * @property Show\Field|Collection file_name
     * @property Show\Field|Collection file_path
     * @property Show\Field|Collection rfq_id
     * @property Show\Field|Collection category_desc
     * @property Show\Field|Collection mnf_count
     * @property Show\Field|Collection mnf_desc
     * @property Show\Field|Collection mnf_img
     * @property Show\Field|Collection mnf_name
     * @property Show\Field|Collection manufacturer_id
     * @property Show\Field|Collection relation_type
     * @property Show\Field|Collection mnf_id
     * @property Show\Field|Collection code
     * @property Show\Field|Collection remark
     * @property Show\Field|Collection category_one_id
     * @property Show\Field|Collection category_two_id
     * @property Show\Field|Collection data_sheet_url
     * @property Show\Field|Collection img
     * @property Show\Field|Collection in_stock
     * @property Show\Field|Collection is_battery
     * @property Show\Field|Collection is_rohs
     * @property Show\Field|Collection mpn
     * @property Show\Field|Collection price_1
     * @property Show\Field|Collection price_1_currency
     * @property Show\Field|Collection price_2
     * @property Show\Field|Collection price_2_currency
     * @property Show\Field|Collection price_3
     * @property Show\Field|Collection price_3_currency
     * @property Show\Field|Collection price_4
     * @property Show\Field|Collection price_4_currency
     * @property Show\Field|Collection price_5
     * @property Show\Field|Collection price_5_currency
     * @property Show\Field|Collection price_6
     * @property Show\Field|Collection price_6_currency
     * @property Show\Field|Collection price_break_1
     * @property Show\Field|Collection price_break_2
     * @property Show\Field|Collection price_break_3
     * @property Show\Field|Collection price_break_4
     * @property Show\Field|Collection price_break_5
     * @property Show\Field|Collection price_break_6
     * @property Show\Field|Collection price_unit
     * @property Show\Field|Collection product_desc
     * @property Show\Field|Collection product_package
     * @property Show\Field|Collection quantity
     * @property Show\Field|Collection sku
     * @property Show\Field|Collection specifications
     * @property Show\Field|Collection standard_packing_quantity
     * @property Show\Field|Collection weight
     * @property Show\Field|Collection download_error
     * @property Show\Field|Collection download_status
     * @property Show\Field|Collection downloaded_at
     * @property Show\Field|Collection file_size
     * @property Show\Field|Collection local_path
     * @property Show\Field|Collection md5_hash
     * @property Show\Field|Collection retry_count
     * @property Show\Field|Collection s3_key
     * @property Show\Field|Collection param_name
     * @property Show\Field|Collection param_unit
     * @property Show\Field|Collection param_value
     * @property Show\Field|Collection product_id
     * @property Show\Field|Collection currency_code
     * @property Show\Field|Collection price
     * @property Show\Field|Collection stock
     * @property Show\Field|Collection stock_type
     * @property Show\Field|Collection accept_language
     * @property Show\Field|Collection company
     * @property Show\Field|Collection contact_name
     * @property Show\Field|Collection ip_address
     * @property Show\Field|Collection message
     * @property Show\Field|Collection phone
     * @property Show\Field|Collection source
     * @property Show\Field|Collection upload_file_id
     * @property Show\Field|Collection user_agent
     * @property Show\Field|Collection manufacturer
     * @property Show\Field|Collection target_price
     * @property Show\Field|Collection param
     * @property Show\Field|Collection connection
     * @property Show\Field|Collection exception
     * @property Show\Field|Collection failed_at
     * @property Show\Field|Collection payload
     * @property Show\Field|Collection queue
     * @property Show\Field|Collection uuid
     * @property Show\Field|Collection first_category_name
     * @property Show\Field|Collection batch
     * @property Show\Field|Collection check_label
     * @property Show\Field|Collection check_name
     * @property Show\Field|Collection ended_at
     * @property Show\Field|Collection meta
     * @property Show\Field|Collection notification_message
     * @property Show\Field|Collection short_summary
     * @property Show\Field|Collection status
     * @property Show\Field|Collection cancelled_at
     * @property Show\Field|Collection failed_job_ids
     * @property Show\Field|Collection failed_jobs
     * @property Show\Field|Collection finished_at
     * @property Show\Field|Collection pending_jobs
     * @property Show\Field|Collection total_jobs
     * @property Show\Field|Collection attempts
     * @property Show\Field|Collection available_at
     * @property Show\Field|Collection reserved_at
     * @property Show\Field|Collection is_index
     * @property Show\Field|Collection manufacture_name
     * @property Show\Field|Collection info_avatar
     * @property Show\Field|Collection info_nick
     * @property Show\Field|Collection member_user_id
     * @property Show\Field|Collection open_id
     * @property Show\Field|Collection avatar_big
     * @property Show\Field|Collection avatar_medium
     * @property Show\Field|Collection balance
     * @property Show\Field|Collection delete_at_time
     * @property Show\Field|Collection email_verified
     * @property Show\Field|Collection freeze_price
     * @property Show\Field|Collection gender
     * @property Show\Field|Collection group_id
     * @property Show\Field|Collection is_certified
     * @property Show\Field|Collection is_deleted
     * @property Show\Field|Collection junior_at
     * @property Show\Field|Collection last_login_ip
     * @property Show\Field|Collection last_login_time
     * @property Show\Field|Collection message_count
     * @property Show\Field|Collection nickname
     * @property Show\Field|Collection phone_verified
     * @property Show\Field|Collection points
     * @property Show\Field|Collection realname
     * @property Show\Field|Collection register_ip
     * @property Show\Field|Collection signature
     * @property Show\Field|Collection temp_parent_id
     * @property Show\Field|Collection vip_expire
     * @property Show\Field|Collection vip_id
     * @property Show\Field|Collection message_id
     * @property Show\Field|Collection uid
     * @property Show\Field|Collection token
     * @property Show\Field|Collection abilities
     * @property Show\Field|Collection expires_at
     * @property Show\Field|Collection last_used_at
     * @property Show\Field|Collection tokenable_id
     * @property Show\Field|Collection tokenable_type
     * @property Show\Field|Collection datasheet_link
     * @property Show\Field|Collection factory_lead_time
     * @property Show\Field|Collection first_category_id
     * @property Show\Field|Collection is_last_stock
     * @property Show\Field|Collection manufacture_id
     * @property Show\Field|Collection manufacturer_no
     * @property Show\Field|Collection package
     * @property Show\Field|Collection product_link
     * @property Show\Field|Collection product_name
     * @property Show\Field|Collection product_slug
     * @property Show\Field|Collection published
     * @property Show\Field|Collection date_code
     * @property Show\Field|Collection info_id
     * @property Show\Field|Collection mnf
     * @property Show\Field|Collection part_no
     * @property Show\Field|Collection qty
     * @property Show\Field|Collection company_name
     * @property Show\Field|Collection phone_no
     * @property Show\Field|Collection admin_id
     * @property Show\Field|Collection app_name
     * @property Show\Field|Collection attr_name
     * @property Show\Field|Collection attr_type
     * @property Show\Field|Collection attr_value
     * @property Show\Field|Collection created_name
     * @property Show\Field|Collection end_time
     * @property Show\Field|Collection request_type
     * @property Show\Field|Collection response
     * @property Show\Field|Collection response_type
     * @property Show\Field|Collection start_time
     * @property Show\Field|Collection task_status
     * @property Show\Field|Collection task_type
     *
     * @method Show\Field|Collection created_at(string $label = null)
     * @method Show\Field|Collection detail(string $label = null)
     * @method Show\Field|Collection id(string $label = null)
     * @method Show\Field|Collection name(string $label = null)
     * @method Show\Field|Collection type(string $label = null)
     * @method Show\Field|Collection updated_at(string $label = null)
     * @method Show\Field|Collection version(string $label = null)
     * @method Show\Field|Collection is_enabled(string $label = null)
     * @method Show\Field|Collection icon(string $label = null)
     * @method Show\Field|Collection is_active(string $label = null)
     * @method Show\Field|Collection sort(string $label = null)
     * @method Show\Field|Collection category_id(string $label = null)
     * @method Show\Field|Collection content(string $label = null)
     * @method Show\Field|Collection link(string $label = null)
     * @method Show\Field|Collection link_target(string $label = null)
     * @method Show\Field|Collection extension(string $label = null)
     * @method Show\Field|Collection order(string $label = null)
     * @method Show\Field|Collection parent_id(string $label = null)
     * @method Show\Field|Collection uri(string $label = null)
     * @method Show\Field|Collection admin_user_id(string $label = null)
     * @method Show\Field|Collection notification_id(string $label = null)
     * @method Show\Field|Collection read_at(string $label = null)
     * @method Show\Field|Collection input(string $label = null)
     * @method Show\Field|Collection ip(string $label = null)
     * @method Show\Field|Collection method(string $label = null)
     * @method Show\Field|Collection panel_code(string $label = null)
     * @method Show\Field|Collection path(string $label = null)
     * @method Show\Field|Collection user_id(string $label = null)
     * @method Show\Field|Collection menu_id(string $label = null)
     * @method Show\Field|Collection permission_id(string $label = null)
     * @method Show\Field|Collection http_method(string $label = null)
     * @method Show\Field|Collection http_path(string $label = null)
     * @method Show\Field|Collection slug(string $label = null)
     * @method Show\Field|Collection role_id(string $label = null)
     * @method Show\Field|Collection group_name(string $label = null)
     * @method Show\Field|Collection value(string $label = null)
     * @method Show\Field|Collection avatar(string $label = null)
     * @method Show\Field|Collection default_role_id(string $label = null)
     * @method Show\Field|Collection email(string $label = null)
     * @method Show\Field|Collection password(string $label = null)
     * @method Show\Field|Collection remember_token(string $label = null)
     * @method Show\Field|Collection username(string $label = null)
     * @method Show\Field|Collection wx_openid(string $label = null)
     * @method Show\Field|Collection expiration(string $label = null)
     * @method Show\Field|Collection key(string $label = null)
     * @method Show\Field|Collection owner(string $label = null)
     * @method Show\Field|Collection category_count(string $label = null)
     * @method Show\Field|Collection category_level(string $label = null)
     * @method Show\Field|Collection category_name(string $label = null)
     * @method Show\Field|Collection deleted_at(string $label = null)
     * @method Show\Field|Collection img_url(string $label = null)
     * @method Show\Field|Collection sorted(string $label = null)
     * @method Show\Field|Collection url(string $label = null)
     * @method Show\Field|Collection exec_status(string $label = null)
     * @method Show\Field|Collection file_name(string $label = null)
     * @method Show\Field|Collection file_path(string $label = null)
     * @method Show\Field|Collection rfq_id(string $label = null)
     * @method Show\Field|Collection category_desc(string $label = null)
     * @method Show\Field|Collection mnf_count(string $label = null)
     * @method Show\Field|Collection mnf_desc(string $label = null)
     * @method Show\Field|Collection mnf_img(string $label = null)
     * @method Show\Field|Collection mnf_name(string $label = null)
     * @method Show\Field|Collection manufacturer_id(string $label = null)
     * @method Show\Field|Collection relation_type(string $label = null)
     * @method Show\Field|Collection mnf_id(string $label = null)
     * @method Show\Field|Collection code(string $label = null)
     * @method Show\Field|Collection remark(string $label = null)
     * @method Show\Field|Collection category_one_id(string $label = null)
     * @method Show\Field|Collection category_two_id(string $label = null)
     * @method Show\Field|Collection data_sheet_url(string $label = null)
     * @method Show\Field|Collection img(string $label = null)
     * @method Show\Field|Collection in_stock(string $label = null)
     * @method Show\Field|Collection is_battery(string $label = null)
     * @method Show\Field|Collection is_rohs(string $label = null)
     * @method Show\Field|Collection mpn(string $label = null)
     * @method Show\Field|Collection price_1(string $label = null)
     * @method Show\Field|Collection price_1_currency(string $label = null)
     * @method Show\Field|Collection price_2(string $label = null)
     * @method Show\Field|Collection price_2_currency(string $label = null)
     * @method Show\Field|Collection price_3(string $label = null)
     * @method Show\Field|Collection price_3_currency(string $label = null)
     * @method Show\Field|Collection price_4(string $label = null)
     * @method Show\Field|Collection price_4_currency(string $label = null)
     * @method Show\Field|Collection price_5(string $label = null)
     * @method Show\Field|Collection price_5_currency(string $label = null)
     * @method Show\Field|Collection price_6(string $label = null)
     * @method Show\Field|Collection price_6_currency(string $label = null)
     * @method Show\Field|Collection price_break_1(string $label = null)
     * @method Show\Field|Collection price_break_2(string $label = null)
     * @method Show\Field|Collection price_break_3(string $label = null)
     * @method Show\Field|Collection price_break_4(string $label = null)
     * @method Show\Field|Collection price_break_5(string $label = null)
     * @method Show\Field|Collection price_break_6(string $label = null)
     * @method Show\Field|Collection price_unit(string $label = null)
     * @method Show\Field|Collection product_desc(string $label = null)
     * @method Show\Field|Collection product_package(string $label = null)
     * @method Show\Field|Collection quantity(string $label = null)
     * @method Show\Field|Collection sku(string $label = null)
     * @method Show\Field|Collection specifications(string $label = null)
     * @method Show\Field|Collection standard_packing_quantity(string $label = null)
     * @method Show\Field|Collection weight(string $label = null)
     * @method Show\Field|Collection download_error(string $label = null)
     * @method Show\Field|Collection download_status(string $label = null)
     * @method Show\Field|Collection downloaded_at(string $label = null)
     * @method Show\Field|Collection file_size(string $label = null)
     * @method Show\Field|Collection local_path(string $label = null)
     * @method Show\Field|Collection md5_hash(string $label = null)
     * @method Show\Field|Collection retry_count(string $label = null)
     * @method Show\Field|Collection s3_key(string $label = null)
     * @method Show\Field|Collection param_name(string $label = null)
     * @method Show\Field|Collection param_unit(string $label = null)
     * @method Show\Field|Collection param_value(string $label = null)
     * @method Show\Field|Collection product_id(string $label = null)
     * @method Show\Field|Collection currency_code(string $label = null)
     * @method Show\Field|Collection price(string $label = null)
     * @method Show\Field|Collection stock(string $label = null)
     * @method Show\Field|Collection stock_type(string $label = null)
     * @method Show\Field|Collection accept_language(string $label = null)
     * @method Show\Field|Collection company(string $label = null)
     * @method Show\Field|Collection contact_name(string $label = null)
     * @method Show\Field|Collection ip_address(string $label = null)
     * @method Show\Field|Collection message(string $label = null)
     * @method Show\Field|Collection phone(string $label = null)
     * @method Show\Field|Collection source(string $label = null)
     * @method Show\Field|Collection upload_file_id(string $label = null)
     * @method Show\Field|Collection user_agent(string $label = null)
     * @method Show\Field|Collection manufacturer(string $label = null)
     * @method Show\Field|Collection target_price(string $label = null)
     * @method Show\Field|Collection param(string $label = null)
     * @method Show\Field|Collection connection(string $label = null)
     * @method Show\Field|Collection exception(string $label = null)
     * @method Show\Field|Collection failed_at(string $label = null)
     * @method Show\Field|Collection payload(string $label = null)
     * @method Show\Field|Collection queue(string $label = null)
     * @method Show\Field|Collection uuid(string $label = null)
     * @method Show\Field|Collection first_category_name(string $label = null)
     * @method Show\Field|Collection batch(string $label = null)
     * @method Show\Field|Collection check_label(string $label = null)
     * @method Show\Field|Collection check_name(string $label = null)
     * @method Show\Field|Collection ended_at(string $label = null)
     * @method Show\Field|Collection meta(string $label = null)
     * @method Show\Field|Collection notification_message(string $label = null)
     * @method Show\Field|Collection short_summary(string $label = null)
     * @method Show\Field|Collection status(string $label = null)
     * @method Show\Field|Collection cancelled_at(string $label = null)
     * @method Show\Field|Collection failed_job_ids(string $label = null)
     * @method Show\Field|Collection failed_jobs(string $label = null)
     * @method Show\Field|Collection finished_at(string $label = null)
     * @method Show\Field|Collection pending_jobs(string $label = null)
     * @method Show\Field|Collection total_jobs(string $label = null)
     * @method Show\Field|Collection attempts(string $label = null)
     * @method Show\Field|Collection available_at(string $label = null)
     * @method Show\Field|Collection reserved_at(string $label = null)
     * @method Show\Field|Collection is_index(string $label = null)
     * @method Show\Field|Collection manufacture_name(string $label = null)
     * @method Show\Field|Collection info_avatar(string $label = null)
     * @method Show\Field|Collection info_nick(string $label = null)
     * @method Show\Field|Collection member_user_id(string $label = null)
     * @method Show\Field|Collection open_id(string $label = null)
     * @method Show\Field|Collection avatar_big(string $label = null)
     * @method Show\Field|Collection avatar_medium(string $label = null)
     * @method Show\Field|Collection balance(string $label = null)
     * @method Show\Field|Collection delete_at_time(string $label = null)
     * @method Show\Field|Collection email_verified(string $label = null)
     * @method Show\Field|Collection freeze_price(string $label = null)
     * @method Show\Field|Collection gender(string $label = null)
     * @method Show\Field|Collection group_id(string $label = null)
     * @method Show\Field|Collection is_certified(string $label = null)
     * @method Show\Field|Collection is_deleted(string $label = null)
     * @method Show\Field|Collection junior_at(string $label = null)
     * @method Show\Field|Collection last_login_ip(string $label = null)
     * @method Show\Field|Collection last_login_time(string $label = null)
     * @method Show\Field|Collection message_count(string $label = null)
     * @method Show\Field|Collection nickname(string $label = null)
     * @method Show\Field|Collection phone_verified(string $label = null)
     * @method Show\Field|Collection points(string $label = null)
     * @method Show\Field|Collection realname(string $label = null)
     * @method Show\Field|Collection register_ip(string $label = null)
     * @method Show\Field|Collection signature(string $label = null)
     * @method Show\Field|Collection temp_parent_id(string $label = null)
     * @method Show\Field|Collection vip_expire(string $label = null)
     * @method Show\Field|Collection vip_id(string $label = null)
     * @method Show\Field|Collection message_id(string $label = null)
     * @method Show\Field|Collection uid(string $label = null)
     * @method Show\Field|Collection token(string $label = null)
     * @method Show\Field|Collection abilities(string $label = null)
     * @method Show\Field|Collection expires_at(string $label = null)
     * @method Show\Field|Collection last_used_at(string $label = null)
     * @method Show\Field|Collection tokenable_id(string $label = null)
     * @method Show\Field|Collection tokenable_type(string $label = null)
     * @method Show\Field|Collection datasheet_link(string $label = null)
     * @method Show\Field|Collection factory_lead_time(string $label = null)
     * @method Show\Field|Collection first_category_id(string $label = null)
     * @method Show\Field|Collection is_last_stock(string $label = null)
     * @method Show\Field|Collection manufacture_id(string $label = null)
     * @method Show\Field|Collection manufacturer_no(string $label = null)
     * @method Show\Field|Collection package(string $label = null)
     * @method Show\Field|Collection product_link(string $label = null)
     * @method Show\Field|Collection product_name(string $label = null)
     * @method Show\Field|Collection product_slug(string $label = null)
     * @method Show\Field|Collection published(string $label = null)
     * @method Show\Field|Collection date_code(string $label = null)
     * @method Show\Field|Collection info_id(string $label = null)
     * @method Show\Field|Collection mnf(string $label = null)
     * @method Show\Field|Collection part_no(string $label = null)
     * @method Show\Field|Collection qty(string $label = null)
     * @method Show\Field|Collection company_name(string $label = null)
     * @method Show\Field|Collection phone_no(string $label = null)
     * @method Show\Field|Collection admin_id(string $label = null)
     * @method Show\Field|Collection app_name(string $label = null)
     * @method Show\Field|Collection attr_name(string $label = null)
     * @method Show\Field|Collection attr_type(string $label = null)
     * @method Show\Field|Collection attr_value(string $label = null)
     * @method Show\Field|Collection created_name(string $label = null)
     * @method Show\Field|Collection end_time(string $label = null)
     * @method Show\Field|Collection request_type(string $label = null)
     * @method Show\Field|Collection response(string $label = null)
     * @method Show\Field|Collection response_type(string $label = null)
     * @method Show\Field|Collection start_time(string $label = null)
     * @method Show\Field|Collection task_status(string $label = null)
     * @method Show\Field|Collection task_type(string $label = null)
     */
    class Show {}

    class Form {}

}

namespace Dcat\Admin\Grid {
    class Column {}

    class Filter {}
}

namespace Dcat\Admin\Show {
    class Field {}
}
