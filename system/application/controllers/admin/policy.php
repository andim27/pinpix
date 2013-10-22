<?php
class Policy extends Controller {
function testacl()
    {
    	
    	$this->load->library('khacl');
    	
    	
		$this->khacl->aco->create('website');
        $this->khacl->aco->create('adminarea', 'website');
        
        $this->khacl->aro->create('users');
        $this->khacl->aro->create('reg_users', 'users');
        $this->khacl->aro->create('moderators', 'reg_users');
        $this->khacl->aro->create('hired_moderators', 'moderators');
        $this->khacl->aro->create('admins', 'reg_users');
        
        $this->khacl->axo->create('view');
		$this->khacl->axo->create('add');
        $this->khacl->axo->create('edit');
        $this->khacl->axo->create('delete');
        
        $this->khacl->deny('users','website');
        $this->khacl->allow('admins','adminarea','view');
        $this->khacl->allow('moderators','adminarea','view');
    	$this->khacl->allow('hired_moderators','adminarea','view');
    	
    	
    	/*
    	 * //////////////////////////////////////////////
    	// This is what we want to restrict access to
        $this->khacl->aco->create('Website');
        $this->khacl->aco->create('Mainpage', 'Website');
        $this->khacl->aco->create('Adminpage', 'Website');
        $this->khacl->aco->create('Album', 'Mainpage');
        $this->khacl->aco->create('Photo', 'Album');
        $this->khacl->aco->create('AlbumComment', 'Album');
        $this->khacl->aco->create('PhotoComment', 'Photo');

        $this->khacl->aco->create('UserProfile', 'Mainpage');


        // create tree root group and nested sub-groups
        $this->khacl->aro->create('Users');
        // admin and guest are both users so inherit user permissions
        //$this->khacl->aro->create('Guest', 'Users');
        $this->khacl->aro->create('Registered', 'Users');
        $this->khacl->aro->create('Owner', 'Registered');
        $this->khacl->aro->create('Admin', 'Users');
        $this->khacl->aro->create('Moderator', 'Users');
        $this->khacl->aro->create('HiredModerator', 'Moderator');


        // These are the actions axos
        // View is generic action we will use
        // several times applied to different acos
        $this->khacl->axo->create('View');

        // these will be restricted to admins
        $this->khacl->axo->create('Add');
        $this->khacl->axo->create('Edit');
        $this->khacl->axo->create('Delete');

        // OK lets first deny all users everything
        // (company is the root group)
        $this->khacl->deny('Users','Website');

        // allow anyone in company to view
        // anything in entire website
        $this->khacl->allow('Users','Mainpage','View');

        //��������� ����� �������, ����, �����������
        $this->khacl->allow('Registered','Album','Add');
        $this->khacl->allow('Registered','PhotoComment','Add');
        $this->khacl->deny('Registered','Photo','Add');

        $this->khacl->allow('Owner','Album','Edit');
        $this->khacl->allow('Owner','Album','Delete');
        $this->khacl->allow('Owner','Photo','Add');

        $this->khacl->allow('Moderator','Photo','Delete');
        $this->khacl->allow('Moderator','AlbumComment','Delete');
        $this->khacl->allow('Moderator','PhotoComment','Edit');
        $this->khacl->allow('Moderator','AlbumComment','Edit');

        $this->khacl->allow('HiredModerator','UserProfile','Delete');


        // allow admin group to administrate any part of
        // the website including the email and database
        $this->khacl->allow('Admin','Adminpage','View');
        $this->khacl->allow('Admin','Website','Add');
        $this->khacl->allow('Admin','Website','Edit');
        $this->khacl->allow('Admin','Website','Delete');
*/
/*
        // create some users belonging to the nested groups
        // first the overall group
        $this->khacl->aro->create('Bob','Company');
        // now the next level down
        $this->khacl->aro->create('David','User');
        // finally the lowest levels where
        // the axos get detailed
        $this->khacl->aro->create('Jim', 'Admin');
        $this->khacl->aro->create('Bernice', 'Guest');



        // now test the settings
        echo 'these should be true<hr>';
        // any admin should be able to edit email
        $this->_can('Admin', 'Website', 'Edit', TRUE);
        // specific admin should be able to view any part of website
        $this->_can('Jim', 'Website', 'View', TRUE);
        // specific admin should be able to delete any part of website
        $this->_can('Jim', 'Website', 'Delete', TRUE);
        // specific admin should be able to delete any part of database
        $this->_can('Jim', 'Database', 'Delete', TRUE);
        // specific user should be able to view any part of the website
        $this->_can('Bob', 'Website', 'View', TRUE);
        // specific user should be able to view any part of the database
        $this->_can('Bob', 'Database', 'View', TRUE);
        // specific user should be able to view any part of the database
        $this->_can('David', 'Database', 'View', TRUE);

        echo '<br/>these should be false<hr/>';
        // specific user should not be able to edit any part of the website
        $this->_can('Bob', 'Website', 'Edit', FALSE);
        // specific user should not be able to edit any part of the website
        $this->_can('David', 'Website', 'Edit', FALSE);
        // specific user should not be able to edit any part of the database
        $this->_can('Bob', 'Database', 'Edit', FALSE);
        // guest should not be allowed to view any email
        $this->_can('Guest', 'Email', 'View', FALSE);
        // guest should not be allowed to view any database
        $this->_can('Guest', 'Database', 'View', FALSE);
        // specific guest user should not be allowed to view any email
        $this->_can('Bernice', 'Email', 'View', FALSE);
        // specific user should not be able to edit any part of the Email
        $this->_can('Bob', 'Email', 'Edit', FALSE);
        // guest should not be able to edit any part of the Email
        $this->_can('Guest', 'Email', 'Edit', FALSE);
        // specific guest user should not be allowed to edit any email
        $this->_can('Bernice', 'Email', 'Edit', FALSE);

        // the khhaos_acl system returns FALSE if indeterminate
        echo '<br/>these should confuse the system and give indeterminate answers<hr/>';
        // only admin should be able to edit email,
        // system should not allow generic user, but answer is ambiguous
        $this->_can('User', 'Email', 'Edit', 'UNKNOWN');
        // generic user should not be able to edit any part of
        // the website, only admin in the next level down can
        $this->_can('User', 'Website', 'Edit', 'UNKNOWN');
*/
    }

    // utility to wrap the kh_acl_check() method
    // and allow printing of results to browser
    function _can($aro, $aco, $axo, $expected)
    {
        $can = kh_acl_check($aro, $aco, $axo);
        print_r(var_export($can, TRUE) . '='. var_export($expected, TRUE) .'<br/>');
        return $can;
    }

}
?>
