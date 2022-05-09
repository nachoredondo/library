<?php
require_once("../../classes/controller.php");
require_once("../../classes/ssp.php");
require '../../classes/session.php';
require '../../classes/user.php';
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

class SSPAllAuthors extends SSP {
	private const FROM = "`author` as author";

	public static function exec ($request, $conn) {
		// Datatbles columns - ADD THEM HERE
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$DT_COLUMNS = [
			[ 'db' => 'id', 'dt' => 'id' ],
			[ 'db' => 'name', 'dt' => 'name' ],
			[ 'db' => 'pseudonym', 'dt' => 'pseudonym' ],
			[ 'db' => 'birthdate', 'dt' => 'birthdate' ],
			[ 'db' => 'death_date', 'dt' => 'death_date' ],
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

		$from = self::FROM;

		// Main query to actually get the data
		$sql = "SELECT *
			 FROM $from
			 $where
			 $order
			 $limit";

		$data = self::sql_exec($db, $bindings, $sql);

		// Total data set length
		$resTotalLength = self::sql_exec($db, $bindings_join,
			"SELECT COUNT(`author`.`id`)
			 FROM $from"
		);
		$recordsTotal = $resTotalLength[0][0];
		// Data set length after filtering
		$recordsFiltered = $recordsTotal;
		if (!empty($where)) {
			$resFilterLength = self::sql_exec($db, $bindings_join,
				"SELECT COUNT(`author`.`id`)
				 FROM $from"
			);
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
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

echo json_encode(
	SSPAllAuthors::exec($_POST, get_db_connection())
);
