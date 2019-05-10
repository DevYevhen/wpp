<?php
class CRUD_Menu {
 
    private $crud_page;
 
    public function __construct( $crud_page ) {
        $this->crud_page = $crud_page;
    }
 
    public function init() {
         add_action( 'admin_menu', array( $this, 'add_options_page' ) );
	}


 
    public function add_options_page() {
 
		add_submenu_page(
			'edit.php',
            'Quotes REST CRUD opts',
            'Quotes Admin',
            'manage_options',
            'quotes_rest_manage',
            array( $this->crud_page, 'render' )
        );
    }
}
