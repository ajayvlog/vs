<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();


$package_expiry_date = array(
    'package_expiry_date' => array(
        'type' => 'varchar',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci',
        'constraint' => '100'
    )
);
$CI->dbforge->add_column('listing', $package_expiry_date);


$hero_settings['type'] = 'hero_settings';
$hero_settings['description'] = '{"title":"How it works","sub_title":"Atlas is a professional business directory listing website. Atlas works with the combination of admin and customers. Atlas is a kind of web page that lists businesses and directories that exist on a website.","topic_one":"Search locations","icon_picker_one":"fas fa-search","sub_topic_one":"Businesses and directories can be searched by location from the search bar and listings page.","topic_two":"Search by category","icon_picker_two":"fas fa-info-circle","sub_topic_two":"Businesses and directories listings can be filtered or searched category-wise.","topic_three":"Book, Reach or Call","icon_picker_three":"far fa-thumbs-up","sub_topic_three":"Customers can book, reach or call the businesses directories and payment can be received online\/offline."}';
$CI->db->insert('frontend_settings', $hero_settings);





$listings = $this->db->get_where('listing')->result_array();
foreach($listings as $listing){
    if($listing['user_id'] == 1){
        $this->db->where('id', $listing['id']);
        $this->db->update('listing', array('package_expiry_date' => 'admin'));
    }else{
        $CI->db->select('expired_date');
        $CI->db->select_max('id');
        $CI->db->where('user_id', $listing['user_id']);
        $result = $CI->db->get('package_purchased_history');

        if($result->num_rows() > 0){
            $this->db->where('id', $listing['id']);
            $this->db->update('listing', array('package_expiry_date' => $result->row('expired_date')));
        }
    }
}



//update data in settings table
$settings_datas['description'] = '2.10';
$CI->db->where('type', 'version');
$CI->db->update('settings', $settings_datas);
?>
