<?php
require_once("../../classes/controller.php");
require_once("../../classes/ssp.php");
require '../../classes/session.php';
Session::check_login_redirect();

// Session::check_login_error();

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

class SSPCategory extends SSP {
	private const FROM = "`category` as c ";
		private const FROM_LEFT_JOIN = "
					LEFT JOIN `book_categories` as bc ON c.id = bc.id_category  
					LEFT JOIN `book` as b ON bc.id_book = b.id";

	public static function exec ($request, $conn) {
		// Datatbles columns - ADD THEM HERE
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$DT_COLUMNS = [
			[ 'db' => 'id', 'dt' => 'id' ],
			[ 'db' => 'name', 'dt' => 'name' ],
			[ 'db' => 'description', 'dt' => 'description' ],
		];
		return self::do_search($request, $conn, $DT_COLUMNS);
	}

	private static function do_search($request, $conn, $columns) {
		$bindings = [];
		$bindings_join = [];
		$db = self::db($conn);

		$request['search']['value'] = $request['search']['value'];

		// Build the SQL query string from the request
		$limit = self::limit($request, $columns);
		$order = self::order($request, $columns);
		$where = self::filter($request, $columns, $bindings);
		$where_join = "";
		$sql_data = "";
		$sql_info = "";

		$from = self::FROM;

		if (isset($request['assigned'])) {
			$from .= self::FROM_LEFT_JOIN;
			$where_join = self::where_assigned($request, $bindings_join);
			if ($request['assigned'] == "true") {
				$where = self::where_add($where, self::where_assigned($request, $bindings));
				$sql_data = "SELECT c.*
					FROM $from
					$where
					$order
					$limit";
				$sql_info = "SELECT COUNT(c.`id`)
					FROM $from
					WHERE $where_join";
			} else {
				$where = self::where_add($where, " `c`.`id` ");
				$where_assigned = self::where_assigned($request, $bindings);
				$sql_data = "SELECT `c`.*
					FROM `category` as c
					$where NOT IN (SELECT c.`id`
						FROM $from
						WHERE $where_assigned)
					$order
					$limit";
				$sql_info = "SELECT COUNT(c.`id`)
					FROM `category` as c
					WHERE `c`.`id` NOT IN (SELECT c.`id`
						FROM $from
						WHERE $where_join)";
			}
		}

		$data = self::sql_exec($db, $bindings, $sql_data);

		// Total data set length
		$resTotalLength = self::sql_exec($db, $bindings_join, $sql_info);
		$recordsTotal = $resTotalLength[0][0];
		// Data set length after filtering
		$recordsFiltered = $recordsTotal;
		if (!empty($where)) {
			$resFilterLength = self::sql_exec($db, $bindings_join, $sql_info);
			$recordsFiltered = $resFilterLength[0][0];
		}
		/*
		 * Output
		 */
		return [
			"draw" => isset($request['draw']) ? intval($request['draw']) : 0,
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsFiltered),
			"data" => self::data_output($columns, $data),
		];
	}

	static function where_add(?string $where = '', string $cond, string $glue = ' AND ') : string {
		if (empty($cond))
			return $where;
		return $where ? $where . $glue . $cond : 'WHERE ' . $cond;
	}

	private static function where_assigned($request, &$bindings) {
		$id_book = self::bind($bindings, $request['id_book'], PDO::PARAM_STR);
		return "b.`id` = $id_book";
	}
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

echo json_encode(
	SSPCategory::exec($_POST, get_db_connection())
);
