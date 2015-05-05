<?php namespace DAO {

# SiteConfig class
class SiteConfig extends DAOBase {

	private static $SCHEMA = 'RPH';
	private static $TABLE = 'site_config';
	private static $COLUMNS = array('id', 'keyname', 'value', 'created', 'modified');

	public $keyname;
	public $value;

	public function __construct() {
		return parent::__construct(self::$SCHEMA, self::$TABLE);
	}

	public function getColumns() {
		return self::$COLUMNS;
	}

	# Static Functions
	public static function FindByKeyname($keyname) {
		$conditions = array(
			'keyname' => $keyname,
		);

		# Parent is an abstract class, so gotta invoke myself
		$class = new SiteConfig();
		$objects = $class->select_object($conditions);

		return (is_array($objects) && count($objects) > 0)
			? $objects[0]
			: false;
	}

	public static function load_all() {
		$class = new SiteNav();
		$conditions = array();
		return $class->select_object($conditions);
	}
}

} ?>
