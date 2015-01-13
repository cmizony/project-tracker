<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration for release 0.1
 *
 * Initial schema creation
 */
class Migration_release_0_1 extends CI_Migration {

	public function up() 
	{

		## Create Table activities
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`type` varchar(45) NULL ");
		$this->dbforge->add_field("`action` varchar(45) NULL ");
		$this->dbforge->add_field("`title` varchar(200) NULL ");
		$this->dbforge->add_field("`ip_address` varchar(16) NULL ");
		$this->dbforge->add_field("`date` datetime NULL ");
		$this->dbforge->add_field("`user_agent` varchar(200) NULL ");
		$this->dbforge->add_field("`url` varchar(200) NULL ");
		$this->dbforge->add_field("`client_id` int(11) NULL ");
		$this->dbforge->add_field("`id_link` int(11) NULL ");
		$this->dbforge->add_field("`uri_link` varchar(45) NULL ");
		$this->dbforge->create_table("activities", TRUE);
		$this->db->query('ALTER TABLE  `activities` ENGINE = MyISAM');
		## Create Table cards
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`title` varchar(100) NULL ");
		$this->dbforge->add_field("`description` text NULL ");
		$this->dbforge->add_field("`outline` text NULL ");
		$this->dbforge->add_field("`date` datetime NULL ");
		$this->dbforge->add_field("`project_id` int(11) NOT NULL ");
		$this->dbforge->add_field("`thumbnail` varchar(45) NULL ");
		$this->dbforge->add_field("`chat_id` int(11) NULL ");
		$this->dbforge->create_table("cards", TRUE);
		$this->db->query('ALTER TABLE  `cards` ENGINE = MyISAM');
		## Create Table chats
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`flag_lock` int(11) NULL ");
		$this->dbforge->create_table("chats", TRUE);
		$this->db->query('ALTER TABLE  `chats` ENGINE = MyISAM');
		## Create Table ci_sessions
		$this->dbforge->add_field("`session_id` varchar(40) NOT NULL ");
		$this->dbforge->add_key("session_id",true);
		$this->dbforge->add_field("`ip_address` varchar(16) NULL ");
		$this->dbforge->add_field("`user_agent` varchar(120) NULL ");
		$this->dbforge->add_field("`last_activity` int(10) NULL ");
		$this->dbforge->add_field("`user_data` text NULL ");
		$this->dbforge->create_table("ci_sessions", TRUE);
		$this->db->query('ALTER TABLE  `ci_sessions` ENGINE = MyISAM');
		## Create Table clients
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`login` varchar(100) NULL ");
		$this->dbforge->add_field("`password` varchar(1024) NULL ");
		$this->dbforge->add_field("`date` datetime NULL ");
		$this->dbforge->add_field("`email` varchar(100) NULL ");
		$this->dbforge->add_field("`name` varchar(100) NULL ");
		$this->dbforge->add_field("`phone` varchar(45) NULL ");
		$this->dbforge->add_field("`company` varchar(100) NULL ");
		$this->dbforge->add_field("`note` text NULL ");
		$this->dbforge->add_field("`address` text NULL ");
		$this->dbforge->add_field("`latitude` float NULL ");
		$this->dbforge->add_field("`longitude` float NULL ");
		$this->dbforge->add_field("`flag_lock` int(11) NULL ");
		$this->dbforge->add_field("`folder_id` int(11) NOT NULL ");
		$this->dbforge->create_table("clients", TRUE);
		$this->db->query('ALTER TABLE  `clients` ENGINE = MyISAM');
		## Create Table files
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`title` varchar(100) NULL ");
		$this->dbforge->add_field("`date` datetime NULL ");
		$this->dbforge->add_field("`hash` varchar(32) NULL ");
		$this->dbforge->add_field("`type` varchar(45) NULL ");
		$this->dbforge->add_field("`note` text NULL ");
		$this->dbforge->add_field("`name` varchar(45) NULL ");
		$this->dbforge->add_field("`size` int(11) NULL ");
		$this->dbforge->add_field("`folder_id` int(11) NOT NULL ");
		$this->dbforge->add_field("`downloads` int(11) NULL ");
		$this->dbforge->create_table("files", TRUE);
		$this->db->query('ALTER TABLE  `files` ENGINE = MyISAM');
		## Create Table folders
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`flag_lock` int(11) NULL ");
		$this->dbforge->create_table("folders", TRUE);
		$this->db->query('ALTER TABLE  `folders` ENGINE = MyISAM');
		## Create Table messages
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`content` text NULL ");
		$this->dbforge->add_field("`date` datetime NULL ");
		$this->dbforge->add_field("`chat_id` int(11) NOT NULL ");
		$this->dbforge->add_field("`client_id` int(11) NULL ");
		$this->dbforge->create_table("messages", TRUE);
		$this->db->query('ALTER TABLE  `messages` ENGINE = MyISAM');
		## Create Table projects
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`name` varchar(100) NULL ");
		$this->dbforge->add_field("`description` text NULL ");
		$this->dbforge->add_field("`date` datetime NULL ");
		$this->dbforge->add_field("`type` varchar(100) NULL ");
		$this->dbforge->add_field("`price` varchar(45) NULL ");
		$this->dbforge->add_field("`status` varchar(45) NULL ");
		$this->dbforge->add_field("`client_id` int(11) NULL ");
		$this->dbforge->add_field("`label` varchar(45) NULL ");
		$this->dbforge->add_field("`chat_id` int(11) NOT NULL ");
		$this->dbforge->add_field("`note` text NULL ");
		$this->dbforge->add_field("`folder_id` int(11) NOT NULL ");
		$this->dbforge->create_table("projects", TRUE);
		$this->db->query('ALTER TABLE  `projects` ENGINE = MyISAM');
		## Create Table subtasks
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`title` varchar(100) NULL ");
		$this->dbforge->add_field("`date` datetime NULL ");
		$this->dbforge->add_field("`deadline` datetime NULL ");
		$this->dbforge->add_field("`responsible` varchar(45) NULL ");
		$this->dbforge->add_field("`status` varchar(45) NULL ");
		$this->dbforge->add_field("`note` text NULL ");
		$this->dbforge->add_field("`task_id` int(11) NOT NULL ");
		$this->dbforge->add_field("`tag_id` int(11) NOT NULL ");
		$this->dbforge->create_table("subtasks", TRUE);
		$this->db->query('ALTER TABLE  `subtasks` ENGINE = MyISAM');
		## Create Table tags
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`color` varchar(45) NULL ");
		$this->dbforge->add_field("`text` varchar(100) NULL ");
		$this->dbforge->add_field("`date` datetime NULL ");
		$this->dbforge->create_table("tags", TRUE);
		$this->db->query('ALTER TABLE  `tags` ENGINE = MyISAM');
		## Create Table tasks
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`title` varchar(100) NULL ");
		$this->dbforge->add_field("`description` text NULL ");
		$this->dbforge->add_field("`date` datetime NULL ");
		$this->dbforge->add_field("`status` varchar(45) NULL ");
		$this->dbforge->add_field("`time` int(11) NULL ");
		$this->dbforge->add_field("`project_id` int(11) NOT NULL ");
		$this->dbforge->add_field("`label` varchar(45) NULL ");
		$this->dbforge->add_field("`folder_id` int(11) NOT NULL ");
		$this->dbforge->create_table("tasks", TRUE);
		$this->db->query('ALTER TABLE  `tasks` ENGINE = MyISAM');
		## Create Table tickets
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`title` varchar(100) NULL ");
		$this->dbforge->add_field("`description` text NULL ");
		$this->dbforge->add_field("`status` varchar(45) NULL ");
		$this->dbforge->add_field("`date` datetime NULL ");
		$this->dbforge->add_field("`type` varchar(45) NULL ");
		$this->dbforge->add_field("`flag_lock` tinyint(1) NULL ");
		$this->dbforge->add_field("`note` text NULL ");
		$this->dbforge->add_field("`project_id` int(11) NOT NULL ");
		$this->dbforge->add_field("`number` int(11) NULL ");
		$this->dbforge->add_field("`priority` varchar(45) NULL ");
		$this->dbforge->add_field("`chat_id` int(11) NOT NULL ");
		$this->dbforge->add_field("`folder_id` int(11) NOT NULL ");
		$this->dbforge->add_field("`tag_id` int(11) NOT NULL ");
		$this->dbforge->create_table("tickets", TRUE);
		$this->db->query('ALTER TABLE  `tickets` ENGINE = MyISAM');
	}

	public function down()	
	{
		### Drop table activities ##
		$this->dbforge->drop_table("activities", TRUE);
		### Drop table cards ##
		$this->dbforge->drop_table("cards", TRUE);
		### Drop table chats ##
		$this->dbforge->drop_table("chats", TRUE);
		### Drop table ci_sessions ##
		$this->dbforge->drop_table("ci_sessions", TRUE);
		### Drop table clients ##
		$this->dbforge->drop_table("clients", TRUE);
		### Drop table files ##
		$this->dbforge->drop_table("files", TRUE);
		### Drop table folders ##
		$this->dbforge->drop_table("folders", TRUE);
		### Drop table messages ##
		$this->dbforge->drop_table("messages", TRUE);
		### Drop table projects ##
		$this->dbforge->drop_table("projects", TRUE);
		### Drop table subtasks ##
		$this->dbforge->drop_table("subtasks", TRUE);
		### Drop table tags ##
		$this->dbforge->drop_table("tags", TRUE);
		### Drop table tasks ##
		$this->dbforge->drop_table("tasks", TRUE);
		### Drop table tickets ##
		$this->dbforge->drop_table("tickets", TRUE);

	}
}
