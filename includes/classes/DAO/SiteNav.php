<?php namespace DAO {

# SiteNav class
class SiteNav extends DAOBase {

	private static $SCHEMA = 'RPH';
	private static $TABLE = 'site_nav';
	private static $COLUMNS = array('id', 'display', 'path', 'order', 'description');

	public $display;
	public $path;

	public function __construct() {
		return parent::__construct(self::$SCHEMA, self::$TABLE);
	}

	public function getColumns() {
		return self::$COLUMNS;
	}

	# Static Functions
	public static function load_all() {
		$class = new SiteNav();
		$conditions = array();
		return $class->select_object($conditions);
	}
}

} ?>
